<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/user_inbox.inc.php
$Revision: 25 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$message_filters_default['defaults']=array('filter_id'=>0,'filter_type'=>1,'fk_user_id'=>0,'field'=>'','field_value'=>'','fk_folder_id'=>0);
$message_filters_default['types']=array('filter_id'=>_HTML_INT_,'filter_type'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'field'=>_HTML_TEXTFIELD_,'field_value'=>_HTML_TEXTFIELD_,'fk_folder_id'=>_HTML_INT_);
