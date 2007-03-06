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
$user_photos_default['types']=array('photo_id'=>HTML_INT,'fk_user_id'=>HTML_INT,'photo'=>HTML_TEXTFIELD,'is_main'=>HTML_INT,'is_private'=>HTML_INT,'allow_comments'=>HTML_INT,'caption'=>HTML_TEXTFIELD,'status'=>HTML_INT,'date_posted'=>HTML_TEXTFIELD,'last_changed'=>HTML_TEXTFIELD);
