<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/site_skins.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$site_skins=array();
$query="SELECT a.`module_code`,a.`module_name`,a.`version`,b.`config_option`,b.`config_value` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_code`=b.`fk_module_code` AND a.`module_type`=".MODULE_SKIN;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$i=-1;
$last_code='';
while ($rsrow=mysql_fetch_assoc($res)) {
	if ($rsrow['module_code']!=$last_code) {
		// sanitize previous row
		if ($i>=0) {
			$site_skins[$i]=sanitize_and_format($site_skins[$i],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			if (!empty($site_skins[$i]['is_default'])) {
				$site_skins[$i]['is_default']='<img src="skin/images/check.gif" />';
			} else {
				unset($site_skins[$i]['is_default']);
			}
		}
		++$i;
		$site_skins[$i]['module_code']=$rsrow['module_code'];
		$site_skins[$i]['skin_name']=$rsrow['module_name'].' '.$rsrow['version'];
		$last_code=$rsrow['module_code'];
	}
	$site_skins[$i][$rsrow['config_option']]=$rsrow['config_value'];
}
// one more time for the last row
if ($i>=0) {
	$site_skins[$i]=sanitize_and_format($site_skins[$i],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	if (!empty($site_skins[$i]['is_default'])) {
		$site_skins[$i]['is_default']='<img src="skin/images/check.gif" />';
	} else {
		unset($site_skins[$i]['is_default']);
	}
}

$tpl->set_file('content','site_skins.html');
$tpl->set_loop('site_skins',$site_skins);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP);
$tpl->drop_loop('site_skins');

$tplvars['title']='Skin Settings';
$tplvars['page']='site_skins';
include 'frame.php';
