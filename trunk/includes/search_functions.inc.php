<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/search_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

function search_results($search,$my_membership=1) {
	global $dbtable_prefix;
	global $_pfields;
	$myreturn=array();
	$input['acclevel_code']='search_advanced'; // default access level is the one for advanced search!!!!
	$search_fields=array();
	$continue=false;	// for searches not based on search_fields
	$select="a.`fk_user_id`";
	$from="`{$dbtable_prefix}user_profiles` a";
	$where=' a.`status`='.STAT_APPROVED.' AND a.`del`=0';
	$orderby="ORDER BY a.`score` DESC";
	if (isset($search['min_user_id'])) {
		$where.=" AND a.`fk_user_id`>".$search['min_user_id'];
	}
//	if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
//		$where.=" AND a.`fk_user_id`<>'".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
//	}

	// define here all search types
	// you can either add fields to be read into $search_fields or build the query directly
	if (isset($search['st'])) {
		switch ($search['st']) {

			case 'basic':
				$input['acclevel_code']='search_basic';
				$search_fields=$GLOBALS['basic_search_fields'];
				if (isset($search['wphoto'])) {
					$where.=" AND a.`_photo`!=''";
				}
				break;

			case 'adv':
				$input['acclevel_code']='search_advanced';
				// for advanced search we get all fields
				foreach ($_pfields as $field_id=>$field) {
					if (!empty($field->config['searchable'])) {
						$search_fields[]=$field_id;
					}
				}
				if (isset($search['wphoto'])) {
					$where.=" AND a.`_photo`!=''";
				}
				break;

			case 'user':
				$input['acclevel_code']='search_advanced';
				$continue=true;
				$input['user']=sanitize_and_format_gpc($search,'user',TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD],'');
				if (strlen($input['user'])<=3) {
//					$topass['message']['text']=$GLOBALS['_lang'][8];
//					$topass['message']['type']=MESSAGE_ERROR;
					$where='';	// force no results returned.
				} else {
					$where.=" AND a.`_user` LIKE '".$input['user']."%'";
				}
				break;

			case 'net':
				$input['acclevel_code']='search_basic';
				$continue=true;
				$input['fk_user_id']=sanitize_and_format_gpc($search,'uid',TYPE_INT,0,0);
				$input['fk_net_id']=sanitize_and_format_gpc($search,'nid',TYPE_INT,0,0);
				$select="b.`fk_user_id_other`";
				$from="`{$dbtable_prefix}user_networks` b,".$from;
				$where="b.`fk_user_id`=".$input['fk_user_id']." AND b.`fk_net_id`=".$input['fk_net_id']." AND b.`nconn_status`=1 AND b.`fk_user_id_other`=a.`fk_user_id` AND ".$where;
				break;

			case 'new':
				$input['acclevel_code']='search_basic';
				$continue=true;
				$orderby="ORDER BY a.`date_added` DESC";
				break;

			case 'online':
				$input['acclevel_code']='search_basic';
				$continue=true;
				$from="`{$dbtable_prefix}online` b,".$from;
				$where.=" AND b.`fk_user_id`<>0 AND b.`fk_user_id`=a.`fk_user_id`";
				$orderby="GROUP BY b.`fk_user_id` ".$orderby;
				break;

			case 'vote':
			case 'views':
			case 'comm':
// TODO
				break;

			default:
				break;

		}
	}
	if (allow_at_level($input['acclevel_code'],$my_membership)) {
		for ($i=0;isset($search_fields[$i]);++$i) {
			$field=&$_pfields[$search_fields[$i]];
			$field->search()->set_value($search);
			$where.=$field->search()->query_search();
			$input=array_merge($input,$field->search()->get_value(true));
		}

		if (!empty($where)) {	// if $where is empty then a condition above prevents us from searching.
			$query="SELECT $select FROM $from WHERE $where $orderby";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$myreturn[]=mysql_result($res,$i,0);
			}
		}
	}
	return $myreturn;
}
