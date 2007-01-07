<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/network_functions.inc.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

function get_network_members($user_id,$net_id,$limit=0) {
	$myreturn=array();
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="SELECT `fk_user_id_friend` FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id`='$user_id' AND `fk_net_id`='$net_id' AND `nconn_status`=1";
	if (!empty($limit)) {
		$query.=" LIMIT $limit";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn[]=mysql_result($res,$i,0);
	}
	return $myreturn;
}
