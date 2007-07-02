<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/db_backup.php
$Revision: 174 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);
set_time_limit(0);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$query="SHOW TABLES";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$tables=array();
	while ($rsrow=mysql_fetch_row($res)) {
		$tables[]=$rsrow[0];
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
					$rsrow['Key_name']='UNIQUE KEY `'.$rsrow['Key_name'].'`';
				} elseif ($rsrow['Index_type']=='FULLTEXT') {
					$rsrow['Non_unique']='FULLTEXT KEY `'.$rsrow['Key_name'].'`';
				} else {
					$rsrow['Non_unique']='KEY `'.$rsrow['Key_name'].'`';
				}
				$keys[$rsrow['Key_name']][$rsrow['Seq_in_index']]=$rsrow['Column_name'];
			}
		}
		foreach ($keys as $keyname=>$fields) {
			$towrite.="\n\t{$keyname} (`".join('`,`',$fields)."`),";
		}
		$towrite=substr($towrite,0,-1);
		$towrite.="\n) TYPE=";
		$query="SHOW TABLE STATUS LIKE '".$tables[$i]."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$rsrow=mysql_fetch_assoc($res);
		$towrite.=$rsrow['Type'].' '.$rsrow['Create_options'].";\n\n";
	}

	$filename=_BASEPATH_.'/tmp/admin/db_'.date('YmdHis').'.sql';
	$fp=fopen($filename,'wb');
	fwrite($fp,$towrite);

	$towrite='';
	$query_char_limit=10000;
	for ($i=0;isset($tables[$i]);++$i) {
		$query="SELECT * FROM `".$tables[$i]."`";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$towrite='';
		$querystart="INSERT INTO `".$tables[$i]."` VALUES ";
		$towrite=$querystart;
		$is_insert=false;
		while ($rsrow=mysql_fetch_row($res)) {
			if (strlen($towrite)<$query_char_limit) {
				$rsrow=sanitize_and_format($rsrow,TYPE_STRING,FORMAT_ADDSLASH);
				$towrite.="('".join("','",$rsrow)."'),";
				$is_insert=true;
			} else {
				$towrite=substr($towrite,0,-1).";\n";
				fwrite($fp,$towrite);
				$towrite=$querystart;
				$is_insert=false;
			}
		}
		if ($is_insert) {
			$towrite=substr($towrite,0,-1).";\n\n";
			fwrite($fp,$towrite);
		}
	}
	fclose($fp);
	if (is_file($filename)) {
		header('Content-Type: application/octet-stream; name="etano_'.date('Y-m-d').'.sql"'); //This should work for Non IE/Opera browsers
		header('Content-Type: application/octetstream; name="etano_'.date('Y-m-d').'.sql"'); // This should work for IE & Opera
		header('Content-Disposition: attachment; filename="etano_'.date('Y-m-d').'.sql"');
		header('Content-transfer-encoding: binary');
		header('Content-length: '.filesize($filename));
		@readfile($filename);
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Database saved.';
	}
}
//redirect2page('admin/db_backup.php',$topass,$qs);
?>