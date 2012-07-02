<?php
header('Content-type: text/html; charset=utf-8');
require_once 'config.php';
require_once DIRLIB . 'DB.php';
$pdo = new PDO( 
    'mysql:host=' . HOST . ';dbname=' . DBNAME, 
    USERNAME, 
    PASSWD,
    array(PDO::ATTR_PERSISTENT => false)
);
$local_url = 'data/smh.xml';
if (is_file($local_url) && date('U')-filemtime($local_url) < 60*5+10) {
	$xml_string = file_get_contents($local_url);
} else {
	$xml_string = file_get_contents(SOURCE);
	file_put_contents($local_url, $xml_string);
}
$aux = simplexml_load_string($xml_string);
$rss_news = $aux->channel->item;
if (!isset($_GET['q']) || empty($_GET['q'])) {
	$q = null;
} else {
	$q = $_GET['q'];
}
?>
<!DOCTYPE html>
<meta charset="UTF-8" />
<title>Australian News | Javier Cejudo</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<body>
<div class="outer-container">
<form name="search_form" action="" method="GET">
<input type="search" placeholder="Search news..." id="q" name="q" value="<?= $q ?>" /><input type="submit" value="Search" />
</form>
<div class="news-container">
<?php
$stmt = DB::prepare_insert($pdo);
foreach ($rss_news as $item) {
	$params = array (
		$item->title, $item->description,
		$item->pubDate,	$item->link, $item->guid
	);
	DB::execute_update($stmt, $params);
}
$news = DB::get_latest_news($pdo, 100, $q); // array of News
$total_news = count($news);
if ($total_news > 0) {
	echo '<div class="item"><a name="total-results"> Showing ' . $total_news . ' items.</a></div>';
	foreach ($news as $item) {
		echo '<div class="item"><a href="' . $item->link . '">' . "\n";
		echo '<p class="pubDate">' . date('H:i | d/m/Y', strtotime($item->pub_date)) . '</p>' . "\n";
		echo '<h1 title="' . $item->title . '">' . $item->title . '</h1>' . "\n";
		echo '<p class="description">' . $item->description . '</p>' . "\n";
		echo '</a></div>' . "\n";
	}
} else {
	echo '<div class="item"><a name="no-results">No relevant results were found.</a></div>';
}
?>
</div>
</div>
<footer>
<p class="technical footer">Valid <a href="http://validator.w3.org/check?uri=referer">HTML5</a>. 
© <?php echo date('Y'); ?> Javier Cejudo</p>
<a href="https://github.com/javiercejudo/Australian-News">
<img class="git_ribbon" src="assets/fork_red.png" alt="Fork me on GitHub">
</a>
</footer>
</body>
