$(function() {
	$('a.rate1,a.rate2,a.rate3,a.rate4,a.rate5').bind('click',function() {
		vote=$(this).attr('class').substr(4);
		$.getJSON($(this).attr('href')+'&silent=1',
			function(json) {
				if (json && json!='') {
					if (typeof json.error!='undefined') {
						alert(json.text);
					} else {
						percent=parseInt((json.score*100)/5,10);
						$('li.current_rating').css('width',percent+'%').html(json.score+' / 5');
						$('#rate_num').html(json.score);
						$('#votes_num').html(json.num_votes);
						alert(json.text);
					}
				}
			}
		);
		return false;
	});
});
