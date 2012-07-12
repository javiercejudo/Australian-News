<?php
require_once '../config.php';
require_once DIRLIB . 'DB.php';
require_once DIRLIB . 'News.php';

$pdo = new PDO( 
    'mysql:host=' . HOST . ';dbname=' . DBNAME, 
    USERNAME, 
    PASSWD,
    array(PDO::ATTR_PERSISTENT => false)
);

$q = '';
$num = DURATION;
$skip = 0;
if (isset($_GET['q']) && !empty($_GET['q'])) {
	$q = $_GET['q'];
}
if (isset($_GET['num']) && !empty($_GET['num'])) {
	$num = $_GET['num'] + DURATION;
}

include DIRINC . 'dynamic_content.php';
