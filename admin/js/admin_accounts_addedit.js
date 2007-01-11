$(function() {
	showhide('change_pass','row_pass');
	showhide('change_pass','row_pass2');
});

function check_form(the) {
	if (the.name.value=='') {
		alert('Please enter the name');
		the.name.focus();
		return false;
	}
	if (the.user.value=='') {
		alert('Please enter the user');
		the.user.focus();
		return false;
	}
	if (document.getElementById("change_pass").checked) {
		if (the.pass.value=='') {
			alert('Please enter the password');
			the.pass.focus();
			return false;
		}
		if (!checkByteLength(the.pass.value,4,20)) {
			alert('The password must have between 4 and 20 chars');
			the.pass.focus();
			return false;
		}
		if (the.pass.value!=the.pass2.value) {
			alert('Passwords do not match');
			the.pass.focus();
			return false;
		}
	}
	return true;
}

function showhide(strCheck,strHide) {
	if (document.getElementById(strCheck).checked==true) {
		$("#"+strHide).show();
	} else {
		$("#"+strHide).hide();
	}
}

