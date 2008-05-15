<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_age_range.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_age_range extends field_range {

	function field_age_range($config=array(),$is_search=false) {
		$this->config=$config;
		$this->is_search=$is_search;
		if ($is_search) {
			$this->config['accepted_values'][0]=$GLOBALS['_lang'][159];
		}
		if (isset($this->config['default_value'])) {
			$this->value=$this->config['default_value'];
		} else {
			$this->value=$this->empty_value['edit'];
		}
	}

	function edit($tabindex=1) {
		$myreturn='<select name="'.$this->config['dbfield'].'_min" id="'.$this->config['dbfield'].'_min" tabindex="'.$tabindex.'"><option value="'.$this->empty_value['edit']['min'].'">'.$GLOBALS['_lang'][159].'</option>'.interval2options($this->config['accepted_values']['min'],$this->config['accepted_values']['max'],$this->value['min']).'</select>';
		$myreturn.=' - ';
		$myreturn.='<select name="'.$this->config['dbfield'].'_max" id="'.$this->config['dbfield'].'_max" tabindex="'.$tabindex.'"><option value="'.$this->empty_value['edit']['max'].'">'.$GLOBALS['_lang'][159].'</option>'.interval2options($this->config['accepted_values']['min'],$this->config['accepted_values']['max'],$this->value['max']).'</select>';
		return $myreturn;
	}

	function query_search() {
		$myreturn='';
		$now=gmdate('YmdHis');
		if ($this->value['max']!=$this->empty_value['edit']['max']) {
			$myreturn.=' AND `'.$this->config['dbfield']."`>=DATE_SUB('$now',INTERVAL ".$this->value['max'].' YEAR)';
		}
		if ($this->value['min']!=$this->empty_value['edit']['min']) {
			$myreturn.=' AND `'.$this->config['dbfield']."`<=DATE_SUB('$now',INTERVAL ".$this->value['min'].' YEAR)';
		}
		return $myreturn;
	}
}
