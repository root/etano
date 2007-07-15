$(function() {
	$('#contact_form').bind('submit',function() {
		return check_form(this);
	});

	$('#subject').focus();
});

function check_form(the) {
	if (the.subject.value=='') {
		alert('Please enter the subject of the message');
		the.subject.focus();
		return false;
	}
	if (typeof(the.email)!='undefined' && !validate_email(the.email.value)) {
		alert('Please enter your email address where we can contact you back');
		the.email.focus();
		return false;
	}
	if (the.message_body.value=='') {
		alert('Please enter the message');
		the.message_body.focus();
		return false;
	}
	if (typeof(the.captcha)!='undefined' && the.captcha.value=='') {
		alert('Please enter the code from the image');
		the.captcha.focus();
		return false;
	}
	return true;
}
