$(function() {
	$('#folders_form').submit(function() {
		if (this.folder.value=='') {
			alert('Please enter the folder name');
			return false;
		}
	});

	$('a.item_delete').bind('click',function() {
		return(confirm('Are you sure you want to delete this folder? All messages inside this folder will be moved to Trash and all the filters associated with this folder will be deleted!'));
	}
});

function rename_folder(fid,defval) {
	new_name=prompt('Please enter the new folder name',unescape(defval));
	if (new_name && new_name!='' && new_name!=defval) {
		$('#folders_form')[0].folder_id.value=fid;
		$('#folders_form')[0].folder.value=new_name;
		$('#folders_form')[0].submit();
	}
}
