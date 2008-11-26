$(function() {
	$('.sco_bbcode').sco_bbcode();
});

jQuery.fn.sco_bbcode = function() {
	$(this).each(function() {
		var ta=$(this);
		ta.before('<div class="bb_tools" id="bbmenu_'+ta.attr('id')+'"><a href="#" class="bold" title="Bold">Bold</a><a href="#" class="underline" title="Underline">Underline</a><a href="#" class="hyperlink" title="Hyperlink">Hyperlink</a><a href="#" class="quote" title="Quote">Quote</a><a href="#" class="smileys" title="smileys">Smileys</a></div>');
		$('#bbmenu_'+ta.attr('id')+' .bold').click(function() {
			var my_ta=$(this).parents('.bb_tools').attr('id').substr(7);
			add_bbcode2('b',$('#'+my_ta));
			return false;
		});
		$('#bbmenu_'+ta.attr('id')+' .underline').click(function() {
			var my_ta=$(this).parents('.bb_tools').attr('id').substr(7);
			add_bbcode2('u',$('#'+my_ta));
			return false;
		});
		$('#bbmenu_'+ta.attr('id')+' .hyperlink').click(function() {
			var my_ta=$(this).parents('.bb_tools').attr('id').substr(7);
			add_bbcode2('url',$('#'+my_ta));
			return false;
		});
		$('#bbmenu_'+ta.attr('id')+' .quote').click(function() {
			var my_ta=$(this).parents('.bb_tools').attr('id').substr(7);
			add_bbcode2('quote',$('#'+my_ta));
			return false;
		});
		$('#bbmenu_'+ta.attr('id')+' .smileys').click(function() {
			var my_ta=$(this).parents('.bb_tools').attr('id').substr(7);
//			if (!$('.smiley_set')[0]) {
				var smiley_set='<div class="smiley_set"><table><tbody>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="#" title="angry" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'&gt;:(\'))"><img src="'+baseurl+'/images/emoticons/angry.gif" alt="angry" /></a></td>';
				smiley_set+='<td><a href="#" title="big grin" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':D\'))"><img src="'+baseurl+'/images/emoticons/biggrin.gif" alt="big grin" /></a></td>';
				smiley_set+='<td><a href="#" title="blink" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'o.O\'))"><img src="'+baseurl+'/images/emoticons/blink.gif" alt="blink" /></a></td>';
				smiley_set+='<td><a href="#" title="censor" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'|o\'))"><img src="'+baseurl+'/images/emoticons/censor.gif" alt="censor" /></a></td>';
				smiley_set+='<td><a href="#" title="closed eyes" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'-.-\'))"><img src="'+baseurl+'/images/emoticons/closedeyes.gif" alt="closed eyes" /></a></td>';
				smiley_set+='<td><a href="#" title="cool" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'8)\'))"><img src="'+baseurl+'/images/emoticons/cool.gif" alt="cool" /></a></td>';
				smiley_set+='<td><a href="#" title="cry" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':~(\'))"><img src="'+baseurl+'/images/emoticons/cry.gif" alt="cry" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="#" title="devil" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'&gt;:)\'))"><img src="'+baseurl+'/images/emoticons/devil.gif" alt="devil" /></a></td>';
				smiley_set+='<td><a href="#" title="doh" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':doh:\'))"><img src="'+baseurl+'/images/emoticons/doh.gif" alt="doh" /></a></td>';
				smiley_set+='<td><a href="#" title="dry" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'&lt;.&lt;\'))"><img src="'+baseurl+'/images/emoticons/dry.gif" alt="dry" /></a></td>';
				smiley_set+='<td><a href="#" title="grrrr" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':grr:\'))"><img src="'+baseurl+'/images/emoticons/grrrr.gif" alt="grrrr" /></a></td>';
				smiley_set+='<td><a href="#" title="happy" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\'^,^\'))"><img src="'+baseurl+'/images/emoticons/happy.gif" alt="happy" /></a></td>';
				smiley_set+='<td><a href="#" title="holy" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':h:\'))"><img src="'+baseurl+'/images/emoticons/holy.gif" alt="holy" /></a></td>';
				smiley_set+='<td><a href="#" title="huh" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':huh:\'))"><img src="'+baseurl+'/images/emoticons/huh.gif" alt="huh" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="#" title="laugh" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':lol:\'))"><img src="'+baseurl+'/images/emoticons/laugh.gif" alt="laugh" /></a></td>';
				smiley_set+='<td><a href="#" title="lips" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':x\'))"><img src="'+baseurl+'/images/emoticons/lips.gif" alt="lips" /></a></td>';
				smiley_set+='<td><a href="#" title="mellow" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':,\'))"><img src="'+baseurl+'/images/emoticons/mellow.gif" alt="mellow" /></a></td>';
				smiley_set+='<td><a href="#" title="ohmy" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':O\'))"><img src="'+baseurl+'/images/emoticons/ohmy.gif" alt="ohmy" /></a></td>';
				smiley_set+='<td><a href="#" title="rolleyes" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':r:\'))"><img src="'+baseurl+'/images/emoticons/rolleyes.gif" alt="rolleyes" /></a></td>';
				smiley_set+='<td><a href="#" title="sad" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':(\'))"><img src="'+baseurl+'/images/emoticons/sad.gif" alt="sad" /></a></td>';
				smiley_set+='<td><a href="#" title="smile" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':)\'))"><img src="'+baseurl+'/images/emoticons/smile.gif" alt="smile" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='<tr>';
				smiley_set+='<td><a href="#" title="thumbsup" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':t:\'))"><img src="'+baseurl+'/images/emoticons/thumbsup.gif" alt="thumbsup" /></a></td>';
				smiley_set+='<td><a href="#" title="tongue" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':P\'))"><img src="'+baseurl+'/images/emoticons/tongue.gif" alt="tongue" /></a></td>';
				smiley_set+='<td><a href="#" title="unsure" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':u\'))"><img src="'+baseurl+'/images/emoticons/unsure.gif" alt="unsure" /></a></td>';
				smiley_set+='<td><a href="#" title="wacko" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':w:\'))"><img src="'+baseurl+'/images/emoticons/wacko.gif" alt="wacko" /></a></td>';
				smiley_set+='<td><a href="#" title="whistling" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':.\'))"><img src="'+baseurl+'/images/emoticons/whistling.gif" alt="whistling" /></a></td>';
				smiley_set+='<td><a href="#" title="wink" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\';)\'))"><img src="'+baseurl+'/images/emoticons/wink.gif" alt="wink" /></a></td>';
				smiley_set+='<td><a href="#" title="yay" onclick="return(add_bbcode2(\'smiley\',$(\'#'+my_ta+'\'),\':!:\'))"><img src="'+baseurl+'/images/emoticons/yay.gif" alt="yay" /></a></td>';
				smiley_set+='</tr>';
				smiley_set+='</tbody></table></div>';
				$(smiley_set).appendTo($('#bbmenu_'+my_ta));
//			}
			$('#'+my_ta+' .smiley_set').toggle();
			return false;
		});
	});
}

function add_bbcode2(tag,ta,smil) {
	if (typeof ta=='string') {
		ta=$('#'+ta);
	}
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
		$('.smiley_set').hide();
	}
	if (document.selection && document.selection.createRange) {		// IE
		ta.focus();
		var range=document.selection.createRange();
		var range_copy=range.duplicate();
		range_copy.moveToElementText(ta[0]);
		range_copy.setEndPoint( 'EndToEnd', range );
		ta[0].selectionStart=range_copy.text.length - range.text.length;
		ta[0].selectionEnd = ta[0].selectionStart + range.text.length;
	}
	if (typeof(ta[0].selectionStart)!='undefined') {
		before=ta.val().substr(0,ta[0].selectionStart);
		selection=ta.val().substr(ta[0].selectionStart,ta[0].selectionEnd-ta[0].selectionStart);
		after=ta.val().substr(ta[0].selectionEnd);
		newCursorPos=ta[0].selectionStart;
		scrollPos=ta[0].scrollTop;
		ta.val(before+tagStart+selection+tagEnd+after);
		if (ta[0].setSelectionRange) {
			if (selection.length==0) {
				ta[0].setSelectionRange(newCursorPos+tagStart.length,newCursorPos+tagStart.length);
			} else {
				ta[0].setSelectionRange(newCursorPos,newCursorPos+tagStart.length+selection.length+tagEnd.length);
			}
			ta.focus();
		}
		if (document.selection && document.selection.createRange) {		// IE again - move the cursor after the closing tag
			var range=document.selection.createRange();
			range.moveToElementText(ta[0]);
			range.move('character',ta[0].selectionEnd+tagStart.length+tagEnd.length);
			range.select();
		}
		ta[0].scrollTop = scrollPos;
	} else {
		ta[0].value+=tagStart+tagEnd;
		ta[0].focus(ta.val().length-tagEnd.length);
	}
	return false;
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
