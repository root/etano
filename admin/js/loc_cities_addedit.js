$(function() {
	$('#city').focus();

	$('#loc_cities_addedit').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	return true;
}
