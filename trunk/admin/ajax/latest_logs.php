<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/ajax/latest_logs.php
$Revision: 145 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$last_id=sanitize_and_format_gpc($_GET,'last_id',TYPE_INT,0,0);
$output='';

$datetime_format=isset($_SESSION['admin']['prefs']['datetime_format']) ? $_SESSION['admin']['prefs']['datetime_format'] : get_site_option('datetime_format','def_user_prefs');
$query="SELECT `log_id`,`fk_user_id`,`user`,`level_code`,`ip`,UNIX_TIMESTAMP(`time`) as `time` FROM `{$dbtable_prefix}site_log` WHERE `log_id`>'$last_id' ORDER BY `log_id` DESC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output.='"log": [';
	while ($rsrow=mysql_fetch_assoc($res)) {
		$output.='{"log_id":'.$rsrow['log_id'].',';
		$output.='"fk_user_id":'.$rsrow['fk_user_id'].',';
		if (!empty($rsrow['fk_user_id'])) {
			$output.='"user": "<a href=\"profile.php?uid='.$rsrow['fk_user_id'].'\">'.$rsrow['user'].'</a>",';
		} else {
			$output.='"user": "'.$rsrow['user'].'",';
		}
		$output.='"level_code": "'.$rsrow['level_code'].'",';
		$output.='"ip": "'.long2ip($rsrow['ip']).'",';
		$output.='"time": "'.strftime($datetime_format,$rsrow['time']).'"';
		$output.='},';
	}
	$output=substr($output,0,-1);
	$output.=']';
}

echo '{'.$output.'}';
?>