<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/blog_comments.inc.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$blog_comments_default['defaults']=array('comment_id'=>0,'fk_post_id'=>0,'fk_user_id'=>0,'comment'=>'');
$blog_comments_default['types']=array('comment_id'=>HTML_INT,'fk_post_id'=>HTML_INT,'fk_user_id'=>HTML_INT,'comment'=>HTML_TEXTAREA);
