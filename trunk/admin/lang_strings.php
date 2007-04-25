<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/lang_strings.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$content='';
if (isset($_GET['s'])) {
	$skin=sanitize_and_format($_GET['s'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
	$query="SELECT * FROM `{$dbtable_prefix}lang_keys` ORDER BY `lk_id` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	$lang_strings=array();
	$temp=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$lang_strings[$i]=$rsrow;
		$lang_strings[$i]['lang_value']='';
		if ($rsrow['lk_type']==FIELD_TEXTFIELD) {
			$lang_strings[$i]['tf']=true;
		}
		$temp[$rsrow['lk_id']]=$i;
		++$i;
	}
	$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$skin'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$lang_strings[$temp[$rsrow['fk_lk_id']]]['lang_value']=$rsrow['lang_value'];
	}
	$lang_strings=sanitize_and_format($lang_strings,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);

	$tpl->set_file('content','lang_strings.html');
	$tpl->set_loop('lang_strings',$lang_strings);
	$tpl->set_var('skin',$skin);
	$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP);
	$tpl->drop_loop('lang_strings');
	unset($lang_strings);
}

$tplvars['title']='Language Strings';
include 'frame.php';
?>