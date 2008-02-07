<?php
if (!is_dir(dirname(__FILE__).'/install')) {	// already installed. it safe to include it
	include 'includes/common.inc.php';
	echo _INTERNAL_VERSION_;
	if (isset($_GET['lk']) && $_GET['lk']==_LICENSE_KEY_) {
		$query="SELECT `module_code`,`version` FROM `{$dbtable_prefix}modules`";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		echo '<table><tr><th>Module</th><th>Version</th></tr>';
		while ($rsrow=mysql_fetch_assoc($res)) {
			echo '<tr><td>'.$rsrow['module_code'].'</td><td>'.$rsrow['version'].'</td></tr>';
		}
		echo '</table>';
	}
}
if (isset($_GET['serverinfo'])) {
	print phpinfo();
}
