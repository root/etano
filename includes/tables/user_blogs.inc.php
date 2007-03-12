<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/user_blogs.inc.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_blogs_default['defaults']=array('blog_id'=>0,'fk_user_id'=>0,'blog_name'=>'','blog_diz'=>'','blog_skin'=>'','blog_url'=>'');
$user_blogs_default['types']=array('blog_id'=>HTML_INT,'fk_user_id'=>HTML_INT,'blog_name'=>HTML_TEXTFIELD,'blog_diz'=>HTML_TEXTAREA,'blog_skin'=>HTML_TEXTFIELD,'blog_url'=>HTML_TEXTFIELD);
