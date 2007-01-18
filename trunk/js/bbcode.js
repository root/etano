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

function add_bbcode2(tag,ta) {
	tagStart='';
	tagEnd='';
	if (tag=='b') {
		tagStart='[b]';
		tagEnd='[/b]';
	} else if (tag=='u') {
		tagStart='[u]';
		tagEnd='[/u]';
	} else if (tag=='quote') {
		tagStart='[quote]';
		tagEnd='[/quote]';
	} else if (tag=='url') {
		url=prompt('Please enter the URL','http://');
		if (!url || url=='') {
			return false;
		} else {
			tagStart='[url='+url+']';
			tagEnd='[/url]';
		}
	} else if (tag=='img') {
		url=prompt('Please enter the image URL','http://');
		if (!url || url=='') {
			return false;
		} else {
			tagStart='[img='+url+']';
			tagEnd='';
		}
	}
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

