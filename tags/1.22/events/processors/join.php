<?php

$_on_after_insert[]='send_admin_notif';

function send_admin_notif() {
	global $input;
	if ($input['page']==1) {
		$config=get_site_option(array('alert_email','alert_on_join'),'core');
		if ($config['alert_on_join']) {
			$email['subject']='New member on '._SITENAME_;
			$email['message_body']='User: '.$input['user']."<br />\n";
			$email['message_body'].='Email: '.$input['email']."<br />\n";
			$email['message_body'].='Link: <a href="'._BASEURL_.'/admin/member_results.php?user='.$input['user'].'">'._BASEURL_.'/admin/member_results.php?user='.$input['user'].'</a>'."<br />\n";
			queue_or_send_email($config['alert_email'],$email);
		}
	}
}
