<?php
/******************************************************************************
Etano
===============================================================================
File:                       friendship_requests_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$tpl->set_file('left_content','friendship_requests_left.html');
$tpl->set_var('tplvars',$tplvars);
$tpl->process('left_content','left_content',TPL_OPTIONAL);
