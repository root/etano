$(function() {
	$('#pcat_name').focus();
	$('#profile_categories_addedit').bind('submit',function() {
		return(check_form());
	});

	if ($('#pcat_id').val()=='' || $('#pcat_id').val()=='0') {
		$('a.translate').hide();
	}
});

function check_form() {
	the=$('#profile_categories_addedit')[0];
	if (the.pcat_name.value=='') {
		alert('Please enter the category!');
		the.pcat_name.focus();
		return false;
	}
	return true;
}

function check_uncheck(the) {
	for (var i=0;i<the.elements.length;i++) {
		var e=the.elements[i];
		if (e.name!='check_all' && e.name!='dummy' && e.type=='checkbox') {
			e.checked=the.check_all.checked;
		}
	}
}
