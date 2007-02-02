<?php
$jobs[]='optimize_tables';

function optimize_tables() {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];

	$query="SHOW TABLES";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$tables=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$tables[]=mysql_result($res,$i,0);
	}
	$query="OPTIMIZE TABLE `".join('`,`',$tables)."`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	return true;
}
?>