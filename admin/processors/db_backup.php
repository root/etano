<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/db_backup.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
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
			$towrite.="\n\t`".$rsrow['Field'].'` '.$rsrow['Type'];
			if ($rsrow['Extra']!='auto_increment' && $rsrow['Default']!=null) {
				if (strcasecmp($rsrow['Type'],'timestamp')==0 && strcasecmp($rsrow['Default'],'CURRENT_TIMESTAMP')==0) {
					$towrite.=" DEFAULT ".$rsrow['Default'];
				} else {
					$towrite.=" DEFAULT '".$rsrow['Default']."'";
				}
			}
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
			if ($rsrow['Non_unique']==0) {
				if ($rsrow['Key_name']=='PRIMARY') {
					$rsrow['Key_name']='PRIMARY KEY';
				} else {
					$rsrow['Key_name']='UNIQUE KEY `'.$rsrow['Key_name'].'`';
				}
			} elseif ($rsrow['Index_type']=='FULLTEXT') {
				$rsrow['Key_name']='FULLTEXT KEY `'.$rsrow['Key_name'].'`';
			} else {
				$rsrow['Key_name']='KEY `'.$rsrow['Key_name'].'`';
			}
			if (!empty($rsrow['Sub_part'])) {
				$keys[$rsrow['Key_name']][$rsrow['Seq_in_index']]=$rsrow['Column_name'].'('.$rsrow['Sub_part'].')';
			} else {
				$keys[$rsrow['Key_name']][$rsrow['Seq_in_index']]=$rsrow['Column_name'];				
			}
		}
		foreach ($keys as $keyname=>$fields) {
			$towrite.="\n\t{$keyname} (`".join('`,`',$fields)."`),";
		}
		$towrite=substr($towrite,0,-1);
		$towrite.="\n)";
		$query="SHOW TABLE STATUS LIKE '".$tables[$i]."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$rsrow=mysql_fetch_assoc($res);
		if (isset($rsrow['Type'])) {
			 $towrite.=' TYPE='.$rsrow['Type'];
		} elseif (isset($rsrow['Engine'])) {
			 $towrite.=' ENGINE='.$rsrow['Engine'];
		}
		$towrite.=' '.$rsrow['Create_options'].";\n\n";
	}

	if (!empty($towrite)) {
		header('Content-Type: application/octet-stream; name="'._DBNAME_.'_'.date('Y-m-d').'.sql"'); //This should work for Non IE/Opera browsers
		header('Content-Type: application/octetstream; name="'._DBNAME_.'_'.date('Y-m-d').'.sql"'); // This should work for IE & Opera
		header('Content-Disposition: attachment; filename="'._DBNAME_.'_'.date('Y-m-d').'.sql"');
		header('Content-transfer-encoding: binary');
		echo $towrite;
		ob_flush();
		flush();
		$towrite='';
		$query_char_limit=20000;
		for ($i=0;isset($tables[$i]);++$i) {
			$query="SELECT * FROM `".$tables[$i]."`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$towrite='';
			$querystart="INSERT INTO `".$tables[$i]."` VALUES ";
			$towrite=$querystart;
			while ($rsrow=mysql_fetch_row($res)) {
				$rsrow=sanitize_and_format($rsrow,TYPE_STRING,FORMAT_ADDSLASH);
				if (strlen($towrite)>=$query_char_limit) {
					$towrite=substr($towrite,0,-1).";\n";
					echo $towrite;
					ob_flush();
					flush();
					$towrite=$querystart;
				}
				$towrite.="('".join("','",$rsrow)."'),";
			}
			if ($towrite!=$querystart) {
				$towrite=substr($towrite,0,-1).";\n\n";
				echo $towrite;
				ob_flush();
				flush();
			}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Database saved.';
	}
}
//redirect2page('admin/db_backup.php',$topass,$qs);
