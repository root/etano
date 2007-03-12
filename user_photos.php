<?php
/******************************************************************************
newdsb
===============================================================================
File:                       user_photos.php
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
check_login_member(12);

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$uid=0;
$user='';
if (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=(int)$_GET['uid'];
	$user=get_user_by_userid($uid);
} elseif (isset($_GET['user']) && !isset($_GET['uid'])) {
	$user=sanitize_and_format($_GET['user'],TYPE_STRING,$__html2format[HTML_TEXTFIELD]);
	$uid=get_userid_by_user($user);
} elseif (isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id'])) {
	$uid=$_SESSION['user']['user_id'];
	$user=$_SESSION['user']['user'];
} else {
	redirect2page('index.php');
}

if (isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']==$uid) {
	redirect2page('my_photos.php');
}

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;

$where="`fk_user_id`='$uid' AND `del`=0";
if (!isset($_SESSION['user']['user_id']) || $uid!=$_SESSION['user']['user_id']) {
	$where.=" AND `is_private`=0 AND `status`=".PSTAT_APPROVED;
}
$from="`{$dbtable_prefix}user_photos`";

$editable=false;
if (isset($_SESSION['user']['user_id']) && $uid==$_SESSION['user']['user_id']) {
	$tpl->set_var('editable',true);
	$editable=true;
}

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$user_album=array();
if (!empty($totalrows)) {
	$query="SELECT *,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM $from WHERE $where LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$temp=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_posted']=sprintf('Uploaded on %1s',strftime($_user_settings['date_format'],$rsrow['date_posted']+$_user_settings['time_offset']));
		$rsrow['is_private']=sprintf('This photo is <strong>%1s</strong>',empty($rsrow['is_private']) ? 'public' : 'private');
		$rsrow['stat_comments']=sprintf('%1u comments',$rsrow['stat_comments']);
		$rsrow['stat_views']=sprintf('%1u views',$rsrow['stat_views']);
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$temp[]=$rsrow;
	}
	for ($i=0;isset($temp[$i]);++$i) {
		$user_album[$i]='<a href="photo_view.php?photo_id='.$temp[$i]['photo_id'].'&amp;o='.$o.'&amp;r='.$r.'" title="View photo &amp; comments"><img src="'.$tplvars['relative_path'].'media/pics/t1/'.$temp[$i]['photo'].'" /></a><p class="caption">'.$temp[$i]['caption'].'</p>';
		if ($editable) {
			$user_album[$i].='<p class="privacy">'.$temp[$i]['is_private'].' <a href="photo_settings.php?photo_ids='.$temp[$i]['photo_id'].'&amp;o='.$o.'&amp;r='.$r.'">Change</a></p>';
		}
		$user_album[$i].='<p class="date_posted">'.$temp[$i]['date_posted'];
		if ($editable) {
			$user_album[$i].=' | <a href="processors/photo_delete.php?photo_id='.$temp[$i]['photo_id'].'&amp;o='.$o.'&amp;r='.$r.'" onclick="return(confirm(\'Are you sure you want to delete this photo?\'))">Delete</a>';
		}
		$user_album[$i].='</p><p class="activity">'.$temp[$i]['stat_views'].' | <a href="photo_view.php?photo_id='.$temp[$i]['photo_id'].'&amp;o='.$o.'&amp;r='.$r.'">'.$temp[$i]['stat_comments'].'</a></p>';
	}
	$user_album=smart_table($user_album,3,'id="table_album"');
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}

$tpl->set_file('content','user_photos.html');
$tpl->set_var('user_album',$user_album);
$tpl->set_var('user',$user);
$tpl->set_var('uid',$uid);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->process('content','content',TPL_OPTIONAL);
$tpl->drop_var('user_album');

$tplvars['title']=sprintf('Photos from %1s',$user);
if (is_file('user_photos_left.php')) {
	include 'user_photos_left.php';
}
include 'frame.php';
?>