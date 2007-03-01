$(function() {
	$('#subject')[0].focus();
	$('#save_tpl').bind('click',function() {
		save_as_template();
	});
});

function add_bbcode(tagStart,tagEnd,ta) {
	if (typeof(ta.caretPos)!='undefined' && ta.createTextRange) {
		caretPos=ta.caretPos;
		caretPos.text=tagStart+caretPos.text+tagEnd;
		caretPos.select();
	} else if (typeof(ta.selectionStart)!='undefined') {
		before=ta.value.substr(0,ta.selectionStart);
		selection=ta.value.substr(ta.selectionStart,ta.selectionEnd-ta.selectionStart);
		after=ta.value.substr(ta.selectionEnd);
		newCursorPos=ta.selectionStart;
		scrollPos=ta.scrollTop;
		ta.value=before+tagStart+selection+tagEnd+after;

		if (ta.setSelectionRange) {
			if (selection.length==0) {
				ta.setSelectionRange(newCursorPos+tagStart.length,newCursorPos+tagStart.length);
			} else {
				ta.setSelectionRange(newCursorPos,newCursorPos+tagStart.length+selection.length+tagEnd.length);
			}
			ta.focus();
		}
		ta.scrollTop = scrollPos;
	} else {
		ta.value+=tagStart+tagEnd;
		ta.focus(ta.value.length-1);
	}
}

function save_as_template() {
	theform=$('#msend_form')[0];
	if (check_form(theform)) {
		qs='subject='+escape(theform.subject.value)+'&message_body='+escape(theform.message_body.value);
		r=new Scoax();
		r.sendRequest('POST','ajax/save_user_tpl.php',qs,finish_save);
	}
}

function finish_save(data) {
	if (data==1) {
		alert('Message saved as template');
	} else if (data==2) {
		alert('You are not allowed to save message templates');
	}
}

function check_form(theform,silent_mode) {
	if (theform.subject.value=='') {
		if (!silent_mode) {
			alert('Please enter a subject');
			theform.subject.focus();
		}
		return false;
	}
	if (theform.message_body.value=='') {
		if (!silent_mode) {
			alert('Please enter the message');
			theform.message_body.focus();
		}
		return false;
	}
	return true;
}
