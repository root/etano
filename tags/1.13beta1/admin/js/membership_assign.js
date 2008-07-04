$(function() {
	$('#m_value').focus();
	$('#duration').numeric();
	$('#assign_m_form').submit(function() {
		if (this.duration.value=='') {
			alert('Please enter the duration of this membership');
			$('#duration').focus();
			return false;
		}
		return true;
	});
});
