<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/loc_zipcodes.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$zipcodes_default['defaults']=array('zip_id'=>0,'zipcode'=>'','fk_country_id'=>0,'fk_state_id'=>0,'fk_city_id'=>0,'latitude'=>0,'longitude'=>0);
$zipcodes_default['types']=array('zip_id'=>FIELD_INT,'zipcode'=>FIELD_TEXTFIELD,'fk_country_id'=>FIELD_INT,'fk_state_id'=>FIELD_INT,'fk_city_id'=>FIELD_INT,'latitude'=>FIELD_FLOAT,'longitude'=>FIELD_FLOAT);
