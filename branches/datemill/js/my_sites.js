function assign_site(lk) {
	baseurl=prompt('Please enter the URL','http://');
	if (!baseurl || baseurl=='') {
		return false;
	} else {
		if (baseurl.substr(0,6)!='http://') {
			$.post(baseurl+'/ajax/save_baseurl.php',
					{'lk':lk,'baseurl':baseurl},
					function(data) {
						if (data!=null) {
							if (data=='1') {
								window.document.location=window.document.location;
							} else {
								alert(data);
							}
						} else {
							alert('An error has occured trying to save the url. Please try again.');
						}
					}
			);
		} else {
			alert('The URL must start with http://');
		}
	}
	return false;
}
