<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_birthdate.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_birthdate extends iprofile_field {
	var $empty_value=array('edit'=>array('year'=>0,'month'=>0,'day'=>0),'display'=>'-');

	function field_birthdate($config=array(),$is_search=false) {
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
			$this->value['day']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_day',TYPE_INT,0,$this->empty_value['edit']['day']);
			$this->value['month']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_month',TYPE_INT,0,$this->empty_value['edit']['month']);
			$this->value['year']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_year',TYPE_INT,0,$this->empty_value['edit']['year']);
		} elseif (isset($all_values[$this->config['dbfield']])) {
			$temp=explode('-',$all_values[$this->config['dbfield']]);
			if (count($temp)==3) {
				$this->value['year']=(int)$temp[0];
				$this->value['month']=(int)$temp[1];
				$this->value['day']=(int)$temp[2];
			}
		}
		$this->value['day']=str_pad($this->value['day'],2,'0',STR_PAD_LEFT);
		$this->value['month']=str_pad($this->value['month'],2,'0',STR_PAD_LEFT);
		return true;
	}

	function edit($tabindex=1) {
		$myreturn='<select name="'.$this->config['dbfield'].'_month" id="'.$this->config['dbfield'].'_month" tabindex="'.$tabindex.'">'.vector2options($GLOBALS['accepted_months'],$this->value['month']).'</select>';
		$myreturn.='<select name="'.$this->config['dbfield'].'_day" id="'.$this->config['dbfield'].'_day" tabindex="'.$tabindex.'"><option value="">'.$GLOBALS['_lang'][131].'</option>'.interval2options(1,31,$this->value['day']).'</select>';
		$myreturn.='<select name="'.$this->config['dbfield'].'_year" id="'.$this->config['dbfield'].'_year" tabindex="'.$tabindex.'"><option value="">'.$GLOBALS['_lang'][132].'</option>'.interval2options($this->config['accepted_values'][1],$this->config['accepted_values'][2],$this->value['year'],array(),1,2).'</select>';
		return $myreturn;
	}

	/**
	 *	displays the age, not the actual birthday
	 *	We assume that the label will be read after the call to display() so we set the label here to search_label
	 *	("Age:" instead of "Birtdate:")
	 */
	function display() {
		$this->config['label']=$this->config['search_label'];
		// we prepend '1' so the type cast won't convert '04' to 4
		$myreturn=(int)gmdate('Y')-(int)$this->value['year']-(int)(((int)('1'.gmdate('md')))<((int)('1'.$this->value['month'].$this->value['day'])));
		if ($myreturn>110) {
			$myreturn=$this->empty_value['display'];
		}
		return $myreturn;
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
//			$temp=array($this->config['dbfield'].'_year'=>$this->value['year'],$this->config['dbfield'].'_month'=>$this->value['month'],$this->config['dbfield'].'_day'=>$this->value['day']);
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
		return "DATE_FORMAT(`".$this->config['dbfield']."`,'%Y-%m-%d') as `".$this->config['dbfield'].'`';
	}

	function query_set() {
		return '`'.$this->config['dbfield']."`='".$this->value['year'].$this->value['month'].$this->value['day']."'";
	}

	function edit_js() {
		$myreturn='';
		if ($this->config['required']) {
			$myreturn.='$(\'#'.$this->config['dbfield'].'_year\').parents(\'form\').bind(\'submit\',function() {
				var is_empty=true;
				if ($(\'#'.$this->config['dbfield'].'_day\').val()!='.$this->empty_value['edit']['day'].' && $(\'#'.$this->config['dbfield'].'_month\').val()!='.$this->empty_value['edit']['month'].' && $(\'#'.$this->config['dbfield'].'_year\').val()!='.$this->empty_value['edit']['year'].') {
					is_empty=false;
				}
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
		if (!empty($this->config['required'])) {
			if (((int)$this->value['day'])==$this->empty_value['edit']['day'] || ((int)$this->value['month'])==$this->empty_value['edit']['month'] || ((int)$this->value['year'])==$this->empty_value['edit']['year']) {
				$myreturn=false;
			}
		}
		return $myreturn;
	}

	function get_value($as_array=false) {
		if ($as_array) {
			return array($this->config['dbfield'].'_year'=>$this->value['year'],$this->config['dbfield'].'_month'=>$this->value['month'],$this->config['dbfield'].'_day'=>$this->value['day']);
		} else {
			return $this->value;
		}
	}
}
