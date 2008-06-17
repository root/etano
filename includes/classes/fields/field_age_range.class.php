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
	var $display_name='Age Range';

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

	function edit_admin($mode='direct') {
		global $output;
		$myreturn='';
		if ($mode=='search') {
			$output['search_start']=isset($output['search_start']) ? $output['search_start'] : '';
			$output['search_end']=isset($output['search_end']) ? $output['search_end'] : '';
			$myreturn.='<div class="clear">
				<label for="search_start">Default search range:</label>
				<input class="text numeric" type="text" name="search_start" id="search_start" value="'.$output['search_start'].'" size="2" maxlength="2" tabindex="15" />
				to
				<input class="text numeric" type="text" name="search_end" id="search_end" value="'.$output['search_end'].'" size="2" maxlength="2" tabindex="16" />
				<p class="comment">Enter here the ages you want preselected in the search box like Age: 18 to 30. Must match the years above.</p>';
		}
		return $myreturn;
	}

	function admin_processor($mode='direct') {
		$error=false;
		$my_input=array();
		if ($mode=='search') {
			$my_input['search_start']=sanitize_and_format_gpc($_POST,'search_start',TYPE_INT,0,0);
			$my_input['search_end']=sanitize_and_format_gpc($_POST,'search_end',TYPE_INT,0,0);
			return $my_input;
		}
		return $error;
	}
}

if (defined('IN_ADMIN')) {
	$accepted_fieldtype['search']['field_age_range']='Age Range';
}
