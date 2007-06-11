-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 23, 2007 at 11:55 PM
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
(11, 0x77726974655f626c6f6773, 'Write own blogs', 6, 1),
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

INSERT INTO `dsb_admin_accounts` (`admin_id`, `user`, `pass`, `name`, `status`, `dept_id`, `email`) VALUES (1, 0x61646d696e, 0x3964323763666564386236633738373833616162623534643264393464393331, 'Dan Caragea', 15, 4, 'dan@sco.ro'),
(0, 0x616469, 0x3362623132633663393066343564343166646633633364343038616537646639, 'adrian chiper', 15, 4, '');

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

INSERT INTO `dsb_admin_mtpls` (`amtpl_id`, `amtpl_name`, `subject`, `message_body`, `amtpl_type`) VALUES (1, 'Reject member profile', 'Your profile was not approved', '<html><head><title>Your profile has not been approved</title>   <link href="{tplvars.baseurl}/skins/def/styles/common.css" media="screen" type="text/css" rel="stylesheet" /> </head><body> <div id="trim"> 	<div id="content"> 		<p>Thank you for joining <a href="{tplvars.baseurl}">{tplvars.sitename}</a>.</p> 		<p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest to other members.</p><p>Please update your profile with relevant information.<br /></p> 	</div> </div> </body></html>', 1),
(2, 'Reject photo', 'One of your photos was not approved', '<html><head><title>Your photo has not been approved</title>                       <link href="{tplvars.baseurl}/skins/def/styles/common.css" media="screen" type="text/css" rel="stylesheet" />     </head><body spellcheck="false"><p>Unfortunately we are unable to publish your photo on the site yet because</p>         <p>&nbsp;</p>         <p>Regards,<br />{tplvars.sitename} admin</p></body></html>', 2),
(3, 'Reject blog', 'One of your blog posts was not approved', '<html><head><title>Your blog post has not been approved</title>                       <link rel="stylesheet" type="text/css" media="screen" href="{tplvars.baseurl}/skins/def/styles/common.css" />     </head><body spellcheck="false"><p>Unfortunately we are unable to publish your blog post on the site yet because</p>         <p>&nbsp;</p>         <p>Regards,<br />         {tplvars.sitename} admin</p></body></html>', 3),
(4, 'Reject comment', 'One of your comments was not approved', '<html><head><title>Your comment has not been approved</title>                       <link rel="stylesheet" type="text/css" media="screen" href="{tplvars.baseurl}/skins/def/styles/common.css" />     </head><body spellcheck="false"><p>Unfortunately we are unable to publish your comment on the site yet because</p>         <p>&nbsp;</p>         <p>Regards,<br />         {tplvars.sitename} admin</p></body></html>', 4);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_banned_words`
-- 

DROP TABLE IF EXISTS `dsb_banned_words`;
CREATE TABLE `dsb_banned_words` (
  `word_id` int(5) unsigned NOT NULL auto_increment,
  `word` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`word_id`),
  UNIQUE KEY `word` (`word`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_banned_words`
-- 

INSERT INTO `dsb_banned_words` (`word_id`, `word`) VALUES (5, 'fuck'),
(6, 'suck'),
(7, 'sucks'),
(8, 'dick'),
(9, 'cock'),
(10, 'penis');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_blog_comments`
-- 

DROP TABLE IF EXISTS `dsb_blog_comments`;
CREATE TABLE `dsb_blog_comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `fk_parent_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `comment` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `processed` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_parent_id`,`status`),
  KEY `processed` (`processed`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_blog_comments`
-- 

INSERT INTO `dsb_blog_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`, `processed`) VALUES (9, 0, 0, 'dragon', 'howdy kid? asdasdasd', '2007-04-19 14:49:34', '2007-04-19 14:49:34', 15, 0),
(11, 0, 0, 'strawberries', 'I wonder what the design resource kit will contain.', '2007-04-19 14:53:07', '2007-04-19 14:53:07', 15, 0),
(21, 10, 0, 'dragon', 'Think of this like user stories with bells and whistles. Instead of having some predefined topics, the user creates his/her topics and [b]keeps [/b]talking. Plus others can comment on their posts. In this application, they''re supposed to replace the user stories.:)', '2007-04-20 12:58:50', '2007-05-23 11:47:29', 15, 0),
(22, 10, 0, 'guest', 'whoa', '2007-04-20 15:57:02', '2007-04-20 15:57:02', 15, 0),
(23, 10, 0, 'guest', 'rrrr :P', '2007-04-20 22:07:46', '2007-05-22 16:05:35', 15, 0),
(25, 10, 0, 'guest', 'gege', '2007-04-21 13:20:27', '2007-04-21 13:20:27', 15, 0),
(26, 0, 0, 'emma', 'hi asdasd', '2007-04-21 19:02:07', '2007-04-21 19:02:07', 15, 0),
(28, 10, 7, 'dragon', ':)', '2007-04-24 17:20:12', '2007-04-24 17:20:12', 15, 0),
(29, 10, 0, 'dragon', 'test #######you', '2007-04-26 18:45:36', '2007-05-23 11:55:56', 15, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_blog_posts`
-- 

DROP TABLE IF EXISTS `dsb_blog_posts`;
CREATE TABLE `dsb_blog_posts` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `fk_blog_id` int(10) unsigned NOT NULL default '0',
  `is_public` tinyint(1) unsigned NOT NULL default '1',
  `title` varchar(200) NOT NULL default '',
  `post_content` text NOT NULL,
  `allow_comments` tinyint(1) unsigned NOT NULL default '1',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `post_url` text NOT NULL,
  `stat_views` int(5) unsigned NOT NULL default '0',
  `stat_comments` int(5) unsigned NOT NULL default '0',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `reject_reason` text NOT NULL,
  `processed` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `is_public` (`is_public`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_blog_id`,`is_public`,`status`),
  KEY `key2` (`fk_user_id`,`is_public`,`status`),
  KEY `processed` (`processed`),
  FULLTEXT KEY `text_key` (`title`,`post_content`)
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `dsb_blog_posts`
-- 

INSERT INTO `dsb_blog_posts` (`post_id`, `date_posted`, `fk_user_id`, `_user`, `fk_blog_id`, `is_public`, `title`, `post_content`, `allow_comments`, `status`, `post_url`, `stat_views`, `stat_comments`, `last_changed`, `reject_reason`, `processed`) VALUES (6, '2007-04-18 23:30:54', 19, 'pkusa', 6, 1, 'About MyOrg, Inc', 'Why Choose Us?\nWhy would someone choose one provider over another?  When it comes to Internet Connectivity, how well does one really know any provider? \n\nRegardless of how many server racks are maintained, or how low the monthly price is for hosting your site, we believe that ultimately, our customers choose us because they have gotten to know us.  They know that we will help them connect to the maze that is the Internet and, should they get lost, they know that we will be there to help them find their way.\n\nWe are dedicated to ensuring that whatever service you choose with us, should it be: Web Hosting Solutions, Domain Names, SSL, Co-Location or Web Development we always are courteous, knowledgeable and quick to respond.\n\nIn order to stay ahead of the competition, MyOrg, Inc. and its family of companies have embarked on providing our consumer and corporate customers with true quality-of-service initiatives, focused on making our customers'' Internet experience the best it can be.', 1, 15, '', 0, 1, '2007-05-23 09:32:35', '<html>\r\n    <head>\r\n        <title>Your blog post has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/def/styles/common.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <p>Unfortunately we are unable to publish your blog post on the site yet because</p>\r\n        <p>&nbsp;</p>\r\n        <p>Regards,<br />\r\n        Nexus / R i t m o / friendy admin</p>\r\n    </body>\r\n</html>', 0),
(7, '2007-04-19 05:01:39', 12, 'a_l_f', 7, 1, 'A design resource kita', 'I look forward to the design resource kit as i cannot leave things alone :-)\ni wonder how long it will [quote]take me to [/quote]crash the new install when its released :-0', 1, 15, '', 1, 1, '2007-05-22 13:00:18', '', 0),
(8, '2007-04-19 09:57:59', 1, 'emma', 1, 1, 'Testing blogs', 'Hello\n\n[quote]everybody[/quote]\n\n[b]how[/b]\n\n[u]are[/u]\n\n[url=http://www.datemill.com/friendy/profile.php?user=emma]you[/url]?', 1, 15, '', 0, 5, '2007-04-19 09:57:59', '', 0),
(9, '2007-04-19 21:40:05', 7, 'dragon', 5, 1, 'Today''s update take 2', 'Ok, another update today - we focused on bugs and overall stability but a couple of features were added too. :)\n- You should be able to send (and receive) messages, flirts, etc.\n- You will receive new message email notifications when you get a new message (if you said you want to receive notifs in your settings)\n- You will also receive message and email notifications when a new comment is made on one of your pictures or blogs.\n- The one and only cron job is active on the demo site.', 1, 15, '', 2, 0, '2007-05-07 11:13:21', '', 0),
(10, '2007-04-20 02:01:23', 11, 'johnboy', 4, 1, 'Testing this out', 'I''m not very familiar about blogs, so I have no idea what Im doing here, not even sure if this will post in the main blog area. Are blogs just another type of FORUMS?', 1, 15, '', 79, 6, '2007-05-23 17:01:39', '', 1);

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

INSERT INTO `dsb_feed_cache` (`module_code`, `feed_xml`, `update_time`) VALUES (0x6f7369676e616c5f66656564, '<?xml version="1.0" encoding="UTF-8"?>\r\n<?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/rss2full.xsl" type="text/xsl" media="screen"?><?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/itemcontent.css" type="text/css" media="screen"?><rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">\r\n<channel>\r\n	<title>Original Signal - Transmitting Tech</title>\r\n	<link>http://tech.originalsignal.com</link>\r\n	<description>Orginal Signal aggregates the 15 most popular technology sites. The main purpose of the site is to provide \r\na quick glance on what''s happening without using your desktop/web RSS reader. New headlines (since your \r\nlast cookied visit) come in pretty orange, visited ones are grey. All credits go to the authors of these weblogs. \r\nWithout their hard work Original Signal would not exist. Original Signal was inspired by Popurls and the Web 2.0 Workgroup.</description>\r\n	<pubDate>Wed, 23 May 2007 18:58:31 CEST</pubDate>\r\n	<language>en</language>\r\n	\r\n	  <atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" href="http://feeds.feedburner.com/OriginalSignal/tech" type="application/rss+xml" /><item>\r\n  <title>BA puts green IT at the top of its agenda</title>\r\n  <link>http://tech.originalsignal.com/article/52856/ba-puts-green-it-at-the-top-of-its-agenda.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:47 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52856/ba-puts-green-it-at-the-top-of-its-agenda.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Lisa Kelly and Dave Friedlos , Computing, Thursday 24 May 2007 at 00:00:00Airline lends support to Green Computing campaignBritish Airways (BA) is placing environmental policies and practices at the heart of its strategy this year, and has given its backing to our Green Computing campaign....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>BT cuts costs automating HR records</title>\r\n  <link>http://tech.originalsignal.com/article/52857/bt-cuts-costs-automating-hr-records.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:47 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52857/bt-cuts-costs-automating-hr-records.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Lisa Kelly , Computing, Thursday 24 May 2007 at 00:00:00Compnay replaces manual processesBT has automated the management of employee records to ensure compliance with the Data Protection Act and improve service and efficiency levels....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Coty uses SOA to speed up integration</title>\r\n  <link>http://tech.originalsignal.com/article/52858/coty-uses-soa-to-speed-up-integration.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:47 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52858/coty-uses-soa-to-speed-up-integration.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Lisa Kelly, Computing, Thursday 24 May 2007 at 00:00:00Beauty firm turns to Information BuildersBeauty company Coty has integrated the IT infrastructure of a firm acquired from Unilever using software based on a service-oriented architecture (SOA)....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Retailers boost IT spending</title>\r\n  <link>http://tech.originalsignal.com/article/52859/retailers-boost-it-spending.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:47 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52859/retailers-boost-it-spending.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Dave Friedlos and Lara Williams , Computing, Thursday 24 May 2007 at 00:00:00Marks & Spencer and Sainsbury increase their IT spend to stay competitiveRetailers Marks & Spencer (M& S) and Sainsbury''s have increased their IT investments to improve efficiency and catch up with rivals....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Brewer''s planning process gains extra clarity</title>\r\n  <link>http://tech.originalsignal.com/article/52860/brewers-planning-process-gains-extra-clarity.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:47 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52860/brewers-planning-process-gains-extra-clarity.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Lara Williams, Computing, Thursday 24 May 2007 at 00:00:00Scottish & Newcastle deploys portfolio management tool for greater ITplanning efficiencyBrewer Scottish & Newcastle has reduced the time spent on IT planning by 80 per cent following the introduction of a portfolio management tool....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>IBA Health takes on iSoft</title>\r\n  <link>http://tech.originalsignal.com/article/52861/iba-health-takes-on-isoft.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:47 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52861/iba-health-takes-on-isoft.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Dave Friedlos, Computing, Thursday 24 May 2007 at 00:00:00Deal could create positive opportunities for the NHSBeleaguered NHS software supplier iSoft is to be acquired by rival firm IBA Health, creating the fourth largest healthcare software group in the world....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>I-SPY with my little eye... a new spyware act</title>\r\n  <link>http://tech.originalsignal.com/article/52855/i-spy-with-my-little-eye-a-new-spyware-act.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:36 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52855/i-spy-with-my-little-eye-a-new-spyware-act.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  The House has passed a new bill designed to crack down on spyware, even though most spyware practices are currently illegal.Read More...    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>The Immaculate Conception Exists, at Least Among Sharks</title>\r\n  <link>http://tech.originalsignal.com/article/52854/the-immaculate-conception-exists-at-least-among-sharks.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:28 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52854/the-immaculate-conception-exists-at-least-among-sharks.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Who needs a man? Female sharks can fertilize their own eggs with sperm, says a new study being published Wednesday.     ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Hack My Son''s Computer, Please</title>\r\n  <link>http://tech.originalsignal.com/article/52853/hack-my-sons-computer-please.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52853/hack-my-sons-computer-please.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Can an elderly father give police permission to search a password-protected computer kept in his adult son''s bedroom, without probable cause or a warrant? In April, a three judge panel of the 10th Circuit Court of Appeals said yes.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Female Sharks Can Reproduce Alone</title>\r\n  <link>http://tech.originalsignal.com/article/52852/female-sharks-can-reproduce-alone.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:38:03 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52852/female-sharks-can-reproduce-alone.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  mikesd81 writes "The Washington Post has an article about a team of American and Irish researchers that have discovered that some female sharks can reproduce without having sex, the first time that scientists have found the unusual capacity in such an ancient vertebrate species. Their report concludes that sharks can reproduce asexually through the process known as parthenogenesis (the growth and development of an embryo or seed without fertilization by a male). Scientists started investigating after a female hammerhead shark was mysteriously born at Omaha''s Henry Doorly Zoo in a tank that housed 3 female sharks. It was originally thought one had stored sperm from a male shark before fertilizing an egg. However, baby shark''s genetic makeup perfectly matched one of the females in the tank, with no sign of a male parent."Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Chip scraps feed solar industry''s hunger</title>\r\n  <link>http://tech.originalsignal.com/article/52851/chip-scraps-feed-solar-industrys-hunger.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:08:17 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52851/chip-scraps-feed-solar-industrys-hunger.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Selling unusable silicon wafers to solar cell makers is good for the environment, and companies like TI make money at it too.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Cool Earth Solar: solar farms on the cheap</title>\r\n  <link>http://tech.originalsignal.com/article/52849/cool-earth-solar-solar-farms-on-the-cheap.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:08:15 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52849/cool-earth-solar-solar-farms-on-the-cheap.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Blog: Cool Earth Solar, a company now raising an initial round of outside funding, is looking to prototype a plastic balloon capable of generating electricity in solar farms.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Chip scraps feed solar industry''s hunger</title>\r\n  <link>http://tech.originalsignal.com/article/52850/chip-scraps-feed-solar-industrys-hunger.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:08:15 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52850/chip-scraps-feed-solar-industrys-hunger.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Selling unusable silicon wafers to solar cell makers is good for the environment, and companies like TI make money at it too.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Become your own ISP: The Perfect Server - Mandriva 2007 Spring Free</title>\r\n  <link>http://tech.originalsignal.com/article/52847/become-your-own-isp-the-perfect-server-mandriva-2007-spring-free.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:08:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52847/become-your-own-isp-the-perfect-server-mandriva-2007-spring-free.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  This guide shows how to set up a Mandriva 2007 Spring Free (Mandriva 2007.1) server that offers all services needed by ISPs and hosters: Apache web server (SSL-capable), Postfix mail server with SMTP-AUTH and TLS, BIND DNS server, Proftpd FTP server, MySQL server, Courier POP3/IMAP, Quota, Firewall, etc. With this help, you can be your own ISP.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Google to scan 800,000 manuscripts, books from Indian university</title>\r\n  <link>http://tech.originalsignal.com/article/52848/google-to-scan-800-000-manuscripts-books-from-indian-university.html</link>\r\n  <pubDate>Wed, 23 May 2007 18:08:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/52848/google-to-scan-800-000-manuscripts-books-from-indian-university.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Google''s dream of organizing all the world''s information has come one step closer to reality with the announcement that it will digitize 800,000 Indian books and manuscripts for inclusion in its Google Book Search program.  ]]></content:encoded>\r\n  </item>\r\n    	\r\n	</channel>\r\n</rss>', '20070523170058'),
(0x646174656d696c6c5f66656564, '<?xml version="1.0" encoding="UTF-8"?>\r\n<?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/rss2full.xsl" type="text/xsl" media="screen"?><?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/itemcontent.css" type="text/css" media="screen"?><rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">\r\n<channel>\r\n	<title>Original Signal - Transmitting Tech</title>\r\n	<link>http://tech.originalsignal.com</link>\r\n	<description>Orginal Signal aggregates the 15 most popular technology sites. The main purpose of the site is to provide \r\na quick glance on what''s happening without using your desktop/web RSS reader. New headlines (since your \r\nlast cookied visit) come in pretty orange, visited ones are grey. All credits go to the authors of these weblogs. \r\nWithout their hard work Original Signal would not exist. Original Signal was inspired by Popurls and the Web 2.0 Workgroup.</description>\r\n	<pubDate>Fri, 18 May 2007 11:13:56 CEST</pubDate>\r\n	<language>en</language>\r\n	\r\n	  <atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" href="http://feeds.feedburner.com/OriginalSignal/tech" type="application/rss+xml" /><item>\r\n  <title>Politicians'' Latest Grandstanding: Force ISPs To Hide Rogue Internet Pharmacies</title>\r\n  <link>http://tech.originalsignal.com/article/51976/politicians-latest-grandstanding-force-isps-to-hide-rogue-internet-pharmacies.html</link>\r\n  <pubDate>Fri, 18 May 2007 10:38:36 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51976/politicians-latest-grandstanding-force-isps-to-hide-rogue-internet-pharmacies.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  The problem with legislators is that all they know how to do is legislate.  Even if there are perfectly acceptable laws in place, you don''t get re-elected for saying, "you know what, we didn''t need any new laws this time around."  No, you have to propose and support legislation that makes it sound like you''re solving a big problem -- even if the problem isn''t that big and your proposed solution will likely make it worse.  The latest such situation concerns unauthorized internet pharmacies.  Sure, there may be some problems with people getting access to prescription medicine they shouldn''t be able to order, but even the DEA says that they don''t any new laws, as existing laws are perfectly well suited for shutting these pharmacies down.  Of course, that won''t stop the politicians from pushing forward.  However, not only are they proposing more restrictions and penalties for such pharmacies, but also demanding that ISPs and search engines proactively block these sites -- and also block advertisements for these sites.  Yes, despite the fact that courts throw out every attempt by politicians to force ISPs to block sites they don''t like, the politicians insist that this time it won''t violate the Constitution.  Yes, despite the fact that those who really want to access these sites will get around the blocks, politicians insist they''re useful.  Even better, they got a law professor to claim that "It is no burden to (the ISPs). They know how to do it; they can do it in a minute."  Trying telling that to the ISPs who would now be responsible for blocking content.  Once again, the ISPs are simply running connectivity.  They should have no responsibility for what''s done over that connectivity.  If the sites, themselves, are illegal, go after the sites.  If the sites are offshore, then block the shipments through customs.  But, requiring ISPs to waste time, effort, money and resources on putting up ineffective blocks that aren''t needed won''t help the situation.  It''ll just waste time, effort, money and resources so that some politicians can claim they were tough on illegal internet pharmacies during the next election.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Dell unveils Project Hybrid</title>\r\n  <link>http://tech.originalsignal.com/article/51975/dell-unveils-project-hybrid.html</link>\r\n  <pubDate>Fri, 18 May 2007 10:38:32 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51975/dell-unveils-project-hybrid.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Tom Sanders in San Francisco, vnunet.com, Friday 18 May 2007 at 00:00:00Better margins in integrated systems, not 1u boxesDell is preparing to roll out new products and services that will allow the company to sell more complete systems instead of point products.  The server and PC maker used...&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Quick custom text ad placement in WordPress blog categories</title>\r\n  <link>http://tech.originalsignal.com/article/51974/quick-custom-text-ad-placement-in-wordpress-blog-categories.html</link>\r\n  <pubDate>Fri, 18 May 2007 10:38:29 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51974/quick-custom-text-ad-placement-in-wordpress-blog-categories.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  WordPress plugins abound for Google AdSense and other third-party text ad brokers, but what happens when someone wants to buy ad space directly from you, on a specific category page in your blog? You can easily add static HTML text ads to your category search result pages by creating category-specific page templates.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>The First Terabyte Hard Drive--Review</title>\r\n  <link>http://tech.originalsignal.com/article/51973/the-first-terabyte-hard-drive-review.html</link>\r\n  <pubDate>Fri, 18 May 2007 10:38:06 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51973/the-first-terabyte-hard-drive-review.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  The terabyte era arrives, with Hitachi''s 5-platter, 10-head 7K1000 hard drive. ExtremeTech puts Hitachi''s latest hard drive on the bench and let you know how it performs.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>First Pictures of Apple Store in Saigon</title>\r\n  <link>http://tech.originalsignal.com/article/51971/first-pictures-of-apple-store-in-saigon.html</link>\r\n  <pubDate>Fri, 18 May 2007 09:38:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51971/first-pictures-of-apple-store-in-saigon.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Pictures of Apple''s brand new store in Saigon  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Mac Switcher: Three ways to take screenshots</title>\r\n  <link>http://tech.originalsignal.com/article/51972/mac-switcher-three-ways-to-take-screenshots.html</link>\r\n  <pubDate>Fri, 18 May 2007 09:38:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51972/mac-switcher-three-ways-to-take-screenshots.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  One thing you lose  is that Print Screen key; setting up a Mac desktop or notebook will reveal that the key is nowhere to be found, apparently leaving us out in the cold when it comes to capturing that golden moment on your display. Fortunately, this isn''t the case.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>PlayOgg! Now that the music is being set free from DRM...</title>\r\n  <link>http://tech.originalsignal.com/article/51970/playogg-now-that-the-music-is-being-set-free-from-drm.html</link>\r\n  <pubDate>Fri, 18 May 2007 09:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51970/playogg-now-that-the-music-is-being-set-free-from-drm.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Both popular music file formats MP3 and AACS are patent encumbered - that means we all end up paying too much for our music one way or another. Time to switch to playing Ogg! Playing Ogg is ethically, legally and technically superior. Download this Ogg friendly media player for Mac and Windows now! (Ogg support comes o''natural for GNU/Linux users)  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Transformers Full Theatrical Trailer Available</title>\r\n  <link>http://tech.originalsignal.com/article/51969/transformers-full-theatrical-trailer-available.html</link>\r\n  <pubDate>Fri, 18 May 2007 08:38:02 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51969/transformers-full-theatrical-trailer-available.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  roelbj writes "The full trailer for Michael Bay''s upcoming Transformers movie is now finally available on Yahoo. Unlike the teaser trailers that have only hinted at what the final effects would deliver, we can at long last get a much better feeling for how the live-action CGI Transformers will look."Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>30 Scripts For Galleries, Slideshows and Lightboxes</title>\r\n  <link>http://tech.originalsignal.com/article/51968/30-scripts-for-galleries-slideshows-and-lightboxes.html</link>\r\n  <pubDate>Fri, 18 May 2007 08:08:03 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51968/30-scripts-for-galleries-slideshows-and-lightboxes.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Family photos, vacation snapshots or creative artistic works: whatever images you have to present, you can present them in a variety of ways. On a big screen, in slide shows or in a thumbnails gallery. However, to convey the message of presented data effectively, it’s important to offer it in an attractive and intuitive way.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>May 18, 1953: Jackie Cochran, First Woman to Break the Sound Barrier</title>\r\n  <link>http://tech.originalsignal.com/article/51967/may-18-1953-jackie-cochran-first-woman-to-break-the-sound-barrier.html</link>\r\n  <pubDate>Fri, 18 May 2007 07:38:19 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51967/may-18-1953-jackie-cochran-first-woman-to-break-the-sound-barrier.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Famed aviatrix averages 652 mph as she streaks across the desert sky over California.     ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Video game sales up 20 percent in April</title>\r\n  <link>http://tech.originalsignal.com/article/51966/video-game-sales-up-20-percent-in-april.html</link>\r\n  <pubDate>Fri, 18 May 2007 07:38:17 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51966/video-game-sales-up-20-percent-in-april.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Strong demand for Nintendo''s Wii console and new Pokemon games for Nintendo''s DS handheld drive sales.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Engadget Responds to yesterday''s Apple news</title>\r\n  <link>http://tech.originalsignal.com/article/51964/engadget-responds-to-yesterdays-apple-news.html</link>\r\n  <pubDate>Fri, 18 May 2007 07:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51964/engadget-responds-to-yesterdays-apple-news.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  "The question we faced at that moment was: Do we run with the story without Apple''s comment or not? The answer seemed fairly clear there, too, at the time. We possessed what confirmed Apple employees believed was an internal Apple memo that with absolutely no doubt had also been received by any number of other Apple employees. "  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Amazon offering discounts on old, new MacBook models</title>\r\n  <link>http://tech.originalsignal.com/article/51965/amazon-offering-discounts-on-old-new-macbook-models.html</link>\r\n  <pubDate>Fri, 18 May 2007 07:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51965/amazon-offering-discounts-on-old-new-macbook-models.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  One day after Apple Inc. introduced a modest refresh to its MacBook line, the 13-inch notebooks were crowding the upper echelon of Amazon.com''s top seller list, thanks partly to some hefty discounts by the retailer on new and previous generation models.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Want To Search For Online Porn In Korea?  Please Identify Yourself First</title>\r\n  <link>http://tech.originalsignal.com/article/51963/want-to-search-for-online-porn-in-korea-please-identify-yourself-first.html</link>\r\n  <pubDate>Fri, 18 May 2007 06:38:33 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51963/want-to-search-for-online-porn-in-korea-please-identify-yourself-first.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Apparently, Google Korea is going to follow the lead of other Korean search engines introducing what they''re calling an age-verification system for its search engine later this year.  The idea is that "adult-themed" search will require you to be 19 years old or older.  If their searches touch on any of the list of 700 words supplied by the Korean government, users will have to enter their name and national resident registration number (think of it as your porn license!) to be checked against a database to make sure you''re old enough.  Of course, this also means the government (and Google) will have a very detailed record of who is searching for porn.  Or, they would assuming that no one ever finds out the national resident registration number of someone else over the age of 19 and enters that instead of their own information.  Not that that would ever happen...  Also, it''s not clear what words are included in the list, but you have to wonder how such systems handle searches for things like "breast cancer?"  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>AMD''s Next-Generation Mobile Architecture Revealed: Griffin</title>\r\n  <link>http://tech.originalsignal.com/article/51962/amds-next-generation-mobile-architecture-revealed-griffin.html</link>\r\n  <pubDate>Fri, 18 May 2007 06:38:24 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/51962/amds-next-generation-mobile-architecture-revealed-griffin.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  AMD is continuing with its disclosure of future plans, this time talking about Griffin and Puma, it''s new mobile architecture and platform.  ]]></content:encoded>\r\n  </item>\r\n    	\r\n	</channel>\r\n</rss>', '20070518091623');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_flirts`
-- 

DROP TABLE IF EXISTS `dsb_flirts`;
CREATE TABLE `dsb_flirts` (
  `flirt_id` int(3) unsigned NOT NULL auto_increment,
  `flirt_text` text NOT NULL,
  `flirt_type` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`flirt_id`),
  KEY `flirt_type` (`flirt_type`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_flirts`
-- 

INSERT INTO `dsb_flirts` (`flirt_id`, `flirt_text`, `flirt_type`) VALUES (5, 'Hey sexy!', 0),
(6, 'Whaaadddup!', 0),
(4, 'I hope you know CPR, cuz you take my breath away...', 0),
(7, 'Hello? Hello? Anybody there?', 0),
(8, 'In your dreams', 1),
(9, 'Maybe later', 1),
(10, 'Sure, let''s go', 1);

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

INSERT INTO `dsb_lang_keys` (`lk_id`, `lk_type`, `lk_diz`, `lk_use`) VALUES (1, 2, 'Category name', 1),
(2, 2, 'Category name', 1),
(3, 2, 'Label for f1 field', 0),
(4, 2, 'Search label for f1 field', 0),
(5, 4, 'Help text for f1 field', 0),
(6, 2, 'Field value', 1),
(7, 2, 'Field value', 1),
(8, 2, 'Label for f2 field', 0),
(9, 2, 'Search label for f2 field', 0),
(10, 4, 'Help text for f2 field', 0),
(11, 2, 'Field value', 1),
(12, 2, 'Field value', 1),
(13, 2, 'Label for f3 field', 0),
(14, 2, 'Search label for f3 field', 0),
(15, 4, 'Help text for f3 field', 0),
(46, 2, 'Field value', 1),
(47, 2, 'Field value', 1),
(48, 2, 'Field value', 1),
(49, 2, 'Field value', 1),
(22, 2, 'Label for f4 field', 0),
(23, 2, 'Search label for f4 field', 0),
(24, 4, 'Help text for f4 field', 0),
(25, 2, 'Label for f5 field', 0),
(26, 2, 'Search label for f5 field', 0),
(27, 4, 'Help text for f5 field', 0),
(28, 2, 'Label for f6 field', 0),
(29, 2, 'Search label for f6 field', 0),
(30, 4, 'Help text for f6 field', 0),
(31, 2, 'Category name', 1),
(32, 2, 'Category name', 1),
(33, 2, 'Category name', 1),
(34, 2, 'Field value', 1),
(35, 2, 'Field value', 1),
(36, 2, 'Field value', 1),
(37, 2, 'Field value', 1),
(38, 2, 'Field value', 1),
(39, 2, 'Field value', 1),
(40, 2, 'Field value', 1),
(41, 2, 'Field value', 1),
(42, 2, 'Field value', 1),
(43, 2, 'Label for f7 field', 0),
(44, 2, 'Search label for f7 field', 0),
(45, 4, 'Help text for f7 field', 0),
(50, 2, 'Field value', 1),
(51, 2, 'Field value', 1),
(52, 2, 'Field value', 1),
(53, 2, 'Field value', 1),
(54, 2, 'Field value', 1),
(55, 2, 'Field value', 1),
(106, 2, 'Field value', 1),
(57, 2, 'Field value', 1),
(58, 2, 'Field value', 1),
(59, 2, 'Field value', 1),
(60, 2, 'Field value', 1),
(61, 2, 'Field value', 1),
(62, 2, 'Field value', 1),
(63, 2, 'Field value', 1),
(64, 2, 'Field value', 1),
(65, 2, 'Field value', 1),
(66, 2, 'Field value', 1),
(67, 2, 'Field value', 1),
(68, 2, 'Field value', 1),
(69, 2, 'Field value', 1),
(70, 2, 'Field value', 1),
(71, 2, 'Field value', 1),
(72, 2, 'Field value', 1),
(73, 2, 'Field value', 1),
(74, 2, 'Field value', 1),
(75, 2, 'Field value', 1),
(76, 2, 'Field value', 1),
(77, 2, 'Field value', 1),
(78, 2, 'Field value', 1),
(79, 2, 'Field value', 1),
(80, 2, 'Field value', 1),
(81, 2, 'Field value', 1),
(82, 2, 'Field value', 1),
(83, 2, 'Field value', 1),
(84, 2, 'Field value', 1),
(85, 2, 'Field value', 1),
(86, 2, 'Field value', 1),
(87, 2, 'Field value', 1),
(88, 2, 'Field value', 1),
(89, 2, 'Field value', 1),
(90, 2, 'Field value', 1),
(91, 2, 'Field value', 1),
(92, 2, 'Field value', 1),
(93, 2, 'Field value', 1),
(94, 2, 'Field value', 1),
(95, 2, 'Field value', 1),
(96, 2, 'Field value', 1),
(97, 2, 'Field value', 1),
(98, 2, 'Field value', 1),
(99, 2, 'Field value', 1),
(100, 2, 'Field value', 1),
(101, 2, 'Field value', 1),
(102, 2, 'Field value', 1),
(103, 2, 'Field value', 1),
(104, 2, 'Field value', 1),
(105, 2, 'Field value', 1),
(107, 2, 'Field value', 1),
(108, 2, 'Field value', 1),
(109, 2, 'Field value', 1),
(110, 2, 'Field value', 1),
(111, 2, 'Field value', 1),
(112, 2, 'Field value', 1),
(113, 2, 'Field value', 1),
(114, 2, 'Field value', 1),
(115, 2, 'Field value', 1),
(116, 2, 'Label for f8 field', 0),
(117, 2, 'Search label for f8 field', 0),
(118, 4, 'Help text for f8 field', 0),
(119, 2, 'Field value', 1),
(120, 2, 'Field value', 1),
(121, 2, 'Field value', 1),
(122, 2, 'Field value', 1),
(123, 2, 'Field value', 1),
(124, 2, 'Field value', 1),
(125, 2, 'Label for f9 field', 0),
(126, 2, 'Search label for f9 field', 0),
(127, 4, 'Help text for f9 field', 0),
(128, 2, 'Field value', 1),
(129, 2, 'Field value', 1),
(130, 2, 'Field value', 1),
(131, 2, 'Field value', 1),
(132, 2, 'Field value', 1),
(133, 2, 'Field value', 1),
(134, 2, 'Field value', 1),
(135, 2, 'Field value', 1),
(136, 2, 'Field value', 1),
(137, 2, 'Label for f10 field', 0),
(138, 2, 'Search label for f10 field', 0),
(139, 4, 'Help text for f10 field', 0),
(140, 2, 'Field value', 1),
(141, 2, 'Field value', 1),
(142, 2, 'Field value', 1),
(143, 2, 'Label for f11 field', 0),
(144, 2, 'Search label for f11 field', 0),
(145, 4, 'Help text for f11 field', 0),
(146, 2, 'Field value', 1),
(147, 2, 'Field value', 1),
(148, 2, 'Field value', 1),
(149, 2, 'Label for f12 field', 0),
(150, 2, 'Search label for f12 field', 0),
(151, 4, 'Help text for f12 field', 0),
(152, 2, 'Field value', 1),
(153, 2, 'Field value', 1),
(154, 2, 'Field value', 1),
(155, 2, 'Field value', 1),
(156, 2, 'Field value', 1),
(157, 2, 'Label for f13 field', 0),
(158, 2, 'Search label for f13 field', 0),
(159, 4, 'Help text for f13 field', 0),
(160, 2, 'Field value', 1),
(161, 2, 'Field value', 1),
(162, 2, 'Field value', 1),
(163, 2, 'Field value', 1),
(164, 2, 'Label for f14 field', 0),
(165, 2, 'Search label for f14 field', 0),
(166, 4, 'Help text for f14 field', 0),
(167, 2, 'Field value', 1),
(168, 2, 'Field value', 1),
(169, 2, 'Field value', 1),
(170, 2, 'Field value', 1),
(171, 2, 'Field value', 1),
(172, 2, 'Field value', 1),
(173, 2, 'Field value', 1),
(174, 2, 'Label for f15 field', 0),
(175, 2, 'Search label for f15 field', 0),
(176, 4, 'Help text for f15 field', 0);

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

INSERT INTO `dsb_lang_strings` (`ls_id`, `fk_lk_id`, `skin`, `lang_value`) VALUES (1, 1, 'skin_def', 'Basic Info'),
(2, 2, 'skin_def', 'Appearance'),
(3, 3, 'skin_def', 'About me'),
(4, 4, 'skin_def', ''),
(5, 5, 'skin_def', ''),
(6, 6, 'skin_def', 'Man'),
(7, 7, 'skin_def', 'Woman'),
(8, 8, 'skin_def', 'I am a'),
(9, 9, 'skin_def', 'Find a'),
(10, 10, 'skin_def', ''),
(11, 11, 'skin_def', 'Men'),
(12, 12, 'skin_def', 'Women'),
(13, 13, 'skin_def', 'Seeking'),
(14, 14, 'skin_def', 'Seeking'),
(15, 15, 'skin_def', ''),
(49, 49, 'skin_def', '3''3"'),
(50, 50, 'skin_def', '3''4"'),
(51, 51, 'skin_def', '3''5"'),
(52, 52, 'skin_def', '3''6"'),
(22, 22, 'skin_def', 'My height is'),
(23, 23, 'skin_def', 'Height'),
(24, 24, 'skin_def', ''),
(31, 31, 'skin_def', 'Lifestyle'),
(46, 46, 'skin_def', '3''0"'),
(47, 47, 'skin_def', '3''1"'),
(48, 48, 'skin_def', '3''2"'),
(25, 25, 'skin_def', 'Birthdate'),
(26, 26, 'skin_def', 'Age'),
(27, 27, 'skin_def', ''),
(28, 28, 'skin_def', 'Country'),
(29, 29, 'skin_def', 'From'),
(30, 30, 'skin_def', ''),
(32, 32, 'skin_def', 'Home Life'),
(33, 33, 'skin_def', 'Personality'),
(34, 34, 'skin_def', 'African American (black)'),
(35, 35, 'skin_def', 'Asian'),
(36, 36, 'skin_def', 'Caucasian (white)'),
(37, 37, 'skin_def', 'East Indian'),
(38, 38, 'skin_def', 'Hispanic/Latino'),
(39, 39, 'skin_def', 'Middle Eastern'),
(40, 40, 'skin_def', 'Native American'),
(41, 41, 'skin_def', 'Pacific Islander'),
(42, 42, 'skin_def', 'Inter-racial'),
(43, 43, 'skin_def', 'My ethnicity is'),
(44, 44, 'skin_def', 'Having ethnicity'),
(45, 45, 'skin_def', ''),
(53, 53, 'skin_def', '3''7"'),
(54, 54, 'skin_def', '3''8"'),
(55, 55, 'skin_def', '3''9"'),
(106, 106, 'skin_def', '4''0"'),
(57, 57, 'skin_def', '3''10"'),
(58, 58, 'skin_def', '3''11"'),
(59, 59, 'skin_def', '4''1"'),
(60, 60, 'skin_def', '4''2"'),
(61, 61, 'skin_def', '4''3"'),
(62, 62, 'skin_def', '4''4"'),
(63, 63, 'skin_def', '4''5"'),
(64, 64, 'skin_def', '4''6"'),
(65, 65, 'skin_def', '4''7"'),
(66, 66, 'skin_def', '4''8"'),
(67, 67, 'skin_def', '4''9"'),
(68, 68, 'skin_def', '4''10"'),
(69, 69, 'skin_def', '4''11"'),
(70, 70, 'skin_def', '5''0"'),
(71, 71, 'skin_def', '5''1"'),
(72, 72, 'skin_def', '5''2"'),
(73, 73, 'skin_def', '5''3"'),
(74, 74, 'skin_def', '5''4"'),
(75, 75, 'skin_def', '5''5"'),
(76, 76, 'skin_def', '5''6"'),
(77, 77, 'skin_def', '5''7"'),
(78, 78, 'skin_def', '5''8"'),
(79, 79, 'skin_def', '5''9"'),
(80, 80, 'skin_def', '5''10"'),
(81, 81, 'skin_def', '5''11"'),
(82, 82, 'skin_def', '6''0"'),
(83, 83, 'skin_def', '6''1"'),
(84, 84, 'skin_def', '6''2"'),
(85, 85, 'skin_def', '6''3"'),
(86, 86, 'skin_def', '6''4"'),
(87, 87, 'skin_def', '6''5"'),
(88, 88, 'skin_def', '6''6"'),
(89, 89, 'skin_def', '6''7"'),
(90, 90, 'skin_def', '6''8"'),
(91, 91, 'skin_def', '6''9"'),
(92, 92, 'skin_def', '6''10"'),
(93, 93, 'skin_def', '6''11"'),
(94, 94, 'skin_def', '7''0"'),
(95, 95, 'skin_def', '7''1"'),
(96, 96, 'skin_def', '7''2"'),
(97, 97, 'skin_def', '7''3"'),
(98, 98, 'skin_def', '7''4"'),
(99, 99, 'skin_def', '7''5"'),
(100, 100, 'skin_def', '7''6"'),
(101, 101, 'skin_def', '7''7"'),
(102, 102, 'skin_def', '7''8"'),
(103, 103, 'skin_def', '7''9"'),
(104, 104, 'skin_def', '7''10"'),
(105, 105, 'skin_def', '7''11"'),
(107, 107, 'skin_def', 'Slim'),
(108, 108, 'skin_def', 'Slender'),
(109, 109, 'skin_def', 'Average'),
(110, 110, 'skin_def', 'Athletic'),
(111, 111, 'skin_def', 'Fit'),
(112, 112, 'skin_def', 'Thick'),
(113, 113, 'skin_def', 'A few extra pounds'),
(114, 114, 'skin_def', 'Large'),
(115, 115, 'skin_def', 'Voluptous'),
(116, 116, 'skin_def', 'My body type is'),
(117, 117, 'skin_def', 'Body'),
(118, 118, 'skin_def', ''),
(119, 119, 'skin_def', 'Black'),
(120, 120, 'skin_def', 'Blue'),
(121, 121, 'skin_def', 'Brown'),
(122, 122, 'skin_def', 'Gray'),
(123, 123, 'skin_def', 'Green'),
(124, 124, 'skin_def', 'Hazel'),
(125, 125, 'skin_def', 'My eyes are'),
(126, 126, 'skin_def', 'Eyes'),
(127, 127, 'skin_def', ''),
(128, 128, 'skin_def', 'Auburn'),
(129, 129, 'skin_def', 'Black'),
(130, 130, 'skin_def', 'Blonde'),
(131, 131, 'skin_def', 'Light Brown'),
(132, 132, 'skin_def', 'Dark Brown'),
(133, 133, 'skin_def', 'Red'),
(134, 134, 'skin_def', 'White/Gray'),
(135, 135, 'skin_def', 'Bald'),
(136, 136, 'skin_def', 'A little gray'),
(137, 137, 'skin_def', 'My hair is'),
(138, 138, 'skin_def', 'Hair'),
(139, 139, 'skin_def', ''),
(140, 140, 'skin_def', 'No'),
(141, 141, 'skin_def', 'Socially'),
(142, 142, 'skin_def', 'Daily'),
(143, 143, 'skin_def', 'I smoke'),
(144, 144, 'skin_def', 'Smoke'),
(145, 145, 'skin_def', ''),
(146, 146, 'skin_def', 'No'),
(147, 147, 'skin_def', 'Socially'),
(148, 148, 'skin_def', 'Daily'),
(149, 149, 'skin_def', 'I drink'),
(150, 150, 'skin_def', 'Drink'),
(151, 151, 'skin_def', ''),
(152, 152, 'skin_def', 'Some High School'),
(153, 153, 'skin_def', 'High School Grad'),
(154, 154, 'skin_def', 'Some College'),
(155, 155, 'skin_def', 'College Grad'),
(156, 156, 'skin_def', 'Post-Graduate'),
(157, 157, 'skin_def', 'My education is'),
(158, 158, 'skin_def', 'Education'),
(159, 159, 'skin_def', ''),
(160, 160, 'skin_def', 'Single, never married'),
(161, 161, 'skin_def', 'Divorced'),
(162, 162, 'skin_def', 'Widowed'),
(163, 163, 'skin_def', 'Separated'),
(164, 164, 'skin_def', 'My marital status is'),
(165, 165, 'skin_def', 'Marital status'),
(166, 166, 'skin_def', ''),
(167, 167, 'skin_def', 'Alone'),
(168, 168, 'skin_def', 'With kids'),
(169, 169, 'skin_def', 'With parents'),
(170, 170, 'skin_def', 'With pets'),
(171, 171, 'skin_def', 'With roommate(s)'),
(172, 172, 'skin_def', 'Family and friends visit often'),
(173, 173, 'skin_def', 'There is a party every night'),
(174, 174, 'skin_def', 'I am currently living'),
(175, 175, 'skin_def', 'Living'),
(176, 176, 'skin_def', '');

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
(0x6f7369676e616c5f66656564, 'Original Signal Tech Feed', 'Retrieves the latest original signal tech stories', 3, 1.00),
(0x736b696e5f646566, 'Default Skin', 'Official default skin', 4, 0.01),
(0x6465665f757365725f7072656673, 'Default User Preferences', 'The default user preferences', 0, 1.00),
(0x646174656d696c6c5f66656564, 'Datemill Admin Feed', 'Datemill news for administrators', 3, 1.00);

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
(2, 'Blocked Members', 0, 0),
(3, 'Favorites', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_online`
-- 

DROP TABLE IF EXISTS `dsb_online`;
CREATE TABLE `dsb_online` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `last_activity` timestamp(14) NOT NULL,
  `sess` varchar(32) binary NOT NULL default '',
  UNIQUE KEY `key2` (`fk_user_id`,`sess`),
  UNIQUE KEY `key1` (`sess`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_online`
-- 

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES (0, '20070523194829', 0x3263663832336133653337616332336132373433363434393264616132663430);

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

INSERT INTO `dsb_payments` (`payment_id`, `fk_user_id`, `_user`, `gateway`, `fk_subscr_id`, `is_recuring`, `gw_txn`, `name`, `country`, `state`, `city`, `zip`, `street_address`, `email`, `phone`, `m_value_from`, `m_value_to`, `amount_paid`, `paid_from`, `paid_until`, `is_suspect`, `suspect_reason`, `date`) VALUES (1, 24, 'ssw33', '', 1, 0, '', '', '', '', '', '', '', 'newdsb9@sco.ro', '', 2, 4, 0.00, '2007-05-02', '2007-06-01', 0, '', '20070502190356'),
(2, 25, 'ssw333', '', 1, 0, '', '', '', '', '', '', '', 'newdsb10@sco.ro', '', 2, 4, 0.00, '2007-05-02', '2007-06-01', 0, '', '20070502191258'),
(3, 26, 'testere', '', 1, 0, '', '', '', '', '', '', '', 'testere@sco.ro', '', 2, 4, 0.00, '2007-05-07', '2007-06-06', 0, '', '20070507215648');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_photo_comments`
-- 

DROP TABLE IF EXISTS `dsb_photo_comments`;
CREATE TABLE `dsb_photo_comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `fk_parent_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `comment` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_parent_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_photo_comments`
-- 

INSERT INTO `dsb_photo_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 1, 6, 'cocacola', 'hi thetr', '2007-04-16 18:54:48', '2007-04-16 18:54:48', 15),
(2, 1, 9, 'raneglo', 'Hello Mr Watermark', '2007-04-16 19:59:47', '2007-04-16 19:59:47', 15),
(3, 6, 10, 'strawberries', 'party on, dude!', '2007-04-16 21:12:23', '2007-04-16 21:12:23', 15),
(4, 1, 11, 'johnboy', 'Hi Maverick, your pic looks cool :)', '2007-04-17 04:46:16', '2007-04-17 04:46:16', 15),
(5, 5, 11, 'johnboy', 'I wonder if you might be Dan.... are you?', '2007-04-17 04:49:33', '2007-04-17 04:49:33', 15),
(6, 9, 11, 'johnboy', 'Looks pretty cold there', '2007-04-17 05:35:36', '2007-04-17 05:35:36', 15),
(7, 9, 13, 'johnboy2', 'YES it is', '2007-04-17 12:29:32', '2007-04-17 12:29:32', 15),
(8, 5, 1, 'emma', 'bau ''    uab\n\nLast edited by emma on 2007-04-24 14:17:20 GMT', '2007-04-19 14:25:46', '2007-04-24 14:17:20', 15),
(13, 18, 1, 'emma', 'asdasd', '2007-04-21 19:07:52', '2007-04-21 19:07:52', 15),
(14, 18, 7, 'dragon', ':)\n\nLast edited by dragon on 2007-04-27 17:15:57 GMT', '2007-04-24 17:19:32', '2007-04-27 17:15:57', 15);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_photo_ratings`
-- 

DROP TABLE IF EXISTS `dsb_photo_ratings`;
CREATE TABLE `dsb_photo_ratings` (
  `fk_photo_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `vote` int(2) unsigned NOT NULL default '0',
  `date_voted` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `fk_photo_id` (`fk_photo_id`),
  KEY `key1` (`fk_user_id`,`date_voted`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_photo_ratings`
-- 

INSERT INTO `dsb_photo_ratings` (`fk_photo_id`, `fk_user_id`, `vote`, `date_voted`) VALUES (1, 6, 4, '2007-04-16 18:54:40'),
(5, 1, 5, '2007-04-16 19:45:27'),
(5, 8, 5, '2007-04-16 20:16:18'),
(1, 11, 5, '2007-04-17 04:46:33'),
(5, 11, 5, '2007-04-17 04:49:46'),
(8, 13, 5, '2007-04-17 05:22:43'),
(9, 11, 4, '2007-04-17 05:35:43');

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

INSERT INTO `dsb_profile_categories` (`pcat_id`, `fk_lk_id_pcat`, `access_level`) VALUES (1, 1, 7),
(2, 2, 7),
(3, 31, 7),
(4, 32, 7),
(5, 33, 7);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_profile_comments`
-- 

DROP TABLE IF EXISTS `dsb_profile_comments`;
CREATE TABLE `dsb_profile_comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `fk_parent_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `comment` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_parent_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_profile_comments`
-- 

INSERT INTO `dsb_profile_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 1, 7, 'dragon', 'hi emma', '2007-04-27 16:03:07', '2007-04-27 16:03:07', 15),
(2, 7, 7, 'dragon', 'hi emma1', '2007-04-27 16:20:27', '2007-04-27 16:20:27', 15),
(3, 1, 7, 'dragon', 'hi emma', '2007-04-27 16:20:47', '2007-04-27 16:20:47', 15),
(4, 1, 7, 'dragon', 'whoa', '2007-04-27 16:25:29', '2007-04-27 16:25:29', 15),
(5, 0, 7, 'dragon', 'booboo', '2007-05-15 09:28:36', '2007-05-15 09:28:36', 15);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_profile_fields`
-- 

DROP TABLE IF EXISTS `dsb_profile_fields`;
CREATE TABLE `dsb_profile_fields` (
  `pfield_id` int(5) unsigned NOT NULL auto_increment,
  `fk_lk_id_label` int(5) unsigned NOT NULL default '0',
  `field_type` tinyint(2) unsigned NOT NULL default '0',
  `searchable` tinyint(1) unsigned NOT NULL default '0',
  `search_type` tinyint(2) unsigned NOT NULL default '0',
  `for_basic` tinyint(1) unsigned NOT NULL default '0',
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

INSERT INTO `dsb_profile_fields` (`pfield_id`, `fk_lk_id_label`, `field_type`, `searchable`, `search_type`, `for_basic`, `fk_lk_id_search`, `at_registration`, `reg_page`, `required`, `editable`, `visible`, `dbfield`, `fk_lk_id_help`, `fk_pcat_id`, `access_level`, `accepted_values`, `default_value`, `default_search`, `fn_on_change`, `order_num`) VALUES (1, 3, 4, 0, 1, 0, 4, 1, 2, 0, 1, 1, 0x6631, 5, 1, 0, '1000', '', '', '', 5),
(2, 8, 3, 1, 10, 1, 9, 1, 1, 1, 1, 1, 0x6632, 10, 1, 0, '|6|7|', '|0|', '|1|', '', 1),
(3, 13, 10, 1, 10, 1, 14, 1, 1, 1, 1, 1, 0x6633, 15, 1, 0, '|11|12|', '|1|', '|0|', '', 2),
(4, 22, 3, 1, 108, 0, 23, 1, 2, 0, 1, 1, 0x6634, 24, 2, 0, '|49|50|51|52|46|47|48|53|54|55|57|58|106|59|60|61|62|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|', '', '|0|59|', '', 7),
(5, 25, 103, 1, 108, 1, 26, 1, 1, 0, 1, 1, 0x6635, 27, 1, 0, '|1930|1989|', '', '|18|75|', '', 3),
(6, 28, 107, 1, 107, 1, 29, 1, 1, 0, 1, 1, 0x6636, 30, 1, 0, '', '|218|', '', 'update_location', 4),
(7, 43, 3, 1, 10, 0, 44, 1, 2, 0, 1, 1, 0x6637, 45, 2, 0, '|34|35|36|37|38|39|40|41|42|', '', '', '', 6),
(8, 116, 3, 1, 10, 0, 117, 1, 2, 0, 1, 1, 0x6638, 118, 2, 0, '|107|108|109|110|111|112|113|114|115|', '', '', '', 8),
(9, 125, 3, 1, 3, 0, 126, 1, 2, 0, 1, 1, 0x6639, 127, 2, 0, '|119|120|121|122|123|124|', '', '', '', 9),
(10, 137, 3, 1, 10, 0, 138, 1, 2, 0, 1, 1, 0x663130, 139, 2, 0, '|128|129|130|131|132|133|134|135|136|', '', '', '', 10),
(11, 143, 3, 1, 10, 0, 144, 1, 2, 0, 1, 1, 0x663131, 145, 3, 0, '|140|141|142|', '', '', '', 11),
(12, 149, 3, 1, 10, 0, 150, 1, 2, 0, 1, 1, 0x663132, 151, 3, 0, '|146|147|148|', '', '', '', 12),
(13, 157, 3, 1, 3, 0, 158, 1, 2, 0, 1, 1, 0x663133, 159, 3, 0, '|152|153|154|155|156|', '', '', '', 13),
(14, 164, 3, 1, 3, 0, 165, 1, 2, 0, 1, 1, 0x663134, 166, 4, 0, '|160|161|162|163|', '', '', '', 14),
(15, 174, 10, 1, 10, 0, 175, 1, 2, 0, 1, 1, 0x663135, 176, 4, 0, '|167|168|169|170|171|172|173|', '', '', '', 15);

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
  KEY `to_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_queue_message`
-- 

INSERT INTO `dsb_queue_message` (`mail_id`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (9, 0, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm5">Click here</a> to view the comment', '2007-05-15 09:28:36', 2);

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

INSERT INTO `dsb_rate_limiter` (`rate_id`, `fk_level_id`, `m_value`, `limit`, `interval`, `punishment`) VALUES (1, 1, 1, 1, 2, 1);

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

INSERT INTO `dsb_site_log` (`log_id`, `fk_user_id`, `user`, `m_value`, `fk_level_id`, `ip`, `time`) VALUES (1, 0, 'dragon', 1, 1, 2130706433, '20070517190225'),
(2, 0, 'dragon', 1, 1, 2130706433, '20070517191702'),
(3, 0, 'dragon', 1, 1, 2130706433, '20070517193331'),
(4, 0, 'dragon', 1, 1, 2130706433, '20070519145307'),
(5, 0, 'dragon', 1, 1, 2130706433, '20070519145559'),
(6, 0, 'dragon', 1, 1, 2130706433, '20070519145605'),
(7, 0, 'dragon', 1, 1, 2130706433, '20070522141855');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_site_news`
-- 

DROP TABLE IF EXISTS `dsb_site_news`;
CREATE TABLE `dsb_site_news` (
  `news_id` int(10) unsigned NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL default '',
  `news_body` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`news_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_news`
-- 

INSERT INTO `dsb_site_news` (`news_id`, `news_title`, `news_body`, `date_posted`) VALUES (1, 'first news', '<p>N<font size="1">e</font><font size="2">w</font><font size="3">s</font> <font size="4">b</font><font size="5">o</font><font size="6">d</font><font size="7">y</font></p>', '0000-00-00 00:00:00'),
(2, 'second news entry', '<p>This one shows</p>\r\n<ol>\r\n    <li><a href="http://forum.datemill.com">links</a> in posts</li>\r\n    <li>c<font color="#ff6600">o</font><font color="#0000ff">l</font><font color="#00ff00">o</font><font color="#ff00ff">r</font><font color="#ffffff"><span style="background-color: rgb(255, 0, 0);">s</span></font></li>\r\n    <li><font face="Comic Sans MS">different </font><font face="Tahoma">fonts</font></li>\r\n    <li>and <font size="5">sizes</font></li>\r\n    <li>and let''s not forget lists</li>\r\n</ol>', '0000-00-00 00:00:00');

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
  `option_type` tinyint(3) unsigned NOT NULL default '0',
  `fk_module_code` varchar(32) binary NOT NULL default '',
  `per_user` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`config_id`),
  UNIQUE KEY `thekey` (`config_option`,`fk_module_code`),
  KEY `per_user` (`per_user`),
  KEY `fk_module_code` (`fk_module_code`)
) TYPE=MyISAM COMMENT='0-n/a,9-chkbox,2-tf,4-ta';

-- 
-- Dumping data for table `dsb_site_options3`
-- 

INSERT INTO `dsb_site_options3` (`config_id`, `config_option`, `config_value`, `config_diz`, `option_type`, `fk_module_code`, `per_user`) VALUES (1, 0x64626669656c645f696e646578, '16', 'The last index of the custom profile fields (field_xx)', 0, 0x636f7265, 0),
(2, 0x7573655f63617074636861, '1', 'Use the dynamic image text (captcha image) to keep spam bots out?', 9, 0x636f7265, 0),
(3, 0x6d616e75616c5f70726f66696c655f617070726f76616c, '0', 'New profiles or changes to existing profiles require manual approval from an administrator before being displayed on site?', 9, 0x636f7265, 0),
(4, 0x646174655f666f726d6174, '%m/%d/%Y', 'Default date format', 2, 0x636f7265, 1),
(5, 0x74315f7769647468, '100', 'The width in pixels of the smalest thumbnail generated for each user photo', 104, 0x636f72655f70686f746f, 0),
(6, 0x74325f7769647468, '500', 'The width in pixels of the larger thumbnail generated for each user photo', 104, 0x636f72655f70686f746f, 0),
(7, 0x7069635f7769647468, '800', 'The maximum width in pixels of any picture uploaded by a member', 104, 0x636f72655f70686f746f, 0),
(8, 0x6d616e75616c5f70686f746f5f617070726f76616c, '1', 'New uploaded photos require manual approval before being displayed on the site?', 9, 0x636f72655f70686f746f, 0),
(9, 0x6d616e75616c5f626c6f675f617070726f76616c, '0', 'New blog posts or changes to existing posts require manual approval from an administrator before being displayed on site?', 9, 0x636f72655f626c6f67, 0),
(10, 0x6d616e75616c5f636f6d5f617070726f76616c, '0', 'Comments to profiles, photos, blogs need approval from admin?', 9, 0x636f7265, 0),
(11, 0x77617465726d61726b5f74657874, 'watermark text', 'The text to stamp the user photos with', 2, 0x636f72655f70686f746f, 0),
(12, 0x77617465726d61726b5f746578745f636f6c6f72, 'FFFFFF', 'Color of the text watermark', 2, 0x636f72655f70686f746f, 0),
(13, 0x6d6f64756c655f616374697665, '1', 'Module active?', 9, 0x70617970616c, 0),
(14, 0x70617970616c5f656d61696c, 'dan@sco.ro', 'Your paypal email address', 2, 0x70617970616c, 0),
(15, 0x6d6f64756c655f616374697665, '1', 'Is this module active?', 9, 0x74776f636865636b6f7574, 0),
(16, 0x736964, '117760', 'Your 2co seller ID', 2, 0x74776f636865636b6f7574, 0),
(17, 0x64656d6f5f6d6f6465, '1', 'Enable test mode? Don''t enable this on a live site!', 9, 0x74776f636865636b6f7574, 0),
(18, 0x64656d6f5f6d6f6465, '1', 'Enable test mode? Don''t enable this on a live site!', 9, 0x70617970616c, 0),
(19, 0x736563726574, 'secret_word', 'The secret word you set in your 2co account', 2, 0x74776f636865636b6f7574, 0),
(20, 0x6c6963656e73655f6b6579, '1234', 'Your Maxmind license key', 2, 0x6d61786d696e64, 0),
(21, 0x7573655f7175657565, '1', 'Use the message queue (recommended) or send the messages directly?', 9, 0x636f7265, 0),
(22, 0x6d61696c5f66726f6d, 'dan@rdsct.ro', 'Email address to send emails from', 2, 0x636f7265, 0),
(23, 0x6262636f64655f70726f66696c65, '1', 'Use BBcode in profile fields? (like about me, about you)', 9, 0x636f7265, 0),
(24, 0x6262636f64655f636f6d6d656e7473, '1', 'Use BBcode in comments?', 9, 0x636f7265, 0),
(32, 0x6d696e5f73697a65, '0', 'Minimum photo file size in bytes (use 0 for not limited).', 104, 0x636f72655f70686f746f, 0),
(33, 0x6d61785f73697a65, '0', 'Maximum photo file size in bytes (use 0 for server default).', 104, 0x636f72655f70686f746f, 0),
(34, 0x6262636f64655f6d657373616765, '1', 'Allow BBCode in member to member messages?', 9, 0x636f7265, 0),
(35, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 'Date and time format', 2, 0x636f7265, 1),
(36, 0x726f756e645f636f726e657273, '1', 'Use round corners for user photos?', 9, 0x636f72655f70686f746f, 0),
(37, 0x656e61626c6564, '1', 'Enable this widget?', 9, 0x6f7369676e616c5f66656564, 0),
(38, 0x666565645f75726c, 'http://feeds.feedburner.com/OriginalSignal/tech', 'The url of the feed', 2, 0x6f7369676e616c5f66656564, 0),
(39, 0x736b696e5f646972, 'def', '', 0, 0x736b696e5f646566, 0),
(40, 0x736b696e5f6e616d65, 'Default', '', 0, 0x736b696e5f646566, 0),
(41, 0x666b5f6c6f63616c655f6964, '11', '', 0, 0x736b696e5f646566, 0),
(42, 0x69735f64656661756c74, '1', '', 0, 0x736b696e5f646566, 0),
(43, 0x696e6163746976655f74696d65, '5', 'Time of inactivity in minutes after a member is considered offline', 104, 0x636f7265, 0),
(44, 0x6262636f64655f626c6f6773, '1', 'Allow bbcode in blog posts?', 9, 0x636f72655f626c6f67, 0),
(45, 0x73656e645f616c6572745f696e74657276616c, '2', 'How often do you want to receive your search matches? (days)', 104, 0x6465665f757365725f7072656673, 1),
(46, 0x726174655f6d795f70726f66696c65, '1', 'Allow my profile to be rated?', 9, 0x6465665f757365725f7072656673, 1),
(47, 0x726174655f6d795f70686f746f73, '1', 'Allow my photos to be rated?', 9, 0x6465665f757365725f7072656673, 1),
(48, 0x70757267655f756e7665726966696564, '7', 'Purge unverified accounts after how many days?', 104, 0x636f7265, 0),
(50, 0x6e6f746966795f6d65, '1', 'Send me email notifications when I receive messages?', 9, 0x6465665f757365725f7072656673, 1),
(51, 0x6d61696c5f63726c66, '1', 'Check or uncheck this option if you can''t send emails out to members', 9, 0x636f7265, 0),
(52, 0x7573655f736d696c696573, '1', 'Allow smilies in profile fields?', 9, 0x636f7265, 0),
(53, 0x736d696c6965735f636f6d6d, '1', 'Allow smilies in user comments?', 9, 0x636f7265, 0),
(54, 0x7573655f736d696c696573, '1', 'Allow smilies in blogs?', 9, 0x636f72655f626c6f67, 0),
(55, 0x70726f66696c655f636f6d6d656e7473, '1', 'Allow comments on my profile?', 9, 0x6465665f757365725f7072656673, 1),
(56, 0x74615f6c656e, '1000', 'Maximum number of characters users may enter in textareas (use 0 for unlimited)', 104, 0x636f7265, 0),
(57, 0x616c6c6f775f6e657773, '1', 'Receive periodic news and announcements to your email address?', 9, 0x6465665f757365725f7072656673, 1),
(58, 0x666565645f75726c, 'http://www.datemill.com/remote/feeds/admin.xml', 'The url of the feed', 2, 0x646174656d696c6c5f66656564, 0);

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

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES ('40cd750bba9870f18aada2478b24840a', 2, 'a:0:{}', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19', 0, '20070523231145'),
('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26', 0, '20070523231138'),
('40cd750bba9870f18aada2478b24840a', 3, 'a:0:{}', '6,7,8,9,10', 0, '20070523200114'),
('03779786131a896c9673218c42c33ce8', 3, 'a:1:{s:3:"uid";s:2:"11";}', '10', 0, '20070523192001'),
('15867d3158697d2b499246c7694fdde6', 1, 'a:1:{s:4:"user";s:1:"j";}', '11,13', 0, '20070523195805');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_stats_dot`
-- 

DROP TABLE IF EXISTS `dsb_stats_dot`;
CREATE TABLE `dsb_stats_dot` (
  `dot_id` int(10) unsigned NOT NULL auto_increment,
  `dataset` varchar(48) NOT NULL default '',
  `value` int(10) NOT NULL default '0',
  `time` int(8) NOT NULL default '0',
  PRIMARY KEY  (`dot_id`),
  UNIQUE KEY `key1` (`dataset`,`time`),
  KEY `dataset` (`dataset`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_stats_dot`
-- 

INSERT INTO `dsb_stats_dot` (`dot_id`, `dataset`, `value`, `time`) VALUES (1, 'online_users', 0, 20070520),
(2, 'online_users', 1, 20070521),
(3, 'num_users', 25, 20070521),
(4, 'online_users', 0, 20070523);

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
  `duration_units` enum('DAY','MONTH','YEAR') NOT NULL default 'DAY',
  `is_visible` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`subscr_id`),
  KEY `thekey` (`m_value_from`,`is_visible`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_subscriptions`
-- 

INSERT INTO `dsb_subscriptions` (`subscr_id`, `subscr_name`, `subscr_diz`, `price`, `currency`, `is_recurent`, `m_value_from`, `m_value_to`, `duration`, `duration_units`, `is_visible`) VALUES (1, '30$ / month', '', 30.00, 'USD', 0, 2, 4, 30, 'DAY', 1),
(3, 'Trial', '', 0.00, 'USD', 0, 2, 4, 5, 'DAY', 0),
(4, 'gold membership', 'this is the description for the gold membership which gives you unlimited access to all features for a couple of seconds', 100.00, 'USD', 0, 2, 4, 1, 'DAY', 1),
(5, 'bronze membership', 'ala bala portocala', 130.00, 'EUR', 0, 2, 4, 365, 'DAY', 1);

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

INSERT INTO `dsb_subscriptions_auto` (`asubscr_id`, `dbfield`, `field_value`, `fk_subscr_id`, `date_start`) VALUES (1, 'f2', 2, 3, '0000-00-00'),
(2, '', 0, 1, '0000-00-00');

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
  UNIQUE KEY `user` (`user`),
  KEY `key1` (`status`,`temp_pass`),
  KEY `email` (`email`)
) TYPE=MyISAM COMMENT='membership is m_value';

-- 
-- Dumping data for table `dsb_user_accounts`
-- 

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES (1, 0x656d6d61, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '02912174c5cc19dedd90bc498af2d9b0', '20070511152233'),
(2, 0x6b65697468, 0x3166333837306265323734663663343962336533316130633637323839353766, 15, 2, 'newdsb@sco.ro', '', '2fba765ee1b6ade5287073584ec894e4', '20070418125301'),
(3, 0x73686f6e323035, 0x3037373631303430363739346562326133633736393139396439346131613739, 15, 2, 'newdsb@sco.ro', '', 'b3d7ac7336a79fe5a4b01a88f5727b13', '20070418125301'),
(4, 0x6d6176657269636b, 0x3839393636303063333130383863366137363436346365333864393635636364, 15, 2, 'newdsb@sco.ro', '', '87a6ce818365e8b3bef125df92826702', '20070418125301'),
(5, 0x313030343537, 0x3836653236636161363936356531313066666461353936373465373163643264, 15, 2, 'newdsb@sco.ro', '', 'c5cb27f0e77add4c24ffc48562a0706d', '20070515170816'),
(6, 0x636f6361636f6c61, 0x6361323466306531653366663730316661346633336335336639303566396461, 15, 2, 'newdsb@sco.ro', '', '7f2fe99deb74578bf4f6e5e26dd99ae0', '20070418125301'),
(7, 0x647261676f6e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '1dc9d7dbc613c521a8efeae0f7706f2e', '20070522120222'),
(8, 0x616e676b617361, 0x3634383530366161333739666566623930323663376530313664386463626663, 15, 2, 'newdsb@sco.ro', '', '36e12348474821624874994164838526', '20070516101326'),
(9, 0x72616e65676c6f, 0x3739343063343139653932336166306265373963623835613432663836343964, 15, 2, 'newdsb@sco.ro', '', '83f1200b9f1bdd252e2aebc2dfffe34f', '20070418125301'),
(10, 0x737472617762657272696573, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '68779ace53ca9ba01a52efa82e53bf86', '20070418125301'),
(11, 0x6a6f686e626f79, 0x6463613961313066636662396434346365346265393037393965633762623738, 15, 2, 'newdsb@sco.ro', '', '4173da6fd2bf9e455e18cdd70953f0b6', '20070418125301'),
(12, 0x615f6c5f66, 0x3530393636333531626362373264373463636466636634313038613734613664, 15, 2, 'newdsb@sco.ro', '', 'c846ec67e081e672e99c7a14d167b463', '20070418125301'),
(13, 0x6a6f686e626f7932, 0x6463613961313066636662396434346365346265393037393965633762623738, 15, 2, 'newdsb@sco.ro', '', 'f446c1ae194fc4ed6309e1b60f177ebb', '20070418125301'),
(14, 0x7470616e647470, 0x3639336538313066663237363034653664613237346461346337376531333663, 15, 2, 'newdsb@sco.ro', '', '76b41bfad8b909b658e578f08f7a6244', '20070418125301'),
(15, 0x626c61636b7761746572, 0x6166623332376439386230316537323035383035323233613331313363303563, 15, 2, 'newdsb@sco.ro', '', 'bc217753a490dd732c4e992340db665c', '20070418125301'),
(16, 0x746573746572, 0x6234663062656231303130343734626564396366303234303231366333313464, 10, 2, 'newdsb@sco.ro', '', 'cf3b1718be2b5af79bf2c189eb0bbedf', '20070418125301'),
(17, 0x776f6d616e, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'newdsb2@sco.ro', '', '21ae80263d622606553cb2fb3d5df086', '20070502183910'),
(18, 0x7465737477, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'newdsb3@sco.ro', '', '1b50d61ac11ff0146ccec294f111d87e', '20070502184617'),
(19, 0x746573747777, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'newdsb4@sco.ro', '', '979e19c497d184ebe0d112cc9686a12c', '20070502184720'),
(20, 0x74657374777777, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'newdsb5@sco.ro', '', 'c7cf65943096e6934bcb209caee3a833', '20070502184900'),
(21, 0x7465737477777777, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 4, 'newdsb6@sco.ro', '', '2a80a51e86bffebc9f7dd26919230163', '20070502184937'),
(22, 0x746573747777777777, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'newdsb7@sco.ro', '', '5247ee3f66615fea3b2a5d5bc8188558', '20070502185354'),
(24, 0x7373773333, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 4, 'newdsb9@sco.ro', '', '5e63429a53d8efce4c5d663e523f4d50', '20070502190356'),
(25, 0x737377333333, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 4, 'newdsb10@sco.ro', '', '3398f59e583769a478d824aedf227a04', '20070502191258'),
(26, 0x74657374657265, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 4, 'testere@sco.ro', '', '64bfd4d3f8ef4711896ddcc5836b119b', '20070507215648');

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_blogs`
-- 

DROP TABLE IF EXISTS `dsb_user_blogs`;
CREATE TABLE `dsb_user_blogs` (
  `blog_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `blog_name` varchar(100) NOT NULL default '',
  `blog_diz` varchar(255) NOT NULL default '',
  `stat_posts` int(4) unsigned NOT NULL default '0',
  `blog_skin` varchar(32) NOT NULL default '',
  `blog_url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`blog_id`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_blogs`
-- 

INSERT INTO `dsb_user_blogs` (`blog_id`, `fk_user_id`, `blog_name`, `blog_diz`, `stat_posts`, `blog_skin`, `blog_url`) VALUES (1, 1, 'My first ever blog', 'What can I break today?', 2, '', ''),
(2, 4, 'Friendy Software', 'Looks good so far!', 0, '', ''),
(3, 10, 'Fluffy''s Blog', 'The life and times of Fluffy', 2, '', ''),
(4, 11, 'Testing Testing the Blog', 'Testing Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing', 4, '', ''),
(5, 7, 'My words of wisdom', '', 3, '', ''),
(6, 19, 'TEST BLOG', 'This is Just a test Blog to see how it all works', 1, '', ''),
(7, 12, 'Yahooo the new look', 'Great to see that the new look is a goer :-)', 1, '', '');

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

INSERT INTO `dsb_user_folders` (`folder_id`, `fk_user_id`, `folder`) VALUES (1, 6, 'Test'),
(2, 11, 'My Sexy Email'),
(3, 10, 'test folder');

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
  KEY `key1` (`fk_user_id`,`fk_folder_id`,`del`),
  KEY `key2` (`is_read`,`fk_user_id`,`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_inbox`
-- 

INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES (45, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(43, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(42, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(40, 1, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(41, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(36, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 1),
(37, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(38, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(39, 1, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(14, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 1),
(15, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(16, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(17, 1, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(18, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(19, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(20, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(21, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(22, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(23, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(50, 1, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(49, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(48, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(47, 1, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(46, 1, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(44, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(35, 1, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 2, 0, 1),
(25, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(26, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(27, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(28, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(29, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(30, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(31, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(32, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(33, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(34, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(51, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(52, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(53, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(54, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(55, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(56, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(57, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(58, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(59, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(60, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(61, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(62, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(63, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(64, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(65, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(66, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(67, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(68, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(69, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(70, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(71, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(72, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(73, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(74, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(75, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(76, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(77, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(78, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(79, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(80, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(81, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(82, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(83, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(84, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(85, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(86, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(87, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(88, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(89, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(90, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(91, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(92, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(93, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(94, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(95, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(96, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(97, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(98, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(99, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(100, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(101, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 1),
(102, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 1),
(103, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 1),
(104, 1, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 1),
(105, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(106, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(107, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(108, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(109, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(110, 0, 7, 1, 'emma', 'frfrfrf', 'asdasd\r\nasd\r\nasd\r\nas\r\nda\r\ndsa\r\nsd', '2007-04-19 10:44:28', 0, 0, 1),
(111, 1, 7, 0, '', 'New comment on your photos', 'emma posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id= 5">Click here</a> to view the comment', '2007-04-19 14:25:46', 2, 0, 1),
(112, 0, 12, 1, 'emma', 'xxx', 'yyy', '2007-04-20 15:46:28', 0, 0, 0),
(113, 0, 12, 1, 'emma', 'emma sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-20 15:46:36', 1, 0, 0),
(114, 0, 11, 0, '', 'New comment on one of your blogs', 'guest posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10">Click here</a> to view the comment', '2007-04-20 15:57:02', 2, 0, 0),
(115, 0, 11, 0, '', 'New comment on one of your blogs', 'guest posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10">Click here</a> to view the comment', '2007-04-20 22:08:44', 2, 0, 0),
(116, 0, 1, 0, '', 'New comment on one of your photos', 'dragon posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=18">Click here</a> to view the comment', '2007-04-24 17:19:32', 2, 0, 0),
(117, 0, 11, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10">Click here</a> to view the comment', '2007-04-24 17:20:12', 2, 0, 0),
(118, 0, 11, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10#comm29">Click here</a> to view the comment', '2007-04-26 18:45:36', 2, 0, 0),
(119, 0, 1, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm2">Click here</a> to view the comment', '2007-04-27 16:20:27', 2, 0, 0),
(120, 0, 1, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm3">Click here</a> to view the comment', '2007-04-27 16:20:47', 2, 0, 0),
(121, 1, 1, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm4">Click here</a> to view the comment', '2007-04-27 16:25:29', 2, 0, 0),
(122, 0, 11, 7, 'dragon', 'Re: johnboy sent you a flirt', '[quote]Aye aye, mate![/quote]', '2007-05-14 10:34:22', 0, 0, 0),
(123, 0, 11, 7, 'dragon', 'dragon sent you a flirt', 'Maybe later', '2007-05-14 14:37:30', 1, 0, 0);

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


-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_networks`
-- 

DROP TABLE IF EXISTS `dsb_user_networks`;
CREATE TABLE `dsb_user_networks` (
  `nconn_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `fk_net_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id_other` int(10) unsigned NOT NULL default '0',
  `nconn_status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`nconn_id`),
  UNIQUE KEY `unique1` (`fk_user_id`,`fk_net_id`,`fk_user_id_other`),
  KEY `index1` (`fk_user_id`,`fk_net_id`,`nconn_status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_networks`
-- 

INSERT INTO `dsb_user_networks` (`nconn_id`, `fk_user_id`, `fk_net_id`, `fk_user_id_other`, `nconn_status`) VALUES (1, 4, 1, 1, 1),
(2, 4, 1, 8, 1),
(3, 1, 1, 7, 1),
(4, 10, 1, 7, 0),
(5, 11, 1, 7, 0),
(6, 10, 3, 10, 1),
(7, 11, 2, 10, 1),
(8, 13, 1, 11, 0),
(9, 11, 3, 13, 1),
(10, 11, 1, 13, 0),
(14, 1, 1, 4, 0),
(17, 1, 3, 12, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_outbox`
-- 

DROP TABLE IF EXISTS `dsb_user_outbox`;
CREATE TABLE `dsb_user_outbox` (
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
-- Dumping data for table `dsb_user_outbox`
-- 

INSERT INTO `dsb_user_outbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (16385, 0, 1, 12, 'a_l_f', 'xxx', 'yyy', '2007-04-20 15:46:28', 0),
(16386, 0, 7, 11, 'johnboy', 'Re: johnboy sent you a flirt', '[quote]Aye aye, mate![/quote]', '2007-05-14 10:34:22', 0);

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
  `allow_rating` tinyint(1) unsigned NOT NULL default '0',
  `caption` varchar(255) NOT NULL default '',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `del` tinyint(1) unsigned NOT NULL default '0',
  `flagged` tinyint(3) unsigned NOT NULL default '0',
  `reject_reason` text NOT NULL,
  `stat_views` int(10) unsigned NOT NULL default '0',
  `stat_votes` int(4) unsigned NOT NULL default '0',
  `stat_votes_total` int(5) unsigned NOT NULL default '0',
  `stat_comments` int(5) unsigned NOT NULL default '0',
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `processed` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`photo_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `is_main` (`is_main`),
  KEY `is_private` (`is_private`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`status`,`del`),
  KEY `flagged` (`flagged`),
  FULLTEXT KEY `caption` (`caption`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_photos`
-- 

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `allow_rating`, `caption`, `status`, `del`, `flagged`, `reject_reason`, `stat_views`, `stat_votes`, `stat_votes_total`, `stat_comments`, `date_posted`, `last_changed`, `processed`) VALUES (1, 4, 'maverick', '7/4_11176746548.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 20, 2, 9, 3, '2007-04-16 18:02:29', '2007-04-16 18:08:52', 0),
(2, 1, 'emma', '4/1_11176750826.jpg', 0, 0, 1, 1, 'ruuuuun, enemies are coming!!', 10, 0, 0, '<html>\r\n    <head>\r\n        <title>Your photo has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <p>Unfortunately we are unable to publish your photo on the site yet because</p>\r\n        <p>&nbsp;</p>\r\n        <p>Regards,<br />\r\n        Nexus / R i t m o / friendy admin</p>\r\n    </body>\r\n</html>', 15, 0, 0, 0, '2007-04-16 19:13:53', '2007-05-20 18:39:58', 0),
(3, 1, 'emma', '2/1_21176750826.jpg', 0, 0, 1, 1, 'daddy''s girl', 15, 0, 0, '', 8, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-21 13:37:55', 0),
(4, 1, 'emma', '1/1_31176750826.jpg', 0, 0, 1, 1, 'hey, look, I can walk...sort of :)', 15, 0, 0, '', 6, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-21 13:47:02', 0),
(5, 7, 'dragon', '0/7_11176751977.jpg', 1, 0, 1, 1, 'xxx', 10, 0, 0, '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Nexus / R i t m o / friendy</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 24, 3, 15, 2, '2007-04-16 19:32:59', '2007-05-14 09:56:03', 0),
(6, 10, 'strawberries', '3/10_11176756425.jpg', 1, 0, 1, 1, 'Fluffy, she who must be obeyed', 15, 0, 0, '', 9, 0, 0, 1, '2007-04-16 20:47:07', '2007-04-16 20:48:32', 0),
(7, 10, 'strawberries', '3/10_21176756425.jpg', 0, 0, 1, 1, 'Fluffy loves getting her picture taken', 15, 0, 0, '', 6, 0, 0, 0, '2007-04-16 20:47:07', '2007-04-16 20:48:32', 0),
(8, 11, 'johnboy', '0/11_11176758735.jpg', 1, 1, 1, 1, '', 15, 0, 0, '', 13, 1, 5, 0, '2007-04-16 21:25:35', '2007-04-17 12:27:01', 0),
(9, 13, 'johnboy2', '6/13_11176787947.jpg', 1, 0, 1, 1, 'Out the front of my house', 15, 0, 0, '', 6, 1, 4, 2, '2007-04-17 05:32:29', '2007-04-17 05:33:50', 0),
(10, 13, 'johnboy2', '8/13_21176787947.jpg', 0, 0, 1, 1, 'A poor little bird freezing its butt off.', 15, 0, 0, '', 1, 0, 0, 0, '2007-04-17 05:32:29', '2007-04-17 05:33:50', 0),
(11, 8, 'angkasa', '2/8_11176790979.jpg', 1, 0, 1, 1, 'fgfgfgfgf', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 06:23:02', '2007-05-16 14:34:13', 0),
(12, 15, 'blackwater', '1/15_11176795306.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 07:35:07', '2007-04-17 07:36:26', 0),
(13, 12, 'a_l_f', '9/12_11176812224.jpg', 1, 0, 1, 1, 'Here Kitty Kitty', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 12:17:04', '2007-04-17 12:17:31', 0),
(14, 1, 'emma', '2/1_11176990562.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 13:49:25', '2007-04-19 13:50:02', 0),
(15, 1, 'emma', '7/1_11176990694.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 13:51:39', '2007-04-19 13:51:43', 0),
(16, 1, 'emma', '0/1_11176991248.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 14:00:51', '2007-04-19 14:03:04', 0),
(17, 1, 'emma', '3/1_21176991248.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 14:00:51', '2007-04-19 14:03:19', 0),
(18, 1, 'emma', '8/1_21176991707.jpg', 1, 0, 1, 0, 'auuu, my eye, my eyeee!', 15, 0, 0, '', 39, 0, 0, 6, '2007-04-19 14:08:29', '2007-04-25 19:54:58', 0),
(19, 1, 'emma', '8/1_41176991707.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 14:08:29', '2007-04-19 14:08:38', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_profiles`
-- 

DROP TABLE IF EXISTS `dsb_user_profiles`;
CREATE TABLE `dsb_user_profiles` (
  `profile_id` int(10) unsigned NOT NULL auto_increment,
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL default '0',
  `del` tinyint(1) unsigned NOT NULL default '0',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `reject_reason` text NOT NULL,
  `_user` varchar(32) NOT NULL default '',
  `_photo` varchar(128) NOT NULL default '',
  `longitude` float(20,10) NOT NULL default '0.0000000000',
  `latitude` float(20,10) NOT NULL default '0.0000000000',
  `score` int(5) unsigned NOT NULL default '0',
  `f1` text NOT NULL,
  `f2` int(5) NOT NULL default '0',
  `f3` text NOT NULL,
  `f4` int(5) NOT NULL default '0',
  `f5` date NOT NULL default '0000-00-00',
  `f6_country` int(3) NOT NULL default '0',
  `f6_state` int(10) NOT NULL default '0',
  `f6_city` int(10) NOT NULL default '0',
  `f6_zip` varchar(10) NOT NULL default '',
  `f7` int(5) NOT NULL default '0',
  `f8` int(5) NOT NULL default '0',
  `f9` int(5) NOT NULL default '0',
  `f10` int(5) NOT NULL default '0',
  `f11` int(5) NOT NULL default '0',
  `f12` int(5) NOT NULL default '0',
  `f13` int(5) NOT NULL default '0',
  `f14` int(5) NOT NULL default '0',
  `f15` text NOT NULL,
  PRIMARY KEY  (`profile_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `_user` (`_user`),
  KEY `score` (`score`),
  KEY `longitude` (`longitude`,`latitude`),
  KEY `key1` (`status`,`del`),
  KEY `_photo` (`_photo`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_profiles`
-- 

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `del`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6_country`, `f6_state`, `f6_city`, `f6_zip`, `f7`, `f8`, `f9`, `f10`, `f11`, `f12`, `f13`, `f14`, `f15`) VALUES (1, 1, 15, 0, '2007-04-19 14:08:29', '2007-04-16 14:42:06', '', 'emma', '', 0.0000000000, 0.0000000000, 28, '', 2, '|1|', 1, '1989-06-05', 165, 0, 0, '', 3, 3, 2, 3, 1, 1, 1, 1, ''),
(2, 2, 15, 0, '2007-04-16 12:42:01', '2007-04-16 17:09:23', '', 'keith', '', 0.0000000000, 0.0000000000, 0, 'Testing', 1, '|2|', 1, '1976-05-12', 217, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, '|1|4|7|'),
(3, 3, 15, 0, '2007-04-16 17:29:10', '2007-04-16 17:24:38', '', 'shon205', '', -81.6286010742, 41.4509010315, 0, '', 1, '|2|', 1, '1971-01-06', 218, 41, 1620, '44105', 1, 1, 1, 1, 1, 1, 1, 1, ''),
(4, 4, 15, 0, '2007-04-16 12:41:49', '2007-04-16 17:46:34', '', 'maverick', '', 0.0000000000, 0.0000000000, 4, '', 1, '|2|', 33, '0000-00-00', 218, 0, 0, '86314', 3, 3, 2, 3, 1, 1, 2, 2, '|1|'),
(5, 5, 15, 0, '2007-04-16 12:41:45', '2007-04-16 18:26:47', '', '100457', '', 0.0000000000, 0.0000000000, 1, 'cool me', 1, '|2|', 32, '1978-04-07', 218, 0, 0, '49504', 5, 1, 3, 1, 1, 1, 1, 1, '|1|7|'),
(6, 6, 15, 0, '2007-04-16 12:41:53', '2007-04-16 18:50:27', '', 'cocacola', '', 0.0000000000, 0.0000000000, 5, 'Im cool', 1, '|2|', 38, '1969-01-01', 186, 0, 0, '07631', 3, 1, 1, 1, 1, 1, 1, 1, '|1|'),
(7, 7, 15, 0, '2007-05-10 16:00:12', '2007-04-16 19:26:41', '', 'dragon', '', 0.0000000000, 0.0000000000, 100, 'oops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this should have beoops, this should have been a textarea, not a textfield. Easy to fix!asdasdoops, this shou0987654321', 1, '|2|', 58, '1976-11-01', 165, 0, 0, '', 3, 5, 2, 5, 1, 2, 4, 1, '|2|'),
(8, 8, 10, 0, '2007-05-19 15:31:49', '2007-04-16 19:54:09', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link rel="stylesheet" type="text/css" media="screen" href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest to other members.</p>\r\n        <p>Please update your profile with relevant information.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'angkasa', '2/8_11176790979.jpg', 0.0000000000, 0.0000000000, 5, 'a[b]sdas[/b]dasd[quote]hahah[/quote]', 1, '|2|', 47, '1980-09-18', 181, 0, 0, '', 2, 3, 1, 2, 3, 2, 5, 3, '|1|'),
(9, 9, 15, 0, '2007-04-16 19:56:29', '2007-04-16 19:55:41', '', 'raneglo', '', 0.0000000000, 0.0000000000, 0, 'I am me!', 1, '|1|', 34, '1961-07-29', 218, 0, 0, '77584', 3, 3, 6, 5, 1, 2, 4, 1, '|4|5|'),
(10, 10, 15, 0, '2007-04-16 20:48:32', '2007-04-16 20:26:23', '', 'strawberries', '3/10_11176756425.jpg', 0.0000000000, 0.0000000000, 2, '', 1, '|2|', 38, '1964-12-31', 217, 0, 0, '', 3, 3, 2, 5, 1, 2, 3, 1, '|1|'),
(11, 11, 15, 0, '2007-04-17 04:54:56', '2007-04-16 21:10:00', '', 'johnboy', '0/11_11176758735.jpg', 0.0000000000, 0.0000000000, 10, 'Hello everyone, at last we can see what the new DSB is about, I LIKE IT, very clean.', 1, '|2|', 39, '1958-01-17', 145, 0, 0, '', 3, 1, 2, 9, 3, 2, 2, 2, '|7|'),
(12, 12, 15, 0, '2007-04-17 12:17:31', '2007-04-17 00:26:40', '', 'a_l_f', '9/12_11176812224.jpg', 0.0000000000, 0.0000000000, 0, 'Computer Junkie that loves kung fu and motor bikes', 1, '|2|', 29, '1959-07-28', 11, 0, 0, '', 3, 3, 6, 2, 3, 2, 3, 1, '|3|'),
(13, 13, 15, 0, '2007-04-17 12:32:53', '2007-04-17 05:13:27', '', 'johnboy2', '6/13_11176787947.jpg', 0.0000000000, 0.0000000000, 5, 'Testing this out', 1, '|2|', 39, '1958-01-17', 145, 0, 0, '', 3, 1, 2, 9, 3, 2, 2, 2, '|7|'),
(14, 14, 15, 0, '2007-04-17 05:48:43', '2007-04-17 05:48:17', '', 'tpandtp', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 1, '0000-00-00', 11, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, ''),
(15, 15, 15, 0, '2007-04-17 07:36:26', '2007-04-17 07:27:52', '', 'blackwater', '1/15_11176795306.jpg', 0.0000000000, 0.0000000000, 0, 'şşşş ğğğğğ ııııı', 1, '|2|', 51, '1977-04-28', 210, 0, 0, '', 3, 3, 1, 2, 2, 2, 3, 1, '|1|7|'),
(16, 16, 15, 0, '2007-04-17 10:36:09', '2007-04-17 10:35:07', '', 'tester', '', 0.0000000000, 0.0000000000, 0, 'about-me...looking at this softa\\ware', 1, '|2|', 50, '1969-11-30', 186, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, '|4|6|7|'),
(17, 17, 15, 0, '2007-05-02 15:39:49', '2007-05-02 15:39:12', '', 'woman', '', 0.0000000000, 0.0000000000, 0, '', 2, '|1|2|', 1, '0000-00-00', 165, 0, 0, '', 1, 1, 1, 1, 1, 1, 1, 1, ''),
(18, 18, 15, 0, '2007-05-02 15:46:19', '2007-05-02 15:46:19', '', 'testw', '', 0.0000000000, 0.0000000000, 0, '', 2, '|1|2|', 0, '0000-00-00', 165, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(19, 19, 15, 0, '2007-05-02 15:47:22', '2007-05-02 15:47:22', '', 'testww', '', 0.0000000000, 0.0000000000, 0, '', 2, '|1|2|', 0, '0000-00-00', 165, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(20, 20, 15, 0, '2007-05-02 15:49:01', '2007-05-02 15:49:01', '', 'testwww', '', 0.0000000000, 0.0000000000, 0, '', 2, '|1|2|', 0, '0000-00-00', 165, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(21, 21, 15, 0, '2007-05-02 15:49:37', '2007-05-02 15:49:37', '', 'testwwww', '', 0.0000000000, 0.0000000000, 0, '', 2, '|1|2|', 0, '0000-00-00', 165, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(22, 22, 15, 0, '2007-05-02 15:53:56', '2007-05-02 15:53:56', '', 'testwwwww', '', 0.0000000000, 0.0000000000, 0, '', 2, '|1|2|', 0, '0000-00-00', 218, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(24, 24, 15, 0, '2007-05-02 16:03:56', '2007-05-02 16:03:56', '', 'ssw33', '', 0.0000000000, 0.0000000000, 0, '', 1, '|1|2|', 0, '0000-00-00', 218, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(25, 25, 15, 0, '2007-05-02 16:12:58', '2007-05-02 16:12:58', '', 'ssw333', '', 0.0000000000, 0.0000000000, 0, '', 1, '|1|2|', 0, '0000-00-00', 218, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(26, 26, 15, 0, '2007-05-07 18:56:48', '2007-05-07 18:56:48', '', 'testere', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 0, '1988-02-02', 218, 0, 0, '12345', 0, 0, 0, 0, 0, 0, 0, 0, '');

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
  `fk_module_code` varchar(32) binary NOT NULL default '',
  PRIMARY KEY  (`config_id`),
  UNIQUE KEY `thekey` (`fk_user_id`,`config_option`,`fk_module_code`),
  KEY `fk_module_code` (`fk_module_code`)
) TYPE=MyISAM COMMENT='0-n/a,1-chkbox,2-tf,3-ta';

-- 
-- Dumping data for table `dsb_user_settings2`
-- 

INSERT INTO `dsb_user_settings2` (`config_id`, `fk_user_id`, `config_option`, `config_value`, `fk_module_code`) VALUES (1, 10, 0x646174655f666f726d6174, '%m/%d/%Y', 0x636f7265),
(2, 10, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 0x636f7265),
(3, 10, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(4, 10, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(5, 10, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(6, 10, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(7, 11, 0x646174655f666f726d6174, '%d/%m/%Y', 0x636f7265),
(8, 11, 0x6461746574696d655f666f726d6174, '%d/%m/%Y %r', 0x636f7265),
(9, 11, 0x73656e645f616c6572745f696e74657276616c, '1', 0x6465665f757365725f7072656673),
(10, 11, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(11, 11, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(12, 11, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(13, 12, 0x646174655f666f726d6174, '%m/%d/%Y', 0x636f7265),
(14, 12, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 0x636f7265),
(15, 12, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(16, 12, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(17, 12, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(18, 12, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(51, 1, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(50, 1, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 0x636f7265),
(49, 1, 0x646174655f666f726d6174, '%m/%d/%Y', 0x636f7265),
(52, 1, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(53, 1, 0x726174655f6d795f70686f746f73, '0', 0x6465665f757365725f7072656673),
(54, 1, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(37, 7, '', '', ''),
(38, 7, 0x6e6f746966795f6d65, '1', ''),
(39, 1, '', '', ''),
(40, 1, 0x70726f66696c655f636f6d6d656e7473, '1', ''),
(55, 1, 0x70726f66696c655f636f6d6d656e7473, '1', 0x6465665f757365725f7072656673),
(56, 1, 0x616c6c6f775f6e657773, '1', 0x6465665f757365725f7072656673),
(57, 7, 0x70726f66696c655f636f6d6d656e7473, '1', ''),
(58, 11, '', '', ''),
(59, 11, 0x70726f66696c655f636f6d6d656e7473, '1', '');

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

INSERT INTO `dsb_user_spambox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 1, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0),
(2, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_stats`
-- 

DROP TABLE IF EXISTS `dsb_user_stats`;
CREATE TABLE `dsb_user_stats` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `stat` varchar(50) NOT NULL default '',
  `value` int(10) NOT NULL default '0',
  UNIQUE KEY `key1` (`fk_user_id`,`stat`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_stats`
-- 

INSERT INTO `dsb_user_stats` (`fk_user_id`, `stat`, `value`) VALUES (2, 'total_photos', 1),
(1, 'total_photos', 21),
(1, 'flirts_sent', 2),
(1, 'blog_posts', 3),
(3, 'total_photos', 6),
(2, 'blog_posts', 1),
(4, 'total_photos', 3),
(3, 'pviews', 8),
(2, 'pviews', 11),
(15, 'pviews', 1),
(12, 'pviews', 24),
(10, 'pviews', 1),
(13, 'pviews', 1),
(1, 'total_messages', -4),
(4, 'pviews', 18),
(7, 'pviews', 22),
(1, 'pviews', 35),
(8, 'pviews', 1),
(1, 'mess_sent', 2),
(11, 'pviews', 4),
(7, 'comments_made', 7),
(1, 'profile_comments', 3),
(7, 'mess_sent', 1),
(7, 'flirts_sent', 1),
(7, 'profile_comments', 3),
(11, 'blog_posts', 4);