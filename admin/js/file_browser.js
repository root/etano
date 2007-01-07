$(function() {req_content(mypath);});

function req_content(val) {
	$("#file_browser").load('ajax/file_browser.php',{path:val});
}

