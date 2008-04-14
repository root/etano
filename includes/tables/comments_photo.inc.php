<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/comments_photo.inc.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$comments_photo_default['defaults']=array('comment_id'=>0,'fk_parent_id'=>0,'fk_user_id'=>0,'comment'=>'');
$comments_photo_default['types']=array('comment_id'=>FIELD_INT,'fk_parent_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'comment'=>FIELD_TEXTAREA);
