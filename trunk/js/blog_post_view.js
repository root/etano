$(function() {
	$('div.comment a.link_change[@id^=editme_]').bind('click',function() {
		c_id=$(this).attr('id').substr(7);
		$.post('ajax/get_blog_comment.php',
				{'comment_id':c_id},
				function(data) {
					if (data!=null && data!='') {
						data=data.split('|');
						if (typeof(data[0])!='undefined') {
							if (data[0]==0) {
								$('#comment_id').val(unescape(data[1]));
								$('#comment').val(unescape(data[2]));
								$('#postarea').ScrollTo(800);
							} else {
								alert(data[1]);
							}
						} else {
							return true;
						}
					}
				}
		);
		return false;
	});
});

