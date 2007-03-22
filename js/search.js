$(function() {
	$('#gview_switch').bind('click',function() {
		$('ul.table_row').removeClass('list_view').addClass('gallery_view');
		return false;
	});
	$('#lview_switch').bind('click',function() {
		$('ul.table_row').removeClass('gallery_view').addClass('list_view');
		return false;
	});
});
