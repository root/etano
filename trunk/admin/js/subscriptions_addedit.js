function check_form(theform) {
	if (theform.subscr_name.value=='') {
		alert('Please enter the short description');
		theform.subscr_name.focus();
		return false;
	}
	if (theform.m_value_from.value==theform.m_value_to.value) {
		alert('The ending membership must be different from the starting membership!');
		theform.m_value_to.focus();
		return false;
	}
	return confirm('Are you sure you want to save your changes?');
}

function manage_keydown(e) {
	var e = (e) ? e : ((event) ? event : null);
	if (!e.originalTarget && e.srcElement) e.originalTarget=e.srcElement;
	mykey=(e.which) ? e.which : ((e.keyCode) ? e.keyCode : null);
	if (e.originalTarget.id=='price') {	// only numbers allowed
//alert(mykey);
		if (e.keyCode==null || e.keyCode==0 || e.keyCode==8 || e.keyCode==9 || e.keyCode==27 || e.keyCode==35 || e.keyCode==36 || e.keyCode==37 || e.keyCode==39 || e.keyCode==46) {
			return true;
		} else if (mykey>=48 && mykey<=57) { // numbers
			return true;
		} else if ((mykey==190 || mykey==110) && e.originalTarget.value.indexOf(".")==-1) { // .
			return true;
		} else {
			return false;
		}
	} else if (e.originalTarget.id=='days') {	// only numbers allowed
		if (e.keyCode==null || e.keyCode==0 || e.keyCode==8 || e.keyCode==9 || e.keyCode==27 || e.keyCode==35 || e.keyCode==36 || e.keyCode==37 || e.keyCode==39 || e.keyCode==46) {
			return true;
		} else if (mykey>=48 && mykey<=57) { // numbers
			return true;
		} else {
			return false;
		}
	} else if (e.originalTarget.id=='subscr_name') {
		if (e.shiftKey && e.keyCode==9) {
			document.getElementById('btn_save').focus();
			return false;
		}
	} else if (e.originalTarget.id=='btn_save') {
		if (!e.shiftKey && e.keyCode==9) {
			document.getElementById('subscr_name').focus();
			return false;
		}
	}
	return true;
}
