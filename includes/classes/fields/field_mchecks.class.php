<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_mchecks.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_mchecks extends iprofile_field {
	var $empty_value=array('edit'=>'','display'=>'');

	function field_mchecks($config=array(),$is_search=false) {
		$this->config=$config;
		$this->is_search=$is_search;
		if (isset($this->config['default_value'])) {
			$this->value=$this->config['default_value'];
		} else {
			$this->value=$this->empty_value['edit'];
		}
	}

	function set_value(&$all_values,$sanitize=true) {
		if ($sanitize) {
			$this->value=sanitize_and_format_gpc($all_values,$this->config['dbfield'],TYPE_ARRAY_LARGE,$GLOBALS['__field2format'][FIELD_CHECKBOX_LARGE],$this->empty_value['edit']);
		} elseif (isset($all_values[$this->config['dbfield']])) {
			$this->value=$all_values[$this->config['dbfield']];
		}
		return true;
	}

	function edit($tabindex=1) {
		return vector2checkboxes_str($this->config['accepted_values'],array(0),$this->config['dbfield'],$this->value,1,true,'tabindex="'.$tabindex.'"');
	}

	function display() {
		return sanitize_and_format(vector2string_str($this->config['accepted_values'],$this->value),TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
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
		// $this->value should be sanitized for DB if set_value() didn't sanitize the input.
		// This means that we should call this function only in an addedit processor!!!!
		return '`'.$this->config['dbfield']."`='".$this->value."'";
	}

	function query_search() {
		$myreturn='';
		if ($this->value!=$this->empty_value['edit']) {
			$all_values=explode('|',substr($this->value,1,-1));
			if (count($all_values)) {
				if ($this->config['parent_class']=='field_select') {
					$myreturn.=' AND (';
					for ($j=0;isset($all_values[$j]);++$j) {
						$myreturn.='`'.$this->config['dbfield'].'`='.$all_values[$j].' OR ';
					}
					if (substr($myreturn,-4)==' OR ') {
						$myreturn=substr($myreturn,0,-4);	// substract the last ' OR '
					}
					$myreturn.=')';
				} elseif ($this->config['parent_class']=='field_mchecks') {
					$myreturn.=' AND (';
					for ($j=0;isset($all_values[$j]);++$j) {
						$myreturn.='`'.$this->config['dbfield']."` LIKE '%|".$all_values[$j]."|%' OR ";
					}
					if (substr($myreturn,-4)==' OR ') {
						$myreturn=substr($myreturn,0,-4);	// substract the last ' OR '
					}
					$myreturn.=')';
				}
			}
		}
		return $myreturn;
	}

	function edit_js() {
		$myreturn='';
		if (!empty($this->config['required'])) {
			$myreturn.='$(\'input[@id^='.$this->config['dbfield'].']\').parents(\'form\').bind(\'submit\',function() {
				var is_empty=true;
				$(\'input[@id^='.$this->config['dbfield'].']\').each(function() {
					if (this.checked) {
						is_empty=false;
					}
				});
				if (is_empty) {
					alert(\'"'.$this->config['label'].'" cannot be empty\');
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
			$all_values=explode('|',substr($this->value,1,-1));
			return array($this->config['dbfield']=>$all_values);
		} else {
			return $this->value;
		}
	}
}
