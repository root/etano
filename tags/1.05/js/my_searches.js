function change_title(search_id,curr_title) {
	new_title=prompt('Please enter the new title',unescape(curr_title));
	if (new_title && new_title!='' && new_title!=curr_title) {
		$('#renamesearch_form')[0].search_id.value=search_id;
		$('#renamesearch_form')[0].title.value=new_title;
		$('#renamesearch_form')[0].submit();
		return false;
	}
}
