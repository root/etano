$(function() {
//	get_reason();
	$('#save_tpl_btn').bind('click',save_template);
});

function get_reason() {
	myval=$("#reason").val();
	if (myval!='') {
		$.ajax({url: 'ajax/get_reject_reason.php',
				type: 'POST',
				dataType: 'xml',
				data: 'reason='+myval,
				success: function(xml) {
							set_reason(xml);
						}
				});
	}
}

function set_reason(xml) {
	$("#reason_title").val(unescape($("reason_title",xml).text()));
	$("#reject_reason").val(unescape($("reject_reason",xml).text()));
}

function check_form(theform) {
	return confirm('Are you sure?');
}

function save_template() {
	tinyMCE.triggerSave(true,true);
	var mycontent=tinyMCE.get('reject_reason').getContent();
	$.ajax({url:'ajax/save_admin_mtpl.php',
			type: 'POST',
			dataType:'html',
			data:'amtpl_type='+$('#t').val()+'&amtpl_id='+$('#amtpl_id').val()+'&subject='+escape($('#reason_title').val())+'&message_body='+escape(mycontent),
			success: function(data) {
						alert(data);
					}
			});
}
