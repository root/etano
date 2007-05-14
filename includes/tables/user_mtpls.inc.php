<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/user_mtpls.inc.php
$Revision: 25 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_mtpls_default['defaults']=array('mtpl_id'=>0,'fk_user_id'=>0,'subject'=>'','message_body'=>'');
$user_mtpls_default['types']=array('mtpl_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'subject'=>FIELD_TEXTFIELD,'message_body'=>FIELD_TEXTAREA);
