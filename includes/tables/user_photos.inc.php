<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/user_photos.inc.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_photos_default['defaults']=array('photo_id'=>0,'fk_user_id'=>0,'photo'=>'','is_main'=>0,'is_private'=>0,'allow_comments'=>0,'caption'=>'','status'=>0,'date_posted'=>'','last_changed'=>'');
$user_photos_default['types']=array('photo_id'=>_HTML_INT_,'fk_user_id'=>_HTML_INT_,'photo'=>_HTML_TEXTFIELD_,'is_main'=>_HTML_INT_,'is_private'=>_HTML_INT_,'allow_comments'=>_HTML_INT_,'caption'=>_HTML_TEXTFIELD_,'status'=>_HTML_INT_,'date_posted'=>_HTML_TEXTFIELD_,'last_changed'=>_HTML_TEXTFIELD_);
