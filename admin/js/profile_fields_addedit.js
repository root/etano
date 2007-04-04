$(function() {
// for selects or checkboxes display the list of values
	if (html_type==3 || html_type==10) {
		update_list();
	}

	if ($('#pfield_id').val()=='' || $('#pfield_id').val()=='0') {
		$('a.translate').hide();
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

// only numbers allowed in these fields
	$('#reg_page').numeric();
	$('#year_start').numeric();
	$('#year_end').numeric();
	$('#def_start').numeric();
	$('#def_end').numeric();

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
	stval=$('#searchable')[0].checked ? $('#search_type').val() : 0;
	if (stval==3 && default_search.length>1) {	// HTML_SELECT
		default_search=default_search.slice(0,1);
	}
	if (stval==108 && default_search.length>2) {	// HTML_INTERVAL
		default_search=default_search.slice(0,2);
	}
	for (i=0;i<accvals.length;i++) {
		towrite+='<li><ul class="litem_tools"><li><a class="item_edit" href="javascript:;" onclick="addedit_accval(\'edit\','+i+')" title="Edit value"><i>Edit</i></a></li><li><a class="item_add" href="javascript:;" onclick="addedit_accval(\'add\','+(i+1)+')" title="Add new value after this one"><i>Add new value after this one</i></a></li><li><a class="item_del" href="javascript:;" onclick="delete_accval('+i+')" title="Delete value"><i>Delete value</i></a></li>';
		if (html_type==3) {	// HTML_SELECT
			towrite+='<li><input type="radio" name="default_value[]" id="default_value_'+i+'" value="'+i+'" title="Default value" onclick="adddel_defval(true,'+i+')"';
			for (j=0;j<default_value.length;j++) {
				if (parseInt(default_value[j])==i) {
					towrite+=' checked="checked"';
					break;
				}
			}
			towrite+=' /></li>';
		} else if (html_type==10 || html_type==108) {	// HTML_CHECKBOX_LARGE || HTML_INTERVAL
			towrite+='<li><input type="checkbox" name="default_value[]" id="default_value_'+i+'" value="'+i+'" title="Default value" onclick="adddel_defval(this.checked,'+i+')"';
			for (j=0;j<default_value.length;j++) {
				if (parseInt(default_value[j])==i) {
					towrite+=' checked="checked"';
					break;
				}
			}
			towrite+=' /></li>';
		}
// the search checkbox/radio depends on the search_type:
		if (stval==3) {	// HTML_SELECT
			towrite+='<li><input type="radio" name="default_search[]" id="default_search_'+i+'" value="'+i+'" title="Default search value" onclick="adddel_defsearch(true,'+i+')"';
			for (j=0;j<default_search.length;j++) {
				if (parseInt(default_search[j])==i) {
					towrite+=' checked="checked"';
					break;
				}
			}
			towrite+=' /></li>';
		} else if (stval==10) {	// HTML_CHECKBOX_LARGE
			towrite+='<li><input type="checkbox" name="default_search[]" id="default_search_'+i+'" value="'+i+'" title="Default search value" onclick="adddel_defsearch(this.checked,'+i+')"';
			for (j=0;j<default_search.length;j++) {
				if (parseInt(default_search[j])==i) {
					towrite+=' checked="checked"';
					break;
				}
			}
			towrite+=' /></li>';
		} else if (stval==108) {	// HTML_INTERVAL
			towrite+='<li><input type="checkbox" name="default_search[]" id="default_search_'+i+'" value="'+i+'" title="Default search value" onclick="adddel_defsearch(this.checked,'+i+')"';
			for (j=0;j<default_search.length;j++) {
				if (parseInt(default_search[j])==i) {
					towrite+=' checked="checked"';
					break;
				}
			}
			towrite+=' /></li>';
		}
		towrite+='</ul><span class="litem_text">'+accvals[i]+'</span></li>'+"\n";
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
					$('#default_value_'+i)[0].checked=false;
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
	stval=$('#search_type').val();
	if (stval==3) {	//HTML_SELECT
		default_search[0]=position.toString();
	} else if (stval==10) { 	//HTML_CHECKBOX_LARGE
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
	} else if (stval==108) { 	//HTML_INTERVAL
		if (type) {	// add here
			doadd=true;
			for (i=0;i<default_search.length;i++) {
				if (parseInt(default_search[i])==position) {
					doadd=false;
					break;
				}
			}
			if (doadd) {
				if (default_search.length<2) {
					default_search[default_search.length]=position.toString();
				} else {
					$('#default_search_'+position)[0].checked=false;
				}
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

