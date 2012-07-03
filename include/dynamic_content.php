<?php
$qq = '';
$query = '';
$news = DB::get_latest_news($pdo, 100, $q, $qq, $query);
$total_news = count($news);
if ($total_news > 0) {
	echo '<div class="item no-news total-results"><a name="total-results"> Showing ' . $total_news . ' items';
	if (!empty($qq)) {
		echo ' for "' . $qq . '"';
	}
	if (!empty($query)) {
		//echo ' <br /><br /> Query :: ' . $query;
	}
	echo '</a></div>';
	echo '<ul class="wave" id="fancy-list">';
	foreach ($news as $item) {
		echo '<li class="item"><a href="' . $item->link . '">' . "\n";
		echo '<p class="pubDate">' . date('H:i | d/m/Y', strtotime($item->pub_date)) . '</p>' . "\n";
		echo '<h1 title="' . $item->title . '">' . $item->title . '</h1>' . "\n";
		echo '<p class="description">' . $item->description . '</p>' . "\n";
		echo '</a></li>' . "\n";
	}
	echo '</ul>';
} else {
	echo '<div class="item no-news total-results"><a name="no-results">No relevant results were found for "' . $qq . '"';
	if (!empty($query)) {
		//echo ' <br /><br /> Query :: ' . $query;
	}
	echo '</a></div>';
}
