<?php
/******************************************************************************
newdsb
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
$subscriptions_default['types']=array('subscr_id'=>HTML_INT,'subscr_name'=>HTML_TEXTFIELD,'subscr_diz'=>HTML_TEXTFIELD,'price'=>HTML_FLOAT,'currency'=>HTML_TEXTFIELD,'duration'=>HTML_INT,'is_recurent'=>HTML_INT,'m_value_from'=>HTML_INT,'m_value_to'=>HTML_INT,'is_visible'=>HTML_INT);
