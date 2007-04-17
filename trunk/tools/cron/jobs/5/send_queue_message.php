<?php
$jobs[]='send_queue_message';

function send_queue_message() {
	global $dbtable_prefix;

	$filters=array();
	$notifs=array();
	$mail_ids=array();
	$query="SELECT `mail_id`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type` FROM `{$dbtable_prefix}queue_message` ORDER BY `mail_id` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$message=mysql_fetch_assoc($res);
		$mail_ids[]=$message['mail_id'];
		unset($message['mail_id']);

		if (!isset($filters[$message['fk_user_id']])) {
			$query="SELECT `filter_type`,`field`,`field_value`,`fk_folder_id` FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`='".$message['fk_user_id']."'";
			if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res2)) {
				$filters[$message['fk_user_id']][]=$rsrow;
			}
			if (!isset($filters[$message['fk_user_id']])) {
				$filters[$message['fk_user_id']]=array();
			}
		}

		if (!isset($notifs[$message['fk_user_id']])) {
			$notifs[$message['fk_user_id']]=get_user_settings($message['fk_user_id'],'def_user_prefs','notify_me');
		}

		if (!empty($filters[$message['fk_user_id']])) {
			for ($i=0;isset($filters[$message['fk_user_id']][$i]);++$i) {
				$filter=$filters[$message['fk_user_id']][$i];
				switch ($filter['filter_type']) {

					case FILTER_SENDER:
						if ($message['fk_user_id_other']==$filter['field_value']) {
							if ($message['fk_folder_id']==FOLDER_SPAMBOX) {
								$into='`{$dbtable_prefix}user_spambox`';
								unset($message['fk_folder_id']);
							} else {
								$into='`{$dbtable_prefix}user_inbox`';
							}
							$query="INSERT INTO $into SET ";
							foreach ($message as $k=>$v) {
								$query.="`$k`='$v',";
							}
							$query=substr($query,0,-1);
							if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						}
						if ($notifs[$message['fk_user_id']]) {
							send_template_email();
						}
						break 2;	// exit the filters for() too
				}
			}
		} else {
			$query="INSERT INTO `{$dbtable_prefix}user_inbox` SET ";
			foreach ($message as $k=>$v) {
				$query.="`$k`='$v',";
			}
			$query=substr($query,0,-1);
			if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}

		$mail_ids=array();
		$errors=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$mail->ClearAddresses();
			$mail->AddAddress($rsrow['to']);
			$mail->Subject=$rsrow['subject'];
			$mail->Body=$rsrow['message_body'];
			if (!$mail->Send()) {
				$errors[]=$mail->ErrorInfo;
			}
		}

		if (!empty($mail_ids)) {
			$query="DELETE FROM `{$dbtable_prefix}queue_email` WHERE `mail_id` IN ('".join("','",$mail_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
	return true;
}
