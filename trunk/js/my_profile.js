$(function() {
	$('.comment_delete').bind('click',function() {
		return confirm('Are you sure you want to remove this comment?');
	});
});
