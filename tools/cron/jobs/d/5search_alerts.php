<?php
$jobs[]='search_alerts';

function search_alerts() {
	global $dbtable_prefix,$tplvars;
	$tplvars['tplrelpath']=_BASEPATH_.'/skins_site/def';
	require_once _BASEPATH_.'/includes/search_functions.inc.php';
	require_once _BASEPATH_.'/includes/user_functions.inc.php';

	$config['limit_results']=5;
	$query_strlen=20000;

	$query="SELECT a.`search_id`,a.`fk_user_id`,a.`title`,a.`search`,a.`search_qs`,a.`alert_last_id`,UNIX_TIMESTAMP(a.`alert_last_sent`) as `alert_last_sent`,b.`membership`,b.`".USER_ACCOUNT_USER."` as `user`,b.`email` FROM `{$dbtable_prefix}user_searches` a,`".USER_ACCOUNTS_TABLE."` b WHERE a.`fk_user_id`=b.`".USER_ACCOUNT_ID."` AND a.`alert`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$skin=get_default_skin_dir();
		$tpl=new phemplate(_BASEPATH_.'/skins_site/'.$skin.'/emails/','remove_nonjs');
		$tpl->set_file('temp','search_alert.html');
		$tpl->set_var('tplvars',$tplvars);
		$subject=sprintf('New matches for you on %s',_SITENAME_);	// translate
		$subject=sanitize_and_format($subject,TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD]);
		require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
		$user_cache=new user_cache($skin);
		$insert="INSERT INTO `{$dbtable_prefix}queue_email` (`to`,`subject`,`message_body`) VALUES ";
		$iquery=$insert;
		$alert_intervals=array();
		$now=time();
		while ($rsrow=mysql_fetch_assoc($res)) {
			if (!isset($alert_intervals[$rsrow['fk_user_id']])) {
				$alert_intervals[$rsrow['fk_user_id']]=get_user_settings($rsrow['fk_user_id'],'def_user_prefs','send_alert_interval')*86400;
			}
			if (((int)$now-(int)$rsrow['alert_last_sent'])>=(int)$alert_intervals[$rsrow['fk_user_id']]) {
				$output=array();
				$rsrow['search']=unserialize($rsrow['search']);
				$rsrow['search']['min_user_id']=$rsrow['alert_last_id'];
				$user_ids=search_results($rsrow['search'],$rsrow['membership']);
				if (!empty($user_ids)) {
					$last_user_id=0;
					for ($i=0;isset($user_ids[$i]);++$i) {
						if ($user_ids[$i]>$last_user_id) {
							$last_user_id=$user_ids[$i];
						}
					}
					$user_ids=array_slice($user_ids,0,$config['limit_results']);

					// last activity only for not online members
					$temp=array();
					$inject_by_uid=array();
					for ($i=0;isset($user_ids[$i]);++$i) {
						if (!isset($GLOBALS['_list_of_online_members'][$user_ids[$i]])) {
							$temp[]=$user_ids[$i];
						} else {
							$inject_by_uid[$user_ids[$i]]=array('last_online'=>'Online now!');	// translate
						}
					}
					if (!empty($temp)) {
						$time=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
						$query="SELECT `".USER_ACCOUNT_ID."` as `uid`,UNIX_TIMESTAMP(`last_activity`) as `last_activity` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$temp)."')";
						if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						while ($rsrow2=mysql_fetch_assoc($res2)) {
							$rsrow2['last_activity']=$time-$rsrow2['last_activity'];
							if ($rsrow2['last_activity']<86400) {
								$inject_by_uid[$rsrow2['uid']]=array('last_online'=>'Today.');	// translate
							} elseif ($rsrow2['last_activity']<172800) {
								$inject_by_uid[$rsrow2['uid']]=array('last_online'=>'Yesterday.');	// translate
							} elseif ($rsrow2['last_activity']<604800) {
								$inject_by_uid[$rsrow2['uid']]=array('last_online'=>'Last week.');	// translate
							} elseif ($rsrow2['last_activity']<2419200) {
								$inject_by_uid[$rsrow2['uid']]=array('last_online'=>'Last month.');	// translate
							} else {
								$inject_by_uid[$rsrow2['uid']]=array('last_online'=>'More than a month ago.');	// translate
							}
						}
					}
					$cell_css_classes=array();
					for ($i=0;isset($user_ids[$i]);++$i) {
						if (isset($GLOBALS['_list_of_online_members'][$user_ids[$i]])) {
							$cell_css_classes[$i]='is_online';
						}
					}
					$output['results']=smart_table($user_cache->get_cache_array($user_ids,'result_user',$inject_by_uid),5,'list_view',$cell_css_classes);
					$output['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
					$output['search_qs']=$rsrow['search_qs'];
					$output['user']=$rsrow['user'];

					$tpl->set_var('output',$output);
					$message_body=$tpl->process('','temp',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_FINISH);
					$message_body=sanitize_and_format($message_body,TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTAREA]);
					if (strlen($iquery)>$query_strlen) {
						$iquery=substr($iquery,0,-1);
						if (!($res2=@mysql_query($iquery))) {trigger_error(mysql_error(),E_USER_ERROR);}
						$iquery=$insert;
					}
					$iquery.="('".$rsrow['email']."','$subject','$message_body'),";
					$query="UPDATE `{$dbtable_prefix}user_searches` SET `alert_last_id`=$last_user_id,`alert_last_sent`=now() WHERE `search_id`=".$rsrow['search_id'];
					@mysql_query($query);
				}
			}
		}
		if ($iquery!=$insert) {
			$iquery=substr($iquery,0,-1);
			if (!($res2=@mysql_query($iquery))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
	return true;
}
