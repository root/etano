<?php
$jobs[]='gen_user_cache';

function gen_user_cache() {
	global $dbtable_prefix,$_pfields,$_pcats;

	$dirname=dirname(__FILE__);
	$temp=array();
	if ($dirname{0}=='/') {				// unixes here
		$temp=explode('/',$dirname);
	} else {							// windows here
		$temp=explode('\\',$dirname);
	}
	$interval=(int)$temp[count($temp)-1];	// that's how often we're executed ;)

	$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');

	$query="SELECT a.`config_value` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b WHERE a.`config_option`='skin_dir' AND a.`fk_module_code`=b.`module_code` AND b.`module_type`=".MODULE_SKIN;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$skins=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}

	require _BASEPATH_.'/includes/classes/Cache/Lite.php';
	$cache=new Cache_Lite($GLOBALS['_cache_config']);

	$now=gmdate('YmdHis');
	$select='`fk_user_id`,`status`,`del`,UNIX_TIMESTAMP(`last_changed`) as `last_changed`,UNIX_TIMESTAMP(`date_added`) as `date_added`,`_user`,`_photo`,`rad_longitude`,`rad_latitude`';
	$used_fields=array();
	foreach ($_pfields as $field_id=>$field) {
		if ($field->config['visible']) {
			$select.=','.$field->query_select();
			$used_fields[]=$field_id;
		}
	}

	// create the cache in every skin
	for ($s=0;isset($skins[$s]);++$s) {
		$GLOBALS['_lang']=array();
		$GLOBALS['_pfields']=array();
		$GLOBALS['_pcats']=array();
		include _BASEPATH_.'/skins_site/'.$skins[$s].'/lang/global.inc.php';
		include _BASEPATH_.'/includes/fields.inc.php';
		$query="SELECT $select FROM `{$dbtable_prefix}user_profiles` WHERE `status`=".STAT_APPROVED." AND `last_changed`>=DATE_SUB('$now',INTERVAL ".($interval+2)." MINUTE)";
//print $query;
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($profile=mysql_fetch_assoc($res)) {
			for ($i=0;isset($used_fields[$i]);++$i) {
				$field=&$_pfields[$used_fields[$i]];
				$field->set_value($profile,false);
				$profile[$field->config['dbfield']]=$field->display();
				// the label should be set after the call to display(). See field_birthdate::display() for explanation.
				$profile[$field->config['dbfield'].'_label']=$field->config['label'];
/*
				} elseif ($field['field_type']==FIELD_INT || $field['field_type']==FIELD_FLOAT) {
		//			$profile[$field['dbfield']]=$profile[$field['dbfield']];
*/
			}
			if (empty($profile['_photo']) || !is_file(_PHOTOPATH_.'/t1/'.$profile['_photo']) || !is_file(_PHOTOPATH_.'/t2/'.$profile['_photo']) || !is_file(_PHOTOPATH_.'/'.$profile['_photo'])) {
				$profile['_photo']='no_photo.gif';
			} else {
				$profile['has_photo']=true;
			}

			$tpl->set_var('profile',$profile);
			// generate the user details for result lists
			$tpl->set_file('temp',$skins[$s].'/static/result_user.html');
			$towrite=$tpl->process('','temp',TPL_OPTIONAL);
			$cache->save($towrite,'skin'.$skins[$s].$profile['fk_user_id'].'result_user');

			// generate the categories to be used on profile.php page
			$categs=array();
			$tpl->set_file('temp',$skins[$s].'/static/profile_categ.html');
			foreach ($_pcats as $pcat_id=>$pcat) {
				$fields=array();
				$j=0;
				for ($k=0;isset($pcat['fields'][$k]);++$k) {
					if (!empty($profile[$_pfields[$pcat['fields'][$k]]->config['dbfield']])) {
						$fields[$j]['label']=$profile[$_pfields[$pcat['fields'][$k]]->config['dbfield'].'_label'];
						$fields[$j]['field']=$profile[$_pfields[$pcat['fields'][$k]]->config['dbfield']];
						$fields[$j]['dbfield']=$_pfields[$pcat['fields'][$k]]->config['dbfield'];
						++$j;
					}
				}
				$categs['pcat_name']=$pcat['pcat_name'];
				$categs['pcat_id']=$pcat_id;
				$tpl->set_loop('fields',$fields);
				$tpl->set_var('categs',$categs);
				$towrite=$tpl->process('','temp',TPL_LOOP);
				$cache->save($towrite,'skin'.$skins[$s].$profile['fk_user_id'].'pcat'.$pcat_id);
				$tpl->drop_loop('fields');
				$tpl->drop_var('categs');
			}
			$tpl->drop_var('profile');
		}
	}
	return true;
}
