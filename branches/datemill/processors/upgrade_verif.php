<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/upgrade_verif.php
$Revision: 400 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='info.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['upid']=isset($_POST['upid']) ? (int)$_POST['upid'] : 0;

	if (empty($input['upid'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No site selected for authentication.';
		$nextpage='upgrade.php';
	}

	if (!$error) {
		$query="SELECT `upid`,`email`,`name`,`old_url`,`key` FROM `dsb2_upgrades` WHERE `upid`=".$input['upid'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$input=mysql_fetch_assoc($res);
			$ch=curl_init($input['old_url'].'/dm_'.$input['key'].'.html');
			curl_setopt($ch,CURLOPT_HEADER, true);
			curl_setopt($ch,CURLOPT_NOBODY, true);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$return=curl_exec($ch);
			if (!curl_errno($ch) && strpos($return,'HTTP/1.1 200 OK')===0) {
				$query="UPDATE `dsb2_upgrades` SET `is_verified`=1 WHERE `upid`=".$input['upid'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Your site has been authenticated and your request received. We will contact you shortly for details on how to upgrade your site.';
				$config=get_site_option(array('mail_from','mail_crlf'),'core');
				require_once _BASEPATH_.'/includes/classes/phpmailer.class.php';
				$mail=new PHPMailer();
				$mail->IsHTML(false);
				$mail->From=$config['mail_from'];
				$mail->Sender=$config['mail_from'];
				$mail->FromName=$input['name'];
				if ($config['mail_crlf']) {
					$mail->LE="\r\n";
				} else {
					$mail->LE="\n";
				}
				$mail->IsMail();
				$mail->AddAddress($config['mail_from']);
				$mail->Subject='DSB to Etano upgrade request';
				$mail->Body=var_export($input,true);
				@$mail->Send();
			} else {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='The verification file was not found on your site. Maybe you haven\'t uploaded it yet?';
				$nextpage='upgrade_verif.php';
				$qs.=$qs_sep.'upid='.$input['upid'];
			}
			curl_close($ch);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter your DSB purchase details first.';
		}
	}
	if ($error) {
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
