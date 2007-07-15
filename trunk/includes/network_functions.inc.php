<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/network_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

function get_network_members($user_id,$net_id,$limit=0) {
	$myreturn=array();
	global $dbtable_prefix;
	$query="SELECT `fk_user_id_other` FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id`='$user_id' AND `fk_net_id`='$net_id' AND `nconn_status`=1 ORDER BY `nconn_id` DESC";
	if (!empty($limit)) {
		$query.=" LIMIT $limit";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn[]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


function is_network_member($user_id,$other_id,$net_id) {
	$myreturn=false;
	global $dbtable_prefix;
	$query="SELECT `nconn_id` FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id`='$user_id' AND `fk_net_id`='$net_id' AND `fk_user_id_other`='$other_id' AND `nconn_status`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=true;
	}
	return $myreturn;
}


function get_net_name($net_id) {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT `network` FROM `{$dbtable_prefix}networks` WHERE `net_id`='$net_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}
