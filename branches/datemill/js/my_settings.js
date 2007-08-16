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
});
