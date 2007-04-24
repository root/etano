$(function() {
	oFCKeditor=new FCKeditor('news_body');
	oFCKeditor.BasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('site_news_addedit.php'))+'fckeditor/';
	oFCKeditor.Height=200;
	oFCKeditor.Width=600;

//oFCKeditor.Config.ToolbarSets["Xyz"] = [
//	['Bold','Italic','Underline','StrikeThrough'],['Subscript','Superscript','-','OrderedList','UnorderedList','-','Link','Unlink'],
//	['Style','FontFormat','FontName','FontSize'],
//	['TextColor','BGColor']
//] ;
	oFCKeditor.ToolbarSet='Basic';
	oFCKeditor.Config['FullPage']=false;
	oFCKeditor.ReplaceTextarea();

	$('#site_news_form').bind('submit',function() {
		return check_form($(this)[0]);
	});
	$('#news_title').focus();
});

function check_form(the) {
	if (the.news_title.value=='') {
		alert('Please enter the subject of the news');
		the.news_title.focus();
		return false;
	}
	if (the.news_body.value=='') {
		alert('Please enter the body of the news');
		the.news_body.focus();
		return false;
	}
	return true;
}
