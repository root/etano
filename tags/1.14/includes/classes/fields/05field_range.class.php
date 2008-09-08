<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_range.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_range extends iprofile_field {
	var $empty_value=array('edit'=>array('min'=>0,'max'=>0),'display'=>'?');
	var $display_name='Range';

	function field_range($config=array(),$is_search=false) {
		$this->config=$config;
		$this->is_search=$is_search;
		if ($is_search) {
			$this->config['accepted_values'][0]=$GLOBALS['_lang'][159];
		}
		if (isset($this->config['default_value'][0])) {
			$this->value['min']=(int)$this->config['default_value'][0];
		} else {
			$this->value['min']=$this->empty_value['edit']['min'];
		}
		if (isset($this->config['default_value'][1])) {
			$this->value['max']=(int)$this->config['default_value'][1];
		} else {
			$this->value['max']=$this->empty_value['edit']['max'];
		}
	}

	function set_value(&$all_values,$sanitize=true) {
		$this->value=$this->empty_value['edit'];
		if ($sanitize) {
			$this->value['min']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_min',TYPE_INT,0,$this->empty_value['edit']['min']);
			$this->value['max']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_max',TYPE_INT,0,$this->empty_value['edit']['max']);
		} else {
			if (isset($all_values[$this->config['dbfield'].'_min'])) {
				$this->value['min']=(int)$all_values[$this->config['dbfield'].'_min'];
			}
			if (isset($all_values[$this->config['dbfield'].'_max'])) {
				$this->value['max']=(int)$all_values[$this->config['dbfield'].'_max'];
			}
		}
		if ($this->value['min']>$this->value['max']) {
			$temp=$this->value['max'];
			$this->value['max']=$this->value['min'];
			$this->value['min']=$temp;
		}
		return true;
	}

	function edit($tabindex=1) {
		$myreturn='<select name="'.$this->config['dbfield'].'_min" id="'.$this->config['dbfield'].'_min" tabindex="'.$tabindex.'">'.vector2options($this->config['accepted_values'],$this->value['min']).'</select>';
		$myreturn.=' - ';
		$myreturn.='<select name="'.$this->config['dbfield'].'_max" id="'.$this->config['dbfield'].'_max" tabindex="'.$tabindex.'">'.vector2options($this->config['accepted_values'],$this->value['max']).'</select>';
		return $myreturn;
	}

	function display() {
		// if we sanitize here " will be rendered as &quot; which is not what we want
		$myreturn=isset($this->config['accepted_values'][$this->value['min']]) ? $this->config['accepted_values'][$this->value['min']] : $this->empty_value['display'];
		$myreturn.=' - ';
		$myreturn=isset($this->config['accepted_values'][$this->value['max']]) ? $this->config['accepted_values'][$this->value['max']] : $this->empty_value['display'];
		return $myreturn;
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
			$new_config['parent_class']=get_class($this);
			$this->search=new $class_name($new_config,true);
			$temp=array($this->config['dbfield'].'_min'=>$this->value['min'],$this->config['dbfield'].'_max'=>$this->value['max']);
//			$this->search->set_value($temp,false);
//			return $this->search;
		} else {
			return $this;
		}
	}

	function edit_admin() {
		return '';
	}

	function admin_processor() {
		$error=false;
		$my_input=array();
		if ($this->is_search) {
			return $my_input;
		}
		return $error;
	}

	function query_select() {
		return '';
	}

	function query_set() {
		return '';
	}

	function query_search() {
		$myreturn='';
		if ($this->value['max']!=$this->empty_value['edit']['max']) {
			$myreturn.=' AND `'.$this->config['dbfield'].'`<='.$this->value['max'];
		}
		if ($this->value['min']!=$this->empty_value['edit']['min']) {
			$myreturn.=' AND `'.$this->config['dbfield'].'`>='.$this->value['min'];
		}
		return $myreturn;
	}

	function edit_js() {
		$myreturn='';
		return $myreturn;
	}

	function validation_server() {
		$myreturn=true;
		return $myreturn;
	}
}

if (defined('IN_ADMIN')) {
	$GLOBALS['accepted_fieldtype']['search']['field_range']='Range';
}
