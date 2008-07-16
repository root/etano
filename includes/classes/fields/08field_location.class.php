<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_location.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_location extends iprofile_field {
	var $empty_value=array('edit'=>array('country'=>0,'state'=>0,'city'=>0,'zip'=>''),'display'=>'');
	var $display_name='Location';
	var $allowed_search_types=array('field_location','field_zip_distance');

	function field_location($config=array(),$is_search=false) {
		$this->config=$config;
		$this->is_search=$is_search;
		$this->value=$this->empty_value['edit'];
		if (isset($this->config['def_country'])) {
			$this->value['country']=(int)$this->config['def_country'];
		} else {
			$this->value['country']=$this->empty_value['edit']['country'];
		}
		$this->value['state']=$this->empty_value['edit']['state'];
		$this->value['city']=$this->empty_value['edit']['city'];
		$this->value['zip']=$this->empty_value['edit']['zip'];
	}

	function set_value(&$all_values,$sanitize=true) {
		if ($sanitize) {
			$this->value['country']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_country',TYPE_INT,0,$this->empty_value['edit']['country']);
			$this->value['state']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_state',TYPE_INT,0,$this->empty_value['edit']['state']);
			$this->value['city']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_city',TYPE_INT,0,$this->empty_value['edit']['city']);
			$this->value['zip']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_zip',TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD],$this->empty_value['edit']['zip']);
		} else {
			if (isset($all_values[$this->config['dbfield'].'_country'])) {
				$this->value['country']=(int)$all_values[$this->config['dbfield'].'_country'];
			}
			if (isset($all_values[$this->config['dbfield'].'_state'])) {
				$this->value['state']=(int)$all_values[$this->config['dbfield'].'_state'];
			}
			if (isset($all_values[$this->config['dbfield'].'_city'])) {
				$this->value['city']=(int)$all_values[$this->config['dbfield'].'_city'];
			}
			if (isset($all_values[$this->config['dbfield'].'_zip'])) {
				$this->value['zip']=$all_values[$this->config['dbfield'].'_zip'];
			}
		}
		return true;
	}

	function edit($tabindex=1) {
		global $dbtable_prefix;
		$myreturn='<select name="'.$this->config['dbfield'].'_country" id="'.$this->config['dbfield'].'_country" tabindex="'.$tabindex.'" class="big_select"><option value="0">'.$GLOBALS['_lang'][126].'</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$this->value['country']).'</select>';
		$prefered_input='s';
		$num_states=0;
		$num_cities=0;
		if (!empty($this->value['country'])) {
			$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`=".$this->value['country'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				list($prefered_input,$num_states)=mysql_fetch_row($res);
			}
		}
		if (!empty($this->value['state'])) {
			$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`=".$this->value['state'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$num_cities=mysql_result($res,0,0);
			}
		}
		$myreturn.='<div id="row_'.$this->config['dbfield'].'_state" class="location_sub '.((!empty($this->value['country']) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible').' '.(!empty($this->config['required']) ? 'required' : '').'">';
		$myreturn.='<label for="'.$this->config['dbfield'].'_state">'.$GLOBALS['_lang'][127].'</label>';
		$myreturn.='<select name="'.$this->config['dbfield'].'_state" id="'.$this->config['dbfield'].'_state" tabindex="'.$tabindex.'"><option value="0">'.$GLOBALS['_lang'][127].'</option>';
		if (!empty($this->value['country']) && $prefered_input=='s' && !empty($num_states)) {
			$myreturn.=dbtable2options("`{$dbtable_prefix}loc_states`",'`state_id`','`state`','`state`',$this->value['state'],"`fk_country_id`=".$this->value['country']);
		}
		$myreturn.='</select></div>';

		$myreturn.='<div id="row_'.$this->config['dbfield'].'_city" class="location_sub '.((!empty($this->value['state']) && $prefered_input=='s' && !empty($num_cities)) ? 'visible' : 'invisible').'">';
		$myreturn.='<label for="'.$this->config['dbfield'].'_city">'.$GLOBALS['_lang'][128].'</label><select name="'.$this->config['dbfield'].'_city" id="'.$this->config['dbfield'].'_city" tabindex="'.$tabindex.'"><option value="0">'.$GLOBALS['_lang'][128].'</option>';
		if (!empty($this->value['state']) && $prefered_input=='s' && !empty($num_cities)) {
			$myreturn.=dbtable2options("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`','`city`',$this->value['city'],"`fk_state_id`=".$this->value['state']);
		}
		$myreturn.='</select></div>';

		$myreturn.='<div id="row_'.$this->config['dbfield'].'_zip" class="location_sub '.((!empty($this->value['country']) && $prefered_input=='z') ? 'visible' : 'invisible').'">';
		$myreturn.='<label for="'.$this->config['dbfield'].'_zip">'.$GLOBALS['_lang'][129].'</label><input type="text" name="'.$this->config['dbfield'].'_zip" id="'.$this->config['dbfield'].'_zip" value="'.$this->value['zip'].'" tabindex="'.$tabindex.'" /></div>';
		return $myreturn;
	}

	function display() {
		global $dbtable_prefix;
		$myreturn=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$this->value['country'],$this->empty_value['display']);
		if (!empty($this->value['state'])) {
			$myreturn.=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$this->value['state'],$this->empty_value['display']);
		}
		if (!empty($this->value['city'])) {
			$myreturn.=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$this->value['city'],$this->empty_value['display']);
		}
		return $myreturn;
	}

	function search() {
		if ($this->search!=null) {
			return $this->search;
		} elseif (!empty($this->config['search_type'])) {
			$class_name=$this->config['search_type'];
			$new_config=$this->config;
			$new_config['label']=$new_config['search_label'];
			unset($new_config['search_default'],$new_config['search_label'],$new_config['searchable'],$new_config['required'],$new_config['search_type'],$new_config['reg_page']);
			$new_config['parent_class']=get_class();
			$this->search=new $class_name($new_config,true);
//			$temp=array($this->config['dbfield'].'_country'=>$this->value['country'],$this->config['dbfield'].'_state'=>$this->value['state'],$this->config['dbfield'].'_city'=>$this->value['city'],$this->config['dbfield'].'_zip'=>$this->value['zip']);
//			$this->search->set_value($temp,false);
			return $this->search;
		} else {
			return $this;
		}
	}

	function edit_admin() {
		global $dbtable_prefix,$default_skin_code,$output,$__field2format,$search_type;
		$myreturn='';
		if (!$this->is_search) {
			$output['def_country']=!empty($output['def_country']) ? $output['def_country'] : 0;
			$myreturn.='<div class="clear">
				<label>Default Country:</label>
				<select id="def_country" name="def_country"><option value="0">Any</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$output['def_country']).'</select>
			</div>';
		}
		return $myreturn;
	}

	function admin_processor() {
		$error=false;
		global $input,$__field2format,$dbtable_prefix,$default_skin_code;
		$my_input=array();
		if (!$this->is_search) {
			$my_input['def_country']=sanitize_and_format_gpc($_POST,'def_country',TYPE_INT,0,0);
			if (!empty($input['searchable']) && !empty($input['search_type'])) {
				$search_field=new $input['search_type'](array(),true);
				$temp=$search_field->admin_processor();
				if (is_array($temp) && !empty($temp)) {
					$my_input=array_merge($my_input,$temp);
				}
			}
			$input['custom_config']=sanitize_and_format(serialize($my_input),TYPE_STRING,FORMAT_ADDSLASH);
		} else {
			return array();
		}
		return $error;
	}

	function query_select() {
		return '`'.$this->config['dbfield'].'_country`,`'.$this->config['dbfield'].'_state`,`'.$this->config['dbfield'].'_city`,`'.$this->config['dbfield'].'_zip`';
	}

	function query_set() {
		return '`'.$this->config['dbfield'].'_country`='.$this->value['country'].',`'.$this->config['dbfield'].'_state`='.$this->value['state'].',`'.$this->config['dbfield'].'_city`='.$this->value['city'].',`'.$this->config['dbfield']."_zip`='".$this->value['zip']."'";
	}

	function query_search() {
		$myreturn='';
		if (!empty($this->value['country'])) {
			$myreturn.=' AND `'.$this->config['dbfield'].'_country`='.$this->value['country'];
		}
		if (!empty($this->value['state'])) {
			$myreturn.=' AND `'.$this->config['dbfield'].'_state`='.$this->value['state'];
		}
		if (!empty($this->value['city'])) {
			$myreturn.=' AND `'.$this->config['dbfield'].'_city`='.$this->value['city'];
		}
		return $myreturn;
	}

	function query_create($dbfield) {
		return " ADD `{$dbfield}_country` int(3) not null default 0, ADD `{$dbfield}_state` int(10) not null default 0, ADD `{$dbfield}_city` int(10) not null default 0, ADD `{$dbfield}_zip` varchar(10) not null default ''";
	}

	function query_drop($dbfield) {
		return " DROP `{$dbfield}_country`,DROP `{$dbfield}_state`,DROP `{$dbfield}_city`,DROP `{$dbfield}_zip`";
	}

	function edit_js() {
		$myreturn='$(\'#'.$this->config['dbfield'].'_country,#'.$this->config['dbfield'].'_state\').bind(\'change\',function() {
			$(\'#\'+$(this).attr(\'id\')).before(\'<span class="loading"></span>\');
			$.post(\'http://\'+window.location.hostname+\'/ajax/location.php\',
					{\'field\':$(this).attr(\'id\'),\'val\':$(this).val()},
					function(data) {
						if (data!=null && data!=\'\') {
							var allopts=data.split("\n");
							var str_field=allopts[0];
							var toshow=allopts[1].split(\'|\');
							$(\'#row_\'+str_field+\'state\').addClass(\'invisible\').removeClass(\'visible\');
							$(\'#row_\'+str_field+\'city\').addClass(\'invisible\').removeClass(\'visible\');
							$(\'#row_\'+str_field+\'zip\').addClass(\'invisible\').removeClass(\'visible\');
							for (i=0;i<toshow.length;i++) {
								$(\'#row_\'+toshow[i]).addClass(\'visible\').removeClass(\'invisible\');
							}
							if (allopts.length>3) {
								var to_update=$(\'#\'+str_field+allopts[2]);
								to_update.html(\'<option>Loading</option>\');
								var towrite=\'\';
								for (i=3;i<allopts.length;i++) {
									oneopt=allopts[i].split(\'|\');
									towrite+=\'<option value="\'+oneopt[0]+\'">\'+oneopt[1]+\'</option>\';
								}
								to_update.html(towrite);
								to_update.focus();
							}
							$(\'.loading\').remove();
						}
					}
			);
		});';
		if (empty($this->is_search)) {
			if (!empty($this->config['required'])) {
				$myreturn.='$(\'#'.$this->config['dbfield'].'_country\').parents(\'form\').bind(\'submit\',function() {
					if ($(\'#'.$this->config['dbfield'].'_country\').val()=='.$this->empty_value['edit']['country'].') {
						alert(\'"'.$this->config['label'].'" cannot be empty\');
						return false;
					}
				});';
	/*	to make the state/city/zip required too we would have to query the db to see if prefered_input is 's' or 'z'
				$myreturn.='$(\'#'.$this->config['dbfield'].'_state\').parents(\'form\').bind(\'submit\',function() {
					var stateField=$(\'#'.$this->config['dbfield'].'_state\');
					if (stateField[0].options.length>1 && stateField.val()=='.$this->empty_value['edit']['state'].') {
						alert(\'"'.$GLOBALS['_lang'][127].'" cannot be empty\');
						stateField.focus();
						return false;
					}
				});';
				$myreturn.='$(\'#'.$this->config['dbfield'].'_city\').parents(\'form\').bind(\'submit\',function() {
					var cityField=$(\'#'.$this->config['dbfield'].'_city\');
					if (cityField[0].options.length>1 && cityField.val()=='.$this->empty_value['edit']['city'].') {
						alert(\'"'.$GLOBALS['_lang'][128].'" cannot be empty\');
						cityField.focus();
						return false;
					}
				});';
	*/
			}
		}
		return $myreturn;
	}

	function validation_server() {
		$myreturn=true;
		if (!empty($this->config['required'])) {
			if (((int)$this->value['country'])==$this->empty_value['edit']['country']) {
				$myreturn=false;
			}
			//to make the state/city/zip required too we would have to query the db to see if prefered_input is 's' or 'z'
		}
		return $myreturn;
	}

	function get_value($as_array=false) {
		if ($as_array) {
			return array($this->config['dbfield'].'_country'=>$this->value['country'],$this->config['dbfield'].'_state'=>$this->value['state'],$this->config['dbfield'].'_city'=>$this->value['city'],$this->config['dbfield'].'_zip'=>$this->value['zip']);
		} else {
			return $this->value;
		}
	}
}

if (defined('IN_ADMIN')) {
	$GLOBALS['accepted_fieldtype']['direct']['field_location']='Location';
	$GLOBALS['accepted_fieldtype']['search']['field_location']='Location';
}
