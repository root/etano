$(function() {
	$('#fk_subscr_id').focus();

	$('#subscriptions_auto_addedit').bind('submit',function() {
		return check_form(this);
	});

	$('#dbfield').bind('change',function() {
		if ($(this).val()=='') {
			$('#to_members_1')[0].checked=true;
			$('#field_value')[0].options.length=0;
		} else {
			$('#to_members_2')[0].checked=true;
			$.post('ajax/get_field_accvals.php',
					{'field':$(this).val()},
					function(data) {
						if (data!=null && data!='') {
							allopts=data.split("\n");
							str_field=allopts[0];
							$('#field_value')[0].options.length=0;
							for (i=0;i<allopts.length;i++) {
								oneopt=allopts[i].split('|');
								opt=new Option(oneopt[1],oneopt[0]);
								$('#field_value')[0].options.add(opt,$('#field_value')[0].length);
							}
						}
					}
			);
		}
	});
});

function check_form(theform) {
	return confirm('Save changes?');
}
