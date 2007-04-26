$(function() {
	$('#flirts_form').bind('submit',function() {
		return check_form($(this)[0]);
	});

	$('#flirt_text').focus();
});

function check_form(the) {
	if (the.flirt_text.value=='') {
		alert('Please enter the flirt');
		the.flirt_text.focus();
		return false;
	}
	return true;
}
