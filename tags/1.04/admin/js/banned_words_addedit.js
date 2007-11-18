$(function() {
	$('#words_form').bind('submit',function() {
		return check_form(this);
	});

	$('#word').focus();
});

function check_form(the) {
	if (the.word.value=='') {
		alert('Please enter the word');
		the.word.focus();
		return false;
	}
	return true;
}
