<?php
/******************************************************************************
datemill.com
===============================================================================
File:                       remote/sync.php
$Revision: 193 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/general_functions.inc.php';

$ident_return=true;
$site_id=require_once dirname(__FILE__).'/ident.php';
$installed_modules=sanitize_and_format_gpc($_POST,'module',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],array());

if (!empty($input['site_id'])) {
	if (!empty($installed_modules)) {
		$query="SELECT b.`module_code` FROM `user_products` a,`product_modules` b WHERE a.`fk_prod_id`=b.`fk_prod_id` AND a.`fk_site_id`=".$input['site_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$allowed_modules=array();
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$allowed_modules[mysql_result($res,$i,0)]=1;
		}

		$query="DELETE FROM `user_site_modules` WHERE `fk_site_id`=".$input['site_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="INSERT INTO `user_site_modules` VALUES ";
		$now=gmdate('YmdHis');
		foreach ($installed_modules as $mc=>$v) {
			$query.="(".$input['site_id'].",'$mc','$v',".(isset($allowed_modules[$mc]) ? 1 : 0).",'$now'),";
		}
		$query=substr($query,0,-1);
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	// this is a login backdoor with most of the code taken from processors/login.php
	// instead of using the user/pass combination to login we rely on the license_md5 to be provided as a $_POST parameter.
	// there should really be a login_user($user_id) function to take care of this.
	$query="SELECT b.`".USER_ACCOUNT_ID."` as `user_id`,b.`".USER_ACCOUNT_USER."` as `user`,b.`status`,b.`membership` FROM `user_products` a,".USER_ACCOUNTS_TABLE." b WHERE a.`fk_user_id`=b.`".USER_ACCOUNT_ID."` AND a.`fk_site_id`=".$input['site_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user=mysql_fetch_assoc($res);
		$user['membership']=(int)$user['membership'];
		$user['user_id']=(int)$user['user_id'];
		if ($user['status']==ASTAT_ACTIVE) {
			update_stats($user['user_id'],'last_sync',mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y')),'=');
			$user['prefs']=get_user_settings($user['user_id'],'def_user_prefs',array('date_format','datetime_format','time_offset'));
			$_SESSION[_LICENSE_KEY_]['user']=$user;
			redirect2page('site_updates.php',array(),'lk='.$input['lk']);
		}
	}
}
