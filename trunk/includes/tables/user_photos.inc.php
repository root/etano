<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/user_photos.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_photos_default['defaults']=array('photo_id'=>0,'fk_user_id'=>0,'photo'=>'','is_main'=>0,'is_private'=>0,'allow_comments'=>0,'allow_rating'=>0,'caption'=>'','status'=>0,'date_posted'=>'','last_changed'=>'');
$user_photos_default['types']=array('photo_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'photo'=>FIELD_TEXTFIELD,'is_main'=>FIELD_INT,'is_private'=>FIELD_INT,'allow_comments'=>FIELD_INT,'allow_rating'=>FIELD_INT,'caption'=>FIELD_TEXTFIELD,'status'=>FIELD_INT,'date_posted'=>FIELD_TEXTFIELD,'last_changed'=>FIELD_TEXTFIELD);
