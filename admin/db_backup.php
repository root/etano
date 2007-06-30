<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/db_backup.php
$Revision: 174 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$query="SHOW TABLES";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$tables=array();
while ($table=mysql_fetch_row($res)) {
	$tables[]=$table[0];
}

$towrite='';
for ($i=0;isset($tables[$i]);++$i) {
	$towrite.="CREATE TABLE `".$tables[$i]."` (";
	$query="SHOW COLUMNS FROM `".$tables[$i]."`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$towrite.="\n\t`".$rsrow['Field'].'` '.$rsrow['Type']." DEFAULT '".$rsrow['Default']."'";
		if ($rsrow['Null']!='YES') {
			$towrite.=' NOT NULL';
		}
		if (!empty($rsrow['Extra'])) {
			$towrite.=' '.$rsrow['Extra'];
		}
		$towrite.=',';
	}
	$query="SHOW INDEXES FROM `".$tables[$i]."`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$keys=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['Key_name']=='PRIMARY') {
			$towrite.="\n\tPRIMARY KEY (`".$rsrow['Column_name']."`),";
		} else {
			if ($rsrow['Non_unique']==0) {
				$rsrow['Non_unique']='UNIQUE KEY';
			} elseif ($rsrow['Index_type']=='FULLTEXT') {
				$rsrow['Non_unique']='FULLTEXT KEY';
			} else {
				$rsrow['Non_unique']='KEY';
			}
			$keys[$rsrow['Non_unique']][$rsrow['Key_name']][$rsrow['Seq_in_index']]=$rsrow['Column_name'];
		}
	}
	foreach ($keys as $keytype=>$v) {
		foreach ($v as $keyname=>$fields) {
			$towrite.="\n\t".$keytype." `$keyname` (`".join('`,`',$fields)."`),";
		}
	}
	$towrite=substr($towrite,0,-1);
	$towrite.="\n) TYPE=";
	$query="SHOW TABLE STATUS LIKE '".$tables[$i]."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$rsrow=mysql_fetch_assoc($res);
	$towrite.=$rsrow['Type'].' '.$rsrow['Create_options'].";\n\n";
}

print $towrite;
?>