<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/frame.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

// this file is a simple included file. Most stuff must be defined outside for the main page to function properly.
// you need to include this file in each and every page.

$tpl->set_file('frame','frame.html');
$message=isset($message) ? $message : (isset($topass['message']) ? $topass['message'] : (isset($_SESSION['topass']['message']) ? $_SESSION['topass']['message'] : array()));
if (!empty($message)) {
	$message['type']=(!isset($message['type']) || $message['type']==MESSAGE_ERROR) ? 'message_error' : 'message_info';
	if (is_array($message['text'])) {
		$message['text']=join('<br />',$message['text']);
	}
	$tpl->set_var('message',$message);
}
$query="SELECT `link_url`,`link_text` FROM `{$dbtable_prefix}admin_menu` WHERE `link_type`=".LINKTYPE_MAIN;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$menu_links=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['link_text']=sanitize_and_format($rsrow['link_text'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$menu_links[]=$rsrow;
}
$tplvars['lk']=md5(_LICENSE_KEY_);
$tplvars['bu']=rawurlencode(base64_encode(_BASEURL_));
$tpl->set_var('tplvars',$tplvars);
$tpl->set_loop('menu_links',$menu_links);
echo $tpl->process('','frame',TPL_FINISH | TPL_OPTIONAL | TPL_INCLUDE | TPL_LOOP);
if (isset($_SESSION['topass'])) {
	unset($_SESSION['topass']);
}
