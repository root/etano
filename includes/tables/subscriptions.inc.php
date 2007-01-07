<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/subscriptions.inc.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$subscriptions_default['defaults']=array('subscr_id'=>0,'subscr_name'=>'','subscr_diz'=>'','price'=>0,'currency'=>'','duration'=>0,'is_recurent'=>0,'m_value_from'=>0,'m_value_to'=>0,'is_visible'=>0);
$subscriptions_default['types']=array('subscr_id'=>_HTML_INT_,'subscr_name'=>_HTML_TEXTFIELD_,'subscr_diz'=>_HTML_TEXTFIELD_,'price'=>_HTML_FLOAT_,'currency'=>_HTML_TEXTFIELD_,'duration'=>_HTML_INT_,'is_recurent'=>_HTML_INT_,'m_value_from'=>_HTML_INT_,'m_value_to'=>_HTML_INT_,'is_visible'=>_HTML_INT_);
