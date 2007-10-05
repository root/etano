<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/user_products.inc.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$user_products_default['defaults']=array('uprod_id'=>0,'fk_prod_id'=>0,'fk_site_id'=>0,'fk_user_id'=>0,'processor'=>'','orderno'=>'','date_purchased'=>'0000-00-00','license'=>'','license_md5'=>'');
$user_products_default['types']=array('uprod_id'=>FIELD_INT,'fk_prod_id'=>FIELD_INT,'fk_site_id'=>FIELD_INT,'fk_user_id'=>FIELD_INT,'processor'=>FIELD_TEXTFIELD,'orderno'=>FIELD_TEXTFIELD,'date_purchased'=>FIELD_TEXTFIELD,'license'=>FIELD_TEXTFIELD,'license_md5'=>FIELD_TEXTFIELD);
