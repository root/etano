<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/site_skins.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$site_skins_default['defaults']=array('fk_module_code'=>'','skin_dir'=>'','skin_name'=>'','fk_locale_id'=>0,'is_default'=>0);
$site_skins_default['types']=array('fk_module_code'=>HTML_TEXTFIELD,'skin_dir'=>HTML_TEXTFIELD,'skin_name'=>HTML_TEXTFIELD,'fk_locale_id'=>HTML_INT,'is_default'=>HTML_INT);