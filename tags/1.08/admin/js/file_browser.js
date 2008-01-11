$(function() {
	req_content(mypath);
});

function req_content(val) {
	$('#loading_warning').css('display','block');
	$("#file_browser").load('ajax/file_browser.php',{path:val},function() {$('#loading_warning').css('display','none');} );
}

