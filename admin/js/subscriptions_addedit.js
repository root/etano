$(function() {
	$('#subscriptions_addedit').bind('submit',function() {
		return check_form(this);
	});
	$('#price').numeric(true);
	$('#duration').numeric();
	$('#subscr_name').focus();
});

function check_form(theform) {
	if (theform.subscr_name.value=='') {
		alert('Please enter the short description');
		theform.subscr_name.focus();
		return false;
	}
	if (theform.m_value_from.value==theform.m_value_to.value) {
		alert('The ending membership must be different from the starting membership!');
		theform.m_value_to.focus();
		return false;
	}
	return confirm('Are you sure you want to save your changes?');
}
