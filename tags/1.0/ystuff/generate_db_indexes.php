<?php
require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/classes/package_downloader.class.php';

$query="show tables";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
for ($i=0;$i<mysql_num_rows($res);++$i) {
	$table=mysql_result($res,$i,0);
	$query="SHOW INDEXES FROM `$table`";
	if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$keys=array();
	while ($rsrow=mysql_fetch_assoc($res2)) {
		if ($rsrow['Non_unique']==0) {
			if ($rsrow['Key_name']=='PRIMARY') {
				$rsrow['Key_name']='PRIMARY KEY ';
			} else {
				$rsrow['Key_name']='UNIQUE `'.$rsrow['Key_name'].'` ';
			}
		} elseif ($rsrow['Index_type']=='FULLTEXT') {
			$rsrow['Key_name']='FULLTEXT `'.$rsrow['Key_name'].'` ';
		} else {
			$rsrow['Key_name']='INDEX `'.$rsrow['Key_name'].'` ';
		}
		$keys[$rsrow['Key_name']][$rsrow['Seq_in_index']]=$rsrow['Column_name'];
	}
	$towrite="ALTER TABLE `$table`";

	foreach ($keys as $keyname=>$fields) {
		$towrite.=" ADD ".$keyname."(`".join('`,`',$fields)."`),";
	}
	$towrite=substr($towrite,0,-1).';<br>';
	print $towrite;
}

