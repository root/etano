<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/rate_item.php
$Revision: 610 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/rating.inc.php';

$output='';
$error=false;
$id=0;
$topass=array();
if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	if (!empty($_REQUEST['t']) && !empty($_REQUEST['id']) && !empty($_REQUEST['vote'])) {
		$vote_type=$_REQUEST['t'];
		$id=(int)$_REQUEST['id'];
		$vote=(int)$_REQUEST['vote'];
		if ($vote_type=='photo') {
			$parent_field="photo_id";
			$parent_table="{$dbtable_prefix}user_photos";
			$table="{$dbtable_prefix}rating_photo";
		} elseif ($vote_type=='blog') {
			$parent_field="post_id";
			$parent_table="{$dbtable_prefix}blog_posts";
			$table="{$dbtable_prefix}rating_blog";
		} elseif ($vote_type=='profile') {
			$parent_field="fk_user_id";
			$parent_table="{$dbtable_prefix}user_profiles";
			$table="{$dbtable_prefix}rating_profile";
		} else {
			$error=true;
		}
		if (!$error) {
			$query="SELECT UNIX_TIMESTAMP(`date_voted`) FROM `$table` WHERE `fk_parent_id`=$id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `date_voted`>'".gmdate('Ymd235959')."'-INTERVAL 1 DAY";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']=$GLOBALS['_lang'][9];
			}
		}
		if (!$error) {
			$query="SELECT `fk_user_id` FROM `$parent_table` WHERE `$parent_field`=$id";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_result($res,0,0)==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']=$GLOBALS['_lang'][10];
			}
		}
		if (!$error) {
			$query="INSERT INTO `$table` SET `fk_parent_id`=$id,`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."',`vote`=$vote,`date_voted`='".gmdate('YmdHis')."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="UPDATE `$parent_table` SET `stat_votes`=`stat_votes`+1,`stat_votes_total`=`stat_votes_total`+'$vote' WHERE `$parent_field`=$id";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="SELECT `stat_votes`,`stat_votes_total` FROM `$parent_table` WHERE `$parent_field`=$id";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				list($num_votes,$total)=mysql_fetch_row($res);
				$output=array('score'=>(float)number_format($total/$num_votes,1),'num_votes'=>(int)$num_votes,'text'=>$GLOBALS['_lang'][255]);
			}
		}
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][11];
	}
} else {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=$GLOBALS['_lang'][12];
}

if (isset($_REQUEST['silent'])) {
	if ($error) {
		$output=array('error'=>true,'text'=>$topass['message']['text']);
	}
	require _BASEPATH_.'/includes/classes/services_json.class.php';
	$json=new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS | SERVICES_JSON_LOOSE_TYPE);
	echo $json->encode($output);
} else {
	if ($vote_type=='photo') {
		$nextpage='photo_view.php';
		$qs='photo_id='.$id;
	} elseif ($vote_type=='blog') {
		$nextpage='blog_post_view.php';
		$qs='pid='.$id;
	} elseif ($vote_type=='profile') {
		$nextpage='profile.php';
		$qs='uid='.$id;
	}
	redirect2page($nextpage,$topass,$qs);
}
