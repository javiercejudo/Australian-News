<?php
$qq = '';
$query = '';
$top_suggestion = '';
$news = DB::get_latest_news($pdo, $num, $q, $qq, $query, $top_suggestion);
$total_news = count($news);
if ($total_news > 0) {
	echo '<div class="no-news total-results"><p>Showing ' . $total_news . ' items';
	if (!empty($qq)) {
		echo ' for "' . $qq . '".';
		if (!empty($top_suggestion) && $qq !== $top_suggestion)
		{
			echo '<p>Maybe you are searching for "<a class="top_suggestion_link" href="./?q=' . $top_suggestion . '">' . $top_suggestion . '</a>".</p>';
		}
	}
	echo '</p>';
	if (!empty($query)) {
		//echo '<p>Debug :: ' . $query . '</p>';
	}
	echo '</div>';
	echo '<ul class="news-feed">';
	$i = 1;
	foreach ($news as $item) {
		echo '<li class="item"><a name="' . $i . '" href="' . $item->link . '">' . "\n";
		echo '<p class="pubDate">' . date('H:i | d/m/Y', strtotime($item->pub_date)) . '</p>' . "\n";
		echo '<h1 title="' . $item->title . '">' . $item->title . '</h1>' . "\n";
		if (strpos($item->description,'<font face=')) 
		{
			echo '<div class="description">' . substr($item->description,0,strpos($item->description,'<font face=')) . '</p></div>' . "\n";
		}
		else
		{
			echo '<p class="description">' . $item->description . '</p>' . "\n";
		}
		echo '</a></li>' . "\n";
		$i++;
	}
	echo '</ul>';
} else {
	echo '<div class="no-news total-results"><p>No relevant results were found for "' . $qq . '".</p>';
	if (!empty($top_suggestion) && $qq !== $top_suggestion)
	{
		echo '<p>Maybe you meant "<a class="top_suggestion_link" href="./?q=' . $top_suggestion . '">' . $top_suggestion . '</a>".</p>';
	}
	if (!empty($query)) {
		//echo '<p>Debug :: ' . $query . '</p>';
	}
	echo '</div>';
}
?>

<div class="more-items-container">
	<a class="more-link" href="?<?php echo "q=" . $q . "&num=" . ($num + DURATION) . "#" . $num  ?>">Load more items</a>
</div>
