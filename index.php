<?php
header('Content-type: text/html; charset=utf-8');
require 'config.php';
$local_url = 'data/smh.xml';
if (is_file($local_url) && date('U')-filemtime($local_url) < 60*5+10) {
	$xml_string = file_get_contents($local_url);
} else {
	$xml_string = file_get_contents(SOURCE);
	file_put_contents($local_url, $xml_string);
}
$aux = simplexml_load_string($xml_string);
$news = $aux->channel->item;
?>
<!DOCTYPE html>
<meta charset="UTF-8" />
<title>Australian News | Javier Cejudo</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<body>
<div class="outer-container">
<?php
foreach ($news as $item) {
	$pubdate = strtotime($item->pubDate);
	echo '<div class="item"><a href="' . $item->link . '">' . "\n";
	echo '<h1>' . $item->title . '</h1>' . "\n";
	echo '<p class="pubDate">' . date('H:i | d/m/Y', $pubdate) . '</p>' . "\n";
	echo '<p class="description">' . $item->description . '</p>' . "\n";
	echo '</a></div>' . "\n";
}
?>
</div>
</body>
