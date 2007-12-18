<?php

$_on_after_insert[]='insert_seolink';
$_on_before_update[]='update_seolink';

function insert_seolink() {
	global $input,$dbtable_prefix,$towrite;
	$input['alt_url']=_BASEURL_.'/blog/'.$input['blog_id'].'/'.strtolower(preg_replace(array('/[^a-zA-Z0-9]+/','/(^-)|(-$)/'),array('_',''),$input['blog_name']));
	$query="UPDATE `{$dbtable_prefix}user_blogs` SET `alt_url`='".$input['alt_url']."' WHERE `blog_id`=".$input['blog_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$towrite['alt_url']=$input['alt_url'];
}

function update_seolink() {
	global $query,$input,$towrite;
	$input['alt_url']=_BASEURL_.'/blog/'.$input['blog_id'].'/'.strtolower(preg_replace(array('/[^a-zA-Z0-9]+/','/(^-)|(-$)/'),array('_',''),$input['blog_name']));
	$temp=explode(' WHERE',$query);
	$temp[0].=",`alt_url`='".$input['alt_url']."'";
	$query=$temp[0].' WHERE'.$temp[1];
	$towrite['alt_url']=$input['alt_url'];
}
