<?php
define('_DBHOST_','{input.dbhost}');// mysql server host name
define('_DBUSER_','{input.dbuser}');// mysql database username
define('_DBPASS_','{input.dbpass}');// mysql database password
define('_DBNAME_','{input.dbname}');// mysql database name
define('_SITENAME_','{input.site_name}');// Your site name
define('_BASEURL_','{input.baseurl}');// protocol required (http:// )
define('_BASEPATH_','{input.basepath}');// path on server to your site
define('_PHOTOURL_',_BASEURL_.'/media/pics');// protocol required (http:// ). URL to your member photos
define('_PHOTOPATH_',_BASEPATH_.'/media/pics');// path on server to your member photos
define('_CACHEPATH_',_BASEPATH_.'/cache');// path on server to the cache folder
define('_FILEOP_MODE_','{input.fileop_mode}');
define('_FTPHOST_','{input.ftphost}');
define('_FTPPATH_','{input.ftppath}');
define('_FTPUSER_','{input.ftpuser}');
define('_FTPPASS_','{input.ftppass}');
$dbtable_prefix=$GLOBALS['dbtable_prefix']='{input.dbtable_prefix}';
define('USER_ACCOUNTS_TABLE',"{$dbtable_prefix}user_accounts");
define('USER_ACCOUNT_ID','user_id');
define('USER_ACCOUNT_USER','user');
define('USER_ACCOUNT_PASS','pass');
define('PASSWORD_ENC_FUNC','md5');

define('_LICENSE_KEY_','{input.license_key}'); // md5()={input.license_key_md5}
define('_INTERNAL_VERSION_','1.22');

$accepted_results_per_page=array(10=>10,5=>5,15=>15,20=>20);
$accepted_images=array('jpg','jpeg','png');
