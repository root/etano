<?php
/******************************************************************************
newdsb
===============================================================================
File:                       photo_view.php
$Revision$
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

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$photo_id=sanitize_and_format_gpc($_GET,'photo_id',TYPE_INT,0,0);

$tplvars['pic_width']=get_site_option('pic_width','core_photo');
$tplvars['bbcode_comments']=get_site_option('bbcode_comments','core');

$output=array();
$loop=array();
if (!empty($photo_id)) {
	$query="SELECT `photo_id`,`photo`,`caption`,`fk_user_id`,`_user` as `user`,`allow_comments` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['caption']=sanitize_and_format($output['caption'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

		if (!empty($output['allow_comments'])) {
			$query="SELECT a.`comment`,a.`fk_user_id`,a.`_user` as `user`,b.`_photo` as `photo`,c.`last_activity` as `is_online` FROM `{$dbtable_prefix}photo_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` LEFT JOIN `{$dbtable_prefix}online` c ON a.`fk_user_id`=c.`fk_user_id` WHERE a.`fk_photo_id`='".$output['photo_id']."' AND a.`status`=".PSTAT_APPROVED." GROUP BY a.`comment_id`,a.`fk_user_id` ORDER BY a.date_posted ASC";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
				if (!empty($tplvars['bbcode_comments'])) {
					$rsrow['comment']=bbcode2html($rsrow['comment']);
				}
				if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
					unset($rsrow['fk_user_id']);
				}
				if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
					$rsrow['photo']='no_photo.gif';
				}
				if (!empty($rsrow['is_online'])) {	// for the link to member profile
					$rsrow['is_online']='is_online';
				}
				$loop[]=$rsrow;
			}
			if (allow_at_level(9,$_SESSION['user']['membership'])) {
				$tpl->set_var('allow_comments',true);
			}
		}
	}
}

$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
$output['return']=rawurlencode($output['return2']);

if (empty($tplvars['bbcode_comments'])) {
	unset($tplvars['bbcode_comments']);
}
$tpl->set_file('content','photo_view.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('comments');

$tplvars['title']='View photos';
$tplvars['page_title']=sprintf('%s photos','<a href="profile.php?uid='.$output['fk_user_id'].'">'.$output['user'].'</a>');	// translate this
$tplvars['page']='photo_view';
$tplvars['css']='photo_view.css';
if (is_file('photo_view_left.php')) {
	include 'photo_view_left.php';
}
include 'frame.php';
?>