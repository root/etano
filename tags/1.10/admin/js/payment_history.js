$(function() {
	$.datePicker.setDateFormat('ymd', '-');
	$('#date_start').datePicker({startDate:'2007-07-01'});
	$('#date_end').datePicker({startDate:'2007-07-01'});
});

function mark_as_fraud(payment_id) {
	suspect_reason=prompt('Please enter the reason','');
	if (suspect_reason) {
		$.get('processors/payments.php',
				{act:'s',payment_id:payment_id,suspect_reason:suspect_reason,silent:1},
				function(data) {
					if (data!=null && data!='') {
						window.location=window.location;
					}
				}
		);
	}
	return false;
}