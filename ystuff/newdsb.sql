-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 11, 2007 at 09:33 PM
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

INSERT INTO `dsb_feed_cache` (`module_code`, `feed_xml`, `update_time`) VALUES (0x646967675f74656368, '<?xml version="1.0" encoding="UTF-8"?>\n<rss version="2.0" xmlns:digg="http://digg.com/docs/diggrss/">\n<channel>\n<title>Digg / Technology</title>\n<language>en-us</language><link>http://digg.com/view/technology</link>\n<description>Digg / Technology</description>\n<item>\n<title>Abu Dhabi, United Arab Emirates: A $27 Billion Vision in the Desert</title>\n<link>http://digg.com/design/Abu_Dhabi_United_Arab_Emirates_A_27_Billion_Vision_in_the_Desert</link>\n<description>&quot;Abu Dhabi has an unlikely proposal for the Middle East. It involves $27 billion, the world’s top architects and a brand-new city of culture. Over the next decade or so it aims to become one of the great cultural centers of the Middle East: the heir, in its way, to cosmopolitan cities of old like Beirut, Cairo and Baghdad.&quot;</description>\n<pubDate>Mon, 5 Feb 2007 21:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Abu_Dhabi_United_Arab_Emirates_A_27_Billion_Vision_in_the_Desert</guid>\n<digg:diggCount>158</digg:diggCount>\n<digg:submitter><digg:username>supernova17</digg:username><digg:userimage>http://digg.com/userimages/supernova17/medium3370.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>26</digg:commentCount>\n</item>\n<item>\n<title>This billboard will cause some traffic!!!!</title>\n<link>http://digg.com/design/This_billboard_will_cause_some_traffic</link>\n<description>This billboard created by Grey Worldwide for a lubricating cream is sure to cause some traffic!</description>\n<pubDate>Mon, 5 Feb 2007 21:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/This_billboard_will_cause_some_traffic</guid>\n<digg:diggCount>593</digg:diggCount>\n<digg:submitter><digg:username>insomnie</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>67</digg:commentCount>\n</item>\n<item>\n<title>Private Jet Built For Two For Mile-high Wannabes</title>\n<link>http://digg.com/gadgets/Private_Jet_Built_For_Two_For_Mile_high_Wannabes</link>\n<description>Fancy getting intimate at high altitude, but don''t like the idea of doing it in a toilet cubicle or fumbling under a blanket in between trolley services? A new company is to offer passengers the chance to join the mile-high club in style - and privacy - on a private jet built for two.</description>\n<pubDate>Mon, 5 Feb 2007 20:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/Private_Jet_Built_For_Two_For_Mile_high_Wannabes</guid>\n<digg:diggCount>284</digg:diggCount>\n<digg:submitter><digg:username>charbarred</digg:username><digg:userimage>http://digg.com/userimages/c/h/a/charbarred/medium3379.jpg</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>25</digg:commentCount>\n</item>\n<item>\n<title>What about a huge URL?</title>\n<link>http://digg.com/gadgets/What_about_a_huge_URL</link>\n<description>Bored with tiny, friendly and nice urls? Not amazed by tinyurl.com? This website makes any url 250+ characters long, and returned addresses work! Just for fun.</description>\n<pubDate>Mon, 5 Feb 2007 20:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/What_about_a_huge_URL</guid>\n<digg:diggCount>380</digg:diggCount>\n<digg:submitter><digg:username>phjr</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>44</digg:commentCount>\n</item>\n<item>\n<title>Mind Blowing Eyewitness Accounts From Hiroshima Atomic Bomb</title>\n<link>http://digg.com/security/Mind_Blowing_Eyewitness_Accounts_From_Hiroshima_Atomic_Bomb</link>\n<description>Random fire tornadoes, walls melting in brilliant colors, people desperately drinking black rain drops, a boy who crawled home to his mother because the soles of his feet were the only part burnt, the rapid appearance of maggots in wounds – it sounds like some twisted acid trip; much more chaotic then it appears from a distance.</description>\n<pubDate>Mon, 5 Feb 2007 20:11:13 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/Mind_Blowing_Eyewitness_Accounts_From_Hiroshima_Atomic_Bomb</guid>\n<digg:diggCount>777</digg:diggCount>\n<digg:submitter><digg:username>TheWalkingDude</digg:username><digg:userimage>http://digg.com/userimages/thewalkingdude/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>167</digg:commentCount>\n</item>\n<item>\n<title>Showdown: Mac Media Center Applications</title>\n<link>http://digg.com/apple/Showdown_Mac_Media_Center_Applications</link>\n<description>The concept of Media Center applications has been around for ages on the Windows and Linux side. However, not many developers have created Media Center apps for the Mac. Here we take a look at the options for the Mac. iTheater, CenterStage, Front Row, and MediaCentral. Which one comes out on top?</description>\n<pubDate>Mon, 5 Feb 2007 19:50:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Showdown_Mac_Media_Center_Applications</guid>\n<digg:diggCount>312</digg:diggCount>\n<digg:submitter><digg:username>Glenn</digg:username><digg:userimage>http://digg.com/userimages/glenn/medium6842.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>29</digg:commentCount>\n</item>\n<item>\n<title>Amarok 1.4.5 released, 2.0 development started</title>\n<link>http://digg.com/linux_unix/Amarok_1_4_5_released_2_0_development_started</link>\n<description>Amarok 1.4.5 has just been released. This version adds some long requested features: A shoutcast stream browser and support for user defined labels (aka tags). The developers have already started to work on the next major release based upon Qt4/KDE4, which is going to run on Linux, OSX, and Windows!</description>\n<pubDate>Mon, 5 Feb 2007 19:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Amarok_1_4_5_released_2_0_development_started</guid>\n<digg:diggCount>419</digg:diggCount>\n<digg:submitter><digg:username>nightrose</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>41</digg:commentCount>\n</item>\n<item>\n<title>Microsoft CEO, Steve Ballmer''s office is SOOOOO small! see photo...</title>\n<link>http://digg.com/tech_news/Microsoft_CEO_Steve_Ballmer_s_office_is_SOOOOO_small_see_photo</link>\n<description>Can you believe how small this guy''s office is?  Mine is bigger than that.</description>\n<pubDate>Mon, 5 Feb 2007 19:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Microsoft_CEO_Steve_Ballmer_s_office_is_SOOOOO_small_see_photo</guid>\n<digg:diggCount>1460</digg:diggCount>\n<digg:submitter><digg:username>dcadorniga</digg:username><digg:userimage>http://digg.com/userimages/dcadorniga/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>216</digg:commentCount>\n</item>\n<item>\n<title>Digg the Bomb (LED Sign)</title>\n<link>http://digg.com/hardware/Digg_the_Bomb_LED_Sign</link>\n<description>After the whole ATHF &quot;Bomb Hoax in Boston&quot; thing (Hilarious by the way), I couldn''t resist making my own &quot;sinister looking device&quot; for Digg and maybe mail it over for the guys at diggnation to realize how REALLY bright this thing is.  It''s very simple to make your own.  Paint, perfboard, LEDs, wire, hot glue, batteries, and their holder.  See pics.</description>\n<pubDate>Mon, 5 Feb 2007 18:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Digg_the_Bomb_LED_Sign</guid>\n<digg:diggCount>1374</digg:diggCount>\n<digg:submitter><digg:username>WeDooWeDoo</digg:username><digg:userimage>http://digg.com/userimages/w/e/d/wedoowedoo/medium9218.jpg</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>99</digg:commentCount>\n</item>\n<item>\n<title>Is Windows losing out and Linux gaining?</title>\n<link>http://digg.com/linux_unix/Is_Windows_losing_out_and_Linux_gaining</link>\n<description>The penguin’s come of age. What began as a battle between proprietary and open source Linux software, started by geeks around the world, isn’t plain tech rhetoric anymore. It’s now a mainstream commercial platform — a technology that enterprises are taking very seriously and looking at as a major cost-effective solution that has scalability and a g</description>\n<pubDate>Mon, 5 Feb 2007 18:47:12 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Is_Windows_losing_out_and_Linux_gaining</guid>\n<digg:diggCount>457</digg:diggCount>\n<digg:submitter><digg:username>mynamefat</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>108</digg:commentCount>\n</item>\n<item>\n<title>2 Days After DiggTaggr''s Public Alpha Release: New Features and a Redesign</title>\n<link>http://digg.com/software/2_Days_After_DiggTaggr_s_Public_Alpha_Release_New_Features_and_a_Redesign</link>\n<description>The public Alpha of DiggTaggr (the 3rd party tool that displays links to relevant Digg stories) was launched on Friday. Two days later, many users'' requests have been implemented! The interface was completely redesigned, now below the summary, instead of to the right (for smaller resolution). Also: Digg count and story summaries included!</description>\n<pubDate>Mon, 5 Feb 2007 18:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/2_Days_After_DiggTaggr_s_Public_Alpha_Release_New_Features_and_a_Redesign</guid>\n<digg:diggCount>332</digg:diggCount>\n<digg:submitter><digg:username>invader</digg:username><digg:userimage>http://digg.com/userimages/invader/medium1113.jpg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>45</digg:commentCount>\n</item>\n<item>\n<title>Open Source Is democratising Knowledge</title>\n<link>http://digg.com/linux_unix/Open_Source_Is_democratising_Knowledge</link>\n<description>In September 1991, when Linux Torvalds, a student at the University of Helsinki in Finland, released 10,000 lines of code on the Internet, nobody could have believed that it would spark off a revolution. In the 15 years since then, Linux has grown into an enormously capable operating system that contains more than 100 million lines of code.</description>\n<pubDate>Mon, 5 Feb 2007 17:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Open_Source_Is_democratising_Knowledge</guid>\n<digg:diggCount>389</digg:diggCount>\n<digg:submitter><digg:username>jrepin</digg:username><digg:userimage>http://digg.com/userimages/jrepin/medium.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>80</digg:commentCount>\n</item>\n<item>\n<title>Digg Launches New UI</title>\n<link>http://digg.com/tech_news/Digg_Launches_New_UI</link>\n<description>As part of the Digg.com updates today, the view of a story''s info when a user is viewing the Comments Section has been updated, to provide a cleaner, more modern feel; and to make the information easier to take in.</description>\n<pubDate>Mon, 5 Feb 2007 17:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Digg_Launches_New_UI</guid>\n<digg:diggCount>1331</digg:diggCount>\n<digg:submitter><digg:username>andrewcod</digg:username><digg:userimage>http://digg.com/userimages/a/n/d/andrewcod/medium5307.jpeg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>213</digg:commentCount>\n</item>\n<item>\n<title>A List of Free Coin Counting Services in Every State</title>\n<link>http://digg.com/mods/A_List_of_Free_Coin_Counting_Services_in_Every_State</link>\n<description>In response to the article posted to Digg describing how to hack Coinstar machines, I present you with a list of banks/businesses that offer FREE coin counting.  Not only do you save money by not having to use Coinstar, but you can avoid those nasty Theft of Services criminal charges you would get by hacking the machine!   Enjoy.</description>\n<pubDate>Mon, 5 Feb 2007 17:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/mods/A_List_of_Free_Coin_Counting_Services_in_Every_State</guid>\n<digg:diggCount>625</digg:diggCount>\n<digg:submitter><digg:username>kajoob</digg:username><digg:userimage>http://digg.com/userimages/kajoob/medium1218.jpg</digg:userimage></digg:submitter>\n<digg:category>Mods</digg:category>\n<digg:commentCount>49</digg:commentCount>\n</item>\n<item>\n<title>It''s official: Apple and The Beatles kiss and make up</title>\n<link>http://digg.com/apple/It_s_official_Apple_and_The_Beatles_kiss_and_make_up</link>\n<description>Apple Inc. and The Beatles’ company Apple Corps Ltd. said Monday that they are pleased to announce the parties have entered into a new agreement concerning the use of the name “Apple.” and apple logos.</description>\n<pubDate>Mon, 5 Feb 2007 16:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/It_s_official_Apple_and_The_Beatles_kiss_and_make_up</guid>\n<digg:diggCount>1354</digg:diggCount>\n<digg:submitter><digg:username>ojames</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>61</digg:commentCount>\n</item>\n<item>\n<title>Negative Captcha</title>\n<link>http://digg.com/programming/Negative_Captcha</link>\n<description>Here''s an interesting thought. The flip side of &quot;prove you aren''t a robot,&quot; is &quot;prove you are human.&quot;  Though it''s no easier to prove, at least it places the onus of proof on the spam bot and not your human guests. Use CSS and a hidden dummy email field to fake out the machines.</description>\n<pubDate>Mon, 5 Feb 2007 15:50:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/programming/Negative_Captcha</guid>\n<digg:diggCount>532</digg:diggCount>\n<digg:submitter><digg:username>spinchange</digg:username><digg:userimage>http://digg.com/userimages/spinchange/medium8749.jpg</digg:userimage></digg:submitter>\n<digg:category>Programming</digg:category>\n<digg:commentCount>64</digg:commentCount>\n</item>\n<item>\n<title>SecureIX Offers Anonymous BitTorrent Downloads</title>\n<link>http://digg.com/software/SecureIX_Offers_Anonymous_BitTorrent_Downloads</link>\n<description>A company called SecureIX is offering a free VPN service which allows you to hide your IP address from peers in a BitTorrent swarm or P2P network. Not only that, the service also encrypts and tunnels your data, making it extremely difficult for your ISP to sniff or shape it.</description>\n<pubDate>Mon, 5 Feb 2007 15:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/SecureIX_Offers_Anonymous_BitTorrent_Downloads</guid>\n<digg:diggCount>759</digg:diggCount>\n<digg:submitter><digg:username>koregaonpark</digg:username><digg:userimage>http://digg.com/userimages/koregaonpark/medium6062.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>72</digg:commentCount>\n</item>\n<item>\n<title>Photograph of a lightbulb burning out</title>\n<link>http://digg.com/design/Photograph_of_a_lightbulb_burning_out</link>\n<description>To create the shot, my friend Harley and I removed the glass enclosure of a standard household lightbulb (while leaving the innards intact) and powered it up in a pitch black room. The result was an immediate burn-out, which we were all to ready to photograph.</description>\n<pubDate>Mon, 5 Feb 2007 15:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Photograph_of_a_lightbulb_burning_out</guid>\n<digg:diggCount>2686</digg:diggCount>\n<digg:submitter><digg:username>LeggNet</digg:username><digg:userimage>http://digg.com/userimages/leggnet/medium3193.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>66</digg:commentCount>\n</item>\n<item>\n<title>A futuristic home for $32,000 - the micro compact home</title>\n<link>http://digg.com/hardware/A_futuristic_home_for_32_000_the_micro_compact_home</link>\n<description>It''s only in Europe at the moment, but coming to the US soon. For €25k you can have a living cube with sleek minimalist design with a tiny footprint. I can imagine all sorts of uses for these - a cabin in the woods, student housing, how about putting one on a Manhattan rooftop?</description>\n<pubDate>Mon, 5 Feb 2007 15:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/A_futuristic_home_for_32_000_the_micro_compact_home</guid>\n<digg:diggCount>1123</digg:diggCount>\n<digg:submitter><digg:username>felchdonkey</digg:username><digg:userimage>http://digg.com/userimages/felchdonkey/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>161</digg:commentCount>\n</item>\n<item>\n<title>8 Reasons Why Idiot Masses Love Lists</title>\n<link>http://digg.com/design/8_Reasons_Why_Idiot_Masses_Love_Lists</link>\n<description>We all know how much people in general love lists ... but have you ever thought why? Well now you don''t have to, because I have, and I made a list about it.</description>\n<pubDate>Mon, 5 Feb 2007 15:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/8_Reasons_Why_Idiot_Masses_Love_Lists</guid>\n<digg:diggCount>712</digg:diggCount>\n<digg:submitter><digg:username>bootlick</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>65</digg:commentCount>\n</item>\n<item>\n<title>Issue 16 of Free Software Magazine is out</title>\n<link>http://digg.com/linux_unix/Issue_16_of_Free_Software_Magazine_is_out</link>\n<description>13 new articles about free software are available to read for free.</description>\n<pubDate>Mon, 5 Feb 2007 15:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Issue_16_of_Free_Software_Magazine_is_out</guid>\n<digg:diggCount>461</digg:diggCount>\n<digg:submitter><digg:username>chieffy</digg:username><digg:userimage>http://digg.com/userimages/c/h/i/chieffy/medium7603.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>22</digg:commentCount>\n</item>\n<item>\n<title>I hate Macs</title>\n<link>http://digg.com/apple/I_hate_Macs</link>\n<description>you have to love British humor...</description>\n<pubDate>Mon, 5 Feb 2007 14:10:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/I_hate_Macs</guid>\n<digg:diggCount>4222</digg:diggCount>\n<digg:submitter><digg:username>WocEnasni</digg:username><digg:userimage>http://digg.com/userimages/wocenasni/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>681</digg:commentCount>\n</item>\n<item>\n<title>Super Bowl Ad Watch: Top Spots</title>\n<link>http://digg.com/gadgets/Super_Bowl_Ad_Watch_Top_Spots</link>\n<description>Here are the Gizmodo Super Bowl Spot Awards, were we picked our favorites, giving each one its own special designation. You''ll be able to click and play each one of our embedded selections, and don''t forget to check our our pick for best commercial of the night.</description>\n<pubDate>Mon, 5 Feb 2007 14:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/Super_Bowl_Ad_Watch_Top_Spots</guid>\n<digg:diggCount>1138</digg:diggCount>\n<digg:submitter><digg:username>tempamatic</digg:username><digg:userimage>http://digg.com/userimages/t/e/m/tempamatic/medium4420.jpg</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>103</digg:commentCount>\n</item>\n<item>\n<title>Amazing Folding Couch! Unbelievable Video!</title>\n<link>http://digg.com/design/Amazing_Folding_Couch_Unbelievable_Video</link>\n<description>The sofa appears to be made out of hundreds of thin, flat chair-shaped silhouettes, joined together by some kind of membrane in an accordion that can collapse down into a dictionary-think chunk or be spread out for feet on end, as a divan, chair or sofa. (via BoingBoing)</description>\n<pubDate>Mon, 5 Feb 2007 13:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Amazing_Folding_Couch_Unbelievable_Video</guid>\n<digg:diggCount>2952</digg:diggCount>\n<digg:submitter><digg:username>ani-pockdotnet</digg:username><digg:userimage>http://digg.com/userimages/ani-pockdotnet/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>118</digg:commentCount>\n</item>\n<item>\n<title>Songs from iPod commercials</title>\n<link>http://digg.com/apple/Songs_from_iPod_commercials</link>\n<description>iTunes links for songs from every Apple iPod commercial.</description>\n<pubDate>Mon, 5 Feb 2007 12:20:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Songs_from_iPod_commercials</guid>\n<digg:diggCount>814</digg:diggCount>\n<digg:submitter><digg:username>sdd27</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>38</digg:commentCount>\n</item>\n<item>\n<title>Spanning Sync Public Beta (Re)Opens</title>\n<link>http://digg.com/apple/Spanning_Sync_Public_Beta_Re_Opens</link>\n<description>The title says it. I guess quiet a bunch of us were waiting for this Software. So far it works like a charm.</description>\n<pubDate>Mon, 5 Feb 2007 10:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Spanning_Sync_Public_Beta_Re_Opens</guid>\n<digg:diggCount>361</digg:diggCount>\n<digg:submitter><digg:username>jendrik</digg:username><digg:userimage>http://digg.com/userimages/jendrik/medium6953.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>34</digg:commentCount>\n</item>\n<item>\n<title>Review: Intype - TextMate for Windows?</title>\n<link>http://digg.com/software/Review_Intype_TextMate_for_Windows</link>\n<description>Intype is powerful and intuitive code editor for Windows with lightning fast response. It''s in the early stages of development but well on its way to becoming &quot;TextMate for Windows&quot;.</description>\n<pubDate>Mon, 5 Feb 2007 08:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Review_Intype_TextMate_for_Windows</guid>\n<digg:diggCount>417</digg:diggCount>\n<digg:submitter><digg:username>opensourcemaven</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>67</digg:commentCount>\n</item>\n<item>\n<title>Buy from OverStock and Save -37% (negative 37%)</title>\n<link>http://digg.com/tech_deals/Buy_from_OverStock_and_Save_37_negative_37</link>\n<description>Buy from OverStock and Save -37% (negative 37%) - look at the link. Is it genuine genuis or real sloppy system design flaw ?</description>\n<pubDate>Mon, 5 Feb 2007 07:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_deals/Buy_from_OverStock_and_Save_37_negative_37</guid>\n<digg:diggCount>830</digg:diggCount>\n<digg:submitter><digg:username>shashark</digg:username><digg:userimage>http://digg.com/userimages/s/h/a/shashark/medium3672.png</digg:userimage></digg:submitter>\n<digg:category>Tech Deals</digg:category>\n<digg:commentCount>90</digg:commentCount>\n</item>\n<item>\n<title>High res Picture of a Burning Match</title>\n<link>http://digg.com/design/High_res_Picture_of_a_Burning_Match</link>\n<description>Macrophotograph of a Burning Match</description>\n<pubDate>Mon, 5 Feb 2007 07:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/High_res_Picture_of_a_Burning_Match</guid>\n<digg:diggCount>2284</digg:diggCount>\n<digg:submitter><digg:username>RozzleDozzle</digg:username><digg:userimage>http://digg.com/userimages/r/o/z/rozzledozzle/medium6995.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>61</digg:commentCount>\n</item>\n<item>\n<title>Cool art that will mess with your head </title>\n<link>http://digg.com/design/Cool_art_that_will_mess_with_your_head_2</link>\n<description>Wow this stuff is trippy</description>\n<pubDate>Mon, 5 Feb 2007 06:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Cool_art_that_will_mess_with_your_head_2</guid>\n<digg:diggCount>1977</digg:diggCount>\n<digg:submitter><digg:username>clos</digg:username><digg:userimage>http://digg.com/userimages/clos/medium3235.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>85</digg:commentCount>\n</item>\n<item>\n<title>Hidden Feature of OS X Printing: CUPS</title>\n<link>http://digg.com/apple/Hidden_Feature_of_OS_X_Printing_CUPS</link>\n<description>A little-known secret service of OS X (and many Linux distros, as well) is CUPS, a printer management system. It is more powerful than the built-in GUI, but the GUI interfaces with the same daemon (also part of CUPS). The networking functionality and settings are really neat. Its really configurable, too, with a config file similar to Apache’s.</description>\n<pubDate>Mon, 5 Feb 2007 06:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Hidden_Feature_of_OS_X_Printing_CUPS</guid>\n<digg:diggCount>563</digg:diggCount>\n<digg:submitter><digg:username>nokrev</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>54</digg:commentCount>\n</item>\n<item>\n<title>Interview with Hamad Darwish, the man behind Vista''s Wallpapers</title>\n<link>http://digg.com/design/Interview_with_Hamad_Darwish_the_man_behind_Vista_s_Wallpapers</link>\n<description>Hamad Darwish might not be a household name, but if you use Windows Vista, then his work is on your computer. In fact, it might be even on your desktop right now! Assuming IDC’s predictions are accurate, then before the end of the year, up to 90 million people will have the chance to see Hamad’s work, and all he had to do was upload it to Flickr.</description>\n<pubDate>Mon, 5 Feb 2007 06:00:05 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Interview_with_Hamad_Darwish_the_man_behind_Vista_s_Wallpapers</guid>\n<digg:diggCount>638</digg:diggCount>\n<digg:submitter><digg:username>Brajeshwar</digg:username><digg:userimage>http://digg.com/userimages/brajeshwar/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>30</digg:commentCount>\n</item>\n<item>\n<title>Ubuntu Canada vs. Microsoft''s Ice House</title>\n<link>http://digg.com/linux_unix/Ubuntu_Canada_vs_Microsoft_s_Ice_House</link>\n<description>Ubuntu Canada/Toronto reports that their counter-marketing campaign in front of the Microsoft Ice House was an absolute success, despite initial discouragement and words from Toronto Police. Link to pics included.</description>\n<pubDate>Mon, 5 Feb 2007 06:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Ubuntu_Canada_vs_Microsoft_s_Ice_House</guid>\n<digg:diggCount>490</digg:diggCount>\n<digg:submitter><digg:username>lophyte</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>43</digg:commentCount>\n</item>\n<item>\n<title>iPhone inspires next generation iPod...Touchscreen on iPod by Fall?</title>\n<link>http://digg.com/apple/iPhone_inspires_next_generation_iPod_Touchscreen_on_iPod_by_Fall</link>\n<description>The iPod is overdue for a change. By fall, it will be two years since Apple introduced the so-called fifth-generation iPod. You may see a touch-screen iPod this fall, a few months after the buzz of the iPhone launch settles and a few months before the key holiday sales season kicks in</description>\n<pubDate>Mon, 5 Feb 2007 06:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/iPhone_inspires_next_generation_iPod_Touchscreen_on_iPod_by_Fall</guid>\n<digg:diggCount>654</digg:diggCount>\n<digg:submitter><digg:username>CLIFFosakaJAPAN</digg:username><digg:userimage>http://digg.com/userimages/cliffosakajapan/medium5296.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>65</digg:commentCount>\n</item>\n<item>\n<title>Wireless Internet for All, Without the Towers!!!</title>\n<link>http://digg.com/tech_news/Wireless_Internet_for_All_Without_the_Towers_3</link>\n<description>These still are early days for the Internet, globally speaking. One billion people online; five billion to go. The next billion to be connected are living in homes that are physically close to an Internet gateway. They await a solution to the famous “last mile” problem: extending affordable broadband service to each person’s doorstep.</description>\n<pubDate>Mon, 5 Feb 2007 04:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Wireless_Internet_for_All_Without_the_Towers_3</guid>\n<digg:diggCount>710</digg:diggCount>\n<digg:submitter><digg:username>UCBearcats</digg:username><digg:userimage>http://digg.com/userimages/ucbearcats/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>56</digg:commentCount>\n</item>\n<item>\n<title>PICTURE: Full Circle Rainbow, Really Cool!!!</title>\n<link>http://digg.com/design/PICTURE_Full_Circle_Rainbow_Really_Cool</link>\n<description>Yes those are real rainbow (full circle), not lens flare or any sort....amazing really. Talk about being in the right place at the right time. I was in Canberra on the weekend. So decided to go up to the Telstra Tower as i''ve never been there.</description>\n<pubDate>Mon, 5 Feb 2007 04:30:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/PICTURE_Full_Circle_Rainbow_Really_Cool</guid>\n<digg:diggCount>1924</digg:diggCount>\n<digg:submitter><digg:username>mattman12345</digg:username><digg:userimage>http://digg.com/userimages/m/a/t/mattman12345/medium2870.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>80</digg:commentCount>\n</item>\n<item>\n<title>Classic TSS - Kevin builds a G4 Mac that runs OS X</title>\n<link>http://digg.com/apple/Classic_TSS_Kevin_builds_a_G4_Mac_that_runs_OS_X</link>\n<description>Kevin Rose builds a G4 Mac on The Screen Savers. That''s how it was before OSX86.</description>\n<pubDate>Mon, 5 Feb 2007 04:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Classic_TSS_Kevin_builds_a_G4_Mac_that_runs_OS_X</guid>\n<digg:diggCount>1386</digg:diggCount>\n<digg:submitter><digg:username>coollettuce</digg:username><digg:userimage>http://digg.com/userimages/coollettuce/medium4659.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>89</digg:commentCount>\n</item>\n<item>\n<title>Flickr User becomes Pro for Microsoft</title>\n<link>http://digg.com/design/Flickr_User_becomes_Pro_for_Microsoft</link>\n<description>Flickr user Hamad Darwish is one of a small group of flickr users who were approached by Microsoft for either use of their photos or commissioned to to create new images for the desktop backgrounds that are included in the new Windows Vista operating system.</description>\n<pubDate>Mon, 5 Feb 2007 03:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Flickr_User_becomes_Pro_for_Microsoft</guid>\n<digg:diggCount>826</digg:diggCount>\n<digg:submitter><digg:username>japonia18</digg:username><digg:userimage>http://digg.com/userimages/j/a/p/japonia18/medium7871.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>33</digg:commentCount>\n</item>\n<item>\n<title>The Future of Personal Security</title>\n<link>http://digg.com/security/The_Future_of_Personal_Security</link>\n<description>High-tech security isn''t just for the airport anymore. Advances now coming out of the labs will help protect what''s dear to you, from your car to your kids, your dinner to your dinero.</description>\n<pubDate>Mon, 5 Feb 2007 03:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/security/The_Future_of_Personal_Security</guid>\n<digg:diggCount>395</digg:diggCount>\n<digg:submitter><digg:username>msaleem</digg:username><digg:userimage>http://digg.com/userimages/msaleem/medium9502.jpeg</digg:userimage></digg:submitter>\n<digg:category>Security</digg:category>\n<digg:commentCount>16</digg:commentCount>\n</item>\n<item>\n<title>*PIC* Kevin and Alex in GoDaddy''s Super Bowl ad</title>\n<link>http://digg.com/tech_news/PIC_Kevin_and_Alex_in_GoDaddy_s_Super_Bowl_ad_2</link>\n<description>Bonus points to Alex for wearing a Diggnation shirt! (Ignore the other one I submitted- I didn''t pay attention to it going to a page requiring a sign in.  I''m lame and hang my head in shame for that.)</description>\n<pubDate>Mon, 5 Feb 2007 03:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/PIC_Kevin_and_Alex_in_GoDaddy_s_Super_Bowl_ad_2</guid>\n<digg:diggCount>4274</digg:diggCount>\n<digg:submitter><digg:username>AriaStar</digg:username><digg:userimage>http://digg.com/userimages/a/r/i/ariastar/medium9611.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>209</digg:commentCount>\n</item>\n</channel>\n</rss>', '20070206000738');

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
(771, 622, 'skin_def', '');

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
  `sess` varchar(32) binary NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_online`
-- 

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES (0, '20070211175507', 0x3739623935316638373733653662653634626339323337396238613734366633),
(0, '20070211175526', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175537', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175541', 0x3739623935316638373733653662653634626339323337396238613734366633),
(0, '20070211175646', 0x3739623935316638373733653662653634626339323337396238613734366633),
(0, '20070211175650', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175657', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175700', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175706', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175706', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175709', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175709', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211175824', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180018', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180034', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180039', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180041', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180043', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180055', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180057', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180101', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180104', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180113', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180124', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180150', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180151', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180153', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180202', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180203', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180205', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180218', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180442', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211180603', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181414', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181422', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181522', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181553', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181556', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181600', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181743', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181745', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211181957', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182050', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182152', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182351', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182611', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182619', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182628', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211182629', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211183010', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211183231', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211183701', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211184017', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211184124', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211184246', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211184332', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211184443', 0x3739623935316638373733653662653634626339323337396238613734366633),
(2, '20070211184939', 0x3739623935316638373733653662653634626339323337396238613734366633),
(0, '20070211185027', 0x3739623935316638373733653662653634626339323337396238613734366633),
(0, '20070211190852', 0x6234396234326666613165636662303262636531333833343766336232643131),
(0, '20070211190859', 0x6234396234326666613165636662303262636531333833343766336232643131),
(0, '20070211190910', 0x6234396234326666613165636662303262636531333833343766336232643131),
(0, '20070211191042', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211191221', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211191223', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211191228', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211191231', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211191700', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211192537', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211192601', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211192605', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211192625', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211192646', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211193504', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211193516', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211193520', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211193523', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211193547', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211193624', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211194103', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211195247', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201529', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201535', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201535', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201621', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201701', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201703', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201705', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201713', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201720', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211201723', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202323', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202323', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202507', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202519', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202526', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202528', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202528', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202559', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202605', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211202607', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203025', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203210', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203210', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203218', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203221', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203223', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203224', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203327', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203329', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203331', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203644', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211203647', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204018', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204022', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204024', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204025', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204026', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204029', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204031', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204033', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204041', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204049', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204055', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204056', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204401', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204406', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204408', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204411', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204411', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204535', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204537', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204539', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204556', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204607', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204609', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204615', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204615', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204618', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204620', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204631', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204633', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204639', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204642', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204643', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204703', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204720', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204725', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204728', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204849', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204849', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211204851', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205605', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205608', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205611', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205611', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205615', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205618', 0x6234396234326666613165636662303262636531333833343766336232643131),
(2, '20070211205620', 0x6234396234326666613165636662303262636531333833343766336232643131),
(0, '20070211205947', 0x6234396234326666613165636662303262636531333833343766336232643131),
(0, '20070211212522', 0x6539353364613964636264346433353135393661396333663737393461363134);

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

INSERT INTO `dsb_site_log` (`log_id`, `fk_user_id`, `user`, `m_value`, `fk_level_id`, `ip`, `time`) VALUES (1, 0, 'test', 1, 1, 2130706433, '20070211191221');

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
(36, 0x726f756e645f636f726e657273, '0', 'Use round corners for user photos?', 1, 0x636f72655f70686f746f),
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

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES (1, 0x64616e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@sco.ro', '', '', '20070206202919'),
(2, 0x74657374, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@rdsct.ro', 'basic', '', '20070211205620'),
(209, 0x7465737432, 0x3662343238383630323064303630386435646138373431633464353564303563, 5, 4, 'dan@rdsct.ro', '', '', '20070206202919');

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
(62, 2, 'test', '6/2_11170336020.jpg', 1, 1, 0, '', 15, '', 0, 0, '2007-02-01 13:20:26', '2007-02-01 15:56:19', 0);

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
  `date_added` timestamp(14) NOT NULL,
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

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `del`, `field_46`, `field_47`, `field_48`, `field_50_country`, `field_50_state`, `field_50_city`, `field_50_zip`, `f51`, `f52`, `f53`, `f54`, `f55`, `f56`) VALUES (1, 1, 15, '2007-01-08 16:50:11', '20070205205928', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'dan', '', 0.0000000000, 0.0000000000, 0, 0, 0, '', NULL, 0, 0, 0, '', 0, 0, 0, 0, '', ''),
(3, 2, 5, '2007-02-02 12:40:39', '20070205205928', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test', '6/2_11170336020.jpg', -93.6367034912, 42.0276985168, 0, 0, 2, '|1|', '1986-01-03', 218, 16, 7089, '50010', 1, 1, 1, 1, '', 'asd1'),
(620, 209, 10, '2006-12-22 00:23:32', '20070205205928', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test2', '', 0.0000000000, 0.0000000000, 0, 0, 2, '|1|', '1981-04-05', 206, 0, 0, '', 0, 0, 0, 0, '', '');

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

