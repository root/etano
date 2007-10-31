$(function() {
	$('#title')[0].focus();
	$('#post_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(theform) {
	if (theform.title.value=='') {
		alert(lang[0]);
		theform.title.focus();
		return false;
	}
	if (theform.post_content.value=='') {
		alert(lang[1]);
		theform.post_content.focus();
		return false;
	}
	return true;
}
