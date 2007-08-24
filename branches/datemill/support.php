<?php
/******************************************************************************
Etano
===============================================================================
File:                       support.php
$Revision: 271 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';

$dbtable_prefix='';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

$kbc_id=isset($_GET['kbc_id']) ? (int)$_GET['kbc_id'] : 0;

// tracer
$tracer=array();
get_kbc_parents($kbc_id);

function get_kbc_parents($kbc_id) {
	global $dbtable_prefix;
	$query="SELECT `kbc_id`,`kbc_title`,`fk_kbc_id_parent` as `parent_id` FROM `{$dbtable_prefix}kb_categs` WHERE `kbc_id`=$kbc_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$rsrow=mysql_fetch_assoc($res);
		if ($rsrow['kbc_id']!=0) {
			$GLOBALS['tracer'][$rsrow['kbc_id']]=$rsrow['kbc_title'];
			if ($rsrow['parent_id']!=0) {
				get_kbc_parents($rsrow['parent_id']);
			} else {
				$GLOBALS['tracer'][0]='Knowledge Base';
			}
		} else {
			$GLOBALS['tracer'][0]='Knowledge Base';
		}
	} else {
		$GLOBALS['tracer'][0]='Knowledge Base';
	}
}
$tracer=array_reverse($tracer,true);

$output['tracer']='';
foreach ($tracer as $k=>$v) {
	$output['tracer'].='<a href="'._BASEURL_.'/kb/'.$k.'/'.strtolower(preg_replace(array('/[^a-zA-Z0-9]/','/--+/'),array('-',''),$v)).'">'.$v.'</a> &raquo; ';
}
$output['tracer']=substr($output['tracer'],0,-9);

// subcategories
$query="SELECT `kbc_id`,`kbc_title` FROM `{$dbtable_prefix}kb_categs` WHERE `fk_kbc_id_parent`=$kbc_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$subcategs=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['kbc_title']=sanitize_and_format($rsrow['kbc_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['kbc_title_url']=strtolower(preg_replace(array('/[^a-zA-Z0-9]/','/--+/'),array('-',''),$rsrow['kbc_title']));
	$subcategs[]=$rsrow;
}
if (!empty($subcategs)) {
	$output['has_subcategs']=true;
}

// articles in this category
$query="SELECT count(*) FROM `{$dbtable_prefix}kb_articles` WHERE `fk_kbc_id`=$kbc_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$articles=array();
if ($totalrows) {
	$query="SELECT `kba_id`,`kba_title`,`kba_content` FROM `{$dbtable_prefix}kb_articles` WHERE `fk_kbc_id`=$kbc_id LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['kba_title']=sanitize_and_format($rsrow['kba_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
// don't sanitize the content cause we want to be able to include links or html formatting.
//		$rsrow['kba_content']=sanitize_and_format($rsrow['kba_content'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$articles[]=$rsrow;
	}
	if ($totalrows>$r) {
		$output['pager']=pager($totalrows,$o,$r);
	}
}
if (!empty($articles)) {
	$output['has_articles']=true;
}

$tpl->set_file('content','support.html');
$tpl->set_var('output',$output);
$tpl->set_loop('subcategs',$subcategs);
$tpl->set_loop('articles',$articles);
$tpl->process('content','content',TPL_OPTIONAL | TPL_LOOP);

$tplvars['title']='Support';
$tplvars['page_title']='Support';
$tplvars['page']='support';
$tplvars['menu_support']='active';
$tplvars['css']='support.css';
include 'frame.php';
