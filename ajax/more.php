<?php
require_once '../config.php';
require_once DIRLIB . 'DB.php';
require_once DIRLIB . 'News.php';

$pdo = DB::connect();

$q = '';
$num = DURATION;
$skip = 0;
if (isset($_GET['q']) && !empty($_GET['q'])) {
	$q = $_GET['q'];
}
if (isset($_GET['num']) && !empty($_GET['num'])) {
	$num = DURATION;
	$skip = $_GET['num'];
}

include DIRINC . 'dynamic_content.php';
