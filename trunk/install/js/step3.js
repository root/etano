$(function() {
	$('#install_form').bind('submit',function() {
		$('#btn_submit').get(0).disabled='disabled';
		$('<img src="skin/images/load_indicator.gif" /><span> Loading...please wait</span>').appendTo('#loading');
	});
});
