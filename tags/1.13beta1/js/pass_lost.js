$(function() {
	$('#pass_lost input:visible:first').focus();
	$('#pass_lost').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (!validate_email(the.email.value)) {
		alert(lang[0]);
		the.email.focus();
		return false;
	}
	if (use_captcha && the.captcha.value=='') {
		alert(lang[1]);
		the.captcha.focus();
		return false;
	}
	return true;
}
