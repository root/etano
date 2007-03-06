<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/admin_accounts.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$admin_accounts_default['defaults']=array('admin_id'=>0,'user'=>'','pass'=>'','name'=>'','status'=>0,'dept_id'=>0,'email'=>'');
$admin_accounts_default['types']=array('admin_id'=>HTML_INT,'user'=>HTML_TEXTFIELD,'pass'=>HTML_TEXTFIELD,'name'=>HTML_TEXTFIELD,'status'=>HTML_INT,'dept_id'=>HTML_INT,'email'=>HTML_TEXTFIELD);
