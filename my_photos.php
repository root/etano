<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_photos.php
$Revision: 21 $
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
check_login_member(-1);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$input=array();
$output['o']=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$output['r']=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;

$where="`fk_user_id`='".$_SESSION['user']['user_id']."' AND `del`=0";
$from="`{$dbtable_prefix}user_photos`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	$query="SELECT *,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM $from WHERE $where LIMIT ".$output['o'].','.$output['r'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$tpl->set_file('temp','static/photo_gallery.html');
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_posted']=strftime($_user_settings['date_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
		$rsrow['is_private']=sprintf('%1s',empty($rsrow['is_private']) ? 'public' : 'private');	// translate this
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$tpl->set_var('photo',$rsrow);
		$loop[]=$tpl->process('','temp',TPL_OPTIONAL);
	}
	$tpl->drop_var('temp');
	$loop=smart_table($loop,3,'gallery_row');
	$tpl->set_var('pager2',pager($totalrows,$output['o'],$output['r']));
}

$tpl->set_file('content','my_photos.html');
$tpl->set_var('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);
$tpl->drop_var('loop');
$tpl->drop_var('output');

$tplvars['title']='My Photos';
$tplvars['page_title']='My Photos';
$tplvars['page']='my_photos';
$tplvars['css']='my_photos.css';
if (is_file('my_photos_left.php')) {
	include 'my_photos_left.php';
}
include 'frame.php';
?>