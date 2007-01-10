$(function() {
	if (html_type==3 || html_type==10) {
		update_list();
	}
});

function check_form() {
	return confirm('Are you sure you want to save your changes?');
}

function update_list() {
	towrite='';
	for (i=0;i<accvals.length;i++) {
		towrite+='<li><span class="litem_text">'+accvals[i]+'</span> <span class="litem_tools"><a href="javascript:;" onclick="addedit_accval(\'edit\','+i+')" title="Edit value"><img src="skin/images/edit.gif" alt="Edit value" /></a>&nbsp; &nbsp;<a href="javascript:;" onclick="addedit_accval(\'add\','+(i+1)+')" title="Add new value after this one"><img src="skin/images/add.gif" alt="Add new value after this one" /></a>&nbsp; &nbsp;<a href="javascript:;" onclick="delete_accval('+i+')" title="Delete value"><img src="skin/images/del.gif" alt="Delete value" /></a>';
		towrite+=' <input type="checkbox" name="default_value['+i+']" id="default_value_'+i+'" value="1" title="Default value" onclick="adddel_defval(this.checked,'+i+')"';
		for (j=0;j<default_value.length;j++) {
			if (parseInt(default_value[j])==i) {
				towrite+=' checked="checked"';
				break;
			}
		}
		towrite+=' />';
		towrite+=' <input type="checkbox" name="default_search['+i+']" id="default_search_'+i+'" value="1" title="Default search value" onclick="adddel_defsearch(this.checked,'+i+')"';
		for (j=0;j<default_search.length;j++) {
			if (parseInt(default_search[j])==i) {
				towrite+=' checked="checked"';
				break;
			}
		}
		towrite+='</span></li>';
	}
	document.getElementById('litems').innerHTML=towrite;
}

function adddel_defval(type,position) {
	if (html_type==3) {	//_HTML_SELECT_
		if (type) {		// add here
			for (i=0;i<accvals.length;i++) {
				if (i!=position) {
					document.getElementById('default_value_'+i).checked=false;
				}
			}
			default_value[0]=position;
		} else {		// del here
			default_value=new Array();
		}
	} else if (html_type==10) { 	//_HTML_CHECKBOX_LARGE_
		if (type) {	// add here
			doadd=true;
			for (i=0;i<default_value.length;i++) {
				if (parseInt(default_value[i])==position) {
					doadd=false;
					break;
				}
			}
			if (doadd) {
				default_value[default_value.length]=position.toString();
			}
		} else {	// del here
			for (i=0;i<default_value.length;i++) {
				if (parseInt(default_value[i])==position) {
					default_value=default_value.slice(0,i).concat(default_value.slice(i+1));
					break;
				}
			}
		}
	}
}

function adddel_defsearch(type,position) {
	if (document.getElementById('search_type').value==3) {	//_HTML_SELECT_
		if (type) {		// add here
			for (i=0;i<accvals.length;i++) {
				if (i!=position) {
					document.getElementById('default_search_'+i).checked=false;
				}
			}
			default_search[0]=position;
		} else {		// del here
			default_search=new Array();
		}
	} else if (document.getElementById('search_type').value==10) { 	//_HTML_CHECKBOX_LARGE_
		if (type) {	// add here
			doadd=true;
			for (i=0;i<default_search.length;i++) {
				if (parseInt(default_search[i])==position) {
					doadd=false;
					break;
				}
			}
			if (doadd) {
				default_search[default_search.length]=position.toString();
			}
		} else {	// del here
			for (i=0;i<default_search.length;i++) {
				if (parseInt(default_search[i])==position) {
					default_search=default_search.slice(0,i).concat(default_search.slice(i+1));
					break;
				}
			}
		}
	}
}

function addedit_accval(optype,position) {
	defval='';
	if (optype=='edit') {
		defval=accvals[position];
	}
	badfield=true;
	while (badfield) {
		myval=prompt('Please enter the new value. DO NOT use the \'|\' character!',defval);
		if (!myval || myval=='') {
			return false;
		}
		if (myval.indexOf('|')<0) {
			badfield=false;
		}
	}
	myval=myval.replace(/</g,'&lt;');
	myval=myval.replace(/>/g,'&gt;');

	if (myval) {
		if (optype=='add') {
			accvals=accvals.slice(0,position).concat(new Array(myval)).concat(accvals.slice(position));
			for (i=0;i<default_value.length;i++) {
				if (parseInt(default_value[i])>=position) {
					default_value[i]=(parseInt(default_value[i])+1).toString();
				}
			}
			for (i=0;i<default_search.length;i++) {
				if (parseInt(default_search[i])>=position) {
					default_search[i]=(parseInt(default_search[i])+1).toString();
				}
			}
		} else {	// edit
			accvals[position]=myval;
		}
		document.getElementById('accepted_values').value=vector2psv(accvals);
		update_list();
	}
}

function delete_accval(position) {
	if (confirm('Are you sure you want to remove "'+accvals[position]+'" from the list of values?')) {
		accvals=accvals.slice(0,position).concat(accvals.slice(position+1));
		adddel_defval(false,position);
		for (i=0;i<default_value.length;i++) {
			if (parseInt(default_value[i])>=position) {
				default_value[i]=(parseInt(default_value[i])-1).toString();
			}
		}
		for (i=0;i<default_search.length;i++) {
			if (parseInt(default_search[i])>=position) {
				default_search[i]=(parseInt(default_search[i])-1).toString();
			}
		}
		document.getElementById('accepted_values').value=vector2psv(accvals);
		update_list();
		return true;
	} else {
		return false;
	}
}

function vector2psv(myarray) {
	myreturn='|';
	myreturn+=myarray.join('|');
	myreturn+='|';
	return myreturn;
}

function manage_keydown(e) {
	var e = (e) ? e : ((event) ? event : null);
	if (!e.originalTarget && e.srcElement) e.originalTarget=e.srcElement;
	mykey=(e.which) ? e.which : ((e.keyCode) ? e.keyCode : null);
	if (e.originalTarget.id=='reg_page') {	// only numbers allowed
		if (e.keyCode==null || e.keyCode==0 || e.keyCode==8 || e.keyCode==9 || e.keyCode==27 || e.keyCode==35 || e.keyCode==36 || e.keyCode==37 || e.keyCode==39 || e.keyCode==46) {
			return true;
		} else if (mykey>=48 && mykey<=57) { // numbers
			return true;
		} else {
			return false;
		}
	} else if (e.originalTarget.id=='label') {
		if (e.shiftKey && e.keyCode==9) {
			document.getElementById('btn_save').focus();
			return false;
		}
	} else if (e.originalTarget.id=='btn_save') {
		if (!e.shiftKey && e.keyCode==9) {
			document.getElementById('label').focus();
			return false;
		}
	}
	return true;
}

function manage_change(e) {
	var e = (e) ? e : ((event) ? event : null);
	if (!e.originalTarget && e.srcElement) e.originalTarget=e.srcElement;
	formchanged=true;

	if (e.originalTarget.id=='at_registration') {
		showhide('at_registration','row_reg_page');
	} else if (e.originalTarget.id=='searchable') {
		if (document.getElementById('search_type').length>0) {
			showhide('searchable','row_st');
		}
		showhide('searchable','row_sl');
	}
}

function showhide(strCheck,strHide) {
	if (document.getElementById(strCheck).checked==true) {
		$("#"+strHide).show();
	} else {
		$("#"+strHide).hide();
	}
}

