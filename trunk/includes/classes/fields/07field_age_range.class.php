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

	function __construct($config=array(),$is_search=false) {
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
		$now1=(int)gmdate('Y');
		$now2=gmdate('-m-d');
		if ($this->value['max']!=$this->empty_value['edit']['max']) {
			$myreturn.=' AND `'.$this->config['dbfield']."`>='".($now1-(int)$this->value['max']).$now2."'";
		}
		if ($this->value['min']!=$this->empty_value['edit']['min']) {
			$myreturn.=' AND `'.$this->config['dbfield']."`<='".($now1-(int)$this->value['min']).$now2."'";
		}
		return $myreturn;
	}

	function edit_admin() {
		global $output;
		$myreturn='';
		if ($this->is_search) {
			$output['search_default']['min']=isset($output['search_default']['min']) ? $output['search_default']['min'] : '';
			$output['search_default']['max']=isset($output['search_default']['max']) ? $output['search_default']['max'] : '';
			$myreturn.='<div class="clear">
					<label for="search_start">Default search range:</label>
					<input class="text numeric" type="text" name="search_start" id="search_start" value="'.$output['search_default']['min'].'" size="3" maxlength="3" tabindex="15" />
					to
					<input class="text numeric" type="text" name="search_end" id="search_end" value="'.$output['search_default']['max'].'" size="3" maxlength="3" tabindex="16" />
					<p class="comment">Enter here the ages you want preselected in the search box like Age: 18 to 30.</p>
				</div>';
		}
		return $myreturn;
	}

	function admin_processor() {
		$error=false;
		$my_input=array();
		if ($this->is_search) {
			$my_input['search_default']=array('min'=>sanitize_and_format_gpc($_POST,'search_start',TYPE_INT,0,0),'max'=>sanitize_and_format_gpc($_POST,'search_end',TYPE_INT,0,0));
			return $my_input;
		}
		return $error;
	}
}

if (defined('IN_ADMIN')) {
	$GLOBALS['accepted_fieldtype']['search']['field_age_range']='Age Range';
}
