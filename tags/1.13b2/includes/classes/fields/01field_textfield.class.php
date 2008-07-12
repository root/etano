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


class field_textfield extends iprofile_field {
	var $empty_value=array('edit'=>'','display'=>'','search'=>'');
	var $display_name='Textfield';

	function field_textfield($config=array(),$is_search=false) {
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
			$this->value=remove_banned_words(sanitize_and_format_gpc($all_values,$this->config['dbfield'],TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD],$this->empty_value['edit']));
		} elseif (isset($all_values[$this->config['dbfield']])) {
			$this->value=$all_values[$this->config['dbfield']];
		}
		return true;
	}

	function edit($tabindex=1) {
		return '<input type="text" class="text" name="'.$this->config['dbfield'].'" id="'.$this->config['dbfield'].'" tabindex="'.$tabindex.'" value="'.sanitize_and_format($this->value,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]).'" />';
	}

	function display() {
		return sanitize_and_format($this->value,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
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
		global $output,$__field2format;
		$myreturn='';
		if (!$this->is_search) {
			$output['changes_status']=!empty($output['changes_status']) ? 'checked="checked"' : '';
			$myreturn.='<div class="clear">
				<label for="changes_status">Changes status?</label>
				<input type="checkbox" class="checkbox" name="changes_status" id="changes_status" value="1" '.$output['changes_status'].' />
				<p class="comment">If a member makes changes to this field, should his/her profile be re-approved by an administrator?</p>
			</div>';
		}
		return $myreturn;
	}

	function admin_processor() {
		$error=false;
		return $error;
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
		return ' AND `'.$this->config['dbfield']."` LIKE '".$this->value."%'";
	}

	function query_create($dbfield) {
		return " ADD `{$dbfield}` varchar(100) not null default ''";
	}

	function query_drop($dbfield) {
		return " DROP `{$dbfield}`";
	}

	function edit_js() {
		$myreturn='';
		if (!empty($this->config['required'])) {
			$myreturn.='$(\'#'.$this->config['dbfield'].'\').parents(\'form\').bind(\'submit\',function() {
				if ($(\'#'.$this->config['dbfield'].'\',this).val()==\''.$this->empty_value['edit'].'\') {
					alert(\''.$this->config['label'].' cannot be empty\');
					return false;
				}
			})';
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
	$GLOBALS['accepted_fieldtype']['direct']['field_textfield']='Textfield';
}
