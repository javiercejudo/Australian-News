<?php
$qq = '';
$query = '';
$top_suggestion = '';
$news = DB::get_latest_news($pdo, 100, $q, $qq, $query, $top_suggestion);
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
	echo '<ul class="wave" id="fancy-list">';
	foreach ($news as $item) {
		echo '<li class="item"><a href="' . $item->link . '">' . "\n";
		echo '<p class="pubDate">' . date('H:i | d/m/Y', strtotime($item->pub_date)) . '</p>' . "\n";
		echo '<h1 title="' . $item->title . '">' . $item->title . '</h1>' . "\n";
		if (strtotime($item->created) > date('U')-3*60*60) 
		{
			echo '<div class="description">' . substr($item->description,0,strpos($item->description,'<font face=')) . '</p></div>' . "\n";
		}
		else
		{
			echo '<p class="description">' . $item->description . '</p>' . "\n";
		}
		echo '</a></li>' . "\n";
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
