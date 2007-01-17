<?php
/******************************************************************************
newdsb
===============================================================================
File:                       show_captcha.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
require_once 'includes/classes/sco_captcha.class.php';

$captcha_word=$_SESSION['captcha_word'];
$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4,true,13,14,10,true);
header('Content-type: image/jpeg');
$c->make_captcha($captcha_word);
?>