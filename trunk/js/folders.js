$(function() {
	$('#folders_form').submit(function() {
		if (this.folder.value=='') {
			alert('Please enter the folder name');
			return false;
		}
	});
});

function rename_folder(fid,defval) {
	new_name=prompt('Please enter the new folder name',unescape(defval));
	if (new_name && new_name!='' && new_name!=defval) {
		$('#folders_form')[0].folder_id.value=fid;
		$('#folders_form')[0].folder.value=new_name;
		$('#folders_form')[0].submit();
	}
}
