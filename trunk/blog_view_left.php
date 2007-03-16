<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blog_view_left.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$blog_archive=array();
//include _CACHEPATH_.'/blogs/'.$blog['blog_id'].'/blog_archive.inc.php';

$i=0;
$blog_archive[$i]['is_current']='current';
$blog_archive[$i]['year']=2007;
$j=0;
$blog_archive[$i]['months'][$j]['month']=2;
$blog_archive[$i]['months'][$j]['month_name']='february';
++$j;
$blog_archive[$i]['months'][$j]['month']=1;
$blog_archive[$i]['months'][$j]['month_name']='january';

++$i;
$blog_archive[$i]['year']=2006;
$j=0;
$blog_archive[$i]['months'][$j]['month']=12;
$blog_archive[$i]['months'][$j]['month_name']='december';
++$j;
$blog_archive[$i]['months'][$j]['month']=11;
$blog_archive[$i]['months'][$j]['month_name']='november';

$tpl->set_file('left_content','blog_view_left.html');
$tpl->set_loop('blog_archive',$blog_archive);
$tpl->process('left_content','left_content',TPL_MULTILOOP);
$tpl->drop_loop('blog_archive');
?>