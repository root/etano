<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/loc_cities.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$cities_default['defaults']=array('city_id'=>0,'city'=>'','latitude'=>0,'longitude'=>0,'fk_country_id'=>0,'fk_state_id'=>0);
$cities_default['types']=array('city_id'=>FIELD_INT,'city'=>FIELD_TEXTFIELD,'latitude'=>FIELD_FLOAT,'longitude'=>FIELD_FLOAT,'fk_country_id'=>FIELD_INT,'fk_state_id'=>FIELD_INT);
