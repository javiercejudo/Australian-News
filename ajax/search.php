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
if (isset($_GET['q']) && !empty($_GET['q'])) {
	$q = $_GET['q'];
}

$news = DB::get_latest_news($pdo, 100, $q);
$total_news = count($news);
if ($total_news > 0) {
	echo '<div class="item no-news total-results"><a name="total-results"> Showing ' . $total_news . ' items.</a></div>';
	foreach ($news as $item) {
		echo '<div class="item"><a href="' . $item->link . '">' . "\n";
		echo '<p class="pubDate">' . date('H:i | d/m/Y', strtotime($item->pub_date)) . '</p>' . "\n";
		echo '<h1 title="' . $item->title . '">' . $item->title . '</h1>' . "\n";
		echo '<p class="description">' . $item->description . '</p>' . "\n";
		echo '</a></div>' . "\n";
	}
}
else {
	echo '<div class="item"><a name="no-results">No relevant results were found.</a></div>';
}
