<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/user_blogs.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$user_blogs_default['defaults']=array('blog_id'=>0,'fk_user_id'=>0,'blog_name'=>'','blog_diz'=>'','blog_skin'=>'','blog_url'=>'');
$user_blogs_default['types']=array('blog_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'blog_name'=>FIELD_TEXTFIELD,'blog_diz'=>FIELD_TEXTAREA,'blog_skin'=>FIELD_TEXTFIELD,'blog_url'=>FIELD_TEXTFIELD);
