function toggle_module_visibility(module_code) {
	mydiv=document.getElementById('mcontent_'+module_code);
	if (mydiv.className=='module_content shown_options') {
		mydiv.className='module_content hidden_options';
	} else {
		mydiv.className='module_content shown_options';
		document.getElementById('module_code').value=module_code;
	}
}
