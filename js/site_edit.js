$(function() {
	$('#site_edit_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (the.baseurl.value=='' || the.baseurl.value=='http://') {
		alert('Please enter the URL of your site');
		the.baseurl.focus();
		return false;
	}
	if (the.baseurl.value.substr(0,7)!='http://') {
		alert('The URL of the site must start with http://');
		the.baseurl.focus();
		return false;
	}
	return true;
}
