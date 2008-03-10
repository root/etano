<?php
/******************************************************************************
Etano
===============================================================================
File:                       photo_settings.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

//define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/photos.inc.php';
check_login_member('upload_photos');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$photo_ids=array();
if (isset($_SESSION['topass']['input'])) {
	$photo_ids=$_SESSION['topass']['input'];
} elseif (!empty($_GET['photo_ids'])) {
	$photo_ids=sanitize_and_format($_GET['photo_ids'],TYPE_INT,0,0);
	if (is_numeric($photo_ids)) {
		$photo_ids=array($photo_ids);
	}
}
$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$output['return']=rawurlencode($output['return2']);

$loop=array();
if (!empty($photo_ids)) {
	$query="SELECT `photo_id`,`photo`,`caption`,`is_main`,`is_private`,`allow_comments` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$rsrow['is_main']=$rsrow['is_main']==1 ? 'checked="checked"' : '';
		$rsrow['is_private']=$rsrow['is_private']==1 ? 'checked="checked"' : '';
		$rsrow['allow_comments']=$rsrow['allow_comments']==1 ? 'checked="checked"' : '';
		$loop[]=$rsrow;
	}
}

$tpl->set_file('content','photo_settings.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=$GLOBALS['_lang'][241];
$tplvars['page_title']=$GLOBALS['_lang'][241];
$tplvars['page']='photo_settings';
$tplvars['css']='photo_settings.css';
if (is_file('photo_settings_left.php')) {
	include 'photo_settings_left.php';
}
include 'frame.php';
