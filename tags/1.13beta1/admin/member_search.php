<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/member_search.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
$output['astat']=vector2options($accepted_astats);
$output['pstat']=vector2options($accepted_pstats);
$output['membership']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`');

$loop=array();
$s=0;
for ($i=0;isset($basic_search_fields[$i]);++$i) {
	$field=&$_pfields[$basic_search_fields[$i]];
	if (!empty($field->config['search_type'])) {
		$loop[]=array('label'=>$field->search()->config['label'],
						'dbfield'=>$field->search()->config['dbfield'],
						'field'=>$field->search()->edit($i+4),
						'js'=>$field->search()->edit_js()
					);
	}
}

$tpl->set_file('content','member_search.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('loop');

$tplvars['title']='Search';
$tplvars['css']='member_search.css';
$tplvars['page']='member_search';
include 'frame.php';
