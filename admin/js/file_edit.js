$(function() {
	if (typeof(richedit)!='undefined' && richedit) {
		oFCKeditor=new FCKeditor('file_content');
		oFCKeditor.BasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('file_edit.php'))+'fckeditor/';
		oFCKeditor.Config["CustomConfigurationsPath"] = oFCKeditor.BasePath+'../js/fckconfig.js';
		oFCKeditor.ToolbarSet="Datemill";
		if (typeof(fullpage)!='undefined' && fullpage) {
			oFCKeditor.Config['FullPage']=true;
		} else {
			oFCKeditor.Config['FullPage']=false;
		}
		oFCKeditor.Height=500;
		oFCKeditor.Width=750;
		oFCKeditor.ReplaceTextarea();
	}
});

function check_form(the) {
	return confirm('Are you sure you want to save this file?');
}
