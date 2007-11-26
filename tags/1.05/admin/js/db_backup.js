$(function() {
	$('#backup_form').bind('submit',function() {
		$('#btn_backup').attr({disabled:'disabled'});
		$('<div><img src="skin/images/load_indicator.gif" /> Please wait, loading</div>').appendTo('#backup_form');
	});
});
