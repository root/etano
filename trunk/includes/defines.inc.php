<?php
define('_DBHOSTNAME_','localhost');// mysql server host name
define('_DBUSERNAME_','root');// mysql database username
define('_DBPASSWORD_','rootpass');// mysql database password
define('_DBNAME_','newdsb');// mysql database name
define('_SITENAME_','Good Beyond Words Dating Application');// Your site name
define('_BASEURL_','http://dating.sco.ro/newdsb');// protocol required (http:// )
define('_BASEPATH_','/www/htdocs/newdsb');// path on server to your site
define('_RESULTS_',6);// default search results per page
define('_DEFAULT_SKIN_','skin_basic');
define('_FTP_SERVER_','localhost');
define('_FTPPATH_','/newdsb');
define('_FTP_USER_','newdsb');
define('_FTP_PASS_','test132');
$dbtable_prefix='dsb_';
define('USER_ACCOUNTS_TABLE',"`{$dbtable_prefix}user_accounts`");
$accepted_results_per_page=array('6'=>6,'12'=>12,'24'=>24,'48'=>48);
$accepted_images=array('jpg','jpeg','png');

define('_LICENSE_KEY_','0917JJ8239HG8S623DFG45');

$_user_settings=array('date_format'=>'%x','datetime_format'=>'%c','time_offset'=>7200);
