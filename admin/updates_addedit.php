<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/updates_addedit.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['update_id'])) {
	$update_id=(int)$_GET['update_id'];
	$query="SELECT `update_id`,`update_name`,`update_diz`,`filename` FROM `updates` WHERE `update_id`=$update_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['update_name']=sanitize_and_format($output['update_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$output['update_diz']=sanitize_and_format($output['update_diz'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$query="SELECT `module_code`,`version`,`min-version`,`max-version` FROM `update_requirements` WHERE `fk_update_id`=".$output['update_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$output['requires']=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$temp=$rsrow['module_code'];
			if ((float)$rsrow['version']!=0) {
				$temp.='='.$rsrow['version'];
			}
			if ((float)$rsrow['min-version']!=0) {
				$temp.='>='.$rsrow['min-version'];
			}
			if ((float)$rsrow['max-version']!=0) {
				$temp.='<='.$rsrow['max-version'];
			}
			$output['requires'][]=$temp;
		}
		$output['requires']=join(', ',$output['requires']);
	}
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$tpl->set_file('content','updates_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Module Update Management';
$tplvars['css']='updates_addedit.css';
$tplvars['page']='updates_addedit';
include 'frame.php';
