window.addEvent('domready', function() {
	// the request that will be triggered after the content of the search box
	// is modified. we create it outside the addEvent so we can cancel previous
	// request before they are completed using the parameter link:
	var input_req = new Request({
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
	
	var more_req = new Request({
		method: 'get',
		url: 'ajax/more.php',
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
	
	// makes the request whenever we tap a key that actually modifies the
	// content of the search box
	$('q').addEvent('input', function(event){
		input_req.get({ 'q' : $('q').get('value') });
		$('num').set('value', 10);
	});
	
	// when the suggestion is clicked, this brings it into the search box
	// and refreshes the results
	$$('.outer-container').addEvent('click:relay(a.top_suggestion_link)', function(event, target){
		if(event) { event.preventDefault(); }
		$('q').set('value', target.get('html'));
		$('q').fireEvent('input');
		$('q').focus();
	});
	
	// allows the tab button to mimic the behavior of a click on a suggestion
	// this is because the suggestion is the next item that can be focused
	$$('.outer-container').addEvent('focus:relay(a.top_suggestion_link)', function(event, target){
		$$('.outer-container').fireEvent('click:relay(a.top_suggestion_link)', [event, target]);
	});
	
	$$('.outer-container').addEvent('click:relay(a.more_link)', function(event, target){
		if(event) { event.preventDefault(); }
		more_req.get({ 
			'q'   : $('q').get('value'),
			'num' : $('num').get('value'),
		});
		var current_value = $('num').get('value');
		$('num').set('value', parseInt(current_value) + 10);
	});
});
