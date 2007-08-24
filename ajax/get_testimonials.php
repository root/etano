<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/get_testimonials.php
$Revision: 228 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/testimonials.inc.php';

$output='';
$tid=mt_rand(0,count($testimonials)-1);
$output.="'ttext': '".addslashes($testimonials[$tid]['ttext'])."'";
$output.=",'tname': '".addslashes($testimonials[$tid]['tname'])."'";

echo '{'.$output.'}';
