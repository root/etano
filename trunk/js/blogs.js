$(function() {
	$('#blog_search').bind('submit',function() {
		return(check_form($(this)[0]));
	});
});

function check_form(the) {
	if (the.tags.value=='') {
		alert('Please enter a search term');
		return false;
	}
	return true;
}
