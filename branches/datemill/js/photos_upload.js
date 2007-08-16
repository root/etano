$(function() {
	$('#photos_upload_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (the.file1.value=='' && the.file2.value=='' && the.file3.value=='' && the.file4.value=='' && the.file5.value=='' && the.file6.value=='') {
		alert('Please select at least one photo to upload!');
		return false;
	}
	return true;
}
