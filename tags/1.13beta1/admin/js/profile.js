function change_password() {
	newpass=prompt('Please enter the new password:','');
	if (!newpass || newpass=='') {
		return false;
	}
	$.post('processors/account_changes.php',
			{'silent':1,'act':'pass','uids[]':uid,'pass':newpass},
			function(data) {
				if (data!=null && data!='') {
					alert(data);
				}
			}
	);
}
