<?php
require_once 'News.php';
class DB {
	static function prepare_insert($pdo) {
		$sql = 'INSERT INTO `news`
			(`title`, `description`, `pub_date`, `link`, `guid`, `created`) 
			VALUES 
			(:title,:description,:pub_date,:link,:guid,:created)';
		$stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $stmt;
	}
	static function execute_update($stmt,$ap){
		$params = array (
			':title' => $ap[0],
			':description' => $ap[1],
			':pub_date' => date('Y-m-d H:i:s', strtotime($ap[2])),
			':link' => $ap[3],
			':guid' => $ap[4],
			':created' => date('Y-m-d H:i:s')
		);
		$stmt->execute($params);
	}
	static function get_latest_news($pdo, $num=100, $skip=0,$q=null, &$qq, &$query, &$total_in_database, &$top_suggestion){
		if (!isset($_GET['q']) || empty($q)) { $q = null; }
		//else { $q = substr($pdo->quote($q), 1, -1); }
		$sqld = ' SELECT * FROM `news` ';
		$sqlt = ' SELECT count(*) as total_in_database FROM `news` ';
		if ($q !== null) {
			$fulltext = ' WHERE MATCH (`title`,`description`) AGAINST (?) ';
			//$fulltext .= ' WHERE MATCH (`title`,`description`) AGAINST (? IN BOOLEAN MODE) ';
			//$fulltext .= ' WHERE MATCH (`title`,`description`) AGAINST (? WITH QUERY EXPANSION) ';
			$sqld .= $fulltext;
			$sqlt .= $fulltext;
		} else {
			$sqld .= ' ORDER BY `pub_date` DESC, `id` ';
		}
		$sqld .= ' LIMIT ' . $skip . ', ' . $num;
		$stmtd = $pdo->prepare($sqld);
		$stmtt = $pdo->prepare($sqlt);
		$stmtd->execute(array(addslashes($q)));
		$stmtt->execute(array(addslashes($q)));
		$result = $stmtd->fetchAll(PDO::FETCH_CLASS, 'News');
		$aux = $stmtt->fetch();
		$total_in_database = $aux['total_in_database'];
		
		// fallback in the case that the fulltext search returns no results
		// this is usual within the first strokes given our instant approach
		if (count($result) < 1 && true) {
			$sqld = ' SELECT * FROM `news` ';
			$sqlt = ' SELECT count(*) as total_in_database FROM `news` ';
			$sqld .= ' WHERE ';
			$sqlt .= ' WHERE ';
			$params_aux = array();
			foreach (explode(" ", $q) as $piece) {
				if (trim($piece) !== '') {
					$sqld .= '`title` LIKE ? OR `description` LIKE ? OR ';
					$sqlt .= '`title` LIKE ? OR `description` LIKE ? OR ';
					$params_aux[] = '%' . addslashes($piece) . '%';
					$params_aux[] = '%' . addslashes($piece) . '%';
				}				
			}
			$sqld .= ' 1=2 ORDER BY `id` DESC ';
			$sqlt .= ' 1=2 ';
			$sqld .= ' LIMIT ' . $skip . ', ' . $num;
			$stmtd = $pdo->prepare($sqld);
			$stmtt = $pdo->prepare($sqlt);
			$stmtd->execute($params_aux);
			$stmtt->execute($params_aux);
			$result = $stmtd->fetchAll(PDO::FETCH_CLASS, 'News');
			$aux = $stmtt->fetch();
			$total_in_database = $aux['total_in_database'];
			// this block handles google suggestions
			if ($total_in_database == 0) {
				$suggestions = utf8_encode(file_get_contents('http://suggestqueries.google.com/complete/search?hl=en&cr=countryAU&client=firefox&q=' . str_replace(' ','+',addslashes($q))));
				$sug_aux1 = preg_replace('/[\[\]\"]/','',$suggestions);
				$sug_aux2 = array_filter(explode(',',$sug_aux1));
				if (count($sug_aux2)>1) $top_suggestion = $sug_aux2[1];
			}
		}		
		$qq = trim($q);
		$query = $sqld;
		return $result;
	}
}
