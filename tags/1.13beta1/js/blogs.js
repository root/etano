$(function() {
	$('#blog_search').bind('submit',function() {
		return(check_form(this));
	});
});

function check_form(the) {
	if (the.tags.value=='') {
		alert(lang[0]);
		return false;
	}
	return true;
}
