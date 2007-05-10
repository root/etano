$(function() {
	oFCKeditor=new FCKeditor('message_body');
	oFCKeditor.BasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('email_send.php'))+'fckeditor/';
	oFCKeditor.Config["CustomConfigurationsPath"] = oFCKeditor.BasePath+'../js/fckconfig.js';
alert(oFCKeditor.Config["CustomConfigurationsPath"]);
	oFCKeditor.Config['FullPage']=true;
	oFCKeditor.ToolbarSet="Datemill";
	oFCKeditor.Height=500;
	oFCKeditor.Width=700;
	oFCKeditor.ReplaceTextarea();

	$('#subject').focus();
});

