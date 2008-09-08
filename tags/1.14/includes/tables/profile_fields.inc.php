<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/profile_fields.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$profile_fields_default['defaults']=array('pfield_id'=>0,'fk_lk_id_label'=>0,'field_type'=>'','searchable'=>0,'search_type'=>'','for_basic'=>0,'fk_lk_id_search'=>0,'at_registration'=>0,'reg_page'=>1,'required'=>0,'editable'=>0,'visible'=>0,'dbfield'=>'','fk_lk_id_help'=>0,'fk_pcat_id'=>0,'custom_config'=>'','fn_on_change'=>'','order_num'=>0);
$profile_fields_default['types']=array('pfield_id'=>FIELD_INT,'fk_lk_id_label'=>FIELD_INT,'field_type'=>FIELD_TEXTFIELD,'searchable'=>FIELD_INT,'search_type'=>FIELD_TEXTFIELD,'for_basic'=>FIELD_INT,'fk_lk_id_search'=>FIELD_INT,'at_registration'=>FIELD_INT,'reg_page'=>FIELD_INT,'required'=>FIELD_INT,'editable'=>FIELD_INT,'visible'=>FIELD_INT,'dbfield'=>FIELD_TEXTFIELD,'fk_lk_id_help'=>FIELD_INT,'fk_pcat_id'=>FIELD_INT,'custom_config'=>FIELD_TEXTAREA,'fn_on_change'=>FIELD_TEXTFIELD,'order_num'=>FIELD_INT);
