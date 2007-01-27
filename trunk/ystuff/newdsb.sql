-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 27, 2007 at 05:56 PM
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
  `feed_url` varchar(255) binary NOT NULL default '',
  `feed_xml` text NOT NULL,
  `update_time` timestamp(14) NOT NULL,
  PRIMARY KEY  (`feed_url`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_feed_cache`
-- 

INSERT INTO `dsb_feed_cache` (`feed_url`, `feed_xml`, `update_time`) VALUES (0x687474703a2f2f646967672e636f6d2f7273732f636f6e7461696e6572746563686e6f6c6f67792e786d6c, '<?xml version="1.0" encoding="UTF-8"?>\n<rss version="2.0" xmlns:digg="http://digg.com/docs/diggrss/">\n<channel>\n<title>Digg / Technology</title>\n<language>en-us</language><link>http://digg.com/view/technology</link>\n<description>Digg / Technology</description>\n<item>\n<title>WOW Starts Now! The dawn of Vista DRM</title>\n<link>http://digg.com/tech_news/WOW_Starts_Now_The_dawn_of_Vista_DRM</link>\n<description>We have all read the stories about Vista being just a glorified DRM platform. But from Monday we will start to see what effect adoption of Vista will have on users'' rights. DefectiveByDesign.org will be at the Vista launch parties in New York. Microsoft will be bringing the stars and comedians - but we won''t be laughing.</description>\n<pubDate>Sat, 27 Jan 2007 11:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/WOW_Starts_Now_The_dawn_of_Vista_DRM</guid>\n<digg:diggCount>78</digg:diggCount>\n<digg:submitter><digg:username>cmister</digg:username><digg:userimage>http://digg.com/userimages/c/m/i/cmister/medium5657.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>16</digg:commentCount>\n</item>\n<item>\n<title>Apple Offers Firmware Update to 802.11n for Free</title>\n<link>http://digg.com/apple/Apple_Offers_Firmware_Update_to_802_11n_for_Free</link>\n<description>Away with the $5 fee to update Mac computers running Intel processors from 802.11g to 802.11n - Apple has released the firmware update for Free.</description>\n<pubDate>Sat, 27 Jan 2007 10:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Apple_Offers_Firmware_Update_to_802_11n_for_Free</guid>\n<digg:diggCount>75</digg:diggCount>\n<digg:submitter><digg:username>nexlogic</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>14</digg:commentCount>\n</item>\n<item>\n<title>59% of Americans say parking in a fire lane is worse than pirating movies</title>\n<link>http://digg.com/tech_news/59_of_Americans_say_parking_in_a_fire_lane_is_worse_than_pirating_movies</link>\n<description>Only 40 percent of Americans polled by Toronto-based Solutions Research Group agreed that downloading copyrighted movies on the Internet was a &quot;very serious offense.&quot;</description>\n<pubDate>Sat, 27 Jan 2007 10:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/59_of_Americans_say_parking_in_a_fire_lane_is_worse_than_pirating_movies</guid>\n<digg:diggCount>213</digg:diggCount>\n<digg:submitter><digg:username>sockpuppets</digg:username><digg:userimage>http://digg.com/userimages/s/o/c/sockpuppets/medium3968.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>41</digg:commentCount>\n</item>\n<item>\n<title>Addicted to Digg? Here Are 5 &quot;Must Have&quot; Tools for You!</title>\n<link>http://digg.com/software/Addicted_to_Digg_Here_Are_5_Must_Have_Tools_for_You</link>\n<description>Like most of you, I myself, am also addicted to digg. I can easily spend hours going through the queues and front page stories while at the same time obsessively updating my submitted stories. Therefore, I have put together a list of applications that can help ease your digg addiction.  These are awesome applications and tools.</description>\n<pubDate>Sat, 27 Jan 2007 08:50:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Addicted_to_Digg_Here_Are_5_Must_Have_Tools_for_You</guid>\n<digg:diggCount>336</digg:diggCount>\n<digg:submitter><digg:username>econoar</digg:username><digg:userimage>http://digg.com/userimages/e/c/o/econoar/medium5809.gif</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>26</digg:commentCount>\n</item>\n<item>\n<title>Evolutionary musical organism</title>\n<link>http://digg.com/design/Evolutionary_musical_organism</link>\n<description>Bacterial Orchestra is a self-organizing evolutionary musical organism made of audio cells. Every cell -consisting of microphone and a loudspeaker- listens to its surroundings and picks up sounds trying to play them back in sync with what it hears.</description>\n<pubDate>Sat, 27 Jan 2007 08:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Evolutionary_musical_organism</guid>\n<digg:diggCount>195</digg:diggCount>\n<digg:submitter><digg:username>Alexius</digg:username><digg:userimage>http://digg.com/userimages/alexius/medium1140.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>25</digg:commentCount>\n</item>\n<item>\n<title>Back Up Linux And Windows Systems With BackupPC</title>\n<link>http://digg.com/linux_unix/Back_Up_Linux_And_Windows_Systems_With_BackupPC</link>\n<description>This tutorial shows how you can back up Linux and Windows systems with BackupPC. BackupPC acts as a server and is installed on a Linux system, and from there it can connect to all Linux and Windows systems in your local network to back them up and restore them. Supports full and incremental backups and comes with a powerful web frontend.</description>\n<pubDate>Sat, 27 Jan 2007 07:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Back_Up_Linux_And_Windows_Systems_With_BackupPC</guid>\n<digg:diggCount>204</digg:diggCount>\n<digg:submitter><digg:username>hausmasta</digg:username><digg:userimage>http://digg.com/userimages/h/a/u/hausmasta/medium3897.gif</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>16</digg:commentCount>\n</item>\n<item>\n<title>Microsoft emails reveal Tiger envy</title>\n<link>http://digg.com/apple/Microsoft_emails_reveal_Tiger_envy</link>\n<description>Two and a half years ago, Microsoft executives were privately green with envy over the features soon to be released in Mac OS 10.4 Tiger, as revealed in a series of emails submitted as evidence in the Iowa antitrust lawsuit.</description>\n<pubDate>Sat, 27 Jan 2007 07:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Microsoft_emails_reveal_Tiger_envy</guid>\n<digg:diggCount>100</digg:diggCount>\n<digg:submitter><digg:username>vocaro</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>15</digg:commentCount>\n</item>\n<item>\n<title>Who Sucks More - GoDaddy or MySpace?</title>\n<link>http://digg.com/tech_news/Who_Sucks_More_GoDaddy_or_MySpace</link>\n<description>We learned today that MySpace was responsible for pulling down a security website. Seclists.org, which archives security mailing lists, had its domain suspended by its registrar GoDaddy for ‘violation of the GoDaddy.com Abuse Policy’.</description>\n<pubDate>Sat, 27 Jan 2007 06:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Who_Sucks_More_GoDaddy_or_MySpace</guid>\n<digg:diggCount>84</digg:diggCount>\n<digg:submitter><digg:username>charbarred</digg:username><digg:userimage>http://digg.com/userimages/c/h/a/charbarred/medium3379.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>14</digg:commentCount>\n</item>\n<item>\n<title>Freakin Awsome : Your Desktop on Steroids.</title>\n<link>http://digg.com/software/Freakin_Awsome_Your_Desktop_on_Steroids</link>\n<description>You have to see this....</description>\n<pubDate>Sat, 27 Jan 2007 06:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Freakin_Awsome_Your_Desktop_on_Steroids</guid>\n<digg:diggCount>1857</digg:diggCount>\n<digg:submitter><digg:username>se1zure</digg:username><digg:userimage>http://digg.com/userimages/se1zure/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>182</digg:commentCount>\n</item>\n<item>\n<title>My Life As An RSS Junkie </title>\n<link>http://digg.com/tech_news/My_Life_As_An_RSS_Junkie</link>\n<description>&quot;My name is Kirk Biglione and I have a problem. I’m addicted to RSS.&quot;</description>\n<pubDate>Sat, 27 Jan 2007 05:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/My_Life_As_An_RSS_Junkie</guid>\n<digg:diggCount>415</digg:diggCount>\n<digg:submitter><digg:username>charbarred</digg:username><digg:userimage>http://digg.com/userimages/c/h/a/charbarred/medium3379.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>54</digg:commentCount>\n</item>\n<item>\n<title>Rumor Smashed - Apple has NOT Released The 802.11n Update For Free</title>\n<link>http://digg.com/hardware/Rumor_Smashed_Apple_has_NOT_Released_The_802_11n_Update_For_Free</link>\n<description>Apple released an Airport Extreme firmware update for all Intel-based macs yesterday, and already sites are claiming Apple''s gone and lowered $1.99 to $0.00. Not quite. An Apple contact confirmed for us that the update is not the AirPort Extreme enabler, and just updates Intel Macs for compatibility with AirPort Extreme base stations.</description>\n<pubDate>Sat, 27 Jan 2007 05:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Rumor_Smashed_Apple_has_NOT_Released_The_802_11n_Update_For_Free</guid>\n<digg:diggCount>363</digg:diggCount>\n<digg:submitter><digg:username>diskopo</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>75</digg:commentCount>\n</item>\n<item>\n<title>Top 10 Online Databases for General Research</title>\n<link>http://digg.com/tech_news/Top_10_Online_Databases_for_General_Research</link>\n<description>When writing papers for school, it’s always good to start your research online.  Many academic journals publish their articles online—which can give you a head start on research prior to heading to your local library.</description>\n<pubDate>Sat, 27 Jan 2007 05:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Top_10_Online_Databases_for_General_Research</guid>\n<digg:diggCount>447</digg:diggCount>\n<digg:submitter><digg:username>charbarred</digg:username><digg:userimage>http://digg.com/userimages/c/h/a/charbarred/medium3379.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>28</digg:commentCount>\n</item>\n<item>\n<title>Search GMail geek style</title>\n<link>http://digg.com/tech_news/Search_GMail_geek_style</link>\n<description>&quot;There you have it, the possibilities are practically infinite, that’s what I like about Google, it just provides the tools for you to enjoy.&quot;</description>\n<pubDate>Sat, 27 Jan 2007 04:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Search_GMail_geek_style</guid>\n<digg:diggCount>604</digg:diggCount>\n<digg:submitter><digg:username>boghy2k</digg:username><digg:userimage>http://digg.com/userimages/b/o/g/boghy2k/medium5371.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>28</digg:commentCount>\n</item>\n<item>\n<title>3D Linux Kernel Animation !!</title>\n<link>http://digg.com/linux_unix/3D_Linux_Kernel_Animation</link>\n<description>A short 3D animation of Linux kernel 2.4.5.</description>\n<pubDate>Sat, 27 Jan 2007 04:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/3D_Linux_Kernel_Animation</guid>\n<digg:diggCount>358</digg:diggCount>\n<digg:submitter><digg:username>buggolo</digg:username><digg:userimage>http://digg.com/userimages/b/u/g/buggolo/medium9219.jpg</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>35</digg:commentCount>\n</item>\n<item>\n<title>Leo Laporte announces his tech radio show is to be syndicated nationwide</title>\n<link>http://digg.com/tech_news/Leo_Laporte_announces_his_tech_radio_show_is_to_be_syndicated_nationwide</link>\n<description>&quot;I''m pleased to announce that Premiere Radio Networks - the biggest radio syndicator in the US - has picked up my KFI radio show for syndication. We''re signing up affiliates now, so ask your local talk radio station to give Premiere a call. KFI listeners: the show will continue exactly as always. There just will be a few more people listening in.&quot;</description>\n<pubDate>Sat, 27 Jan 2007 03:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Leo_Laporte_announces_his_tech_radio_show_is_to_be_syndicated_nationwide</guid>\n<digg:diggCount>1534</digg:diggCount>\n<digg:submitter><digg:username>DeckardRep</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>94</digg:commentCount>\n</item>\n<item>\n<title>Apple Sends out Invites for Feb. 1st Special Event</title>\n<link>http://digg.com/apple/Apple_Sends_out_Invites_for_Feb_1st_Special_Event</link>\n<description>Link to a picture of what looks like to an Apple invite. Shows a picture of the Earth and the Moon. Also says &quot;Should old acquaintance be forgot...&quot;</description>\n<pubDate>Sat, 27 Jan 2007 03:40:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Apple_Sends_out_Invites_for_Feb_1st_Special_Event</guid>\n<digg:diggCount>160</digg:diggCount>\n<digg:submitter><digg:username>lu0s3r322</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>51</digg:commentCount>\n</item>\n<item>\n<title>Mac Shrine in his home</title>\n<link>http://digg.com/apple/Mac_Shrine_in_his_home</link>\n<description>If the term &quot;Apple fanboy&quot; were in the dictionary, a line drawing of Jeremy Mehrle could well appear beside it. Chip Chick says the St. Louis resident has 74 Macs on display, including 30 that adorn the bar pictured here. &quot;His collection contains 18 different CRT-based iMacs, a Next Cube, four different Apple II computers,</description>\n<pubDate>Sat, 27 Jan 2007 03:20:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Mac_Shrine_in_his_home</guid>\n<digg:diggCount>764</digg:diggCount>\n<digg:submitter><digg:username>dimmerswitch</digg:username><digg:userimage>http://digg.com/userimages/dimmerswitch/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>89</digg:commentCount>\n</item>\n<item>\n<title>DIGG vs Reddit - the ultimate fight</title>\n<link>http://digg.com/software/DIGG_vs_Reddit_the_ultimate_fight</link>\n<description>I think I already know the winner...</description>\n<pubDate>Sat, 27 Jan 2007 03:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/DIGG_vs_Reddit_the_ultimate_fight</guid>\n<digg:diggCount>255</digg:diggCount>\n<digg:submitter><digg:username>picktwo</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>13</digg:commentCount>\n</item>\n<item>\n<title>22&quot; LCD Multimedia Monitor - $279.99 No Rebates </title>\n<link>http://digg.com/tech_deals/22_LCD_Multimedia_Monitor_279_99_No_Rebates</link>\n<description>Killer deal. I know it''s not like the $199 at best buy on black Friday or whatever, but this is still a good deal.Port Connectors: VGA Analog 15-pin D-Sub DVI/HDCP</description>\n<pubDate>Sat, 27 Jan 2007 02:40:01 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_deals/22_LCD_Multimedia_Monitor_279_99_No_Rebates</guid>\n<digg:diggCount>629</digg:diggCount>\n<digg:submitter><digg:username>sicc</digg:username><digg:userimage>http://digg.com/userimages/s/i/c/sicc/medium5335.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Deals</digg:category>\n<digg:commentCount>83</digg:commentCount>\n</item>\n<item>\n<title>The Real First Apple Phone</title>\n<link>http://digg.com/apple/The_Real_First_Apple_Phone</link>\n<description>The first Apple Phone with a date patent listed as December 10, 1985 (more than a quarter of a century old) and filed in 1982. You’ll note in the diagram reproduced below that unlike the Apple iPhone introduced in 2007, the 1985 version was a flip-phone :-)</description>\n<pubDate>Sat, 27 Jan 2007 02:34:20 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/The_Real_First_Apple_Phone</guid>\n<digg:diggCount>814</digg:diggCount>\n<digg:submitter><digg:username>khaled</digg:username><digg:userimage>http://digg.com/userimages/khaled/medium.gif</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>56</digg:commentCount>\n</item>\n<item>\n<title>One billion mobile phones were shipped in 2006</title>\n<link>http://digg.com/tech_news/One_billion_mobile_phones_were_shipped_in_2006</link>\n<description>Blockbuster mobile phone sales during the holiday season last year propelled shipments to over one billion for all of 2006, market researcher IDC said Thursday.</description>\n<pubDate>Sat, 27 Jan 2007 02:16:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/One_billion_mobile_phones_were_shipped_in_2006</guid>\n<digg:diggCount>271</digg:diggCount>\n<digg:submitter><digg:username>JimMessenger</digg:username><digg:userimage>http://digg.com/userimages/j/i/m/jimmessenger/medium1479.JPG</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>18</digg:commentCount>\n</item>\n<item>\n<title> Light or Lighter Weight Aps To Replace Memory Hogs in Windows XP</title>\n<link>http://digg.com/software/Light_or_Lighter_Weight_Aps_To_Replace_Memory_Hogs_in_Windows_XP</link>\n<description>Today on Digg, there was a story about software that slows down Windows XP substantially. The worst offender? Norton Anti-Virus et. al. That sparked a discussion regarding light or lighter weight applications to replace those applications that slow you down to a crawl. I decided to compile that list here, a &quot;one stop shop&quot; if you will.</description>\n<pubDate>Sat, 27 Jan 2007 01:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Light_or_Lighter_Weight_Aps_To_Replace_Memory_Hogs_in_Windows_XP</guid>\n<digg:diggCount>865</digg:diggCount>\n<digg:submitter><digg:username>mbthompson</digg:username><digg:userimage>http://digg.com/userimages/mbthompson/medium6826.gif</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>114</digg:commentCount>\n</item>\n<item>\n<title>A-Data''s 128GB Solid State Drive Sees the Light of Day</title>\n<link>http://digg.com/hardware/A_Data_s_128GB_Solid_State_Drive_Sees_the_Light_of_Day</link>\n<description>A-Data was showing off this 128GB 2.5-inch solid-state drive in the back rooms of CES, and finally a picture of the largest-capacity SSD in the world has surfaced. Not much is known about it except that it''s a SATA II drive and might be shipping about six months from now.</description>\n<pubDate>Sat, 27 Jan 2007 01:12:39 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/A_Data_s_128GB_Solid_State_Drive_Sees_the_Light_of_Day</guid>\n<digg:diggCount>674</digg:diggCount>\n<digg:submitter><digg:username>radicaldementia</digg:username><digg:userimage>http://digg.com/userimages/r/a/d/radicaldementia/medium5467.jpg</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>62</digg:commentCount>\n</item>\n<item>\n<title>Top 10 Flickr Hacks</title>\n<link>http://digg.com/design/Top_10_Flickr_Hacks</link>\n<description>Thomas Hawk reviews the top 10 Flickr Hacks. Some you''ve never heard of, but they all look useful.</description>\n<pubDate>Sat, 27 Jan 2007 01:00:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Top_10_Flickr_Hacks</guid>\n<digg:diggCount>730</digg:diggCount>\n<digg:submitter><digg:username>chrisirmo</digg:username><digg:userimage>http://digg.com/userimages/chrisirmo/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>17</digg:commentCount>\n</item>\n<item>\n<title>Interview with Digg''s Daniel Burka...UI Designer</title>\n<link>http://digg.com/tech_news/Interview_with_Digg_s_Daniel_Burka_UI_Designer</link>\n<description>Daniel is not only the full-time UI designer of the SanFrancisco-based digg, he’s also partner of a firm in Canada called silverorange. Daniel and I talk about accessibility, usability and the viral content at the core of what makes digg work. We learn about the joys of working on a site that’s forever stuck in Alexa’s top 100 list.</description>\n<pubDate>Sat, 27 Jan 2007 01:00:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Interview_with_Digg_s_Daniel_Burka_UI_Designer</guid>\n<digg:diggCount>455</digg:diggCount>\n<digg:submitter><digg:username>UCBearcats</digg:username><digg:userimage>http://digg.com/userimages/ucbearcats/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>19</digg:commentCount>\n</item>\n<item>\n<title>Photos: The computing power behind ILM''s magic</title>\n<link>http://digg.com/tech_news/Photos_The_computing_power_behind_ILM_s_magic</link>\n<description>Lucasfilm open up the doors of its data center to reveal an impressive array of servers and storage devices.</description>\n<pubDate>Sat, 27 Jan 2007 00:30:04 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Photos_The_computing_power_behind_ILM_s_magic</guid>\n<digg:diggCount>688</digg:diggCount>\n<digg:submitter><digg:username>MrBabyMan</digg:username><digg:userimage>http://digg.com/userimages/mrbabyman/medium7859.gif</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>45</digg:commentCount>\n</item>\n<item>\n<title>Abandoned Russian Planes</title>\n<link>http://digg.com/hardware/Abandoned_Russian_Planes_2</link>\n<description>Once the most feared artifacts in the air, now just simple cadavers being ripped off.</description>\n<pubDate>Sat, 27 Jan 2007 00:30:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Abandoned_Russian_Planes_2</guid>\n<digg:diggCount>678</digg:diggCount>\n<digg:submitter><digg:username>bonlebon</digg:username><digg:userimage>http://digg.com/userimages/bonlebon/medium5388.jpg</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>42</digg:commentCount>\n</item>\n<item>\n<title>Geek to Live: Manage multiple Firefox profiles</title>\n<link>http://digg.com/software/Geek_to_Live_Manage_multiple_Firefox_profiles</link>\n<description>If you share a computer with family members or roommates, or if you simply browse for different reasons - like work, blogging, school research, or play - you may want to use different Firefox settings each time you sit down to surf. Luckily, the Firefox profile manager can manage multiple browsing personalities for a single user on the same desktop</description>\n<pubDate>Sat, 27 Jan 2007 00:04:57 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/software/Geek_to_Live_Manage_multiple_Firefox_profiles</guid>\n<digg:diggCount>403</digg:diggCount>\n<digg:submitter><digg:username>dtyler21</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Software</digg:category>\n<digg:commentCount>29</digg:commentCount>\n</item>\n<item>\n<title>Scientists build 200 meg memory chip the size of a BLOOD CELL! </title>\n<link>http://digg.com/tech_news/Scientists_build_200_meg_memory_chip_the_size_of_a_BLOOD_CELL</link>\n<description>According to today''s New York Times, scientists have built a memory chip the size of a white blood cell with wires as thin as proteins. It holds 160,000 bits (200 megabytes) and is 40 times as dense as today''s memory chips — impressive.</description>\n<pubDate>Fri, 26 Jan 2007 23:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Scientists_build_200_meg_memory_chip_the_size_of_a_BLOOD_CELL</guid>\n<digg:diggCount>117</digg:diggCount>\n<digg:submitter><digg:username>ryland2</digg:username><digg:userimage>http://digg.com/userimages/r/y/l/ryland2/medium8027.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>22</digg:commentCount>\n</item>\n<item>\n<title>The Digg The Never Happened</title>\n<link>http://digg.com/tech_news/The_Digg_The_Never_Happened</link>\n<description>This is the story of the digg that never happened. Sometimes a submission''s diggs build up steadily over time but then it never makes the jump to the front page. This tends to happen to top users but there is no explanation as to how or why it happens.</description>\n<pubDate>Fri, 26 Jan 2007 23:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/The_Digg_The_Never_Happened</guid>\n<digg:diggCount>44</digg:diggCount>\n<digg:submitter><digg:username>Enigma5</digg:username><digg:userimage>http://digg.com/userimages/enigma5/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>9</digg:commentCount>\n</item>\n<item>\n<title>Bill Gates to appear on ''The Daily Show'' for Vista launch</title>\n<link>http://digg.com/tech_news/Bill_Gates_to_appear_on_The_Daily_Show_for_Vista_launch</link>\n<description>Microsoft Chairman Bill Gates is scheduled to appear on The Daily Show with Jon Stewart on Monday, January 29, the eve of Microsoft''s Windows Vista launch.</description>\n<pubDate>Fri, 26 Jan 2007 23:10:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Bill_Gates_to_appear_on_The_Daily_Show_for_Vista_launch</guid>\n<digg:diggCount>2014</digg:diggCount>\n<digg:submitter><digg:username>srobbin</digg:username><digg:userimage>http://digg.com/userimages/srobbin/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>122</digg:commentCount>\n</item>\n<item>\n<title>Holy Mac! His basement is full with his Mac collection</title>\n<link>http://digg.com/apple/Holy_Mac_His_basement_is_full_with_his_Mac_collection</link>\n<description>Photos of his Mac collection in his basement.</description>\n<pubDate>Fri, 26 Jan 2007 23:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Holy_Mac_His_basement_is_full_with_his_Mac_collection</guid>\n<digg:diggCount>89</digg:diggCount>\n<digg:submitter><digg:username>Brajeshwar</digg:username><digg:userimage>http://digg.com/userimages/brajeshwar/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>20</digg:commentCount>\n</item>\n<item>\n<title>Rechargeable Battery for Nintendo Wii Wiimote Released</title>\n<link>http://digg.com/gadgets/Rechargeable_Battery_for_Nintendo_Wii_Wiimote_Released</link>\n<description>A 3rd Party company has released the first Rechargeable Battery for the Nintendo Wii Wiimote Controller, charges up via USB Cable,</description>\n<pubDate>Fri, 26 Jan 2007 23:00:05 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/gadgets/Rechargeable_Battery_for_Nintendo_Wii_Wiimote_Released</guid>\n<digg:diggCount>737</digg:diggCount>\n<digg:submitter><digg:username>Wraggster</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Gadgets</digg:category>\n<digg:commentCount>46</digg:commentCount>\n</item>\n<item>\n<title>Ripping off the veil: The mysterious PS3 hardware scaler exposed</title>\n<link>http://digg.com/hardware/Ripping_off_the_veil_The_mysterious_PS3_hardware_scaler_exposed</link>\n<description>Beyond3D uncover details on the new hardware scaler functionality revealed by the latest SDK update for PS3 developers, which will let the console upscale to 1080i/p, a capability invisible to the developer until now.</description>\n<pubDate>Fri, 26 Jan 2007 22:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Ripping_off_the_veil_The_mysterious_PS3_hardware_scaler_exposed</guid>\n<digg:diggCount>461</digg:diggCount>\n<digg:submitter><digg:username>megalon</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>88</digg:commentCount>\n</item>\n<item>\n<title>Worst Website Ever? Why? Cause this is my WEB DESIGN TEACHERS site.</title>\n<link>http://digg.com/design/Worst_Website_Ever_Why_Cause_this_is_my_WEB_DESIGN_TEACHERS_site</link>\n<description>We have all had ''that teacher''. This guy didn''t understand ANYTHING, and he is a DOCTOR. Now, personally, I am a really bad web designer. Why? Because this guy was my teacher! A favorite quote from this guy (in a South African accent): &quot;Web designers work for peanut money.&quot; (What he meant: They work for peanuts)</description>\n<pubDate>Fri, 26 Jan 2007 22:40:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/design/Worst_Website_Ever_Why_Cause_this_is_my_WEB_DESIGN_TEACHERS_site</guid>\n<digg:diggCount>3853</digg:diggCount>\n<digg:submitter><digg:username>MLyzz</digg:username><digg:userimage>http://digg.com/userimages/mlyzz/medium.jpg</digg:userimage></digg:submitter>\n<digg:category>Design</digg:category>\n<digg:commentCount>495</digg:commentCount>\n</item>\n<item>\n<title>Google''s Matt Cutts: Web Dominatrix</title>\n<link>http://digg.com/tech_news/Google_s_Matt_Cutts_Web_Dominatrix</link>\n<description>Awesome cartoon about Google''s hypocritical nofollow tag policy.</description>\n<pubDate>Fri, 26 Jan 2007 22:20:03 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/tech_news/Google_s_Matt_Cutts_Web_Dominatrix</guid>\n<digg:diggCount>70</digg:diggCount>\n<digg:submitter><digg:username>ScienceBlog</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Tech Industry News</digg:category>\n<digg:commentCount>21</digg:commentCount>\n</item>\n<item>\n<title>Why my Next Laptop Will Be a Mac</title>\n<link>http://digg.com/apple/Why_my_Next_Laptop_Will_Be_a_Mac</link>\n<description>Exactly what it says. Why I am not considering buying a PC-driven laptop again.</description>\n<pubDate>Fri, 26 Jan 2007 22:10:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Why_my_Next_Laptop_Will_Be_a_Mac</guid>\n<digg:diggCount>128</digg:diggCount>\n<digg:submitter><digg:username>HCDean</digg:username><digg:userimage>http://digg.com/userimages/h/c/d/hcdean/medium6429.jpg</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>80</digg:commentCount>\n</item>\n<item>\n<title>Screenshot: Microsoft threatend by Linux?</title>\n<link>http://digg.com/linux_unix/Screenshot_Microsoft_threatend_by_Linux</link>\n<description>&quot;Windows outperforms Linux. Why IT pros select Windows server over Linux? Find out this and more&quot; - Number one, Microsoft advertises with Google Adsense? Secondly, do they sound threatend or what?</description>\n<pubDate>Fri, 26 Jan 2007 22:00:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/linux_unix/Screenshot_Microsoft_threatend_by_Linux</guid>\n<digg:diggCount>67</digg:diggCount>\n<digg:submitter><digg:username>koregaonpark</digg:username><digg:userimage>http://digg.com/userimages/koregaonpark/medium6062.png</digg:userimage></digg:submitter>\n<digg:category>Linux/Unix</digg:category>\n<digg:commentCount>39</digg:commentCount>\n</item>\n<item>\n<title>Apple Has Released the MacBook 802.11n Update for Free</title>\n<link>http://digg.com/apple/Apple_Has_Released_the_MacBook_802_11n_Update_for_Free</link>\n<description>After first saying they''ll charge $4.99 for the 802.11n update to Core 2 Duo MacBook and MacBook Pros and then changing that to $1.99 it is now the best price you can get -- FREE!  The download patch is available today.</description>\n<pubDate>Fri, 26 Jan 2007 20:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/apple/Apple_Has_Released_the_MacBook_802_11n_Update_for_Free</guid>\n<digg:diggCount>470</digg:diggCount>\n<digg:submitter><digg:username>drew2778</digg:username><digg:userimage>http://digg.com/img/user-medium/user-default.png</digg:userimage></digg:submitter>\n<digg:category>Apple</digg:category>\n<digg:commentCount>65</digg:commentCount>\n</item>\n<item>\n<title>Via''s incredible shrinking mobo line spawns &quot;pico-ITX&quot;</title>\n<link>http://digg.com/hardware/Via_s_incredible_shrinking_mobo_line_spawns_pico_ITX</link>\n<description>Via has gone and done it again.  The folks who brought us mini-ITX, and then nano-ITX, have now introduced their first teeny mobo on an even smaller format:  &quot;pico-ITX,&quot; which measures a scant 3.9 x 2.8 inches and features a 1GHz C7 processor, along with rich audio/video I/O,</description>\n<pubDate>Fri, 26 Jan 2007 19:50:02 GMT</pubDate>\n<guid isPermaLink="true">http://digg.com/hardware/Via_s_incredible_shrinking_mobo_line_spawns_pico_ITX</guid>\n<digg:diggCount>751</digg:diggCount>\n<digg:submitter><digg:username>deviceguru</digg:username><digg:userimage>http://digg.com/userimages/d/e/v/deviceguru/medium3183.jpg</digg:userimage></digg:submitter>\n<digg:category>Hardware</digg:category>\n<digg:commentCount>56</digg:commentCount>\n</item>\n</channel>\n</rss>', '20070127140628');

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

INSERT INTO `dsb_queue_email` (`mail_id`, `to`, `subject`, `message_body`, `date_added`) VALUES (1, 'dan@rdsct.ro', 'Your profile was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', '20061222002332'),
(2, 'dan@sco.ro', 'r', '<html dir="ltr">\r\n    <head>\r\n    </head>\r\n    <body>\r\n        &lt;a href=&quot;&quot;&gt;asd&lt;/a&gt;<br />\r\n        <br />\r\n        asd<br />\r\n        asd<br />\r\n        asd<br />\r\n        &quot; ''<br />\r\n    </body>\r\n</html>', '20070124232308'),
(3, 'dan@rdsct.ro', 'r', '<html dir="ltr">\r\n    <head>\r\n    </head>\r\n    <body>\r\n        &lt;a href=&quot;&quot;&gt;asd&lt;/a&gt;<br />\r\n        <br />\r\n        asd<br />\r\n        asd<br />\r\n        asd<br />\r\n        &quot; ''<br />\r\n    </body>\r\n</html>', '20070124232308'),
(4, 'dan@rdsct.ro', 'r', '<html dir="ltr">\r\n    <head>\r\n    </head>\r\n    <body>\r\n        &lt;a href=&quot;&quot;&gt;asd&lt;/a&gt;<br />\r\n        <br />\r\n        asd<br />\r\n        asd<br />\r\n        asd<br />\r\n        &quot; ''<br />\r\n    </body>\r\n</html>', '20070124232308');

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
(28, 0x69735f64656661756c74, '1', 'Is this skin the default site skin?', 0, 0x736b696e5f6261736963),
(32, 0x6d696e5f73697a65, '', 'Minimum photo file size in bytes (use 0 for not limited).', 2, 0x636f72655f70686f746f),
(33, 0x6d61785f73697a65, '', 'Maximum photo file size in bytes (use 0 for server default).', 2, 0x636f72655f70686f746f),
(34, 0x6262636f64655f6d657373616765, '1', 'Allow BBCode in member to member messages?', 1, 0x636f7265),
(35, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 'Date and time format', 2, 0x636f7265);

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
('8816b2c8b45df99a22880b00513d7867', 1, 'a:1:{s:5:"pstat";s:1:"5";}', '1', 0, '20070108165007'),
('4101f936649ffe012c18c4a2aad2bcdf', 1, 'a:1:{s:4:"user";s:4:"test";}', '2,209', 0, '20070125122846'),
('6017549d266aaf0bedef80f63a2372c4', 1, 'a:1:{s:5:"album";s:1:"1";}', '2', 0, '20070125130726'),
('a7d680a64dd6b6535e36ec6c9fca60f7', 1, 'a:1:{s:7:"wophoto";s:1:"1";}', '1,209', 0, '20070125131331'),
('82dd29774c45e2d44b8be91e9ac4449e', 1, 'a:1:{s:6:"wphoto";s:1:"1";}', '2', 0, '20070125131335'),
('dfb91604070758abb437af0ea5540b59', 1, 'a:2:{s:7:"wophoto";s:1:"1";s:5:"album";s:1:"1";}', '', 0, '20070125131339'),
('1aa8959f46075f6beaac17320dfecb64', 1, 'a:2:{s:6:"wphoto";s:1:"1";s:5:"album";s:1:"1";}', '2', 0, '20070125131348'),
('7eb2c4fba0cbd2c3ffa0a7adb02265dc', 2, 'a:1:{s:7:"is_main";s:1:"1";}', '40', 0, '20070125131852'),
('e27c84d0397e73b95f91def400629716', 2, 'a:1:{s:7:"caption";s:1:"1";}', '41,40,39', 0, '20070125153942'),
('567e154947e6a8c46ecd823edf3a424d', 2, 'a:1:{s:10:"is_private";s:1:"1";}', '39', 0, '20070125153950'),
('3a0b785e2faaff550198f79bdd016b4d', 2, 'a:2:{s:10:"is_private";s:1:"1";s:7:"caption";s:1:"1";}', '39', 0, '20070125154008');

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

