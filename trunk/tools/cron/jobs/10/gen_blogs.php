<?
$jobs[]='gen_blog_cache';

function gen_blog_cache() {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$dirname=dirname(__FILE__);
	$temp=array();
	if ($dirname{0}=='/') {				// unixes here
		$temp=explode('/',$dirname);
	} else {							// windows here
		$temp=explode('\\',$dirname);
	}
	$interval=(int)$temp[count($temp)-1];	// that's how often we're executed ;)

	$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');

	$query="SELECT a.`config_value` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b WHERE a.`config_option`='skin_dir' AND a.`fk_module_code`=b.`module_code` AND b.`module_type`='".MODULE_SKIN."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$skins=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}

	$config=get_site_option(array('bbcode_blogs'),'core');

	require_once '../../includes/classes/modman.class.php';
	$modman=new modman();

	$query="SELECT a.*,b.`blog_name`,b.`blog_diz`,b.`blog_url` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_blogs` b WHERE a.`status`='".PSTAT_APPROVED."' AND `last_changed`>=DATE_SUB(now(),INTERVAL ".($interval+2)." MINUTE)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($output=mysql_fetch_assoc($res)) {
		$output=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		if ($config['bbcode_blogs']) {
			$output['post_content']=bbcode2html($output['post_content']);
		}
		$tpl->set_var('output',$output);

		// create the cache in every skin
		for ($s=0;isset($skins[$s]);++$s) {
			// create the blog cache folder if it doesn't exist
			if (!is_dir(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/blogs/'.$output['fk_blog_id'])) {
				$modman->fileop->mkdir(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/blogs/'.$output['fk_blog_id']);
			}

			// generate the middle part of page for post view mode
			$tpl->set_file('temp',$skins[$s].'/static/blog_post_view.html');
			$towrite=$tpl->process('','temp',TPL_OPTIONAL);
			$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/blogs/'.$output['fk_blog_id'].'/post_'.$output['post_id'].'.html',$towrite);

			// generate the user details for gallery view
			$tpl->set_file('temp',$skins[$s].'/static/user_gallery.html');
			$towrite=$tpl->process('','temp');
			$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$output['fk_user_id']{0}.'/'.$output['fk_user_id'].'/user_gallery.html',$towrite);

			// generate the user details for list view
			$tpl->set_file('temp',$skins[$s].'/static/user_list.html');
			$towrite=$tpl->process('','temp');
			$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$output['fk_user_id']{0}.'/'.$output['fk_user_id'].'/user_list.html',$towrite);

			// generate the categories to be used on profile.php page
			$categs=array();
			$tpl->set_file('temp',$skins[$s].'/static/profile_categ.html');
			foreach ($_pcats as $pcat_id=>$pcat) {
				$fields=array();
				$j=0;
				for ($i=0;isset($pcat['fields'][$i]);++$i) {
					$fields[$i]['label']=$_pfields[$pcat['fields'][$i]]['label'];
					$fields[$i]['field']=$output[$_pfields[$pcat['fields'][$i]]['dbfield']];
				}
				$categs['pcat_name']=$pcat['pcat_name'];
				$categs['pcat_id']=$pcat_id;
				$tpl->set_loop('fields',$fields);
				$tpl->set_var('categs',$categs);
				$towrite=$tpl->process('','temp',TPL_LOOP);
				$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$output['fk_user_id']{0}.'/'.$output['fk_user_id'].'/categ_'.$pcat_id.'.html',$towrite);
				$tpl->drop_loop('fields');
				$tpl->drop_var('categs');
			}
		}
		$tpl->drop_var('user');
	}
}
