<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/location.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

/* format of output:
1. first line - the actual field (in the field_12_ format)
2. second line - which one of the 4 location fields to show separated by pipe (|). Only the termination (country|state|city|zip)
3. third line - the field to update with the options below. Only the termination
4. all other lines - the options for the field above in the <option_id>|<option_text> format
*/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);

$output='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$field=$_POST['field'];
	$val=(int)$_POST['val'];
	$actual_field='';
	if (strpos($field,'country')) {
		$actual_field=substr($field,0,-7);
		$field='country';
	} elseif (strpos($field,'state')) {
		$actual_field=substr($field,0,-5);
		$field='state';
	}
	$output.=$actual_field."\n";												//1
	if ($field=='country') {
		$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`=$val";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($prefered_input,$num_states)=mysql_fetch_row($res);
			if ($prefered_input=='s') {
				$query="SELECT `state_id`,`state` FROM `{$dbtable_prefix}loc_states` WHERE `fk_country_id`=$val";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$output.="{$actual_field}country|{$actual_field}state\n";	//2
					$output.="state\n";											//3
					$output.='0|'.$GLOBALS['_lang'][159];
					while ($rsrow=mysql_fetch_row($res)) {
						$rsrow[1]=sanitize_and_format($rsrow[1],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
						$output.="\n".$rsrow[0].'|'.$rsrow[1];
					}
				} else {
					$output.="{$actual_field}country\n";						//2
				}
			} else {
				$output.="{$actual_field}country|{$actual_field}zip\n";			//2
			}
		}
	} elseif ($field=='state') {
		$query="SELECT `city_id`,`city` FROM `{$dbtable_prefix}loc_cities` WHERE `fk_state_id`=$val";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output.="{$actual_field}country|{$actual_field}state|{$actual_field}city\n";	//2
			$output.="city\n";																//3
			$output.='0|'.$GLOBALS['_lang'][159];
			while ($rsrow=mysql_fetch_row($res)) {
				$output.="\n".$rsrow[0].'|'.$rsrow[1];
			}
		} else {
			$output.="{$actual_field}country|{$actual_field}state\n";	//2
		}
	}
}
echo $output;
