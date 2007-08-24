<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/kb_articles.inc.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$kb_articles_default['defaults']=array('kba_id'=>0,'fk_kbc_id'=>0,'kba_title'=>'','kba_content'=>'');
$kb_articles_default['types']=array('kba_id'=>FIELD_INT,'fk_kbc_id'=>FIELD_INT,'kba_title'=>FIELD_TEXTFIELD,'kba_content'=>FIELD_TEXTAREA);
