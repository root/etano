<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/photos_upload.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
require_once '../includes/classes/modman.class.php';
require_once '../includes/img_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(8);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='user_photos.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['is_private']=sanitize_and_format_gpc($_POST,'is_private',TYPE_INT,0,0);

	$config=get_site_option(array('round_corners','watermark_text','watermark_text_color','t1_width','t2_width','pic_width','manual_photo_approval','min_size','max_size'),'core_photo');
	$config2=$config;
	unset($config['round_corners']);
	$curtime=mktime();

	$filename=$_SESSION['user']['user_id'].'_1'.$curtime;
	$input['file1']=upload_file(_BASEPATH_.'/tmp','file1',$filename);
	mt_srand(make_seed());
	if (!empty($input['file1'])) {
		if (((!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file1'])>$config['min_size']) || empty($config['min_size'])) && ((!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file1'])<$config['max_size']) || empty($config['max_size']))) {
			$rand=mt_rand(0,9);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file1'],$config['t1_width'],_BASEPATH_.'/media/pics/t1/'.$rand,$filename,$config2);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file1'],$config['t2_width'],_BASEPATH_.'/media/pics/t2/'.$rand,$filename,$config);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file1'],$config['pic_width'],_BASEPATH_.'/media/pics/'.$rand,$filename,$config);
			@unlink(_BASEPATH_.'/tmp/'.$input['file1']);
			$input['file1']=$rand.'/'.$filename.'.jpg';
		} else {
			$input['file1']='';
		}
	}
	$filename=$_SESSION['user']['user_id'].'_2'.$curtime;
	$input['file2']=upload_file(_BASEPATH_.'/tmp','file2',$filename);
	mt_srand(make_seed());
	if (!empty($input['file2'])) {
		if (((!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file2'])>$config['min_size']) || empty($config['min_size'])) && ((!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file2'])<$config['max_size']) || empty($config['max_size']))) {
			$rand=mt_rand(0,9);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file2'],$config['t1_width'],_BASEPATH_.'/media/pics/t1/'.$rand,$filename,$config2);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file2'],$config['t2_width'],_BASEPATH_.'/media/pics/t2/'.$rand,$filename,$config);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file2'],$config['pic_width'],_BASEPATH_.'/media/pics/'.$rand,$filename,$config);
			@unlink(_BASEPATH_.'/tmp/'.$input['file2']);
			$input['file2']=$rand.'/'.$filename.'.jpg';
		} else {
			$input['file2']='';
		}
	}
	$filename=$_SESSION['user']['user_id'].'_3'.$curtime;
	$input['file3']=upload_file(_BASEPATH_.'/tmp','file3',$filename);
	mt_srand(make_seed());
	if (!empty($input['file3'])) {
		if (((!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file3'])>$config['min_size']) || empty($config['min_size'])) && ((!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file3'])<$config['max_size']) || empty($config['max_size']))) {
			mt_srand(make_seed());
			$rand=mt_rand(0,9);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file3'],$config['t1_width'],_BASEPATH_.'/media/pics/t1/'.$rand,$filename,$config2);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file3'],$config['t2_width'],_BASEPATH_.'/media/pics/t2/'.$rand,$filename,$config);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file3'],$config['pic_width'],_BASEPATH_.'/media/pics/'.$rand,$filename,$config);
			@unlink(_BASEPATH_.'/tmp/'.$input['file3']);
			$input['file3']=$rand.'/'.$filename.'.jpg';
		} else {
			$input['file3']='';
		}
	}
	$filename=$_SESSION['user']['user_id'].'_4'.$curtime;
	$input['file4']=upload_file(_BASEPATH_.'/tmp','file4',$filename);
	mt_srand(make_seed());
	if (!empty($input['file4'])) {
		if (((!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file4'])>$config['min_size']) || empty($config['min_size'])) && ((!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file4'])<$config['max_size']) || empty($config['max_size']))) {
			$rand=mt_rand(0,9);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file4'],$config['t1_width'],_BASEPATH_.'/media/pics/t1/'.$rand,$filename,$config2);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file4'],$config['t2_width'],_BASEPATH_.'/media/pics/t2/'.$rand,$filename,$config);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file4'],$config['pic_width'],_BASEPATH_.'/media/pics/'.$rand,$filename,$config);
			@unlink(_BASEPATH_.'/tmp/'.$input['file4']);
			$input['file4']=$rand.'/'.$filename.'.jpg';
		} else {
			$input['file4']='';
		}
	}
	$filename=$_SESSION['user']['user_id'].'_5'.$curtime;
	$input['file5']=upload_file(_BASEPATH_.'/tmp','file5',$filename);
	mt_srand(make_seed());
	if (!empty($input['file5'])) {
		if (((!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file5'])>$config['min_size']) || empty($config['min_size'])) && ((!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file5'])<$config['max_size']) || empty($config['max_size']))) {
			$rand=mt_rand(0,9);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file5'],$config['t1_width'],_BASEPATH_.'/media/pics/t1/'.$rand,$filename,$config2);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file5'],$config['t2_width'],_BASEPATH_.'/media/pics/t2/'.$rand,$filename,$config);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file5'],$config['pic_width'],_BASEPATH_.'/media/pics/'.$rand,$filename,$config);
			@unlink(_BASEPATH_.'/tmp/'.$input['file5']);
			$input['file5']=$rand.'/'.$filename.'.jpg';
		} else {
			$input['file5']='';
		}
	}
	$filename=$_SESSION['user']['user_id'].'_6'.$curtime;
	$input['file6']=upload_file(_BASEPATH_.'/tmp','file6',$filename);
	mt_srand(make_seed());
	if (!empty($input['file6'])) {
		if (((!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file6'])>$config['min_size']) || empty($config['min_size'])) && ((!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file6'])<$config['max_size']) || empty($config['max_size']))) {
			$rand=mt_rand(0,9);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file6'],$config['t1_width'],_BASEPATH_.'/media/pics/t1/'.$rand,$filename,$config2);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file6'],$config['t2_width'],_BASEPATH_.'/media/pics/t2/'.$rand,$filename,$config);
			save_thumbnail(_BASEPATH_.'/tmp/'.$input['file6'],$config['pic_width'],_BASEPATH_.'/media/pics/'.$rand,$filename,$config);
			@unlink(_BASEPATH_.'/tmp/'.$input['file6']);
			$input['file6']=$rand.'/'.$filename.'.jpg';
		} else {
			$input['file6']='';
		}
	}

	if (!$error) {
		$ids=array();
		$now=gmdate('YmdHis');
		for ($i=1;$i<=6;++$i) {
			if (!empty($input['file'.$i])) {
				$query="INSERT INTO `{$dbtable_prefix}user_photos` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`_user`='".$_SESSION['user']['user']."',`photo`='".$input['file'.$i]."',`is_main`=0,`is_private`='".$input['is_private']."',`date_posted`='$now',`last_changed`='$now'";
				if ($config['manual_photo_approval']==1) {
					$query.=",`status`='".PSTAT_PENDING."'";
				} else {
					$query.=",`status`='".PSTAT_APPROVED."'";
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$ids[]=mysql_insert_id();
			}
		}
		if (!empty($ids)) {
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=sprintf('%1u photos uploaded.',count($ids));
			$qs=$qs_sep.array2qs(array('photo_ids'=>$ids));
			$qs_sep='&';
			$nextpage='photo_settings.php';
		}
	}
}
redirect2page($nextpage,$topass,$qs);
?>