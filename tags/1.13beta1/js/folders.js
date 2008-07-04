$(function() {
	$('#folders_form').submit(function() {
		if (this.folder.value=='') {
			alert(lang[0]);
			return false;
		}
	});

	$('div.row a.link_delete').bind('click',function() {
		return(confirm(lang[1]));
	});
});

function rename_folder(fid,defval) {
	new_name=prompt(lang[2],unescape(decodeURI(defval)));
	if (new_name && new_name!='' && new_name!=defval) {
		$('#folders_form')[0].folder_id.value=fid;
		$('#folders_form')[0].folder.value=new_name;
		$('#folders_form')[0].submit();
	}
}
