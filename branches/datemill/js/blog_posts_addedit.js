$(function() {
	$('#title')[0].focus();
	$('#post_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(theform) {
	if (theform.title.value=='') {
		alert('Please add a title for this post');
		theform.title.focus();
		return false;
	}
	if (theform.post_content.value=='') {
		alert('Please write something in the post');
		theform.post_content.focus();
		return false;
	}
	return true;
}
