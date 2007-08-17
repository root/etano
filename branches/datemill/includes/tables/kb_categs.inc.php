<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/kb_categs.inc.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$kb_categs_default['defaults']=array('kbc_id'=>0,'fk_kbc_id_parent'=>0,'kbc_title'=>'');
$kb_categs_default['types']=array('kbc_id'=>FIELD_INT,'fk_kbc_id_parent'=>FIELD_INT,'kbc_title'=>FIELD_TEXTFIELD);
