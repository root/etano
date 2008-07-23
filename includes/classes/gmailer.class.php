<?php

/***  DOCUMENTATION LAYER

Klenwell Gmail Mailer Class
	extends PHPMailer

Name: GmailMailer
Last Update: May 2007
Author: Tom at klenwell@gmail.com

DESCRIPTION
	An extension of the PHPMailer class that allows it to be used with PHPMailer
	without requiring alteration of the PHPMailer class itself.

	Be sure to change $__PHPMAILER['dirpath'] setting below

METHODS
	MAGIC
	GmailMailer($debug=0)	*php 4 constructor*
	__construct($debug)		*php 5 constructor*
	__destruct()

	PUBLIC
	send_email()
	use_smtp()
	SmtpConnect()
	print_r()

	PRIVATE
	_ini_smtp()
	_set_filename()
	_set_dirpath()

USAGE
	require_once('path/to/gmailer.class.php');
	$Mailer = new GmailMailer($debug=1);
	$Mailer->Username = '';
	$Mailer->Password = '';
	$Mailer->FromName = 'Your Name';
	$Mailer->AddAddress('recipient@gmail.com', 'Recipient Name');
	$Mailer->Subject = 'testing GmailMailer';
	$Mailer->Body = "This is a test of GmailMailer, an extension of PHPMailer for use with Gmail accounts.";
	if ( !$Mailer->send_email() ) trigger_error('drats! It failed', E_USER_WARNING);

NOTES
	the base class's method SmtpConnect is overridden below to enable SSL support.
	This is necessary for usage with SMTP servers that require SSL/TLS
	authentication, like GMAIL.  Modification based on this script:

	http://www.buayacorp.com/archivos/phpmailer-con-gmail/

	note : SSL also requires SSL is enabled with PHP

	You may get the following error, even when the mail is successfully sent :

		Warning: fgets() [function.fgets]: SSL: fatal protocol error in phpmailer\class.smtp.php on line 1024

	more info here: http://bugs.php.net/bug.php?id=23220

______________________________________________________________________________*/


// *** IMPORTANT : set path to directory holding PHPMailer base class here
$__PHPMAILER['dirpath'] = _BASEPATH_.'/includes/classes/';
$__PHPMAILER['basename'] = 'phpmailer.class.php';
require $__PHPMAILER['dirpath'] . $__PHPMAILER['basename'];


// GmailMailer
/*____________________________________________________________________________*/
class GmailMailer extends PHPMailer
{
/* PUBLIC PROPERTIES */
var $DS = DIRECTORY_SEPARATOR;
var $debug = 0;
var $oid = 'GmailMailer';

// Preset to Gmail Settings (these can be reset for use with other SMTPs)
var $Mailer = 'smtp';			// mail, smtp, or sendmail
var $Port = 465;
var $Host = 'ssl://smtp.gmail.com';		// ex: "smtp1.example.com;smtp2.example.com"
var $Username = '';
var $Password = '';

// Other Settings
var $FromName = 'admin';
var $From = 'contact@datemill.com';
var $Sender = '';
var $Priority = 3;
var $WordWrap = 75;
var $language = 'en';

/* PRIVATE PROPERTIES */
var $__filename = '';
var $__dirpath = '';


// php4 constructor
function GmailMailer($debug=0)
{
	$this->__construct($debug);
	register_shutdown_function( array($this, '__destruct') );
}
// END constructor

/* ** MAGIC METHODS ** */
// php5 constructor
function __construct($debug=0)
{
	// default
	$this->debug = $debug;
	$this->_set_filename();
	$this->_set_dirpath();
	if ( $this->debug ) $this->print_d('debugging is active');

	// set language
	global $__PHPMAILER;
	if ( isset($__PHPMAILER['dirpath']) && !empty($this->language) )
	{
		$this->SetLanguage($this->language, $__PHPMAILER['dirpath'] . 'language' . $this->DS);
	}
}
// END constructor

// destructor
function __destruct()
{
}
// END destructor


/* ** PUBLIC METHODS ** */
// method: send email
function send_email()
{
	$is_sent = 0;		// return

	// use smtp
	$this->use_smtp();

	// send
	if ( $this->Send() )
	{
		$is_sent = 1;
	}
	else
	{
		trigger_error('unable to send message: ' . $this->ErrorInfo, E_USER_WARNING);
	}

	return $is_sent;
}
// END method


// method: use smtp
function use_smtp()
{
	// sanity checks
	if ( empty($this->Host) ) { trigger_error('empty SMTP host', E_USER_WARNING); return 0; }
	if ( empty($this->Username) ) { trigger_error('empty SMTP login', E_USER_WARNING); return 0; }
	if ( empty($this->Password) ) { trigger_error('empty SMTP password', E_USER_WARNING); return 0; }

	// set SMTP properties
	$this->Mailer = 'smtp';
	$this->SMTPAuth = 1;

	// for windows: see http://us.php.net/mail
	if ( isset($_ENV['OS']) && strpos($_ENV['OS'], 'Win') !== FALSE ) $this->_ini_smtp();
	return;
}
// END method


// method: SmtpConnect (see note above)
function SmtpConnect()
{
	if($this->smtp == NULL) { $this->smtp = new SMTP(); }

	$this->smtp->do_debug = $this->SMTPDebug;
	$hosts = explode(";", $this->Host);
	$index = 0;
	$connection = ($this->smtp->Connected());

	// Retry while there is no connection
	while($index < count($hosts) && $connection == false)
	{
		// replaced with next code block to support SSL
		/*
		if(strstr($hosts[$index], ":"))
		{
			list($host, $port) = explode(":", $hosts[$index]);
		}
		else
		{
			$host = $hosts[$index];
			$port = $this->Port;
		}
		*/

		// modification (source: http://www.buayacorp.com/archivos/phpmailer-con-gmail/)
		if (preg_match ("# (([a-z] +: /)? [^:]+): (\ d+) #i", $hosts[$index], $match))
		{
			$host = $match[1];
			$port = $match[3];
		}
		else
		{
			$host = $hosts[$index];
			$port = $this->Port;
		}

		// debug
		if ( $this->debug ) $this->print_d("testing with SMTP host: {$host}:{$port}");

		if($this->smtp->Connect($host, $port, $this->Timeout))
		{
			if ($this->Helo != '')
				$this->smtp->Hello($this->Helo);
			else
				$this->smtp->Hello($this->ServerHostname());

			if($this->SMTPAuth)
			{
				if(!$this->smtp->Authenticate($this->Username, $this->Password))
				{
					$this->SetError($this->Lang("authenticate"));
					$this->smtp->Reset();
					$connection = false;
				}
			}
			$connection = true;
		}
		$index++;
	}

	if(!$connection) $this->SetError($this->Lang("connect_host"));

	return $connection;
}
// END method


// print_d
function print_d($message, $color='c33')
{
	$out = "<div style='line-height:1.5em; font-family:monospace; color:$color;'>$message</div>";
	echo $out;
	return;
}
// end method


/* ** PUBLIC METHODS ** */
// method : print_r
function print_r()
{
	$return = htmlspecialchars(print_r($this, 1));
	$return = "<pre>$return</pre>";
	return $return;
}
// end method


/* ** PRIVATE METHODS ** */
// method : _ini_smtp
function _ini_smtp()
{
	ini_set("SMTP", $this->Host);
	ini_set("sendmail_from", $this->From);
}

// end method
function _set_filename() { $this->_filename = basename(__FILE__); }
function _set_dirpath() { $this->_dirpath = dirname(__FILE__) . $this->DS; }

} // end class
/*____________________________________________________________________________*/


?>
