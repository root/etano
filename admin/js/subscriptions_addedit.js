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
	return confirm('Are you sure you want to save your changes?');
}
