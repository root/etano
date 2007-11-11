$(function() {
	$('#tco_payment').bind('submit',function() {
		if ($('#x_amount').val()=='' || $('#x_amount').val()<=0) {
			alert('Please enter the amount');
			$('#x_amount').focus();
			return false;
		}
		$('#c_price').val($('#x_amount').val());
		return true;
	});

	$('#paypal_payment').bind('submit',function() {
		if ($('#amount').val()=='' || $('#amount').val()<=0) {
			alert('Please enter the amount');
			$('#amount').focus();
			return false;
		}
		return true;
	});

	$('#x_amount, #amount').numeric(true);
});
