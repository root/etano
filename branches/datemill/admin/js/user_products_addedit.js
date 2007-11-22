$(function() {
	$.datePicker.setDateFormat('ymd','-');
	$('#date').datePicker({startDate:'1995-01-01'});

	$('#gen_license').bind('click',function() {
		$('#license').val(lic_key);
	});
	$('#gen_beta_license').bind('click',function() {
		$('#license').val(lic_key_beta);
	});
});
