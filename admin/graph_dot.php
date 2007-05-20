<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/graph_dot.php
$Revision: 133 $
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
require_once '../includes/classes/advgraph4.class.php';
allow_dept(DEPT_ADMIN | DEPT_MODERATOR);

$dot_types=array('num_users','online_users','paid_members');
$type=sanitize_and_format_gpc($_GET,'t',TYPE_STRING,0,'');
$start_date=sanitize_and_format_gpc($_GET,'start',TYPE_INT,0,0);	// unix time
$end_date=sanitize_and_format_gpc($_GET,'end',TYPE_INT,0,0);	// unix time
$forced_end=false;
if (empty($end_date)) {
	$end_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
	$forced_end=true;
}

if (in_array($type,$dot_types)) {
	if (!is_file(_BASEPATH_.'/tmp/admin/'.$type.$start_date.$end_date.'.png')) {
		$query="SELECT `value`,UNIX_TIMESTAMP(`time`) as `time` FROM `{$dbtable_prefix}stats_dot` WHERE `dataset`='$type'";
		if (!empty($start_date)) {
			$query.=" AND `time`>='".date('Ymd',$start_date)."'";
		}
		if (!$forced_end) {
			$query.=" AND `time`<='".date('Ymd',$end_date)."'";
		}
		$query.=" ORDER BY `dot_id` ASC";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$graph=new graph(500,200);
			$i=0;
			$min_time=0;
			$max_time=0;
			$min_val=0;
			$max_val=0;
			while ($rsrow=mysql_fetch_assoc($res)) {
				if ($i==0) {
					$min_time=$rsrow['time'];
					$min_val=$rsrow['value'];
				}
				if ($min_val>$rsrow['value']) {
					$min_val=$rsrow['value'];
				}
				if ($max_val<$rsrow['value']) {
					$max_val=$rsrow['value'];
				}
				$max_time=$rsrow['time'];
				$graph->addPoint($rsrow['value'],$rsrow['time']-$min_time);
				++$i;
			}
			if ($end_date==0) {
				$end_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
			}
			$val_dif=$max_val-$min_val;
			$yscale=7;
			for ($i=4;$i<=10;++$i) {	// find a divisor
				if (($val_dif%$i)==0) {
					$yscale=$i;
					break;
				}
			}

			$graph->setProp('scale','date');
			$graph->setProp('pointstyle',7);
			$graph->setColor('pointcolor',-1,255,0,0);
			$graph->setProp('startdate',(int)$min_time);
			$graph->setProp('enddate',(int)$max_time);
			$graph->setProp('xsclpts',6);
			$graph->setProp('xincpts',6);
			$graph->setProp('ysclpts',$yscale);
			$graph->setProp('yincpts',$yscale);
			if ($max_time-$min_time<=365*24*60*60) {
				$graph->setProp('dateformat',1);
			} elseif ($max_time-$min_time>365*24*60*60) {
				$graph->setProp('dateformat',1);
			}
			$graph->setProp('showyear',true);
			$graph->graph();
			$graph->showGraph(_BASEPATH_.'/tmp/admin/'.$type.$start_date.$end_date.'.png');
//			$graph->showGraph();
		}
	}
	redirect2page('tmp/admin/'.$type.$start_date.$end_date.'.png');
}
?>