$(function() {
	if (typeof(bbcode_field_id)!='undefined') {
		$('#bb_tools #bold').click(function() {
			add_bbcode2('b',$(bbcode_field_id)[0]);
			return false;
		});
		$('#bb_tools #underline').click(function() {
			add_bbcode2('u',$(bbcode_field_id)[0]);
			return false;
		});
		$('#bb_tools #hyperlink').click(function() {
			add_bbcode2('url',$(bbcode_field_id)[0]);
			return false;
		});
		$('#bb_tools #quote').click(function() {
			add_bbcode2('quote',$(bbcode_field_id)[0]);
			return false;
		});
		$('#bb_tools #smileys').click(function() {
			if (!$('#smiley_set')[0]) {
				var smiley_set='<div id="smiley_set"><table><tbody>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="javascript:;" title="angry" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'&gt;:(\')"><img src="images/emoticons/angry.gif" alt="angry" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="big grin" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':D\')"><img src="images/emoticons/biggrin.gif" alt="big grin" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="blink" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'o.O\')"><img src="images/emoticons/blink.gif" alt="blink" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="censor" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'|o\')"><img src="images/emoticons/censor.gif" alt="censor" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="closed eyes" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'-.-\')"><img src="images/emoticons/closedeyes.gif" alt="closed eyes" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="cool" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'8)\')"><img src="images/emoticons/cool.gif" alt="cool" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="cry" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':~(\')"><img src="images/emoticons/cry.gif" alt="cry" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="javascript:;" title="devil" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'&gt;:)\')"><img src="images/emoticons/devil.gif" alt="devil" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="doh" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':doh:\')"><img src="images/emoticons/doh.gif" alt="doh" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="dry" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'&lt;.&lt;\')"><img src="images/emoticons/dry.gif" alt="dry" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="grrrr" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':grr:\')"><img src="images/emoticons/grrrr.gif" alt="grrrr" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="happy" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\'^,^\')"><img src="images/emoticons/happy.gif" alt="happy" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="holy" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':h:\')"><img src="images/emoticons/holy.gif" alt="holy" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="huh" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':huh:\')"><img src="images/emoticons/huh.gif" alt="huh" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="javascript:;" title="laugh" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':lol:\')"><img src="images/emoticons/laugh.gif" alt="laugh" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="lips" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':x\')"><img src="images/emoticons/lips.gif" alt="lips" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="mellow" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':,\')"><img src="images/emoticons/mellow.gif" alt="mellow" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="ohmy" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':O\')"><img src="images/emoticons/ohmy.gif" alt="ohmy" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="rolleyes" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':r:\')"><img src="images/emoticons/rolleyes.gif" alt="rolleyes" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="sad" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':(\')"><img src="images/emoticons/sad.gif" alt="sad" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="smile" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':)\')"><img src="images/emoticons/smile.gif" alt="smile" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="javascript:;" title="thumbsup" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':t:\')"><img src="images/emoticons/thumbsup.gif" alt="thumbsup" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="tongue" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':P\')"><img src="images/emoticons/tongue.gif" alt="tongue" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="unsure" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':u\')"><img src="images/emoticons/unsure.gif" alt="unsure" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="wacko" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':w:\')"><img src="images/emoticons/wacko.gif" alt="wacko" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="whistling" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':.\')"><img src="images/emoticons/whistling.gif" alt="whistling" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="wink" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\';)\')"><img src="images/emoticons/wink.gif" alt="wink" /></a></td>';
				smiley_set+='<td><a href="javascript:;" title="yay" onclick="add_bbcode2(\'smiley\',$(bbcode_field_id)[0],\':!:\')"><img src="images/emoticons/yay.gif" alt="yay" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='</tbody></table></div>';
				$(smiley_set).appendTo('#smileys-wrapper');
			}
			$('#smiley_set').toggle();
			return false;
		});
	}
});

function add_bbcode2(tag,ta,smil) {
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
	} else if (tag=='smiley') {
		tagStart=smil;
		$('#smiley_set').hide();
	}
	if (document.selection && document.selection.createRange) {		// IE
		ta.focus();
		var range=document.selection.createRange();
		var range_copy=range.duplicate();
		range_copy.moveToElementText(ta);
		range_copy.setEndPoint( 'EndToEnd', range );
		ta.selectionStart=range_copy.text.length - range.text.length;
		ta.selectionEnd = ta.selectionStart + range.text.length;
	}
	if (typeof(ta.selectionStart)!='undefined') {
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
		if (document.selection && document.selection.createRange) {		// IE again - move the cursor after the closing tag
			var range=document.selection.createRange();
			range.moveToElementText(ta);
			range.move('character',ta.selectionEnd+tagStart.length+tagEnd.length);
			range.select();
		}
		ta.scrollTop = scrollPos;
	} else {
		ta.value+=tagStart+tagEnd;
		ta.focus(ta.value.length-tagEnd.length);
	}
}


function get_ie_caret_pos(ta) {
	var current_pos = Math.abs(document.selection.createRange().moveEnd("character", -1000000));
	/* Moving cursor position to zero */
	var textRange = ta.createTextRange();
	textRange.move("character", 0);
	textRange.select();
	/* identifying the position at zero index */
	var start_caret_pos = Math.abs(document.selection.createRange().moveEnd("character", -1000000));

	/* actual caret position should be difference between current position and start_position*/
	var caret_pos = current_pos - start_caret_pos;
	textRange.move("character", caret_pos);

	textRange.select();
}