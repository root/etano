<?php
if (!empty($_SERVER['HTTP_HOST'])) {
	$my_url=substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/'));
	$baseurl=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$my_url;
	header("Location: $baseurl/install/index.php");
} else {
	echo '<a href="install/index.php">Click here to install Etano</a>';
}
die;
