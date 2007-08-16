function add_membership() {
	myval=prompt('Please enter the name of the membership you want to add:');
	if (!myval || myval=='') {
		return false;
	}
	the=document.getElementById('manage_memberships');
	the.act.value='add';
	the.m_name.value=myval;
	the.submit();
}

function del_membership(val) {
	myval=confirm('Are you sure you want to delete this membership?');
	if (myval) {
		the=document.getElementById('manage_memberships');
		the.act.value='del';
		the.m_id.value=val;
		the.submit();
	}
}
