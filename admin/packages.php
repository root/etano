<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/cpanel.php
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
require_once '../includes/classes/zip.class.php';
require_once '../includes/classes/fileop.class.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$output=array();

$query="SELECT `module_name`,`module_type`,`version` FROM `{$dbtable_prefix}modules` ORDER BY `module_name`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$installed=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['module_name']=sanitize_and_format($rsrow['module_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['module_type']=$accepted_module_types[$rsrow['module_type']];
	$installed[]=$rsrow;
}

$packages=array();
$d=dir(_BASEPATH_.'/tmp/packages');
$i=0;
while (false!==($entry=$d->read())) {
	if (substr($entry,-4)=='.zip') {
		$filename=substr($entry,0,-4);
		if (is_file($filename.'.info')) {
			$packages[$i]=file_get_contents($filename.'.info');
		} else {
			// read the manifest from the zip file
			$zipfile=new zipfile();
			$zipfile->read_zip(_BASEPATH_.'/tmp/packages/'.$entry);
			$found=false;
			foreach ($zipfile->files as $zfile) {
				if ($zfile['name']=='manifest.xml' && $zfile['dir']=='') {
					$found=true;
					$packages[$i]=$zfile['data'];
					break;
				}
			}
			if ($found) {
				// now save it as a separate file to speed things up next time
				$fileop=new fileop();
				$fileop->file_put_contents(_BASEPATH_.'/tmp/packages/'.$filename.'.info',$packages[$i]);
			}
		}
		++$i;
	}
}

$tpl->set_file('content','packages.html');
$tpl->set_loop('installed',$installed);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);

$tplvars['title']='Package manager';
$tplvars['page']='packages';
$tplvars['css']='packages.css';
include 'frame.php';
