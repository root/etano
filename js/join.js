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

function req_update_location(str_field,val) {
	$('#'+str_field).before('<span class="loading"></span>');
	$.post(baseurl+'/ajax/location.php',
			{'field':str_field,'val':val},
			function(data) {
				if (data!=null && data!='') {
					var allopts=data.split("\n");
					var str_field=allopts[0];
					var toshow=allopts[1].split('|');
					$('#row_'+str_field+'state').addClass('invisible').removeClass('visible');
					$('#row_'+str_field+'city').addClass('invisible').removeClass('visible');
					$('#row_'+str_field+'zip').addClass('invisible').removeClass('visible');
					for (i=0;i<toshow.length;i++) {
						$('#row_'+toshow[i]).addClass('visible').removeClass('invisible');
					}
					if (allopts.length>3) {
						var to_update=$('#'+str_field+allopts[2]);
						to_update.html('<option>Loading</option>');
						var towrite='';
						for (i=3;i<allopts.length;i++) {
							oneopt=allopts[i].split('|');
							towrite+='<option value="'+oneopt[0]+'">'+oneopt[1]+'</option>';
						}
						to_update.html(towrite);
						to_update.focus();
					}
					$('.loading').remove();
				}
			}
	);
}

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
