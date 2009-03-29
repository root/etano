$(function() {
	$('#check_all').bind('click',function() {
		check_uncheck(this.checked,'requests_form','nconn_id');
	});
	$('#btn_delete').bind('click',function() {
		send_form('del');
	});
	$('#btn_move').bind('click',function() {
		$('#mailbox_form input[name=moveto_fid]').val($('#folder_id').val());
		send_form('move');
	});
});

function send_form(new_act) {
	$('#mailbox_form input[name=act]').val(new_act);
	$('#mailbox_form')[0].submit();
}

function check_uncheck(is_checked,form_id,field_str) {
	$('#'+form_id+' input[type=checkbox]').each(function() {
		if ($(this).attr('id').indexOf(field_str,0)==0) {
			this.checked=is_checked;
		}
	});
}
