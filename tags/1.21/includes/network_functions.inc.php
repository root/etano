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

/**
 *
 */
function get_network_members($user_id,$net_id,$limit=0) {
	$myreturn=array();
	global $dbtable_prefix;
	$query="SELECT `fk_user_id_other` FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id`=$user_id AND `fk_net_id`=$net_id AND `nconn_status`=1 ORDER BY `nconn_id` DESC";
	if (!empty($limit)) {
		$query.=" LIMIT $limit";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn[]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


function is_network_member($user_id,$other_id,$net_id=0,$excluded_nets=array()) {
	$myreturn=false;
	global $dbtable_prefix;
	if (!empty($user_id) && !empty($other_id)) {
		$query="SELECT b.`net_id`,b.`network` FROM `{$dbtable_prefix}user_networks` a,`{$dbtable_prefix}networks` b WHERE a.`fk_net_id`=b.`net_id` AND a.`fk_user_id`=$user_id AND a.`nconn_status`=1 AND a.`fk_user_id_other`=$other_id";
		if (!empty($net_id)) {
			$query.=" AND `fk_net_id`=$net_id";
		} elseif (!empty($excluded_nets)) {
			$query.=" AND `fk_net_id` NOT IN ('".join("','",$excluded_nets)."')";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$myreturn[]=$rsrow;
		}
	}
	return $myreturn;
}


function get_net_name($net_id) {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT `network` FROM `{$dbtable_prefix}networks` WHERE `net_id`=$net_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}
