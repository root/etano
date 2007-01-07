<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/loc_zipcodes.inc.php
$Revision: 72 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$zipcodes_default['defaults']=array('zip_id'=>0,'zipcode'=>'','fk_country_id'=>0,'fk_state_id'=>0,'fk_city_id'=>0,'latitude'=>0,'longitude'=>0);
$zipcodes_default['types']=array('zip_id'=>_HTML_INT_,'zipcode'=>_HTML_TEXTFIELD_,'fk_country_id'=>_HTML_INT_,'fk_state_id'=>_HTML_INT_,'fk_city_id'=>_HTML_INT_,'latitude'=>_HTML_FLOAT_,'longitude'=>_HTML_FLOAT_);
