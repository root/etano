$(function() {
	$.datePicker.setDateFormat('ymd','-');
	$('#date').datePicker({startDate:'1995-01-01'});

	if ($('#license').val()=='') {
		$('#license').after('<a href="#" id="gen_license">Gen License</a> <a href="#" id="gen_beta_license">Gen Beta License</a>');
		$('#gen_license').bind('click',function() {
			$('#license').val(lic_key);
		});
		$('#gen_beta_license').bind('click',function() {
			$('#license').val(lic_key_beta);
		});
	}
});
