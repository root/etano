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
$user_spambox_default['types']=array('mail_id'=>_HTML_INT_,'is_read'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'fk_user_id_other'=>_HTML_INT_,'_user_other'=>_HTML_TEXTFIELD_,'subject'=>_HTML_TEXTFIELD_,'message_body'=>_HTML_TEXTAREA_,'date_sent'=>_HTML_TEXTFIELD_,'message_type'=>_HTML_INT_);
