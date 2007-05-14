$(function() {
	$('#edit_form').bind('submit',function() {
		return(check_form(this));
	});

	$('#edit_form input:visible:first').focus();

	$('#edit_form textarea').bind('keyup',function() {
		myid=$(this).attr('id');
		remaining=ta_len-$(this).val().length;
		if (remaining<0) {
			$(this).val($(this).val().substr(0,$(this).val().length+remaining));
			remaining=0;
		}
		$('#'+myid+'_chars').html(remaining.toString());
	}).bind('blur',function() {
		myid=$(this).attr('id');
		remaining=ta_len-$(this).val().length;
		if (remaining<0) {
			$(this).val($(this).val().substr(0,$(this).val().length+remaining));
			remaining=0;
		}
		$('#'+myid+'_chars').html(remaining.toString());
	});
});

function req_update_location(str_field,val) {
	$.post('ajax/location.php',
			{'field':str_field,'val':val},
			function(data) {
				if (data!=null && data!='') {
					allopts=data.split("\n");
					str_field=allopts[0];
					toshow=allopts[1].split('|');
					$('#row_'+str_field+'state').addClass('invisible').removeClass('visible');
					$('#row_'+str_field+'city').addClass('invisible').removeClass('visible');
					$('#row_'+str_field+'zip').addClass('invisible').removeClass('visible');
					for (i=0;i<toshow.length;i++) {
						$('#row_'+toshow[i]).addClass('visible').removeClass('invisible');
					}
					if (allopts.length>3) {
						to_update=$('#'+str_field+allopts[2])[0];
						to_update.options.length=0;
						for (i=3;i<allopts.length;i++) {
							oneopt=allopts[i].split('|');
							opt=new Option(oneopt[1],oneopt[0]);
							to_update.options.add(opt,to_update.length);
						}
					}
				}
			}
	);
}

function check_form(theform) {
	return confirm('Save?');
}
