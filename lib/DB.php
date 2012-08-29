<?php

require_once 'News.php';

class DB {
	static function connect() {
		try{
			$pdo = new PDO( 
				'mysql:host=' . HOST . ';dbname=' . DBNAME, 
				USERNAME, 
				PASSWD,
				array(PDO::ATTR_PERSISTENT => false)
			);
			return $pdo;
		} catch (PDOException $e) {
			echo 'The site is down due to an internal error but it should be back soon. We are sorry for the inconvenience.';
			die;
		}
	}
	
	static function do_insert ($xml) {
		$pdo = self::connect();
		if ($xml !== false) {
			$rss_news = $xml->channel->item;
			$stmt = self::prepare_insert($pdo);
			foreach ($rss_news as $item) {
				$item = new News($item, false);
				$params = array (
					$item->title(), $item->description(),
					$item->pub_date(), $item->link(), $item->guid()
				);
				self::execute_insert($stmt, $params);
			}
		}	
	}
	
	private function prepare_insert($pdo) {
		$sql = 'INSERT INTO `news`
			(`title`, `description`, `pub_date`, `link`, `guid`, `created`) 
			VALUES 
			(:title,:description,:pub_date,:link,:guid,:created)';
		$stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $stmt;
	}
	
	private function execute_insert($stmt,$ap){
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
		if (strlen($q) > 3) {
			$sqld = ' SELECT * FROM `news` ';
			$sqlt = ' SELECT count(*) as total_in_database FROM `news` ';
			$fulltext = ' WHERE MATCH (`title`,`description`) AGAINST (?) ';
			//$fulltext .= ' WHERE MATCH (`title`,`description`) AGAINST (? IN BOOLEAN MODE) ';
			//$fulltext .= ' WHERE MATCH (`title`,`description`) AGAINST (? WITH QUERY EXPANSION) ';
			$sqld .= $fulltext;
			$sqlt .= $fulltext;
			$sqld .= ' LIMIT ' . $skip . ', ' . $num;
			$stmtd = $pdo->prepare($sqld);
			$stmtt = $pdo->prepare($sqlt);
			$stmtd->execute(array(addslashes($q)));
			$stmtt->execute(array(addslashes($q)));
			$result = $stmtd->fetchAll(PDO::FETCH_CLASS);
			$aux = $stmtt->fetch();
			$total_in_database = $aux['total_in_database'];
			//echo "<p>$sqld</p>";
		}
		
		// fallback in the case that the fulltext search returns no results
		// this is usual within the first strokes given our instant approach
		if (!isset($result) || count($result) < 1 || strlen($q) < 4) {
			$sqld = ' SELECT * FROM `news` ';
			$sqlt = ' SELECT count(*) as total_in_database FROM `news` ';
			$params_aux = array();
			if ($q !== null) {
				$where_literal = ' WHERE ';
				$sqld .= $where_literal;
				$sqlt .= $where_literal;
				$params_aux = array();
				foreach (explode(" ", $q) as $piece) {
					if (trim($piece) !== '') {
						$likes = '`title` LIKE ? OR `description` LIKE ? OR ';
						$sqld .= $likes;
						$sqlt .= $likes;
						$likes_params = '%' . addslashes($piece) . '%';
						$params_aux[] = $likes_params;
						$params_aux[] = $likes_params;
					}
				}
				$false_literal = ' 1=2 ';
				$sqld .= $false_literal;
				$sqlt .= $false_literal;
			}
			$sqld .= ' ORDER BY `pub_date` DESC, `id` ';
			$sqld .= ' LIMIT ' . $skip . ', ' . $num;
			//echo "<p>$sqld</p>";
			$stmtd = $pdo->prepare($sqld);
			$stmtt = $pdo->prepare($sqlt);
			$stmtd->execute($params_aux);
			$stmtt->execute($params_aux);
			$result = $stmtd->fetchAll(PDO::FETCH_CLASS);
			$aux = $stmtt->fetch();
			$total_in_database = $aux['total_in_database'];
		}
		// this block handles google suggestions
		if ($total_in_database < 10000 && $skip < 1 && strlen($q) > 3) {
			$top_suggestion = self::get_top_suggestion($q);
		}
		$qq = trim($q);
		$query = $sqld;
		return $result;
	}
	
	private function get_top_suggestion ($q) {
		$suggestions = utf8_encode(
		    file_get_contents(
		        'http://suggestqueries.google.com/complete/search?hl=en&cr=countryAUS&client=firefox&q=' .
		        str_replace(' ','+',addslashes($q))
		    )
		);
		$sug_aux1 = preg_replace('/[\[\]\"]/','', $suggestions);
		$sug_aux2 = array_filter(explode(',', $sug_aux1));
		$top_suggestion = '';
		if (count($sug_aux2)>1) {
			$top_suggestion = $sug_aux2[1];
		}
		return $top_suggestion;
	}
}
