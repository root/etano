<?
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/classes/modman.class.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$tpl=new phemplate(_BASEPATH_.'/skins/','remove_nonjs');

$query="SELECT b.`config_value` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`='"._MODULE_SKIN_."' AND a.`module_code`=b.`fk_module_code` AND b.`config_value`='skin_dir'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$skin_dirs=array();
for ($i=0;$i<mysql_num_rows($res);++$i) {
	$skin_dirs[]=mysql_result($res,$i,0);
}

$modman=new modman();

$query="SELECT * FROM `{$dbtable_prefix}user_profiles` WHERE `status`='".PSTAT_APPROVED."'";
//$query="SELECT * FROM `{$dbtable_prefix}user_profiles` WHERE `status`='".PSTAT_APPROVED."' AND `last_changed`>=DATE_SUB(now(),INTERVAL 12 MINUTE)";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$profile=sanitize_and_format($rsrow,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
// set all the fields to their real (readable) values
	foreach ($_pfields as $i=>$v) {
		if ($_pfields[$i]['html_type']==_HTML_TEXTFIELD_ || $_pfields[$i]['html_type']==_HTML_TEXTAREA_) {
			$profile[$_pfields[$i]['dbfield']]=sanitize_and_format($profile[$_pfields[$i]['dbfield']],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		} elseif ($_pfields[$i]['html_type']==_HTML_SELECT_) {
			$profile[$_pfields[$i]['dbfield']]=sanitize_and_format($_pfields[$i]['accepted_values'][$profile[$_pfields[$i]['dbfield']]],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		} elseif ($_pfields[$i]['html_type']==_HTML_CHECKBOX_LARGE_) {
			$profile[$_pfields[$i]['dbfield']]=vector2string_str($_pfields[$i]['accepted_values'],$profile[$_pfields[$i]['dbfield']]);
		} elseif ($_pfields[$i]['html_type']==_HTML_INT_ || $_pfields[$i]['html_type']==_HTML_FLOAT_) {
//			$profile[$_pfields[$i]['dbfield']]=$profile[$_pfields[$i]['dbfield']];
		} elseif ($_pfields[$i]['html_type']==_HTML_DATE_) {
//			$profile[$_pfields[$i]['dbfield']]=$profile[$_pfields[$i]['dbfield']];
		}
	}
	if (empty($profile['_photo']) || !is_file(_BASEPATH_.'/media/pics/t1/'.$profile['_photo']) || !is_file(_BASEPATH_.'/media/pics/t2/'.$profile['_photo']) || !is_file(_BASEPATH_.'/media/pics/'.$profile['_photo'])) {
		$profile['_photo']='no_photo.gif';
	}
	$tpl->set_var('profile',$profile);
	for ($s=0;isset($skin_dirs[$s]);++$s) {
		if (!is_dir(_BASEPATH_.'/skins/'.$skin_dirs[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'])) {
			$modman->fileop->mkdir(_BASEPATH_.'/skins/'.$skin_dirs[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id']);
		}
		// generate the user details mini
		$tpl->set_file('temp',$skin_dirs[$s].'/static/user_details_mini.html');
		$towrite=$tpl->process('','temp');
		$modman->fileop->file_put_contents(_BASEPATH_.'/skins/'.$skin_dirs[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'].'/user_details_mini.html',$towrite);
	}
	$tpl->drop_var('user');
}
?>