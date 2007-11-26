$(function() {
	$('input.number').numeric();
	$('#auto_detect_tz').click(function() {
		my_time=new Date();
		var diff=Math.round((my_time.getTime()-server_time.getTime())/3600000);
		diff%=24;
		diff*=3600;
		$('#def_user_prefs_time_offset').val(diff);
		return false;
	});

	$('#prefs_form input:visible:first').focus();

	$('#passchange_form').bind('submit',function() {return check_passchange(this)});
});


function check_passchange(the) {
	if (the.pass.value=='') {
		alert(lang[0]);
		the.pass.focus();
		return false;
	}
	if (the.pass.value!=the.pass2.value) {
		alert(lang[1]);
		the.pass2.focus();
		return false;
	}
	return true;
}
