$(function() {
	$('#link_block').bind('click',function() {
		return confirm('Are you sure you want to block this member?');
	});
	$('#link_unblock').bind('click',function() {
		return confirm('Are you sure you want to unblock this member?');
	});
});
