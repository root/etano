<?php
/******************************************************************************
newdsb
===============================================================================
File:                       photo_view.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(13);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$photo_id=sanitize_and_format_gpc($_GET,'photo_id',TYPE_INT,0,0);
$user_id=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);

$photo=array();
$comments=array();
if (!empty($photo_id)) {
	$query="SELECT `photo_id`,`photo`,`caption`,`fk_user_id`,`_user` as `user`,`allow_comments` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$photo=mysql_fetch_assoc($res);
		$photo['caption']=sanitize_and_format($photo['caption'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$photo['user']=sanitize_and_format($photo['user'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$user_id=$photo['fk_user_id'];

		if (!empty($photo['allow_comments'])) {
			$query="SELECT a.`comment`,a.`fk_user_id`,a.`_user` as `user`,b.`_photo` as `photo` FROM `{$dbtable_prefix}photo_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_photo_id`='$photo_id' AND a.`status`=".PSTAT_APPROVED." ORDER BY a.date_posted ASC";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
				$rsrow['user']=sanitize_and_format($rsrow['user'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
				if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
					unset($rsrow['fk_user_id']);
				}
				$comments[]=$rsrow;
			}
			if (allow_at_level(9,$_SESSION['user']['membership'])) {
				$tpl->set_var('allow_comments',true);
			}
		}
	}
}

$tplvars['pic_width']=get_site_option('pic_width','core_photo');
$tplvars['bbcode_comments']=get_site_option('bbcode_comments','core');
if (empty($tplvars['bbcode_comments'])) {
	unset($tplvars['bbcode_comments']);
}
$tpl->set_file('content','photo_view.html');
$tpl->set_var('photo',$photo);
$tpl->set_var('user_id',$user_id);
$tpl->set_loop('comments',$comments);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('comments');

if (is_file('photo_view_left.php')) {
	include 'photo_view_left.php';
}
$tplvars['title']='View photos';
include 'frame.php';
?>