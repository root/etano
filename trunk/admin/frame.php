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
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

// this file is a simple included file. Most stuff must be defined outside for the main page to function properly.
// you need to include this file in each and every page.

$tpl->set_file('frame','frame.html');
$message=isset($message) ? $message : (isset($topass['message']) ? $topass['message'] : (isset($_SESSION['topass']['message']) ? $_SESSION['topass']['message'] : array()));
if (!empty($message)) {
	$message['type']=(!isset($message['type']) || $message['type']==MESSAGE_ERROR) ? 'message_error' : 'message_info';
	if (is_array($message['text'])) {
		$message['text']=join('<br>',$message['text']);
	}
	$tpl->set_var('message',$message);
}
$tplvars['lk']=md5(_LICENSE_KEY_);
$tpl->set_var('tplvars',$tplvars);
echo $tpl->process('','frame',TPL_FINISH | TPL_OPTIONAL | TPL_INCLUDE);
if (isset($_SESSION['topass'])) {
	unset($_SESSION['topass']);
}
