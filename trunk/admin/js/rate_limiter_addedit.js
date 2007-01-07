function check_form(the) {
	if (the.limit.value=='' || the.limit.value==0) {
		alert('Please enter the number of times allowed for this limit.');
		the.limit.focus();
		return false;
	}
	if (the.interval.value=='' || the.interval.value==0) {
		alert('Please enter the interval of time in minutes for this limit.');
		the.interval.focus();
		return false;
	}
	return true;
}


function manage_keydown(e) {
	var e = (e) ? e : ((event) ? event : null);
	if (!e.originalTarget && e.srcElement) e.originalTarget=e.srcElement;
	mykey=(e.which) ? e.which : ((e.keyCode) ? e.keyCode : null);
	if (e.originalTarget.id=='limit' || e.originalTarget.id=='interval') {	// only numbers allowed
		if (e.keyCode==null || e.keyCode==0 || e.keyCode==8 || e.keyCode==9 || e.keyCode==27 || e.keyCode==35 || e.keyCode==36 || e.keyCode==37 || e.keyCode==39 || e.keyCode==46) {
			return true;
		} else if (mykey>=48 && mykey<=57) { // numbers
			return true;
		} else {
			return false;
		}
	}
	return true;
}
