<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/photo_comments.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$photo_comments_default['defaults']=array('comment_id'=>0,'fk_photo_id'=>0,'fk_user_id'=>0,'comment'=>'');
$photo_comments_default['types']=array('comment_id'=>_HTML_INT_,'fk_photo_id'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'comment'=>_HTML_TEXTAREA_);
