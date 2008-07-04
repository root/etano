<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/rate_limiter.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$rate_limiter_default['defaults']=array('rate_id'=>0,'m_value'=>1,'level_code'=>'','limit'=>0,'interval'=>0,'punishment'=>1,'fk_lk_id_error_message'=>0);
$rate_limiter_default['types']=array('rate_id'=>FIELD_INT,'m_value'=>FIELD_INT,'level_code'=>FIELD_TEXTFIELD,'limit'=>FIELD_INT,'interval'=>FIELD_INT,'punishment'=>FIELD_INT,'fk_lk_id_error_message'=>FIELD_INT);
