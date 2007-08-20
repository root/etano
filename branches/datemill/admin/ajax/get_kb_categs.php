<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/ajax/get_kb_categs.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$output='';
$dbtable_prefix='';
if (isset($_GET['kbc_id'])) {
	$kbc_id=(int)$_GET['kbc_id'];
	$output="'id':$kbc_id,'subcategs': [";
	$query="SELECT `kbc_id`,`kbc_title`,`num_articles` FROM `{$dbtable_prefix}kb_categs` WHERE `fk_kbc_id_parent`=$kbc_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['kbc_title']=sanitize_and_format($rsrow['kbc_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			$output.="{'id':".$rsrow['kbc_id'].",'title':'".$rsrow['kbc_title']."','num_articles':".$rsrow['num_articles']."},";
		}
		$output=substr($output,0,-1);
	}
	$output.=']';
}
echo '{'.$output.'}';
