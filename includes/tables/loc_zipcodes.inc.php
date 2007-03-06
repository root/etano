<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/loc_zipcodes.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$zipcodes_default['defaults']=array('zip_id'=>0,'zipcode'=>'','fk_country_id'=>0,'fk_state_id'=>0,'fk_city_id'=>0,'latitude'=>0,'longitude'=>0);
$zipcodes_default['types']=array('zip_id'=>HTML_INT,'zipcode'=>HTML_TEXTFIELD,'fk_country_id'=>HTML_INT,'fk_state_id'=>HTML_INT,'fk_city_id'=>HTML_INT,'latitude'=>HTML_FLOAT,'longitude'=>HTML_FLOAT);
