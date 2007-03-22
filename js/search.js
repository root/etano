$(function() {
	$('#gview_switch').bind('click',function() {
		$('ul.table_row').removeClass('list_view').addClass('gallery_view');
		createCookie('sco_app[rv_mode]','g',365);	// results_view_mode
		return false;
	});
	$('#lview_switch').bind('click',function() {
		$('ul.table_row').removeClass('gallery_view').addClass('list_view');
		createCookie('sco_app[rv_mode]','l',365);	// results_view_mode
		return false;
	});
});
