<?php
define('_DBHOST_','localhost');// mysql server host name
define('_DBUSER_','root');// mysql database username
define('_DBPASS_','rootpass');// mysql database password
define('_DBNAME_','etano');// mysql database name
define('_SITENAME_','Etano');// Your site name
define('_BASEURL_','http://dating.sco.ro/etano');// protocol required (http:// )
define('_BASEPATH_','/www/htdocs/etano');// path on server to your site
define('_PHOTOURL_',_BASEURL_.'/media/pics');// protocol required (http:// ). URL to your member photos
define('_PHOTOPATH_',_BASEPATH_.'/media/pics');// path on server to your member photos
define('_CACHEPATH_',_BASEPATH_.'/cache');// path on server to the cache folder
define('_FILEOP_MODE_','disk');
define('_FTPHOST_','');
define('_FTPPATH_','');
define('_FTPUSER_','');
define('_FTPPASS_','');
$dbtable_prefix='dsb_';
define('USER_ACCOUNTS_TABLE',"`{$dbtable_prefix}user_accounts`");
define('USER_ACCOUNT_ID','user_id');
define('USER_ACCOUNT_USER','user');
define('USER_ACCOUNT_PASS','pass');
define('PASSWORD_ENC_FUNC','md5');

define('_LICENSE_KEY_','0917JJ8239HG8S623DFG45'); // md5()=d3074931bf4080bff08a1cc60fff4504
define('_INTERNAL_VERSION_','0.8');

$accepted_results_per_page=array(10=>10,5=>5,15=>15,20=>20);
$accepted_images=array('jpg','jpeg','png');
