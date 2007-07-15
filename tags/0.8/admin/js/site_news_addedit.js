$(function() {
	oFCKeditor=new FCKeditor('news_body');
	oFCKeditor.BasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('/'))+'/fckeditor/';
	oFCKeditor.Config["CustomConfigurationsPath"] = oFCKeditor.BasePath+'../js/fckconfig.js';
	oFCKeditor.Config['FullPage']=false;
	oFCKeditor.ToolbarSet='Datemill';
	oFCKeditor.Width=600;
	oFCKeditor.Height=200;
	oFCKeditor.ReplaceTextarea();

	$('#site_news_form').bind('submit',function() {
		return check_form(this);
	});
	$('#news_title').focus();
});


function check_form(the) {
	if (the.news_title.value=='') {
		alert('Please enter the subject of the news');
		the.news_title.focus();
		return false;
	}

//	if (the.news_body.value=='') {
//		alert('Please enter the body of the news');
//		the.news_body.focus();
//		return false;
//	}
	return true;
}
