<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/tables/rate_limiter.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$rate_limiter_default['defaults']=array('rate_id'=>0,'m_value'=>1,'fk_level_id'=>0,'limit'=>0,'interval'=>0,'punishment'=>1);
$rate_limiter_default['types']=array('rate_id'=>HTML_INT,'m_value'=>HTML_INT,'fk_level_id'=>HTML_INT,'limit'=>HTML_INT,'interval'=>HTML_INT,'punishment'=>HTML_INT);
