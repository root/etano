function set_tpl(theid) {
	opener.document.getElementById('subject').value=document.getElementById('subject_'+theid).innerHTML;
	opener.document.getElementById('message_body').value=document.getElementById('mbody_'+theid).innerHTML;
	window.close();
}
