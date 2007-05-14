$(function() {
	$('#pass_lost input:first:visible').focus();
	$('#pass_lost').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (!validate_email(the.email.value)) {
		alert('Please enter the email address you used to register with us.');
		the.email.focus();
		return false;
	}
	if (use_captcha && the.captcha.value=='') {
		alert('Please enter the code from the image');
		the.captcha.focus();
		return false;
	}
	return true;
}
