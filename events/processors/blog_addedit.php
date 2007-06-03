<?php

$_on_before_update[]='testb';
$_on_after_update[]='testa';

function testb() {
	$fp=fopen('/tmp/test.txt','wb');
	fwrite($fp,"test before ok\n");
	fclose($fp);
}

function testa() {
	$fp=fopen('/tmp/test.txt','ab');
	fwrite($fp,"test after ok\n");
	fclose($fp);
}
