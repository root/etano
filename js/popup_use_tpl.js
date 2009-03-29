$(function() {
	$('a[id^=link_]').bind('click',function() {
		myid=$(this).attr('id').substr(5);
		set_tpl(myid);
		// thickbox
		parent.tb_remove();
		return false;
	});
});

function set_tpl(theid) {
	parent.$('#subject').val(unescape(decodeURI(subjects[theid])));
	parent.$('#message_body').val(unescape(decodeURI(message_bodies[theid])));
	window.close();
}
