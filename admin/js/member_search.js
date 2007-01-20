$(function() {
	$('#dummy').bind('click',toogle_checked);
});

function toogle_checked() {
	is_checked=$('#dummy')[0].checked;
	$('#mass_member_form input:checkbox').each(function(i) {
		this.checked=is_checked;
	});
}

function handle_mass_member() {
	if ($('#act').val()=='email') {
		$('#mass_member_form').attr('action','email_send.php');

	} else if ($('#act').val()=='message') {
		$('#mass_member_form').attr('action','message_send.php');

	} else if ($('#act').val()=='membership') {
		$('#mass_member_form').attr('action','membership_set.php');

	} else if ($('#act').val()=='astat_active') {
//_ASTAT_ACTIVE_
		$('#mass_member_form').attr('action','processors/account_changes.php');
		$('#mass_member_form').append('<input type="hidden" name="act" value="status" /><input type="hidden" name="status" value="15" />');

	} else if ($('#act').val()=='astat_suspend') {
//_ASTAT_SUSPENDED_
		$('#mass_member_form').attr('action','processors/account_changes.php');
		$('#mass_member_form').append('<input type="hidden" name="act" value="status" /><input type="hidden" name="status" value="5" />');

	} else if ($('#act').val()=='del') {
		really=confirm('Are you sure you want to delete the selected members?');
		if (!really) {
			return false;
		}
		$('#mass_member_form').attr('action','processors/member_delete.php');
	}

	if ($('#sel').val()==1) {
		one_checked=false;
		$('#mass_member_form input:checkbox').each(function(i) {
			if (this.checked) {
				one_checked=true;
			}
		});
		if (!one_checked) {
			alert("You must select at least one member");
			return false;
		}
		$('#search').val('');
	}
	$('#mass_member_form')[0].submit();
	return true;
}
