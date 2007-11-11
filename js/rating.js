$(function() {
	$('a.rate1,a.rate2,a.rate3,a.rate4,a.rate5').bind('click',function() {
		vote=$(this).attr('class').substr(4);
		$.post(baseurl+'/ajax/rate_photo.php',
			{'photo_id':photo_id,'vote':vote,'silent':1},
			function(data) {
				if (data && data!='') {
					if (data.substr(0,1)==0) {	// if error
						alert(data.substr(2));
					} else {
						opts=data.split('|');
						percent=parseInt((opts[1]*100)/5);
						$('li.current_rating').css('width',percent+'%').html(opts[1]+' / 5');
						$('#rate_num').html(opts[1]);
						$('#votes_num').html(opts[2]);
						alert('Thank you for your vote');
					}
				}
			}
		);
		return false;
	});
});
