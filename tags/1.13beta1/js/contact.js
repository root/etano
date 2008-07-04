$(function() {
	$('#contact_form').bind('submit',function() {
		return check_form(this);
	});

	$('#subject').focus();
});

function check_form(the) {
	if (the.subject.value=='') {
		alert(lang[0]);
		the.subject.focus();
		return false;
	}
	if (typeof(the.email)!='undefined' && !validate_email(the.email.value)) {
		alert(lang[1]);
		the.email.focus();
		return false;
	}
	if (the.message_body.value=='') {
		alert(lang[2]);
		the.message_body.focus();
		return false;
	}
	if (typeof(the.captcha)!='undefined' && the.captcha.value=='') {
		alert(lang[3]);
		the.captcha.focus();
		return false;
	}
	return true;
}
