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

$user_sites_default['defaults']=array('site_id'=>0,'fk_user_id'=>0,'baseurl'=>'','ftp_user'=>'','ftp_pass'=>'','active'=>0,'license'=>'','license_md5'=>'','remote_cron'=>0,'is_featured'=>0,'screenshot'=>'');
$user_sites_default['types']=array('site_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'baseurl'=>FIELD_TEXTFIELD,'ftp_user'=>FIELD_TEXTFIELD,'ftp_pass'=>FIELD_TEXTFIELD,'active'=>FIELD_INT,'license'=>FIELD_TEXTFIELD,'license_md5'=>FIELD_TEXTFIELD,'remote_cron'=>FIELD_INT,'is_featured'=>FIELD_INT,'screenshot'=>FIELD_TEXTFIELD);
