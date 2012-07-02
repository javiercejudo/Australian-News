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
	static function get_latest_news($pdo, $num=100, $filter=null){
		$sql = ' SELECT * FROM `news` ';
		if ($filter !== null) {
			$sql .= ' WHERE MATCH (`title`,`description`) AGAINST (\'' . $filter . '\') ';
		} else {
			$sql .= ' ORDER BY pub_date DESC LIMIT ' . $num;
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_CLASS, 'News');
		return $result;
	}
}
