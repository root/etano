$(function() {
	$('div.row a.link_delete').bind('click',function() {
		return(confirm('Are you sure you want to delete this filter?'));
	});
});
