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

function toggle_module_visibility(module_code) {
	$('#mcontent_'+module_code).toggleClass('shown_options').toggleClass('hidden_options');
	if ($('#mcontent_'+module_code).attr('class').indexOf('shown_options')!=-1) {
		$('#module_code').val(module_code);
	}
}
