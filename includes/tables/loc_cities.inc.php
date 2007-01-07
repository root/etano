<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/loc_cities.inc.php
$Revision: 72 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$cities_default['defaults']=array('city_id'=>0,'city'=>'','latitude'=>0,'longitude'=>0,'fk_country_id'=>0,'fk_state_id'=>0);
$cities_default['types']=array('city_id'=>_HTML_INT_,'city'=>_HTML_TEXTFIELD_,'latitude'=>_HTML_FLOAT_,'longitude'=>_HTML_FLOAT_,'fk_country_id'=>_HTML_INT_,'fk_state_id'=>_HTML_INT_);
