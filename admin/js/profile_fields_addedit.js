$(function() {
// for selects or checkboxes display the list of values
	if (html_type==3 || html_type==10) {
		update_list();
	}

// show or hide dependant options and bind their display to the triggering fields.
	if ($('#search_type')[0] && $('#search_type')[0].length>0) {
		showhide('searchable','row_st');
	} else if ($('#row_st')[0]) {
		$('#row_st').hide();
	}
	showhide('searchable','row_sl');
	showhide('at_registration','row_reg_page');
	$('#label').focus();
	$('#at_registration').bind('change',function() {
		showhide('at_registration','row_reg_page');
	});
	$('#searchable').bind('change',function() {
		if ($('#search_type')[0].length>0) {
			showhide('searchable','row_st');
		}
		showhide('searchable','row_sl');
		update_list();
	});

	$('#search_type').bind('change',function() {
		update_list();
	});

// only numbers allowed in this field
	$('#reg_page').numeric();

// tab management
	$('#label').bind('keydown',function(e) {
		if (e.shiftKey && e.keyCode==9) {
			$('#btn_save').focus();
			return false;
		}
	});
	$('#btn_save').bind('keydown',function(e) {
		if (!e.shiftKey && e.keyCode==9) {
			$('#label').focus();
			return false;
		}
	});

	$("#profile_fields_addedit").bind('submit',function() {
		return(check_form());
	});
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
		if ($('#search_type').val()==3) {	// HTML_SELECT
		} else if ($('#search_type').val()==10) {	// HTML_CHECKBOX_LARGE
			towrite+=' <input type="checkbox" name="default_search['+i+']" id="default_search_'+i+'" value="1" title="Default search value" onclick="adddel_defsearch(this.checked,'+i+')"';
			for (j=0;j<default_search.length;j++) {
				if (parseInt(default_search[j])==i) {
					towrite+=' checked="checked"';
					break;
				}
			}
			towrite+=' />';
		}
		towrite+='</span></li>'+"\n";
	}
	$('#litems').html(towrite);
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
			var lk_id=0;
			$.ajax({url:'ajax/field_values.php',
					type:'POST',
					dataType:'html',
					data:'optype=add&val='+myval,
					async:false,
					success:function(data) {
						if (data!=null && data!='' && parseInt(data).toString()==data) {
							lk_id=parseInt(data);
							if (lk_id!=0) {
								accvals=accvals.slice(0,position).concat(new Array(myval)).concat(accvals.slice(position));
								accval_lks=accval_lks.slice(0,position).concat(new Array(lk_id.toString())).concat(accval_lks.slice(position));
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
							}
						}
					}
			});
		} else {	// edit
			err=true;
			$.ajax({url:'ajax/field_values.php',
					type:'POST',
					dataType:'html',
					data:'optype=edit&val='+myval+'&lk_id='+accval_lks[position],
					async:false,
					success:function(data) {
						if (data!=null && data!='' && parseInt(data).toString()==data) {
							accvals[position]=myval;
						}
					}
			});
		}
		$('#accepted_values').val(vector2psv(accval_lks));
		update_list();
	}
}

function delete_accval(position) {
	if (confirm('Are you sure you want to remove "'+accvals[position]+'" from the list of values?')) {
		err=true;
		$.ajax({url:'ajax/field_values.php',
				type:'POST',
				dataType:'html',
				data:'optype=del&lk_id='+accval_lks[position],
				async:false,
				success:function(data) {
					if (data!=null && data!='' && parseInt(data).toString()==data) {
						accvals=accvals.slice(0,position).concat(accvals.slice(position+1));
						accval_lks=accval_lks.slice(0,position).concat(accval_lks.slice(position+1));
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
						$('#accepted_values').val(vector2psv(accval_lks));
						update_list();
					}
				}
		});
	}
}

function adddel_defval(type,position) {
	if (html_type==3) {	//HTML_SELECT
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
	} else if (html_type==10) { 	//HTML_CHECKBOX_LARGE
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
	if ($('#search_type').val()==3) {	//HTML_SELECT
		if (type) {		// add here
			for (i=0;i<accvals.length;i++) {
				if (i!=position) {
					$('#default_search_'+i)[0].checked=false;
				}
			}
			default_search[0]=position;
		} else {		// del here
			default_search=new Array();
		}
	} else if ($('search_type').val()==10) { 	//HTML_CHECKBOX_LARGE
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


function vector2psv(myarray) {
	myreturn='|';
	myreturn+=myarray.join('|');
	myreturn+='|';
	return myreturn;
}


function showhide(strCheck,strHide) {
	if ($('#'+strCheck)[0].checked==true) {
		$('#'+strHide).show();
	} else {
		$('#'+strHide).hide();
	}
}

