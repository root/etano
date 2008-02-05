<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/admin_accounts.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$admin_accounts_default['defaults']=array('admin_id'=>0,'user'=>'','pass'=>'','name'=>'','status'=>0,'dept_id'=>0,'email'=>'');
$admin_accounts_default['types']=array('admin_id'=>FIELD_INT,'user'=>FIELD_TEXTFIELD,'pass'=>FIELD_TEXTFIELD,'name'=>FIELD_TEXTFIELD,'status'=>FIELD_INT,'dept_id'=>FIELD_INT,'email'=>FIELD_TEXTFIELD);
