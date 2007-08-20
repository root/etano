<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/ajax/get_kb_article.php
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

$dbtable_prefix='';

$output='';
if (isset($_GET['kba_id'])) {
	$kba_id=(int)$_GET['kba_id'];
	$query="SELECT `kba_id`,`fk_kbc_id`,`kba_title`,`kba_content` FROM `{$dbtable_prefix}kb_articles` WHERE `kba_id`=$kba_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$rsrow=mysql_fetch_assoc($res);
		$rsrow['kba_title']=sanitize_and_format($rsrow['kba_title'],TYPE_STRING,FORMAT_ADDSLASH);
		$rsrow['kba_content']=sanitize_and_format($rsrow['kba_content'],TYPE_STRING,FORMAT_ADDSLASH);
		$output="'kba_id':".$rsrow['kba_id'].",'fk_kbc_id': ".$rsrow['fk_kbc_id'].",'kba_title':'".$rsrow['kba_title']."','kba_content':'".$rsrow['kba_content']."'";
	}
}
echo '{'.$output.'}';
