$(function() {
	oFCKeditor=new FCKeditor('message_body');
	oFCKeditor.BasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('email_send.php'))+'fckeditor/';
	oFCKeditor.Height=500;
	oFCKeditor.ToolbarSet='Basic';
	oFCKeditor.Config['FullPage']=true;
	oFCKeditor.ReplaceTextarea();
	$('#subject').focus();
});

