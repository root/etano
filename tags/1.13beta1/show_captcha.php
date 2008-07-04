<?php
/******************************************************************************
Etano
===============================================================================
File:                       show_captcha.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/classes/sco_captcha.class.php';

$captcha_word=$_SESSION['captcha_word'];
$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4,true,13,14,10,true);
header('Content-type: image/jpeg');
$c->make_captcha($captcha_word);
