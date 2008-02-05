$(function() {
	$('#level_code').focus();
	$('#access_levels_form').submit(function() {
		if (this.level_code.value=='') {
			alert('Please enter the level code!');
			this.level_code.focus();
			return false;
		}
		return true;
	});
});
