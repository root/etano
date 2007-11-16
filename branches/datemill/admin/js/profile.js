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

function change_user() {
	newuser=prompt('Please enter the new user:','');
	if (!newuser || newuser=='') {
		return false;
	}
	$.post('processors/account_changes.php',
			{'silent':1,'act':'user','uids[]':uid,'user':newuser},
			function(data) {
				if (data!=null && data!='') {
					alert(data);
				}
			}
	);
}
