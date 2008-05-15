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
		} elseif (!empty($this->config['search_type']) && is_file(_BASEPATH_.'/includes/classes/fields/'.$this->config['search_type'].'.class.php')) {
			$class_name=$this->config['search_type'];
			$new_config=$this->config;
			if (isset($new_config['search_default'])) {
				$new_config['label']=$new_config['search_label'];
				$new_config['default_value']=$new_config['search_default'];
				unset($new_config['search_default'],$new_config['search_label'],$new_config['searchable'],$new_config['required'],$new_config['search_type'],$new_config['reg_page']);
			}
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
		return '';
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

	function edit_js() {
		$myreturn='';
		if (!empty($this->config['required'])) {
			$myreturn.='$(\'#'.$this->config['dbfield'].'\').parents(\'form\').bind(\'submit\',function() {
				if ($(\'#'.$this->config['dbfield'].'\',this).val()=='.$this->empty_value['edit'].') {
					alert(\'"'.$this->config['label'].'" cannot be empty\');
					$(\'#'.$this->config['dbfield'].'\',this).focus();
					return false;
				}
			});';
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
