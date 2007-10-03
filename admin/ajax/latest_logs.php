<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/ajax/latest_logs.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$last_id=sanitize_and_format_gpc($_GET,'last_id',TYPE_INT,0,0);
$output='';

if (!isset($_SESSION[_LICENSE_KEY_]['admin']['prefs']['datetime_format']) || !isset($_SESSION[_LICENSE_KEY_]['admin']['prefs']['time_offset'])) {
	if (!isset($_SESSION[_LICENSE_KEY_]['admin']['prefs'])) {
		$_SESSION[_LICENSE_KEY_]['admin']['prefs']=array();
	}
	$_SESSION[_LICENSE_KEY_]['admin']['prefs']=array_merge($_SESSION[_LICENSE_KEY_]['admin']['prefs'],get_site_option(array('time_offset','datetime_format'),'def_user_prefs'));
}

$query="SELECT `log_id`,`fk_user_id`,`user`,`level_code`,`ip`,UNIX_TIMESTAMP(`time`) as `time` FROM `{$dbtable_prefix}site_log` WHERE `log_id`>$last_id ORDER BY `log_id` DESC";
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
		$output.='"time": "'.strftime($_SESSION[_LICENSE_KEY_]['admin']['prefs']['datetime_format'],$rsrow['time']+$_SESSION[_LICENSE_KEY_]['admin']['prefs']['time_offset']).'"';
		$output.='},';
	}
	$output=substr($output,0,-1);
	$output.=']';
}

echo '{'.$output.'}';
