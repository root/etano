$(function() {
	$('a[@id^=kbc_toggle_]').bind('click',ontoggle_click);
	$('a[@id^=editkbc_]').bind('click',oneditkbc_click);
	$('a[@id^=addkbc_]').bind('click',onaddkbc_click);
	$('a[@id^=delkbc_]').bind('click',ondelkbc_click);
	$('a[@id^=addart_]').bind('click',onaddart_click);
});


function ontoggle_click() {
	myid=$(this).attr('id').substr(11);
	$(this).toggleClass('closed').toggleClass('opened');
	if ($(this).attr('class')=='opened') {
		refresh_categ_subcategs(myid,true);
		if (myid!=0) {
			refresh_categ_articles(myid,true);
		}
	} else {
		$('#subcategs_'+myid+', #articles_'+myid).toggle();
	}
	return true;
}


function refresh_categ_subcategs(some_id,do_toggle) {
	$.getJSON('ajax/get_kb_categs.php',
				{'kbc_id':some_id},
				function(json) {
					towrite='';
					for (i=0;i<json.subcategs.length;i++) {
						towrite+='<li><dl id="dlk_'+json.subcategs[i].id+'" class="clearfix"><dd><a href="#kbc_'+json.subcategs[i].id+'" id="kbc_toggle_'+json.subcategs[i].id+'" class="closed">'+json.subcategs[i].title+'</a> <span>('+json.subcategs[i].num_articles+')</span></dd><dd><a href="javascript:;" class="item_add" id="addart_'+json.subcategs[i].id+'" title="Add articles in this category"></a></dd><dd><a href="javascript:;" class="item_edit" id="editkbc_'+json.subcategs[i].id+'_'+json.id+'" title="Edit category title"></a></dd><dd><a href="javascript:;" class="item_del" id="delkbc_'+json.subcategs[i].id+'" title="Delete category and all articles"></a></dd><dd><a href="javascript:;" class="item_addunder" id="addkbc_'+json.subcategs[i].id+'" title="Add subcategory"></a></dd></dl><ul class="subcategs" id="subcategs_'+json.subcategs[i].id+'" style="display: none;"></ul><ul class="articles" id="articles_'+json.subcategs[i].id+'" style="display: none;"></ul></li>';
					}
					$('#subcategs_'+json.id).html(towrite);
					$('a[@id^=kbc_toggle_]').bind('click',ontoggle_click);	// rebind to include the new links again.
					$('a[@id^=editkbc_]').bind('click',oneditkbc_click);
					$('a[@id^=addkbc_]').bind('click',onaddkbc_click);
					$('a[@id^=delkbc_]').bind('click',ondelkbc_click);
					$('a[@id^=addart_]').bind('click',onaddart_click);
					if (do_toggle) {
						$('#subcategs_'+json.id).toggle();
					}
				}
	);
}


function refresh_categ_articles(some_id,do_toggle) {
	$.getJSON('ajax/get_kb_articles.php',
				{'kbc_id':some_id},
				function(json) {
					towrite='';
					for (i=0;i<json.articles.length;i++) {
						towrite+='<li><dl id="dla_'+json.articles[i].id+'" class="clearfix"><dd>'+json.articles[i].title+'</dd><dd><a href="javascript:;" class="item_edit" id="editkba_'+json.articles[i].id+'_'+json.id+'" title="Edit article"></a></dd><dd><a href="javascript:;" class="item_del" id="delkba_'+json.articles[i].id+'" title="Delete article"></a></dd></dl></li>';
					}
					$('#articles_'+json.id).html(towrite);
					$('a[@id^=editkba_]').bind('click',oneditkba_click);
					$('a[@id^=delkba_]').bind('click',ondelkba_click);
					$('a[@id^=delkba_]').bind('click',ondelkba_click);
					if (do_toggle) {
						$('#articles_'+json.id).toggle();
					}
				}
	);
}


function oneditkbc_click() {
	temp=$(this).attr('id').substr(8);
	myid=temp.substr(0,temp.indexOf('_'));
	parentid=temp.substr(temp.indexOf('_')+1);
	$('#dlk_'+myid).hide();
	$('<form id="frmeditkbc_'+myid+'"><input type="hidden" name="kbc_id" value="'+myid+'" /><input type="hidden" name="fk_kbc_id_parent" value="'+parentid+'" /><input type="text" name="kbc_title" id="kbc_title_'+myid+'" value="" /><input type="submit" value="Save" /><input type="submit" value="Cancel" id="reset_'+myid+'" /></form>').insertBefore('#dlk_'+myid);
	$('#kbc_title_'+myid).val($('#kbc_toggle_'+myid).text());
	$('#kbc_title_'+myid).focus();
	$('#frmeditkbc_'+myid).bind('submit',save_kbc);
	$('#reset_'+myid).bind('click',function() {return cancel_edit_kbc(this)});
	return false;
}


function onaddkbc_click() {
	myid=0;
	$('#frmeditkbc_'+myid).remove();	// remove it if already exists
	parentid=$(this).attr('id').substr(7);
	if ($('#kbc_toggle_'+parentid).attr('class')=='closed') {
		$('#kbc_toggle_'+parentid).attr({class:'opened'});
		$('#subcategs_'+parentid).show();
	}
	$('<li><form id="frmeditkbc_'+myid+'"><input type="hidden" name="kbc_id" value="'+myid+'" /><input type="hidden" name="fk_kbc_id_parent" value="'+parentid+'" /><input type="text" name="kbc_title" id="kbc_title_'+myid+'" /><input type="submit" value="Save" /><input type="submit" value="Cancel" id="reset_'+myid+'" /></form></li>').appendTo('#subcategs_'+parentid);
	$('#kbc_title_'+myid).focus();
	$('#frmeditkbc_'+myid).bind('submit',save_kbc);
	$('#reset_'+myid).bind('click',function() {return cancel_edit_kbc(this)});
	return false;
}


function cancel_edit_kbc(ths) {
	myid=ths.id.substr(6);
	$('#dlk_'+myid).show();
	$('#frmeditkbc_'+myid).remove();
	return false;
}


function save_kbc() {
	$.post('processors/kb_categs_addedit.php',
			{silent:1,kbc_id:this.kbc_id.value,fk_kbc_id_parent:this.fk_kbc_id_parent.value,kbc_title:encodeURIComponent(this.kbc_title.value)},
			function(data) {
				if (data!=parseInt(data)) {
					if (data.indexOf('|')!=-1) {
						alert(data.substr(data.indexOf('|')+1));
					}
				} else {
					refresh_categ_subcategs(data,false);
				}
			}
	);
	return false;
}


function ondelkbc_click() {
	myid=$(this).attr('id').substr(7);
	if (confirm('Are you sure you want to delete this category, all subcategories and all articles from this category?')) {
		$.get('processors/kb_categs_delete.php',
				{silent:1,kbc_id:myid},
				function(data) {
					if (data!=parseInt(data)) {
						if (data.indexOf('|')!=-1) {
							alert(data.substr(data.indexOf('|')+1));
						}
					} else {
						$('#dlk_'+data+',#subcategs_'+data+',#articles_'+data).parent().remove();
					}
				}
		);
	}
	return false;
}


function ondelkba_click() {
	myid=$(this).attr('id').substr(7);
	if (confirm('Are you sure you want to delete this article?')) {
		$.get('processors/kb_articles_delete.php',
				{silent:1,kba_id:myid},
				function(data) {
					if (data!=parseInt(data)) {
						if (data.indexOf('|')!=-1) {
							alert(data.substr(data.indexOf('|')+1));
						}
					} else {
						$('#dla_'+data).parent().remove();
					}
				}
		);
	}
	return false;
}


function oneditkba_click() {
	temp=$(this).attr('id').substr(8);
	myid=temp.substr(0,temp.indexOf('_'));
	my_kbcid=temp.substr(temp.indexOf('_')+1);
	$('#dla_'+myid).hide();
	$.getJSON('ajax/get_kb_article.php',
			{'kba_id':myid},
			function(json) {
				if (json) {
					$('<form id="frmeditart_'+json.kba_id+'"><input type="hidden" name="kba_id" value="'+json.kba_id+'" /><input type="hidden" name="fk_kbc_id" value="'+json.fk_kbc_id+'" /><input type="text" name="kba_title" id="kba_title_'+json.kba_id+'" class="kba_title" /><textarea name="kba_content" id="kba_content_'+myid+'" cols="" rows=""></textarea><input type="submit" value="Save" /><input type="submit" value="Cancel" id="resetart_'+myid+'" /></form>').insertBefore('#dla_'+myid);
					$('#frmeditart_'+json.kba_id)[0].kba_id.value=json.kba_id;
					$('#frmeditart_'+json.kba_id)[0].fk_kbc_id.value=json.fk_kbc_id;
					$('#frmeditart_'+json.kba_id)[0].kba_title.value=json.kba_title;
					$('#frmeditart_'+json.kba_id)[0].kba_content.value=json.kba_content;
					$('#kba_title_'+json.kba_id).focus();
					$('#frmeditart_'+json.kba_id).bind('submit',save_kba);
					$('#resetart_'+json.kba_id).bind('click',function() {return cancel_edit_kba(this,false)});
				}
			}
	);
	return false;
}

function onaddart_click() {
	my_kbcid=$(this).attr('id').substr(7);
	myid=0;
	$('#frmeditart_'+myid).remove();	// remove it if already exists
	if ($('#kbc_toggle_'+my_kbcid).attr('class')=='closed') {
		$('#kbc_toggle_'+my_kbcid).attr({class:'opened'});
		$('#articles_'+my_kbcid).show();
	}
	$('<li><form id="frmeditart_'+myid+'"><input type="hidden" name="kba_id" value="'+myid+'" /><input type="hidden" name="fk_kbc_id" value="'+my_kbcid+'" /><input type="text" name="kba_title" id="kba_title_'+myid+'" class="kba_title" /><textarea name="kba_content" id="kba_content_'+myid+'" cols="" rows=""></textarea><input type="submit" value="Save" /><input type="submit" value="Cancel" id="resetart_'+myid+'" /></form></li>').appendTo('#articles_'+my_kbcid);
	$('#kba_title_'+myid).focus();
	$('#frmeditart_'+myid).bind('submit',save_kba);
	$('#resetart_'+myid).bind('click',function() {return cancel_edit_kba(this,true)});
	return false;
}

function cancel_edit_kba(ths,del_parent) {
	myid=ths.id.substr(9);
	$('#dla_'+myid).show();
	if (del_parent) {
		$('#frmeditart_'+myid).parent().remove();
	} else {
		$('#frmeditart_'+myid).remove();
	}
	return false;
}


function save_kba() {
	$.post('processors/kb_articles_addedit.php',
			{silent:1,kba_id:this.kba_id.value,fk_kbc_id:this.fk_kbc_id.value,kba_title:encodeURIComponent(this.kba_title.value),kba_content:encodeURIComponent(this.kba_content.value)},
			function(data) {
				if (data!=parseInt(data)) {
					if (data.indexOf('|')!=-1) {
						alert(data.substr(data.indexOf('|')+1));
					}
				} else {
					refresh_categ_articles(data,false);
				}
			}
	);
	return false;
}
