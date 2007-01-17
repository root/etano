<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/user_folders.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_folders_default['defaults']=array('folder_id'=>0,'fk_user_id'=>0,'folder'=>'');
$user_folders_default['types']=array('folder_id'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'folder'=>_HTML_TEXTFIELD_);
