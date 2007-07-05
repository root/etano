<?php
/******************************************************************************
Etano
===============================================================================
File:                       tools/crons/cron_web.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
set_error_handler('general_error');
set_time_limit(0);

if (!isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['argc'])) {
	$day=(int)date('d');
	$weekday=(int)date('w');	//0 for sunday
	$hour=(int)date('H');
	$minute=(int)date('i');
	$minute=$minute-$minute%5;	// allow 4 minutes and 59 seconds run delay

//print "time: $hour:$minute<br>";

	$jobs=array();
	// every 5 minutes
	if ($minute%5==0) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/5')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/5/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// every 10 minutes
	if ($minute%10==0) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/10')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/10/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// every 15 minutes
	if ($minute%15==0) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/15')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/15/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// every 30 minutes
	if ($minute%30==0) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/30')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/30/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// every 1 hour
	if ($minute==55) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/60')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/60/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// every 2 hours
	if ($hour%2==0 && $minute==55) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/120')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/120/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// every day
	if ($hour==23 && $minute==55) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/d')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/d/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// once per week: sunday 11:55 PM
	if ($weekday==0 && $hour==23 && $minute==55) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/w')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/w/'.$file;
				}
			}
			closedir($dh);
		}
	}

	// once per month: on 1st at 12:05 AM
	if ($day==1 && $hour==0 && $minute==5) {
		if ($dh=opendir(dirname(__FILE__).'/jobs/m')) {
			while (($file=readdir($dh))!==false) {
				if ($file{0}!='.' && substr($file,-3)=='php') {
					include_once dirname(__FILE__).'/jobs/m/'.$file;
				}
			}
			closedir($dh);
		}
	}

//print_r($jobs);

	// execute all functions from $jobs, whatever they may be
	if (!empty($jobs)) {
		$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');
		for ($i=0;isset($jobs[$i]);++$i) {
			if (function_exists($jobs[$i])) {
				$start_time=time();
				$jobs[$i]();
//				echo $jobs[$i].': '.(time()-$start_time)." seconds<br>\n";
			}
		}
	}
//	echo count($jobs)." jobs run.\n";
}
?>