$(function() {
	$('#zipcode').focus();

	$('#zip_form').bind('submit',function() {
		return check_form(this);
	});
});

function check_form(the) {
	return true;
}
