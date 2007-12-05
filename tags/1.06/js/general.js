$(function() {
	// IE hack: hover for buttons
	if($.browser.msie) {
		$('input.large, input.medium, input.small').hover(function() {
			$(this).css('background-position','bottom');
		},function() {
			$(this).css('background-position','top');
		});
	}

	$('a[@rel=external]').attr('target','_blank');

	last_ka_check=new Date().getTime();
	if (typeof(in_admin)=='undefined') {
		window.setTimeout("keep_alive();",270000);
	}
});

function keep_alive() {
	var cur_time=new Date().getTime();
	if (baseurl && cur_time-last_ka_check>240000) {
		var tempImage=new Image();
		tempImage.src=baseurl+"/ajax/keepalive.php?"+cur_time;
		last_ka_check=cur_time;
	}
	window.setTimeout("keep_alive();",270000);
}

/*
 * Allows only numbers to be entered into input boxes.
 *
 * @name     numeric
 * @param    use_dec      boolean value to allow/disallow using the decimal separator
 * @param    decimal      Decimal separator (e.g. '.' or ',' - default is '.')
 * @param    callback     A function that runs if the number is not valid (fires onblur)
 * @author   Sam Collett (http://www.texotela.co.uk)
 * @example  $(".numeric").numeric();
 * @example  $(".numeric").numeric(true,",");
 * @example  $(".numeric").numeric(false,null, callback);
 *
 */
jQuery.fn.numeric = function(use_dec,decimal, callback)
{
/*
 *
 * Copyright (c) 2006 Sam Collett (http://www.texotela.co.uk)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */

	decimal = decimal || ".";
	callback = typeof callback == "function" ? callback : function(){};
	this.keypress(
		function(e)
		{
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			// allow enter/return key (only when in an input box)
			if(key == 13 && this.nodeName.toLowerCase() == "input")
			{
				return true;
			}
			else if(key == 13)
			{
				return false;
			}
			var allow = false;
			// allow Ctrl+A
			if((e.ctrlKey && key == 97 /* firefox */) || (e.ctrlKey && key == 65) /* opera */) return true;
			// allow Ctrl+X (cut)
			if((e.ctrlKey && key == 120 /* firefox */) || (e.ctrlKey && key == 88) /* opera */) return true;
			// allow Ctrl+C (copy)
			if((e.ctrlKey && key == 99 /* firefox */) || (e.ctrlKey && key == 67) /* opera */) return true;
			// allow Ctrl+Z (undo)
			if((e.ctrlKey && key == 122 /* firefox */) || (e.ctrlKey && key == 90) /* opera */) return true;
			// allow or deny Ctrl+V (paste), Shift+Ins
			if((e.ctrlKey && key == 118 /* firefox */) || (e.ctrlKey && key == 86) /* opera */
			|| (e.shiftKey && key == 45)) return true;
			// if a number was not pressed
			if(key < 48 || key > 57)
			{
				/* '-' only allowed at start */
				if(key == 45 && this.value.length == 0) return true;
				/* only one decimal separator allowed */
				if(key == decimal.charCodeAt(0) && (!use_dec || this.value.indexOf(decimal) != -1))
				{
					allow = false;
				}
				// check for other keys that have special purposes
				if(
					key != 8 /* backspace */ &&
					key != 9 /* tab */ &&
					key != 13 /* enter */ &&
					key != 35 /* end */ &&
					key != 36 /* home */ &&
					key != 37 /* left */ &&
					key != 39 /* right */ &&
					key != 46 /* del */
				)
				{
					allow = false;
				}
				else
				{
					// for detecting special keys (listed above)
					// IE does not support 'charCode' and ignores them in keypress anyway
					if(typeof e.charCode != "undefined")
					{
						// special keys have 'keyCode' and 'which' the same (e.g. backspace)
						if(e.keyCode == e.which && e.which != 0)
						{
							allow = true;
						}
						// or keyCode != 0 and 'charCode'/'which' = 0
						else if(e.keyCode != 0 && e.charCode == 0 && e.which == 0)
						{
							allow = true;
						}
					}
				}
				// if key pressed is the decimal and it is not already in the field
				if(key == decimal.charCodeAt(0) && use_dec && this.value.indexOf(decimal) == -1)
				{
					allow = true;
				}
			}
			else
			{
				allow = true;
			}
			return allow;
		}
	)
	.blur(
		function()
		{
			var val = jQuery(this).val();
			if(val != "")
			{
				var re = new RegExp("^\\d+$|\\d*" + decimal + "\\d+");
				if(!re.exec(val))
				{
					callback.apply(this);
				}
			}
		}
	)
	return this;
}

function is_alphanum(str) {
	patn=/^[a-zA-Z0-9_]+$/;
	if (patn.test(str)) {
		return true;
	}
	return false;
}


function validate_user(str) {
	if (!is_alphanum(str) || !checkByteLength(str,4,20)) {
		return false;
	}
	return true;
}


function checkByteLength(str,minlen,maxlen) {
	if (str==null) {
		return false;
	}
	var l=str.length;
	var blen=0;
	for(i=0;i<l;i++) {
		if ((str.charCodeAt(i)&0xff00)!=0) {
			blen++;
		}
		blen ++;
	}
	if (blen>maxlen || blen<minlen) {
		return false;
	}
	return true;
}


function validate_email(str) {
	var patn=/^[_a-zA-Z0-9\-\.]+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)+$/;
	if (patn.test(str)) {
		return true;
	}
	return false;
}

function popWin(script,winname,wwidth,wheight,wresizable,wscrollbars,wmenubar,wtoolbar,wstatus,wlocation,wtop,wleft) {
	strWindowFeatures="width="+wwidth+",height="+wheight;
	if (wresizable && (wresizable=='yes' || wresizable==1)) {
		strWindowFeatures+=',resizable=yes';
	}
	if (wscrollbars && (wscrollbars=='yes' || wscrollbars==1)) {
		strWindowFeatures+=',scrollbars=yes';
	}
	if (wmenubar && (wmenubar=='yes' || wmenubar==1)) {
		strWindowFeatures+=',menubar=yes';
	}
	if (wtoolbar && (wtoolbar=='yes' || wtoolbar==1)) {
		strWindowFeatures+=',toolbar=yes';
	}
	if (wstatus && (wstatus=='yes' || wstatus==1)) {
		strWindowFeatures+=',status=yes';
	}
	if (wlocation && (wlocation=='yes' || wlocation==1)) {
		strWindowFeatures+=',location=yes';
	}
	if (wtop) {
		strWindowFeatures+=',screenX='+wtop+',top='+wtop;
	}
	if (wleft) {
		strWindowFeatures+=',screenY='+wleft+',left='+wleft;
	}
	newPopup=window.open(script,winname,strWindowFeatures);
	newPopup.focus();
}

/* mmmm...cookies :) */
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	} else {
		var expires = "";
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
