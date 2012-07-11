window.addEvent('domready', function() {
	var req = new Request({
		method: 'get',
		url: 'ajax/search.php',
		link: 'cancel',
		onRequest: function() {
			$('news-container').setStyle('opacity', '0.5');
		},
		onComplete: function(response) {
			timeout = setTimeout(function () {
			    $('news-container').set('html', response);
				$('news-container').setStyle('opacity', '1');
			}, 100);
		}
	});
	
	$('q').addEvent('input', function(event){
		req.get({ 'q' : $('q').get('value') });
	});
	
	$$('.outer-container').addEvent('click:relay(a.top_suggestion_link)', function(event, target){
		if(event) { event.preventDefault(); }
		$('q').set('value', target.get('html'));
		$('q').fireEvent('input');
		$('q').focus();
	});
	
	$$('.outer-container').addEvent('focus:relay(a.top_suggestion_link)', function(event, target){
		$$('.outer-container').fireEvent('click:relay(a.top_suggestion_link)', [event, target]);
	});
});
