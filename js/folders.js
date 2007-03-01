$(function() {
	$('#folders_form').submit(function() {
		if (this.folder.value=='') {
			alert('Please enter the folder name');
			return false;
		}
	});
});

function rename_folder(fid,defval) {
	new_name=prompt('Please enter the new folder name',defval);
	if (new_name && new_name!='' && new_name!=defval) {
		document.getElementById('folders_form').folder_id.value=fid;
		document.getElementById('folders_form').folder.value=new_name;
		document.getElementById('folders_form').submit();
	}
}
