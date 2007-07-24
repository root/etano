<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/photos_upload.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/classes/fileop.class.php';
require_once '../includes/img_functions.inc.php';
require_once '../includes/triggers.inc.php';
check_login_member('upload_photos');
set_time_limit(0);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_photos.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['is_private']=sanitize_and_format_gpc($_POST,'is_private',TYPE_INT,0,0);

	$config=get_site_option(array('round_corners','watermark_text','watermark_text_color','t1_width','t2_width','pic_width','manual_photo_approval','min_size','max_size'),'core_photo');
	$config['padding_type']=PAD_NONE;
	$config_t1=$config;
	$config_t1['padding_type']=PAD_2SIDES;
	$config_t2=$config;
	$config_t2['padding_type']=PAD_1SIDE;
	unset($config['round_corners']);
	$curtime=time();

	$fileop=new fileop();

	if (!isset($_FILES) || empty($_FILES)) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='Sorry, an error has occured. Try uploading less photos at once or smaller photos.';
	}

	if (!$error) {
		$filename=$_SESSION['user']['user_id'].'_1'.$curtime;
		$input['file1']=upload_file(_BASEPATH_.'/tmp','file1',$filename);
		mt_srand(make_seed());
		if (!empty($input['file1'])) {
			if (!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file1'])<$config['min_size']) {
				$input['file1']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The first photo was not uploaded because the minimum allowed size for photos is %1$s bytes.',$config['min_size']);
			} elseif (!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file1'])>$config['max_size']) {
				$input['file1']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The first photo was not uploaded because the maximum allowed size for photos is %1$s bytes.',$config['max_size']);
			} else {
				$rand=mt_rand(0,9);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file1'],$config['t1_width'],_BASEPATH_.'/tmp',$filename.'_1',$config_t1);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file1'],$config['t2_width'],_BASEPATH_.'/tmp',$filename.'_2',$config_t2);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file1'],$config['pic_width'],_BASEPATH_.'/tmp',$filename.'_3',$config);
				@unlink(_BASEPATH_.'/tmp/'.$input['file1']);
				$input['file1']=$rand.'/'.$filename.'.jpg';
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_1.jpg',_PHOTOPATH_.'/t1/'.$input['file1']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_2.jpg',_PHOTOPATH_.'/t2/'.$input['file1']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_3.jpg',_PHOTOPATH_.'/'.$input['file1']);
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_1.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_2.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_3.jpg');
			}
		} elseif ($input['file1']===false) {
			$error=true;
			// we should have $topass['message'] set from within upload_file();
		}

		$filename=$_SESSION['user']['user_id'].'_2'.$curtime;
		$input['file2']=upload_file(_BASEPATH_.'/tmp','file2',$filename);
		mt_srand(make_seed());
		if (!empty($input['file2'])) {
			if (!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file2'])<$config['min_size']) {
				$input['file2']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The second photo was not uploaded because the minimum allowed size for photos is %1$s bytes.',$config['min_size']);
			} elseif (!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file2'])>$config['max_size']) {
				$input['file2']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The second photo was not uploaded because the maximum allowed size for photos is %1$s bytes.',$config['max_size']);
			} else {
				$rand=mt_rand(0,9);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file2'],$config['t1_width'],_BASEPATH_.'/tmp',$filename.'_1',$config_t1);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file2'],$config['t2_width'],_BASEPATH_.'/tmp',$filename.'_2',$config_t2);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file2'],$config['pic_width'],_BASEPATH_.'/tmp',$filename.'_3',$config);
				@unlink(_BASEPATH_.'/tmp/'.$input['file2']);
				$input['file2']=$rand.'/'.$filename.'.jpg';
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_1.jpg',_PHOTOPATH_.'/t1/'.$input['file2']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_2.jpg',_PHOTOPATH_.'/t2/'.$input['file2']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_3.jpg',_PHOTOPATH_.'/'.$input['file2']);
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_1.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_2.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_3.jpg');
			}
		} elseif ($input['file2']===false) {
			$error=true;
			// we should have $topass['message'] set from within upload_file();
		}

		$filename=$_SESSION['user']['user_id'].'_3'.$curtime;
		$input['file3']=upload_file(_BASEPATH_.'/tmp','file3',$filename);
		mt_srand(make_seed());
		if (!empty($input['file3'])) {
			if (!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file3'])<$config['min_size']) {
				$input['file3']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The third photo was not uploaded because the minimum allowed size for photos is %1$s bytes.',$config['min_size']);
			} elseif (!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file3'])>$config['max_size']) {
				$input['file3']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The third photo was not uploaded because the maximum allowed size for photos is %1$s bytes.',$config['max_size']);
			} else {
				mt_srand(make_seed());
				$rand=mt_rand(0,9);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file3'],$config['t1_width'],_BASEPATH_.'/tmp',$filename.'_1',$config_t1);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file3'],$config['t2_width'],_BASEPATH_.'/tmp',$filename.'_2',$config_t2);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file3'],$config['pic_width'],_BASEPATH_.'/tmp',$filename.'_3',$config);
				@unlink(_BASEPATH_.'/tmp/'.$input['file3']);
				$input['file3']=$rand.'/'.$filename.'.jpg';
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_1.jpg',_PHOTOPATH_.'/t1/'.$input['file3']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_2.jpg',_PHOTOPATH_.'/t2/'.$input['file3']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_3.jpg',_PHOTOPATH_.'/'.$input['file3']);
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_1.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_2.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_3.jpg');
			}
		} elseif ($input['file3']===false) {
			$error=true;
			// we should have $topass['message'] set from within upload_file();
		}

		$filename=$_SESSION['user']['user_id'].'_4'.$curtime;
		$input['file4']=upload_file(_BASEPATH_.'/tmp','file4',$filename);
		mt_srand(make_seed());
		if (!empty($input['file4'])) {
			if (!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file4'])<$config['min_size']) {
				$input['file4']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The fourth photo was not uploaded because the minimum allowed size for photos is %1$s bytes.',$config['min_size']);
			} elseif (!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file4'])>$config['max_size']) {
				$input['file4']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The fourth photo was not uploaded because the maximum allowed size for photos is %1$s bytes.',$config['max_size']);
			} else {
				$rand=mt_rand(0,9);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file4'],$config['t1_width'],_BASEPATH_.'/tmp',$filename.'_1',$config_t1);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file4'],$config['t2_width'],_BASEPATH_.'/tmp',$filename.'_2',$config_t2);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file4'],$config['pic_width'],_BASEPATH_.'/tmp',$filename.'_3',$config);
				@unlink(_BASEPATH_.'/tmp/'.$input['file4']);
				$input['file4']=$rand.'/'.$filename.'.jpg';
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_1.jpg',_PHOTOPATH_.'/t1/'.$input['file4']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_2.jpg',_PHOTOPATH_.'/t2/'.$input['file4']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_3.jpg',_PHOTOPATH_.'/'.$input['file4']);
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_1.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_2.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_3.jpg');
			}
		} elseif ($input['file4']===false) {
			$error=true;
			// we should have $topass['message'] set from within upload_file();
		}

		$filename=$_SESSION['user']['user_id'].'_5'.$curtime;
		$input['file5']=upload_file(_BASEPATH_.'/tmp','file5',$filename);
		mt_srand(make_seed());
		if (!empty($input['file5'])) {
			if (!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file5'])<$config['min_size']) {
				$input['file5']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The fifth photo was not uploaded because the minimum allowed size for photos is %1$s bytes.',$config['min_size']);
			} elseif (!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file5'])>$config['max_size']) {
				$input['file5']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The fifth photo was not uploaded because the maximum allowed size for photos is %1$s bytes.',$config['max_size']);
			} else {
				$rand=mt_rand(0,9);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file5'],$config['t1_width'],_BASEPATH_.'/tmp',$filename.'_1',$config_t1);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file5'],$config['t2_width'],_BASEPATH_.'/tmp',$filename.'_2',$config_t2);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file5'],$config['pic_width'],_BASEPATH_.'/tmp',$filename.'_3',$config);
				@unlink(_BASEPATH_.'/tmp/'.$input['file5']);
				$input['file5']=$rand.'/'.$filename.'.jpg';
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_1.jpg',_PHOTOPATH_.'/t1/'.$input['file5']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_2.jpg',_PHOTOPATH_.'/t2/'.$input['file5']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_3.jpg',_PHOTOPATH_.'/'.$input['file5']);
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_1.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_2.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_3.jpg');
			}
		} elseif ($input['file5']===false) {
			$error=true;
			// we should have $topass['message'] set from within upload_file();
		}

		$filename=$_SESSION['user']['user_id'].'_6'.$curtime;
		$input['file6']=upload_file(_BASEPATH_.'/tmp','file6',$filename);
		mt_srand(make_seed());
		if (!empty($input['file6'])) {
			if (!empty($config['min_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file6'])<$config['min_size']) {
				$input['file6']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The sixth photo was not uploaded because the minimum allowed size for photos is %1$s bytes.',$config['min_size']);
			} elseif (!empty($config['max_size']) && filesize(_BASEPATH_.'/tmp/'.$input['file6'])>$config['max_size']) {
				$input['file6']='';
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf('The sixth photo was not uploaded because the maximum allowed size for photos is %1$s bytes.',$config['max_size']);
			} else {
				$rand=mt_rand(0,9);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file6'],$config['t1_width'],_BASEPATH_.'/tmp',$filename.'_1',$config_t1);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file6'],$config['t2_width'],_BASEPATH_.'/tmp',$filename.'_2',$config_t2);
				save_thumbnail(_BASEPATH_.'/tmp/'.$input['file6'],$config['pic_width'],_BASEPATH_.'/tmp',$filename.'_3',$config);
				@unlink(_BASEPATH_.'/tmp/'.$input['file6']);
				$input['file6']=$rand.'/'.$filename.'.jpg';
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_1.jpg',_PHOTOPATH_.'/t1/'.$input['file6']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_2.jpg',_PHOTOPATH_.'/t2/'.$input['file6']);
				$fileop->copy(_BASEPATH_.'/tmp/'.$filename.'_3.jpg',_PHOTOPATH_.'/'.$input['file6']);
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_1.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_2.jpg');
				@unlink(_BASEPATH_.'/tmp/'.$filename.'_3.jpg');
			}
		} elseif ($input['file6']===false) {
			$error=true;
			// we should have $topass['message'] set from within upload_file();
		}
	}

	if (!$error) {
		$ids=array();
		$now=gmdate('YmdHis');
		$force_main=false;
		if (empty($input['is_private'])) {
			// if there's no main photo yet, make the first one the main one
			$query="SELECT `photo_id` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`=".$_SESSION['user']['user_id']." AND `is_main`=1";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!mysql_num_rows($res)) {
				$force_main=true;
			}
		}
		for ($i=1;$i<=6;++$i) {
			if (!empty($input['file'.$i])) {
				$query="INSERT INTO `{$dbtable_prefix}user_photos` SET `fk_user_id`=".$_SESSION['user']['user_id'].",`_user`='".$_SESSION['user']['user']."',`photo`='".$input['file'.$i]."',`allow_comments`=1,`allow_rating`='".$_SESSION['user']['prefs']['rate_my_photos']."',`is_private`=".$input['is_private'].",`date_posted`='$now',`last_changed`='$now'";
				if ($config['manual_photo_approval']) {
					$query.=",`status`=".STAT_PENDING;
				} else {
					$query.=",`status`=".STAT_APPROVED;
				}
				if ($force_main) {
					$query.=",`is_main`=1";
					$force_main=false;
					// unfortunately we have to force _photo in user_profile here instead of in photo_settings
					// if photo approvals are automatic then we can make this photo the main photo now. Otherwise it will have to be done upon approval!!!
					if (empty($config['manual_photo_approval'])) {
						$query2="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='".$input['file'.$i]."',`last_changed`='".gmdate('YmdHis')."' WHERE `fk_user_id`=".$_SESSION['user']['user_id'];
						if (!($res=@mysql_query($query2))) {trigger_error(mysql_error(),E_USER_ERROR);}
					}
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$ids[]=mysql_insert_id();
			}
		}
		if (!empty($ids)) {
			if (empty($config['manual_photo_approval'])) {
				on_approve_photo($ids);
			}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=sprintf('%1u photos uploaded.',count($ids));
			$qs=$qs_sep.array2qs(array('photo_ids'=>$ids));
			$qs_sep='&';
			$nextpage='photo_settings.php';
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=sprintf('No photo uploaded.');
		}
	} else {
		$nextpage='photos_upload.php';
	}
}
redirect2page($nextpage,$topass,$qs);
