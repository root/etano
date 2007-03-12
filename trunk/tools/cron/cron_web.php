<?php
/******************************************************************************
newdsb
===============================================================================
File:                       tools/crons/cron_web.php
$Revision: 36 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
set_time_limit(0);

if (isset($_SERVER['REMOTE_ADDR'])) {
	$lk=sanitize_and_format_gpc($_GET,'lk',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
	if ($lk==md5(_LICENSE_KEY_)) {
		$day=(int)date('d');
		$weekday=(int)date('w');	//0 for sunday
		$hour=(int)date('H');
		$minute=(int)date('i');
		$minute=$minute-$minute%5;	// allow 4 minutes and 59 seconds run delay

		$jobs=array();
		// every 5 minutes
		if ($minute/5==(int)($minute/5)) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/5')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/5/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// every 10 minutes
		if ($minute/10==(int)($minute/10)) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/10')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/10/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// every 15 minutes
		if ($minute/15==(int)($minute/15)) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/15')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/15/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// every 30 minutes
		if ($minute/30==(int)($minute/30)) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/30')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/30/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// every 1 hour
		if ($minute/60==(int)($minute/60)) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/60')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/60/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// every 2 hours
		if ($hour/2==(int)($hour/2)) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/120')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/120/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// every day
		if ($hour==23 && $minute==55) {
			if ($dh=opendir(dirname(__FILE__).'/jobs/1440')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/1440/'.$file);
					}
				}
				closedir($dh);
			}
		}

		if ($weekday==0 && $hour==23 && $minute==55) {	// once per week: sunday 11:55 PM
			if ($dh=opendir(dirname(__FILE__).'/jobs/w')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/w/'.$file);
					}
				}
				closedir($dh);
			}
		}

		if ($day==1 && $hour==0 && $minute==5) {	// once per month: 1st at 12:05 AM
			if ($dh=opendir(dirname(__FILE__).'/jobs/m')) {
				while (($file=readdir($dh))!==false) {
					if ($file{0}!='.' && substr($file,-3)=='php') {
						include_once(dirname(__FILE__).'/jobs/m/'.$file);
					}
				}
				closedir($dh);
			}
		}

		// execute all functions from $jobs, whatever they may be
		if (!empty($jobs)) {
			$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');
			for ($i=0;isset($jobs[$i]);++$i) {
				if (function_exists($jobs[$i])) {
					$start_time=time();
					$jobs[$i]();
					echo $jobs[$i].': '.(time()-$start_time).' seconds<br>';
					ob_flush();
					flush();
				}
			}
		}
		echo count($jobs).' jobs run.';
	}
}
?>