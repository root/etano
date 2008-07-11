$(function() {
	$('#join_form input:visible:first').focus();
	$('#check_username').click(function() {
		req_check_username();
		return false;
	});
	if (typeof $('#user').attr('id')!='undefined') {
		$('#join_form').bind('submit',function() {
			if (!validate_user(this.user.value)) {
				alert(lang['63']);
				this.user.focus();
				return false;
			}
			if (this.pass.value=='') {
				alert(lang['65']);
				this.user.focus();
				return false;
			}
			if (this.email.value!=this.email2.value) {
				alert(lang['37']);
				this.email.focus();
				return false;
			}
			if (!validate_email(this.email.value)) {
				alert(lang['66']);
				this.email.focus();
				return false;
			}
			if (typeof(this.captcha)!='undefined' && this.captcha.value=='') {
				alert(lang['259']);
				this.captcha.focus();
				return false;
			}
			if (!this.agree.checked) {
				alert(lang['68']);
				this.agree.focus();
				return false;
			}
		});
	}
});

function req_check_username() {
	user=$('#user').val();
	if (validate_user(user)) {
		$.post(baseurl+'/ajax/user_exists.php',
				{'user':user},
				function(data) {
					if (data!=null && data!='') {
						alert(lang['64']);
					} else {
						alert(lang['272']);
					}
				}
		);
	} else {
		alert(lang['63']);
	}
}
