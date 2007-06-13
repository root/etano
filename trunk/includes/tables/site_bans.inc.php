<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/site_bans.inc.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$site_bans_default['defaults']=array('ban_id'=>0,'ban_type'=>0,'what'=>'','fk_lk_id_reason'=>0);
$site_bans_default['types']=array('ban_id'=>FIELD_INT,'ban_type'=>FIELD_INT,'what'=>FIELD_TEXTFIELD,'fk_lk_id_reason'=>FIELD_INT);
