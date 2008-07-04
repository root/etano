$(function() {
	$('#bans_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	if (!$('#ban_type_2')[0].checked && !$('#ban_type_3')[0].checked) {
		alert('Please select the ban type');
		return false;
	}
	if (the.what.value=='') {
		alert('Please enter the username or IP address to ban');
		the.what.focus();
		return false;
	}
	return true;
}
