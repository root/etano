<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/tables/site_news.inc.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$site_news_default['defaults']=array('news_id'=>0,'news_title'=>'','news_body'=>'','date_posted'=>'');
$site_news_default['types']=array('news_id'=>FIELD_INT,'news_title'=>FIELD_TEXTFIELD,'news_body'=>FIELD_TEXTAREA,'date_posted'=>FIELD_TEXTFIELD);
