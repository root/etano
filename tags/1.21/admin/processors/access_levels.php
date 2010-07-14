<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/access_levels.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
// first figure out what input we need:
	$query="SELECT `level_id` FROM `{$dbtable_prefix}access_levels`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$levels=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$levels[]=mysql_result($res,$i,0);
	}
	$query="SELECT `m_value` FROM `{$dbtable_prefix}memberships`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$memberships=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$memberships[]=mysql_result($res,$i,0);
	}

// calculate the levels
	$new_levels=array();
	for ($l=0;isset($levels[$l]);++$l) {
		for ($m=0;isset($memberships[$m]);++$m) {
			if (!isset($new_levels[$levels[$l]])) {
				$new_levels[$levels[$l]]=0;
			}
			if (isset($_POST['levels'][$levels[$l]][$memberships[$m]])) {
				$new_levels[$levels[$l]]+=$memberships[$m];
			}
		}
	}
// save in db
	foreach ($new_levels as $k=>$v) {
		$query="UPDATE `{$dbtable_prefix}access_levels` SET `level`='$v' WHERE `level_id`=$k";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

// save in file
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	require_once _BASEPATH_.'/includes/access_levels.inc.php';
	$query="SELECT `level_code`,`level` FROM `{$dbtable_prefix}access_levels`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$towrite="<?php\n\$GLOBALS['_access_level']=array('auth'=>65534,'all'=>65535";
	while ($rsrow=mysql_fetch_row($res)) {
		$towrite.=",'".$rsrow[0]."'=>".$rsrow[1];
	}
	$towrite.=");\n";
	$towrite.='$GLOBALS[\'_allow_na\']='.var_export($GLOBALS['_allow_na'],true).";\n";
	$fileop=new fileop();
	$fileop->file_put_contents(_BASEPATH_.'/includes/access_levels.inc.php',$towrite);

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Access levels changed.';
}
redirect2page('admin/access_levels.php',$topass,$qs);
