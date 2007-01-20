$(function() {req_content(mypath);});

function req_content(val) {
	$('#loading_warning')[0].style.display='block';
	$("#file_browser").load('ajax/file_browser.php',{path:val},function() {$('#loading_warning')[0].style.display='none';} );
}

