$(function() {
	$('input[id^=is_private_]').each(function() {
		var photo_id=$(this).attr('id').substr(11);
		if (this.checked) {
			$('input#is_main_'+photo_id).removeAttr('checked');
		}
	});

	$('input[id^=is_private_]').bind('click',function() {
		var photo_id=$(this).attr('id').substr(11);
		if (this.checked) {
			$('input#is_main_'+photo_id).removeAttr('checked');
		}
	});

	$('input[id^=is_main_]').bind('click',function() {
		var photo_id=$(this).attr('id').substr(8);
		if (this.checked) {
			$('input#is_private_'+photo_id).removeAttr('checked');
		}
	});
});
