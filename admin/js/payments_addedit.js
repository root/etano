$(function() {
	$('#payments_form input:visible:first').focus();
	$('#amount_paid, #refunded').numeric(true);
	$.datePicker.setDateFormat('ymd', '-');
	$('#date').datePicker({startDate:'2007-07-01'});
});

function check_form(the) {
	return true;
}
