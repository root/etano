function check_form(theform) {
	is_checked=false;
	for (i=0;i<theform.subscr_id.length;i++) {
		if (theform.subscr_id[i].checked==true) {
			is_checked=true;
			break;
		}
	}
	if (!is_checked) {
		alert('Please select the membership type');
		return false;
	}
	is_checked=false;
	for (i=0;i<theform.module_code.length;i++) {
		if (theform.module_code[i].checked==true) {
			is_checked=true;
			break;
		}
	}
	if (!is_checked) {
		alert('Please select the desired payment system');
		return false;
	}
	return true;
}
