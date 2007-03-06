<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/user_spambox.inc.php
$Revision: 25 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_spambox_default['defaults']=array('mail_id'=>0,'is_read'=>0,'fk_user_id'=>0,'fk_user_id_other'=>0,'_user_other'=>'','subject'=>'','message_body'=>'','date_sent'=>'','message_type'=>0);
$user_spambox_default['types']=array('mail_id'=>HTML_INT,'is_read'=>HTML_INT,'fk_user_id'=>HTML_INT,'fk_user_id_other'=>HTML_INT,'_user_other'=>HTML_TEXTFIELD,'subject'=>HTML_TEXTFIELD,'message_body'=>HTML_TEXTAREA,'date_sent'=>HTML_TEXTFIELD,'message_type'=>HTML_INT);
