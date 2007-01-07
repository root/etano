<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/blog_posts.inc.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$blog_posts_default['defaults']=array('post_id'=>0,'fk_post_id_parent'=>0,'fk_user_id'=>0,'is_public'=>1,'title'=>'','photo'=>'','post_content'=>'','allow_comments'=>0);
$blog_posts_default['types']=array('post_id'=>_HTML_INT_,'fk_post_id_parent'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'is_public'=>_HTML_INT_,'title'=>_HTML_TEXTFIELD_,'photo'=>_HTML_TEXTFIELD_,'post_content'=>_HTML_TEXTAREA_,'allow_comments'=>_HTML_INT_);
