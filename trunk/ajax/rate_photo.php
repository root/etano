<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/rate_photo.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';

$output='';
$error=false;
$photo_id=0;
$topass=array();
if (isset($_SESSION['user']['user_id'])) {
	if (!empty($_REQUEST['photo_id']) && !empty($_REQUEST['vote'])) {
		$photo_id=(int)$_REQUEST['photo_id'];
		$vote=(int)$_REQUEST['vote'];
		$query="SELECT UNIX_TIMESTAMP(`date_voted`) FROM `{$dbtable_prefix}photo_ratings` WHERE `fk_photo_id`='$photo_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."' AND `date_voted`>".gmdate('YmdHis')."-INTERVAL 1 DAY";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='You cannot vote more than once a day for the same photo';
		}
		if (!$error) {
			$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_result($res,0,0)==$_SESSION['user']['user_id']) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='You are not allowed to vote for your own photos';
			}
		}

		if (!$error) {
			$query="INSERT INTO `{$dbtable_prefix}photo_ratings` SET `fk_photo_id`='$photo_id',`fk_user_id`='".$_SESSION['user']['user_id']."',`vote`='$vote',`date_voted`='".gmdate('YmdHis')."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="UPDATE `{$dbtable_prefix}user_photos` SET `stat_votes`=`stat_votes`+1,`stat_votes_total`=`stat_votes_total`+'$vote' WHERE `photo_id`='$photo_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="SELECT `stat_votes`,`stat_votes_total` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				list($num_votes,$total)=mysql_fetch_row($res);
				$score=number_format($total/$num_votes,1);
				$output='1|'.$score.'|'.$num_votes;
			}
		}
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid vote received';
	}
} else {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='You must be logged in to rate photos.';
}
if (isset($_REQUEST['silent'])) {
	if ($error) {
		$output='0|'.$topass['message']['text'];
	}
	echo $output;
} else {
	$qs='photo_id='.$photo_id;
	redirect2page('photo_view.php',$topass,$qs);
}
