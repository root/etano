$(function() {
	$('#gview_switch').bind('click',function() {
		$('ul.table_row').removeClass('list_view').addClass('gallery_view');
		createCookie('sco_app[rv_mode]','g',365);	// results_view_mode
		return false;
	});
	$('#lview_switch').bind('click',function() {
		$('ul.table_row').removeClass('gallery_view').addClass('list_view');
		createCookie('sco_app[rv_mode]','l',365);	// results_view_mode
		return false;
	});
	$('#save_search').bind('click',function() {
		myval='';
		myval=prompt('Please enter a name to remember this search:',myval);
		if (!myval || myval=='') {
			return false;
		} else {
			$.post(baseurl+'/processors/popup_save_search.php',
					{'search':search_md5,'title':escape(myval),'silent':1},
					function(data) {
						if (data!=null && data!='') {
							alert(data);
							document.location=document.location;
						} else {
							alert('There has been an error saving your search. Please try again later.');
						}
					}
			);
		}
		return false;
	});
});
