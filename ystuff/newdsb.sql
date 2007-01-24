-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 23, 2007 at 11:26 AM
-- Server version: 4.0.18
-- PHP Version: 4.4.2
-- 
-- Database: `newdsb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_access_levels`
-- 

DROP TABLE IF EXISTS `dsb_access_levels`;
CREATE TABLE `dsb_access_levels` (
  `level_id` int(10) unsigned NOT NULL auto_increment,
  `level_code` varchar(30) binary NOT NULL default '',
  `level_diz` varchar(255) NOT NULL default '',
  `level` int(10) unsigned NOT NULL default '0',
  `disabled_level` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`level_id`),
  KEY `level_code` (`level_code`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_access_levels`
-- 

INSERT INTO `dsb_access_levels` (`level_id`, `level_code`, `level_diz`, `level`, `disabled_level`) VALUES (1, 0x6c6f67696e, 'when someone tries to login', 6, 1),
(2, 0x70726f66696c655f76696577, 'View a member profile', 7, 0),
(3, 0x70726f66696c655f65646974, 'Change your profile details', 6, 1),
(4, 0x6d6573736167655f72656164, 'Read messages', 6, 1),
(5, 0x6d6573736167655f7772697465, 'Write messages', 6, 1),
(6, 0x666c6972745f72656164, 'Read flirts', 6, 1),
(7, 0x666c6972745f73656e64, 'Send flirts', 6, 1),
(8, 0x75706c6f61645f70686f746f73, 'Upload photos', 6, 1),
(9, 0x77726974655f70686f746f5f636f6d6d656e7473, 'Add comments to photos', 6, 0),
(10, 0x726561645f626c6f6773, 'Read blogs', 7, 0),
(11, 0x77726974655f626c6f6773, 'Write own blogs', 4, 1),
(12, 0x766965775f616c62756d, 'Who''s allowed to view the list of photos in a photo album', 7, 0),
(13, 0x766965775f70686f746f, 'View a single photo with a bigger size and photo comments', 7, 0),
(14, 0x6d616e6167655f666f6c646572, 'Add/Edit/Delete personal mail folders', 6, 1),
(15, 0x6d6573736167655f74656d706c61746573, 'Save and use message templates', 6, 1),
(16, 0x7365617263685f6261736963, 'Who is allowed to search?', 7, 0),
(17, 0x7365617263685f616476616e636564, 'Who is allowed to use the advanced search?', 6, 0),
(18, 0x6d616e6167655f6e6574776f726b73, 'Who is allowed to add/remove members in their networks?', 6, 1),
(19, 0x736176655f7365617263686573, 'Who is allowed to save personal searches?', 6, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_admin_accounts`
-- 

DROP TABLE IF EXISTS `dsb_admin_accounts`;
CREATE TABLE `dsb_admin_accounts` (
  `admin_id` int(3) unsigned NOT NULL default '0',
  `user` varchar(20) binary NOT NULL default '',
  `pass` varchar(32) binary NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `dept_id` tinyint(2) unsigned NOT NULL default '0',
  `email` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`admin_id`),
  UNIQUE KEY `user` (`user`)
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `dsb_admin_accounts`
-- 

INSERT INTO `dsb_admin_accounts` (`admin_id`, `user`, `pass`, `name`, `status`, `dept_id`, `email`) VALUES (1, 0x61646d696e, 0x3931383062346461336630633765383039373566616436383566376631333465, 'Dan Caragea', 15, 4, 'dan@sco.ro');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_admin_mtpls`
-- 

DROP TABLE IF EXISTS `dsb_admin_mtpls`;
CREATE TABLE `dsb_admin_mtpls` (
  `amtpl_id` int(10) unsigned NOT NULL auto_increment,
  `amtpl_name` varchar(40) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `amtpl_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`amtpl_id`),
  KEY `amtpl_type` (`amtpl_type`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_admin_mtpls`
-- 

INSERT INTO `dsb_admin_mtpls` (`amtpl_id`, `amtpl_name`, `subject`, `message_body`, `amtpl_type`) VALUES (1, 'Reject member profile', 'Your profile was not approved', '<html><head><title>Your profile has not been approved</title>   <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" /> </head><body> <div id="trim"> 	<div id="content"> 		<p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p> 		<p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest to other members.</p><p>Please update your profile with relevant information.<br /></p> 	</div> </div> </body></html>', 1),
(2, 'Reject photo', 'Web Application: One of your photos was not approved', '<html>\n<head>\n<title>Your profile has not been approved</title>\n<link rel="stylesheet" type="text/css" media="screen" href="{tplvars.baseurl}/skins/basic/styles/screen.css" />\n</head>\n<body>\n<div id="trim">\n   <div id="content">\n       <p>Thank you for joining <a href="{tplvars.baseurl}">{tplvars.sitename}</a>.</p>\n       <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\n   </div>\n</div>\n</body>\n</html>', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_blog_posts`
-- 

DROP TABLE IF EXISTS `dsb_blog_posts`;
CREATE TABLE `dsb_blog_posts` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `fk_post_id_parent` int(10) unsigned NOT NULL default '0',
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `is_public` tinyint(1) unsigned NOT NULL default '1',
  `title` varchar(200) NOT NULL default '',
  `photo` varchar(128) NOT NULL default '',
  `post_content` text NOT NULL,
  `allow_comments` tinyint(1) unsigned NOT NULL default '1',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `reject_reason` text NOT NULL,
  PRIMARY KEY  (`post_id`),
  KEY `fk_parent_id` (`fk_post_id_parent`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `is_public` (`is_public`),
  KEY `date_posted` (`date_posted`)
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `dsb_blog_posts`
-- 

INSERT INTO `dsb_blog_posts` (`post_id`, `fk_post_id_parent`, `date_posted`, `fk_user_id`, `_user`, `is_public`, `title`, `photo`, `post_content`, `allow_comments`, `status`, `last_changed`, `reject_reason`) VALUES (1, 0, '2006-10-25 14:02:46', 2, 'test', 1, 'test titlu', '', 'ala bala portocala', 1, 0, '2006-10-25 14:02:46', ''),
(2, 0, '2006-10-25 20:55:04', 2, 'test', 1, 'second post', '', '// get the input we need and sanitize it\r\n	foreach ($blog_posts_default[''types''] as $k=>$v) {\r\n		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$blog_posts_default[''defaults''][$k]);\r\n	}\r\n	$input[''fk_user_id'']=$_SESSION[''user''][''user_id''];\r\n	$input[''posted_by'']=$_SESSION[''user''][''user''];\r\n\r\n	if (!$error) {\r\n		if (!empty($input[''post_id''])) {\r\n			unset($input[''date_posted'']);\r\n			$query="UPDATE `$blog_posts` SET `last_changed`=''".gmdate(''YmdHis'')."''";\r\n			if (get_site_option(''manual_blog_approval'',2)==1) {\r\n				$query.=",`status`=''".PSTAT_PROCESSING."''";\r\n			} else {\r\n				$query.=",`status`=''".PSTAT_APPROVED."''";\r\n			}\r\n			foreach ($blog_posts_default[''defaults''] as $k=>$v) {\r\n				if (isset($input[$k])) {\r\n					$query.=",`$k`=''".$input[$k]."''";\r\n				}\r\n			}\r\n			$query.=" WHERE `post_id`=''".$input[''post_id'']."''";\r\n			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}\r\n			$topass[''message''][''type'']=MESSAGE_INFO;\r\n			$topass[''message''][''text'']=''Post changed successfully.'';\r\n		} else {\r\n			$input[''date_posted'']=date(''Y-m-d H:i:s'');\r\n			$query="INSERT INTO `$blog_posts` SET `last_changed`=''".gmdate(''YmdHis'')."''";\r\n			if (get_site_option(''manual_blog_approval'',2)==1) {\r\n				$query.=",`status`=''".PSTAT_PROCESSING."''";\r\n			} else {\r\n				$query.=",`status`=''".PSTAT_APPROVED."''";\r\n			}\r\n			foreach ($blog_posts_default[''defaults''] as $k=>$v) {\r\n				if (isset($input[$k])) {\r\n					$query.=",`$k`=''".$input[$k]."''";\r\n				}\r\n			}\r\n			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}\r\n			$topass[''message''][''type'']=MESSAGE_INFO;\r\n			$topass[''message''][''text'']=''Post saved.'';\r\n		}\r\n	} else {\r\n		$nextpage=''blogs_addedit.php'';\r\n		$topass[''input'']=$input;\r\n	}\r\n}\r\nredirect2page($nextpage,$topass,$qs);\r\n?>', 1, 15, '2006-10-26 10:33:37', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_error_log`
-- 

DROP TABLE IF EXISTS `dsb_error_log`;
CREATE TABLE `dsb_error_log` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `module` varchar(48) NOT NULL default '',
  `error` text NOT NULL,
  `error_date` timestamp(14) NOT NULL,
  PRIMARY KEY  (`log_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_error_log`
-- 

INSERT INTO `dsb_error_log` (`log_id`, `module`, `error`, `error_date`) VALUES (1, '', 'Undefined index:  6\n<br />Last query run: SELECT * FROM `dsb_user_profiles` WHERE `fk_user_id`=''2''\n<br /><pre>Array\n(\n    [0] => Array\n        (\n            [function] => general_error\n            [args] => Array\n                (\n                    [0] => 8\n                    [1] => Undefined index:  6\n                    [2] => f:\\www\\htdocs\\newdsb\\admin\\profile.php\n                    [3] => 76\n                    [4] => Array\n                        (\n                            [HTTP_POST_VARS] => Array\n                                (\n                                )\n\n                            [_POST] => Array\n                                (\n                                )\n\n                            [HTTP_GET_VARS] => Array\n                                (\n                                    [uid] => 2\n                                    [search] => 40cd750bba9870f18aada2478b24840a\n                                    [o] => 0\n                                    [r] => 6\n                                )\n\n                            [_GET] => Array\n                                (\n                                    [uid] => 2\n                                    [search] => 40cd750bba9870f18aada2478b24840a\n                                    [o] => 0\n                                    [r] => 6\n                                )\n\n                            [HTTP_COOKIE_VARS] => Array\n                                (\n                                    [__utmz] => 221341338.1162157171.1.1.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none)\n                                    [__utma] => 221341338.1630776446.1162157171.1162157171.1162157171.1\n                                    [sco_app] => Array\n                                        (\n                                            [skin] => skin_basic\n                                        )\n\n                                    [PHPSESSID] => 8a7b1fc4960549f6c1662c398f2f4ff9\n                                )\n\n                            [_COOKIE] => Array\n                                (\n                                    [__utmz] => 221341338.1162157171.1.1.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none)\n                                    [__utma] => 221341338.1630776446.1162157171.1162157171.1162157171.1\n                                    [sco_app] => Array\n                                        (\n                                            [skin] => skin_basic\n                                        )\n\n                                    [PHPSESSID] => 8a7b1fc4960549f6c1662c398f2f4ff9\n                                )\n\n                            [HTTP_SERVER_VARS] => Array\n                                (\n                                    [COMSPEC] => C:\\WINBLOWS\\system32\\cmd.exe\n                                    [DOCUMENT_ROOT] => f:/www/htdocs\n                                    [HTTP_ACCEPT] => text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\n                                    [HTTP_ACCEPT_CHARSET] => ISO-8859-1,utf-8;q=0.7,*;q=0.7\n                                    [HTTP_ACCEPT_ENCODING] => gzip,deflate\n                                    [HTTP_ACCEPT_LANGUAGE] => en-us,en;q=0.5\n                                    [HTTP_CONNECTION] => keep-alive\n                                    [HTTP_COOKIE] => __utmz=221341338.1162157171.1.1.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none); __utma=221341338.1630776446.1162157171.1162157171.1162157171.1; sco_app[skin]=skin_basic; PHPSESSID=8a7b1fc4960549f6c1662c398f2f4ff9\n                                    [HTTP_HOST] => dating.sco.ro\n                                    [HTTP_KEEP_ALIVE] => 300\n                                    [HTTP_REFERER] => http://dating.sco.ro/newdsb/admin/member_results.php?user=&astat=0&pstat=0&membership=0&field_48_min=0&field_48_max=0&field_50_country=0&field_50_state=0&field_50_city=0&field_50_dist=1&field_50_zip=\n                                    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1\n                                    [PATH] => C:\\WINBLOWS\\system32;C:\\WINBLOWS;C:\\WINBLOWS\\System32\\Wbem;d:\\Program Files\\Intel\\DMIX;D:\\Program Files\\ATI Technologies\\ATI.ACE\\;F:\\www\\mysql5018\\bin;F:\\www\\php-4.4.2\\PEAR;F:\\www\\php-4.4.2;F:\\www\\php-4.4.2\\PEAR;F:\\www\\php-4.4.2\\PEAR\\pear;D:\\Program Files\\Perforce\n                                    [REMOTE_ADDR] => 127.0.0.1\n                                    [REMOTE_PORT] => 2393\n                                    [SCRIPT_FILENAME] => f:/www/htdocs/newdsb/admin/profile.php\n                                    [SERVER_ADDR] => 127.0.0.1\n                                    [SERVER_ADMIN] => localwebserver@sco.ro\n                                    [SERVER_NAME] => localhost\n                                    [SERVER_PORT] => 80\n                                    [SERVER_SIGNATURE] => <ADDRESS>Apache/1.3.34 Server at localhost Port 80</ADDRESS>\n\n                                    [SERVER_SOFTWARE] => Apache/1.3.34 (Win32) PHP/4.4.2\n                                    [SystemRoot] => C:\\WINBLOWS\n                                    [WINDIR] => C:\\WINBLOWS\n                                    [GATEWAY_INTERFACE] => CGI/1.1\n                                    [SERVER_PROTOCOL] => HTTP/1.1\n                                    [REQUEST_METHOD] => GET\n                                    [QUERY_STRING] => uid=2&search=40cd750bba9870f18aada2478b24840a&o=0&r=6\n                                    [REQUEST_URI] => /newdsb/admin/profile.php?uid=2&search=40cd750bba9870f18aada2478b24840a&o=0&r=6\n                                    [SCRIPT_NAME] => /newdsb/admin/profile.php\n                                    [PATH_TRANSLATED] => f:/www/htdocs/newdsb/admin/profile.php\n                                    [PHP_SELF] => /newdsb/admin/profile.php\n                                )\n\n                            [_SERVER] => Array\n                                (\n                                    [COMSPEC] => C:\\WINBLOWS\\system32\\cmd.exe\n                                    [DOCUMENT_ROOT] => f:/www/htdocs\n                                    [HTTP_ACCEPT] => text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\n                                    [HTTP_ACCEPT_CHARSET] => ISO-8859-1,utf-8;q=0.7,*;q=0.7\n                                    [HTTP_ACCEPT_ENCODING] => gzip,deflate\n                                    [HTTP_ACCEPT_LANGUAGE] => en-us,en;q=0.5\n                                    [HTTP_CONNECTION] => keep-alive\n                                    [HTTP_COOKIE] => __utmz=221341338.1162157171.1.1.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none); __utma=221341338.1630776446.1162157171.1162157171.1162157171.1; sco_app[skin]=skin_basic; PHPSESSID=8a7b1fc4960549f6c1662c398f2f4ff9\n                                    [HTTP_HOST] => dating.sco.ro\n                                    [HTTP_KEEP_ALIVE] => 300\n                                    [HTTP_REFERER] => http://dating.sco.ro/newdsb/admin/member_results.php?user=&astat=0&pstat=0&membership=0&field_48_min=0&field_48_max=0&field_50_country=0&field_50_state=0&field_50_city=0&field_50_dist=1&field_50_zip=\n                                    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1\n                                    [PATH] => C:\\WINBLOWS\\system32;C:\\WINBLOWS;C:\\WINBLOWS\\System32\\Wbem;d:\\Program Files\\Intel\\DMIX;D:\\Program Files\\ATI Technologies\\ATI.ACE\\;F:\\www\\mysql5018\\bin;F:\\www\\php-4.4.2\\PEAR;F:\\www\\php-4.4.2;F:\\www\\php-4.4.2\\PEAR;F:\\www\\php-4.4.2\\PEAR\\pear;D:\\Program Files\\Perforce\n                                    [REMOTE_ADDR] => 127.0.0.1\n                                    [REMOTE_PORT] => 2393\n                                    [SCRIPT_FILENAME] => f:/www/htdocs/newdsb/admin/profile.php\n                                    [SERVER_ADDR] => 127.0.0.1\n                                    [SERVER_ADMIN] => localwebserver@sco.ro\n                                    [SERVER_NAME] => localhost\n                                    [SERVER_PORT] => 80\n                                    [SERVER_SIGNATURE] => <ADDRESS>Apache/1.3.34 Server at localhost Port 80</ADDRESS>\n\n                                    [SERVER_SOFTWARE] => Apache/1.3.34 (Win32) PHP/4.4.2\n                                    [SystemRoot] => C:\\WINBLOWS\n                                    [WINDIR] => C:\\WINBLOWS\n                                    [GATEWAY_INTERFACE] => CGI/1.1\n                                    [SERVER_PROTOCOL] => HTTP/1.1\n                                    [REQUEST_METHOD] => GET\n                                    [QUERY_STRING] => uid=2&search=40cd750bba9870f18aada2478b24840a&o=0&r=6\n                                    [REQUEST_URI] => /newdsb/admin/profile.php?uid=2&search=40cd750bba9870f18aada2478b24840a&o=0&r=6\n                                    [SCRIPT_NAME] => /newdsb/admin/profile.php\n                                    [PATH_TRANSLATED] => f:/www/htdocs/newdsb/admin/profile.php\n                                    [PHP_SELF] => /newdsb/admin/profile.php\n                                )\n\n                            [HTTP_ENV_VARS] => Array\n                                (\n                                )\n\n                            [_ENV] => Array\n                                (\n                                )\n\n                            [HTTP_POST_FILES] => Array\n                                (\n                                )\n\n                            [_FILES] => Array\n                                (\n                                )\n\n                            [_REQUEST] => Array\n                                (\n                                    [uid] => 2\n                                    [search] => 40cd750bba9870f18aada2478b24840a\n                                    [o] => 0\n                                    [r] => 6\n                                    [__utmz] => 221341338.1162157171.1.1.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none)\n                                    [__utma] => 221341338.1630776446.1162157171.1162157171.1162157171.1\n                                    [sco_app] => Array\n                                        (\n                                            [skin] => skin_basic\n                                        )\n\n                                    [PHPSESSID] => 8a7b1fc4960549f6c1662c398f2f4ff9\n                                )\n\n                            [cookie_domain] => \n                            [is_ip] => 1\n                            [i] => 0\n                            [HTTP_SESSION_VARS] => Array\n                                (\n                                    [user] => Array\n                                        (\n                                            [user] => guest\n                                            [membership] => 1\n                                        )\n\n                                    [admin] => Array\n                                        (\n                                            [admin_id] => 1\n                                            [name] => Dan Caragea\n                                            [dept_id] => 4\n                                            [status] => 15\n                                        )\n\n                                )\n\n                            [_SESSION] => Array\n                                (\n                                    [user] => Array\n                                        (\n                                            [user] => guest\n                                            [membership] => 1\n                                        )\n\n                                    [admin] => Array\n                                        (\n                                            [admin_id] => 1\n                                            [name] => Dan Caragea\n                                            [dept_id] => 4\n                                            [status] => 15\n                                        )\n\n                                )\n\n                            [dbtable_prefix] => dsb_\n                            [accepted_results_per_page] => Array\n                                (\n                                    [6] => 6\n                                    [12] => 12\n                                    [24] => 24\n                                    [48] => 48\n                                )\n\n                            [accepted_images] => Array\n                                (\n                                    [0] => jpg\n                                    [1] => jpeg\n                                    [2] => png\n                                )\n\n                            [_user_settings] => Array\n                                (\n                                    [date_format] => %x\n                                    [datetime_format] => %c\n                                    [time_offset] => 7200\n                                )\n\n                            [__html2type] => Array\n                                (\n                                    [2] => 3\n                                    [4] => 3\n                                    [3] => 1\n                                    [9] => 4\n                                    [10] => 9\n                                    [103] => 3\n                                    [104] => 1\n                                    [105] => 2\n                                    [107] => 1\n                                )\n\n                            [__html2format] => Array\n                                (\n                                    [104] => 0\n                                    [105] => 0\n                                    [2] => 4617\n                                    [4] => 4609\n                                    [3] => 0\n                                    [9] => 4097\n                                    [10] => 4105\n                                    [103] => 4617\n                                    [200] => 8192\n                                    [201] => 9216\n                                    [204] => 1\n                                    [202] => 12288\n                                    [203] => 13312\n                                    [107] => 0\n                                )\n\n                            [_access_level] => Array\n                                (\n                                    [1] => 6\n                                    [2] => 7\n                                    [3] => 6\n                                    [4] => 6\n                                    [5] => 6\n                                    [6] => 6\n                                    [7] => 6\n                                    [8] => 6\n                                    [9] => 6\n                                    [10] => 7\n                                    [11] => 4\n                                    [12] => 7\n                                    [13] => 7\n                                    [14] => 6\n                                    [15] => 6\n                                    [16] => 7\n                                    [17] => 6\n                                    [18] => 6\n                                    [19] => 6\n                                )\n\n                            [_lang] => Array\n                                (\n                                    [2] => We&#039;re sorry but you tried to login too many times. Please wait for a while before trying that again.\n                                    [3] => We&#039;re sorry but you don&#039;t have access to this feature. --link to payment--\n                                    [4] => month\n                                    [5] => day\n                                    [6] => year\n                                    [7] => Jan\n                                    [8] => Feb\n                                    [9] => Mar\n                                    [10] => Apr\n                                    [11] => May\n                                    [12] => Jun\n                                    [13] => Jul\n                                    [14] => Aug\n                                    [15] => Sep\n                                    [16] => Oct\n                                    [17] => Nov\n                                    [18] => Dec\n                                    [19] => Invalid user name. Please use only letters and digits.\n                                    [20] => This account already exists. Please choose another one.\n                                    [21] => Password cannot be empty. Please enter your password.\n                                    [22] => Emails do not match. Please check the emails.\n                                    [23] => Invalid email entered. Please check your email.\n                                    [24] => The verification code doesn&#039;t match. Please enter the new code.\n                                    [25] => The fields outlined below are required and must not be empty.\n                                    [26] => You must agree to the terms of services before joining the site.\n                                    [27] => \n                                    [28] => \n                                    [29] => \n                                    [30] => \n                                    [500] => Basic Info\n                                    [501] => Gender\n                                    [502] => Find\n                                    [503] => Help text to explain what is this field for.\n                                    [504] => Looking for\n                                    [505] => Looking for:\n                                    [506] => \n                                    [507] => Date of birth\n                                    [508] => Age:\n                                    [509] => \n                                    [516] => Location\n                                    [517] => From:\n                                    [518] => \n                                    [519] => Physical Features\n                                    [520] => Height\n                                    [521] => Height:\n                                    [522] => Height is your height measured in meters when you stand up on your feet, with your back at 30 degrees from the vertical position. this is a very long comment.\n                                    [523] => Weight\n                                    [524] => Weight:\n                                    [525] => \n                                    [526] => Constitution\n                                    [527] => Ssdf\n                                    [528] => \n                                    [529] => Eyes\n                                    [530] => \n                                    [531] => \n                                    [532] => Favorite food\n                                    [533] => \n                                    [534] => \n                                    [535] => About me\n                                    [536] => \n                                    [537] => Please enter a few words about you\n                                    [538] => pos1\n                                    [539] => ss\n                                    [540] => sdsd\n                                    [541] => dsdsd\n                                    [542] => dddd\n                                    [543] => dsd\n                                    [544] => dsd\n                                    [545] => ssss\n                                    [546] => aa\n                                    [547] => ssss\n                                    [548] => s\n                                    [549] => s\n                                    [550] => s\n                                    [551] => d\n                                    [552] => s\n                                    [553] => d\n                                    [554] => sdsd\n                                    [555] => d\n                                    [556] => d\n                                    [557] => a\n                                    [558] => a\n                                    [559] => sss\n                                    [560] => dd\n                                    [561] => ddd\n                                    [562] => dd\n                                    [563] => sss\n                                    [564] => sss\n                                    [565] => ss\n                                    [566] => s\n                                    [567] => s\n                                    [568] => s\n                                    [569] => a\n                                    [570] => sd\n                                    [571] => d\n                                    [572] => asd\n                                    [573] => ss\n                                    [574] => s\n                                    [575] => a\n                                    [576] => s\n                                    [577] => a\n                                    [578] => s\n                                    [579] => s\n                                    [580] => s\n                                    [581] => d\n                                    [582] => s\n                                    [583] => a\n                                    [584] => ds\n                                    [585] => a\n                                    [586] => s\n                                    [587] => \n                                    [588] => \n                                    [589] => \n                                    [590] => \n                                    [591] => \n                                    [592] => \n                                    [593] => \n                                    [594] => \n                                    [595] => \n                                    [596] => asd1\n                                    [597] => dsa\n                                    [600] => 1m\n                                    [601] => 2m\n                                    [602] => 3m\n                                    [603] => Men\n                                    [604] => Women\n                                    [605] => 1kg\n                                    [606] => 2kg\n                                    [607] => 3kg\n                                    [608] => big\n                                    [609] => slim\n                                    [610] => petite\n                                    [611] => overweight\n                                    [612] => muscular\n                                    [613] => blue\n                                    [614] => green\n                                    [615] => grey\n                                    [616] => brown\n                                    [617] => american\n                                    [618] => mexican\n                                    [619] => indian\n                                    [620] => chinese\n                                    [621] => Man\n                                    [622] => Women\n                                )\n\n                            [_pfields] => Array\n                                (\n                                    [1] => Array\n                                        (\n                                            [label] => Gender\n                                            [html_type] => 3\n                                            [searchable] => 1\n                                            [search_type] => 10\n                                            [search_label] => Find\n                                            [reg_page] => 1\n                                            [required] => 1\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => field_46\n                                            [fk_pcat_id] => 1\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => Man\n                                                    [2] => Women\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                    [0] => 1\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                    [0] => 2\n                                                )\n\n                                            [help_text] => Help text to explain what is this field for.\n                                        )\n\n                                    [2] => Array\n                                        (\n                                            [label] => Looking for\n                                            [html_type] => 10\n                                            [searchable] => 1\n                                            [search_type] => 10\n                                            [search_label] => Looking for:\n                                            [reg_page] => 1\n                                            [required] => 1\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => field_47\n                                            [fk_pcat_id] => 1\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => Men\n                                                    [2] => Women\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                    [0] => 2\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                    [0] => 1\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [3] => Array\n                                        (\n                                            [label] => Date of birth\n                                            [html_type] => 103\n                                            [searchable] => 1\n                                            [search_type] => 103\n                                            [search_label] => Age:\n                                            [reg_page] => 1\n                                            [required] => 1\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => field_48\n                                            [fk_pcat_id] => 1\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => 1950\n                                                    [2] => 1989\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                    [0] => 18\n                                                    [1] => 35\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [4] => Array\n                                        (\n                                            [label] => Location\n                                            [html_type] => 107\n                                            [searchable] => 1\n                                            [search_type] => 107\n                                            [search_label] => From:\n                                            [reg_page] => 1\n                                            [required] => 1\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => field_50\n                                            [fk_pcat_id] => 1\n                                            [fn_on_change] => update_location\n                                            [default_value] => Array\n                                                (\n                                                    [0] => 218\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [5] => Array\n                                        (\n                                            [label] => Height\n                                            [html_type] => 3\n                                            [searchable] => 1\n                                            [search_type] => 3\n                                            [search_label] => Height:\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => f51\n                                            [fk_pcat_id] => 5\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => 1m\n                                                    [2] => 2m\n                                                    [3] => 3m\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => Height is your height measured in meters when you stand up on your feet, with your back at 30 degrees from the vertical position. this is a very long comment.\n                                        )\n\n                                    [6] => Array\n                                        (\n                                            [label] => Weight\n                                            [html_type] => 3\n                                            [searchable] => 1\n                                            [search_type] => 3\n                                            [search_label] => Weight:\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => f52\n                                            [fk_pcat_id] => 5\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => 1kg\n                                                    [2] => 2kg\n                                                    [3] => 3kg\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [7] => Array\n                                        (\n                                            [label] => Constitution\n                                            [html_type] => 3\n                                            [searchable] => 1\n                                            [search_type] => 3\n                                            [search_label] => Ssdf\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => f53\n                                            [fk_pcat_id] => 5\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => big\n                                                    [2] => slim\n                                                    [3] => petite\n                                                    [4] => overweight\n                                                    [5] => muscular\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [8] => Array\n                                        (\n                                            [label] => Eyes\n                                            [html_type] => 3\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => f54\n                                            [fk_pcat_id] => 1\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => blue\n                                                    [2] => green\n                                                    [3] => grey\n                                                    [4] => brown\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [9] => Array\n                                        (\n                                            [label] => Favorite food\n                                            [html_type] => 10\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => f55\n                                            [fk_pcat_id] => 1\n                                            [accepted_values] => Array\n                                                (\n                                                    [0] => -\n                                                    [1] => american\n                                                    [2] => mexican\n                                                    [3] => indian\n                                                    [4] => chinese\n                                                )\n\n                                            [default_value] => Array\n                                                (\n                                                )\n\n                                            [default_search] => Array\n                                                (\n                                                )\n\n                                            [help_text] => \n                                        )\n\n                                    [10] => Array\n                                        (\n                                            [label] => About me\n                                            [html_type] => 4\n                                            [editable] => 1\n                                            [visible] => 1\n                                            [dbfield] => f56\n                                            [fk_pcat_id] => 1\n                                            [help_text] => Please enter a few words about you\n                                        )\n\n                                )\n\n                            [_pcats] => Array\n                                (\n                                    [1] => Array\n                                        (\n                                            [pcat_name] => Basic Info\n                                            [access_level] => 7\n                                            [fields] => Array\n                                                (\n                                                    [0] => 1\n                                                    [1] => 2\n                                                    [2] => 3\n                                                    [3] => 4\n                                                    [4] => 8\n                                                    [5] => 9\n                                                    [6] => 10\n                                                )\n\n                                        )\n\n                                    [5] => Array\n                                        (\n                                            [pcat_name] => Physical Features\n                                            [access_level] => 7\n                                            [fields] => Array\n                                                (\n                                                    [0] => 5\n                                                    [1] => 6\n                                                    [2] => 7\n                                                )\n\n                                        )\n\n                                )\n\n                            [accepted_months] => Array\n                                (\n                                    [0] => month\n                                    [1] => Jan\n                                    [2] => Feb\n                                    [3] => Mar\n                                    [4] => Apr\n                                    [5] => May\n                                    [6] => Jun\n                                    [7] => Jul\n                                    [8] => Aug\n                                    [9] => Sep\n                                    [10] => Oct\n                                    [11] => Nov\n                                    [12] => Dec\n                                )\n\n                            [accepted_currencies] => Array\n                                (\n                                    [USD] => USD\n                                    [EUR] => EUR\n                                )\n\n                            [tplvars] => Array\n                                (\n                                    [sitename] => Web Application\n                                    [baseurl] => http://dating.sco.ro/newdsb\n                                    [relative_path] => ../\n                                    [tplurl] => http://dating.sco.ro/newdsb/skins/basic\n                                    [tplrelpath] => ../skins/basic\n                                    [myself] => Array\n                                        (\n                                            [user] => guest\n                                            [membership] => 1\n                                        )\n\n                                    [default_skin] => skin_basic\n                                )\n\n                            [default_search_fields] => Array\n                                (\n                                    [0] => 1\n                                    [1] => 2\n                                    [2] => 3\n                                    [3] => 4\n                                )\n\n                            [accepted_htmltype] => Array\n                                (\n                                    [2] => Textfield\n                                    [4] => Textarea\n                                    [3] => Drop-down box\n                                    [10] => Multiple checkboxes\n                                    [103] => Date\n                                    [107] => Location\n                                )\n\n                            [field_dbtypes] => Array\n                                (\n                                    [2] => varchar(100) not null default ''''\n                                    [3] => int(5) not null default 0\n                                    [102] => int(10) not null default 0\n                                    [4] => text not null default ''''\n                                    [10] => text not null default ''''\n                                    [101] => varchar(64) not null default ''''\n                                    [103] => date\n                                    [104] => int(5) not null default 0\n                                    [105] => double not null default 0\n                                )\n\n                            [accepted_admin_depts] => Array\n                                (\n                                    [4] => Administrator\n                                    [2] => Moderator\n                                )\n\n                            [accepted_astats] => Array\n                                (\n                                    [5] => Suspended\n                                    [10] => Unactivated\n                                    [15] => Active\n                                )\n\n                            [accepted_pstats] => Array\n                                (\n                                    [5] => Awaiting approval\n                                    [10] => Requires edit\n                                    [15] => Approved\n                                )\n\n                            [accepted_yesno] => Array\n                                (\n                                    [0] => No\n                                    [1] => Yes\n                                )\n\n                            [country_prefered_input] => Array\n                                (\n                                    [s] => state/city selection\n                                    [z] => zip/postal code\n                                )\n\n                            [tpl] => phemplate Object\n                                (\n                                    [vars] => Array\n                                        (\n                                        )\n\n                                    [loops] => Array\n                                        (\n                                        )\n\n                                    [root] => skin/\n                                    [unknowns] => remove\n                                    [parameters] => 0\n                                    [error_handler] => \n                                    [block_start_string] => <block name="|">\n                                    [block_end_string] => </block name="|">\n                                )\n\n                            [search_md5] => 40cd750bba9870f18aada2478b24840a\n                            [uid] => 2\n                            [categs] => Array\n                                (\n                                    [0] => Array\n                                        (\n                                            [pcat_name] => Basic Info\n                                            [cat_content] => Array\n                                                (\n                                                    [0] => Array\n                                                        (\n                                                            [label] => Gender\n                                                            [field] => Man\n                                                        )\n\n                                                    [1] => Array\n                                                        (\n                                                            [label] => Looking for\n                                                            [field] => Women\n                                                        )\n\n                                                    [2] => Array\n                                                        (\n                                                            [label] => Date of birth\n                                                            [field] => 1976-11-01\n                                                        )\n\n                                                    [3] => Array\n                                                        (\n                                                            [label] => Location\n                                                            [field] => United States / Iowa / Ames\n                                                        )\n\n                                                    [4] => Array\n                                                        (\n                                                            [label] => Eyes\n                                                            [field] => blue\n                                                        )\n\n                                                    [5] => Array\n                                                        (\n                                                            [label] => Favorite food\n                                                            [field] => \n                                                        )\n\n                                                    [6] => Array\n                                                        (\n                                                            [label] => About me\n                                                            [field] => Please enter a few<br />\r\n words about you.\n                                                        )\n\n                                                )\n\n                                        )\n\n                                    [1] => Array\n                                        (\n                                            [pcat_name] => Physical Features\n                                        )\n\n                                )\n\n                            [profile] => Array\n                                (\n                                    [profile_id] => 3\n                                    [fk_user_id] => 2\n                                    [status] => 15\n                                    [last_changed] => 2007-01-08 15:14:00\n                                    [reject_reason] => <html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>\n                                    [_user] => test\n                                    [_photo] => 9/2_21168263188.jpg\n                                    [longitude] => -93.6367034912\n                                    [latitude] => 42.0276985168\n                                    [score] => 0\n                                    [field_46] => 1\n                                    [field_47] => |2|\n                                    [field_48] => 1976-11-01\n                                    [field_50_country] => 218\n                                    [field_50_state] => 16\n                                    [field_50_city] => 7089\n                                    [field_50_zip] => 50010\n                                    [f51] => 6\n                                    [f52] => 4\n                                    [f53] => 4\n                                    [f54] => 1\n                                    [f55] => \n                                    [f56] => Please enter a few\r\n words about you.\n                                )\n\n                            [account] => Array\n                                (\n                                )\n\n                            [query] => SELECT * FROM `dsb_user_profiles` WHERE `fk_user_id`=''2''\n                            [res] => Resource id #12\n                            [c] => 1\n                            [pcat_id] => 5\n                            [pcat] => Array\n                                (\n                                    [pcat_name] => Physical Features\n                                    [access_level] => 7\n                                    [fields] => Array\n                                        (\n                                            [0] => 5\n                                            [1] => 6\n                                            [2] => 7\n                                        )\n\n                                )\n\n                            [cat_content] => Array\n                                (\n                                    [0] => Array\n                                        (\n                                            [label] => Height\n                                        )\n\n                                )\n\n                            [field] => Array\n                                (\n                                    [label] => Height\n                                    [html_type] => 3\n                                    [searchable] => 1\n                                    [search_type] => 3\n                                    [search_label] => Height:\n                                    [editable] => 1\n                                    [visible] => 1\n                                    [dbfield] => f51\n                                    [fk_pcat_id] => 5\n                                    [accepted_values] => Array\n                                        (\n                                            [0] => -\n                                            [1] => 1m\n                                            [2] => 2m\n                                            [3] => 3m\n                                        )\n\n                                    [default_value] => Array\n                                        (\n                                        )\n\n                                    [default_search] => Array\n                                        (\n                                        )\n\n                                    [help_text] => Height is your height measured in meters when you stand up on your feet, with your back at 30 degrees from the vertical position. this is a very long comment.\n                                )\n\n                        )\n\n                )\n\n        )\n\n    [1] => Array\n        (\n            [file] => f:\\www\\htdocs\\newdsb\\admin\\profile.php\n            [line] => 76\n            [function] => unknown\n        )\n\n)\n</pre>', '20070123112528');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_feed_cache`
-- 

DROP TABLE IF EXISTS `dsb_feed_cache`;
CREATE TABLE `dsb_feed_cache` (
  `feed_url` varchar(255) binary NOT NULL default '',
  `feed_xml` text NOT NULL,
  `update_time` timestamp(14) NOT NULL,
  PRIMARY KEY  (`feed_url`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_feed_cache`
-- 

INSERT INTO `dsb_feed_cache` (`feed_url`, `feed_xml`, `update_time`) VALUES (0x687474703a2f2f646967672e636f6d2f7273732f636f6e7461696e6572746563686e6f6c6f67792e786d6c, '<?xml version="1.0" encoding="UTF-8"?>\n<rss version="2.0" xmlns:digg="http://digg.com/docs/diggrss/">\n<channel>\n<title>Digg / Technology</title>\n<language>en-us</language><link>http://digg.com/view/technology</link>\n<description>Digg / Technology</description>\n<item>\n<title>Apple confirms 802.11n unlock fee, but it''s just $2 - Engadget</title>\n<link>http://digg.com/apple/Apple_confirms_802_11n_unlock_fee_but_it_s_just_2_Engadget</link>\n<description>i new it</description>\n<pubDate>Fri, 19 Jan 2007 17:50:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Apple_confirms_802_11n_unlock_fee_but_it_s_just_2_Engadget</guid>\n<digg:diggCount>50</digg:diggCount>\n<digg:submitter><digg:username>csleser</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>12</digg:commentCount>\n</item>\n<item>\n<title> Web 2.0: Free Fading Corner Images for your CSS</title>\n<link>http://digg.com/design/Web_2_0_Free_Fading_Corner_Images_for_your_CSS</link>\n<description>PSD, PNG, JPEG, GIF''s. I tried to mix up the sizes a bit by making 2 different corner radii, as well as vertical, horizontal, and 2 border styles.</description>\n<pubDate>Fri, 19 Jan 2007 17:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Web_2_0_Free_Fading_Corner_Images_for_your_CSS</guid>\n<digg:diggCount>215</digg:diggCount>\n<digg:submitter><digg:username>GaffleSnipe</digg:username><digg:userimage>http://digg.com/userimages/g/a/f/gafflesnipe/medium8006.gif</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>21</digg:commentCount>\n</item>\n<item>\n<title>Totally metal hack allows custom Guitar Hero II songs</title>\n<link>http://digg.com/mods/Totally_metal_hack_allows_custom_Guitar_Hero_II_songs</link>\n<description>The folks over at the Scorehero forums are at it again, hacking their way through Guitar Hero II. This time, they''ve managed to replace songs found in the game with custom songs, new note charts and all. A huge list of custom songs (including tunes by TOOL, Dragonforce, Metallica, Journey, and more) can be found</description>\n<pubDate>Fri, 19 Jan 2007 17:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/mods/Totally_metal_hack_allows_custom_Guitar_Hero_II_songs</guid>\n<digg:diggCount>344</digg:diggCount>\n<digg:submitter><digg:username>PacoDG</digg:username><digg:userimage>http://digg.com/userimages/pacodg/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Mods</digg:category>\n<digg:commentCount>39</digg:commentCount>\n</item>\n<item>\n<title>Sometimes MySQL Sucks So Much that You Need PostgreSQL</title>\n<link>http://digg.com/programming/Sometimes_MySQL_Sucks_So_Much_that_You_Need_PostgreSQL</link>\n<description>A website acheived an increase in performance by switching to PostgreSQL.</description>\n<pubDate>Fri, 19 Jan 2007 17:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/Sometimes_MySQL_Sucks_So_Much_that_You_Need_PostgreSQL</guid>\n<digg:diggCount>87</digg:diggCount>\n<digg:submitter><digg:username>kzadorozhny</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>24</digg:commentCount>\n</item>\n<item>\n<title>The Wikipedia dilemma: What is the ideal reading level?</title>\n<link>http://digg.com/tech_news/The_Wikipedia_dilemma_What_is_the_ideal_reading_level</link>\n<description>A quick and dirty test of five of the ''featured articles'' in Wikipedia show that on a readability scale, they come out very high. These aren''t just simple articles written by any High School student; they are complex and in-depth. In a world where USA Today reads at a 10th grade level, could Wikipedia be more ''useful'' if more readable? Depends...</description>\n<pubDate>Fri, 19 Jan 2007 16:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/The_Wikipedia_dilemma_What_is_the_ideal_reading_level</guid>\n<digg:diggCount>250</digg:diggCount>\n<digg:submitter><digg:username>kazzyD</digg:username><digg:userimage>http://digg.com/userimages/kazzyd/medium9219.JPG</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>85</digg:commentCount>\n</item>\n<item>\n<title>Reinventing the invention system</title>\n<link>http://digg.com/tech_news/Reinventing_the_invention_system</link>\n<description>Many inventors and legal experts think reforming the patent system is among the important innovations currently under discussion in business and scientific circles-and the U.S. Patent and Trademark Office agrees as well. And so does the company that''s earned more patents than any other for each of the last 14 years. Includes audio podcast.</description>\n<pubDate>Fri, 19 Jan 2007 16:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Reinventing_the_invention_system</guid>\n<digg:diggCount>217</digg:diggCount>\n<digg:submitter><digg:username>iAlex</digg:username><digg:userimage>http://digg.com/userimages/ialex/medium5393.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>10</digg:commentCount>\n</item>\n<item>\n<title>FLAC &amp; Ogg/Vorbis plugins for iTunes</title>\n<link>http://digg.com/software/FLAC_Ogg_Vorbis_plugins_for_iTunes</link>\n<description>XiphQT components is all you need to playback FLAC &amp; Ogg/Vorbis files in QuickTime or iTunes. A must for people like me who have both iTunes and a Linux media player (Rhythmbox/Listen/Amarok) accessing their music library.</description>\n<pubDate>Fri, 19 Jan 2007 16:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/FLAC_Ogg_Vorbis_plugins_for_iTunes</guid>\n<digg:diggCount>353</digg:diggCount>\n<digg:submitter><digg:username>koregaonpark</digg:username><digg:userimage>http://digg.com/userimages/koregaonpark/medium6062.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>45</digg:commentCount>\n</item>\n<item>\n<title>Mafia 2.0: Is The Mob Married To Your Computer?</title>\n<link>http://digg.com/security/Mafia_2_0_Is_The_Mob_Married_To_Your_Computer</link>\n<description>How organized crime could be using your PC to run rackets on the Internet and what you can do about it.</description>\n<pubDate>Fri, 19 Jan 2007 16:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/Mafia_2_0_Is_The_Mob_Married_To_Your_Computer</guid>\n<digg:diggCount>247</digg:diggCount>\n<digg:submitter><digg:username>scoreboard27</digg:username><digg:userimage>http://digg.com/userimages/s/c/o/scoreboard27/medium3301.jpg</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>25</digg:commentCount>\n</item>\n<item>\n<title>Operators In PHP</title>\n<link>http://digg.com/programming/Operators_In_PHP</link>\n<description>This article is the second of a series of PHP guides that aim at teaching you the basics of PHP programming. Today we are going to discuss different types of operators used in PHP. I hope you remember the basic definition of the operators and operands from my last article (PHP Programming Basics).</description>\n<pubDate>Fri, 19 Jan 2007 15:50:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/Operators_In_PHP</guid>\n<digg:diggCount>287</digg:diggCount>\n<digg:submitter><digg:username>jrepin</digg:username><digg:userimage>http://digg.com/userimages/jrepin/medium.png</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>30</digg:commentCount>\n</item>\n<item>\n<title>Gigabyte H971 Home Theater PC – HDMI, C2D and Loads of Style</title>\n<link>http://digg.com/hardware/Gigabyte_H971_Home_Theater_PC_n_HDMI_C2D_and_Loads_of_Style</link>\n<description>Looking to buy a new Home Theater PC with all the latest mods and cons? Check out the Gigabyte H971 entertainment PC!</description>\n<pubDate>Fri, 19 Jan 2007 15:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Gigabyte_H971_Home_Theater_PC_n_HDMI_C2D_and_Loads_of_Style</guid>\n<digg:diggCount>198</digg:diggCount>\n<digg:submitter><digg:username>TWEAK</digg:username><digg:userimage>http://digg.com/userimages/t/w/e/tweak/medium3854.png</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>33</digg:commentCount>\n</item>\n<item>\n<title>Apple Ushers in Era of the Fluid UI </title>\n<link>http://digg.com/design/Apple_Ushers_in_Era_of_the_Fluid_UI</link>\n<description>Netvibes and Pageflakes are good examples of rudimentary interfaces that depend on fluidity. Digg Spy and Cloud View are other examples of a fluid UI. The commonality between all these services is that they are dealing with massive amounts of information, just like the new CE devices.</description>\n<pubDate>Fri, 19 Jan 2007 15:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Apple_Ushers_in_Era_of_the_Fluid_UI</guid>\n<digg:diggCount>305</digg:diggCount>\n<digg:submitter><digg:username>marksmayo</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>55</digg:commentCount>\n</item>\n<item>\n<title>(Photos) Airstrips that even Experienced Pilots would consider a Challenge</title>\n<link>http://digg.com/design/Photos_Airstrips_that_even_Experienced_Pilots_would_consider_a_Challenge</link>\n<description>Airport Designers could build Airstrips anywhere I guess...Correct me if I''m wrong but isn''t the last photo just a causeway used for just Taxiing?</description>\n<pubDate>Fri, 19 Jan 2007 15:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Photos_Airstrips_that_even_Experienced_Pilots_would_consider_a_Challenge</guid>\n<digg:diggCount>677</digg:diggCount>\n<digg:submitter><digg:username>CLIFFosakaJAPAN</digg:username><digg:userimage>http://digg.com/userimages/cliffosakajapan/medium5296.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>75</digg:commentCount>\n</item>\n<item>\n<title>CodeFetch: Search through the source code in programming books.</title>\n<link>http://digg.com/programming/CodeFetch_Search_through_the_source_code_in_programming_books</link>\n<description>Select a language, and search for code. It is as simple as that. The search displays the code along with what book it was found in and provides an Amazon link to buy the book.</description>\n<pubDate>Fri, 19 Jan 2007 14:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/CodeFetch_Search_through_the_source_code_in_programming_books</guid>\n<digg:diggCount>435</digg:diggCount>\n<digg:submitter><digg:username>3monkeys</digg:username><digg:userimage>http://digg.com/userimages/3monkeys/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>30</digg:commentCount>\n</item>\n<item>\n<title>New lobbying bill to criminalize political bloggers?</title>\n<link>http://digg.com/tech_news/New_lobbying_bill_to_criminalize_political_bloggers</link>\n<description>Opponents of a new lobbying bill are warning supporters that it represents &quot;totalitarianism&quot; and an &quot;expansive intrusion on First Amendment rights,&quot; and that it will put bloggers in prison. Are they right?</description>\n<pubDate>Fri, 19 Jan 2007 14:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/New_lobbying_bill_to_criminalize_political_bloggers</guid>\n<digg:diggCount>134</digg:diggCount>\n<digg:submitter><digg:username>MrBabyMan</digg:username><digg:userimage>http://digg.com/userimages/mrbabyman/medium7859.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>41</digg:commentCount>\n</item>\n<item>\n<title>PopSci''s Best of CES 2007 - Photo Gallery</title>\n<link>http://digg.com/gadgets/PopSci_s_Best_of_CES_2007_Photo_Gallery</link>\n<description>Here, we present a dozen noteworthy products that rose above the din to truly impress us this year.</description>\n<pubDate>Fri, 19 Jan 2007 13:50:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/PopSci_s_Best_of_CES_2007_Photo_Gallery</guid>\n<digg:diggCount>625</digg:diggCount>\n<digg:submitter><digg:username>vudicarus</digg:username><digg:userimage>http://digg.com/userimages/v/u/d/vudicarus/medium9940.gif</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>21</digg:commentCount>\n</item>\n<item>\n<title>Flag564. This is for you.</title>\n<link>http://digg.com/apple/Flag564_This_is_for_you</link>\n<description>Digg this up if you hate flag564. Can you say: REVENGE!</description>\n<pubDate>Fri, 19 Jan 2007 13:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Flag564_This_is_for_you</guid>\n<digg:diggCount>121</digg:diggCount>\n<digg:submitter><digg:username>Universal</digg:username><digg:userimage>http://digg.com/userimages/universal/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>57</digg:commentCount>\n</item>\n<item>\n<title>Ruby on Rails 1.2 released!</title>\n<link>http://digg.com/apple/Ruby_on_Rails_1_2_released</link>\n<description>Get out your party balloons and funny hats because we’re there, baby. Yes, sire, Rails 1.2 is finally available in all it’s glory. It took a little longer than we initially anticipated to get everything lined up (and even then we had a tiny snag that bumped us straight from 1.2.0 to 1.2.1 before this announcement even had time to be written).</description>\n<pubDate>Fri, 19 Jan 2007 13:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Ruby_on_Rails_1_2_released</guid>\n<digg:diggCount>550</digg:diggCount>\n<digg:submitter><digg:username>digrob</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>44</digg:commentCount>\n</item>\n<item>\n<title>Update: Apple Will Charge Only $1.99 To Activate 802.11n </title>\n<link>http://digg.com/apple/Update_Apple_Will_Charge_Only_1_99_To_Activate_802_11n</link>\n<description>Remember that post about Apple charging $5 for current Mac users to activate 802.11n on their machines? Turns out it''s only $1.99, and it''ll be available for purchase on Apple''s website.</description>\n<pubDate>Fri, 19 Jan 2007 13:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Update_Apple_Will_Charge_Only_1_99_To_Activate_802_11n</guid>\n<digg:diggCount>70</digg:diggCount>\n<digg:submitter><digg:username>ryland2</digg:username><digg:userimage>http://digg.com/userimages/r/y/l/ryland2/medium8027.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>16</digg:commentCount>\n</item>\n<item>\n<title>MASSIVE SECURITY BREACH reveals credit card data: TJ Maxx, Marshalls, etc.</title>\n<link>http://digg.com/security/MASSIVE_SECURITY_BREACH_reveals_credit_card_data_TJ_Maxx_Marshalls_etc</link>\n<description>The TJX Companies, a large retailer that operates more than 2,000 retail stores under brands such as Bob’s Stores, HomeGoods, Marshalls, T.J. Maxx and A.J. Wright, said on Wednesday that it suffered a massive computer breach on a portion of its network that handles credit card, debit card, check and merchandise transactions in the US, Canada, and..</description>\n<pubDate>Fri, 19 Jan 2007 13:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/MASSIVE_SECURITY_BREACH_reveals_credit_card_data_TJ_Maxx_Marshalls_etc</guid>\n<digg:diggCount>574</digg:diggCount>\n<digg:submitter><digg:username>BigKitty</digg:username><digg:userimage>http://digg.com/userimages/b/i/g/bigkitty/medium2819.jpg</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>64</digg:commentCount>\n</item>\n<item>\n<title>Gimmie 0.2.0 Released - Now an Applet</title>\n<link>http://digg.com/linux_unix/Gimmie_0_2_0_Released_Now_an_Applet</link>\n<description>Alex Graveley has released version 0.2.0 of his Gimmie project. Gimmie is an experimental panel replacement for the Gnome desktop. Unlike previous versions, which required you to completely replace you desktop panels, this version of Gimmie is a normal panel applet that you can easily run along with your existing desktop setup.</description>\n<pubDate>Fri, 19 Jan 2007 13:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Gimmie_0_2_0_Released_Now_an_Applet</guid>\n<digg:diggCount>294</digg:diggCount>\n<digg:submitter><digg:username>sgarrity</digg:username><digg:userimage>http://digg.com/userimages/sgarrity/medium7887.gif</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>20</digg:commentCount>\n</item>\n<item>\n<title>Changes to Daylight Savings Time in 2007 may affect your databases; DB2...</title>\n<link>http://digg.com/programming/Changes_to_Daylight_Savings_Time_in_2007_may_affect_your_databases_DB2</link>\n<description>In the United States the start and end of daylight savings times are being changed in 2007. Daylight savings time will now start on March 11, 2007 (rather than early April) and will end on November 4, 2007 (rather than late October). Canada has also decided to follow the same schedule. This may impact your databases so read on.</description>\n<pubDate>Fri, 19 Jan 2007 10:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/Changes_to_Daylight_Savings_Time_in_2007_may_affect_your_databases_DB2</guid>\n<digg:diggCount>618</digg:diggCount>\n<digg:submitter><digg:username>zaibatsu</digg:username><digg:userimage>http://digg.com/userimages/zaibatsu/medium9352.jpg</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>71</digg:commentCount>\n</item>\n<item>\n<title>Best Buy is fighting against &quot;devil shoppers&quot;, or *20%* of their customers!</title>\n<link>http://digg.com/tech_news/Best_Buy_is_fighting_against_devil_shoppers_or_20_of_their_customers</link>\n<description>&quot; These shoppers account for as many as one-fifth of Best Buy''s 500 million customer visits each year, and according to Best Buy CEO Brad Anderson, ''They can wreak enormous economic havoc.'' &quot;  But are the shoppers really the problem, or does the problem lay with Best Buy''s shoddy business practices?</description>\n<pubDate>Fri, 19 Jan 2007 10:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Best_Buy_is_fighting_against_devil_shoppers_or_20_of_their_customers</guid>\n<digg:diggCount>1287</digg:diggCount>\n<digg:submitter><digg:username>AriaStar</digg:username><digg:userimage>http://digg.com/userimages/a/r/i/ariastar/medium9611.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>238</digg:commentCount>\n</item>\n<item>\n<title>5 Freefonts You Shouldn’t Have Missed</title>\n<link>http://digg.com/design/5_Freefonts_You_Shouldn_t_Have_Missed</link>\n<description>We expand our collection of high-quality freefonts we’ve put together few months ago (and started one year ago). Some of newbies are quite old, however they haven’t been known for a while...</description>\n<pubDate>Fri, 19 Jan 2007 10:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/5_Freefonts_You_Shouldn_t_Have_Missed</guid>\n<digg:diggCount>1274</digg:diggCount>\n<digg:submitter><digg:username>Viewel</digg:username><digg:userimage>http://digg.com/userimages/v/i/e/viewel/medium4856.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>50</digg:commentCount>\n</item>\n<item>\n<title>Diggnation on the Cover of PC Mag! (Pic)</title>\n<link>http://digg.com/tech_news/Diggnation_on_the_Cover_of_PC_Mag_Pic</link>\n<description>The newest issue of PC Magazine has a small picture of Alex and Kevin from episode 75 along with a few other Internet celebrities. Although I don''t think they are mentioned in the featured article.</description>\n<pubDate>Fri, 19 Jan 2007 09:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Diggnation_on_the_Cover_of_PC_Mag_Pic</guid>\n<digg:diggCount>55</digg:diggCount>\n<digg:submitter><digg:username>souled</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>6</digg:commentCount>\n</item>\n<item>\n<title>187 Amazingly designed sites to see before you die.</title>\n<link>http://digg.com/design/187_Amazingly_designed_sites_to_see_before_you_die</link>\n<description>A compilation of some of the best designed websites of the Internet. Flash, CSS and static all appear as the creators flex the boundaries and conventions of design. Truly original work and concepts. You can preview the website before visiting, to save you loading times :)</description>\n<pubDate>Fri, 19 Jan 2007 09:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/187_Amazingly_designed_sites_to_see_before_you_die</guid>\n<digg:diggCount>146</digg:diggCount>\n<digg:submitter><digg:username>silentcollision</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>28</digg:commentCount>\n</item>\n<item>\n<title>I''ll Digg that for a Dollar!</title>\n<link>http://digg.com/tech_news/I_ll_Digg_that_for_a_Dollar</link>\n<description>Average salaries for unskilled labor in South Asia hover around $60/month (on the high end). That means $2.7/day and 34 cents/hour. Furthermore, creating multiple accounts on Digg (or any other socially driven content site), and using them to submit and promote content doesn’t require much skill.</description>\n<pubDate>Fri, 19 Jan 2007 09:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/I_ll_Digg_that_for_a_Dollar</guid>\n<digg:diggCount>661</digg:diggCount>\n<digg:submitter><digg:username>HMTKSteve</digg:username><digg:userimage>http://digg.com/userimages/hmtksteve/medium6844.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>55</digg:commentCount>\n</item>\n<item>\n<title>Microsoft: &quot;I''m going to send you the biggest damn box you''ve ever seen.&quot; </title>\n<link>http://digg.com/hardware/Microsoft_I_m_going_to_send_you_the_biggest_damn_box_you_ve_ever_seen</link>\n<description>Ever had to deal with Microsoft support? Has your xbox360 ever failed? Then you''ll want to help me send Microsoft the biggest damn box they''ve ever seen...</description>\n<pubDate>Fri, 19 Jan 2007 09:10:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Microsoft_I_m_going_to_send_you_the_biggest_damn_box_you_ve_ever_seen</guid>\n<digg:diggCount>340</digg:diggCount>\n<digg:submitter><digg:username>limpidezza</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>134</digg:commentCount>\n</item>\n<item>\n<title>The evolution of a web developer/designer</title>\n<link>http://digg.com/programming/The_evolution_of_a_web_developer_designer</link>\n<description>The popular &quot;The Evolution of a Programmer&quot; joke rewritten for web developers/web designers.</description>\n<pubDate>Fri, 19 Jan 2007 09:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/The_evolution_of_a_web_developer_designer</guid>\n<digg:diggCount>906</digg:diggCount>\n<digg:submitter><digg:username>picktwo</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>44</digg:commentCount>\n</item>\n<item>\n<title>Linus Torvalds: Windows Vista &quot;Over-hyped&quot;</title>\n<link>http://digg.com/software/Linus_Torvalds_Windows_Vista_Over_hyped</link>\n<description>With the imminent release of Windows Vista to consumers this month, Linus Torvalds, the father of Linux, has claimed Microsoft''s latest desktop effort is over-hyped and not a revolutionary advancement. &quot;I don''t actually think that something like Vista will change how people work that much,&quot; Torvalds told Computerworld.</description>\n<pubDate>Fri, 19 Jan 2007 09:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Linus_Torvalds_Windows_Vista_Over_hyped</guid>\n<digg:diggCount>835</digg:diggCount>\n<digg:submitter><digg:username>jrepin</digg:username><digg:userimage>http://digg.com/userimages/jrepin/medium.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>147</digg:commentCount>\n</item>\n<item>\n<title>AJAX Debugging with Firebug: web development is fun again!</title>\n<link>http://digg.com/programming/AJAX_Debugging_with_Firebug_web_development_is_fun_again</link>\n<description>The creator of Firebug shows that this Firefox extension is much more than just a JavaScript console: modify the page in place, explore and change all object properties, display invisible elements, monitor network requests, and a lot more (via Ajaxian)</description>\n<pubDate>Fri, 19 Jan 2007 07:40:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/AJAX_Debugging_with_Firebug_web_development_is_fun_again</guid>\n<digg:diggCount>560</digg:diggCount>\n<digg:submitter><digg:username>mklopez</digg:username><digg:userimage>http://digg.com/userimages/mklopez/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>30</digg:commentCount>\n</item>\n<item>\n<title>Sealand Wont be Sold to Pirates</title>\n<link>http://digg.com/tech_news/Sealand_Wont_be_Sold_to_Pirates</link>\n<description>His Royal Highness Prince Michael of Sealand said in an interview with ‘CBC The Hour’ that buying Sealand is probably a good way to circumvent international copyright law, but that he wont sell sealand to the Pirate Bay.</description>\n<pubDate>Fri, 19 Jan 2007 06:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Sealand_Wont_be_Sold_to_Pirates</guid>\n<digg:diggCount>1717</digg:diggCount>\n<digg:submitter><digg:username>thecahos1</digg:username><digg:userimage>http://digg.com/userimages/t/h/e/thecahos1/medium9714.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>134</digg:commentCount>\n</item>\n<item>\n<title>Geeks are cool, grab one before they run out</title>\n<link>http://digg.com/linux_unix/Geeks_are_cool_grab_one_before_they_run_out</link>\n<description>A funny article on how most geeks are a girls &quot;hidden diamond&quot;.</description>\n<pubDate>Fri, 19 Jan 2007 06:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Geeks_are_cool_grab_one_before_they_run_out</guid>\n<digg:diggCount>324</digg:diggCount>\n<digg:submitter><digg:username>geeshock</digg:username><digg:userimage>http://digg.com/userimages/g/e/e/geeshock/medium3126.jpg</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>69</digg:commentCount>\n</item>\n<item>\n<title>iPhone Price to Drop by 50%</title>\n<link>http://digg.com/apple/iPhone_Price_to_Drop_by_50</link>\n<description>Due to the huge margin''s Apple built into its new iPhone''s announced price, Apple analysts have announced that huge price cuts are coming.</description>\n<pubDate>Fri, 19 Jan 2007 06:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/iPhone_Price_to_Drop_by_50</guid>\n<digg:diggCount>273</digg:diggCount>\n<digg:submitter><digg:username>danzarrella</digg:username><digg:userimage>http://digg.com/userimages/d/a/n/danzarrella/medium7915.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>47</digg:commentCount>\n</item>\n<item>\n<title>Microsoft announces direct downloads of Vista, family licensing</title>\n<link>http://digg.com/software/Microsoft_announces_direct_downloads_of_Vista_family_licensing</link>\n<description>North American Windows users will be able to eschew physical media with Windows Vista. Microsoft will sell the OS online along with a limited-time family licensing program to upgrade multiple PCs with Vista Ultimate.</description>\n<pubDate>Fri, 19 Jan 2007 05:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Microsoft_announces_direct_downloads_of_Vista_family_licensing</guid>\n<digg:diggCount>776</digg:diggCount>\n<digg:submitter><digg:username>MrBabyMan</digg:username><digg:userimage>http://digg.com/userimages/mrbabyman/medium7859.gif</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>86</digg:commentCount>\n</item>\n<item>\n<title>53 CSS-Techniques You Couldn’t Live Without</title>\n<link>http://digg.com/design/53_CSS_Techniques_You_Couldn_t_Live_Without</link>\n<description>CSS-based techniques you should always have ready to hand if you develop web-sites. Thanks to all developers who contributed to accessible and usable css-based design over the last few years. We really appreciate it. Show some Love.</description>\n<pubDate>Fri, 19 Jan 2007 04:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/53_CSS_Techniques_You_Couldn_t_Live_Without</guid>\n<digg:diggCount>3309</digg:diggCount>\n<digg:submitter><digg:username>jummy</digg:username><digg:userimage>http://digg.com/userimages/j/u/m/jummy/medium4162.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>63</digg:commentCount>\n</item>\n<item>\n<title>The Largest Photoshop Brush Resource Site on The Internet</title>\n<link>http://digg.com/design/The_Largest_Photoshop_Brush_Resource_Site_on_The_Internet</link>\n<description>If you need Photoshop Brushes, this site has tons of them, in addition they''ve add a great Stock Photos section so you can find lots of free stock photos.</description>\n<pubDate>Fri, 19 Jan 2007 04:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/The_Largest_Photoshop_Brush_Resource_Site_on_The_Internet</guid>\n<digg:diggCount>1730</digg:diggCount>\n<digg:submitter><digg:username>zaibatsu</digg:username><digg:userimage>http://digg.com/userimages/zaibatsu/medium9352.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>27</digg:commentCount>\n</item>\n<item>\n<title>Photoshop : Create a fake software box</title>\n<link>http://digg.com/design/Photoshop_Create_a_fake_software_box</link>\n<description>Create a fake software box with photoshop that could be used if you are selling software programs or web scripts from a site but dont have an actual physical box that you can photograph.</description>\n<pubDate>Fri, 19 Jan 2007 04:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Photoshop_Create_a_fake_software_box</guid>\n<digg:diggCount>1514</digg:diggCount>\n<digg:submitter><digg:username>keepclear</digg:username><digg:userimage>http://digg.com/userimages/k/e/e/keepclear/medium1761.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>39</digg:commentCount>\n</item>\n<item>\n<title>Reader Mini - Browse Google Reader When You''re Mobile</title>\n<link>http://digg.com/software/Reader_Mini_Browse_Google_Reader_When_You_re_Mobile</link>\n<description>Reader Mini is a light-weight alternative to Google Reader. Reader Mini uses the Google Reader API to access your feeds.</description>\n<pubDate>Fri, 19 Jan 2007 03:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Reader_Mini_Browse_Google_Reader_When_You_re_Mobile</guid>\n<digg:diggCount>319</digg:diggCount>\n<digg:submitter><digg:username>digitalgopher</digg:username><digg:userimage>http://digg.com/userimages/digitalgopher/medium3133.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>22</digg:commentCount>\n</item>\n<item>\n<title>Google AdSense updates their policies</title>\n<link>http://digg.com/tech_news/Google_AdSense_updates_their_policies</link>\n<description>We have had the same Google AdSense policies in place since March 2006, and with recent changes being made to things such as how publishers can use images with their ad units an updated Google AdSense Policy wasn''t too far behind. The policies have now been updated and revamped with a new order and look, and with one very significant change.</description>\n<pubDate>Fri, 19 Jan 2007 03:00:05 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Google_AdSense_updates_their_policies</guid>\n<digg:diggCount>366</digg:diggCount>\n<digg:submitter><digg:username>DigiDave</digg:username><digg:userimage>http://digg.com/userimages/digidave/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>21</digg:commentCount>\n</item>\n<item>\n<title>Apple Special Event in February</title>\n<link>http://digg.com/apple/Apple_Special_Event_in_February_2</link>\n<description>It looks like when Steve Jobs said &quot;See you soon&quot; at the end of his annual MacWorld keynote, it appears that he literally meant it. Rumors have been floating around that an Apple Special Event will take place late next month or early March. The Special Event is supposedly going to reveal the &quot;Top Secret&quot; features of Leopard and also introduce the n</description>\n<pubDate>Fri, 19 Jan 2007 03:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Apple_Special_Event_in_February_2</guid>\n<digg:diggCount>81</digg:diggCount>\n<digg:submitter><digg:username>colonels1020</digg:username><digg:userimage>http://digg.com/userimages/c/o/l/colonels1020/medium1520.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>16</digg:commentCount>\n</item>\n</channel>\n</rss>', '20070119201707');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_flirts`
-- 

DROP TABLE IF EXISTS `dsb_flirts`;
CREATE TABLE `dsb_flirts` (
  `flirt_id` int(3) unsigned NOT NULL auto_increment,
  `flirt_text` text NOT NULL,
  PRIMARY KEY  (`flirt_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_flirts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_lang_keys`
-- 

DROP TABLE IF EXISTS `dsb_lang_keys`;
CREATE TABLE `dsb_lang_keys` (
  `lk_id` int(5) unsigned NOT NULL auto_increment,
  `lk_type` tinyint(1) unsigned NOT NULL default '0',
  `lk_diz` varchar(255) NOT NULL default '',
  `lk_use` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lk_id`),
  KEY `lk_use` (`lk_use`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_lang_keys`
-- 

INSERT INTO `dsb_lang_keys` (`lk_id`, `lk_type`, `lk_diz`, `lk_use`) VALUES (600, 2, 'Field value', 1),
(2, 2, '', 2),
(3, 2, '', 2),
(4, 2, '', 0),
(5, 2, '', 0),
(6, 2, '', 0),
(7, 2, '', 0),
(8, 2, '', 0),
(9, 2, '', 0),
(10, 2, '', 0),
(11, 2, '', 0),
(12, 2, '', 0),
(13, 2, '', 0),
(14, 2, '', 0),
(15, 2, '', 0),
(16, 2, '', 0),
(17, 2, '', 0),
(18, 2, '', 0),
(19, 2, '', 2),
(20, 2, '', 2),
(21, 2, '', 2),
(22, 2, '', 2),
(23, 2, '', 2),
(24, 2, '', 2),
(25, 2, '', 2),
(26, 2, '', 2),
(27, 2, '', 0),
(28, 2, '', 0),
(29, 2, '', 0),
(30, 2, '', 0),
(500, 2, 'Category name', 1),
(501, 2, 'Label for field_46 field', 1),
(502, 2, 'Search label for field_46 field', 1),
(503, 4, 'Help text for field_46 field', 1),
(504, 2, 'Label for field_47 field', 1),
(505, 2, 'Search label for field_47 field', 1),
(506, 4, 'Help text for field_47 field', 1),
(507, 2, 'Label for field_48 field', 1),
(508, 2, 'Search label for field_48 field', 1),
(509, 4, 'Help text for field_48 field', 1),
(518, 4, 'Help text for field_50 field', 1),
(517, 2, 'Search label for field_50 field', 1),
(516, 2, 'Label for field_50 field', 1),
(519, 2, 'Category name', 1),
(520, 2, 'Label for f51 field', 1),
(521, 2, 'Search label for f51 field', 1),
(522, 4, 'Help text for f51 field', 1),
(523, 2, 'Label for f52 field', 1),
(524, 2, 'Search label for f52 field', 1),
(525, 4, 'Help text for f52 field', 1),
(526, 2, 'Label for f53 field', 1),
(527, 2, 'Search label for f53 field', 1),
(528, 4, 'Help text for f53 field', 1),
(529, 2, 'Label for f54 field', 1),
(530, 2, 'Search label for f54 field', 1),
(531, 4, 'Help text for f54 field', 1),
(532, 2, 'Label for f55 field', 1),
(533, 2, 'Search label for f55 field', 1),
(534, 4, 'Help text for f55 field', 1),
(535, 2, 'Label for f56 field', 1),
(536, 2, 'Search label for f56 field', 1),
(537, 4, 'Help text for f56 field', 1),
(538, 2, 'Field value', 1),
(539, 2, 'Field value', 1),
(540, 2, 'Field value', 1),
(541, 2, 'Field value', 1),
(542, 2, 'Field value', 1),
(543, 2, 'Field value', 1),
(544, 2, 'Field value', 1),
(545, 2, 'Field value', 1),
(546, 2, 'Field value', 1),
(547, 2, 'Field value', 1),
(548, 2, 'Field value', 1),
(549, 2, 'Field value', 1),
(550, 2, 'Field value', 1),
(551, 2, 'Field value', 1),
(552, 2, 'Field value', 1),
(553, 2, 'Field value', 1),
(554, 2, 'Field value', 1),
(555, 2, 'Field value', 1),
(556, 2, 'Field value', 1),
(557, 2, 'Field value', 1),
(558, 2, 'Field value', 1),
(559, 2, 'Field value', 1),
(560, 2, 'Field value', 1),
(561, 2, 'Field value', 1),
(562, 2, 'Field value', 1),
(563, 2, 'Field value', 1),
(564, 2, 'Field value', 1),
(565, 2, 'Field value', 1),
(566, 2, 'Field value', 1),
(567, 2, 'Field value', 1),
(568, 2, 'Field value', 1),
(569, 2, 'Field value', 1),
(570, 2, 'Field value', 1),
(571, 2, 'Field value', 1),
(572, 2, 'Field value', 1),
(573, 2, 'Field value', 1),
(574, 2, 'Field value', 1),
(575, 2, 'Field value', 1),
(576, 2, 'Field value', 1),
(577, 2, 'Field value', 1),
(578, 2, 'Field value', 1),
(579, 2, 'Field value', 1),
(580, 2, 'Field value', 1),
(581, 2, 'Field value', 1),
(582, 2, 'Field value', 1),
(583, 2, 'Field value', 1),
(584, 2, 'Field value', 1),
(585, 2, 'Field value', 1),
(586, 2, 'Field value', 1),
(587, 2, 'Field value', 1),
(588, 2, 'Field value', 1),
(589, 2, 'Field value', 1),
(590, 2, 'Field value', 1),
(591, 2, 'Field value', 1),
(592, 2, 'Field value', 1),
(593, 2, 'Field value', 1),
(594, 2, 'Field value', 1),
(595, 2, 'Field value', 1),
(596, 2, 'Field value', 1),
(597, 2, 'Field value', 1),
(622, 2, 'Field value', 1),
(621, 2, 'Field value', 1),
(601, 2, 'Field value', 1),
(602, 2, 'Field value', 1),
(603, 2, 'Field value', 1),
(604, 2, 'Field value', 1),
(605, 2, 'Field value', 1),
(606, 2, 'Field value', 1),
(607, 2, 'Field value', 1),
(608, 2, 'Field value', 1),
(609, 2, 'Field value', 1),
(610, 2, 'Field value', 1),
(611, 2, 'Field value', 1),
(612, 2, 'Field value', 1),
(613, 2, 'Field value', 1),
(614, 2, 'Field value', 1),
(615, 2, 'Field value', 1),
(616, 2, 'Field value', 1),
(617, 2, 'Field value', 1),
(618, 2, 'Field value', 1),
(619, 2, 'Field value', 1),
(620, 2, 'Field value', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_lang_strings`
-- 

DROP TABLE IF EXISTS `dsb_lang_strings`;
CREATE TABLE `dsb_lang_strings` (
  `ls_id` int(11) unsigned NOT NULL auto_increment,
  `fk_lk_id` int(5) unsigned NOT NULL default '0',
  `skin` varchar(50) NOT NULL default '',
  `lang_value` text NOT NULL,
  PRIMARY KEY  (`ls_id`),
  UNIQUE KEY `thekey` (`fk_lk_id`,`skin`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_lang_strings`
-- 

INSERT INTO `dsb_lang_strings` (`ls_id`, `fk_lk_id`, `skin`, `lang_value`) VALUES (606, 601, 'skin_basic', '2m'),
(2, 2, 'skin_basic', 'We''re sorry but you tried to login too many times. Please wait for a while before trying that again.'),
(3, 3, 'skin_basic', 'We''re sorry but you don''t have access to this feature. --link to payment--'),
(4, 4, 'skin_basic', 'month'),
(5, 5, 'skin_basic', 'day'),
(6, 6, 'skin_basic', 'year'),
(7, 7, 'skin_basic', 'Jan'),
(8, 8, 'skin_basic', 'Feb'),
(9, 9, 'skin_basic', 'Mar'),
(10, 10, 'skin_basic', 'Apr'),
(11, 11, 'skin_basic', 'May'),
(12, 12, 'skin_basic', 'Jun'),
(13, 13, 'skin_basic', 'Jul'),
(14, 14, 'skin_basic', 'Aug'),
(15, 15, 'skin_basic', 'Sep'),
(16, 16, 'skin_basic', 'Oct'),
(17, 17, 'skin_basic', 'Nov'),
(18, 18, 'skin_basic', 'Dec'),
(19, 19, 'skin_basic', 'Invalid user name. Please use only letters and digits.'),
(20, 20, 'skin_basic', 'This account already exists. Please choose another one.'),
(21, 21, 'skin_basic', 'Password cannot be empty. Please enter your password.'),
(22, 22, 'skin_basic', 'Emails do not match. Please check the emails.'),
(23, 23, 'skin_basic', 'Invalid email entered. Please check your email.'),
(24, 24, 'skin_basic', 'The verification code doesn''t match. Please enter the new code.'),
(25, 25, 'skin_basic', 'The fields outlined below are required and must not be empty.'),
(500, 26, 'skin_basic', 'You must agree to the terms of services before joining the site.'),
(501, 27, 'skin_basic', ''),
(502, 28, 'skin_basic', ''),
(503, 29, 'skin_basic', ''),
(504, 30, 'skin_basic', ''),
(505, 500, 'skin_basic', 'Basic Info'),
(506, 501, 'skin_basic', 'Gender'),
(507, 502, 'skin_basic', 'Find'),
(508, 503, 'skin_basic', 'Help text to explain what is this field for.'),
(509, 504, 'skin_basic', 'Looking for'),
(510, 505, 'skin_basic', 'Looking for:'),
(511, 506, 'skin_basic', ''),
(512, 507, 'skin_basic', 'Date of birth'),
(513, 508, 'skin_basic', 'Age:'),
(514, 509, 'skin_basic', ''),
(523, 518, 'skin_basic', ''),
(522, 517, 'skin_basic', 'From:'),
(521, 516, 'skin_basic', 'Location'),
(524, 519, 'skin_basic', 'Physical Features'),
(525, 520, 'skin_basic', 'Height'),
(526, 521, 'skin_basic', 'Height:'),
(527, 522, 'skin_basic', 'Height is your height measured in meters when you stand up on your feet, with your back at 30 degrees from the vertical position. this is a very long comment.'),
(528, 523, 'skin_basic', 'Weight'),
(529, 524, 'skin_basic', 'Weight:'),
(530, 525, 'skin_basic', ''),
(531, 526, 'skin_basic', 'Constitution'),
(532, 527, 'skin_basic', 'Ssdf'),
(533, 528, 'skin_basic', ''),
(534, 529, 'skin_basic', 'Eyes'),
(535, 530, 'skin_basic', ''),
(536, 531, 'skin_basic', ''),
(537, 532, 'skin_basic', 'Favorite food'),
(538, 533, 'skin_basic', ''),
(539, 534, 'skin_basic', ''),
(540, 535, 'skin_basic', 'About me'),
(541, 536, 'skin_basic', ''),
(542, 537, 'skin_basic', 'Please enter a few words about you'),
(543, 538, 'skin_basic', 'pos1'),
(544, 539, 'skin_basic', 'ss'),
(545, 540, 'skin_basic', 'sdsd'),
(546, 541, 'skin_basic', 'dsdsd'),
(547, 542, 'skin_basic', 'dddd'),
(548, 543, 'skin_basic', 'dsd'),
(549, 544, 'skin_basic', 'dsd'),
(550, 545, 'skin_basic', 'ssss'),
(551, 546, 'skin_basic', 'aa'),
(552, 547, 'skin_basic', 'ssss'),
(553, 548, 'skin_basic', 's'),
(554, 549, 'skin_basic', 's'),
(555, 550, 'skin_basic', 's'),
(556, 551, 'skin_basic', 'd'),
(557, 552, 'skin_basic', 's'),
(558, 553, 'skin_basic', 'd'),
(559, 554, 'skin_basic', 'sdsd'),
(560, 555, 'skin_basic', 'd'),
(561, 556, 'skin_basic', 'd'),
(562, 557, 'skin_basic', 'a'),
(563, 558, 'skin_basic', 'a'),
(564, 559, 'skin_basic', 'sss'),
(565, 560, 'skin_basic', 'dd'),
(566, 561, 'skin_basic', 'ddd'),
(567, 562, 'skin_basic', 'dd'),
(568, 563, 'skin_basic', 'sss'),
(569, 564, 'skin_basic', 'sss'),
(570, 565, 'skin_basic', 'ss'),
(571, 566, 'skin_basic', 's'),
(572, 567, 'skin_basic', 's'),
(573, 568, 'skin_basic', 's'),
(574, 569, 'skin_basic', 'a'),
(575, 570, 'skin_basic', 'sd'),
(576, 571, 'skin_basic', 'd'),
(577, 572, 'skin_basic', 'asd'),
(578, 573, 'skin_basic', 'ss'),
(579, 574, 'skin_basic', 's'),
(580, 575, 'skin_basic', 'a'),
(581, 576, 'skin_basic', 's'),
(582, 577, 'skin_basic', 'a'),
(583, 578, 'skin_basic', 's'),
(584, 579, 'skin_basic', 's'),
(585, 580, 'skin_basic', 's'),
(586, 581, 'skin_basic', 'd'),
(587, 582, 'skin_basic', 's'),
(588, 583, 'skin_basic', 'a'),
(589, 584, 'skin_basic', 'ds'),
(590, 585, 'skin_basic', 'a'),
(591, 586, 'skin_basic', 's'),
(605, 600, 'skin_basic', '1m'),
(592, 587, 'basic', 's'),
(593, 588, 'basic', 'd'),
(594, 589, 'basic', 's'),
(595, 590, 'basic', 'd'),
(596, 591, 'basic', 'd'),
(597, 592, 'basic', 'sdsd'),
(598, 593, 'basic', 's'),
(599, 594, 'basic', 'dd'),
(600, 595, 'basic', 'd'),
(601, 596, 'skin_basic', 'asd1'),
(602, 597, 'skin_basic', 'dsa'),
(627, 622, 'skin_basic', 'Women'),
(626, 621, 'skin_basic', 'Man'),
(607, 602, 'skin_basic', '3m'),
(608, 603, 'skin_basic', 'Men'),
(609, 604, 'skin_basic', 'Women'),
(610, 605, 'skin_basic', '1kg'),
(611, 606, 'skin_basic', '2kg'),
(612, 607, 'skin_basic', '3kg'),
(613, 608, 'skin_basic', 'big'),
(614, 609, 'skin_basic', 'slim'),
(615, 610, 'skin_basic', 'petite'),
(616, 611, 'skin_basic', 'overweight'),
(617, 612, 'skin_basic', 'muscular'),
(618, 613, 'skin_basic', 'blue'),
(619, 614, 'skin_basic', 'green'),
(620, 615, 'skin_basic', 'grey'),
(621, 616, 'skin_basic', 'brown'),
(622, 617, 'skin_basic', 'american'),
(623, 618, 'skin_basic', 'mexican'),
(624, 619, 'skin_basic', 'indian'),
(625, 620, 'skin_basic', 'chinese');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_loc_countries`
-- 

DROP TABLE IF EXISTS `dsb_loc_countries`;
CREATE TABLE `dsb_loc_countries` (
  `country_id` int(10) unsigned NOT NULL auto_increment,
  `iso3166` char(2) NOT NULL default '',
  `country` varchar(200) NOT NULL default '',
  `prefered_input` enum('z','s') NOT NULL default 's',
  `num_states` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`country_id`),
  KEY `iso3166` (`iso3166`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_loc_countries`
-- 

INSERT INTO `dsb_loc_countries` (`country_id`, `iso3166`, `country`, `prefered_input`, `num_states`) VALUES (1, 'AF', 'Afghanistan', 's', 0),
(2, 'AL', 'Albania', 's', 0),
(3, 'AG', 'Algeria', 's', 0),
(4, 'AN', 'Andorra', 's', 0),
(5, 'AO', 'Angola', 's', 0),
(6, 'AV', 'Anguilla', 's', 0),
(7, 'AC', 'Antigua and Barbuda', 's', 0),
(8, 'AR', 'Argentina', 's', 0),
(9, 'AM', 'Armenia', 's', 0),
(10, 'AA', 'Aruba', 's', 0),
(11, 'AS', 'Australia', 's', 0),
(12, 'AU', 'Austria', 's', 0),
(13, 'AJ', 'Azerbaijan', 's', 0),
(14, 'BA', 'Bahrain', 's', 0),
(15, 'BG', 'Bangladesh', 's', 0),
(16, 'BB', 'Barbados', 's', 0),
(17, 'BO', 'Belarus', 's', 0),
(18, 'BE', 'Belgium', 's', 0),
(19, 'BH', 'Belize', 's', 0),
(20, 'BN', 'Benin', 's', 0),
(21, 'BD', 'Bermuda', 's', 0),
(22, 'BT', 'Bhutan', 's', 0),
(23, 'BL', 'Bolivia', 's', 0),
(24, 'BK', 'Bosnia and Herzegovina', 's', 0),
(25, 'BC', 'Botswana', 's', 0),
(26, 'BR', 'Brazil', 's', 0),
(27, 'VI', 'British Virgin Islands', 's', 0),
(28, 'BX', 'Brunei', 's', 0),
(29, 'BU', 'Bulgaria', 's', 0),
(30, 'UV', 'Burkina Faso', 's', 0),
(31, 'BY', 'Burundi', 's', 0),
(32, 'CB', 'Cambodia', 's', 0),
(33, 'CM', 'Cameroon', 's', 0),
(34, 'CA', 'Canada', 's', 0),
(35, 'CV', 'Cape Verde', 's', 0),
(36, 'CJ', 'Cayman Islands', 's', 0),
(37, 'CT', 'Central African Republic', 's', 0),
(38, 'CD', 'Chad', 's', 0),
(39, 'CI', 'Chile', 's', 0),
(40, 'CH', 'China', 's', 0),
(41, 'KT', 'Christmas Island', 's', 0),
(42, 'CK', 'Cocos (Keeling) Islands', 's', 0),
(43, 'CO', 'Colombia', 's', 0),
(44, 'CN', 'Comoros', 's', 0),
(45, 'CF', 'Congo (Brazzaville)', 's', 0),
(46, 'CG', 'Congo (Kinshasa)', 's', 0),
(47, 'CW', 'Cook Islands', 's', 0),
(48, 'CS', 'Costa Rica', 's', 0),
(49, 'IV', 'Cote D''Ivoire', 's', 0),
(50, 'HR', 'Croatia', 's', 0),
(51, 'CU', 'Cuba', 's', 0),
(52, 'CY', 'Cyprus', 's', 0),
(53, 'EZ', 'Czech Republic', 's', 0),
(54, 'DA', 'Denmark', 's', 0),
(55, 'DJ', 'Djibouti', 's', 0),
(56, 'DO', 'Dominica', 's', 0),
(57, 'DR', 'Dominican Republic', 's', 0),
(58, 'TT', 'East Timor', 's', 0),
(59, 'EC', 'Ecuador', 's', 0),
(60, 'EG', 'Egypt', 's', 0),
(61, 'ES', 'El Salvador', 's', 0),
(62, 'EK', 'Equatorial Guinea', 's', 0),
(63, 'ER', 'Eritrea', 's', 0),
(64, 'EN', 'Estonia', 's', 0),
(65, 'ET', 'Ethiopia', 's', 0),
(66, 'FK', 'Falkland Islands (Islas Malvinas)', 's', 0),
(67, 'FO', 'Faroe Islands', 's', 0),
(68, 'FJ', 'Fiji', 's', 0),
(69, 'FI', 'Finland', 's', 0),
(70, 'FR', 'France', 's', 0),
(71, 'FG', 'French Guiana', 's', 0),
(72, 'FP', 'French Polynesia', 's', 0),
(73, 'GB', 'Gabon', 's', 0),
(74, 'GZ', 'Gaza Strip', 's', 0),
(75, 'GG', 'Georgia', 's', 0),
(76, 'GM', 'Germany', 's', 0),
(77, 'GH', 'Ghana', 's', 0),
(78, 'GI', 'Gibraltar', 's', 0),
(79, 'GR', 'Greece', 's', 0),
(80, 'GL', 'Greenland', 's', 0),
(81, 'GJ', 'Grenada', 's', 0),
(82, 'GP', 'Guadeloupe', 's', 0),
(83, 'GT', 'Guatemala', 's', 0),
(84, 'GK', 'Guernsey', 's', 0),
(85, 'GV', 'Guinea', 's', 0),
(86, 'PU', 'Guinea-Bissau', 's', 0),
(87, 'GY', 'Guyana', 's', 0),
(88, 'HA', 'Haiti', 's', 0),
(89, 'HO', 'Honduras', 's', 0),
(90, 'HK', 'Hong Kong', 's', 0),
(91, 'HU', 'Hungary', 's', 0),
(92, 'IC', 'Iceland', 's', 0),
(93, 'IN', 'India', 's', 0),
(94, 'ID', 'Indonesia', 's', 0),
(95, 'IR', 'Iran', 's', 0),
(96, 'IZ', 'Iraq', 's', 0),
(97, 'EI', 'Ireland', 's', 0),
(98, 'IM', 'Isle of Man', 's', 0),
(99, 'IS', 'Israel', 's', 0),
(100, 'IT', 'Italy', 's', 0),
(101, 'JM', 'Jamaica', 's', 0),
(102, 'JA', 'Japan', 's', 0),
(103, 'JE', 'Jersey', 's', 0),
(104, 'JO', 'Jordan', 's', 0),
(105, 'KZ', 'Kazakhstan', 's', 0),
(106, 'KE', 'Kenya', 's', 0),
(107, 'KR', 'Kiribati', 's', 0),
(108, 'KU', 'Kuwait', 's', 0),
(109, 'KG', 'Kyrgyzstan', 's', 0),
(110, 'LA', 'Laos', 's', 0),
(111, 'LG', 'Latvia', 's', 0),
(112, 'LE', 'Lebanon', 's', 0),
(113, 'LT', 'Lesotho', 's', 0),
(114, 'LI', 'Liberia', 's', 0),
(115, 'LY', 'Libya', 's', 0),
(116, 'LS', 'Liechtenstein', 's', 0),
(117, 'LH', 'Lithuania', 's', 0),
(118, 'LU', 'Luxembourg', 's', 0),
(119, 'MC', 'Macau', 's', 0),
(120, 'MK', 'Macedonia', 's', 0),
(121, 'MA', 'Madagascar', 's', 0),
(122, 'MI', 'Malawi', 's', 0),
(123, 'MY', 'Malaysia', 's', 0),
(124, 'MV', 'Maldives', 's', 0),
(125, 'ML', 'Mali', 's', 0),
(126, 'MT', 'Malta', 's', 0),
(127, 'MB', 'Martinique', 's', 0),
(128, 'MR', 'Mauritania', 's', 0),
(129, 'MP', 'Mauritius', 's', 0),
(130, 'MF', 'Mayotte', 's', 0),
(131, 'MX', 'Mexico', 's', 0),
(132, 'MD', 'Moldova', 's', 0),
(133, 'MN', 'Monaco', 's', 0),
(134, 'MG', 'Mongolia', 's', 0),
(135, 'MH', 'Montserrat', 's', 0),
(136, 'MO', 'Morocco', 's', 0),
(137, 'MZ', 'Mozambique', 's', 0),
(138, 'BM', 'Myanmar (Burma)', 's', 0),
(139, 'WA', 'Namibia', 's', 0),
(140, 'NR', 'Nauru', 's', 0),
(141, 'NP', 'Nepal', 's', 0),
(142, 'NL', 'Netherlands', 's', 0),
(143, 'NT', 'Netherlands Antilles', 's', 0),
(144, 'NC', 'New Caledonia', 's', 0),
(145, 'NZ', 'New Zealand', 's', 0),
(146, 'NU', 'Nicaragua', 's', 0),
(147, 'NG', 'Niger', 's', 0),
(148, 'NI', 'Nigeria', 's', 0),
(149, 'NE', 'Niue', 's', 0),
(150, 'NF', 'Norfolk Island', 's', 0),
(151, 'KN', 'North Korea', 's', 0),
(152, 'NO', 'Norway', 's', 0),
(153, 'MU', 'Oman', 's', 0),
(154, 'PK', 'Pakistan', 's', 0),
(155, 'PM', 'Panama', 's', 0),
(156, 'PP', 'Papua New Guinea', 's', 0),
(157, 'PA', 'Paraguay', 's', 0),
(158, 'PE', 'Peru', 's', 0),
(159, 'RP', 'Philippines', 's', 0),
(160, 'PC', 'Pitcairn Islands', 's', 0),
(161, 'PL', 'Poland', 's', 0),
(162, 'PO', 'Portugal', 's', 0),
(163, 'QA', 'Qatar', 's', 0),
(164, 'RE', 'Reunion', 's', 0),
(165, 'RO', 'Romania', 's', 0),
(166, 'RS', 'Russia', 's', 0),
(167, 'RW', 'Rwanda', 's', 0),
(168, 'SH', 'Saint Helena', 's', 0),
(169, 'SC', 'Saint Kitts and Nevis', 's', 0),
(170, 'ST', 'Saint Lucia', 's', 0),
(171, 'SB', 'Saint Pierre and Miquelon', 's', 0),
(172, 'VC', 'Saint Vincent and the Grenadines', 's', 0),
(173, 'WS', 'Samoa', 's', 0),
(174, 'SM', 'San Marino', 's', 0),
(175, 'TP', 'Sao Tome and Principe', 's', 0),
(176, 'SA', 'Saudi Arabia', 's', 0),
(177, 'SG', 'Senegal', 's', 0),
(178, 'YI', 'Serbia and Montenegro', 's', 0),
(179, 'SE', 'Seychelles', 's', 0),
(180, 'SL', 'Sierra Leone', 's', 0),
(181, 'SN', 'Singapore', 's', 0),
(182, 'LO', 'Slovakia', 's', 0),
(183, 'SI', 'Slovenia', 's', 0),
(184, 'BP', 'Solomon Islands', 's', 0),
(185, 'SO', 'Somalia', 's', 0),
(186, 'SF', 'South Africa', 's', 0),
(187, 'SX', 'South Georgia and the South Sandw', 's', 0),
(188, 'KS', 'South Korea', 's', 0),
(189, 'SP', 'Spain', 's', 0),
(190, 'PG', 'Spratly Islands', 's', 0),
(191, 'CE', 'Sri Lanka', 's', 0),
(192, 'SU', 'Sudan', 's', 0),
(193, 'NS', 'Suriname', 's', 0),
(194, 'SV', 'Svalbard', 's', 0),
(195, 'WZ', 'Swaziland', 's', 0),
(196, 'SW', 'Sweden', 's', 0),
(197, 'SZ', 'Switzerland', 's', 0),
(198, 'SY', 'Syria', 's', 0),
(199, 'TW', 'Taiwan', 's', 0),
(200, 'TI', 'Tajikistan', 's', 0),
(201, 'TZ', 'Tanzania', 's', 0),
(202, 'TH', 'Thailand', 's', 0),
(203, 'BF', 'The Bahamas', 's', 0),
(204, 'GA', 'The Gambia', 's', 0),
(205, 'TO', 'Togo', 's', 0),
(206, 'TL', 'Tokelau', 's', 0),
(207, 'TN', 'Tonga', 's', 0),
(208, 'TD', 'Trinidad and Tobago', 's', 0),
(209, 'TS', 'Tunisia', 's', 0),
(210, 'TU', 'Turkey', 's', 0),
(211, 'TX', 'Turkmenistan', 's', 0),
(212, 'TK', 'Turks and Caicos Islands', 's', 0),
(213, 'TV', 'Tuvalu', 's', 0),
(214, 'UG', 'Uganda', 's', 0),
(215, 'UP', 'Ukraine', 's', 0),
(216, 'AE', 'United Arab Emirates', 's', 0),
(217, 'UK', 'United Kingdom', 's', 0),
(218, 'US', 'United States', 'z', 59),
(219, 'UY', 'Uruguay', 's', 0),
(220, 'UZ', 'Uzbekistan', 's', 0),
(221, 'NH', 'Vanuatu', 's', 0),
(222, 'VE', 'Venezuela', 's', 0),
(223, 'VM', 'Vietnam', 's', 0),
(224, 'WF', 'Wallis and Futuna', 's', 0),
(225, 'WE', 'West Bank', 's', 0),
(226, 'WI', 'Western Sahara', 's', 0),
(227, 'YM', 'Yemen', 's', 0),
(228, 'ZA', 'Zambia', 's', 0),
(229, 'ZI', 'Zimbabwe', 's', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_locales`
-- 

DROP TABLE IF EXISTS `dsb_locales`;
CREATE TABLE `dsb_locales` (
  `locale_id` int(4) unsigned NOT NULL auto_increment,
  `locale_name` varchar(100) NOT NULL default '',
  `codes` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`locale_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_locales`
-- 

INSERT INTO `dsb_locales` (`locale_id`, `locale_name`, `codes`) VALUES (1, 'Arabic (Algeria)', 'ar_DZ,arabic'),
(2, 'Arabic (Saudi Arabia)', 'ar_SA,arabic'),
(3, 'Bulgarian (Bulgaria)', 'bg_BG,bulgarian'),
(4, 'Chinese, Simplified (China)', 'zh_CN,chinese'),
(5, 'Chinese, Traditional (Hong Kong)', 'zh_HK,chinese'),
(6, 'Chinese, Traditional (Taiwan)', 'zh_TW,chinese'),
(7, 'Czech (Czech Republic)', 'cs_CZ,czech'),
(8, 'Danish (Denmark)', 'da_DK,danish'),
(9, 'Dutch (Netherlands)', 'nl_NL,dutch'),
(10, 'English (United Kingdom)', 'en_GB,english'),
(11, 'English (United States)', 'en_US,english'),
(12, 'Finnish (Finland)', 'fi_FI,finnish'),
(13, 'French (Canada)', 'fr_CA,french'),
(14, 'French (France)', 'fr_FR,french'),
(15, 'German (Germany)', 'de_DE,german'),
(16, 'Greek (Greece)', 'el_GR,greek'),
(17, 'Hebrew (Israel)', 'iw_IL,hebrew'),
(18, 'Hungarian (Hungary)', 'hu_HU,hungarian'),
(19, 'Icelandic (Iceland)', 'is_IS,icelandic'),
(20, 'Italian (Italy)', 'it_IT,italian'),
(21, 'Japanese (Japan)', 'ja_JP,japanese'),
(22, 'Korean (Korea)', 'ko_KR,korean'),
(23, 'Norwegian (Norway)', 'no_NO,norwegian'),
(24, 'Polish (Poland)', 'pl_PL,polish'),
(25, 'Portuguese (Brazil)', 'pt_BR,portuguese'),
(26, 'Portuguese (Portugal)', 'pt_PT,portuguese'),
(27, 'Romanian (Romania)', 'ro_RO,romanian'),
(28, 'Russian (Russia)', 'ru_RU,russian'),
(29, 'Serbocroatian (Croatia)', 'hr_HR,croatian'),
(30, 'Slovak (Slovakia)', 'sk_SK,slovak'),
(31, 'Slovene (Slovenia)', 'sl_SI,sloven'),
(32, 'Spanish (Argentina)', 'es_AR,spanish'),
(33, 'Spanish (Bolivia)', 'es_BO,spanish'),
(34, 'Spanish (Chile)', 'es_CL,spanish'),
(35, 'Spanish (Colombia)', 'es_CO,spanish'),
(36, 'Spanish (Costa Rica)', 'es_CR,spanish'),
(37, 'Spanish (Ecuador)', 'es_EC,spanish'),
(38, 'Spanish (El Salvador)', 'es_SV,spanish'),
(39, 'Spanish (Guatemala)', 'es_GT,spanish'),
(40, 'Spanish (Mexico)', 'es_MX,spanish'),
(41, 'Spanish (Nicaragua)', 'es_NI,spanish'),
(42, 'Spanish (Panama)', 'es_PA,spanish'),
(43, 'Spanish (Paraguay)', 'es_PY,spanish'),
(44, 'Spanish (Peru)', 'es_PE,spanish'),
(45, 'Spanish (Puerto Rico)', 'es_PR,spanish'),
(46, 'Spanish (Spain)', 'es_ES,spanish'),
(47, 'Spanish (Uruguay)', 'es_UY,spanish'),
(48, 'Spanish (Venezuela)', 'es_VE,spanish'),
(49, 'Swedish (Sweden)', 'sv_SE,spanish'),
(50, 'Thai (Thailand)', 'th_TH,thai'),
(51, 'Turkish (Turkey)', 'tr_TR,turkish');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_memberships`
-- 

DROP TABLE IF EXISTS `dsb_memberships`;
CREATE TABLE `dsb_memberships` (
  `m_id` int(2) unsigned NOT NULL auto_increment,
  `m_name` varchar(64) NOT NULL default '',
  `m_value` int(10) unsigned NOT NULL default '0',
  `is_custom` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`m_id`),
  UNIQUE KEY `m_name` (`m_name`),
  UNIQUE KEY `m_value` (`m_value`)
) TYPE=MyISAM PACK_KEYS=0 COMMENT='m_value must be uniq';

-- 
-- Dumping data for table `dsb_memberships`
-- 

INSERT INTO `dsb_memberships` (`m_id`, `m_name`, `m_value`, `is_custom`) VALUES (1, 'Non Members', 1, 0),
(2, 'Free Members', 2, 0),
(3, 'Paid Members', 4, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_message_filters`
-- 

DROP TABLE IF EXISTS `dsb_message_filters`;
CREATE TABLE `dsb_message_filters` (
  `filter_id` int(10) NOT NULL auto_increment,
  `filter_type` tinyint(2) NOT NULL default '1',
  `fk_user_id` int(10) NOT NULL default '0',
  `rule` int(10) NOT NULL default '0',
  `fk_folder_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`filter_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_message_filters`
-- 

INSERT INTO `dsb_message_filters` (`filter_id`, `filter_type`, `fk_user_id`, `rule`, `fk_folder_id`) VALUES (1, 1, 2, 2, -3);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_modules`
-- 

DROP TABLE IF EXISTS `dsb_modules`;
CREATE TABLE `dsb_modules` (
  `module_code` varchar(32) binary NOT NULL default '',
  `module_name` varchar(100) NOT NULL default '',
  `module_diz` text NOT NULL,
  `module_type` tinyint(1) unsigned NOT NULL default '0',
  `version` float(4,2) unsigned NOT NULL default '0.00',
  PRIMARY KEY  (`module_code`),
  KEY `module_type` (`module_type`)
) TYPE=MyISAM COMMENT='0-regular,1-pg,2-fraud,3-widg,4-skin';

-- 
-- Dumping data for table `dsb_modules`
-- 

INSERT INTO `dsb_modules` (`module_code`, `module_name`, `module_diz`, `module_type`, `version`) VALUES (0x636f7265, 'Basic features', '', 0, 1.00),
(0x636f72655f626c6f67, 'Blogs', '', 0, 1.00),
(0x636f72655f70686f746f, 'Photo Album', '', 0, 1.00),
(0x70617970616c, 'Paypal', '', 1, 1.00),
(0x74776f636865636b6f7574, '2CheckOut', 'Credit card payments', 1, 1.00),
(0x617574686f72697a655f6e6574, 'Authorize.net', '', 1, 1.00),
(0x6d61786d696e64, 'Maxmind', 'Fraud checking module. Compares credit card country with  buyer''s IP country.', 2, 1.00),
(0x736b696e5f6261736963, 'Basic', 'The first skin of the site', 4, 1.00);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_networks`
-- 

DROP TABLE IF EXISTS `dsb_networks`;
CREATE TABLE `dsb_networks` (
  `net_id` int(10) unsigned NOT NULL auto_increment,
  `network` varchar(100) NOT NULL default '',
  `is_bidi` tinyint(1) unsigned NOT NULL default '1',
  `max_users` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`net_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_networks`
-- 

INSERT INTO `dsb_networks` (`net_id`, `network`, `is_bidi`, `max_users`) VALUES (1, 'Friends', 1, 0),
(2, 'Family', 1, 0),
(3, 'Blocked Members', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_payments`
-- 

DROP TABLE IF EXISTS `dsb_payments`;
CREATE TABLE `dsb_payments` (
  `payment_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(32) NOT NULL default '',
  `gateway` varchar(32) NOT NULL default '',
  `fk_subscr_id` int(2) unsigned NOT NULL default '0',
  `is_recuring` tinyint(1) unsigned NOT NULL default '0',
  `gw_txn` varchar(30) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `country` varchar(200) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `street_address` varchar(255) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `phone` varchar(30) NOT NULL default '',
  `m_value_from` int(10) unsigned NOT NULL default '0',
  `m_value_to` int(10) unsigned NOT NULL default '0',
  `amount_paid` float(10,2) unsigned NOT NULL default '0.00',
  `paid_from` date NOT NULL default '0000-00-00',
  `paid_until` date NOT NULL default '0000-00-00',
  `is_suspect` tinyint(1) unsigned NOT NULL default '0',
  `suspect_reason` text NOT NULL,
  PRIMARY KEY  (`payment_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_payments`
-- 

INSERT INTO `dsb_payments` (`payment_id`, `fk_user_id`, `_user`, `gateway`, `fk_subscr_id`, `is_recuring`, `gw_txn`, `name`, `country`, `state`, `city`, `zip`, `street_address`, `email`, `phone`, `m_value_from`, `m_value_to`, `amount_paid`, `paid_from`, `paid_until`, `is_suspect`, `suspect_reason`) VALUES (1, 2, 'guest', 'paypal', 4, 0, '68K892680N420214D', 'Dan Caragea', 'United States', '', '', '', '', 'paypal@sco.ro', '', 2, 4, 100.00, '2006-12-12', '2006-12-13', 0, ''),
(2, 2, 'guest', 'paypal', 1, 0, '69X88270JS012512S', 'Dan Caragea', 'United States', '', '', '', '', 'paypal@sco.ro', '', 2, 4, 30.00, '2006-12-13', '2007-01-12', 0, ''),
(3, 1, 'dan', '', 0, 0, '', '', '', '', '', '', '', '', '', 2, 4, 0.00, '2007-01-23', '2007-02-22', 0, ''),
(4, 2, 'test', '', 0, 0, '', '', '', '', '', '', '', '', '', 4, 4, 0.00, '2007-01-23', '2007-02-22', 0, ''),
(5, 209, 'test2', '', 0, 0, '', '', '', '', '', '', '', '', '', 2, 4, 0.00, '2007-01-23', '2007-02-22', 0, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_photo_comments`
-- 

DROP TABLE IF EXISTS `dsb_photo_comments`;
CREATE TABLE `dsb_photo_comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `fk_photo_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `comment` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `fk_photo_id` (`fk_photo_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_photo_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_profile_categories`
-- 

DROP TABLE IF EXISTS `dsb_profile_categories`;
CREATE TABLE `dsb_profile_categories` (
  `pcat_id` int(5) unsigned NOT NULL auto_increment,
  `fk_lk_id_pcat` int(5) unsigned NOT NULL default '0',
  `access_level` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pcat_id`),
  KEY `access_level` (`access_level`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_profile_categories`
-- 

INSERT INTO `dsb_profile_categories` (`pcat_id`, `fk_lk_id_pcat`, `access_level`) VALUES (1, 500, 7),
(5, 519, 7);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_profile_fields`
-- 

DROP TABLE IF EXISTS `dsb_profile_fields`;
CREATE TABLE `dsb_profile_fields` (
  `pfield_id` int(5) unsigned NOT NULL auto_increment,
  `fk_lk_id_label` int(5) unsigned NOT NULL default '0',
  `html_type` tinyint(2) unsigned NOT NULL default '0',
  `searchable` tinyint(1) unsigned NOT NULL default '0',
  `search_type` tinyint(2) unsigned NOT NULL default '0',
  `fk_lk_id_search` int(5) unsigned NOT NULL default '0',
  `at_registration` tinyint(1) unsigned NOT NULL default '0',
  `reg_page` tinyint(2) unsigned NOT NULL default '1',
  `required` tinyint(1) unsigned NOT NULL default '0',
  `editable` tinyint(1) unsigned NOT NULL default '0',
  `visible` tinyint(1) unsigned NOT NULL default '0',
  `dbfield` varchar(32) binary NOT NULL default '',
  `fk_lk_id_help` int(5) unsigned NOT NULL default '0',
  `fk_pcat_id` int(5) unsigned NOT NULL default '0',
  `access_level` int(10) unsigned NOT NULL default '0',
  `accepted_values` text NOT NULL,
  `default_value` varchar(255) NOT NULL default '',
  `default_search` varchar(255) NOT NULL default '',
  `fn_on_change` varchar(100) NOT NULL default '',
  `order_num` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pfield_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_profile_fields`
-- 

INSERT INTO `dsb_profile_fields` (`pfield_id`, `fk_lk_id_label`, `html_type`, `searchable`, `search_type`, `fk_lk_id_search`, `at_registration`, `reg_page`, `required`, `editable`, `visible`, `dbfield`, `fk_lk_id_help`, `fk_pcat_id`, `access_level`, `accepted_values`, `default_value`, `default_search`, `fn_on_change`, `order_num`) VALUES (17, 501, 3, 1, 10, 502, 1, 1, 1, 1, 1, 0x6669656c645f3436, 503, 1, 0, '|621|622|', '|0|', '|1|', '', 1),
(18, 504, 10, 1, 10, 505, 1, 1, 1, 1, 1, 0x6669656c645f3437, 506, 1, 0, '|603|604|', '|1|', '|0|', '', 2),
(19, 507, 103, 1, 103, 508, 1, 1, 1, 1, 1, 0x6669656c645f3438, 509, 1, 0, '|1950|1989|', '|18|35|', '', '', 3),
(22, 516, 107, 1, 107, 517, 1, 1, 1, 1, 1, 0x6669656c645f3530, 518, 1, 0, '', '|218|', '', 'update_location', 4),
(23, 520, 3, 1, 3, 521, 0, 1, 0, 1, 1, 0x663531, 522, 5, 0, '|600|601|602|', '', '', '', 5),
(24, 523, 3, 1, 3, 524, 0, 1, 0, 1, 1, 0x663532, 525, 5, 0, '|605|606|607|', '', '', '', 6),
(25, 526, 3, 1, 3, 527, 0, 1, 0, 1, 1, 0x663533, 528, 5, 0, '|608|609|610|611|612|', '', '', '', 7),
(26, 529, 3, 0, 3, 530, 0, 1, 0, 1, 1, 0x663534, 531, 1, 0, '|613|614|615|616|', '', '', '', 8),
(27, 532, 10, 0, 3, 533, 0, 1, 0, 1, 1, 0x663535, 534, 1, 0, '|617|618|619|620|', '', '', '', 9),
(28, 535, 4, 0, 1, 536, 0, 1, 0, 1, 1, 0x663536, 537, 1, 0, '', '', '', '', 10);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_queue_email`
-- 

DROP TABLE IF EXISTS `dsb_queue_email`;
CREATE TABLE `dsb_queue_email` (
  `mail_id` int(10) unsigned NOT NULL auto_increment,
  `to` varchar(100) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_added` timestamp(14) NOT NULL,
  PRIMARY KEY  (`mail_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_queue_email`
-- 

INSERT INTO `dsb_queue_email` (`mail_id`, `to`, `subject`, `message_body`, `date_added`) VALUES (1, 'dan@rdsct.ro', 'Your profile was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', '20061222002332');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_queue_message`
-- 

DROP TABLE IF EXISTS `dsb_queue_message`;
CREATE TABLE `dsb_queue_message` (
  `mail_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_from` int(10) unsigned NOT NULL default '0',
  `_from` varchar(48) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_from`),
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_queue_message`
-- 

INSERT INTO `dsb_queue_message` (`mail_id`, `fk_user_id`, `fk_user_id_from`, `_from`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 1, 2, 'test', 'test subj', 'test body\r\n', '2006-11-02 11:54:47', 0),
(2, 1, 2, 'test', 'sdsd', 'asdasd', '2006-11-02 11:58:46', 0),
(3, 1, 2, 'test', 'sdsd', '[quote]asdasd[/quote]', '2006-11-03 21:00:19', 0),
(4, 1, 2, 'test', 'test subj', '[quote]test body\r\n[/quote]', '2006-11-03 21:01:24', 0),
(5, 2, 2, 'test', 'test subjasd', '\r\n[quote]test body\r\n[/quote]', '2006-11-04 11:07:26', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_rate_limiter`
-- 

DROP TABLE IF EXISTS `dsb_rate_limiter`;
CREATE TABLE `dsb_rate_limiter` (
  `rate_id` int(10) unsigned NOT NULL auto_increment,
  `fk_level_id` int(10) unsigned NOT NULL default '0',
  `m_value` int(10) unsigned NOT NULL default '0',
  `limit` int(5) unsigned NOT NULL default '0',
  `interval` int(10) unsigned NOT NULL default '0',
  `punishment` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rate_id`),
  KEY `thekey` (`fk_level_id`,`m_value`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_rate_limiter`
-- 

INSERT INTO `dsb_rate_limiter` (`rate_id`, `fk_level_id`, `m_value`, `limit`, `interval`, `punishment`) VALUES (1, 1, 1, 3, 1, 1),
(2, 1, 1, 10, 2, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_site_bans`
-- 

DROP TABLE IF EXISTS `dsb_site_bans`;
CREATE TABLE `dsb_site_bans` (
  `ban_id` int(5) unsigned NOT NULL auto_increment,
  `ban_type` tinyint(1) unsigned NOT NULL default '0',
  `what` varchar(32) NOT NULL default '',
  `reason` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ban_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_bans`
-- 

INSERT INTO `dsb_site_bans` (`ban_id`, `ban_type`, `what`, `reason`) VALUES (1, 2, 'asd', ''),
(2, 2, 'asd', ''),
(3, 2, 'asd', ''),
(4, 2, 'asd', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_site_log`
-- 

DROP TABLE IF EXISTS `dsb_site_log`;
CREATE TABLE `dsb_site_log` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `user` varchar(64) NOT NULL default '',
  `m_value` int(10) unsigned NOT NULL default '0',
  `fk_level_id` int(10) unsigned NOT NULL default '0',
  `ip` int(12) unsigned NOT NULL default '0',
  `time` timestamp(14) NOT NULL,
  PRIMARY KEY  (`log_id`),
  KEY `user` (`user`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_log`
-- 

INSERT INTO `dsb_site_log` (`log_id`, `fk_user_id`, `user`, `m_value`, `fk_level_id`, `ip`, `time`) VALUES (1, 0, 'test', 1, 1, 2130706433, '20070119201716');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_site_options3`
-- 

DROP TABLE IF EXISTS `dsb_site_options3`;
CREATE TABLE `dsb_site_options3` (
  `config_id` int(10) unsigned NOT NULL auto_increment,
  `config_option` varchar(50) binary NOT NULL default '',
  `config_value` varchar(100) NOT NULL default '',
  `config_diz` text NOT NULL,
  `option_type` tinyint(1) unsigned NOT NULL default '0',
  `fk_module_code` varchar(32) binary NOT NULL default '',
  PRIMARY KEY  (`config_id`),
  UNIQUE KEY `thekey` (`config_option`,`fk_module_code`)
) TYPE=MyISAM COMMENT='0-n/a,1-chkbox,2-tf,3-ta';

-- 
-- Dumping data for table `dsb_site_options3`
-- 

INSERT INTO `dsb_site_options3` (`config_id`, `config_option`, `config_value`, `config_diz`, `option_type`, `fk_module_code`) VALUES (1, 0x64626669656c645f696e646578, '57', 'The last index of the custom profile fields (field_xx)', 0, 0x636f7265),
(2, 0x7573655f63617074636861, '1', 'Use the dynamic image text to keep spam bots out?', 1, 0x636f7265),
(3, 0x6d616e75616c5f70726f66696c655f617070726f76616c, '0', 'New profiles or changes to existing profiles require manual approval from an administrator before being displayed on site?', 1, 0x636f7265),
(4, 0x646174655f666f726d6174, '%m %d, %Y', 'Default date format', 2, 0x636f7265),
(5, 0x74315f7769647468, '100', 'The width of the smalest thumbnail generated for each user photo', 2, 0x636f72655f70686f746f),
(6, 0x74325f7769647468, '500', 'The width of the larger thumbnail generated for each user photo', 2, 0x636f72655f70686f746f),
(7, 0x7069635f7769647468, '800', 'The maximum width of any picture uploaded by a member', 2, 0x636f72655f70686f746f),
(8, 0x6d616e75616c5f70686f746f5f617070726f76616c, '0', 'New uploaded photos require manual approval before being displayed on the site?', 1, 0x636f72655f70686f746f),
(9, 0x6d616e75616c5f626c6f675f617070726f76616c, '0', 'New blog posts or changes to existing posts require manual approval from an administrator before being displayed on site?', 1, 0x636f72655f626c6f67),
(10, 0x6d616e75616c5f636f6d5f617070726f76616c, '0', 'Comments to profiles, photos, blogs need approval from admin?', 1, 0x636f7265),
(11, 0x77617465726d61726b5f74657874, 'watermark text', 'The text to stamp the user photos with', 2, 0x636f72655f70686f746f),
(12, 0x77617465726d61726b5f746578745f636f6c6f72, 'FFFFFF', 'Color of the text watermark', 2, 0x636f72655f70686f746f),
(13, 0x6d6f64756c655f616374697665, '1', 'Module active?', 1, 0x70617970616c),
(14, 0x70617970616c5f656d61696c, 'dan@sco.ro', 'Your paypal email address', 2, 0x70617970616c),
(15, 0x6d6f64756c655f616374697665, '1', 'Is this module active?', 1, 0x74776f636865636b6f7574),
(16, 0x736964, '117760', 'Your 2co seller ID', 2, 0x74776f636865636b6f7574),
(17, 0x64656d6f5f6d6f6465, '1', 'Enable test mode? Don''t enable this on a live site!', 1, 0x74776f636865636b6f7574),
(18, 0x64656d6f5f6d6f6465, '1', 'Enable test mode? Don''t enable this on a live site!', 1, 0x70617970616c),
(19, 0x736563726574, 'terebentina', 'The secret word you set in your 2co account', 2, 0x74776f636865636b6f7574),
(20, 0x6c6963656e73655f6b6579, '1234', 'Your Maxmind license key', 2, 0x6d61786d696e64),
(21, 0x7573655f7175657565, '1', 'Use the message queue (recommended) or send the messages directly?', 1, 0x636f7265),
(22, 0x6d61696c5f66726f6d, 'dan@rdsct.ro', 'Email address to send emails from', 2, 0x636f7265),
(23, 0x6262636f64655f70726f66696c65, '1', 'Use BBcode in profile fields? (like about me, about you)', 1, 0x636f7265),
(24, 0x6262636f64655f636f6d6d656e7473, '1', 'Use BBcode in comments?', 1, 0x636f7265),
(25, 0x736b696e5f646972, 'basic', 'Skin folder name in the skins folder.', 0, 0x736b696e5f6261736963),
(26, 0x736b696e5f6e616d65, 'Basic', '', 0, 0x736b696e5f6261736963),
(27, 0x666b5f6c6f63616c655f6964, '11', '', 0, 0x736b696e5f6261736963),
(28, 0x69735f64656661756c74, '1', 'Is this skin the default site skin?', 0, 0x736b696e5f6261736963),
(32, 0x6d696e5f73697a65, '0', 'Minimum photo file size in bytes (use 0 for not limited).', 2, 0x636f72655f70686f746f),
(33, 0x6d61785f73697a65, '0', 'Maximum photo file size in bytes (use 0 for server default).', 2, 0x636f72655f70686f746f),
(34, 0x6262636f64655f6d657373616765, '1', 'Allow BBCode in member to member messages?', 1, 0x636f7265);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_site_searches`
-- 

DROP TABLE IF EXISTS `dsb_site_searches`;
CREATE TABLE `dsb_site_searches` (
  `search_md5` varchar(32) NOT NULL default '',
  `search_type` tinyint(2) unsigned NOT NULL default '0',
  `search` text NOT NULL,
  `results` text NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `date_posted` timestamp(14) NOT NULL,
  UNIQUE KEY `search_md5` (`search_md5`,`search_type`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_searches`
-- 

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES ('cfdc1aadf4017e675c47823917af056d', 2, 'a:1:{s:3:"uid";s:1:"2";}', '27,28,29,30,33,34,35,36,37,38', 0, '20061219134623'),
('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,209', 0, '20061219155258'),
('40cd750bba9870f18aada2478b24840a', 2, 'a:0:{}', '27,28,29,30,33,34,35,36,37,38', 0, '20061220160322'),
('8be66aca9a1a9003f72585c258c916a5', 1, 'a:1:{s:11:"acclevel_id";i:17;}', '', 2, '20061222113940'),
('a9ed76f7355b148cf3a870e8745aa764', 1, 'a:6:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:8:"field_47";a:1:{i:0;s:1:"2";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";s:16:"field_50_country";s:3:"218";}', '', 2, '20070106194507'),
('e097eb6165576ce811ea859b9df97549', 1, 'a:5:{s:11:"acclevel_id";i:17;s:8:"field_47";a:1:{i:0;s:1:"2";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";s:16:"field_50_country";s:3:"218";}', '', 2, '20070106194511'),
('8816b2c8b45df99a22880b00513d7867', 1, 'a:1:{s:5:"pstat";s:1:"5";}', '1', 0, '20070108165007');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_site_skins`
-- 

DROP TABLE IF EXISTS `dsb_site_skins`;
CREATE TABLE `dsb_site_skins` (
  `skin_id` int(5) unsigned NOT NULL auto_increment,
  `skin_code` varchar(50) NOT NULL default '',
  `skin_name` varchar(255) NOT NULL default '',
  `fk_locale_id` int(4) unsigned NOT NULL default '0',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `needs_regen` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`skin_id`),
  UNIQUE KEY `skin_name` (`skin_name`),
  KEY `skin_code` (`skin_code`),
  KEY `is_default` (`is_default`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_skins`
-- 

INSERT INTO `dsb_site_skins` (`skin_id`, `skin_code`, `skin_name`, `fk_locale_id`, `is_default`, `needs_regen`) VALUES (1, 'basic', 'Defaultz', 11, 1, 0),
(2, 'test', 'test', 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_subscriptions`
-- 

DROP TABLE IF EXISTS `dsb_subscriptions`;
CREATE TABLE `dsb_subscriptions` (
  `subscr_id` int(2) unsigned NOT NULL auto_increment,
  `subscr_name` varchar(200) NOT NULL default '',
  `subscr_diz` text NOT NULL,
  `price` float(10,2) unsigned NOT NULL default '0.00',
  `currency` char(3) NOT NULL default '',
  `is_recurent` tinyint(1) unsigned NOT NULL default '0',
  `m_value_from` int(10) unsigned NOT NULL default '0',
  `m_value_to` int(10) unsigned NOT NULL default '0',
  `duration` int(4) unsigned NOT NULL default '0',
  `duration_units` enum('D','M','Y') NOT NULL default 'D',
  `is_visible` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`subscr_id`),
  KEY `thekey` (`m_value_from`,`is_visible`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_subscriptions`
-- 

INSERT INTO `dsb_subscriptions` (`subscr_id`, `subscr_name`, `subscr_diz`, `price`, `currency`, `is_recurent`, `m_value_from`, `m_value_to`, `duration`, `duration_units`, `is_visible`) VALUES (1, '30$ / month', '', 30.00, 'USD', 0, 2, 4, 30, 'D', 1),
(3, 'Trial', '', 0.00, 'USD', 0, 2, 4, 5, 'D', 0),
(4, 'gold membership', 'this is the description for the gold membership which gives you unlimited access to all features for a couple of seconds', 100.00, 'USD', 0, 2, 4, 1, 'D', 1),
(5, 'bronze membership', 'ala bala portocala', 130.00, 'EUR', 0, 2, 4, 365, 'D', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_subscriptions_auto`
-- 

DROP TABLE IF EXISTS `dsb_subscriptions_auto`;
CREATE TABLE `dsb_subscriptions_auto` (
  `asubscr_id` int(3) unsigned NOT NULL auto_increment,
  `dbfield` varchar(32) NOT NULL default '',
  `field_value` int(5) NOT NULL default '0',
  `fk_subscr_id` int(2) unsigned NOT NULL default '0',
  `date_start` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`asubscr_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_subscriptions_auto`
-- 

INSERT INTO `dsb_subscriptions_auto` (`asubscr_id`, `dbfield`, `field_value`, `fk_subscr_id`, `date_start`) VALUES (1, '', 0, 3, '0000-00-00'),
(2, 'field_46', 2, 2, '0000-00-00'),
(4, 'field_46', 2, 1, '0000-00-00');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_accounts`
-- 

DROP TABLE IF EXISTS `dsb_user_accounts`;
CREATE TABLE `dsb_user_accounts` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(32) binary NOT NULL default '',
  `pass` varchar(32) binary NOT NULL default '',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `membership` int(10) unsigned NOT NULL default '0',
  `last_visit` datetime NOT NULL default '0000-00-00 00:00:00',
  `email` varchar(128) NOT NULL default '',
  `skin` varchar(32) NOT NULL default '',
  `temp_pass` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user` (`user`)
) TYPE=MyISAM COMMENT='membership is m_value';

-- 
-- Dumping data for table `dsb_user_accounts`
-- 

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `last_visit`, `email`, `skin`, `temp_pass`) VALUES (1, 0x64616e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, '0000-00-00 00:00:00', 'dan@sco.ro', '', ''),
(2, 0x74657374, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, '0000-00-00 00:00:00', 'dan@rdsct.ro', 'basic', ''),
(209, 0x7465737432, 0x3662343238383630323064303630386435646138373431633464353564303563, 5, 4, '0000-00-00 00:00:00', 'dan@rdsct.ro', '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_cache`
-- 

DROP TABLE IF EXISTS `dsb_user_cache`;
CREATE TABLE `dsb_user_cache` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `skin` varchar(32) NOT NULL default '',
  `part` varchar(48) NOT NULL default '',
  `cache` text NOT NULL,
  KEY `thekey` (`fk_user_id`,`skin`,`part`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_cache`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_fav_links`
-- 

DROP TABLE IF EXISTS `dsb_user_fav_links`;
CREATE TABLE `dsb_user_fav_links` (
  `flink_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `link` varchar(255) NOT NULL default '',
  `notes` varchar(255) NOT NULL default '',
  `is_private` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`flink_id`),
  KEY `index1` (`fk_user_id`,`is_private`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_fav_links`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_folders`
-- 

DROP TABLE IF EXISTS `dsb_user_folders`;
CREATE TABLE `dsb_user_folders` (
  `folder_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `folder` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`folder_id`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_folders`
-- 

INSERT INTO `dsb_user_folders` (`folder_id`, `fk_user_id`, `folder`) VALUES (2, 2, 'Salvate');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_inbox`
-- 

DROP TABLE IF EXISTS `dsb_user_inbox`;
CREATE TABLE `dsb_user_inbox` (
  `mail_id` int(10) unsigned NOT NULL auto_increment,
  `is_read` tinyint(1) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_other` int(10) unsigned NOT NULL default '0',
  `_user_other` varchar(32) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  `fk_folder_id` int(10) unsigned NOT NULL default '0',
  `del` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_other`),
  KEY `user_id_2` (`fk_user_id`,`fk_folder_id`,`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_inbox`
-- 

INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES (1, 1, 2, 2, 'test', 'test subj', 'test body\r\n', '2006-11-02 11:54:47', 0, 0, 0),
(2, 1, 2, 2, 'test', 'sdsd', 'asdasd', '2006-11-02 11:58:46', 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_mtpls`
-- 

DROP TABLE IF EXISTS `dsb_user_mtpls`;
CREATE TABLE `dsb_user_mtpls` (
  `mtpl_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  PRIMARY KEY  (`mtpl_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_mtpls`
-- 

INSERT INTO `dsb_user_mtpls` (`mtpl_id`, `fk_user_id`, `subject`, `message_body`) VALUES (1, 2, 'sdsd', 'asd'),
(2, 2, 'sdsd', 'dsa'),
(3, 2, 'sdsd', 'zxc');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_networks`
-- 

DROP TABLE IF EXISTS `dsb_user_networks`;
CREATE TABLE `dsb_user_networks` (
  `nconn_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_net_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_friend` int(10) unsigned NOT NULL default '0',
  `nconn_status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`nconn_id`),
  KEY `index1` (`fk_user_id`,`fk_net_id`,`nconn_status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_networks`
-- 

INSERT INTO `dsb_user_networks` (`nconn_id`, `fk_user_id`, `fk_net_id`, `fk_user_id_friend`, `nconn_status`) VALUES (1, 2, 1, 1, 1),
(2, 2, 1, 2, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_outbox`
-- 

DROP TABLE IF EXISTS `dsb_user_outbox`;
CREATE TABLE `dsb_user_outbox` (
  `mail_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_other` int(10) unsigned NOT NULL default '0',
  `_user_other` varchar(32) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_other`),
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_outbox`
-- 

INSERT INTO `dsb_user_outbox` (`mail_id`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 2, 2, 'test', 'Outbox test message', 'This is an outbox test message.', '2007-01-12 17:00:00', 0),
(4, 2, 2, 'test', 'test subj', 'mamma mia\r\n\r\n[quote]test body\r\n[/quote]', '2007-01-15 19:48:40', 0),
(5, 2, 2, 'test', 'Re: sdsd', '\r\n[quote]asdasd[/quote]', '2007-01-15 19:51:25', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_photos`
-- 

DROP TABLE IF EXISTS `dsb_user_photos`;
CREATE TABLE `dsb_user_photos` (
  `photo_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `photo` varchar(128) NOT NULL default '',
  `is_main` tinyint(1) unsigned NOT NULL default '0',
  `is_private` tinyint(1) unsigned NOT NULL default '0',
  `allow_comments` tinyint(1) unsigned NOT NULL default '0',
  `caption` varchar(255) NOT NULL default '',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `reject_reason` text NOT NULL,
  `stat_views` int(10) unsigned NOT NULL default '0',
  `stat_comments` int(5) unsigned NOT NULL default '0',
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `del` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`photo_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `is_main` (`is_main`),
  KEY `is_private` (`is_private`),
  KEY `del` (`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_photos`
-- 

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `caption`, `status`, `reject_reason`, `stat_views`, `stat_comments`, `date_posted`, `last_changed`, `del`) VALUES (41, 2, 'test', '2/2_31168263188.jpg', 0, 0, 1, 'a boat and a lake', 15, '', 0, 0, '2007-01-08 13:33:17', '2007-01-08 13:34:04', 0),
(40, 2, 'test', '9/2_21168263188.jpg', 1, 0, 1, 'the beeeee', 15, '', 0, 0, '2007-01-08 13:33:17', '2007-01-08 13:34:04', 0),
(39, 2, 'test', '8/2_11168263188.jpg', 0, 1, 0, 'ground girl', 15, '', 0, 0, '2007-01-08 13:33:17', '2007-01-08 13:34:04', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_profiles`
-- 

DROP TABLE IF EXISTS `dsb_user_profiles`;
CREATE TABLE `dsb_user_profiles` (
  `profile_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `reject_reason` text NOT NULL,
  `_user` varchar(32) NOT NULL default '',
  `_photo` varchar(128) NOT NULL default '',
  `longitude` float(20,10) NOT NULL default '0.0000000000',
  `latitude` float(20,10) NOT NULL default '0.0000000000',
  `score` int(5) unsigned NOT NULL default '0',
  `del` tinyint(1) unsigned NOT NULL default '0',
  `field_46` int(5) NOT NULL default '0',
  `field_47` text NOT NULL,
  `field_48` date default NULL,
  `field_50_country` int(3) NOT NULL default '0',
  `field_50_state` int(10) NOT NULL default '0',
  `field_50_city` int(10) NOT NULL default '0',
  `field_50_zip` varchar(10) NOT NULL default '',
  `f51` int(5) NOT NULL default '0',
  `f52` int(5) NOT NULL default '0',
  `f53` int(5) NOT NULL default '0',
  `f54` int(5) NOT NULL default '0',
  `f55` text NOT NULL,
  `f56` text NOT NULL,
  PRIMARY KEY  (`profile_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `_user` (`_user`),
  KEY `status` (`status`),
  KEY `del` (`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_profiles`
-- 

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `last_changed`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `del`, `field_46`, `field_47`, `field_48`, `field_50_country`, `field_50_state`, `field_50_city`, `field_50_zip`, `f51`, `f52`, `f53`, `f54`, `f55`, `f56`) VALUES (1, 1, 15, '2007-01-08 16:50:11', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'dan', '', 0.0000000000, 0.0000000000, 0, 0, 0, '', NULL, 0, 0, 0, '', 0, 0, 0, 0, '', ''),
(3, 2, 15, '2007-01-08 15:14:00', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test', '9/2_21168263188.jpg', -93.6367034912, 42.0276985168, 0, 0, 1, '|2|', '1976-11-01', 218, 16, 7089, '50010', 0, 0, 0, 0, '', 'Please enter a few\r\n words about you.'),
(620, 209, 10, '2006-12-22 00:23:32', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test2', '', 0.0000000000, 0.0000000000, 0, 0, 2, '|1|', '1981-04-05', 206, 0, 0, '', 0, 0, 0, 0, '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_searches`
-- 

DROP TABLE IF EXISTS `dsb_user_searches`;
CREATE TABLE `dsb_user_searches` (
  `search_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `search_qs` text NOT NULL,
  `alert` tinyint(1) unsigned NOT NULL default '0',
  `alert_last_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`search_id`),
  KEY `key1` (`fk_user_id`,`is_default`),
  KEY `alert` (`alert`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_searches`
-- 

INSERT INTO `dsb_user_searches` (`search_id`, `fk_user_id`, `title`, `is_default`, `search_qs`, `alert`, `alert_last_id`) VALUES (2, 2, 'rrr', 0, 'acclevel_id=17&field_46=0&field_47%5B0%5D=2&field_48_min=18&field_48_max=35&field_50_country=218&field_50_zip=&field_50_dist=1', 1, 0),
(3, 2, '3', 0, 'field_46=0&field_47=0&field_48_min=0&field_48_max=0&field_50_country=0', 1, 0),
(4, 2, '4', 0, 'st=adv&field_46=0&field_47%5B0%5D=2&field_48_min=18&field_48_max=35&field_50_country=218&field_50_zip=&field_50_dist=1', 1, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_settings`
-- 

DROP TABLE IF EXISTS `dsb_user_settings`;
CREATE TABLE `dsb_user_settings` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `email_send_news` tinyint(1) unsigned NOT NULL default '0',
  `email_send_alerts` tinyint(1) unsigned NOT NULL default '0',
  `email_send_matches` tinyint(1) unsigned NOT NULL default '0',
  `send_matches_interval` int(3) unsigned NOT NULL default '0',
  `album_private` tinyint(1) unsigned NOT NULL default '0',
  `feature_me` tinyint(1) unsigned NOT NULL default '0',
  `outside_offers` tinyint(1) unsigned NOT NULL default '0',
  `rate_me` tinyint(1) unsigned NOT NULL default '0',
  `_last_matches_sent` date NOT NULL default '0000-00-00',
  `photo_pass` varchar(32) NOT NULL default '',
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_settings`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_settings2`
-- 

DROP TABLE IF EXISTS `dsb_user_settings2`;
CREATE TABLE `dsb_user_settings2` (
  `config_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `config_option` varchar(50) binary NOT NULL default '',
  `config_value` varchar(100) NOT NULL default '',
  `config_diz` text NOT NULL,
  `option_type` tinyint(1) unsigned NOT NULL default '0',
  `fk_module_code` varchar(32) binary NOT NULL default '',
  PRIMARY KEY  (`config_id`),
  UNIQUE KEY `thekey` (`config_option`,`fk_module_code`,`fk_user_id`)
) TYPE=MyISAM COMMENT='0-checkbox, 1-text';

-- 
-- Dumping data for table `dsb_user_settings2`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_spambox`
-- 

DROP TABLE IF EXISTS `dsb_user_spambox`;
CREATE TABLE `dsb_user_spambox` (
  `mail_id` int(10) unsigned NOT NULL auto_increment,
  `is_read` tinyint(1) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_other` int(10) unsigned NOT NULL default '0',
  `_user_other` varchar(32) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_other`),
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_spambox`
-- 

INSERT INTO `dsb_user_spambox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 1, 2, 2, 'test', 'Spam test message', 'This is a spam message test.', '2007-01-12 19:00:00', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_stats`
-- 

DROP TABLE IF EXISTS `dsb_user_stats`;
CREATE TABLE `dsb_user_stats` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `stat` varchar(50) NOT NULL default '',
  `value` float(10,2) unsigned NOT NULL default '0.00',
  `fk_module_code` varchar(32) binary NOT NULL default '',
  UNIQUE KEY `thekey` (`fk_user_id`,`stat`,`fk_module_code`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_stats`
-- 

