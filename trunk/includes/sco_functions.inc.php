<?php
/******************************************************************************
File:                       includes/sco_functions.inc.php
$Revision$
Info:   					general purpose functions library
File version:				1.2007073001
Created by:                 Dan Caragea (http://www.sco.ro - dan@sco.ro)
******************************************************************************/

/*
v 1.2006091401
 - Initial release
*/

define('MESSAGE_ERROR',1);
define('MESSAGE_INFO',2);

define('TYPE_INT',1);
define('TYPE_FLOAT',2);
define('TYPE_STRING',3);
define('TYPE_ARRAY_SMALL',4);
define('TYPE_ARRAY_LARGE',9);
define('TYPE_NUM',5);
define('TYPE_TIMESTAMP',6);
define('TYPE_EMAIL',7);
define('TYPE_BOOLEAN',8);

define('FORMAT_ADDSLASH',1);
define('FORMAT_OLD_ADDSLASH',2);
define('FORMAT_STRIPSLASH',4);
define('FORMAT_STRIP_MQ',8);
define('FORMAT_HTML2TEXT',16);
define('FORMAT_TEXT2HTML',32);
define('FORMAT_ONELINE',64);
define('FORMAT_DATE',128);
define('FORMAT_TIME',256);
define('FORMAT_DATETIME',512);
//define('FORMAT_EMAIL',1024);
define('FORMAT_FLOAT',2048);
define('FORMAT_TRIM',4096);
define('FORMAT_NL2BR',8192);
define('FORMAT_HTML2TEXT_FULL',16384);
define('FORMAT_UTF_ENCODE',32768);
define('FORMAT_UTF_DECODE',65536);
define('FORMAT_RUDECODE',131072);
define('FORMAT_RUENCODE',262144);

define('FIELD_TEXTFIELD',2);
define('FIELD_SELECT',3);
define('FIELD_TEXTAREA',4);
define('FIELD_CHECKBOX',9);
define('FIELD_CHECKBOX_LARGE',10);
define('FIELD_FILE',101);						//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
define('FIELD_FK_SELECT',102);
define('FIELD_DATE',103);
define('FIELD_INT',104);
define('FIELD_FLOAT',105);
define('HTML_PIC',106);						//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// 107,108 are reserved

// text sources and destinations: from db or gpc for db/edit/display
define('TEXT_DB2EDIT',200);
define('TEXT_DB2DISPLAY',201);
define('TEXT_GPC2EDIT',202);
define('TEXT_GPC2DISPLAY',203);
define('TEXT_DB2DB',204);

$GLOBALS['__field2type']=array(FIELD_TEXTFIELD=>TYPE_STRING,FIELD_TEXTAREA=>TYPE_STRING,FIELD_SELECT=>TYPE_INT,FIELD_CHECKBOX=>TYPE_ARRAY_SMALL,FIELD_CHECKBOX_LARGE=>TYPE_ARRAY_LARGE,FIELD_DATE=>TYPE_STRING,FIELD_INT=>TYPE_INT,FIELD_FLOAT=>TYPE_FLOAT);
$GLOBALS['__field2format']=array(FIELD_INT=>0,FIELD_FLOAT=>0,FIELD_TEXTFIELD=>(FORMAT_STRIP_MQ | FORMAT_ADDSLASH | FORMAT_ONELINE | FORMAT_TRIM),FIELD_TEXTAREA=>(FORMAT_STRIP_MQ | FORMAT_ADDSLASH | FORMAT_TRIM),FIELD_SELECT=>0,FIELD_CHECKBOX=>(FORMAT_STRIP_MQ | FORMAT_ADDSLASH),FIELD_CHECKBOX_LARGE=>(FORMAT_STRIP_MQ | FORMAT_ADDSLASH | FORMAT_ONELINE),FIELD_DATE=>(FORMAT_STRIP_MQ | FORMAT_ADDSLASH | FORMAT_ONELINE | FORMAT_TRIM), TEXT_DB2EDIT=>(FORMAT_HTML2TEXT_FULL), TEXT_DB2DISPLAY=>(FORMAT_HTML2TEXT_FULL | FORMAT_NL2BR), TEXT_DB2DB=>(FORMAT_ADDSLASH), TEXT_GPC2EDIT=>(FORMAT_STRIP_MQ | FORMAT_HTML2TEXT_FULL), TEXT_GPC2DISPLAY=>(FORMAT_STRIP_MQ | FORMAT_HTML2TEXT_FULL | FORMAT_NL2BR));


function sanitize_and_format_gpc(&$array,$key,$input_type,$format=0,$empty_value='') {
	$myreturn='';
	if (!isset($array[$key])) {
		$myreturn=$empty_value;
	} elseif ($input_type==TYPE_INT && ((!is_array($array[$key]) && ((int)$array[$key])==0) || empty($array[$key]))) {
		$myreturn=$empty_value;
	} elseif ($input_type==TYPE_FLOAT && ((!is_array($array[$key]) && ((float)$array[$key])==0) || empty($array[$key]))) {
		$myreturn=$empty_value;
	} elseif ($input_type==TYPE_STRING && ((!is_array($array[$key]) && ((string)($array[$key]))=='') || empty($array[$key]))) {
		$myreturn=$empty_value;
	} else {
		$myreturn=sanitize_and_format($array[$key],$input_type,$format,$empty_value);
	}
	return $myreturn;
}

function sanitize_and_format($input,$input_type,$format=0,$empty_value=null) {
	if (!is_array($input)) {
		switch ($input_type) {

			case TYPE_INT:
				$input=(int)$input;
				if ($input==0 && $empty_value!=null) {
					$input=$empty_value;
				}
				break;

			case TYPE_FLOAT:
				$input=(float)$input;
				if ($input==0 && $empty_value!=null) {
					$input=$empty_value;
				}
				break;

			case TYPE_STRING:
				$input=(string)$input;
				if ($input=='' && $empty_value!=null) {
					$input=$empty_value;
				}
				break;

			case TYPE_NUM:
				$input=0+$input;
				if ($input==0 && $empty_value!=null) {
					$input=$empty_value;
				}
				break;

			case TYPE_TIMESTAMP:
				$input=(int)$input;
				break;

			case TYPE_EMAIL:
				break;

			case TYPE_BOOLEAN:
				if ($input>=1 || $input===true || strcasecmp($input,'y')===0) {
					$input=true;
				} else {
					$input=false;
				}
				break;

		}
		$format=(int)$format;				// just an extra precaution
		if ($format&FORMAT_RUDECODE) {		// must come first
			$input=rawurldecode($input);
		}
		if ($format&FORMAT_STRIPSLASH) {		// must come before html2text & html2text_full
			$input=stripslashes($input);
		}
		if ($format&FORMAT_STRIP_MQ) {		// must come before html2text & html2text_full
			$input=stripslashes_mq($input);
		}
		if ($format&FORMAT_HTML2TEXT) {
			$input=str_replace(array('<','>'),array('&lt;','&gt;'),$input);
		}
		if ($format&FORMAT_HTML2TEXT_FULL) {
			$input=htmlspecialchars_uni($input);
		}
		if ($format&FORMAT_TEXT2HTML) {
			$input=unhtmlspecialchars($input);
		}
		if ($format&FORMAT_ONELINE) {	// must come before FORMAT_ADDSLASH
			$input=preg_replace("/\r|\n/m",'',$input);
		}
		if ($format&FORMAT_TRIM) {	// must come before FORMAT_ADDSLASH
			$input=trim($input);
		}
		if ($format&FORMAT_ADDSLASH) {	// must come after text2html
			$input=mysql_real_escape_string($input);	// due to this function there must always be a db_connect() before calling sanitize_and_format
		}
		if ($format&FORMAT_OLD_ADDSLASH) {	// must come after text2html
			$input=addslashes($input);
		}
		if ($format&FORMAT_UTF_ENCODE) {
			$input=utf8_encode($input);
		}
		if ($format&FORMAT_UTF_DECODE) {
			$input=utf8_decode($input);
		}
	// assumes the input is a unix timestamp!
		if ($format&FORMAT_DATE) {
//			$input=locale_date($input);
		}
	// assumes the input is a unix timestamp!
		if ($format&FORMAT_TIME) {
//			$input=locale_time($input);
		}
	// assumes the input is a unix timestamp!
		if ($format&FORMAT_DATETIME) {
//			$input=locale_datetime($input);
		}
// 2 decimals with '.' as the decimal separator
		if ($format&FORMAT_FLOAT) {
			$input=number_format($input,2);
		}
		if ($format&FORMAT_NL2BR) {
			$input=nl2br($input);
		}
		if ($format&FORMAT_RUENCODE) {		// must come last
			$input=rawurlencode($input);
		}
	} else {
		if ($input_type==TYPE_ARRAY_SMALL) {
			$input=vector2binvalue($input);
		} elseif ($input_type==TYPE_ARRAY_LARGE) {
			$input=vector2binvalue_str($input);
		} else {
			foreach ($input as $k=>$v) {
				$input[sanitize_and_format($k,$input_type,$format,$empty_value)]=sanitize_and_format($v,$input_type,$format,$empty_value);
			}
		}
	}
	return $input;
}


function unhtmlentities($string) {
   return preg_replace('/&#([0-9][0-9])/e', 'chr(\\1)', $string);
}


function unhtmlspecialchars($value) {
	if (is_array($value)) {
		$myreturn=array();
		while (list($k,$v)=each($value)) {
			$myreturn[unhtmlspecialchars($k)]=unhtmlspecialchars($v);
		}
	} else {
		$myreturn=str_replace('&amp;amp;','&',$value);
		$myreturn=str_replace('&amp;','&',$myreturn);
		$myreturn=str_replace('&lt;','<',$myreturn);
		$myreturn=str_replace('&gt;','>',$myreturn);
		$myreturn=str_replace('&quot;','"',$myreturn);
		$myreturn=str_replace('&#39;',"'",$myreturn);
		$myreturn=str_replace('&#039;',"'",$myreturn);
	}
	return $myreturn;
}


function htmlspecialchars_uni($value) {
	if (is_array($value)) {
		$myreturn=array();
		while (list($k,$v)=each($value)) {
			$myreturn[htmlspecialchars_uni($k)]=htmlspecialchars_uni($v);
		}
	} else {
//		$myreturn=preg_replace("/&(?!#[0-9]+;)/si",'&amp;amp;',$value); // fix & but allow unicode
		$myreturn=preg_replace("/&(?!#[0-9]+;)/si",'&amp;',$value); // fix & but allow unicode
		$myreturn=str_replace('<','&lt;',$myreturn);
		$myreturn=str_replace('>','&gt;',$myreturn);
		$myreturn=str_replace('"','&quot;',$myreturn);
		$myreturn=str_replace("'",'&#039;',$myreturn);
		$myreturn=str_replace('  ',' &nbsp;',$myreturn);
	}
	return $myreturn;
}


function smart_table($array,$table_cols=1,$row_css_class='',$cell_css_classes=array()) {
	$myreturn='';
	$num_elem=count($array);
	if (!empty($num_elem)) {
		$myreturn='<ul class="table_row first '.$row_css_class;
		if ($table_cols>=$num_elem) {
			$myreturn.=' last';
		}
		$myreturn.="\">\n";
		for ($i=1;$i<=$num_elem;++$i) {
			if ($i%$table_cols==1 && $i!=1) {
				$myreturn.="\n</ul>\n";
				$myreturn.='<ul class="table_row '.$row_css_class;
				if ($i+$table_cols>$num_elem) {
					$myreturn.=' last';
				}
				$myreturn.="\">\n";
			}
			$myreturn.="\t<li class=\"";
			if ($i%$table_cols==1) {
				$myreturn.='first';
			}
			if ($i%$table_cols==0 || $i==$num_elem) {
				$myreturn.=' last';
			}
			if (!empty($cell_css_classes[$i-1])) {
				$myreturn.=' '.$cell_css_classes[$i-1];
			}
			$myreturn.='">'.$array[$i-1]."</li>\n";
		}
		$myreturn.="</ul>\n";
	}
	return $myreturn;
}


function gen_pass($length=8) {
	$myreturn='';
	$consonants='BCDFGHJKLMNPQRSTVWXYZbcdfghjkmnpqrstvwxyz';	// I,O,l,o,i removed
	$vocals='AEUaeu';
	for ($i=1;$i<=$length;++$i) {
		$myreturn.=$i%2 ? $vocals{(int)mt_rand(0,5)} : $consonants{(int)mt_rand(0,40)};
	}
	return $myreturn;
}


function db_connect($dbhost,$dbuser,$dbpass,$dbname='') {
	$myreturn=false;
	if ((float)(phpversion())>=4.3) {
		if (!($myreturn=@mysql_connect($dbhost,$dbuser,$dbpass,MYSQL_CLIENT_COMPRESS))) {trigger_error(mysql_error(),E_USER_ERROR);}
	} else {
		if (!($myreturn=@mysql_connect($dbhost,$dbuser,$dbpass))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	if (!empty($dbname) && $myreturn) {
		if (!mysql_select_db($dbname,$myreturn)) {
			$myreturn=false;
		}
	}
	return $myreturn;
}


function interval2options($low_value,$high_value,$selected_value='',$exclusion_vector=array(),$increment=1,$direction=1) {
	$myreturn='';
	if ($direction==1) {
		for ($i=$low_value;$i<=$high_value;$i+=$increment) {
			if (!in_array($i,$exclusion_vector)) {
				$myreturn.="<option value=\"$i\"";
				if ($i==$selected_value) {
					$myreturn.=" selected=\"selected\"";
				}
				$myreturn.=">$i</option>\n";
			}
		}
	} elseif ($direction==2) {
		for ($i=$high_value;$i>$low_value;$i-=$increment) {
			$myreturn.="<option value=\"$i\"";
			if ($i==$selected_value) {
				$myreturn.=" selected=\"selected\"";
			}
			$myreturn.=">$i</option>\n";
		}
	}
	return $myreturn;
}


function dbtable2options($table,$key_field,$value_field,$order_field='',$selected_id='',$where='') {
	$myreturn='';
	$query="SELECT $key_field,$value_field FROM $table";
	if (!empty($where)) {
		$query.=" WHERE $where";
	}
	if (!empty($order_field)) {
		$query.=" ORDER BY $order_field";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_row($res)) {
		$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
		$myreturn.='<option value="'.$rsrow[0].'"';
		if ((!is_array($selected_id) && $rsrow[0]==$selected_id) || (is_array($selected_id) && in_array($rsrow[0],$selected_id))) {
			$myreturn.=' selected="selected"';
		}
		$myreturn.='>'.$rsrow[1]."</option>\n";
	}
	return $myreturn;
}


function db_key2value($table,$key_field,$value_field,$key_value,$null_value='') {
	$myreturn=$null_value;
	$query="SELECT $value_field FROM $table WHERE $key_field='$key_value'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}


function vector2options($show_vector,$selected_map_val='',$exclusion_vector=array()) {
	$myreturn='';
	while (list($k,$v)=each($show_vector)) {
		if (!in_array($k,$exclusion_vector)) {
			$myreturn.='<option value="'.sanitize_and_format($k,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]).'"';
			if ((!is_array($selected_map_val) && $k==$selected_map_val) || (is_array($selected_map_val) && in_array($k,$selected_map_val))) {
				$myreturn.=' selected="selected"';
			}
//			$myreturn.='>'.sanitize_and_format($v,TYPE_STRING,$GLOBALS['__field2format'][TEXT_GPC2EDIT])."</option>\n";
			$myreturn.=">$v</option>\n";
		}
	}
	return $myreturn;
}


function vector2checkboxes($show_vector,$excluded_keys_vector,$checkname,$binvalue,$table_cols=1,$showlabel=true,$pass2check='') {
	$myreturn='';
	for ($i=0;isset($excluded_keys_vector[$i]);++$i) {
		unset($show_vector[$excluded_keys_vector[$i]]);
	}
	$num_elem=count($show_vector);
	if (!empty($num_elem)) {
		$myreturn.='<ul class="table_row row_'.$checkname.' first';
		if ($table_cols>=$num_elem) {
			$myreturn.=' last';
		}
		$myreturn.="\">\n";
		$i=1;
		while (list($k,$v)=each($show_vector)) {
			if ($i%$table_cols==1 && $i!=1) {
				$myreturn.="\n</ul>\n";
				$myreturn.='<ul class="table_row row_'.$checkname;
				if ($i+$table_cols>$num_elem) {
					$myreturn.=' last';
				}
				$myreturn.="\">\n";
			}
			$myreturn.="\t<li class=\"";
			if ($i%$table_cols==1) {
				$myreturn.='first';
			}
			if ($i%$table_cols==0 || $i==$num_elem) {
				$myreturn.=' last';
			}
			$myreturn.='"><input type="checkbox" id="'.$checkname.'_'.$k.'" name="'.$checkname.'['.$k.']"';
			if (isset($binvalue) && ($binvalue>0) && (($binvalue>>$k)%2)) {
				$myreturn.=' checked="checked"';
			}
			$myreturn.=' value="1" '.$pass2check.' />';
			if ($showlabel) {
				$myreturn.='<label for="'.$checkname.'_'.$k.'">'.$v.'</label>';
			}
			$myreturn.="</li>\n";
			++$i;
		}
		$myreturn.="</ul>\n";
	}
	return $myreturn;
}


function vector2checkboxes_str($show_vector,$excluded_keys_vector,$checkname,$binvalue,$table_cols=1,$showlabel=true,$pass2check='') {
	$myreturn='';
	if (is_string($binvalue)) {
		$binvalue=binvalue2index_str($binvalue);	// now it is an array of indexes in $show_vector
	}
	for ($i=0;isset($excluded_keys_vector[$i]);++$i) {
		unset($show_vector[$excluded_keys_vector[$i]]);
	}
	$num_elem=count($show_vector);
	if (!empty($num_elem)) {
		$myreturn.='<ul class="table_row row_'.$checkname.' first';
		if ($table_cols>=$num_elem) {
			$myreturn.=' last';
		}
		$myreturn.="\">\n";
		$i=1;
		while (list($k,$v)=each($show_vector)) {
			if ($i%$table_cols==1 && $i!=1) {
				$myreturn.="\n</ul>\n";
				$myreturn.='<ul class="table_row row_'.$checkname;
				if ($i+$table_cols>count($show_vector)) {
					$myreturn.=' last';
				}
				$myreturn.="\">\n";
			}
			$myreturn.="\t<li class=\"";
			if ($i%$table_cols==1) {
				$myreturn.='first';
			}
			if ($i%$table_cols==0 || $i==$num_elem) {
				$myreturn.=' last';
			}
			$myreturn.='"><input type="checkbox" id="'.$checkname.'_'.$k.'" name="'.$checkname.'[]"';
			if (in_array($k,$binvalue)) {
				$myreturn.=' checked="checked"';
			}
			$myreturn.=' value="'.$k.'" '.$pass2check.' />';
			if ($showlabel) {
				$myreturn.='<label for="'.$checkname.'_'.$k.'">'.$v.'</label>';
			}
			$myreturn.="</li>\n";
			++$i;
		}
		$myreturn.="</ul>\n";
	}
	return $myreturn;
}


function vector2radios($show_vector,$radioname,$selected_map_val='',$exclusion_vector=array(),$pass2ul='') {
	$myreturn='<ul '.$pass2ul.'>';
	foreach ($show_vector as $k=>$v) {
		if (!in_array($k,$exclusion_vector)) {
			$myreturn.='<li><input type="radio" name="'.$radioname.'" id="'.$radioname.'_'.$k.'" value="'.$k.'"';
			if ($k==$selected_map_val) {
				$myreturn.=' checked="checked"';
			}
			$myreturn.=' /><label for="'.$radioname.'_'.$k.'">'.$v."</label></li>\n";
		}
	}
	$myreturn.='</ul>';
	return $myreturn;
}


function vector2binvalue($myarray) {
	$myreturn=0;
	while (list($k,$v)=each($myarray)) {
		if (((int)$k)==$k) {
			$myreturn+=(1<<$k);
		}
	}
	return $myreturn;
}


function binvalue2index($binvalue) {
	$myarray=array();
	$i=0;
	while ($binvalue>0) {
		if (((int)$binvalue) & 1) {
			$myarray[]=$i;
		}
		$binvalue>>=1;
		++$i;
	}
	return $myarray;
}


function vector2string($myarray,$binvalue) {
	$myreturn='';
	while (list($k,$v)=each($myarray)) {
		if (isset($binvalue) && ($binvalue>0) && ((((int)$binvalue)>>$k)%2)) {
			$myreturn.=$v.', ';
		}
	}
	$myreturn=substr($myreturn,0,-2);
	return $myreturn;
}


function vector2binvalue_str(&$myarray) {
	$myreturn='';
	if (!empty($myarray)) {
		$myreturn='|'.join('|',$myarray).'|';
	}
	return $myreturn;
}


function binvalue2index_str(&$binvalue) {
	$myreturn=array();
	if (!empty($binvalue)) {
		$myreturn=explode('|',substr($binvalue,1,-1));
	}
	return $myreturn;
}


function vector2string_str(&$myarray,$binvalue) {
	$myreturn='';
	if (is_string($binvalue)) {
		$binvalue=binvalue2index_str($binvalue);
	}
	for ($i=0;isset($binvalue[$i]);++$i) {
		if (isset($myarray[$binvalue[$i]])) {
			$myreturn.=$myarray[$binvalue[$i]].', ';
		}
	}
	$myreturn=substr($myreturn,0,-2);
	return $myreturn;
}


function vector_del_keys($myarray,$keys) {
	$myreturn=array();
	while (list($k,$v)=each($myarray)) {
		if (!in_array($k,$keys)) {
			$myreturn[$k]=$v;
		}
	}
	return $myreturn;
}


function vector_del_empty_vals($myarray) {
	$myreturn=array();
	while (list($k,$v)=each($myarray)) {
		if (!empty($v)) {
			$myreturn[$k]=$v;
		}
	}
	return $myreturn;
}


// strip slashes only if mq added them
function stripslashes_mq($value) {
	if (is_array($value)) {
		$myreturn=array();
		while (list($k,$v)=each($value)) {
			$myreturn[stripslashes_mq($k)]=stripslashes_mq($v);
		}
	} else {
		if(get_magic_quotes_gpc()==0) {
			$myreturn=$value;
		} else {
			$myreturn=stripslashes($value);
		}
	}
	return $myreturn;
}


// just strip slashes, we don't care about mq here
function stripslashes_smart($value) {
	if (is_array($value)) {
		$myreturn=array();
		while (list($k,$v)=each($value)) {
			$myreturn[stripslashes_smart($k)]=stripslashes_smart($v);
		}
	} else {
		$myreturn=stripslashes($value);
	}
	return $myreturn;
}


// add slashes only if mq didn't already add them
function addslashes_mq($value) {
	if (is_array($value)) {
		$myreturn=array();
		while (list($k,$v)=each($value)) {
			$myreturn[addslashes_mq($k)]=addslashes_mq($v);
		}
	} else {
		if(get_magic_quotes_gpc() == 0) {
			$myreturn=addslashes($value);
		} else {
			$myreturn=$value;
		}
	}
	return $myreturn;
}


// just add slashes, we don't care about mq here
function addslashes_smart($value) {
	if (is_array($value)) {
		$myreturn=array();
		while (list($k,$v)=each($value)) {
			$myreturn[addslashes_smart($k)]=addslashes_smart($v);
		}
	} else {
		$myreturn=addslashes($value);
	}
	return $myreturn;
}


function upload_file($destdir,$actual_field_name,$desired_filename='',$required=false) {
	$error=false;
	$filename='';
	$message='';
	if (isset($_FILES[$actual_field_name]['tmp_name']) && is_uploaded_file($_FILES[$actual_field_name]['tmp_name'])) {
		$filename=addslashes_mq($_FILES[$actual_field_name]['name']);
		$ext=strtolower(substr(strrchr($_FILES[$actual_field_name]['name'],"."),1));
		if ($_FILES[$actual_field_name]['size']==0) {
			$error=true;
			$GLOBALS['topass']['message']['type']=MESSAGE_ERROR;
			$GLOBALS['topass']['message']['text']='File upload error';
		} else {
			if (!empty($desired_filename)) {
				$filename=$desired_filename.'.'.$ext;
			}
			if (move_uploaded_file($_FILES[$actual_field_name]['tmp_name'],$destdir.'/'.$filename)) {
				@chmod($destdir.'/'.$filename,0644);
			} else {
				$error=true;
				$GLOBALS['topass']['message']['type']=MESSAGE_ERROR;
				$GLOBALS['topass']['message']['text']='Cannot move file to the destination directory.';
			}
		}
	} elseif ($required) {
		$error=true;
		$GLOBALS['topass']['message']['type']=MESSAGE_ERROR;
		$GLOBALS['topass']['message']['text']='File is required';
	}
	if ($error) {
		$myreturn=false;
	} else {
		$myreturn=$filename;
	}
	return $myreturn;
}


function redirect2page($pagename,$topass=array(),$qstring='',$full_url=false) {
	if (!empty($pagename)) {
		if (!$full_url) {
			$redirect=_BASEURL_.'/'.$pagename;
			$separator='?';
			if (defined('SID') && SID!='') {
				$redirect.=$separator.SID;
				$separator='&';
			}
			if (!empty($qstring)) {
				$redirect.=$separator.$qstring;
				$separator='&';
			}
		} else {
			$redirect=$pagename;
		}
		if (!empty($topass)) {
			$_SESSION['topass']=$topass;
		}
		header('Status: 303 See Other',true);
		header('Location: '.$redirect,true);
	} else {
		trigger_error('No page specified for redirect',E_USER_ERROR);
	}
	exit;
}


function unix2dos($mystring) {
	$mystring=preg_replace("/\r/m",'',$mystring);
	$mystring=preg_replace("/\n/m","\r\n",$mystring);
	return $mystring;
}


function send_email($from,$to,$subject,$message,$html=false,$attachments=array(),$bcc='') {
	$separator='Next.Part.331925W0RdH3R3'.time();
	$att_separator='NextPart.is_a_file9817298743'.time();
	$headers="From: $from\n";
	$headers.="MIME-Version: 1.0\n";
	if (!empty($bcc)) {
		$headers.="Bcc: $bcc\n";
	}
	$text_header="Content-Type: text/plain; charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit\n\n";
	$html_header="Content-Type: text/html; charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit\n\n";
	$html_message=$message;
	$text_message=$message;
	$text_message=str_replace('&nbsp;',' ',$text_message);
	$text_message=trim(strip_tags(stripslashes($text_message)));
	// Bring down number of empty lines to 2 max
	$text_message=preg_replace("/\n\s+\n/","\n",$text_message);
	$text_message=preg_replace("/\n{3,}/", "\n\n",$text_message);
	$text_message=wordwrap($text_message,72);
	$message="\n\n--$separator\n".$text_header.$text_message;
	if ($html) {
		$message.="\n\n--$separator\n".$html_header.$html_message;
	}
	$message.="\n\n--$separator--\n";

	if (!empty($attachments)) {
		$headers.="Content-Type: multipart/mixed; boundary=\"$att_separator\";\n";
		$message="\n\n--$att_separator\nContent-Type: multipart/alternative; boundary=\"$separator\";\n".$message;
		while (list(,$file)=each($attachments)) {
			$message.="\n\n--$att_separator\n";
			$message.="Content-Type: application/octet-stream; name=\"".basename($file)."\"\n";
			$message.="Content-Transfer-Encoding: base64\n";
			$message.='Content-Disposition: attachment; filename="'.basename($file)."\"\n\n";
			$message.=wordwrap(base64_encode(fread(fopen($file,'rb'),filesize($file))),72,"\n",1);
		}
		$message.="\n\n--$att_separator--\n";
	} else {
		$headers.="Content-Type: multipart/alternative; boundary=\"$separator\";\n";
	}
	$message='This is a multi-part message in MIME format.'.$message;
	if (isset($_SERVER['WINDIR']) || isset($_SERVER['windir']) || isset($_ENV['WINDIR']) || isset($_ENV['windir'])) {
		$message=unix2dos($message);
	}
	$headers=unix2dos($headers);
	ini_set('sendmail_from',$from);
	$sentok=@mail($to,$subject,$message,$headers,'-f'.$from);
	return $sentok;
}


function general_error($errlevel,$message,$file='unset',$line='unset') {
	$output=$message."\n<br />";
	if (defined('_DEBUG_') && _DEBUG_!=0) {
		if (!empty($GLOBALS['query'])) {
			$output.='Last query run: '.$GLOBALS['query']."\n<br />";
		}
		if (_DEBUG_==1) {
			$output.="Line: $line\n<br />File: $file\n<br />";
		} elseif (_DEBUG_==2) {
			ob_start();
			echo '<pre>';
			print_r(debug_backtrace());
			echo '</pre>';
			$output.=ob_get_contents();
			ob_end_clean();
		}
	}
	require_once _BASEPATH_.'/includes/classes/log_error.class.php';
	new log_error('',$output);
	if ($errlevel==E_USER_ERROR || (defined('_DEBUG_') && _DEBUG_!=0)) {
		exit;
	}
}


function array2qs($myarray,$excluded_keys=array()) {
	$myreturn="";
	while (list($k,$v)=each($myarray)) {
		if (!in_array($k,$excluded_keys)) {
			if (is_array($v)) {
				while (list($subk,$subv)=each($v)) {
					$myreturn.=$k.'%5B'.$subk.'%5D'.'='.urlencode($subv).'&';
				}
			} else {
				$myreturn.=$k.'='.urlencode($v).'&';
			}
		}
	}
	$myreturn=substr($myreturn,0,-1);
	return $myreturn;
}


function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (int)$sec+(int)($usec*100000);
}


function create_pager2($totalrows,$offset,$results,$lang_strings=array()) {
	mt_srand(make_seed());
	$radius=4;
	$phpself=explode('?',$_SERVER['REQUEST_URI']);
	$phpself=$phpself[0];
	global $accepted_results_per_page;
	$params=array();
	$params=array_merge($_GET,$_POST);
	unset($params['o'],$params['r'],$params['PHPSESSID']);
	$qs=array2qs($params,array('PHPSESSID'));
	$myrand=mt_rand(1000,2000);
	if (empty($results)) {
		$results=10;
	}
	$total_pages=ceil($totalrows/$results);
	$myreturn="<form id=\"pagerform$myrand\" action=\"$phpself\" method=\"get\">\n";
	$myreturn.="<ul class=\"pager\">\n";
	$myreturn.='<li class="text">'.(isset($lang_strings['page']) ? $lang_strings['page'] : '').'</li>';
	$myreturn.='<li class="goto_first">';
	$myreturn.='<a href="'.$phpself.'?o=0&amp;r='.$results;
	if (!empty($qs)) {
		$myreturn.='&amp;'.$qs;
	}
	$myreturn.='" title="'.(isset($lang_strings['goto_first']) ? $lang_strings['goto_first'] : 'Go to first page').'">&lt;&lt;</a></li>';
	if ($offset>0) {
		$myreturn.='<li class="goto_previous">';
		$myreturn.='<a href="'.$phpself.'?o='.(($offset-$results>0) ? $offset-$results : 0).'&amp;r='.$results;
		if (!empty($qs)) {
			$myreturn.='&amp;'.$qs;
		}
		$myreturn.='" title="'.(isset($lang_strings['goto_prev']) ? $lang_strings['goto_prev'] : 'Go to previous page').'">&lt;</a></li>';
	}
	$dotsbefore=false;
	$dotsafter=false;
	for ($i=1;$i<=$total_pages;++$i) {
		if (((($i-1)*$results)<=$offset) && ($offset<$i*$results)) {
			$myreturn.='<li class="current_page';
			if ($i==1) {
				$myreturn.=' first';
			}
			$myreturn.='">'.$i.'</li>';
		} elseif (($i-1+$radius)*$results<$offset) {
			if (!$dotsbefore) {
				$myreturn.='<li class="dots';
				if ($i==1) {
					$myreturn.=' first';
				}
				$myreturn.="\">...</li>\n";
				$dotsbefore=true;
			}
		} elseif (($i-1-$radius)*$results>$offset) {
			if (!$dotsafter) {
				$myreturn.="<li class=\"dots\">...</li>\n";
				$dotsafter=true;
			}
		} else {
			$myreturn.='<li';
			if ($i==1) {
				$myreturn.=' class="first"';
			}
			$myreturn.='><a href="'.$phpself.'?o='.(($i-1)*$results).'&amp;r='.$results;
			if (!empty($qs)) {
				$myreturn.='&amp;'.$qs;
			}
			$myreturn.='">'.$i."</a></li>\n";
		}
	}
	if ($offset+$results<$totalrows) {
		$myreturn.='<li class="goto_next"><a href="'.$phpself.'?o='.($offset+$results).'&amp;r='.$results;
		if (!empty($qs)) {
			$myreturn.='&amp;'.$qs;
		}
		$myreturn.='" title="'.(isset($lang_strings['goto_next']) ? $lang_strings['goto_next'] : 'Go to next page')."\">&gt;</a></li>\n";
	}
	$myreturn.='<li class="goto_last"><a href="'.$phpself.'?o='.(($total_pages-1)*$results).'&amp;r='.$results;
	if (!empty($qs)) {
		$myreturn.='&amp;'.$qs;
	}
	$myreturn.='" title="'.(isset($lang_strings['goto_last']) ? $lang_strings['goto_last'] : 'Go to last page')."\">&gt;&gt;</a></li>\n";
	$myreturn.="<li class=\"rpp\">\n";
	$myreturn.="\t<input type=\"hidden\" name=\"o\" value=\"$offset\" />\n";
	while (list($k,$v)=each($params)) {
		if (is_array($v)) {
			while (list($subk,$subv)=each($v)) {
				$myreturn.="\t<input type=\"hidden\" name=\"{$k}[$subk]\" value=\"$subv\" />\n";
			}
		} else {
			$myreturn.="\t<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n";
		}
	}
	$myreturn.="\t".(isset($lang_strings['rpp']) ? $lang_strings['rpp'] : '')."<select name=\"r\" onchange=\"document.getElementById('pagerform$myrand').submit()\">\n";
	$myreturn.=vector2options($accepted_results_per_page,$results);
	$myreturn.="\t</select>\n";
	$myreturn.="</li>\n";
	$myreturn.="<li class=\"last\"></li>\n";
	$myreturn.="</ul>\n";
	$myreturn.="</form>\n";
	return $myreturn;
}


// $topass must be already sanitized
function post2page($page,$topass=array(),$full_url=false) {
	if (!$full_url) {
		$page=_BASEURL_.'/'.$page;
	}
	$myreturn="<html>\n<body>\n<form id=\"p2pform\" action=\"$page\" method=\"post\">\n";
	foreach ($topass as $k=>$v) {
		if (!is_array($v)) {
			$myreturn.="<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n";
		} else {
			foreach ($v as $vk=>$vv) {
				$myreturn.="<input type=\"hidden\" name=\"{$k}[{$vk}]\" value=\"{$vv}\" />\n";
			}
		}
	}
	$myreturn.="</form>\n";
	$myreturn.="<script type=\"text/javascript\">\n";
	$myreturn.="document.getElementById('p2pform').submit();\n";
	$myreturn.="</script>\n</body>\n</html>";
	echo $myreturn;
	exit;
}
