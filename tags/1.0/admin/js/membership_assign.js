$(function() {
	$('#m_value').focus();
	$('#duration').numeric();
	$('#assign_m_form').submit(function() {
		if (this.duration.value=='' || this.duration.value==0) {
			alert('Please enter the duration of this membership');
			$('#duration').focus();
			return false;
		}
		return true;
	});
});

