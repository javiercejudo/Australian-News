<?php
//usleep(250000);
require_once '../config.php';
require_once DIRLIB . 'DB.php';
require_once DIRLIB . 'News.php';

$q = '';
$num = DURATION;
$skip = 0;
if (isset($_GET['q']) && !empty($_GET['q'])) {
	$q = trim($_GET['q']);
}

include DIRINC . 'dynamic_content.php';
