$(function() {
	$('a[@id^=link_]').bind('click',function() {
		myid=$(this).attr('id').substr(5);
		set_tpl(myid);
	});
});

function set_tpl(theid) {
	opener.$('#subject').val(unescape(subjects[theid]));
	opener.$('#message_body').val(unescape(message_bodies[theid]));
	window.close();
}
