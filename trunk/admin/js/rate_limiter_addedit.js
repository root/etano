$(function() {
	$('#m_value').focus();
	$('#limit, #interval').numeric();
});

function check_form(the) {
	if (the.limit.value=='' || the.limit.value==0) {
		alert('Please enter the number of times allowed for this limit.');
		the.limit.focus();
		return false;
	}
	if (the.interval.value=='' || the.interval.value==0) {
		alert('Please enter the interval of time in minutes for this limit.');
		the.interval.focus();
		return false;
	}
	return true;
}
