$(function() {
	$('#site_news_form').bind('submit',function() {
		return check_form(this);
	});
	$('#news_title').focus();
});


function check_form(the) {
	tinyMCE.triggerSave(true,true);
	var mycontent=tinyMCE.getContent();
	if (mycontent=='') {
		alert('Please enter the subject of the news');
		return false;
	}
	return true;
}
