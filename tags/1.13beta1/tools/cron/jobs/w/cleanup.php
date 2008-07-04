<?php
$jobs[]='clean_tmp';

function clean_tmp() {
	if ($dh=opendir(_BASEPATH_.'/tmp/admin')) {
		while (($file=readdir($dh))!==false) {
			if ($file{0}!='.' && $file!='index.html' && is_file(_BASEPATH_.'/tmp/admin/'.$file) && filectime(_BASEPATH_.'/tmp/admin/'.$file)<time()-172800) {	// 2 days
				@unlink(_BASEPATH_.'/tmp/admin/'.$file);
			}
		}
		closedir($dh);
	}

	if ($dh=opendir(_BASEPATH_.'/tmp')) {
		while (($file=readdir($dh))!==false) {
			if ($file{0}!='.' && $file!='index.html' && is_file(_BASEPATH_.'/tmp/'.$file) && filectime(_BASEPATH_.'/tmp/'.$file)<time()-172800) {	// 2 days
				@unlink(_BASEPATH_.'/tmp/'.$file);
			}
		}
		closedir($dh);
	}
}
