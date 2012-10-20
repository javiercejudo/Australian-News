<?php
header('Content-type: text/html; charset=utf-8');
require_once 'config.php';
require_once DIRLIB . 'App.php';
require_once DIRLIB . 'DB.php';
require_once DIRLIB . 'News.php';

$app = new App($num, $skip, $q);
$app->load_XML();

?>
<!DOCTYPE html>
<meta charset="UTF-8" />
<title>Australian News | Javier Cejudo</title>
<link rel="stylesheet" type="text/css" href="css/styles.css">
<body>
<div class="outer-container">
<form name="search-form" action="./" method="GET">
<input type="search" autocomplete="off" placeholder="Search SMH national news..." id="q" name="q" value="<?= $q ?>" />

<input type="hidden" name="ns" value='true' />
<!--<input type="text" id="total_in_database" name="total_in_database" value="" />
<input type="text" id="skip" name="skip" value="" />-->
<!--<input type="submit" name="dosearch" value="Search" />-->
</form>
<input type="hidden" id="num" name="num" value="<?= $num ?>" />
<input type="hidden" id="items_per_page" name="items_per_page" value="<?= $num ?>" />
<input type="hidden" id="skipped" name="skipped" value="<?= $skip ?>" />
<div id="news-container">
<!--<script type="text/javascript">document.write('Loading...')</script>-->
<?php 
include DIRINC . 'dynamic_content.php'; 
?>
</div>
</div>
<footer>
<p class="technical footer">
	Valid <a href="http://validator.w3.org/check?uri=referer">HTML5</a>. 
	© <?php echo date('Y'); ?> Javier Cejudo
</p>
<a href="https://github.com/javiercejudo/Australian-News">
<img class="git_ribbon" src="assets/fork_red.png" height="149" width="149" alt="Fork me on GitHub" title="Fork the repo for this page on GitHub">
</a>
</footer>
<script src="vendor/js/mootools-core.js" type="text/javascript"></script>
<script src="vendor/js/onhashchange.js" type="text/javascript"></script>
<script src="js/search.js" type="text/javascript"></script>
<script type="text/javascript">
	var DURATION = parseInt(<?= DURATION ?>, 10);
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-33442040-1']);
	_gaq.push(['_trackPageview']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
</body>
