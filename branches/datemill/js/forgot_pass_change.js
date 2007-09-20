$(function() {
	$('#forgot_pass_change input:visible:first').focus();
	$('#forgot_pass_change').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (use_captcha && the.captcha.value=='') {
		alert('Please enter the code from the image');
		the.captcha.focus();
		return false;
	}
	if (the.pass.value=='') {
		alert('Please enter the new password');
		the.pass.focus();
		return false;
	}
	if (the.pass.value!=the.pass2.value) {
		alert('Passwords do not match!');
		the.pass.focus();
		return false;
	}
	return true;
}
