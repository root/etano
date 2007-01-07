function req_update_location(str_field,val) {
	r=new Scoax();
	r.sendRequest('POST','../ajax/location.php','field='+str_field+'&val='+val,update_location);
}

function update_location(data) {
	if (data!=null && data!='') {
		allopts=data.split("\n");
		str_field=allopts[0];
		toshow=allopts[1].split('|');
		document.getElementById('row_'+str_field+'state').className='edit_row invisible';
		document.getElementById('row_'+str_field+'city').className='edit_row invisible';
		document.getElementById('row_'+str_field+'zip').className='edit_row invisible';
		for (i=0;i<toshow.length;i++) {
			document.getElementById('row_'+toshow[i]).className='edit_row visible';
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

function check_form(theform) {
	return true;
}
