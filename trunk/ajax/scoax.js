function Scoax() {
	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;

	this.getXMLHTTPRequest=function() {
		var req;
		try {
			req=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e)	{
			try	{
				req=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e2) {
				req=null;
			}
		}
		if(!req && typeof XMLHttpRequest!="undefined") {
			req = new XMLHttpRequest();
		}
		return req;
	}

	this.sendRequest=function(method,url,qs,orsc_callback) {
		var req=this.getXMLHTTPRequest();
		if (req) {
			method=method.toUpperCase();
			completed=false;
			try {
				req.onreadystatechange=function() {
					if (req.readyState==READY_STATE_COMPLETE && !completed) {
						completed=true;
						if (req.status==200) {
							var mydata=req.responseText;
							if (orsc_callback) {
								orsc_callback(mydata);
							}
						}
					}
				}
				if (method=='GET') {
					req.open('GET',url+'?'+qs,true);
					qs=null;
				} else {
					req.open('POST',url,true);
					req.setRequestHeader('Method', 'POST '+url+' HTTP/1.1');
					req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				}
				req.send(qs);
			} catch (z) {
				return false;
			}
		}
	}
}
