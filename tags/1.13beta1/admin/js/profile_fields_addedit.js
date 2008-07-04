$(function() {
	if ($('#pfield_id').val()=='' || $('#pfield_id').val()=='0') {
		$('a.translate').hide();
	}

	var i=0;
	$('.edit_form > div').each(function() {
		if (i%2==1) {
			$(this).addClass('stripe_odd');
		} else {
			$(this).addClass('stripe_even');
		}
		i++;
	});

// show or hide dependant options and bind their display to the triggering fields.
	if ($('#search_type')[0] && $('#search_type')[0].length>0) {
		showhide('searchable','row_st');
	} else if ($('#row_st')[0]) {
		$('#row_st').hide();
	}
	showhide('searchable','row_sl');
	showhide('searchable','row_bs');
	showhide('searchable','custom_config_search');
	showhide('at_registration','row_reg_page');
	$('#label').focus();
	$('#at_registration').bind('change',function() {
		showhide('at_registration','row_reg_page');
	});
	$('#searchable').bind('change',function() {
		if ($('#search_type')[0].length>0) {
			showhide('searchable','row_st');
		}
		showhide('searchable','row_sl');
		showhide('searchable','row_bs');
	});

// only numbers allowed in these fields
	$('.numeric').numeric();

	$("#profile_fields_addedit").bind('submit',function() {
		return confirm('Are you sure you want to save your changes?');
	});

	$('#search_type').change(function() {
		$.post(baseurl+'/admin/ajax/search_field_custom_config.php',
				{pfield_id: $('#pfield_id').val(),search_type:$(this).val()},
				function(data) {
					$('#custom_config_search').html(data);
				}
		);
	});
});


function showhide(strCheck,strHide) {
	if (typeof $('#'+strCheck)[0]!='undefined' && $('#'+strCheck)[0].checked==true) {
		$('#'+strHide).show();
	} else {
		$('#'+strHide).hide();
	}
}
