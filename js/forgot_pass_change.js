$(function() {
	$('#forgot_pass_change input:visible:first').focus();
	$('#forgot_pass_change').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (use_captcha && the.captcha.value=='') {
		alert(lang[0]);
		the.captcha.focus();
		return false;
	}
	if (the.pass.value=='') {
		alert(lang[1]);
		the.pass.focus();
		return false;
	}
	if (the.pass.value!=the.pass2.value) {
		alert(lang[2]);
		the.pass.focus();
		return false;
	}
	return true;
}
