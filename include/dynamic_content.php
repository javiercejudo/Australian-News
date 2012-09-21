<?php
$qq = '';
$query = '';
$top_suggestion = '';
$total_in_database = 0;
$pdo = DB::connect();
$news = DB::get_latest_news($pdo, $num, $skip, $q, $qq, $query, $total_in_database, $top_suggestion);
$total_news = count($news);
if ($total_news > 0) {
	if ($skip < 1 || !$load_raw) {
		echo '<div class="no-news total-results">';
		if (!empty($qq) && !empty($top_suggestion) && $qq !== $top_suggestion)
		{
			echo '<p>Maybe you are searching for "<a class="top_suggestion_link" href="./?q=' . $top_suggestion . '">' . $top_suggestion . '</a>".</p>';
			//echo '<p>Search on Google: "<a class="top_suggestion_link" href="http://google.com/ncr?q=' . $top_suggestion . '">' . $top_suggestion . '</a>".</p>';
		}
		echo '<p>Showing <span id="num_showing">' . $total_news . '</span> stories';
		if (!empty($qq)) {
			echo ' for "' . $qq . '"';
		}
		echo ' out of <span id="num_total">' . $total_in_database . '</span> items ';
		if (!empty($qq)) {
			echo 'found';
		}
		else {
			echo 'stored';
		}
		if ($skip > 0) {
			$info_offset_js    = ' (' . $skip . ' results skipped)';
			$info_offset_no_js = ' (page ' . ceil(1 + $skip / $num) . '/' . ceil((($num - ($skip % $num)) % $num + $total_in_database) / $num) . ')';
			echo '<script type="text/javascript">document.write("' . $info_offset_js . '")</script>';
			echo '<noscript>' . $info_offset_no_js . '</noscript>';
		}
		echo '</p>';
		if (!empty($query)) {
			//echo '<p>Debug :: ' . $query . '</p>';
		}
		echo '</div>';
		echo '<ul class="news-feed stroll-class">';
	}
	$i = 1;
	foreach ($news as $item) {
		$item = new News($item);
		echo '<li class="item"><a id="s' . $i . '" href="' . $item->link() . '">' . "\n";
		echo '<p class="pubDate" title="Retrieved: ' . date('M j, Y h:i A', strtotime($item->created())) . '&#10;Published: ' . date('M j, Y h:i A', strtotime($item->pub_date())) . '"> ' . date('M j, Y h:i A', strtotime($item->created())) . ' </p>' . "\n";
		echo '<h1 title="' . $item->title() . '">' . $item->title() . '</h1>' . "\n";
		if (strpos($item->description(),'<font face=')) 
		{
			echo '<div class="description">' . preg_replace("/<p><img[^>]+\><\/p>/i","",substr($item->description(),0,strpos($item->description(),'<font face='))) . '</div>' . "\n";
		}
		else
		{
			echo '<div class="description">' . preg_replace("/<p><img[^>]+\><\/p>/i", "", $item->description()) . '</div>' . "\n";
		}
		echo '</a></li>' . "\n";
		$i++;
	}
	if ($skip < 1 || !$load_raw) {
		echo '</ul>';
		if ($skip + $num < $total_in_database) 
		{
			echo '<div class="more-items-container" id="more-items-container">';
			echo '<a class="more_link"    href="?q=' . $q . '&amp;num=' . ($num) . '&amp;skip=' . ($skip + $num) . '">Load more stories</a>';
			echo '<span id="more_loading">Loading...</span>';
			echo '</div>';
		}
	}
} else {
	if ($skip < 1 || !$load_raw) {
		echo '<div class="no-news total-results">';
		if (!empty($top_suggestion) && $qq !== $top_suggestion)
		{
			echo '<p>Maybe you meant "<a class="top_suggestion_link" href="./?q=' . $top_suggestion . '">' . $top_suggestion . '</a>".</p>';
			//echo '<p>Search on Google: "<a class="" href="http://google.com/?q=' . $top_suggestion . '">' . $top_suggestion . '</a>".</p>';
		}
		echo'<p>No relevant stories were found for "' . $qq . '".</p>';
		if (!empty($query)) {
			//echo '<p>Debug :: ' . $query . '</p>';
		}
		echo '</div>';
	}
}
