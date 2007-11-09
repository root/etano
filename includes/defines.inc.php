<?php
define('_DBHOST_','localhost');// mysql server host name
define('_DBUSER_','root');// mysql database username
define('_DBPASS_','rootpass');// mysql database password
define('_DBNAME_','datemill_new');// mysql database name
define('_SITENAME_','Datemill');// Your site name
define('_BASEURL_','http://dating.sco.ro/dm_new');// protocol required (http:// )
define('_BASEPATH_','/www/htdocs/dm_new');// path on server to your site
define('_PHOTOURL_',_BASEURL_.'/media/pics');// protocol required (http:// ). URL to your member photos
define('_PHOTOPATH_',_BASEPATH_.'/media/pics');// path on server to your member photos
define('_CACHEPATH_',_BASEPATH_.'/cache');// path on server to the cache folder
define('_FILEOP_MODE_','disk');
define('_FTPHOST_','');
define('_FTPPATH_','');
define('_FTPUSER_','');
define('_FTPPASS_','');
$dbtable_prefix='dsb_';
define('USER_ACCOUNTS_TABLE',"{$dbtable_prefix}user_accounts");
define('USER_ACCOUNT_ID','user_id');
define('USER_ACCOUNT_USER','user');
define('USER_ACCOUNT_PASS','pass');
define('PASSWORD_ENC_FUNC','md5');

define('_LICENSE_KEY_','7B005E43F1817FA9232845'); // md5()=66886026c95a1da38cc3e630cc8a7d25
define('_INTERNAL_VERSION_','1.00');

$accepted_results_per_page=array(10=>10,5=>5,15=>15,20=>20);
$accepted_images=array('jpg','jpeg','png');
