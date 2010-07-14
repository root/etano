$(function() {
	$('#flirts_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	tinyMCE.triggerSave(true,true);
	var mycontent=tinyMCE.get('flirt_text').getContent();
	if (mycontent=='') {
		alert('Please enter the flirt');
		return false;
	}
	return true;
}
