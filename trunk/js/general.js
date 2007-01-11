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

function numbersonly(myfield,e,dec) {
	var key;
	var keychar;
	if (window.event) {
		key = window.event.keyCode;
	} else if (e) {
		key = e.which;
	} else {
		return true;
	}
	keychar = String.fromCharCode(key);
	// control keys
	if (key==null || key==0 || key==8 || key==9 || key==13 || key==27) {
		return true;
	} else if ((("0123456789").indexOf(keychar)>-1)) { // numbers
		return true;
	} else if (dec && keychar=="." && myfield.value.indexOf(".")==-1) { // decimal point
		return true;
	} else {
		return false;
	}
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

function addEvent(obj,evType,fn) {
	if (obj.addEventListener) {
		obj.addEventListener(evType,fn,true);
		return true;
	} else if (obj.attachEvent) {
		var r=obj.attachEvent("on"+evType, fn);
		return r;
	} else {
		return false;
	}
}
