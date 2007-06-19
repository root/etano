<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/subscriptions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$subscriptions_default['defaults']=array('subscr_id'=>0,'subscr_name'=>'','subscr_diz'=>'','price'=>0,'currency'=>'','duration'=>0,'is_recurent'=>0,'m_value_from'=>0,'m_value_to'=>0,'is_visible'=>0);
$subscriptions_default['types']=array('subscr_id'=>FIELD_INT,'subscr_name'=>FIELD_TEXTFIELD,'subscr_diz'=>FIELD_TEXTFIELD,'price'=>FIELD_FLOAT,'currency'=>FIELD_TEXTFIELD,'duration'=>FIELD_INT,'is_recurent'=>FIELD_INT,'m_value_from'=>FIELD_INT,'m_value_to'=>FIELD_INT,'is_visible'=>FIELD_INT);
