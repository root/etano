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
$admin_accounts_default['types']=array('admin_id'=>_HTML_INT_,'user'=>_HTML_TEXTFIELD_,'pass'=>_HTML_TEXTFIELD_,'name'=>_HTML_TEXTFIELD_,'status'=>_HTML_INT_,'dept_id'=>_HTML_INT_,'email'=>_HTML_TEXTFIELD_);
