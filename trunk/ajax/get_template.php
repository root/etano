<?php
/******************************************************************************
newdsb
===============================================================================
File:                       ajax/get_template.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/sessions.inc.php';
require_once dirname(__FILE__).'/../includes/vars.inc.php';
require_once dirname(__FILE__).'/remote_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$reason2template=array(1=>'reject_company.html',2=>'reject_sale.html',3=>'reject_buy.html',4=>'reject_member.html');
$type2file=array('join'=>'join.html','verify'=>'verify.html','lead'=>'lead.html','alerts'=>'alerts.html','maxjoin'=>'maxjoin.html','invoice'=>'invoice.html','block'=>'block.html','unblock'=>'unblock.html','dmail_show'=>'dmail_show.html','dmail_promotion'=>'dmail_promotion.html','reject_company'=>'reject_company.html','reject_buy'=>'reject_buy.html','reject_sale'=>'reject_sale.html','gen_email'=>'general_email.html','reject_member'=>'reject_member.html');

$output='';
if (isset($_GET['type']) && !empty($_GET['type']) && isset($type2file[$_GET['type']])) {
	$type=$_GET['type'];
	if (isset($_GET['skin']) && !empty($_GET['skin'])) {
		$skin=preg_replace("/[^\w]+/",'',$_GET['skin']);
	}
	if (is_file(_BASEPATH_.'/skins_site/'.get_my_template().'/emails/'.$type2file[$type])) {
		$output.=file_get_contents(_BASEPATH_.'/skins_site/'.get_my_template().'/emails/'.$type2file[$type]);
	}
}
echo $output;
