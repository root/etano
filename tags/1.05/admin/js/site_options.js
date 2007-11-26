$(function() {
	$('input.number').numeric();
});

function toggle_module_visibility(module_code) {
	$('#mcontent_'+module_code).toggleClass('shown_options').toggleClass('hidden_options');
	if ($('#mcontent_'+module_code).attr('class').indexOf('shown_options')!=-1) {
		$('#module_code').val(module_code);
	}
}
