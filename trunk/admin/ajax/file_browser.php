<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/ajax/file_browser.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$path=str_replace('..','',preg_replace('~[^a-zA-Z0-9\._/-]~','',sanitize_and_format_gpc($_POST,'path',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'')));
if (!empty($path) && $path{0}=='/') {
	$path=substr($path,1);
}
$path_parts=explode('/',$path);
$d=dir(_BASEPATH_.'/'.$path);
$i=0;
$folders=array();
$j=0;
$files=array();
while (false!==($entry=$d->read())) {
	if ($entry!='.' && $entry!='..') {
		$class='';
		if (is_dir(_BASEPATH_.'/'.$path.'/'.$entry)) {
			$folders[$i]['file']='<a href="javascript:;" onclick="req_content(\''.$path.'/'.$entry.'\')">'.$entry."</a>";
			$folders[$i]['class']='folder';
			++$i;
		} else {
			$ext=strtolower(substr(strrchr($entry,'.'),1));
			switch ($ext) {

				case 'php':
					$files[$j]['file']='<a href="file_edit.php?m=1&f='.urlencode($path.'/'.$entry).'">'.$entry.'</a>';
					$files[$j]['class']='file_php';
					++$j;
					break;

				case 'html':
					$files[$j]['file']='<a href="file_edit.php?m=2&f='.urlencode($path.'/'.$entry).'">'.$entry.'</a>';
					$files[$j]['class']='file_html';
					++$j;
					break;

				case 'gif':
				case 'jpg':
				case 'png':
					$files[$j]['file']='<a href="javascript:;">'.$entry.'<img src="'._BASEURL_.'/'.$path.'/'.$entry.'" /></a>';
					$files[$j]['class']='file_img';
					++$j;
					break;

				default:
					$files[$j]['file']='<a href="file_edit.php?m=1&f='.urlencode($path.'/'.$entry).'">'.$entry.'</a>';
					$files[$j]['class']='file';
					++$j;

			}
		}
	}
}
$d->close();
for ($j=0;isset($files[$j]);++$j) {
	$folders[$i+$j]=$files[$j];
}

$html_path='<a href="javascript:;" onclick="req_content(\'\')">Home</a>';
$temp='';
for ($i=0;!empty($path_parts[$i]);++$i) {
	$temp.='/'.$path_parts[$i];
	$html_path.=' / <a href="javascript:;" onclick="req_content(\''.$temp.'\')">'.$path_parts[$i].'</a>';
}

$tpl=new phemplate(_BASEPATH_.'/admin/skin/','remove_nonjs');
$tpl->set_file('content','file_browser_content.html');
$tpl->set_loop('server_content',$folders);
$tpl->set_var('path',$html_path);
echo $tpl->process('','content',TPL_LOOP);
?>