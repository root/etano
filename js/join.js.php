function req_update_location(str_field,val) {
	if (val!=0 && val!='') {
		r=new Scoax();
		r.sendRequest('POST','ajax/location.php','field='+str_field+'&val='+val,update_location);
	}
}

function update_location(data) {
	if (data!=null && data!='') {
		allopts=data.split("\n");
		str_field=allopts[0];
		toshow=allopts[1].split('|');
		document.getElementById('row_'+str_field+'state').className='new_row invisible';
		document.getElementById('row_'+str_field+'city').className='new_row invisible';
		document.getElementById('row_'+str_field+'zip').className='new_row invisible';
		for (i=0;i<toshow.length;i++) {
			document.getElementById('row_'+toshow[i]).className='new_row visible';
		}
		if (allopts.length>3) {
			to_update=document.getElementById(str_field+allopts[2]);
			to_update.options.length=0;
			for (i=3;i<allopts.length;i++) {
				oneopt=allopts[i].split('|');
				opt=new Option(oneopt[1],oneopt[0]);
				to_update.options.add(opt,to_update.length);
			}
		}
	}
}

function req_check_username() {
	user=document.getElementById('user').value;
	if (validate_user(user)) {
		r=new Scoax();
		r.sendRequest('POST','ajax/user_exists.php','user='+user,check_username);
	} else {
		alert('Please use only letters and digits for your username. 4-20 chars');
	}
}

function check_username(data) {
	if (data!=null && data!='') {
		alert('You cannot use "'+document.getElementById('user').value+'" as your username because it is already taken.');
	} else {
		alert('You can use "'+document.getElementById('user').value+'" as your username.');
	}
}

function check_form(theform) {
<?php
if (empty($_GET['page']) || $_GET['page']==1) {
?>
	if (!validate_user(theform.user.value)) {
		alert('Please use only letters and digits for your username. 4-20 chars');
		theform.user.focus();
		return false;
	}
	if (theform.email.value!=theform.email2.value) {
		alert('Emails don\'t match');
		theform.email.focus();
		return false;
	}
	if (!validate_email(theform.email.value)) {
		alert('Invalid email entered');
		theform.email.focus();
		return false;
	}
	if (theform.captcha.value=='') {
		alert('Please enter the code in the captcha picture');
		theform.captcha.focus();
		return false;
	}
	if (!theform.agree.checked) {
		alert('You must agree with our terms and conditions before joining the site');
		theform.agree.focus();
		return false;
	}
<?php}?>
	return true;
}

