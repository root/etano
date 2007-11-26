<?php
/******************************************************************************
Etano
===============================================================================
File:                       reject_reason.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

if (isset($_GET['m'])) {
	$id=sanitize_and_format_gpc($_GET,'id',TYPE_INT,0,0);

	$continue=false;
	switch ($_GET['m']) {

		case 'photo':
			$query="SELECT `reject_reason` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=$id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
			$continue=true;
			break;

		case 'profile':
			$query="SELECT `reject_reason` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
			$continue=true;
			break;

		case 'blog':
			$query="SELECT `reject_reason` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`=$id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
			$continue=true;
			break;
	}
	if ($continue) {
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			echo mysql_result($res,0,0);
		}
	}
}
