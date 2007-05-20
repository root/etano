<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/profile_comments.inc.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$profile_comments_default['defaults']=array('comment_id'=>0,'fk_parent_id'=>0,'fk_user_id'=>0,'comment'=>'');
$profile_comments_default['types']=array('comment_id'=>FIELD_INT,'fk_parent_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'comment'=>FIELD_TEXTAREA);
