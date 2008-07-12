<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/message_filters.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$message_filters_default['defaults']=array('filter_id'=>0,'filter_type'=>FILTER_SENDER,'fk_user_id'=>0,'field'=>'','field_value'=>'','fk_folder_id'=>0);
$message_filters_default['types']=array('filter_id'=>FIELD_INT,'filter_type'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'field'=>FIELD_TEXTFIELD,'field_value'=>FIELD_TEXTFIELD,'fk_folder_id'=>FIELD_INT);
