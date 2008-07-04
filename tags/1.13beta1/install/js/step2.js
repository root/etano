$(function() {
	$('#test_db').bind('click',function() {
		req_check_db();
		return false;
	});

	$('#test_ftp').bind('click',function() {
		req_check_ftp();
		return false;
	});

	$('#install_form').bind('submit',function() {
		return check_form(this);
	});

	$('#install_form input:visible:first').focus();
});

function req_check_db() {
	var dbhost=$('#dbhost').val();
	var dbuser=$('#dbuser').val();
	var dbpass=$('#dbpass').val();
	var dbname=$('#dbname').val();
	if (check_host(dbhost)) {
		if (dbuser!='' && dbpass!='' && dbname!='') {
			$.post('ajax/check_db.php',
					{'dbhost':dbhost,'dbuser':dbuser,'dbpass':dbpass,'dbname':dbname},
					function(data) {
						if (data!=null && data!='') {
							alert(data);
						}
					}
			);
		} else {
			alert('Please don\'t leave the DB user, password or name empty');
			$('#dbuser').focus();
		}
	}
}

function req_check_ftp() {
	var ftphost=$('#ftphost').val();
	var ftpuser=$('#ftpuser').val();
	var ftppass=$('#ftppass').val();
	var ftppath=$('#ftppath').val();
	if (check_host(ftphost)) {
		if (ftpuser!='' && ftppass!='' && ftppath!='') {
			$.post('ajax/check_ftp.php',
					{'ftphost':ftphost,'ftpuser':ftpuser,'ftppass':ftppass,'ftppath':ftppath},
					function(data) {
						if (data!=null && data!='') {
							alert(data);
						}
					}
			);
		} else {
			alert('Please don\'t leave the FTP user and password empty');
			$('#ftpuser').focus();
		}
	}
}

function check_host(val) {
	if (val=='') {
		alert('The host must not be empty');
		return false;
	}
	if (val.indexOf('/')!=-1) {
		alert('Please don\'t use prefixes like ftp:// or http:// in the host address and don\'t add a slash at the end of the address');
		return false;
	}
	return true;
}

function check_form(the) {
	if (the.site_name.value=='') {
		alert('The Site Name cannot be left empty');
		the.site_name.focus();
		return false;
	}
	if (the.baseurl.value=='') {
		alert('The Base URL cannot be left empty');
		the.baseurl.focus();
		return false;
	}
	if (the.baseurl.value.indexOf('http://')!=0) {
		alert('The Base URL must start with http://');
		the.baseurl.focus();
		return false;
	}
	if (the.basepath.value=='') {
		alert('The Base Path cannot be left empty');
		the.basepath.focus();
		return false;
	}
	if (the.basepath.value.indexOf('\\')!=-1) {
		alert('The Base Path must contain only forward slashes (/), not back slashes (\\)');
		the.basepath.focus();
		return false;
	}
	if (the.dbhost.value=='') {
		alert('The Database Server Host/IP cannot be left empty');
		the.dbhost.focus();
		return false;
	}
	if (the.dbuser.value=='') {
		alert('The Database User cannot be left empty');
		the.dbuser.focus();
		return false;
	}
	if (the.dbpass.value=='') {
		alert('The Database Password cannot be left empty');
		the.dbpass.focus();
		return false;
	}
	if (the.dbname.value=='') {
		alert('The Database Name cannot be left empty');
		the.dbname.focus();
		return false;
	}
	if (typeof(has_ftp)!='undefined') {
		if (the.ftphost.value=='') {
			alert('The FTP Server Host/IP cannot be left empty');
			the.ftphost.focus();
			return false;
		}
		if (the.ftpuser.value=='') {
			alert('The FTP User cannot be left empty');
			the.ftpuser.focus();
			return false;
		}
		if (the.ftppass.value=='') {
			alert('The FTP Password cannot be left empty');
			the.ftppass.focus();
			return false;
		}
		if (the.ftppath.value=='') {
			alert('The FTP Path to Site cannot be left empty');
			the.ftppath.focus();
			return false;
		}
	}
	if (the.license_key.value.length!=22) {
		alert('The license key is invalid');
		the.license_key.focus();
		return false;
	}
	return true;
}
