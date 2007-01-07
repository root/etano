function req_field_change(val) {
	document.getElementById('to_members_2').checked=true;
	if (val!='') {
		r=new Scoax();
		r.sendRequest('POST','ajax/get_field_accvals.php','field='+val,field_change);
	} else {
		field_change();
	}
}

function field_change(data) {
	to_update=document.getElementById('field_value');
	if (data!=null && data!='') {
		allopts=data.split("\n");
		str_field=allopts[0];
		to_update.options.length=0;
		for (i=0;i<allopts.length;i++) {
			oneopt=allopts[i].split('|');
			opt=new Option(oneopt[1],oneopt[0]);
			to_update.options.add(opt,to_update.length);
		}
	} else {
		to_update.options.length=0;
	}
}

function check_form(theform) {
	return true;
}
