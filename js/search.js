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
                //if (response !== '') {
			        $('news-container').set('html', response);
                //}
                $('news-container').setStyle('opacity', '1');
			}, 100);
		}
	});
	
	// listener de evento click para el selector de actividad/grupo
	$('q').addEvent('input', function(event){
		if(event) { event.preventDefault(); }
		var q = $('q').get('value');
		req.get({ q : q });
	});
});
