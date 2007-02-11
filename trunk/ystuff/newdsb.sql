-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 11, 2007 at 09:42 PM
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


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_feed_cache`
-- 

DROP TABLE IF EXISTS `dsb_feed_cache`;
CREATE TABLE `dsb_feed_cache` (
  `module_code` varchar(32) binary NOT NULL default '',
  `feed_xml` text NOT NULL,
  `update_time` timestamp(14) NOT NULL,
  PRIMARY KEY  (`module_code`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_feed_cache`
-- 

INSERT INTO `dsb_feed_cache` (`module_code`, `feed_xml`, `update_time`) VALUES (0x646967675f74656368, '<?xml version="1.0" encoding="UTF-8"?>\n<rss version="2.0" xmlns:digg="http://digg.com/docs/diggrss/">\n<channel>\n<title>Digg / Technology</title>\n<language>en-us</language><link>http://digg.com/view/technology</link>\n<description>Digg / Technology</description>\n<item>\n<title>Windows XP apps on your Ubuntu desktop - now with Coherence !</title>\n<link>http://digg.com/linux_unix/Windows_XP_apps_on_your_Ubuntu_desktop_now_with_Coherence</link>\n<description>Run Windows XP apps on Ubuntu without having and have them appear on your normal Gnome or KDE desktop. The tutorial starts with uses KQemu  (now Open Source as of today) but the latter section on coherence can easily be adapted to VMWare Player or Xen.</description>\n<pubDate>Wed, 7 Feb 2007 13:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Windows_XP_apps_on_your_Ubuntu_desktop_now_with_Coherence</guid>\n<digg:diggCount>62</digg:diggCount>\n<digg:submitter><digg:username>nailer</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>2</digg:commentCount>\n</item>\n<item>\n<title>Breaking: Tivo Boxes to Download Amazon Unboxed Videos</title>\n<link>http://digg.com/tech_news/Breaking_Tivo_Boxes_to_Download_Amazon_Unboxed_Videos</link>\n<description>Amazon and TiVo just made me wet my pants with what could be a killer app in the digital video distribution arms race: TiVo Series 3 and 2 set top boxes will be living room conduits for Amazon''s Unbox IP video on demand service. This is the first single box solution that intermingles downloadable broadband video and traditional TV in one place.</description>\n<pubDate>Wed, 7 Feb 2007 13:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Breaking_Tivo_Boxes_to_Download_Amazon_Unboxed_Videos</guid>\n<digg:diggCount>85</digg:diggCount>\n<digg:submitter><digg:username>maxaids</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>13</digg:commentCount>\n</item>\n<item>\n<title>New version of Joost available!</title>\n<link>http://digg.com/tech_news/New_version_of_Joost_available</link>\n<description>Version 0.74 of Joost is available to download for beta testers. Improvements include: better coping with certainlocal configurations, better fonts, interface tweaks, added channel numbers in the EPG that are selectable from the keyboard &amp; enabled dual monitor support. If you want a beta invite leave a comment on the blog &amp; I''ll see what I can do</description>\n<pubDate>Wed, 7 Feb 2007 12:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/New_version_of_Joost_available</guid>\n<digg:diggCount>172</digg:diggCount>\n<digg:submitter><digg:username>MrSolutions</digg:username><digg:userimage>http://digg.com/userimages/m/r/s/mrsolutions/medium2222.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>43</digg:commentCount>\n</item>\n<item>\n<title>Run Your Existing Windows Installation on Ubuntu with Vmware Player</title>\n<link>http://digg.com/linux_unix/Run_Your_Existing_Windows_Installation_on_Ubuntu_with_Vmware_Player</link>\n<description>Title says it all.</description>\n<pubDate>Wed, 7 Feb 2007 09:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Run_Your_Existing_Windows_Installation_on_Ubuntu_with_Vmware_Player</guid>\n<digg:diggCount>468</digg:diggCount>\n<digg:submitter><digg:username>blackmh</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>51</digg:commentCount>\n</item>\n<item>\n<title>25 things to see at the Googleplex before you die</title>\n<link>http://digg.com/tech_news/25_things_to_see_at_the_Googleplex_before_you_die</link>\n<description>Google''s sprawling, cheerfully dystopian campus at Mountain View may intimidate the first-time visitor. But there''s no need to fear. Enjoy our annotated map of 25 sights to take in across the entire Google campus before you die, and/or are killed by Google''s very understanding but nevertheless lethal security forces.</description>\n<pubDate>Wed, 7 Feb 2007 08:50:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/25_things_to_see_at_the_Googleplex_before_you_die</guid>\n<digg:diggCount>453</digg:diggCount>\n<digg:submitter><digg:username>mklopez</digg:username><digg:userimage>http://digg.com/userimages/mklopez/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>28</digg:commentCount>\n</item>\n<item>\n<title>The Evolution of Apple.com</title>\n<link>http://digg.com/apple/The_Evolution_of_Apple_com</link>\n<description>Over 150 screenshots documenting how apple.com has changed from 1996 to the present.</description>\n<pubDate>Wed, 7 Feb 2007 06:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/The_Evolution_of_Apple_com</guid>\n<digg:diggCount>888</digg:diggCount>\n<digg:submitter><digg:username>diggamer</digg:username><digg:userimage>http://digg.com/userimages/d/i/g/diggamer/medium1332.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>40</digg:commentCount>\n</item>\n<item>\n<title>Traffic Graph of the Core Internet DNS Services Being Attacked This Morning</title>\n<link>http://digg.com/security/Traffic_Graph_of_the_Core_Internet_DNS_Services_Being_Attacked_This_Morning</link>\n<description>If you haven''t heard the story - &quot;Hackers briefly overwhelmed at least three of the most important root domain name servers in the United States yesterday, in one of the most significant attacks against the Internet since 2002.&quot;.  Here is the graph of the traffic levels on the DNS servers.</description>\n<pubDate>Wed, 7 Feb 2007 06:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/Traffic_Graph_of_the_Core_Internet_DNS_Services_Being_Attacked_This_Morning</guid>\n<digg:diggCount>577</digg:diggCount>\n<digg:submitter><digg:username>marksmayo</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>67</digg:commentCount>\n</item>\n<item>\n<title>Hackers Attack Key Net Traffic Computers</title>\n<link>http://digg.com/security/Hackers_Attack_Key_Net_Traffic_Computers_3</link>\n<description>Hackers briefly overwhelmed at least three of the 13 computers that help manage global computer traffic Tuesday in one of the most significant attacks against the Internet since 2002.</description>\n<pubDate>Wed, 7 Feb 2007 04:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/Hackers_Attack_Key_Net_Traffic_Computers_3</guid>\n<digg:diggCount>901</digg:diggCount>\n<digg:submitter><digg:username>scoreboard27</digg:username><digg:userimage>http://digg.com/userimages/s/c/o/scoreboard27/medium3301.jpg</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>77</digg:commentCount>\n</item>\n<item>\n<title>Microsoft Increases Support Prices for Windows, Office</title>\n<link>http://digg.com/tech_news/Microsoft_Increases_Support_Prices_for_Windows_Office</link>\n<description>Surprise, surprise... Prices for both Windows Vista and XP support were raised, from $39 to $59 per incident while support prices for Office XP and Office 2007 went from $35 to $49 per incident.</description>\n<pubDate>Wed, 7 Feb 2007 04:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Microsoft_Increases_Support_Prices_for_Windows_Office</guid>\n<digg:diggCount>325</digg:diggCount>\n<digg:submitter><digg:username>bonez05</digg:username><digg:userimage>http://digg.com/userimages/b/o/n/bonez05/medium9629.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>49</digg:commentCount>\n</item>\n<item>\n<title>Norway responds to Jobs'' open letter</title>\n<link>http://digg.com/apple/Norway_responds_to_Jobs_open_letter</link>\n<description>Senior advisor Torgeir Waterhouse of the Norwegian Consumer Council has responded to Apple CEO Steve Jobs'' open letter concerning digital rights management and free music, which the executive published earlier today.</description>\n<pubDate>Wed, 7 Feb 2007 04:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Norway_responds_to_Jobs_open_letter</guid>\n<digg:diggCount>588</digg:diggCount>\n<digg:submitter><digg:username>jogrim</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>102</digg:commentCount>\n</item>\n<item>\n<title>New Wal-Mart Download Service 100% Fails in Firefox</title>\n<link>http://digg.com/design/New_Wal_Mart_Download_Service_100_Fails_in_Firefox</link>\n<description>Some design flexibility is OK when doing CSS or Standards-based design.  Degrading without the style sheet is also OK in some instances.  But check out the fancy new Wal-Mart download service that is 100% incompatible with Firefox. Wow.  Oops!</description>\n<pubDate>Wed, 7 Feb 2007 03:40:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/New_Wal_Mart_Download_Service_100_Fails_in_Firefox</guid>\n<digg:diggCount>1738</digg:diggCount>\n<digg:submitter><digg:username>erikjernberg</digg:username><digg:userimage>http://digg.com/userimages/erikjernberg/medium2934.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>241</digg:commentCount>\n</item>\n<item>\n<title>The Coming Internet Traffic Jam</title>\n<link>http://digg.com/tech_news/The_Coming_Internet_Traffic_Jam</link>\n<description>A new assessment from Deloitte &amp; Touche predicts that global traffic will exceed the Internet''s capacity as soon as this year. Why? The rapid growth in the number of global Internet users, combined with the rise of online video services and the lack of investment in new infrastructure.</description>\n<pubDate>Wed, 7 Feb 2007 03:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/The_Coming_Internet_Traffic_Jam</guid>\n<digg:diggCount>535</digg:diggCount>\n<digg:submitter><digg:username>spinchange</digg:username><digg:userimage>http://digg.com/userimages/spinchange/medium8749.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>82</digg:commentCount>\n</item>\n<item>\n<title>6 Startup Lessons for the Year 2007</title>\n<link>http://digg.com/software/6_Startup_Lessons_for_the_Year_2007</link>\n<description>Too many niche startups fall into the trap of trying to satisfy everyone''s needs. Companies have a natural tendency to expand their line of goods as they grow. The article argues that what startups need to do, instead, is stop trying to be everybody products and deliberately narrow their reach regardless of their growth or traffic.</description>\n<pubDate>Wed, 7 Feb 2007 03:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/6_Startup_Lessons_for_the_Year_2007</guid>\n<digg:diggCount>505</digg:diggCount>\n<digg:submitter><digg:username>IvanB</digg:username><digg:userimage>http://digg.com/userimages/ivanb/medium1979.jpg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>16</digg:commentCount>\n</item>\n<item>\n<title>25 Games Tested in Vista</title>\n<link>http://digg.com/software/25_Games_Tested_in_Vista</link>\n<description>25 popular game titles tested in Vista. Which ones work, which ones don''t.</description>\n<pubDate>Wed, 7 Feb 2007 02:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/25_Games_Tested_in_Vista</guid>\n<digg:diggCount>976</digg:diggCount>\n<digg:submitter><digg:username>jervana</digg:username><digg:userimage>http://digg.com/userimages/jervana/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>69</digg:commentCount>\n</item>\n<item>\n<title>TransGaming and Nvidia team up for high-end games on the Mac</title>\n<link>http://digg.com/apple/TransGaming_and_Nvidia_team_up_for_high_end_games_on_the_Mac</link>\n<description>TransGaming and Nvidia have &quot;joined forces&quot; to &quot;bring top tier video games to the Intel-based Macintosh platform using TransGaming''s Cider portability engine in conjunction with NVIDIA''s CgFX graphics system.&quot;</description>\n<pubDate>Wed, 7 Feb 2007 01:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/TransGaming_and_Nvidia_team_up_for_high_end_games_on_the_Mac</guid>\n<digg:diggCount>566</digg:diggCount>\n<digg:submitter><digg:username>keiths</digg:username><digg:userimage>http://digg.com/userimages/k/e/i/keiths/medium8096.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>59</digg:commentCount>\n</item>\n<item>\n<title>BBC slammed for using public funds for Microsoft lock-in</title>\n<link>http://digg.com/linux_unix/BBC_slammed_for_using_public_funds_for_Microsoft_lock_in</link>\n<description>The Open Source Consortium (OSC) has slammed the BBC over plans to lock online TV viewers into Microsoft products. The accusations come after the BBC announced that its new on-demand services will be limited to Microsoft Windows. OSC believes this is anti-competitive, and would be in breach of the broadcaster''s charter.</description>\n<pubDate>Wed, 7 Feb 2007 01:07:56 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/BBC_slammed_for_using_public_funds_for_Microsoft_lock_in</guid>\n<digg:diggCount>757</digg:diggCount>\n<digg:submitter><digg:username>jrepin</digg:username><digg:userimage>http://digg.com/userimages/jrepin/medium.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>92</digg:commentCount>\n</item>\n<item>\n<title>Hacking Skype: 25 Tips to Improve Your Skype Experience</title>\n<link>http://digg.com/tech_news/Hacking_Skype_25_Tips_to_Improve_Your_Skype_Experience</link>\n<description>&quot;Skype is the most popular VoIP solution of choice. If you''re just getting into this telephone alternative, you are going to be surprised how much you can do with it. In this article we cover 25 tips, hacks, and extras to help you utilize Skype to its fullest potential.&quot;</description>\n<pubDate>Wed, 7 Feb 2007 00:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Hacking_Skype_25_Tips_to_Improve_Your_Skype_Experience</guid>\n<digg:diggCount>1132</digg:diggCount>\n<digg:submitter><digg:username>BoneyB</digg:username><digg:userimage>http://digg.com/userimages/b/o/n/boneyb/medium5683.JPG</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>34</digg:commentCount>\n</item>\n<item>\n<title>Gates “dares anybody” to exploit Vista </title>\n<link>http://digg.com/tech_news/Gates_dares_anybody_to_exploit_Vista</link>\n<description>Microsoft chairman Bill Gates talked with Newsweek magazine''s Steven Levy about the new version of Windows and shared his views on the &quot;I''m a Mac&quot; television commercials. In excerpts from the interview Gates goes on the offensive and claims that the security in Vista is better that the security in the Mac</description>\n<pubDate>Wed, 7 Feb 2007 00:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Gates_dares_anybody_to_exploit_Vista</guid>\n<digg:diggCount>2054</digg:diggCount>\n<digg:submitter><digg:username>populist</digg:username><digg:userimage>http://digg.com/userimages/p/o/p/populist/medium6019.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>303</digg:commentCount>\n</item>\n<item>\n<title>Man gives away free iPods because it makes him feel good.</title>\n<link>http://digg.com/apple/Man_gives_away_free_iPods_because_it_makes_him_feel_good</link>\n<description>This guy fixes and gives away iPods because he &quot;gets a real buzz out of knowing I just made someone’s day&quot;. But he''s not the biggest fan of Apple.</description>\n<pubDate>Tue, 6 Feb 2007 23:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Man_gives_away_free_iPods_because_it_makes_him_feel_good</guid>\n<digg:diggCount>1470</digg:diggCount>\n<digg:submitter><digg:username>Hinchcliffe</digg:username><digg:userimage>http://digg.com/userimages/hinchcliffe/medium4585.gif</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>82</digg:commentCount>\n</item>\n<item>\n<title>Use Quicksilver for quick timed reminders</title>\n<link>http://digg.com/apple/Use_Quicksilver_for_quick_timed_reminders</link>\n<description>This tutorial shows you have to setup Quicksilver to display a text message, play a song, go to webpage, etc in a set amount of time. It''s a quick way to give yourself a little reminder.</description>\n<pubDate>Tue, 6 Feb 2007 23:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Use_Quicksilver_for_quick_timed_reminders</guid>\n<digg:diggCount>468</digg:diggCount>\n<digg:submitter><digg:username>RadiantBeing</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>21</digg:commentCount>\n</item>\n<item>\n<title>The Road to KDE 4: Phonon Makes Multimedia Easier (fixed link)</title>\n<link>http://digg.com/linux_unix/The_Road_to_KDE_4_Phonon_Makes_Multimedia_Easier_fixed_link</link>\n<description>Phonon is designed to take some of the complications out of writing multimedia applications in KDE 4, and ensure that these applications will work on a multitude of platforms and sound architectures. Unfortunately, writing about a sound technology produces very few snazzy screenshots, so instead this week has a few more technical details.</description>\n<pubDate>Tue, 6 Feb 2007 23:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/The_Road_to_KDE_4_Phonon_Makes_Multimedia_Easier_fixed_link</guid>\n<digg:diggCount>324</digg:diggCount>\n<digg:submitter><digg:username>jrepin</digg:username><digg:userimage>http://digg.com/userimages/jrepin/medium.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>19</digg:commentCount>\n</item>\n<item>\n<title>Hack Attack: Getting good with Google Reader</title>\n<link>http://digg.com/software/Hack_Attack_Getting_good_with_Google_Reader</link>\n<description>Today, I''m going to show you the ins and outs of Google''s powerful newsreader, with an emphasis on Reader''s powerful and time-saving keyboard shortcuts. To round things off, I''ll finish up with some of my favorite Google Reader-related tweaks and downloads to get you up to speed with the best newsreader on the planet.</description>\n<pubDate>Tue, 6 Feb 2007 22:01:08 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Hack_Attack_Getting_good_with_Google_Reader</guid>\n<digg:diggCount>628</digg:diggCount>\n<digg:submitter><digg:username>mklopez</digg:username><digg:userimage>http://digg.com/userimages/mklopez/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>18</digg:commentCount>\n</item>\n<item>\n<title>Make a Movie of Your Linux Desktop </title>\n<link>http://digg.com/linux_unix/Make_a_Movie_of_Your_Linux_Desktop</link>\n<description>Want to show off your snazzy 3d desktop, or demonstrate the features of your favourite new program? Make a movie of your desktop to capture all your actions, edit it, then add a soundtrack.</description>\n<pubDate>Tue, 6 Feb 2007 21:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Make_a_Movie_of_Your_Linux_Desktop</guid>\n<digg:diggCount>580</digg:diggCount>\n<digg:submitter><digg:username>bonlebon</digg:username><digg:userimage>http://digg.com/userimages/bonlebon/medium5388.jpg</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>36</digg:commentCount>\n</item>\n<item>\n<title>Multi-Column Layouts Climb Out of the Box</title>\n<link>http://digg.com/design/Multi_Column_Layouts_Climb_Out_of_the_Box</link>\n<description>“Holy Grail,” “One True Layout,” “pain in the @$$”... Alan Pearce presents a cleaner approach to designing multi-column layouts.</description>\n<pubDate>Tue, 6 Feb 2007 21:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Multi_Column_Layouts_Climb_Out_of_the_Box</guid>\n<digg:diggCount>789</digg:diggCount>\n<digg:submitter><digg:username>cyberpear</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>60</digg:commentCount>\n</item>\n<item>\n<title>Five ways to make Digg more social</title>\n<link>http://digg.com/tech_news/Five_ways_to_make_Digg_more_social</link>\n<description>When Kevin Rose announced that the top digger list would be no more, he also alluded to plans to add better social networking features to the site. Here are 5 suggestions to make Digg more social.</description>\n<pubDate>Tue, 6 Feb 2007 20:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Five_ways_to_make_Digg_more_social</guid>\n<digg:diggCount>738</digg:diggCount>\n<digg:submitter><digg:username>asif786</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>82</digg:commentCount>\n</item>\n<item>\n<title>Itallian Phone to Feature Roll-up, Paper Thin Display</title>\n<link>http://digg.com/gadgets/Itallian_Phone_to_Feature_Roll_up_Paper_Thin_Display</link>\n<description>Telecom Italia and Polymer Vision have joined forces to create a cell phone that features a roll-up e-paper-like display. It''s a grayscale and can''t be read in the dark, but the 5-inch screen represents a significant step forward in terms of incorporating this technology on mobile devices.</description>\n<pubDate>Tue, 6 Feb 2007 20:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/Itallian_Phone_to_Feature_Roll_up_Paper_Thin_Display</guid>\n<digg:diggCount>641</digg:diggCount>\n<digg:submitter><digg:username>ryland2</digg:username><digg:userimage>http://digg.com/userimages/r/y/l/ryland2/medium8027.jpg</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>44</digg:commentCount>\n</item>\n<item>\n<title>Steve Jobs on Music</title>\n<link>http://digg.com/apple/Steve_Jobs_on_Music</link>\n<description>An interesting column written by Steve Jobs to shout out his thoughts on music. Does he talk about DRM? Yes. &quot;The rub comes from the music Apple sells on its online iTunes Store. Since Apple does not own or control any music itself, it must license the rights to distribute music from others.&quot;Read the whole story it is interesting!</description>\n<pubDate>Tue, 6 Feb 2007 20:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Steve_Jobs_on_Music</guid>\n<digg:diggCount>5044</digg:diggCount>\n<digg:submitter><digg:username>vrikis</digg:username><digg:userimage>http://digg.com/userimages/v/r/i/vrikis/medium3145.JPG</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>401</digg:commentCount>\n</item>\n<item>\n<title>''Tom Cruise'' missile jokester arrested - CNET News.com</title>\n<link>http://digg.com/tech_news/Tom_Cruise_missile_jokester_arrested_CNET_News_com</link>\n<description>Keith Henson, a fugitive since being convicted of interfering with Scientology, faces extradition to California from Arizona.</description>\n<pubDate>Tue, 6 Feb 2007 19:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Tom_Cruise_missile_jokester_arrested_CNET_News_com</guid>\n<digg:diggCount>1068</digg:diggCount>\n<digg:submitter><digg:username>desertrain7</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>160</digg:commentCount>\n</item>\n<item>\n<title>Make any Application Fullscreen (Mac Only)</title>\n<link>http://digg.com/apple/Make_any_Application_Fullscreen_Mac_Only</link>\n<description>&quot;Let''s limit our attention to one application--any application--at any time&quot;Because Mac''s desktops can be very distracting, this application is a lifesaver for anyone who really needs to get work done with no distractions.</description>\n<pubDate>Tue, 6 Feb 2007 19:40:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Make_any_Application_Fullscreen_Mac_Only</guid>\n<digg:diggCount>881</digg:diggCount>\n<digg:submitter><digg:username>atomic16</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>89</digg:commentCount>\n</item>\n<item>\n<title>New ''Smart'' Digg This Buttons For Your Website/Blog</title>\n<link>http://digg.com/tech_news/New_Smart_Digg_This_Buttons_For_Your_Website_Blog</link>\n<description>&quot;We’re glad to announce an update to our Digg This button.  You used to need different tools to provide links on your site to submit content to Digg versus buttons to Digg content you’ve already submitted.  Our new Digg This button has finally been given a brain to do both!&quot;</description>\n<pubDate>Tue, 6 Feb 2007 19:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/New_Smart_Digg_This_Buttons_For_Your_Website_Blog</guid>\n<digg:diggCount>1686</digg:diggCount>\n<digg:submitter><digg:username>kevinrose</digg:username><digg:userimage>http://digg.com/userimages/kevinrose/medium3094.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>64</digg:commentCount>\n</item>\n<item>\n<title>Don''t be misled by these 10 Windows Vista myths</title>\n<link>http://digg.com/software/Don_t_be_misled_by_these_10_Windows_Vista_myths</link>\n<description>The official consumer launch of Windows Vista has brought with it a great deal of confusion, misinformation, and some fairly ignorant assertions. Windows expert Deb Shinder debunks some of the misconceptions she''s been hearing, from exaggerated cost and hardware requirements to feature limitations and compatibility issues.</description>\n<pubDate>Tue, 6 Feb 2007 18:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Don_t_be_misled_by_these_10_Windows_Vista_myths</guid>\n<digg:diggCount>2393</digg:diggCount>\n<digg:submitter><digg:username>msaleem</digg:username><digg:userimage>http://digg.com/userimages/msaleem/medium9502.jpeg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>293</digg:commentCount>\n</item>\n<item>\n<title>PCI-E x1 Graphics Performance Investigated</title>\n<link>http://digg.com/hardware/PCI_E_x1_Graphics_Performance_Investigated</link>\n<description>PCI-E x1 graphics performance is under the spotlight with Galaxy''s GeForce 7300GT graphics card. We see if there is any difference between it and x16 slots. Is it AGP 4x vs. 8x marketing mumbo gumbo all over again?</description>\n<pubDate>Tue, 6 Feb 2007 18:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/PCI_E_x1_Graphics_Performance_Investigated</guid>\n<digg:diggCount>409</digg:diggCount>\n<digg:submitter><digg:username>TWEAK</digg:username><digg:userimage>http://digg.com/userimages/t/w/e/tweak/medium3854.png</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>46</digg:commentCount>\n</item>\n<item>\n<title>Are Social Networks Just A Feature?</title>\n<link>http://digg.com/tech_news/Are_Social_Networks_Just_A_Feature</link>\n<description>It is time to rethink the whole notion of social networking, and start thinking of it as a feature for other online activities. Already, we see companies like Affinity Circles4 and Social Platform5 turning the “social network” into a commodity, by offering turnkey solutions.</description>\n<pubDate>Tue, 6 Feb 2007 18:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Are_Social_Networks_Just_A_Feature</guid>\n<digg:diggCount>334</digg:diggCount>\n<digg:submitter><digg:username>scoreboard27</digg:username><digg:userimage>http://digg.com/userimages/s/c/o/scoreboard27/medium3301.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>26</digg:commentCount>\n</item>\n<item>\n<title>Linux Linux Everywhere!!!An entire airplane booting Linux (with pictures)..</title>\n<link>http://digg.com/linux_unix/Linux_Linux_Everywhere_An_entire_airplane_booting_Linux_with_pictures</link>\n<description>After we landed in Orlando I talked the Delta flight crew into rebooting the entire system. I think this is the first time that something Open Source has ended up on a full, frickin'' plane! The flight crew thought I was nuts...but o well. At least the plane won''t ever crash.</description>\n<pubDate>Tue, 6 Feb 2007 18:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Linux_Linux_Everywhere_An_entire_airplane_booting_Linux_with_pictures</guid>\n<digg:diggCount>1393</digg:diggCount>\n<digg:submitter><digg:username>leadstairway</digg:username><digg:userimage>http://digg.com/userimages/l/e/a/leadstairway/medium5763.jpg</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>131</digg:commentCount>\n</item>\n<item>\n<title>WWDC 2007 Officially Announced - June 11-15</title>\n<link>http://digg.com/apple/WWDC_2007_Officially_Announced_June_11_15</link>\n<description>Apple has officially announced the dates of WWDC 2007.</description>\n<pubDate>Tue, 6 Feb 2007 18:10:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/WWDC_2007_Officially_Announced_June_11_15</guid>\n<digg:diggCount>746</digg:diggCount>\n<digg:submitter><digg:username>AmazingSyco</digg:username><digg:userimage>http://digg.com/userimages/amazingsyco/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>49</digg:commentCount>\n</item>\n<item>\n<title>23 Signs That You''re Becoming a Design Geek</title>\n<link>http://digg.com/design/23_Signs_That_You_re_Becoming_a_Design_Geek</link>\n<description>The world of design can be a ruthless one; you get lured into developing anti-social habits like font-spotting and source-code peeping. Learn to spot the warning signs in time – you know you''re becoming a design geek when...</description>\n<pubDate>Tue, 6 Feb 2007 17:50:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/23_Signs_That_You_re_Becoming_a_Design_Geek</guid>\n<digg:diggCount>1862</digg:diggCount>\n<digg:submitter><digg:username>andash</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>124</digg:commentCount>\n</item>\n<item>\n<title>The QEMU Accelerator (KQEMU Module) is Open Source!</title>\n<link>http://digg.com/linux_unix/The_QEMU_Accelerator_KQEMU_Module_is_Open_Source</link>\n<description>The KQEMU accelerator component of the open source QEMU emulation solution has been released under the GNU General Public License.</description>\n<pubDate>Tue, 6 Feb 2007 17:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/The_QEMU_Accelerator_KQEMU_Module_is_Open_Source</guid>\n<digg:diggCount>446</digg:diggCount>\n<digg:submitter><digg:username>kkubasik</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>42</digg:commentCount>\n</item>\n<item>\n<title>Steve Jobs'' Occupational Hazards</title>\n<link>http://digg.com/tech_news/Steve_Jobs_Occupational_Hazards</link>\n<description>With the success of the iPod, Apple CEO Steve Jobs has proven that there are, in fact, second acts in business. Once known primarily for its Macintosh line of computers, Apple is now a digital powerhouse whose shares have risen more than seven times in value over the last five years.</description>\n<pubDate>Tue, 6 Feb 2007 17:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Steve_Jobs_Occupational_Hazards</guid>\n<digg:diggCount>535</digg:diggCount>\n<digg:submitter><digg:username>Alexius</digg:username><digg:userimage>http://digg.com/userimages/alexius/medium1140.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>29</digg:commentCount>\n</item>\n<item>\n<title>Kodak Invents Photo Ink That''ll Last 100 Years</title>\n<link>http://digg.com/gadgets/Kodak_Invents_Photo_Ink_That_ll_Last_100_Years</link>\n<description>Kodak''s revolutionary new ink can quickly create prints with an archival life of 100 years, versus standard inkjet photos that go the way of toilet paper after 15. And the ink costs half the price. Half?! We should buy Kodak''s new printers simply because they''re not trying to fuck us on the cartridge sales.</description>\n<pubDate>Tue, 6 Feb 2007 17:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/Kodak_Invents_Photo_Ink_That_ll_Last_100_Years</guid>\n<digg:diggCount>1315</digg:diggCount>\n<digg:submitter><digg:username>BLAM8</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>97</digg:commentCount>\n</item>\n<item>\n<title>5 Things You Need to Know About SLR Lenses</title>\n<link>http://digg.com/hardware/5_Things_You_Need_to_Know_About_SLR_Lenses</link>\n<description>If you''re wanting a new lens or two but aren''t sure where to even begin looking, you''re in luck! From the author of &quot;10 Reasons to Buy a DSLR,&quot; (that''s me) comes &quot;5 Things You Need to Know About SLR Lenses.&quot; Get your read on.</description>\n<pubDate>Tue, 6 Feb 2007 17:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/5_Things_You_Need_to_Know_About_SLR_Lenses</guid>\n<digg:diggCount>1024</digg:diggCount>\n<digg:submitter><digg:username>TTLKurtis</digg:username><digg:userimage>http://digg.com/userimages/t/t/l/ttlkurtis/medium8223.jpg</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>59</digg:commentCount>\n</item>\n</channel>\n</rss>', '20070207155534');

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

INSERT INTO `dsb_flirts` (`flirt_id`, `flirt_text`) VALUES (1, 'Hello, baby!'),
(2, 'Aye aye, mate!'),
(3, 'Let''s rock and roll!');

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

INSERT INTO `dsb_lang_keys` (`lk_id`, `lk_type`, `lk_diz`, `lk_use`) VALUES (1, 2, '', 2),
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
(516, 2, 'Label for field_50 field', 1),
(517, 2, 'Search label for field_50 field', 1),
(518, 4, 'Help text for field_50 field', 1),
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
(600, 2, 'Field value', 1),
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
(620, 2, 'Field value', 1),
(621, 2, 'Field value', 1),
(622, 2, 'Field value', 1);

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
(508, 503, 'skin_basic', 'Help text to explain what this field is for.'),
(509, 504, 'skin_basic', 'Looking for'),
(510, 505, 'skin_basic', 'Looking for'),
(511, 506, 'skin_basic', ''),
(512, 507, 'skin_basic', 'Date of birth'),
(513, 508, 'skin_basic', 'Age'),
(514, 509, 'skin_basic', ''),
(523, 518, 'skin_basic', ''),
(522, 517, 'skin_basic', 'From'),
(521, 516, 'skin_basic', 'Location'),
(524, 519, 'skin_basic', 'Physical Features'),
(525, 520, 'skin_basic', 'Height'),
(526, 521, 'skin_basic', 'Height'),
(527, 522, 'skin_basic', 'Height is your height measured in meters when you stand up on your feet, with your back at 30 degrees from the vertical position. this is a very long comment.'),
(528, 523, 'skin_basic', 'Weight'),
(529, 524, 'skin_basic', 'Weight'),
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
(625, 620, 'skin_basic', 'chinese'),
(628, 2, 'skin_def', ''),
(629, 3, 'skin_def', ''),
(630, 4, 'skin_def', ''),
(631, 5, 'skin_def', ''),
(632, 6, 'skin_def', ''),
(633, 7, 'skin_def', ''),
(634, 8, 'skin_def', ''),
(635, 9, 'skin_def', ''),
(636, 10, 'skin_def', ''),
(637, 11, 'skin_def', ''),
(638, 12, 'skin_def', ''),
(639, 13, 'skin_def', ''),
(640, 14, 'skin_def', ''),
(641, 15, 'skin_def', ''),
(642, 16, 'skin_def', ''),
(643, 17, 'skin_def', ''),
(644, 18, 'skin_def', ''),
(645, 19, 'skin_def', ''),
(646, 20, 'skin_def', ''),
(647, 21, 'skin_def', ''),
(648, 22, 'skin_def', ''),
(649, 23, 'skin_def', ''),
(650, 24, 'skin_def', ''),
(651, 25, 'skin_def', ''),
(652, 26, 'skin_def', ''),
(653, 27, 'skin_def', ''),
(654, 28, 'skin_def', ''),
(655, 29, 'skin_def', ''),
(656, 30, 'skin_def', ''),
(657, 500, 'skin_def', ''),
(658, 501, 'skin_def', ''),
(659, 502, 'skin_def', ''),
(660, 503, 'skin_def', ''),
(661, 504, 'skin_def', ''),
(662, 505, 'skin_def', ''),
(663, 506, 'skin_def', ''),
(664, 507, 'skin_def', ''),
(665, 508, 'skin_def', ''),
(666, 509, 'skin_def', ''),
(667, 516, 'skin_def', ''),
(668, 517, 'skin_def', ''),
(669, 518, 'skin_def', ''),
(670, 519, 'skin_def', ''),
(671, 520, 'skin_def', ''),
(672, 521, 'skin_def', ''),
(673, 522, 'skin_def', ''),
(674, 523, 'skin_def', ''),
(675, 524, 'skin_def', ''),
(676, 525, 'skin_def', ''),
(677, 526, 'skin_def', ''),
(678, 527, 'skin_def', ''),
(679, 528, 'skin_def', ''),
(680, 529, 'skin_def', ''),
(681, 530, 'skin_def', ''),
(682, 531, 'skin_def', ''),
(683, 532, 'skin_def', ''),
(684, 533, 'skin_def', ''),
(685, 534, 'skin_def', ''),
(686, 535, 'skin_def', ''),
(687, 536, 'skin_def', ''),
(688, 537, 'skin_def', ''),
(689, 538, 'skin_def', ''),
(690, 539, 'skin_def', ''),
(691, 540, 'skin_def', ''),
(692, 541, 'skin_def', ''),
(693, 542, 'skin_def', ''),
(694, 543, 'skin_def', ''),
(695, 544, 'skin_def', ''),
(696, 545, 'skin_def', ''),
(697, 546, 'skin_def', ''),
(698, 547, 'skin_def', ''),
(699, 548, 'skin_def', ''),
(700, 549, 'skin_def', ''),
(701, 550, 'skin_def', ''),
(702, 551, 'skin_def', ''),
(703, 552, 'skin_def', ''),
(704, 553, 'skin_def', ''),
(705, 554, 'skin_def', ''),
(706, 555, 'skin_def', ''),
(707, 556, 'skin_def', ''),
(708, 557, 'skin_def', ''),
(709, 558, 'skin_def', ''),
(710, 559, 'skin_def', ''),
(711, 560, 'skin_def', ''),
(712, 561, 'skin_def', ''),
(713, 562, 'skin_def', ''),
(714, 563, 'skin_def', ''),
(715, 564, 'skin_def', ''),
(716, 565, 'skin_def', ''),
(717, 566, 'skin_def', ''),
(718, 567, 'skin_def', ''),
(719, 568, 'skin_def', ''),
(720, 569, 'skin_def', ''),
(721, 570, 'skin_def', ''),
(722, 571, 'skin_def', ''),
(723, 572, 'skin_def', ''),
(724, 573, 'skin_def', ''),
(725, 574, 'skin_def', ''),
(726, 575, 'skin_def', ''),
(727, 576, 'skin_def', ''),
(728, 577, 'skin_def', ''),
(729, 578, 'skin_def', ''),
(730, 579, 'skin_def', ''),
(731, 580, 'skin_def', ''),
(732, 581, 'skin_def', ''),
(733, 582, 'skin_def', ''),
(734, 583, 'skin_def', ''),
(735, 584, 'skin_def', ''),
(736, 585, 'skin_def', ''),
(737, 586, 'skin_def', ''),
(738, 587, 'skin_def', ''),
(739, 588, 'skin_def', ''),
(740, 589, 'skin_def', ''),
(741, 590, 'skin_def', ''),
(742, 591, 'skin_def', ''),
(743, 592, 'skin_def', ''),
(744, 593, 'skin_def', ''),
(745, 594, 'skin_def', ''),
(746, 595, 'skin_def', ''),
(747, 596, 'skin_def', ''),
(748, 597, 'skin_def', ''),
(749, 600, 'skin_def', ''),
(750, 601, 'skin_def', ''),
(751, 602, 'skin_def', ''),
(752, 603, 'skin_def', ''),
(753, 604, 'skin_def', ''),
(754, 605, 'skin_def', ''),
(755, 606, 'skin_def', ''),
(756, 607, 'skin_def', ''),
(757, 608, 'skin_def', ''),
(758, 609, 'skin_def', ''),
(759, 610, 'skin_def', ''),
(760, 611, 'skin_def', ''),
(761, 612, 'skin_def', ''),
(762, 613, 'skin_def', ''),
(763, 614, 'skin_def', ''),
(764, 615, 'skin_def', ''),
(765, 616, 'skin_def', ''),
(766, 617, 'skin_def', ''),
(767, 618, 'skin_def', ''),
(768, 619, 'skin_def', ''),
(769, 620, 'skin_def', ''),
(770, 621, 'skin_def', ''),
(771, 622, 'skin_def', ''),
(772, 1, 'skin_basic', 'Invalid user name or password. Please try again.'),
(773, 587, 'skin_basic', ''),
(774, 588, 'skin_basic', ''),
(775, 589, 'skin_basic', ''),
(776, 590, 'skin_basic', ''),
(777, 591, 'skin_basic', ''),
(778, 592, 'skin_basic', ''),
(779, 593, 'skin_basic', ''),
(780, 594, 'skin_basic', ''),
(781, 595, 'skin_basic', '');

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
  `field` varchar(32) binary NOT NULL default '',
  `field_value` varchar(255) NOT NULL default '',
  `fk_folder_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`filter_id`),
  UNIQUE KEY `filter_type` (`filter_type`,`fk_user_id`,`field_value`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_message_filters`
-- 

INSERT INTO `dsb_message_filters` (`filter_id`, `filter_type`, `fk_user_id`, `field`, `field_value`, `fk_folder_id`) VALUES (1, 1, 2, '', '2', -3),
(2, 1, 2, 0x666b5f757365725f6964, '14', 2);

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
(0x736b696e5f6261736963, 'Basic', 'The first skin of the site', 4, 1.00),
(0x646967675f74656368, 'Digg Tech Feed', 'Retrieves the latest digg tech stories', 3, 1.00),
(0x736b696e5f646566, 'Default Skin', 'Official default skin', 4, 0.01);

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
-- Table structure for table `dsb_online`
-- 

DROP TABLE IF EXISTS `dsb_online`;
CREATE TABLE `dsb_online` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `last_activity` timestamp(14) NOT NULL,
  `sess` varchar(32) binary NOT NULL default '',
  UNIQUE KEY `sess` (`sess`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_online`
-- 

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES (0, '20070208191220', 0x6334636433656363303464336663316533353561353839616536336138646436),
(2, '20070211212228', 0x3665623337646433666134613864336539666466626138616333356334663332),
(0, '20070208184534', 0x3961643266386365343961346165376239313734353964306562616339643838),
(0, '20070208185109', 0x3231373137353564636232663830326566323164633632383432333762316337),
(0, '20070208185238', 0x6665306234613034646635633564383634653032643663303736633433646131),
(0, '20070208185803', 0x3266313763333230633437353761306634623037323762653636636163386337),
(0, '20070208190046', 0x3332613962656436316265653366343161393836346436373332336138663763),
(0, '20070208191227', 0x3835623130663437346231353063336432353235376162333865363263356334),
(0, '20070208203328', 0x6561353261623761303439333531323538626133663834313635373532373434),
(0, '20070208194133', 0x3562313233643130353736646330373433303061343639653365623632373362),
(0, '20070208192830', 0x3038303832333338316565356134666331656238623236386235613539616263),
(0, '20070208194328', 0x3735383733623136336566663238393132643561643034626537663835323562),
(0, '20070208202725', 0x6666336563326232386561353934386633333035663864616432663232316536),
(0, '20070208202743', 0x3630613135396166613538366536333062623662396439393731346234633435),
(0, '20070208203333', 0x3531626361646535326436623638323730323065303464383235353761623632),
(0, '20070208203343', 0x3961383230643664383732346230613631313064396632396533613865616632),
(0, '20070208205223', 0x3630323866353232303839633463653437373037326131326436376230616434),
(0, '20070208205140', 0x3639366139653733393761643532343064363838393662666533646363323031),
(0, '20070208211434', 0x3061386365643034333161643862646237613566356366626530376137346535),
(0, '20070209123254', 0x6332363062623166393132613762306163636631376236626637326339386631),
(0, '20070209120156', 0x6338326663386666383233373139333837306364393935356466313565396136),
(0, '20070209120215', 0x3938643730313539366563656431306465633362363230373235313830613036),
(0, '20070210153405', 0x3961666435653664373834643933313835313664666431346533636133626463),
(0, '20070210123352', 0x3037623138373936363835366661633739646337326231633733303139633031),
(0, '20070210134849', 0x6531613336653138303431613264616234356662363437616433666466653737),
(0, '20070210135713', 0x3162613536626365663561653861333064356332656430323039393662366639),
(0, '20070210135730', 0x3337306634333535633431326137323465663662383535376532363037383133),
(0, '20070210151253', 0x3634626162323633383365663432623332353162383733303735336365303764),
(0, '20070210151404', 0x3838306634353961383534613036383966383139343564393735383436336161),
(0, '20070210163727', 0x3065353664643737663933366336656633623836353834643332323030343238),
(0, '20070211145023', 0x3935306633343539613630366330373163663165616334326430653234363636),
(2, '20070211212108', 0x3136313730373864383032313233666537336336643436326234666639656339),
(0, '20070211211841', 0x3063393835646463613862663636383538356331373632326166323639356139);

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
  `date` timestamp(14) NOT NULL,
  PRIMARY KEY  (`payment_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_payments`
-- 

INSERT INTO `dsb_payments` (`payment_id`, `fk_user_id`, `_user`, `gateway`, `fk_subscr_id`, `is_recuring`, `gw_txn`, `name`, `country`, `state`, `city`, `zip`, `street_address`, `email`, `phone`, `m_value_from`, `m_value_to`, `amount_paid`, `paid_from`, `paid_until`, `is_suspect`, `suspect_reason`, `date`) VALUES (1, 2, 'guest', 'paypal', 4, 0, '68K892680N420214D', 'Dan Caragea', 'United States', '', '', '', '', 'paypal@sco.ro', '', 2, 4, 100.00, '2006-12-12', '2006-12-13', 0, '', '20070127180529'),
(2, 2, 'guest', 'paypal', 1, 0, '69X88270JS012512S', 'Dan Caragea', 'United States', '', '', '', '', 'paypal@sco.ro', '', 2, 4, 30.00, '2006-12-13', '2007-01-12', 0, '', '20070127180529'),
(3, 1, 'dan', '', 0, 0, '', '', '', '', '', '', '', '', '', 2, 4, 0.00, '2007-01-23', '2007-02-22', 0, '', '20070127180529'),
(4, 2, 'test', '', 0, 0, '', '', '', '', '', '', '', '', '', 4, 4, 0.00, '2007-01-23', '2007-02-22', 0, '', '20070127180529'),
(5, 209, 'test2', '', 0, 0, '', '', '', '', '', '', '', '', '', 2, 4, 0.00, '2007-01-23', '2007-02-22', 0, '', '20070127180529');

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
(25, 526, 3, 1, 10, 527, 0, 1, 0, 1, 1, 0x663533, 528, 5, 0, '|608|609|610|611|612|', '', '', '', 7),
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

INSERT INTO `dsb_queue_email` (`mail_id`, `to`, `subject`, `message_body`, `date_added`) VALUES (1, 'dan@rdsct.ro', 'Your profile was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', '20061222002332'),
(2, 'dan@sco.ro', 'r', '<html dir="ltr">\r\n    <head>\r\n    </head>\r\n    <body>\r\n        &lt;a href=&quot;&quot;&gt;asd&lt;/a&gt;<br />\r\n        <br />\r\n        asd<br />\r\n        asd<br />\r\n        asd<br />\r\n        &quot; ''<br />\r\n    </body>\r\n</html>', '20070124232308'),
(3, 'dan@rdsct.ro', 'r', '<html dir="ltr">\r\n    <head>\r\n    </head>\r\n    <body>\r\n        &lt;a href=&quot;&quot;&gt;asd&lt;/a&gt;<br />\r\n        <br />\r\n        asd<br />\r\n        asd<br />\r\n        asd<br />\r\n        &quot; ''<br />\r\n    </body>\r\n</html>', '20070124232308'),
(4, 'dan@rdsct.ro', 'r', '<html dir="ltr">\r\n    <head>\r\n    </head>\r\n    <body>\r\n        &lt;a href=&quot;&quot;&gt;asd&lt;/a&gt;<br />\r\n        <br />\r\n        asd<br />\r\n        asd<br />\r\n        asd<br />\r\n        &quot; ''<br />\r\n    </body>\r\n</html>', '20070124232308'),
(5, 'dan@rdsct.ro', 'Web Application: One of your photos was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', '20070130134403'),
(6, 'dan@rdsct.ro', 'Web Application: One of your photos was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', '20070130161551');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_queue_message`
-- 

DROP TABLE IF EXISTS `dsb_queue_message`;
CREATE TABLE `dsb_queue_message` (
  `mail_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_other` int(10) unsigned NOT NULL default '0',
  `_user_other` varchar(48) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `message_body` text NOT NULL,
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `message_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mail_id`),
  KEY `from_id` (`fk_user_id_other`),
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_queue_message`
-- 

INSERT INTO `dsb_queue_message` (`mail_id`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 1, 2, 'test', 'test subj', 'test body\r\n', '2006-11-02 11:54:47', 0),
(2, 1, 2, 'test', 'sdsd', 'asdasd', '2006-11-02 11:58:46', 0),
(3, 1, 2, 'test', 'sdsd', '[quote]asdasd[/quote]', '2006-11-03 21:00:19', 0),
(4, 1, 2, 'test', 'test subj', '[quote]test body\r\n[/quote]', '2006-11-03 21:01:24', 0),
(5, 2, 2, 'test', 'test subjasd', '\r\n[quote]test body\r\n[/quote]', '2006-11-04 11:07:26', 0),
(6, 1, 2, 'test', 'You have received a flirt from test', 'Let''s rock and roll!', '2007-02-11 18:56:11', 2);

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
(3, 0x6d616e75616c5f70726f66696c655f617070726f76616c, '1', 'New profiles or changes to existing profiles require manual approval from an administrator before being displayed on site?', 1, 0x636f7265),
(4, 0x646174655f666f726d6174, '%m/%d/%Y', 'Default date format', 2, 0x636f7265),
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
(28, 0x69735f64656661756c74, '0', 'Is this skin the default site skin?', 0, 0x736b696e5f6261736963),
(32, 0x6d696e5f73697a65, '', 'Minimum photo file size in bytes (use 0 for not limited).', 2, 0x636f72655f70686f746f),
(33, 0x6d61785f73697a65, '', 'Maximum photo file size in bytes (use 0 for server default).', 2, 0x636f72655f70686f746f),
(34, 0x6262636f64655f6d657373616765, '1', 'Allow BBCode in member to member messages?', 1, 0x636f7265),
(35, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 'Date and time format', 2, 0x636f7265),
(36, 0x726f756e645f636f726e657273, '1', 'Use round corners for user photos?', 1, 0x636f72655f70686f746f),
(37, 0x656e61626c6564, '1', 'Enable this widget?', 1, 0x646967675f74656368),
(38, 0x666565645f75726c, 'http://digg.com/rss/containertechnology.xml', 'The url of the feed', 2, 0x646967675f74656368),
(39, 0x736b696e5f646972, 'def', '', 0, 0x736b696e5f646566),
(40, 0x736b696e5f6e616d65, 'Default', '', 0, 0x736b696e5f646566),
(41, 0x666b5f6c6f63616c655f6964, '11', '', 0, 0x736b696e5f646566),
(42, 0x69735f64656661756c74, '1', '', 0, 0x736b696e5f646566),
(43, 0x696e6163746976655f74696d65, '5', 'Time of inactivity in minutes after a member is considered offline', 2, 0x636f7265);

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

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES ('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,209', 0, '20070207155746'),
('8be66aca9a1a9003f72585c258c916a5', 1, 'a:1:{s:11:"acclevel_id";i:17;}', '1,2,209', 2, '20070211142940');

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
  `email` varchar(128) NOT NULL default '',
  `skin` varchar(32) NOT NULL default '',
  `temp_pass` varchar(32) NOT NULL default '',
  `last_activity` timestamp(14) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user` (`user`)
) TYPE=MyISAM COMMENT='membership is m_value';

-- 
-- Dumping data for table `dsb_user_accounts`
-- 

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES (1, 0x64616e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@sco.ro', '', '', '20070211144000'),
(2, 0x74657374, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@rdsct.ro', 'basic', '', '20070211212228'),
(209, 0x7465737432, 0x3662343238383630323064303630386435646138373431633464353564303563, 15, 4, 'dan@rdsct.ro', '', '', '20070207155756');

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
  UNIQUE KEY `fk_user_id` (`fk_user_id`,`folder`)
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

INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES (4, 0, 1, 0, 'Admin', 'crcr', 'mrmr [b]ala bala[/b] [u]portocala[/u]', '2007-01-24 14:26:46', 3, 0, 0),
(5, 1, 2, 0, 'Admin', 'crcr', 'mrmr [b]ala bala[/b] [u]portocala[/u]', '2007-01-24 14:26:46', 3, 0, 0),
(6, 0, 209, 0, 'Admin', 'crcr', 'mrmr [b]ala bala[/b] [u]portocala[/u]', '2007-01-24 14:26:46', 3, 0, 0);

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
  PRIMARY KEY  (`mtpl_id`),
  KEY `fk_user_id` (`fk_user_id`)
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
(5, 2, 2, 'test', 'Re: sdsd', '\r\n[quote]asdasd[/quote]', '2007-01-15 19:51:25', 0),
(6, 2, 1, 'dan', 'You have received a flirt from test', 'Aye aye, mate!', '2007-02-11 18:40:25', 2),
(7, 2, 1, 'dan', 'You have received a flirt from test', 'Let''s rock and roll!', '2007-02-11 18:44:11', 2),
(8, 2, 1, 'dan', 'hello again', 'this is a hello message\r\n', '2007-02-11 18:48:49', 0),
(9, 2, 1, 'dan', 'You have received a flirt from test', 'Let''s rock and roll!', '2007-02-11 18:56:11', 2);

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

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `caption`, `status`, `reject_reason`, `stat_views`, `stat_comments`, `date_posted`, `last_changed`, `del`) VALUES (63, 2, 'test', '7/2_11170336112.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-01 13:21:52', '2007-02-01 13:24:09', 0),
(62, 2, 'test', '6/2_11170336020.jpg', 1, 1, 0, '', 15, '', 0, 0, '2007-02-01 13:20:26', '2007-02-01 15:56:19', 0),
(67, 1, 'dan', '9/1_21171197584.jpg', 1, 0, 0, '', 15, '', 0, 0, '2007-02-11 12:39:49', '2007-02-11 12:40:00', 0),
(66, 1, 'dan', '1/1_11171197584.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 12:39:49', '2007-02-11 12:40:00', 0),
(68, 2, 'test', '4/2_11171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(69, 2, 'test', '6/2_21171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(70, 2, 'test', '8/2_31171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(71, 2, 'test', '5/2_41171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(72, 2, 'test', '8/2_51171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(73, 2, 'test', '3/2_61171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0);

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
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
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

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `del`, `field_46`, `field_47`, `field_48`, `field_50_country`, `field_50_state`, `field_50_city`, `field_50_zip`, `f51`, `f52`, `f53`, `f54`, `f55`, `f56`) VALUES (1, 1, 15, '2007-02-11 12:40:00', '2007-02-11 14:40:00', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'dan', '9/1_21171197584.jpg', 0.0000000000, 0.0000000000, 1, 0, 0, '', NULL, 0, 0, 0, '', 0, 0, 0, 0, '', ''),
(3, 2, 15, '2007-02-07 15:58:01', '2007-02-11 14:37:59', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test', '6/2_11170336020.jpg', -93.6367034912, 42.0276985168, 1, 0, 2, '|1|', '1986-01-03', 218, 16, 7089, '50010', 1, 1, 1, 1, '', 'asd1'),
(620, 209, 15, '2007-02-07 15:57:51', '2007-02-07 15:57:51', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test2', '', 0.0000000000, 0.0000000000, 0, 0, 2, '|1|', '1981-04-05', 206, 0, 0, '', 0, 0, 0, 0, '', '');

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

