window.addEvent('domready', function() {	
	// defines the event that we will use to load results when the hash changes
	window.addEvent('hashchange', function(newhash) {
        if (location.hash.substr(3) !== $('q').get('value')) {
		    $('q').set('value', location.hash.substr(3));
        }
		input_req.get({ 
			'q'   : location.hash.substr(3) ,
			'num' : $('items_per_page').get('value')
		});
		$('num').set('value', $('items_per_page').get('value'));
		$('skipped').set('value', 0);
	});
    
	// the request that will be triggered after the content of the search box
	// is modified. we create it outside the addEvent so we can cancel previous
	// requests before they are completed using the parameter link:
	var input_req = new Request({
		method: 'get',
		url: 'ajax/search.php',
		link: 'cancel',
		onRequest: function() {
			$('news-container').setStyle('opacity', '0.5');
		},
		onComplete: function(response) {
			if (response !== undefined) {
				timeout = setTimeout(function () {
					$('news-container').set('html', response);
					$('news-container').setStyle('opacity', '1');
				}, 0);
			}
		},
		onFailure: function(response) {
			if (response.status == 0) {
				alert('Please check your internet connectivity or reload the page.');
			} else {
				alert('Error ' + response.status + '. Please reload the page or contact javier@javiercejudo.com');
			}
			$('news-container').setStyle('opacity', '1');
			clearInterval(timer);
		}
	});
    
    // handles the initial hash, if it exists
    // it will use input_req, so it must be after that is defined
    if (location.hash.length > 3) {
        window.fireEvent('hashchange');
    }
    
	// the request that will be triggered after asking for more items
	// we create it outside the addEvent so we can ignore following
	// requests before they are completed using the parameter link:
	var more_req = new Request({
		method: 'get',
		url: 'ajax/more.php',
		link: 'ignore',
		onRequest: function() {
			$$('.more_link').setStyle('display', 'none');
			$('more_loading').setStyle('display', 'block');
		},
		onComplete: function(response) {
			if (response !== undefined) {
				timeout = setTimeout(function () {
					var auxElement = new Element('ul', {'class': 'news-feed'});
					auxElement.set('html', response);
					auxElement.inject($('more-items-container'), 'before');
					var num_top = parseInt($('num').get('value'), 10) + parseInt($('items_per_page').get('value'), 10);
					var num_tot = parseInt($('num_total').get('html'), 10);
					var num_sho;
					$('more_loading').setStyle('display', 'none');
					console.log("Number showing: " + num_top);
					console.log("NUM: " + parseInt($('num').get('value'), 10));
					console.log("IPP: " + parseInt($('items_per_page').get('value'), 10));
					console.log("Number total: " + num_tot);
					if (num_top >= num_tot) {
						num_sho = num_tot;
						$$('.more_link').setStyle('display', 'none');
					} else {
						num_sho = num_top;
						$$('.more_link').setStyle('display', 'block');
					}
					$('num').set('value', num_top);
					$('skipped').set('value', parseInt($('skipped').get('value'), 10) + parseInt($('items_per_page').get('value'), 10));
					$('num_showing').set('html', num_sho);
				}, 0);
			}
		},
		onFailure: function(response) {
			if (response.status == 0) {
				alert('Please check your internet connectivity or reload the page.');
			} else {
				alert('Error ' + response.status + '. Please reload the page or contact javier@javiercejudo.com');
			}
			$$('.more_link').setStyle('display', 'block');
			$('more_loading').setStyle('display', 'none');
			clearInterval(timer);
		}
	});
	
	// changes hash, what will trigger the request whenever we tap a key that 
	// actually modifies the content of the search box
	$('q').addEvent('input', function(event){
		window.sethash("#q=" + $('q').get('value'));
	});
	
	// when the suggestion is clicked, this brings it into the search box
	// and refreshes the results
	$$('.outer-container').addEvent('click:relay(a.top_suggestion_link)', function(event, target){
		if(event) { event.preventDefault(); }
		window.sethash("#q=" + target.get('html'));
	});
	
	// allows the tab button to mimic the behavior of a click on a suggestion
	// this is because the suggestion is the next item that can be focused
	$$('.outer-container').addEvent('focus:relay(a.top_suggestion_link)', function(event, target){
		$$('.outer-container').fireEvent('click:relay(a.top_suggestion_link)', [event, target]);
	});
	
	// triggers the request to load more items when the link is clicked
	$$('.outer-container').addEvent('click:relay(a.more_link)', function(event, target){
		if(event) { event.preventDefault(); }
		if ($('num_total') != undefined && parseInt($('skipped').get('value')) + parseInt($('items_per_page').get('value')) < parseInt($('num_total').get('html'))){
			console.log("Searching for: " + $('q').get('value'));
			console.log("Number of stories: " + $('num').get('value'));
			console.log("Skipping: " + $('skipped').get('value'));
			more_req.get({ 
				'q'    : $('q').get('value'),
				'num'  : $('items_per_page').get('value'),
				'skip' : $('skipped').get('value')
			});
		} else {
			$$('.more_link').setStyle('display', 'none');
		}
	});
	
	// this is the test to see if we need to load more items
	// basically, if we are close to the bottom, we mimic the user
	// clicking the link to load more items
	var infiniteScroll = function(){ 
		var elt = document.body;
		if (elt.getScroll().y >= elt.getScrollSize().y - 2*elt.getSize().y) {
			$$('.outer-container').fireEvent('click:relay(a.more_link)');
		}
	};
	// this defines the frequency of the test
	var timer = infiniteScroll.periodical(300);
});
