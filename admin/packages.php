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
require_once '../includes/classes/etano_package.class.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$output=array();
$fileop=new fileop();
$zipfile=new zipfile();

$query="SELECT `module_code`,`module_name`,`module_type`,`version` FROM `{$dbtable_prefix}modules` ORDER BY `module_type`,`sort`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$installed=array();
$mcodes=array();
$accepted_module_types2=$accepted_module_types;
$accepted_module_types2[MODULE_REGULAR]='Core';

while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['module_name']=sanitize_and_format($rsrow['module_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['module_type']=$accepted_module_types2[$rsrow['module_type']];
	$installed[]=$rsrow;
	$mcodes[$rsrow['module_code']]=$rsrow['version'];
}

// read the manifests of existing packages into $packages
$packages=array();
$d=dir(_BASEPATH_.'/tmp/packages');
$i=0;
$filenames=array();
while (false!==($entry=$d->read())) {
	if (substr($entry,-4)=='.zip') {
		$filename=substr($entry,0,-4);
		if (is_file(_BASEPATH_.'/tmp/packages/'.$filename.'.info')) {
			$filenames[$i]=$entry;
			$packages[$i++]=_BASEPATH_.'/tmp/packages/'.$filename.'.info';
		} else {
			// read the manifest from the zip file
			$zipfile->read_zip(_BASEPATH_.'/tmp/packages/'.$entry);
			$found=false;
			$manifest_content='';
			foreach ($zipfile->files as $zfile) {
				if ($zfile['name']=='manifest.xml' && $zfile['dir']=='/') {
					$found=true;
					$filenames[$i]=$entry;
					$manifest_content=$zfile['data'];
					break;
				}
			}
			if ($found) {
				// now save it as a separate file to speed things up next time
				$fileop->file_put_contents(_BASEPATH_.'/tmp/packages/'.$filename.'.info',$manifest_content);
				$packages[$i++]=_BASEPATH_.'/tmp/packages/'.$filename.'.info';
			}
		}
	}
}

$not_installed=array();
$m=0;
for ($i=0;isset($packages[$i]);++$i) {
	$p=new etano_package($packages[$i]);
	if (!isset($mcodes[$p->module_code]) || $mcodes[$p->module_code]<$p->version) {	// not installed packages
		$install_req_satisfied=0;
		$reasons=array();	// holds the reasons why this is not satisfied (if it isn't)
		$relevant_install=true;
		for ($j=0;isset($p->install[$j]);++$j) {
			$req_ok=true;
			for ($k=0;isset($p->install[$j]['requires'][$k]);++$k) {
				if (!isset($mcodes[$p->install[$j]['requires'][$k]['id']]) || (isset($p->install[$j]['requires'][$k]['version']) && ((float)$mcodes[$p->install[$j]['requires'][$k]['id']])!=((float)$p->install[$j]['requires'][$k]['version']))) {
					$req_ok=false;
					$reasons[$j][]=$p->install[$j]['requires'][$k]['id'].(isset($p->install[$j]['requires'][$k]['version']) ? ' ('.$p->install[$j]['requires'][$k]['version'].')' : '');
				}
			}
			$install_req_satisfied|=(int)$req_ok;
			if ($relevant_install && $req_ok) {
				$not_installed[$m]['text']=$p->install[$j]['text'];
				$relevant_install=false;
			}
		}
		if ($install_req_satisfied) {
			$not_installed[$m]['valid']=true;
			$not_installed[$m]['reasons']='No requirements. Package is valid.';
		} else {
			for ($n=0;isset($reasons[$n]);++$n) {
				$reasons[$n]=join(', ',$reasons[$n]);
			}
			$not_installed[$m]['reasons']='<strong>'.join('</strong> OR <strong>',$reasons).'</strong>';
		}
		$not_installed[$m]['module_name']=$p->module_name;
		$not_installed[$m]['module_type']=$accepted_module_types[$p->module_type];
		$not_installed[$m]['version']=$p->version;
		$not_installed[$m]['filename']=rawurlencode($filenames[$i]);
		++$m;
	}
}

$output['bu']=rawurlencode(base64_encode(_BASEURL_));

$tpl->set_file('content','packages.html');
$tpl->set_loop('installed',$installed);
$tpl->set_loop('not_installed',$not_installed);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL | TPL_OPTLOOP);

$tplvars['title']='Package manager';
$tplvars['page']='packages';
$tplvars['css']='packages.css';
include 'frame.php';
