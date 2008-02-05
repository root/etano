$(function() {
	$('#subject').focus();
	$('#newsletter_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	tinyMCE.triggerSave(true,true);
	var mycontent=tinyMCE.getContent();
	if (mycontent=='') {
		alert('Please enter the newsletter content');
		return false;
	}
	return true;
}
