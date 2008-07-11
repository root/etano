<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_textfield.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_select extends iprofile_field {
	var $empty_value=array('edit'=>0,'display'=>'?','search'=>0);
	var $display_name='Select Box';
	var $allowed_search_types=array('field_select','field_mchecks','field_range');
	// how to render the search_value input in the accepted values list (as radios or as checkboxes)
	var $_search_defaults_input_type=array('field_select'=>'radio','field_mchecks'=>'checks','field_range'=>'checks');

	function field_select($config=array(),$is_search=false) {
		$this->config=$config;
		$this->is_search=$is_search;
		if ($is_search) {
			$this->config['accepted_values'][0]=$GLOBALS['_lang'][159];
		}
		if (isset($this->config['default_value'])) {
			$this->value=(int)$this->config['default_value'];
		} else {
			$this->value=$this->empty_value['edit'];
		}
	}

	function set_value(&$all_values,$sanitize=true) {
		if ($sanitize) {
			$this->value=sanitize_and_format_gpc($all_values,$this->config['dbfield'],TYPE_INT,0,$this->empty_value['edit']);
		} elseif (isset($all_values[$this->config['dbfield']])) {
			$this->value=(int)$all_values[$this->config['dbfield']];
		}
		return true;
	}

	function edit($tabindex=1) {
		return '<select name="'.$this->config['dbfield'].'" id="'.$this->config['dbfield'].'" tabindex="'.$tabindex.'">'.vector2options($this->config['accepted_values'],$this->value).'</select>';
	}

	function display() {
		// if we sanitize here " will be rendered as &quot; which is not what we want
		return isset($this->config['accepted_values'][$this->value]) ? $this->config['accepted_values'][$this->value] : $this->empty_value['display'];
	}

	function search() {
		if ($this->search!=null) {
			return $this->search;
		} elseif (!empty($this->config['search_type'])) {
			$class_name=$this->config['search_type'];
			$new_config=$this->config;
			$new_config['label']=$new_config['search_label'];
			if (isset($new_config['search_default'])) {
				$new_config['default_value']=$new_config['search_default'];
			} else {
				unset($new_config['default_value']);
			}
			unset($new_config['search_default'],$new_config['search_label'],$new_config['searchable'],$new_config['required'],$new_config['search_type'],$new_config['reg_page']);
			$new_config['parent_class']=get_class();
			$this->search=new $class_name($new_config,true);
//			$temp=array($this->config['dbfield']=>$this->value);
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
			$myreturn.='<div id="row_accvals" class="clear">
				<label>Accepted Values</label>
				<a href="#" id="accvals_add_first" title="Add a new value at the beginning of the list">Add new value</a> (at the beginning of the list)
				<div id="actual_values"></div>
			</div>';
			$query="SELECT a.`accval_id`,b.`lang_value` as `value`,a.`def_value`,a.`search_value` FROM `{$dbtable_prefix}profile_field_accvals` a, `{$dbtable_prefix}lang_strings` b WHERE a.`fk_lk_id_name`=b.`fk_lk_id` AND b.`skin`='$default_skin_code' AND a.`fk_pfield_id`=".$output['pfield_id']." ORDER BY a.`sort`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$accvals=array();
			// grab the accepted values and pass them to js in json format
			while ($rsrow=mysql_fetch_assoc($res)) {
				// no need to sanitize the value because encoding it to json below solves it for us.
				$accvals[]=$rsrow;
			}
			$json=new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS | SERVICES_JSON_LOOSE_TYPE);
			$accvals=$json->encode($accvals);
			$myreturn.='<script type="text/javascript">
				$(function() {
					search_defaults_input_type = [];
					';
			foreach ($this->_search_defaults_input_type as $k=>$v) {
				$myreturn.="search_defaults_input_type['$k'] = '$v';\n";
			}
			$myreturn.='my_accvals=new Etano.accvals('.$accvals.');
					my_accvals.set_defval_type(\'radio\');
					my_accvals.set_searchval_type(search_defaults_input_type[$(\'#search_type\').val()]);
					my_accvals.render(\'actual_values\');

					$(\'#search_type\').change(function() {
						my_accvals.set_searchval_type(search_defaults_input_type[$(this).val()]);
						my_accvals.render(\'actual_values\');
						rebind_events();
					});

					// add a new field at the beginning of the list
					$(\'#accvals_add_first\').click(function() {
						var myval=prompt_value(\'\');
						if (myval) {
							my_accvals.add(0,{value:myval});
							my_accvals.render(\'actual_values\');
							rebind_events();
						}
						return false;
					});

					$(\'#profile_fields_addedit\').bind(\'submit\',function() {
						$(this).append(my_accvals.on_submit());
					});

					rebind_events();
				});

				function prompt_value(val) {
					if (!val) {
						val=\'\';
					}
					var myval=prompt(\'Please enter the new value.\',val);
					if (myval && myval!=\'\') {
						return myval;
					}
					return false;
				}

				function rebind_events() {
					$(\'.accvals_edit\').click(function() {
						var my_idx=$(this).attr(\'id\').substr(5);
						var myval=prompt_value(my_accvals.container[my_idx].value);
						if (myval) {
							my_accvals.change(my_idx,{value:myval});
							my_accvals.render(\'actual_values\');
							rebind_events();
						}
						return false;
					});

					$(\'.accvals_add\').click(function() {
						var my_idx=$(this).attr(\'id\').substr(4);
						var myval=prompt_value(\'\');
						if (myval) {
							my_idx++;
							my_accvals.add(my_idx,{value:myval});
							my_accvals.render(\'actual_values\');
							rebind_events();
						}
						return false;
					});

					$(\'.accvals_del\').click(function() {
						if (confirm(\'Are you sure you want to remove this value?\')) {
							my_accvals.del($(this).attr(\'id\').substr(4));
							my_accvals.render(\'actual_values\');
							rebind_events();
						}
						return false;
					});

					$(\'input.defval\').click(function() {
						var my_idx=$(this).attr(\'id\').substr(7);
						my_accvals.set_defval(my_idx,this.checked);
					});

					$(\'input.searchval\').click(function() {
						var my_idx=$(this).attr(\'id\').substr(10);
						my_accvals.set_searchval(my_idx,this.checked);
					});
				}
			</script>';
		}
		return $myreturn;
	}

	function admin_processor() {
		$error=false;
		$my_input=array();
		global $input,$__field2format,$dbtable_prefix,$default_skin_code;
		if (!$this->is_search) {
			$json=new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS | SERVICES_JSON_LOOSE_TYPE);
			$temp=$json->decode(urldecode(sanitize_and_format_gpc($_POST,'accvals_new',TYPE_STRING,0,'')));
			for ($i=0;isset($temp[$i]);++$i) {
				$temp[$i]['value']=sanitize_and_format($temp[$i]['value'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
				$temp[$i]['after']=(int)$temp[$i]['after'];
				$temp[$i]['def_value']=(int)$temp[$i]['def_value'];
				$temp[$i]['search_value']=(int)$temp[$i]['search_value'];
			}
			$accvals_new=$temp;

			$temp=$json->decode(urldecode(sanitize_and_format_gpc($_POST,'accvals_changed',TYPE_STRING,0,'')));
			for ($i=0;isset($temp[$i]);++$i) {
				$temp[$i]['accval_id']=(int)$temp[$i]['accval_id'];
				$temp[$i]['value']=sanitize_and_format($temp[$i]['value'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
				$temp[$i]['def_value']=(int)$temp[$i]['def_value'];
				$temp[$i]['search_value']=(int)$temp[$i]['search_value'];
			}
			$accvals_changed=$temp;
			$temp=$json->decode(urldecode(sanitize_and_format_gpc($_POST,'accvals_deleted',TYPE_STRING,0,0)));
			$accvals_deleted=array();
			for ($i=0;isset($temp[$i]);++$i) {
				$accvals_deleted[]=(int)$temp[$i]['accval_id'];
			}

			if (!empty($accvals_new)) {
				// create the language keys and strings for the default skin
				for ($i=0;isset($accvals_new[$i]);++$i) {
					$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTFIELD.",`lk_diz`='Field value',`lk_use`=".LK_FIELD;
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$accvals_new[$i]['fk_lk_id']=mysql_insert_id();
					$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$accvals_new[$i]['value']."',`fk_lk_id`=".$accvals_new[$i]['fk_lk_id'].",`skin`='$default_skin_code'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
				// insert the new values and re-sort the values for this field. Ugly and slow code with lots of queries :(
				$last_accvalid=0;
				for ($i=0;isset($accvals_new[$i]);++$i) {
					if (empty($accvals_new[$i]['after'])) {
						$accvals_new[$i]['after']=$last_accvalid;
					}
					$query="UPDATE `{$dbtable_prefix}profile_field_accvals` a,`{$dbtable_prefix}profile_field_accvals` b SET a.`sort`=a.`sort`+1 WHERE a.`fk_pfield_id`=".$input['pfield_id']." AND b.`fk_pfield_id`=".$input['pfield_id']." AND a.`sort`>b.`sort` AND b.`accval_id`=".$accvals_new[$i]['after'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					// since this is a select box, there can be only one selected value.
					// unselect all other values if this one is the selected one
					if (!empty($accvals_new[$i]['def_value'])) {
						$query="UPDATE `{$dbtable_prefix}profile_field_accvals` SET `def_value`=0 WHERE `fk_pfield_id`=".$input['pfield_id'];
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					}
					$query="INSERT INTO `{$dbtable_prefix}profile_field_accvals` SET `fk_lk_id_name`=".$accvals_new[$i]['fk_lk_id'].",`fk_pfield_id`=".$input['pfield_id'].",`def_value`=".$accvals_new[$i]['def_value'].",`search_value`=".$accvals_new[$i]['search_value'].",`sort`=".($accvals_new[$i]['after']+1);
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$last_accvalid=mysql_insert_id();
				}
			}

			if (!empty($accvals_deleted)) {
				$query="SELECT `fk_lk_id_name` FROM `{$dbtable_prefix}profile_field_accvals` WHERE `accval_id` IN (".join(',',$accvals_deleted).")";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$temp=array();
				while ($rsrow=mysql_fetch_assoc($res)) {
					$temp[]=$rsrow['fk_lk_id_name'];
				}
				$query="DELETE FROM `{$dbtable_prefix}profile_field_accvals` WHERE `accval_id` IN ('".join("','",$accvals_deleted)."')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id` IN ('".join("','",$temp)."')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id` IN ('".join("','",$temp)."')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}

			if (!empty($accvals_changed)) {
				for ($i=0;isset($accvals_changed[$i]);++$i) {
					// update the language string
					$query="UPDATE `{$dbtable_prefix}lang_strings` a,`{$dbtable_prefix}profile_field_accvals` b SET a.`lang_value`='".$accvals_changed[$i]['value']."' WHERE a.`fk_lk_id`=b.`fk_lk_id_name` AND a.`skin`='$default_skin_code' AND b.`accval_id`=".$accvals_changed[$i]['accval_id'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					// since this is a select box, there can be only one selected value.
					// unselect all other values if this one is the selected one
					if (!empty($accvals_changed[$i]['def_value'])) {
						$query="UPDATE `{$dbtable_prefix}profile_field_accvals` SET `def_value`=0 WHERE `fk_pfield_id`=".$input['pfield_id'];
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					}
					$query="UPDATE `{$dbtable_prefix}profile_field_accvals` SET `def_value`=".$accvals_changed[$i]['def_value'].",`search_value`=".$accvals_changed[$i]['search_value']." WHERE `accval_id`=".$accvals_changed[$i]['accval_id'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
			}

			$query="SELECT `fk_lk_id_name`,`def_value`,`search_value` FROM `{$dbtable_prefix}profile_field_accvals` WHERE `fk_pfield_id`=".$input['pfield_id']." ORDER BY `sort`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$temp=array();
			$def_value=0;
			$search_values=array();
			$i=1;
			while ($rsrow=mysql_fetch_assoc($res)) {
				$temp[]=$rsrow['fk_lk_id_name'];
				if (!empty($rsrow['def_value'])) {
					$def_value=$i;
				}
				if (!empty($rsrow['search_value'])) {
					$search_values[]=$i;
				}
				++$i;
			}
			$my_input['accepted_values']="array(''";
			for ($i=0;isset($temp[$i]);++$i) {
				$my_input['accepted_values'].=",&\$GLOBALS['_lang'][".$temp[$i].']';
			}
			$my_input['accepted_values'].=')';
			$my_input['default_value']=$def_value;
			$my_input['search_default']='';
			if ($input['search_type']=='field_select') {
				if (isset($search_values[0])) {
					$my_input['search_default']=(int)$search_values[0];
				}
			} else {
				$my_input['search_default']='array('.join(',',$search_values).')';
			}

			// if the search_type field has any config to save, grab it here.
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
		return '`'.$this->config['dbfield'].'`';
	}

	function query_set() {
		return '`'.$this->config['dbfield']."`=".$this->value;
	}

	function query_search() {
		$myreturn='';
		if ($this->value!=$this->empty_value['edit']) {
			if ($this->config['parent_class']=='field_select') {
				$myreturn=" AND `".$this->config['dbfield']."`=".$this->value;
			} elseif ($this->config['parent_class']=='field_mchecks') {
				$myreturn=' AND `'.$this->config['dbfield']."` LIKE '%|".$this->value.'|%';
			}
		}
		return $myreturn;
	}

	function query_create($dbfield) {
		return " ADD `{$dbfield}` int(5) not null default 0";
	}

	function query_drop($dbfield) {
		return " DROP `{$dbfield}`";
	}

	function edit_js() {
		$myreturn='';
		if (empty($this->is_search)) {
			if (!empty($this->config['required'])) {
				$myreturn.='$(\'#'.$this->config['dbfield'].'\').parents(\'form\').bind(\'submit\',function() {
					if ($(\'#'.$this->config['dbfield'].'\',this).val()=='.$this->empty_value['edit'].') {
						alert(\'"'.$this->config['label'].'" cannot be empty\');
						$(\'#'.$this->config['dbfield'].'\',this).focus();
						return false;
					}
				});';
			}
		}
		return $myreturn;
	}

	function validation_server() {
		$myreturn=true;
		if (!empty($this->config['required']) && $this->value==$this->empty_value['edit']) {
			$myreturn=false;
		}
		return $myreturn;
	}

	function get_value($as_array=false) {
		if ($as_array) {
			return array($this->config['dbfield']=>$this->value);
		} else {
			return $this->value;
		}
	}
}

if (defined('IN_ADMIN')) {
	$GLOBALS['accepted_fieldtype']['direct']['field_select']='Select Box';
	$GLOBALS['accepted_fieldtype']['search']['field_select']='Select Box';
}
