<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blog_post_view_left.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$blog_archive=array();
include _CACHEPATH_.'/blogs/'.$output['fk_blog_id']{0}.'/'.$output['fk_blog_id'].'/blog_archive.inc.php';

$blog['blog_id']=$output['fk_blog_id'];
$year=sanitize_and_format_gpc($_GET,'y',TYPE_INT,0,0);
$loop=array();
$i=0;
$current_passed=false;
foreach ($blog_archive as $y=>$months) {
	$loop[$i]['year']=$y;
	if ($year==$y) {
		$loop[$i]['is_current']='current';
		$current_passed=true;
	}
	$loop[$i]['num_posts']=array_sum($months);
	$j=0;
	foreach ($months as $m=>$num_posts) {
		$loop[$i]['months'][$j]['month']=$m;
		$loop[$i]['months'][$j]['month_name']=$accepted_months[$m];
		$loop[$i]['months'][$j]['num_posts']=$num_posts;
		++$j;
	}
	++$i;
}
if (!$current_passed && isset($loop[0])) {
	$loop[0]['is_current']='current';
}

$tpl->set_file('left_content','blog_view_left.html');
$tpl->set_var('blog',$blog);
$tpl->set_loop('loop',$loop);
$tpl->process('left_content','left_content',TPL_MULTILOOP);
$tpl->drop_loop('loop');
unset($loop);
?>