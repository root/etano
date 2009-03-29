$(function() {
	$('#btn_reply').bind('click',function() {
		send_form('reply');
	});
	$('#btn_delete').bind('click',function() {
		send_form('del');
	});
	$('#btn_spam').bind('click',function() {
		send_form('spam');
	});
	$('#btn_move').bind('click',function() {
		$('#mread_form input[name=moveto_fid]').val($('#folder_id').val());
		send_form('move');
	});
});

function send_form(new_act) {
	$('#mread_form input[name=act]').val(new_act);
	$('#mread_form')[0].submit();
}
