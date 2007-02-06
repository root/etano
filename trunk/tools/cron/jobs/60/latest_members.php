<?php
$jobs[]='latest_members';

function latest_members() {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];

	$tpl=new phemplate(_BASEPATH_.'/skins/','remove_nonjs');

	$query="SELECT a.`config_value` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b WHERE a.`config_option`='skin_dir' AND a.`fk_module_code`=b.`module_code` AND b.`module_type`='"._MODULE_SKIN_."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$skins=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}
	require_once '../../includes/classes/modman.class.php';
	$modman=new modman();

	$query="SELECT `fk_user_id`,`_user`,`_photo` FROM `{$dbtable_prefix}user_profiles` WHERE `del`=0 AND `_photo`<>'' ORDER BY `date_added` LIMIT 4";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$loop=array();
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (empty($rsrow['_photo']) || !is_file(_BASEPATH_.'/media/pics/t1/'.$rsrow['_photo'])) {
			$rsrow['_photo']='no_photo.gif';
		}
		if ($i==0) {
			$rsrow['class']='first';
		}
		$loop[]=$rsrow;
		++$i;
	}

	if (!empty($loop)) {
		$tpl->set_loop('loop',$loop);
		for ($s=0;isset($skins[$s]);++$s) {
			$tpl->set_file('temp',$skins[$s].'/widgets/latest_members/display.html');
			$towrite=$tpl->process('','temp',TPL_LOOP);
			$modman->fileop->file_put_contents(_BASEPATH_.'/skins/'.$skins[$s].'/cache/widgets/latest_members/display.html',$towrite);
		}
	}

	return true;
}
?>