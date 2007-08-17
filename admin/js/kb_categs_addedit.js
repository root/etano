$(function() {
	$('#kbc_title').focus();
	$('#addedit_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (the.kbc_title.value=='') {
		alert('Please enter the title!');
		the.kbc_title.focus();
		return false;
	}
	return true;
}
