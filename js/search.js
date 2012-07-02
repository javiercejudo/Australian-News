window.addEvent('domready', function() {
	var req = new Request({
		method: 'get',
		url: 'ajax/search.php',
		link: 'cancel',
		onRequest: function() {
			//$('news-container').setStyle('opacity', '0.5');
		},
		onComplete: function(respuesta) {
			//if (respuesta != '') {
				timeout = setTimeout(function () {
					$('news-container').set('html', respuesta);
					//$('news-container').setStyle('opacity', '1');
				}, 150);
			//}
		}
	});
	
	// listener de evento click para el selector de actividad/grupo
	$('q').addEvent('keyup', function(event){
		if(event) event.preventDefault();
		var q = $('q').get('value');
		req.get({ q : q });
	});
});
