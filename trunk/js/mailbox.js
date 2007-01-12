function check_uncheck(trigger,master_chk,theform,field_str) {
	if (trigger==1) {
		master_chk.checked=!master_chk.checked;
	}
	is_checked=master_chk.checked;
	for (i=0;i<theform.elements.length;i++) {
		if (theform.elements[i].type=='checkbox' && theform.elements[i].id.indexOf(field_str,0)==0) {
			theform.elements[i].checked=is_checked;
		}
	}
}

function send_form(new_act) {
	document.getElementById('mailbox_form').act.value=new_act;
	document.getElementById('mailbox_form').submit();
}
