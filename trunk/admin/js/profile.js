function change_password() {
	newpass=prompt('Please enter the new password:');
	if (!newpass || newpass=='') {
		return false;
	}
	$.ajax({url:'processors/account_changes.php',
			type: 'POST',
			dataType: 'html',
			data: 'silent=1&act=pass&uid='+uid+'&pass='+newpass+'&search='+search+'&o='+o+'&r='+r,
			success: function(data) {
						alert(data);
					}
			});
}
