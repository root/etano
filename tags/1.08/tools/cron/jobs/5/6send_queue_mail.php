<?php
// this job should be run last!!! In case it takes too long, all other 5mins jobs must be run before.

$jobs[]='send_queue_mail';

function send_queue_mail() {
	global $dbtable_prefix;

	$query="SELECT `mail_id`,`to`,`subject`,`message_body` FROM `{$dbtable_prefix}queue_email` ORDER BY `mail_id` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$config=get_site_option(array('mail_from','mail_crlf'),'core');
		require_once _BASEPATH_.'/includes/classes/phpmailer.class.php';
		$mail=new PHPMailer();
		$mail->IsHTML(true);
		$mail->From=$config['mail_from'];
		$mail->Sender=$config['mail_from'];
		$mail->FromName=_SITENAME_;
		if ($config['mail_crlf']) {
			$mail->LE="\r\n";
		} else {
			$mail->LE="\n";
		}
		$mail->IsMail();

		$mail_ids=array();
		$errors=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$mail->ClearAddresses();
			$mail->AddAddress($rsrow['to']);
			$mail->Subject=$rsrow['subject'];
			$mail->Body=$rsrow['message_body'];
			if (!$mail->Send()) {
				$errors[]='mail_id: '.$rsrow['mail_id'].' error: '.$mail->ErrorInfo;
			} else {
				$mail_ids[]=$rsrow['mail_id'];
			}
		}

		if (!empty($mail_ids)) {
			$query="DELETE FROM `{$dbtable_prefix}queue_email` WHERE `mail_id` IN ('".join("','",$mail_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
	return true;
}