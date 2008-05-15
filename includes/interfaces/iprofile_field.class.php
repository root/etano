<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/interfaces/iprofile_field.class.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/


/*abstract*/ class iprofile_field {
	/**
	 * The $config array holds the field configuration data.
	 */
	var $config=array();

	var $empty_value=array('edit'=>'','display'=>'');

	var $value=null;

	var $is_search=false;

	var $search=null;

	/**
	 *	Sets the raw value which will later be outputed for edit/display/search/insert
	 *
	 *	@param array $all_values (required) Some array containing (amongst other things) the values required by this class.
	 *		$all_values should be a reference to the original array to preserve memory.
	 *	@param boolean $sanitize (optional) Sanitize the input or take it as it is. When setting the value from GPC it should be sanitized, otherwise it can be taken as is.
	 *	@return boolean true
	 */
	function set_value($all_values,$sanitize=true) {
	}

	/**
	 *	Outputs the html code required to edit the received value.
	 *
	 *	@param int $tabindex (optional) The html tabindex attribute for the element.
	 *	@return string html code
	 */
	function edit($tabindex=1) {
		return '';
	}

	/**
	 *	Outputs the value sanitized for DISPLAY. Should only be used by my_profile and the gen_users cron function.
	 *
	 *	@return string the value
	 */
	function display() {
		return '';
	}

	/**
	 *	Returns an instance of a new field which is used in search forms. The field type is based on the 'search_type' config option.
	 *
	 *	@return field object the new field. If 'search_type' is not specified or is invalid, $this should be returned.
	 */
	function search() {
		return null;
	}

	function edit_admin() {
		return '';
	}

	/**
	 *	Returns the part of an sql SELECT required by the field to retrieve the value from db
	 *
	 *	@return string sql part
	 */
	function query_select() {
		return '1';
	}

	/**
	 *	Returns the part of an sql INSERT/UPDATE required to set the value of the field in db
	 *
	 *	@return string sql part
	 */
	function query_set() {
		return '';
	}

	/*abstract*/ public function query_search() {
		return '';
	}

	/**
	 *	Method responsible for client side validation and field behavior
	 */
	function edit_js() {
		return '';
	}

	/**
	 *	Performs server side validation after a field is edited.
	 *
	 *	@return mixed true if validation went ok, false or array('text'=>'error description') if validation did not succeed.
	 */
	function validation_server() {
		return true;
	}

	function get_value($as_array=false) {
		return $this->value;
	}
}
