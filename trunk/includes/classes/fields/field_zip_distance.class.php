<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/fields/field_zip_distance.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


class field_zip_distance extends iprofile_field {
	var $empty_value=array('edit'=>array('zip'=>'','dist'=>0),'display'=>'');
	var $display_name='Zip Distance';

	function field_zip_distance($config=array(),$is_search=false) {
		$this->config=$config;
		$this->is_search=$is_search;
		if (isset($this->config['default_value'])) {
			$this->value=$this->config['default_value'];
		} else {
			$this->value=$this->empty_value['edit'];
		}
	}

	function set_value(&$all_values,$sanitize=true) {
		$this->value=$this->empty_value['edit'];
		if ($sanitize) {
			$this->value['zip']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_zip',TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD],$this->empty_value['edit']['zip']);
			$this->value['dist']=sanitize_and_format_gpc($all_values,$this->config['dbfield'].'_dist',TYPE_INT,0,$this->empty_value['edit']['dist']);
		} else {
			if (isset($all_values[$this->config['dbfield'].'_zip'])) {
				$this->value['zip']=$all_values[$this->config['dbfield'].'_zip'];
			} elseif (isset($this->config['default_value']['zip'])) {
				$this->value['zip']=$this->config['default_value']['zip'];
			}
			if (isset($all_values[$this->config['dbfield'].'_dist'])) {
				$this->value['dist']=(int)$all_values[$this->config['dbfield'].'_dist'];
			} elseif (isset($this->config['default_value']['dist'])) {
				$this->value['dist']=(int)$this->config['default_value']['dist'];
			}
		}
		return true;
	}

	function edit($tabindex=1) {
		$myreturn='<select name="'.$this->config['dbfield'].'_dist" id="'.$this->config['dbfield'].'_dist" tabindex="'.$tabindex.'">'.interval2options(1,10,$this->value['dist']).'</select> <label for="'.$this->config['dbfield'].'_zip" class="in_field_label">'.$GLOBALS['_lang'][186].'</label> <input type="text" class="text" name="'.$this->config['dbfield'].'_zip" id="'.$this->config['dbfield'].'_zip" tabindex="'.$tabindex.'" size="5" value="'.$this->value['zip'].'" />';
		return $myreturn;
	}

	function display() {
		return '';
	}

	function edit_admin($mode='direct') {
		return '';
	}

	function admin_processor($mode='direct') {
		$error=false;
		return $error;
	}

	function query_select() {
		return '`'.$this->config['dbfield'].'_country`,`'.$this->config['dbfield'].'_state`,`'.$this->config['dbfield'].'_city`,`'.$this->config['dbfield'].'_zip`';
	}

	function query_set() {
		return '`'.$this->config['dbfield'].'_country`='.$this->value['country'].',`'.$this->config['dbfield'].'_state`='.$this->value['state'].',`'.$this->config['dbfield'].'_city`='.$this->value['city'].',`'.$this->config['dbfield']."_zip`='".$this->value['zip']."'";
	}

	function query_search() {
		global $dbtable_prefix;
		$myreturn='';
		if (!empty($this->value['zip']) && !empty($this->value['dist'])) {
			$query="SELECT `rad_latitude`,`rad_longitude` FROM `{$dbtable_prefix}loc_zips` WHERE `zipcode`='".$this->value['zip']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				list($rad_latitude,$rad_longitude)=mysql_fetch_row($res);
				// WE USE ONLY MILES HERE. IF YOU WANT KM YOU NEED TO CONVERT MILES TO KM
				// earth radius=3956 miles =6367 km; 3956*2=7912
				// Haversine Formula: (more exact for small distances)
				$myreturn.=" AND a.`rad_latitude`<>-a.`rad_longitude` AND asin(sqrt(pow(sin((".(float)$rad_latitude."-a.`rad_latitude`)/2),2)+cos(".(float)$rad_latitude.")*cos(a.`rad_latitude`)*pow(sin((".(float)$rad_longitude."-a.`rad_longitude`)/2),2)))<=".(((int)$this->value['dist'])/7912);
				// Law of Cosines for Spherical Trigonometry; 60*1.1515=69.09; 1.1515 miles in a degree
//				$where.=" AND DEGREES(ACOS(SIN(".(float)$rad_latitude.")*SIN(a.`rad_latitude`)+COS(".(float)$rad_latitude.")*COS(a.`rad_latitude`)*COS(".(float)$rad_longitude."-a.`rad_longitude`)))<=".(int)$this->value['dist']/69.09;
			} else {
	// should not return any result or at least warn the member that the zip code was not found.
			}
		}
		return $myreturn;
	}
}

if (defined('IN_ADMIN')) {
	$accepted_fieldtype['search']['field_zip_distance']='Zip Distance';
}
