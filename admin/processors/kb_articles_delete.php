<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/kb_articles_delete.php
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
$kba_id=isset($_GET['kba_id']) ? (int)$_GET['kba_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `fk_kbc_id` FROM `{$dbtable_prefix}kb_articles` WHERE `kba_id`=$kba_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$kbc_id=mysql_result($res,0,0);
$query="DELETE FROM `{$dbtable_prefix}kb_articles` WHERE `kba_id`=$kba_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$query="UPDATE `{$dbtable_prefix}kb_categs` SET `num_articles`=`num_articles`-1 WHERE `kbc_id`=$kbc_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

if (!isset($_GET['silent'])) {
	if (!empty($return)) {
		$nextpage=_BASEURL_.'/admin/'.$return;
	} else {
		$nextpage=_BASEURL_.'/admin/kb_categs.php';
	}
	redirect2page($nextpage,$topass,'',true);
} else {
	echo $kba_id;
}
