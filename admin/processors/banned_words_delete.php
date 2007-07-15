<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/banned_words_delete.php
$Revision$
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$word_id=isset($_GET['word_id']) ? (int)$_GET['word_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}banned_words` WHERE `word_id`='$word_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

if (!$error) {
	// save in file
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	$query="SELECT `word` FROM `{$dbtable_prefix}banned_words`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$towrite[]=mysql_result($res,$i,0);
	}
	$towrite='<?php $_banned_words='.var_export($towrite,true).';';
	$fileop=new fileop();
	$fileop->file_put_contents(_BASEPATH_.'/includes/banned_words.inc.php',$towrite);
}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Word deleted.';
redirect2page('admin/banned_words.php',$topass,$qs);
