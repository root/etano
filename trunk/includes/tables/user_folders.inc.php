<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/user_folders.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$user_folders_default['defaults']=array('folder_id'=>0,'fk_user_id'=>0,'folder'=>'');
$user_folders_default['types']=array('folder_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'folder'=>FIELD_TEXTFIELD);
