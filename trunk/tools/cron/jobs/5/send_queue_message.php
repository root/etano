<?php
$jobs[]='send_queue_message';

function send_queue_message() {
	$limit=50;	// number of messages in a batch

	global $dbtable_prefix;
	$filters=array();
	$notifs=array();
	$emails=array();
	$mail_ids=array();
	$query="SELECT a.`mail_id`,a.`fk_user_id`,a.`fk_user_id_other`,a.`_user_other`,a.`subject`,a.`message_body`,a.`date_sent`,a.`message_type`,b.`email`,b.`user` FROM `{$dbtable_prefix}queue_message` a,".USER_ACCOUNTS_TABLE." b WHERE a.`fk_user_id`=b.`user_id` ORDER BY a.`mail_id` ASC LIMIT $limit";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		while ($rsrow=mysql_fetch_assoc($res)) {
			$temp['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
			$temp['_user_other']=$rsrow['_user_other'];
			$temp['email']=$rsrow['email'];
			$temp['user']=$rsrow['user'];
			$mail_ids[]=$rsrow['mail_id'];
			unset($rsrow['mail_id'],$rsrow['email'],$rsrow['user']);
			$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DB]);
			$rsrow['message_body']=sanitize_and_format($rsrow['message_body'],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DB]);

			if (!isset($filters[$rsrow['fk_user_id']])) {
				$query="SELECT `filter_type`,`field`,`field_value`,`fk_folder_id` FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`='".$rsrow['fk_user_id']."'";
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				while ($rsrow2=mysql_fetch_assoc($res2)) {
					$filters[$rsrow['fk_user_id']][]=$rsrow2;
				}
				if (!isset($filters[$rsrow['fk_user_id']])) {
					$filters[$rsrow['fk_user_id']]=array();
				}
			}

			if (!isset($notifs[$rsrow['fk_user_id']])) {
				$notifs[$rsrow['fk_user_id']]=get_user_settings($rsrow['fk_user_id'],'def_user_prefs','notify_me');
			}
			if ($notifs[$rsrow['fk_user_id']]) {
				$emails[]=$temp;
			}

			if (!empty($filters[$rsrow['fk_user_id']])) {
				for ($i=0;isset($filters[$rsrow['fk_user_id']][$i]);++$i) {
					$filter=$filters[$rsrow['fk_user_id']][$i];
					switch ($filter['filter_type']) {

						case FILTER_SENDER:
							if ($rsrow['fk_user_id_other']==$filter['field_value']) {
								if ($filter['fk_folder_id']==FOLDER_SPAMBOX) {
									$into="`{$dbtable_prefix}user_spambox`";
								} else {
									$into="`{$dbtable_prefix}user_inbox`";
									$rsrow['fk_folder_id']=$filter['fk_folder_id'];
								}
								$query="INSERT INTO $into SET ";
								foreach ($rsrow as $k=>$v) {
									$query.="`$k`='$v',";
								}
								$query=substr($query,0,-1);
								if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							}
							break 2;	// exit the filters for() too

					}
				}
			} else {
				// no filter here - insert directly in inbox
				$query="INSERT INTO `{$dbtable_prefix}user_inbox` SET ";
				foreach ($rsrow as $k=>$v) {
					$query.="`$k`='$v',";
				}
				$query=substr($query,0,-1);
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}

		if (!empty($mail_ids)) {
			$query="DELETE FROM `{$dbtable_prefix}queue_message` WHERE `mail_id` IN ('".join("','",$mail_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}

	// send the notification emails
	if (!empty($emails)) {
		$def_skin=get_default_skin_dir();
		for ($i=0;isset($emails[$i]);++$i) {
			send_template_email($emails[$i]['email'],$emails[$i]['subject'],'new_message.html',$def_skin,$emails[$i]);
		}
	}
	return true;
}
