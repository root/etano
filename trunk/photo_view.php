<?php
/******************************************************************************
Etano
===============================================================================
File:                       photo_view.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

//define('CACHE_LIMITER','private');
require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/photos.inc.php';
check_login_member('view_photo');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');
$photo_id=sanitize_and_format_gpc($_GET,'photo_id',TYPE_INT,0,0);
$edit_comment=sanitize_and_format_gpc($_GET,'edit_comment',TYPE_INT,0,0);

$output=array();
$output['pic_width']=get_site_option('pic_width','core_photo');

$loop=array();
if (!empty($photo_id)) {
	$query="SELECT `photo_id`,`photo`,`caption`,`fk_user_id`,`_user` as `user`,`status`,`allow_comments`,`allow_rating`,`stat_votes`,`stat_votes_total` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=$photo_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		if ($output['status']==STAT_APPROVED || (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
			$output['caption']=sanitize_and_format($output['caption'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			if (!empty($output['allow_rating'])) {
				if ($output['stat_votes']>0) {
					$output['rate_num']=number_format($output['stat_votes_total']/$output['stat_votes'],1);
				} else {
					$output['rate_num']=0;
				}
				$output['rate_percent']=(int)(($output['rate_num']*100)/5);
			} else {
				unset($output['allow_rating']);
			}
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
				$output['photo_owner']=true;
			}

			// comments
			$config=get_site_option(array('use_captcha','bbcode_comments','smilies_comm'),'core');
			$loop_comments=create_comments_loop('photo',$output['photo_id'],$config,$output);

			// prev/next stuff
			$query="SELECT max(`photo_id`) FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`<$photo_id AND `is_private`=0 AND `fk_user_id`=".$output['fk_user_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$output['previous']=mysql_result($res,0,0);
			}
			$query="SELECT min(`photo_id`) FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`>$photo_id AND `is_private`=0 AND `fk_user_id`=".$output['fk_user_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$output['next']=mysql_result($res,0,0);
			}
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$GLOBALS['_lang'][6];
			redirect2page('info.php',$topass);
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][6];
		redirect2page('info.php',$topass);
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=$GLOBALS['_lang'][6];
	redirect2page('info.php',$topass);
}
$output['lang_256']=sanitize_and_format($GLOBALS['_lang'][256],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$output['return2me']='photo_view.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);

$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$output['return']=rawurlencode($output['return2']);

$tpl->set_file('content','photo_view.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop_comments',$loop_comments);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop_comments');
unset($loop_comments);

$tplvars['title']=sprintf($GLOBALS['_lang'][143],$output['user']);
$tplvars['page_title']=sprintf($GLOBALS['_lang'][143],'<a href="'.$tplvars['relative_url'].'photo_search.php?st=user&amp;uid='.$output['fk_user_id'].'">'.$output['user'].'</a>');
$tplvars['page']='photo_view';
$tplvars['css']='photo_view.css';
if (is_file('photo_view_left.php')) {
	include 'photo_view_left.php';
}
include 'frame.php';
if (!empty($photo_id) && isset($output['fk_user_id']) && ((!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']!=$_SESSION[_LICENSE_KEY_]['user']['user_id']) || empty($_SESSION[_LICENSE_KEY_]['user']['user_id']))) {
	$query="UPDATE `{$dbtable_prefix}user_photos` SET `stat_views`=`stat_views`+1 WHERE `photo_id`=$photo_id";
	@mysql_query($query);
}
