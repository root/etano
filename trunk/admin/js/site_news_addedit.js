$(function() {
	$('#site_news_form').bind('submit',function() {
		return check_form(this);
	});
	$('#news_title').focus();
});


function check_form(the) {
	tinyMCE.triggerSave(true,true);
	if (the.news_title.value=='') {
		alert('Please enter the subject of the news');
		return false;
	}
	var mycontent=tinyMCE.get('news_body').getContent();
	if (mycontent=='') {
		alert('Please enter the content of the news');
		return false;
	}
	return true;
}
