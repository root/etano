$(function() {
	$('#addedit_form input:visible:first').focus();

	$('#addedit_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	return true;
}
