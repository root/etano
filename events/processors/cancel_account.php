<?php

$_on_after_update[]='send_admin_notif';

function send_admin_notif() {
	$config=get_site_option(array('alert_email','alert_on_cancel'),'core');
	if ($config['alert_on_cancel']) {
		$email['subject']='Member cancelled on '._SITENAME_;
		$email['message_body']='User: '.$_SESSION[_LICENSE_KEY_]['user']['user']."<br />\n";
		$email['message_body'].='Email: '.$_SESSION[_LICENSE_KEY_]['user']['email']."<br />\n";
		$email['message_body'].='Link: <a href="'._BASEURL_.'/admin/member_results.php?user='.$_SESSION[_LICENSE_KEY_]['user']['user'].'">'._BASEURL_.'/admin/member_results.php?user='.$_SESSION[_LICENSE_KEY_]['user']['user'].'</a>'."<br />\n";
		queue_or_send_email($config['alert_email'],$email);
	}
}
