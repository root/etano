<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/user_spambox.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_spambox_default['defaults']=array('mail_id'=>0,'is_read'=>0,'fk_user_id'=>0,'fk_user_id_other'=>0,'_user_other'=>'','subject'=>'','message_body'=>'','date_sent'=>'','message_type'=>0);
$user_spambox_default['types']=array('mail_id'=>FIELD_INT,'is_read'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'fk_user_id_other'=>FIELD_INT,'_user_other'=>FIELD_TEXTFIELD,'subject'=>FIELD_TEXTFIELD,'message_body'=>FIELD_TEXTAREA,'date_sent'=>FIELD_TEXTFIELD,'message_type'=>FIELD_INT);
