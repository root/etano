<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/kb_categs_delete.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$dbtable_prefix='';

$qs='';
$qs_sep='';
$topass=array();
$kbc_id=isset($_GET['kbc_id']) ? (int)$_GET['kbc_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$kbc_ids=array($kbc_id);
$temp=array($kbc_id);
while (!empty($temp)) {
	$query="SELECT `kbc_id` FROM `{$dbtable_prefix}kb_categs` WHERE `fk_kbc_id_parent` IN ('".join("','",$temp)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$temp=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$temp[]=mysql_result($res,$i,0);
	}
	$kbc_ids=array_merge($kbc_ids,$temp);
}

$query="DELETE FROM `{$dbtable_prefix}kb_articles` WHERE `fk_kbc_id` IN ('".join("','",$kbc_ids)."')";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$query="DELETE FROM `{$dbtable_prefix}kb_categs` WHERE `kbc_id` IN ('".join("','",$kbc_ids)."')";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

if (!isset($_GET['silent'])) {
	if (!empty($return)) {
		$nextpage=_BASEURL_.'/admin/'.$return;
	} else {
		$nextpage=_BASEURL_.'/admin/kb_categs.php';
	}
	redirect2page($nextpage,$topass,'',true);
} else {
	echo $kbc_id;
}
