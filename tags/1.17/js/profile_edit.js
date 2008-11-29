$(function() {
	$('#edit_form input:visible:first').focus();
});

/*
function field_empty(dbfield,field_type,form_id) {
	myreturn=false;
	if (field_type==104 || field_type==105) {	// FIELD_INT,FIELD_FLOAT
		if ($('#'+dbfield)[0].value==0) {
			myreturn=true;
		}
	}
	return myreturn;
}
*/