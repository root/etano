$(function() {
	ta=$('#message_body')[0];
	if (typeof(ta.caretPos)!='undefined') {
		ta.caretPos=0;
	} else if (typeof(ta.selectionStart)!='undefined') {
		ta.selectionStart=0;
		ta.selectionEnd=0;
	} else {
		ta.focus(0);
	}
	$('#subject')[0].focus();
	$('#btn_savetpl').bind('click',function() {
		save_as_template();
		return false;	// important! do not bubble
	});

	$('#msend_form').bind('submit',function() {
		return check_form(this);
	});
});

function save_as_template() {
	theform=$('#msend_form')[0];
	if (check_form(theform)) {
		$.post(baseurl+'/ajax/save_user_tpl.php',
				{'subject':escape(theform.subject.value),'message_body':escape(theform.message_body.value)},
				function(data) {
					if (data==1) {
						alert(lang[0]);
					} else if (data==2) {
						alert(lang[1]);
					}
				}
		);
	}
}

function check_form(theform,silent_mode) {
	if (theform.subject.value=='') {
		if (!silent_mode) {
			alert(lang[2]);
			theform.subject.focus();
		}
		return false;
	}
	if (theform.message_body.value=='') {
		if (!silent_mode) {
			alert(lang[3]);
			theform.message_body.focus();
		}
		return false;
	}
	return true;
}
