<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/blog_posts.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$blog_posts_default['defaults']=array('post_id'=>0,'fk_user_id'=>0,'fk_blog_id'=>0,'is_public'=>1,'title'=>'','post_content'=>'','allow_comments'=>1);
$blog_posts_default['types']=array('post_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'fk_blog_id'=>FIELD_INT,'is_public'=>FIELD_INT,'title'=>FIELD_TEXTFIELD,'post_content'=>FIELD_TEXTAREA,'allow_comments'=>FIELD_INT);
