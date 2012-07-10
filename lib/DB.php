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
	static function get_latest_news($pdo, $num=100, $q=null, &$qq, &$query, &$top_suggestion){
		if (!isset($_GET['q']) || empty($_GET['q'])) { $q = null; }
		//else { $q = substr($pdo->quote($q), 1, -1); }
		$sql = ' SELECT * FROM `news` ';
		if ($q !== null) {
			$sql .= ' WHERE MATCH (`title`,`description`) AGAINST (?) ';
			//$sql .= ' WHERE MATCH (`title`,`description`) AGAINST (? IN BOOLEAN MODE) ';
			//$sql .= ' WHERE MATCH (`title`,`description`) AGAINST (? WITH QUERY EXPANSION) ';
		} else {
			$sql .= ' ORDER BY `pub_date` DESC ';
		}
		$sql .= ' LIMIT ' . $num;
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(addslashes($q)));
		$result = $stmt->fetchAll(PDO::FETCH_CLASS, 'News');
		if (count($result) < 1 && true) {
			$sql = ' SELECT * FROM `news` ';
			$sql .= ' WHERE ';
			$params_aux = array();
			foreach (explode(" ", $q) as $piece) {
				if (trim($piece) !== '') {
					$sql .= '`title` LIKE ? OR `description` LIKE ? OR ';
					$params_aux[] = '%' . addslashes($piece) . '%';
					$params_aux[] = '%' . addslashes($piece) . '%';
				}				
			}
			$sql .= ' 1=2 ORDER BY `pub_date` DESC ';
			$sql .= ' LIMIT ' . $num;
			$stmt = $pdo->prepare($sql);
			$stmt->execute($params_aux);
			$result = $stmt->fetchAll(PDO::FETCH_CLASS, 'News');
		}
		$suggestions = utf8_encode(file_get_contents('http://suggestqueries.google.com/complete/search?hl=en&cr=countryAU&client=firefox&q=' . str_replace(' ','+',addslashes($q))));
		$sug_aux1 = preg_replace('/[\[\]\"]/','',$suggestions);
		$sug_aux2 = array_filter(explode(',',$sug_aux1));
		if (count($sug_aux2)>1) $top_suggestion = $sug_aux2[1];
		$qq = trim($q);
		$query = $sql;
		return $result;
	}
}
