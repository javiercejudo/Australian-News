<?php

require_once '../config.php';
require_once DIRLIB . 'DB.php';
require_once DIRLIB . 'News.php';

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
$load_raw = true;
include DIRINC . 'dynamic_content.php';
