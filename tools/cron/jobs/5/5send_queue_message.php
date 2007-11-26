<?php
// this job should come before send_queue_email job

$jobs[]='send_queue_message';

function send_queue_message() {
	$limit=50;	// number of messages in a batch

	unset($_on_before_insert,$_on_after_insert);
	if (is_file(_BASEPATH_.'/events/cronjobs/send_queue_message.php')) {
		include_once _BASEPATH_.'/events/cronjobs/send_queue_message.php';
	}

	global $dbtable_prefix,$def_skin;
	include_once _BASEPATH_.'/skins_site/'.$def_skin.'/lang/mailbox.inc.php';
	$filters=array();
	$notifs=array();
	$emails=array();
	$mail_ids=array();
	$receivers=array();
	$query="SELECT a.`mail_id`,a.`fk_user_id`,a.`fk_user_id_other`,a.`_user_other`,a.`subject`,a.`message_body`,a.`date_sent`,a.`message_type`,b.`email`,b.`".USER_ACCOUNT_USER."` as `user` FROM `{$dbtable_prefix}queue_message` a,`".USER_ACCOUNTS_TABLE."` b WHERE a.`fk_user_id`=b.`".USER_ACCOUNT_ID."` ORDER BY a.`mail_id` ASC LIMIT $limit";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		while ($rsrow=mysql_fetch_assoc($res)) {
			$temp['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
			$temp['_user_other']=$rsrow['_user_other'];
			if (empty($temp['_user_other']) && $rsrow['message_type']==MESS_SYSTEM) {
				$temp['_user_other']=$GLOBALS['_lang'][135];
			}
			$temp['email']=$rsrow['email'];
			$temp['user']=$rsrow['user'];
			$mail_ids[]=$rsrow['mail_id'];
			if (isset($receivers[$rsrow['fk_user_id']])) {
				++$receivers[$rsrow['fk_user_id']];
			} else {
				$receivers[$rsrow['fk_user_id']]=1;
			}
			unset($rsrow['mail_id'],$rsrow['email'],$rsrow['user']);
			$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DB]);
			$rsrow['message_body']=sanitize_and_format($rsrow['message_body'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DB]);

			if (!isset($filters[$rsrow['fk_user_id']])) {
				$query="SELECT `filter_type`,`field`,`field_value`,`fk_folder_id` FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`=".$rsrow['fk_user_id'];
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

			$notify=true;
			$was_sent=false;	// was sent by a filter?
			if (!empty($filters[$rsrow['fk_user_id']])) {
				for ($i=0;isset($filters[$rsrow['fk_user_id']][$i]);++$i) {
					$filter=$filters[$rsrow['fk_user_id']][$i];
					switch ($filter['filter_type']) {

						case FILTER_SENDER:
							if ($rsrow['fk_user_id_other']==$filter['field_value']) {
								if ($filter['fk_folder_id']==FOLDER_SPAMBOX) {
									$into="`{$dbtable_prefix}user_spambox`";
									$notify=false;
								} else {
									$into="`{$dbtable_prefix}user_inbox`";
									$rsrow['fk_folder_id']=$filter['fk_folder_id'];
								}
								$query="INSERT INTO $into SET ";
								foreach ($rsrow as $k=>$v) {
									$query.="`$k`='$v',";
								}
								$query=substr($query,0,-1);
								if (isset($_on_before_insert)) {
									for ($i=0;isset($_on_before_insert[$i]);++$i) {
										call_user_func($_on_before_insert[$i],$rsrow);
									}
								}
								if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (isset($_on_after_insert)) {
									for ($i=0;isset($_on_after_insert[$i]);++$i) {
										call_user_func($_on_after_insert[$i],$rsrow);
									}
								}
								$was_sent=true;
							}
							break 2;	// exit the filters for() too

					}
				}
			}
			if (!$was_sent) {
				// no filter here - insert directly in inbox
				$query="INSERT INTO `{$dbtable_prefix}user_inbox` SET ";
				foreach ($rsrow as $k=>$v) {
					$query.="`$k`='$v',";
				}
				$query=substr($query,0,-1);
				if (isset($_on_before_insert)) {
					for ($i=0;isset($_on_before_insert[$i]);++$i) {
						call_user_func($_on_before_insert[$i],$rsrow);
					}
				}
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (isset($_on_after_insert)) {
					for ($i=0;isset($_on_after_insert[$i]);++$i) {
						call_user_func($_on_after_insert[$i],$rsrow);
					}
				}
			}

			if ($notifs[$rsrow['fk_user_id']] && $notify) {
				$emails[]=$temp;
			}
		}

		if (!empty($mail_ids)) {
			$query="DELETE FROM `{$dbtable_prefix}queue_message` WHERE `mail_id` IN ('".join("','",$mail_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}

	if (!empty($receivers)) {
		$uids=array();	// we build an array like array(num_messages1=>array(uid1,uid2,..),num_messages2=>array(uid3,uid4...),...)
						// this way we can add score for more users at once, saving some processing time
		foreach ($receivers as $uid=>$num) {
			if (isset($uids[$num])) {
				$uids[$num][]=$uid;
			} else {
				$uids[$num]=array($uid);
			}
		}
		foreach ($uids as $num=>$nuids) {
			add_member_score($nuids,'new_message',$num);
		}
	}

	// send the notification emails
	if (!empty($emails)) {
		for ($i=0;isset($emails[$i]);++$i) {
			send_template_email($emails[$i]['email'],$emails[$i]['subject'],'new_message.html',$def_skin,$emails[$i]);
		}
	}
	return true;
}
