<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/queue_message.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$queue_message_default['defaults']=array('mail_id'=>0,'fk_user_id'=>0,'fk_user_id_from'=>0,'_user_from'=>'','subject'=>'','message_body'=>'','message_type'=>0);
$queue_message_default['types']=array('mail_id'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'fk_user_id_from'=>_HTML_INT_,'_user_from'=>_HTML_TEXTFIELD_,'subject'=>_HTML_TEXTFIELD_,'message_body'=>_HTML_TEXTAREA_,'message_type'=>_HTML_INT_);
