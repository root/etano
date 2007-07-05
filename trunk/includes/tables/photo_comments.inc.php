<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/photo_comments.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$photo_comments_default['defaults']=array('comment_id'=>0,'fk_parent_id'=>0,'fk_user_id'=>0,'comment'=>'');
$photo_comments_default['types']=array('comment_id'=>FIELD_INT,'fk_parent_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'comment'=>FIELD_TEXTAREA);
