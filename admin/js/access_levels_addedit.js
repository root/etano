function check_form(the) {
	if (the.level_code.value=='') {
		alert('Please enter the level code!');
		the.level_code.focus();
		return false;
	}
	return true;
}
