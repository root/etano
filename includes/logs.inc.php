<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/logs.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
*******************************************************************************/

define('_PUNISH_ERROR_',1);
define('_PUNISH_BANUSER_',2);
define('_PUNISH_BANIP_',3);
$accepted_punishments=array(_PUNISH_ERROR_=>'Sorry page',_PUNISH_BANUSER_=>'Ban user',_PUNISH_BANIP_=>'Ban IP');

function log_user_action(&$log) {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="INSERT INTO `{$dbtable_prefix}site_log` SET `fk_user_id`='".$log['user_id']."',`user`='".$log['user']."',`m_value`='".$log['membership']."',`fk_level_id`='".$log['level']."',`ip`='".sprintf('%u',ip2long($log['ip']))."'";
	@mysql_query($query);
}


function rate_limiter(&$log) {
	$myreturn=false;
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$log['ip']=sprintf('%u',ip2long($log['ip']));
	$where='';
	if (!empty($log['user_id'])) {
		$where=" AND `fk_user_id`='".$log['user_id']."'";
	} else {
		$where=" AND (`user`='".$log['user']."' OR `ip`='".$log['ip']."')";
	}
	$query="SELECT `limit`,`interval`,`punishment` FROM `{$dbtable_prefix}rate_limiter` WHERE `fk_level_id`='".$log['level']."' AND `m_value`='".$log['membership']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$punish=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$query="SELECT count(*) FROM `{$dbtable_prefix}site_log` WHERE `fk_level_id`='".$log['level']."' AND `time`>=DATE_SUB('".gmdate('YmdHis')."',INTERVAL ".$rsrow['interval']." MINUTE) $where";
		if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_result($res2,0,0)>$rsrow['limit']) {
			if ($rsrow['punishment']==_PUNISH_ERROR_) {
				$punish[_PUNISH_ERROR_]=$rsrow['limit'];
			} elseif ($rsrow['punishment']==_PUNISH_BANUSER_) {
				$punish[_PUNISH_BANUSER_]=$log['user'];
			} elseif ($rsrow['punishment']==_PUNISH_BANIP_) {
				$punish[_PUNISH_BANIP_]=$log['ip'];
			}
		}
	}
	if (isset($punish[_PUNISH_BANIP_])) {
		rate_limiter_ban(_PUNISH_BANIP_,$log['ip']);
	}
	if (isset($punish[_PUNISH_BANUSER_])) {
		rate_limiter_ban(_PUNISH_BANUSER_,$log['user']);
	}
	if (isset($punish[_PUNISH_ERROR_])) {
		$myreturn=true;
	}
	return $myreturn;
}


function rate_limiter_ban($ban_type,$str) {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="INSERT INTO `{$dbtable_prefix}site_bans` SET `ban_type`=".$ban_type.",`what`='".$str."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	regenerate_ban_array();
}


function regenerate_ban_array() {
	require_once _BASEPATH_.'/includes/classes/modman.class.php';
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="SELECT `ban_type`,`what` FROM `{$dbtable_prefix}site_bans` GROUP BY `what`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$_bans=array();
	while ($rsrow=mysql_fetch_row($res)) {
		$_bans[$rsrow[0]][]=$rsrow[1];
	}
	$towrite="<?php\n";
	if (!empty($_bans[_PUNISH_BANIP_])) {
		$towrite.='$_bans[_PUNISH_BANIP_]=array(\''.join("','",$_bans[_PUNISH_BANIP_])."');\n";
	}
	if (!empty($_bans[_PUNISH_BANUSER_])) {
		$towrite.='$_bans[_PUNISH_BANUSER_]=array(\''.join("','",$_bans[_PUNISH_BANUSER_])."');\n";
	}
	$towrite.='?>';
	$modman=new modman();
	$modman->fileop->file_put_contents(_BASEPATH_.'/includes/site_bans.inc.php',$towrite);
}
