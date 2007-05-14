$(function() {
	$('#is_main').focus();
	$('#photo_edit_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	return confirm('Save?');
}
