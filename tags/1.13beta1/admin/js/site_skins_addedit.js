$(function() {
	$('#skin_name').focus();
	$('#site_skins_addedit').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (the.skin_name.value=='') {
		alert('Please enter the name of this skin!');
		the.skin_name.focus();
		return false;
	}
	return true;
}
