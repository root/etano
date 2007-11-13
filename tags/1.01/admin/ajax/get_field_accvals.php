<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/ajax/get_field_accvals.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$output='';
if (!empty($_POST['field'])) {
	$dbfield=$_POST['field'];
	$accepted_values=array();
	foreach ($_pfields as $pfield_id=>$pfield) {
		if ($pfield['dbfield']==$dbfield) {
			$accepted_values=$pfield['accepted_values'];
		}
	}
	for ($i=0;isset($accepted_values[$i]);++$i) {
		$output.=$i.'|'.$accepted_values[$i]."\n";
	}
	$output=sanitize_and_format(substr($output,0,-1),TYPE_STRING,FORMAT_TEXT2HTML);
}
echo $output;
