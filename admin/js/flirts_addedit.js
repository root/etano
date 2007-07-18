$(function() {
	oFCKeditor=new FCKeditor('flirt_text');
	oFCKeditor.BasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('/'))+'/fckeditor/';
	oFCKeditor.Config["CustomConfigurationsPath"] = oFCKeditor.BasePath+'../js/fckconfig.js';
	oFCKeditor.Config['FullPage']=false;
	oFCKeditor.ToolbarSet="Datemill";
	oFCKeditor.Height=200;
	oFCKeditor.Width=600;
	oFCKeditor.ReplaceTextarea();

	$('#flirts_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (the.flirt_text.value=='') {
		alert('Please enter the flirt');
		the.flirt_text.focus();
		return false;
	}
	return true;
}
