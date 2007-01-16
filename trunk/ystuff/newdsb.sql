-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 16, 2007 at 07:03 PM
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
-- Table structure for table `dsb_auto_subscriptions`
-- 

DROP TABLE IF EXISTS `dsb_auto_subscriptions`;
CREATE TABLE `dsb_auto_subscriptions` (
  `asubscr_id` int(3) unsigned NOT NULL auto_increment,
  `dbfield` varchar(32) NOT NULL default '',
  `field_value` int(5) NOT NULL default '0',
  `fk_subscr_id` int(2) unsigned NOT NULL default '0',
  `date_start` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`asubscr_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_auto_subscriptions`
-- 

INSERT INTO `dsb_auto_subscriptions` (`asubscr_id`, `dbfield`, `field_value`, `fk_subscr_id`, `date_start`) VALUES (1, '', 0, 3, '0000-00-00'),
(2, 'field_46', 2, 2, '0000-00-00'),
(4, 'field_46', 2, 1, '0000-00-00');

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

INSERT INTO `dsb_feed_cache` (`feed_url`, `feed_xml`, `update_time`) VALUES (0x687474703a2f2f646967672e636f6d2f7273732f636f6e7461696e6572746563686e6f6c6f67792e786d6c, '<?xml version="1.0" encoding="UTF-8"?>\n<rss version="2.0" xmlns:digg="http://digg.com/docs/diggrss/">\n<channel>\n<title>Digg / Technology</title>\n<language>en-us</language><link>http://digg.com/view/technology</link>\n<description>Digg / Technology</description>\n<item>\n<title>Dell Sues Its Own Affiliates - Outrage or Just Cause?</title>\n<link>http://digg.com/tech_news/Dell_Sues_Its_Own_Affiliates_Outrage_or_Just_Cause</link>\n<description>Dell has sued the registrants of the domain names d3ell.com, de3LL.com, d4ell.com, de4ll.com, dedll.com, derll.com and dxell.com. The owners of these domains had redirected them to Dell to earn affiliate commissions. Dell made some sales, paid out the commissions due, and now sues to get their money back... plus the domains, plus &quot;damages&quot;!</description>\n<pubDate>Tue, 16 Jan 2007 16:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Dell_Sues_Its_Own_Affiliates_Outrage_or_Just_Cause</guid>\n<digg:diggCount>48</digg:diggCount>\n<digg:submitter><digg:username>iPorthos</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>19</digg:commentCount>\n</item>\n<item>\n<title>Fluendo makes proprietary codecs available to Linux users</title>\n<link>http://digg.com/linux_unix/Fluendo_makes_proprietary_codecs_available_to_Linux_users</link>\n<description>Open source media software development company Fluendo is now selling native proprietary media codecs--like Windows Media--for the Linux platform.</description>\n<pubDate>Tue, 16 Jan 2007 16:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Fluendo_makes_proprietary_codecs_available_to_Linux_users</guid>\n<digg:diggCount>30</digg:diggCount>\n<digg:submitter><digg:username>ReindeerFlotila</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>7</digg:commentCount>\n</item>\n<item>\n<title>SUSE versus Ubuntu: a side-by-side comparison.</title>\n<link>http://digg.com/linux_unix/SUSE_versus_Ubuntu_a_side_by_side_comparison</link>\n<description>This is a side-by-side comparison of the SUSE and Ubuntu distributions of GNU/Linux. It covers factors like: the general features of each, supported architectures, hardware requirements, the installation procedure, package management systems, configuration, efficiency, stability, security, and internationalization.</description>\n<pubDate>Tue, 16 Jan 2007 16:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/SUSE_versus_Ubuntu_a_side_by_side_comparison</guid>\n<digg:diggCount>74</digg:diggCount>\n<digg:submitter><digg:username>jenkins86</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>9</digg:commentCount>\n</item>\n<item>\n<title>Home Building Robots To Automate Home Construction</title>\n<link>http://digg.com/tech_news/Home_Building_Robots_To_Automate_Home_Construction</link>\n<description>A robot has been developed to automate the home building process.&quot;The operation is akin to a 3D inkjet printer, with the robot moving about in three dimensional space, spraying out the home layer by layer. Part of the simplicity of the process comes from the simplicity of the materials: nearly the whole house is built with concrete and gypsum.&quot;</description>\n<pubDate>Tue, 16 Jan 2007 16:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Home_Building_Robots_To_Automate_Home_Construction</guid>\n<digg:diggCount>119</digg:diggCount>\n<digg:submitter><digg:username>gamebittk</digg:username><digg:userimage>http://digg.com/userimages/g/a/m/gamebittk/medium9939.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>32</digg:commentCount>\n</item>\n<item>\n<title>Windows Vista Now Avalable!</title>\n<link>http://digg.com/software/Windows_Vista_Now_Avalable</link>\n<description>It appears that Windows Vista is now available for sale. (scroll to Operating System): Windows Vista Home Basic 64-bit, Windows Vista Home Premium 64-bit, Windows Vista Business 64-bit and Windows Vista Ultimate 64-bit</description>\n<pubDate>Tue, 16 Jan 2007 16:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Windows_Vista_Now_Avalable</guid>\n<digg:diggCount>45</digg:diggCount>\n<digg:submitter><digg:username>savingadvice</digg:username><digg:userimage>http://digg.com/userimages/s/a/v/savingadvice/medium3995.jpg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>33</digg:commentCount>\n</item>\n<item>\n<title>First Pirated HD-DVD is loose</title>\n<link>http://digg.com/security/First_Pirated_HD_DVD_is_loose</link>\n<description>It was only a matter of time. Sadly, the recording and film industry will never learn and will keep touting the future generations of DRM as &quot;THE&quot; thing that will keep their content &quot;secure.&quot;  *Breeeech* Excuse me.</description>\n<pubDate>Tue, 16 Jan 2007 16:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/First_Pirated_HD_DVD_is_loose</guid>\n<digg:diggCount>389</digg:diggCount>\n<digg:submitter><digg:username>BoogerRed</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>58</digg:commentCount>\n</item>\n<item>\n<title>Interview with Macworld''s Jason Snell</title>\n<link>http://digg.com/apple/Interview_with_Macworld_s_Jason_Snell</link>\n<description>Macworld Expo/Conference has come and gone, and Jason Snell attended [of course], so I asked Jason to answer a few questions about the Expo and his experience, it''s a good read.</description>\n<pubDate>Tue, 16 Jan 2007 16:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Interview_with_Macworld_s_Jason_Snell</guid>\n<digg:diggCount>77</digg:diggCount>\n<digg:submitter><digg:username>OSXTiger</digg:username><digg:userimage>http://digg.com/userimages/osxtiger/medium7087.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>5</digg:commentCount>\n</item>\n<item>\n<title>Macwelt gets more iPhone details out of Apple</title>\n<link>http://digg.com/apple/Macwelt_gets_more_iPhone_details_out_of_Apple</link>\n<description>Macwelt, Macworld’s German Mac-loving cousins, spent some time talking to Apple Germany and managed to get some new details about the implementation of the iPhone and about what you will and will not be able to do with the iPhone. Passing the article through Babelfish, we were able to get at the general nuggets of information, but if any of our Ger</description>\n<pubDate>Tue, 16 Jan 2007 15:30:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Macwelt_gets_more_iPhone_details_out_of_Apple</guid>\n<digg:diggCount>241</digg:diggCount>\n<digg:submitter><digg:username>Brajeshwar</digg:username><digg:userimage>http://digg.com/userimages/brajeshwar/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>46</digg:commentCount>\n</item>\n<item>\n<title>Youtux.org - VirtualBox Goes Open Source</title>\n<link>http://digg.com/linux_unix/Youtux_org_VirtualBox_Goes_Open_Source</link>\n<description>VirtualBox is a family of powerful x86 virtualization products for enterprise as well as home use. Not only is VirtualBox an extremely feature rich, high performance product for enterprise customers, it is also the only professional solution that is freely available as Open Source Software under the terms of the GNU Public License (GPL).</description>\n<pubDate>Tue, 16 Jan 2007 14:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Youtux_org_VirtualBox_Goes_Open_Source</guid>\n<digg:diggCount>296</digg:diggCount>\n<digg:submitter><digg:username>rdanays</digg:username><digg:userimage>http://digg.com/userimages/r/d/a/rdanays/medium5579.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>22</digg:commentCount>\n</item>\n<item>\n<title>Mark Spencer Presents AsteriskNOW</title>\n<link>http://digg.com/linux_unix/Mark_Spencer_Presents_AsteriskNOW</link>\n<description>AsteriskNOW is the easiest way to get started with Asterisk, the open source PBX for VoIP and traditional telephony.  Mark Spencer, the original author and founder of Digium, presents AsteriskNow which is available as an install disk or live CD.</description>\n<pubDate>Tue, 16 Jan 2007 14:20:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Mark_Spencer_Presents_AsteriskNOW</guid>\n<digg:diggCount>378</digg:diggCount>\n<digg:submitter><digg:username>mspice</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>29</digg:commentCount>\n</item>\n<item>\n<title>Joost - The new name for &quot;The Venice Project&quot;</title>\n<link>http://digg.com/tech_news/Joost_The_new_name_for_The_Venice_Project</link>\n<description>The Venice Project gets a name and a website makeover. Check it out...</description>\n<pubDate>Tue, 16 Jan 2007 13:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Joost_The_new_name_for_The_Venice_Project</guid>\n<digg:diggCount>58</digg:diggCount>\n<digg:submitter><digg:username>cinnix</digg:username><digg:userimage>http://digg.com/userimages/c/i/n/cinnix/medium6834.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>5</digg:commentCount>\n</item>\n<item>\n<title>Top 10 Things I''ve Learned About Computers From The Movies and Any Episode </title>\n<link>http://digg.com/hardware/Top_10_Things_I_ve_Learned_About_Computers_From_The_Movies_and_Any_Episode</link>\n<description>This is perfect -- stuff we all notice and laugh at when watching computers on TV</description>\n<pubDate>Tue, 16 Jan 2007 13:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Top_10_Things_I_ve_Learned_About_Computers_From_The_Movies_and_Any_Episode</guid>\n<digg:diggCount>771</digg:diggCount>\n<digg:submitter><digg:username>NickHodges</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>134</digg:commentCount>\n</item>\n<item>\n<title>Wikipedia Search Engine WikiSeek Launches</title>\n<link>http://digg.com/tech_news/Wikipedia_Search_Engine_WikiSeek_Launches</link>\n<description>This is different than the Wikiasari search engine announced last month. WikiSeek is a search engine that returns results only from Wikipedia and sites linked to from Wikipedia. It has been in development since 2005 and launched 1 hour ago.</description>\n<pubDate>Tue, 16 Jan 2007 12:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Wikipedia_Search_Engine_WikiSeek_Launches</guid>\n<digg:diggCount>888</digg:diggCount>\n<digg:submitter><digg:username>webtech</digg:username><digg:userimage>http://digg.com/userimages/webtech/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>48</digg:commentCount>\n</item>\n<item>\n<title>Netflix Internet TV/Film Delivery Starts This Week</title>\n<link>http://digg.com/tech_news/Netflix_Internet_TV_Film_Delivery_Starts_This_Week</link>\n<description>Netflix will begin IP rental of films and television this week. Those with better plans get more hours, from 6 to 48 hours a month. Over time, they plan to expand the service to the livingroom, and beyond.</description>\n<pubDate>Tue, 16 Jan 2007 12:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Netflix_Internet_TV_Film_Delivery_Starts_This_Week</guid>\n<digg:diggCount>901</digg:diggCount>\n<digg:submitter><digg:username>BLAM8</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>80</digg:commentCount>\n</item>\n<item>\n<title>YouTube ''Gandhi'' Video Sparks Outrage in India</title>\n<link>http://digg.com/tech_news/YouTube_Gandhi_Video_Sparks_Outrage_in_India</link>\n<description>The government of India is said to be considering taking &quot;action&quot; against YouTube, due to a clip posted on the site showing a man dressed as Gandhi performing a pole dance, among other activities. YouTube owner Google says it is reviewing the clip to determine &quot;the best course of action.&quot;</description>\n<pubDate>Tue, 16 Jan 2007 11:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/YouTube_Gandhi_Video_Sparks_Outrage_in_India</guid>\n<digg:diggCount>460</digg:diggCount>\n<digg:submitter><digg:username>IvanB</digg:username><digg:userimage>http://digg.com/userimages/ivanb/medium1979.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>169</digg:commentCount>\n</item>\n<item>\n<title>The only way to quit apple? PISS ON STEVE JOBS DESK</title>\n<link>http://digg.com/apple/The_only_way_to_quit_apple_PISS_ON_STEVE_JOBS_DESK</link>\n<description>What happens when Steve wants you to stay, and you want to quit? Piss on his desk, hilarious.</description>\n<pubDate>Tue, 16 Jan 2007 11:10:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/The_only_way_to_quit_apple_PISS_ON_STEVE_JOBS_DESK</guid>\n<digg:diggCount>219</digg:diggCount>\n<digg:submitter><digg:username>osuguy</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>28</digg:commentCount>\n</item>\n<item>\n<title>CrashPlan - Automatic Offsite Backup</title>\n<link>http://digg.com/software/CrashPlan_Automatic_Offsite_Backup</link>\n<description>I''ve been waiting for someone to write this app.  Rather than paying monthly fees to backup services, CrashPlan uses a friends PC to automatically backup your data.  All data is encrypted on the remote drive for added privacy.  Check out the ''Features'' page.</description>\n<pubDate>Tue, 16 Jan 2007 10:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/CrashPlan_Automatic_Offsite_Backup</guid>\n<digg:diggCount>676</digg:diggCount>\n<digg:submitter><digg:username>kevinrose</digg:username><digg:userimage>http://digg.com/userimages/kevinrose/medium5638.jpg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>53</digg:commentCount>\n</item>\n<item>\n<title>COOL: Grow a world on your computer with your internet traffic</title>\n<link>http://digg.com/software/COOL_Grow_a_world_on_your_computer_with_your_internet_traffic</link>\n<description>just what the title says</description>\n<pubDate>Tue, 16 Jan 2007 10:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/COOL_Grow_a_world_on_your_computer_with_your_internet_traffic</guid>\n<digg:diggCount>1611</digg:diggCount>\n<digg:submitter><digg:username>goleopards1</digg:username><digg:userimage>http://digg.com/userimages/g/o/l/goleopards1/medium6943.jpg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>96</digg:commentCount>\n</item>\n<item>\n<title>Apple posts 35 iPhone-related job listings</title>\n<link>http://digg.com/apple/Apple_posts_35_iPhone_related_job_listings</link>\n<description>Now out of secrecy, Apple''s iPhone division is looking to add to its team of engineers responsible for developing future generation iPhone handsets, and has posted to its website 35 related job openings.</description>\n<pubDate>Tue, 16 Jan 2007 10:30:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Apple_posts_35_iPhone_related_job_listings</guid>\n<digg:diggCount>40</digg:diggCount>\n<digg:submitter><digg:username>KillerX</digg:username><digg:userimage>http://digg.com/userimages/killerx/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>5</digg:commentCount>\n</item>\n<item>\n<title>Review: Killer Intel Motherboard Based on the 680i SLI Chipset</title>\n<link>http://digg.com/hardware/Review_Killer_Intel_Motherboard_Based_on_the_680i_SLI_Chipset</link>\n<description>Motherboards which are truly designed for the enthusiast are few and far between. Sure, it''s easy to put some flashy heatsinks and some basic overclocking controls in to try to lure the casual gamer to their brand - but the true enthusiast demands more.</description>\n<pubDate>Tue, 16 Jan 2007 10:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Review_Killer_Intel_Motherboard_Based_on_the_680i_SLI_Chipset</guid>\n<digg:diggCount>281</digg:diggCount>\n<digg:submitter><digg:username>3monkeys</digg:username><digg:userimage>http://digg.com/userimages/3monkeys/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>25</digg:commentCount>\n</item>\n<item>\n<title>Privately, Hollywood admits DRM isn''t about piracy</title>\n<link>http://digg.com/tech_news/Privately_Hollywood_admits_DRM_isn_t_about_piracy</link>\n<description>For almost ten years now I have argued that digital rights management has little to do with piracy, but that is instead a carefully plotted ruse to undercut fair use and then create new revenue streams where there were previously none. I will briefly repeat my argument here before relating a prime example of it in the wild.</description>\n<pubDate>Tue, 16 Jan 2007 09:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Privately_Hollywood_admits_DRM_isn_t_about_piracy</guid>\n<digg:diggCount>1125</digg:diggCount>\n<digg:submitter><digg:username>Jeffrey903</digg:username><digg:userimage>http://digg.com/userimages/jeffrey903/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>48</digg:commentCount>\n</item>\n<item>\n<title>TorrentFreak 2.0</title>\n<link>http://digg.com/tech_news/TorrentFreak_2_0</link>\n<description>A cool new look to the website we all love....torrent freak</description>\n<pubDate>Tue, 16 Jan 2007 08:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/TorrentFreak_2_0</guid>\n<digg:diggCount>61</digg:diggCount>\n<digg:submitter><digg:username>thecahos1</digg:username><digg:userimage>http://digg.com/userimages/t/h/e/thecahos1/medium9714.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>12</digg:commentCount>\n</item>\n<item>\n<title>Wordpress 2.0.7 available</title>\n<link>http://digg.com/software/Wordpress_2_0_7_available</link>\n<description>Recently a bug in certain versions of PHP came to our attention that could cause a security vulnerability in your blog. We’re able to work around it fairly easily, so we’ve decided to release 2.0.7 to fix the PHP security problem and the Feedburner issue that was in 2.0.6. It is recommended that everyone running</description>\n<pubDate>Tue, 16 Jan 2007 07:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Wordpress_2_0_7_available</guid>\n<digg:diggCount>447</digg:diggCount>\n<digg:submitter><digg:username>boghy2k</digg:username><digg:userimage>http://digg.com/userimages/b/o/g/boghy2k/medium5371.gif</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>41</digg:commentCount>\n</item>\n<item>\n<title>Windows Based Ubuntu Installer - Screenshots and demo </title>\n<link>http://digg.com/linux_unix/Windows_Based_Ubuntu_Installer_Screenshots_and_demo</link>\n<description>If you want to install Ubuntu from your windows here is simple installer this is very useful for new users to install ubuntu very easily.</description>\n<pubDate>Tue, 16 Jan 2007 06:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Windows_Based_Ubuntu_Installer_Screenshots_and_demo</guid>\n<digg:diggCount>925</digg:diggCount>\n<digg:submitter><digg:username>mynamefat</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>90</digg:commentCount>\n</item>\n<item>\n<title>Google offers free wi-fi for San Francisco</title>\n<link>http://digg.com/tech_news/Google_offers_free_wi_fi_for_San_Francisco</link>\n<description>WASHINGTON (MarketWatch) - Google Inc. has offered to provide San Francisco with free wireless Internet access at no cost to the city - a move that heightens speculation the firm may seek to establish a free, nationwide wi-fi network that could cause problems for existing Internet service providers.</description>\n<pubDate>Tue, 16 Jan 2007 06:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Google_offers_free_wi_fi_for_San_Francisco</guid>\n<digg:diggCount>199</digg:diggCount>\n<digg:submitter><digg:username>HMTKSteve</digg:username><digg:userimage>http://digg.com/userimages/hmtksteve/medium6844.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>21</digg:commentCount>\n</item>\n<item>\n<title>State Trooper Develops Ticket-Writing Program; State Tries to Take Source</title>\n<link>http://digg.com/software/State_Trooper_Develops_Ticket_Writing_Program_State_Tries_to_Take_Source</link>\n<description>Eighteen months ago, the State Patrol praised trooper David Meredith for going beyond the call of duty by developing time-saving software that helps officers write traffic tickets electronically. Now, he is suing the head of the patrol, saying the state is trying to illegally seize the source code to software he developed on his own time.</description>\n<pubDate>Tue, 16 Jan 2007 06:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/State_Trooper_Develops_Ticket_Writing_Program_State_Tries_to_Take_Source</guid>\n<digg:diggCount>1108</digg:diggCount>\n<digg:submitter><digg:username>iamcitizen</digg:username><digg:userimage>http://digg.com/userimages/iamcitizen/medium.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>116</digg:commentCount>\n</item>\n<item>\n<title>Anyone can use Linux, as long as its pre-installed</title>\n<link>http://digg.com/linux_unix/Anyone_can_use_Linux_as_long_as_its_pre_installed</link>\n<description>A post from a very satisfied and very non-technical person on their 2 year experience of using Linux.  The secret however is there in the post, someone else installed it for her.  It would be interesting how easy Windows would be if everyone had to install it themselves! &quot;That was 2 years ago now and my computer works perfectly&quot;</description>\n<pubDate>Tue, 16 Jan 2007 06:10:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Anyone_can_use_Linux_as_long_as_its_pre_installed</guid>\n<digg:diggCount>117</digg:diggCount>\n<digg:submitter><digg:username>JamesSSmith</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>37</digg:commentCount>\n</item>\n<item>\n<title>LinDVD--LEGAL DVD playback on Linux??  Whaaaaat?</title>\n<link>http://digg.com/linux_unix/LinDVD_LEGAL_DVD_playback_on_Linux_Whaaaaat</link>\n<description>It''s called LinDVD.  Intervideo--yes, *that* Intervideo, makes a legal and licensable software for &quot;embedded&quot; Linux (by which they mean commercial distros and hardware). Supposedly, paid-for Mandriva Linux bundles it with their distro.</description>\n<pubDate>Tue, 16 Jan 2007 06:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/LinDVD_LEGAL_DVD_playback_on_Linux_Whaaaaat</guid>\n<digg:diggCount>443</digg:diggCount>\n<digg:submitter><digg:username>diggapleaze</digg:username><digg:userimage>http://digg.com/userimages/diggapleaze/medium3977.jpg</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>51</digg:commentCount>\n</item>\n<item>\n<title>The Road to KDE 4: Full Mac OS X Support</title>\n<link>http://digg.com/apple/The_Road_to_KDE_4_Full_Mac_OS_X_Support</link>\n<description>&quot;Just because KDE has been designed to be portable across Linux, FreeBSD and other UNIX/X11 environments for an age now, doesn''t mean we aren''t up for the occasional challenge. [....]  Today I am focusing on the KDE/Mac developments for KDE 4. Read on for the details....&quot; (first screen shots included).</description>\n<pubDate>Tue, 16 Jan 2007 06:00:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/The_Road_to_KDE_4_Full_Mac_OS_X_Support</guid>\n<digg:diggCount>549</digg:diggCount>\n<digg:submitter><digg:username>satipip</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>35</digg:commentCount>\n</item>\n<item>\n<title>Venice Project to launch as JOOST</title>\n<link>http://digg.com/tech_news/Venice_Project_to_launch_as_JOOST</link>\n<description>The Venice Project, created by Skype founders Niklas Zennstrom and Janus Friis, will launch under the name Joost. The site is password protected, but simply hitting &quot;cancel&quot; multiple times will show an unformatted Venice Project web site.</description>\n<pubDate>Tue, 16 Jan 2007 05:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Venice_Project_to_launch_as_JOOST</guid>\n<digg:diggCount>424</digg:diggCount>\n<digg:submitter><digg:username>webtech</digg:username><digg:userimage>http://digg.com/userimages/webtech/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>39</digg:commentCount>\n</item>\n<item>\n<title>VirtualBox goes open source!</title>\n<link>http://digg.com/linux_unix/VirtualBox_goes_open_source</link>\n<description>InnoTek VirtualBox is a family of powerful x86 virtualization products for enterprise as well as home use. Not only is VirtualBox an extremely feature rich, high performance product for enterprise customers, it is also the only professional solution that is freely available as Open Source Software under the terms of the GNU Public License (GPL).</description>\n<pubDate>Tue, 16 Jan 2007 05:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/VirtualBox_goes_open_source</guid>\n<digg:diggCount>398</digg:diggCount>\n<digg:submitter><digg:username>beeman</digg:username><digg:userimage>http://digg.com/userimages/b/e/e/beeman/medium6336.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>24</digg:commentCount>\n</item>\n<item>\n<title>Another Great Dilbert Comic ( Linux style )</title>\n<link>http://digg.com/linux_unix/Another_Great_Dilbert_Comic_Linux_style</link>\n<description>Dilbert''s mom sends a &quot;Flaming e-mail to Bill Gates&quot;</description>\n<pubDate>Tue, 16 Jan 2007 05:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Another_Great_Dilbert_Comic_Linux_style</guid>\n<digg:diggCount>1083</digg:diggCount>\n<digg:submitter><digg:username>diggtochina</digg:username><digg:userimage>http://digg.com/userimages/d/i/g/diggtochina/medium1857.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>54</digg:commentCount>\n</item>\n<item>\n<title>LCD + webcam = cool</title>\n<link>http://digg.com/mods/LCD_webcam_cool</link>\n<description>The text you enter on the site pops up on the display in this guys office :)</description>\n<pubDate>Tue, 16 Jan 2007 04:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/mods/LCD_webcam_cool</guid>\n<digg:diggCount>1024</digg:diggCount>\n<digg:submitter><digg:username>Gunhead123</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Mods</digg:category>\n<digg:commentCount>95</digg:commentCount>\n</item>\n<item>\n<title>Start Firefox with a splash </title>\n<link>http://digg.com/linux_unix/Start_Firefox_with_a_splash</link>\n<description>Adds a splash screen to most Mozilla and Mozilla Based products.</description>\n<pubDate>Tue, 16 Jan 2007 04:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Start_Firefox_with_a_splash</guid>\n<digg:diggCount>782</digg:diggCount>\n<digg:submitter><digg:username>mynamefat</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>91</digg:commentCount>\n</item>\n<item>\n<title>5 Gadgets Every Guy Needs</title>\n<link>http://digg.com/gadgets/5_Gadgets_Every_Guy_Needs</link>\n<description>Our editors have compiled a list of five gadgets that every guy needs, or so we’d like to think. From the Cruzin Cooler to the Beer Belly, you’ll find it here. Which ones are your favorites?</description>\n<pubDate>Tue, 16 Jan 2007 04:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/5_Gadgets_Every_Guy_Needs</guid>\n<digg:diggCount>121</digg:diggCount>\n<digg:submitter><digg:username>UCBearcats</digg:username><digg:userimage>http://digg.com/userimages/ucbearcats/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>29</digg:commentCount>\n</item>\n<item>\n<title>How to Tag and Categorize Your Content</title>\n<link>http://digg.com/design/How_to_Tag_and_Categorize_Your_Content</link>\n<description>A good primer on two very simple tasks that most bloggers either suck at or they don''t adhere to.</description>\n<pubDate>Tue, 16 Jan 2007 03:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/How_to_Tag_and_Categorize_Your_Content</guid>\n<digg:diggCount>346</digg:diggCount>\n<digg:submitter><digg:username>scoreboard27</digg:username><digg:userimage>http://digg.com/userimages/s/c/o/scoreboard27/medium3301.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>13</digg:commentCount>\n</item>\n<item>\n<title>A PC you can install in your WALL!</title>\n<link>http://digg.com/tech_news/A_PC_you_can_install_in_your_WALL</link>\n<description>We all heard of the smallest PC, but this seems to fit into a category of its own. A PC that can be mounted into a socket in the wall. You just plug in your mouse, keyboard, speakers and monitor. Pretty cool, huh?</description>\n<pubDate>Tue, 16 Jan 2007 03:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/A_PC_you_can_install_in_your_WALL</guid>\n<digg:diggCount>1304</digg:diggCount>\n<digg:submitter><digg:username>andreiknox</digg:username><digg:userimage>http://digg.com/userimages/a/n/d/andreiknox/medium3836.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>106</digg:commentCount>\n</item>\n<item>\n<title>Cisco Demanded to have a access to iTunes in return for Sharing iPhone Name</title>\n<link>http://digg.com/tech_news/Cisco_Demanded_to_have_a_access_to_iTunes_in_return_for_Sharing_iPhone_Name</link>\n<description>Cisco spells out what''s in the ''iPhone'' name: Cisco is understood to have demanded access to Apple''s closed media distribution service, the online iTunes store, in return for sharing the ''iPhone'' brand name at the centre of a trademark dispute between the two groups</description>\n<pubDate>Tue, 16 Jan 2007 03:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Cisco_Demanded_to_have_a_access_to_iTunes_in_return_for_Sharing_iPhone_Name</guid>\n<digg:diggCount>698</digg:diggCount>\n<digg:submitter><digg:username>CLIFFosakaJAPAN</digg:username><digg:userimage>http://digg.com/userimages/cliffosakajapan/medium5296.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>87</digg:commentCount>\n</item>\n<item>\n<title>Happy BDAY Wikipedia, Turns 6 Today!</title>\n<link>http://digg.com/tech_news/Happy_BDAY_Wikipedia_Turns_6_Today</link>\n<description>Wikipedia was launched as an English language project on January 15, 2001, as a complement to the expert-written and now defunct Nupedia, and is now operated by the non-profit Wikimedia Foundation.</description>\n<pubDate>Tue, 16 Jan 2007 03:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Happy_BDAY_Wikipedia_Turns_6_Today</guid>\n<digg:diggCount>1676</digg:diggCount>\n<digg:submitter><digg:username>UCBearcats</digg:username><digg:userimage>http://digg.com/userimages/ucbearcats/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>48</digg:commentCount>\n</item>\n<item>\n<title> First Linux live CD designed for the Sony PlayStation 3</title>\n<link>http://digg.com/linux_unix/First_Linux_live_CD_designed_for_the_Sony_PlayStation_3</link>\n<description>Takeshi Yaegashi has announced the release of what appears to be the first Linux live CD designed for the Sony PlayStation 3: &quot;I''ve released Debian live CD for PlayStation 3 which also integrates the Debian-Installer in a single ISO image.&quot; The CD boots into a GNOME 2.14 desktop environment complemented by a small selection of other useful software</description>\n<pubDate>Tue, 16 Jan 2007 03:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/First_Linux_live_CD_designed_for_the_Sony_PlayStation_3</guid>\n<digg:diggCount>518</digg:diggCount>\n<digg:submitter><digg:username>mynamefat</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>33</digg:commentCount>\n</item>\n</channel>\n</rss>', '20070116185728');

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
(2, 2, 'guest', 'paypal', 1, 0, '69X88270JS012512S', 'Dan Caragea', 'United States', '', '', '', '', 'paypal@sco.ro', '', 2, 4, 30.00, '2006-12-13', '2007-01-12', 0, '');

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
(28, 0x69735f64656661756c74, '1', 'Is this skin the default site skin?', 0, 0x736b696e5f6261736963);

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

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `last_visit`, `email`, `skin`, `temp_pass`) VALUES (1, 0x64616e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, '0000-00-00 00:00:00', 'dan@sco.ro', '', ''),
(2, 0x74657374, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, '0000-00-00 00:00:00', 'dan@rdsct.ro', 'basic', ''),
(209, 0x7465737432, 0x3662343238383630323064303630386435646138373431633464353564303563, 5, 2, '0000-00-00 00:00:00', 'dan@rdsct.ro', '', '');

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
  `fk_user_id_from` int(10) unsigned NOT NULL default '0',
  `_user_from` varchar(32) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  `fk_folder_id` int(10) unsigned NOT NULL default '0',
  `del` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_from`),
  KEY `user_id_2` (`fk_user_id`,`fk_folder_id`,`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_inbox`
-- 

INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_from`, `_user_from`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES (1, 1, 2, 2, 'test', 'test subj', 'test body\r\n', '2006-11-02 11:54:47', 0, 0, 0),
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
  `fk_user_id_to` int(10) unsigned NOT NULL default '0',
  `_user_to` varchar(32) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_to`),
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_outbox`
-- 

INSERT INTO `dsb_user_outbox` (`mail_id`, `fk_user_id`, `fk_user_id_to`, `_user_to`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 2, 2, 'test', 'Outbox test message', 'This is an outbox test message.', '2007-01-12 17:00:00', 0);

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
(3, 2, 15, '2007-01-08 15:14:00', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test', '9/2_21168263188.jpg', -93.6367034912, 42.0276985168, 0, 0, 1, '|2|', '1976-11-01', 218, 16, 7089, '50010', 6, 4, 4, 1, '', 'Please enter a few\r\n words about you.'),
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
  `fk_user_id_from` int(10) unsigned NOT NULL default '0',
  `_user_from` varchar(32) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_from`),
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_spambox`
-- 


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

