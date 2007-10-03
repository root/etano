<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/user_sites.inc.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$user_sites_default['defaults']=array('site_id'=>0,'baseurl'=>'','remote_cron'=>0,'is_featured'=>0,'screenshot'=>'');
$user_sites_default['types']=array('site_id'=>FIELD_INT,'baseurl'=>FIELD_TEXTFIELD,'remote_cron'=>FIELD_INT,'is_featured'=>FIELD_INT,'screenshot'=>FIELD_TEXTFIELD);
