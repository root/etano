<?php
/******************************************************************************
newdsb
===============================================================================
File:                       search_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$my_searches=array();
if (isset($_SESSION['user']['user_id'])) {
	$query="SELECT `search_qs`,`title` FROM `{$dbtable_prefix}user_searches` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' ORDER BY `search_id` DESC LIMIT 5";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$__html2format[HTML_TEXTFIELD]);
		$my_searches[]=$rsrow;
	}
}

if (isset($_GET['v']) && $_GET['v']=='g') {
	$tpl->set_var('is_gallery_view',true);
}

$tpl->set_file('left_content','search_left.html');
$tpl->set_var('search',$search_md5);
$tpl->set_loop('my_searches',$my_searches);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_NOLOOP | TPL_OPTIONAL);
$tpl->drop_loop('my_searches');
?>