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
$q = '';
if (isset($_GET['q']) && !empty($_GET['q'])) {
	$q = trim($_GET['q']);
}
?>
<!DOCTYPE html>
<meta charset="UTF-8" />
<title>Australian News | Javier Cejudo</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<link rel="stylesheet" type="text/css" href="vendor/css/stroll.css">
<body>
<div class="outer-container">
<form name="search_form" action="" method="GET">
<input type="search" autocomplete="off" autofocus="autofocus" placeholder="Search SMH national news..." id="q" name="q" value="<?= $q ?>" />
<!--<input type="submit" value="Search" />-->
</form>
<div id="news-container">
<?php
$stmt = DB::prepare_insert($pdo);
foreach ($rss_news as $item) {
	$params = array (
		$item->title, $item->description,
		$item->pubDate,	$item->link, $item->guid
	);
	DB::execute_update($stmt, $params);
}
include DIRINC . 'dynamic_content.php';
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
<script src="vendor/js/mootools-core.js" type="text/javascript"></script>
<script src="vendor/js/stroll.js" type="text/javascript"></script>
<script src="js/search.js" type="text/javascript"></script>
<script>
	//stroll.bind(document.getElementById('fancy-list'));
</script>
</body>
