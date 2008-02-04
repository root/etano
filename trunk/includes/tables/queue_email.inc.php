<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/queue_email.inc.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$queue_email_default['defaults']=array('mail_id'=>0,'to'=>'','subject'=>'','message_body'=>'');
$queue_email_default['types']=array('mail_id'=>FIELD_INT,'to'=>FIELD_TEXTFIELD,'subject'=>FIELD_TEXTFIELD,'message_body'=>FIELD_TEXTAREA);
