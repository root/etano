function check_form() {
	the=document.getElementById('profile_categories_addedit');
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
