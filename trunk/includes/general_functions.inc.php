<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/general_functions.inc.php
$Revision: 75 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

function get_site_option($option,$module_code) {
	$myreturn=0;
	global $dbtable_prefix;
	$query="SELECT `config_option`,`config_value` FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code`='$module_code'";
	if (is_array($option)) {
		if (!empty($option)) {
			$query.=" AND `config_option` IN ('".join("','",$option)."')";
		}
	} else {
		$query.=" AND `config_option`='$option'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=array();
		while ($rsrow=mysql_fetch_row($res)) {
			$myreturn[$rsrow[0]]=$rsrow[1];
		}
		if (is_string($option)) {
			$myreturn=array_shift($myreturn);
		}
	}
	return $myreturn;
}


function get_module_codes_by_type($module_type) {
	$myreturn=array();
	global $dbtable_prefix;
	$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`='$module_type'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn[]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


// This function does NOT convert html to text.
// Make sure that the string is clean before calling this function
function bbcode2html($str) {
	$from=array('~\[url=(http://[^<">\(\)\[\]]*?)\](.*?)\[/url\]~','~\[b\](.*?)\[/b\]~','~\[u\](.*?)\[/u\]~','~\[quote\](.*?)\[/quote\]~','~\[img=(http://[^<">\(\)\[\]]*?)\]~');
	$to=array('<a target="_blank" rel="nofollow" href="$1">$2</a>','<strong>$1</strong>','<span class="underline">$1</span>','<blockquote>$1</blockquote>','<img src="$1" />');
	return preg_replace($from,$to,$str);
}


// wrapper for the create_pager2() function
function pager($totalrows,$offset,$results) {
	$lang_strings['page']='Pages:';					// translate this
	$lang_strings['rpp']='Results to show:';		// translate this
	$lang_strings['goto_first']='Go to first page';		// translate this
	$lang_strings['goto_last']='Go to last page';		// translate this
	$lang_strings['goto_next']='Go to next page';		// translate this
	$lang_strings['goto_prev']='Go to previous page';		// translate this
	return create_pager2($totalrows,$offset,$results,$lang_strings);
}


function get_my_skin() {
	if (isset($_SESSION['user']['skin']) && !empty($_SESSION['user']['skin']) && is_dir(_BASEPATH_.'/skins_site/'.$_SESSION['user']['skin'])) {
		$myreturn=$_SESSION['user']['skin'];
	} elseif (isset($_COOKIE['sco_app']['skin']) && preg_match('/^\w+$/',$_COOKIE['sco_app']['skin']) && !empty($_COOKIE['sco_app']['skin']) && is_dir(_BASEPATH_.'/skins_site/'.$_COOKIE['sco_app']['skin'])) {
		$myreturn=$_COOKIE['sco_app']['skin'];
		// save the option in less expensive places
		$_SESSION['user']['skin']=$myreturn;
	} else {
		$myreturn=get_default_skin_dir();
		// save the option in less expensive places
		$_COOKIE['sco_app']['skin']=$myreturn;
		$_SESSION['user']['skin']=$myreturn;
	}
	return $myreturn;
}


function get_default_skin_dir() {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT a.`config_value` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b,`{$dbtable_prefix}site_options3` c WHERE a.`config_option`='skin_dir' AND a.`fk_module_code`=b.`module_code` AND b.`module_code`=c.`fk_module_code` AND b.`module_type`='".MODULE_SKIN."' AND c.`config_option`='is_default' AND c.`config_value`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	if (empty($myreturn)) {
		$myreturn='basic';
	}
	return $myreturn;
}


function get_default_skin_code() {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT a.`module_code` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_code`=b.`fk_module_code` AND a.`module_type`='".MODULE_SKIN."' AND b.`config_option`='is_default' AND b.`config_value`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	if (empty($myreturn)) {
		$myreturn='basic';
	}
	return $myreturn;
}


function send_template_email($to,$subject,$template,$skin,$output=array()) {
	$myreturn=true;
	$tpl=new phemplate(_BASEPATH_.'/skins_site/'.$skin.'/emails/','remove_nonjs');
	$tpl->set_file('temp',$template);
	$tpl->set_var('output',$output);
	$message_body=$tpl->process('temp','temp',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_FINISH);

	$config=get_site_option(array('mail_from'),'core');
	require_once _BASEPATH_.'/includes/classes/phpmailer.class.php';
	$mail=new PHPMailer();
	$mail->IsHTML(true);
	$mail->From=$config['mail_from'];
	$mail->Sender=$config['mail_from'];
	$mail->FromName=_SITENAME_;
	$mail->LE="\r\n";
	$mail->IsMail();
	$mail->AddAddress($to);
	$mail->Subject=$subject;
	$mail->Body=$message_body;
	if (!$mail->Send()) {
		$myreturn=false;
		$GLOBALS['topass']['message']['type']=MESSAGE_ERROR;
		$GLOBALS['topass']['message']['text']=$mail->ErrorInfo;
	}
	return $myreturn;
}
