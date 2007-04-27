-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 27, 2007 at 08:16 PM
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

INSERT INTO `dsb_admin_accounts` (`admin_id`, `user`, `pass`, `name`, `status`, `dept_id`, `email`) VALUES (1, 0x61646d696e, 0x3964323763666564386236633738373833616162623534643264393464393331, 'Dan Caragea', 15, 4, 'dan@sco.ro');

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
  `fk_post_id` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `comment` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_post_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_blog_comments`
-- 

INSERT INTO `dsb_blog_comments` (`comment_id`, `fk_post_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 1, 10, 'strawberries', 'a reply.\r\n\r\nHi Emma!', '2007-04-16 21:13:30', '2007-04-16 21:13:30', 15),
(2, 2, 10, 'strawberries', 'welcome to the hairball''s abode.', '2007-04-17 21:46:30', '2007-04-17 21:46:30', 15),
(3, 3, 10, 'strawberries', 'It looks excellent, Dan.  I love it.  It looks so clean and covers all the bases. \r\n \r\nI''ll be keen to see the rss look.....for me, it will be important to have more content on the index page.  \r\n\r\nI was messing around with the css stylesheet tonight in my firefox browser.  This is a great script.  Great work.', '2007-04-17 21:51:51', '2007-04-17 21:51:51', 15),
(4, 4, 10, 'strawberries', '''woof woof'' said the big bad wolf', '2007-04-18 14:03:21', '2007-04-18 14:03:21', 15),
(5, 3, 7, 'dragon', 'after 3 or 4 versions of the skin and lots of fights between the designer and us - the developers, it better be good. We really tried to make everything easy to customize: the php code, the html, css...', '2007-04-18 21:31:08', '2007-04-18 21:31:08', 15),
(6, 3, 12, 'a_l_f', 'Kewl  \r\n        so now my filrts can have\r\n send \r\n(1) i have the weekend free wanna catch-up\r\n(2) im intrested \r\n(3) lets meet for coffee \r\n\r\nReply \r\n\r\n1. no thanks\r\n2. great im free also\r\n3. Sure lets meet \r\n\r\nLoving it thus far and it can only get better', '2007-04-19 05:13:36', '2007-04-19 05:13:36', 15),
(7, 3, 10, 'strawberries', 'looking good, Dan, looking good!  And I see you''ve done a fair bit of work yesterday.\r\n\r\na_l_f, thanks for the flirt message.  I was alerted to your flirt via email thi morning.  Though when I clicked on the url link in the email, the webpage came up as an error....404 not found  index.php.\r\n\r\nBut when i went to the friendy site and signed in, i could go to my inbox and see your flirt message.  I guess the problem was to do with me not being signed in.\r\n\r\nJust a wee bug.\r\n', '2007-04-19 07:38:12', '2007-04-19 07:38:12', 15),
(8, 5, 10, 'strawberries', 'excellent work, Dan.\r\n\r\nIt''s all coming together nicely.  It''s starting to snowball.  \r\n\r\nBTW You can see a small error that i had this morning (see your other blog thread for details).', '2007-04-19 07:40:39', '2007-04-19 07:40:39', 15),
(9, 8, 7, 'dragon', 'howdy kid?', '2007-04-19 14:49:34', '2007-04-19 14:49:34', 15),
(11, 7, 10, 'strawberries', 'I wonder what the design resource kit will contain.\r\n\r\n', '2007-04-19 14:53:07', '2007-04-19 14:53:07', 15),
(12, 6, 10, 'strawberries', 'I hate spammers and the horse they rode in on.', '2007-04-19 14:54:29', '2007-04-19 14:54:29', 15),
(27, 10, 1, 'emma', 'unu ''     doi\n\nLast edited by emma on 2007-04-24 14:08:43 GMT', '2007-04-24 13:35:12', '2007-04-24 14:08:43', 15),
(15, 9, 10, 'strawberries', 'excellentae!\r\n\r\nI replied to  a_l_f''s test flirt yesterday morning, but i had an error page.  I''ll resend the message now.\r\n\r\nIt''s great that we now that get notified via email when any posts are made re our pictures or blogs.', '2007-04-19 23:43:46', '2007-04-19 23:43:46', 15),
(16, 2, 10, 'strawberries', 'test message', '2007-04-20 00:10:19', '2007-04-20 00:10:19', 15),
(17, 9, 13, 'johnboy2', 'Wow this bits cool, only just discovered it today.:)', '2007-04-20 01:52:19', '2007-04-20 01:52:19', 15),
(18, 9, 13, 'johnboy2', 'Can a user edit or delete these comments they leave?', '2007-04-20 01:53:10', '2007-04-20 01:53:10', 15),
(19, 9, 10, 'strawberries', 'no ability to edit that i can see, johnboy\r\n\r\nwonder if possible to see the time/date of posted messages.\r\n', '2007-04-20 02:50:11', '2007-04-20 02:50:11', 15),
(20, 3, 12, 'a_l_f', 'LOL strawberries\r\n               yes just a wee bug or simple \r\njust not yet ready togo As the crew did say they would work on it each day :-)\r\nWhat would be great is a few simleys for the blog tooo :-)', '2007-04-20 03:35:55', '2007-04-20 03:35:55', 15),
(21, 10, 7, 'dragon', 'Think of this like user stories with bells and whistles. Instead of having some predefined topics, the user creates his/her topics and keeps talking. Plus others can comment on their posts. In this application, they''re supposed to replace the user stories.\n\nLast edited by dragon on 2007-04-27 17:14:57 GMT', '2007-04-20 12:58:50', '2007-04-27 17:14:57', 15),
(22, 10, 0, 'guest', 'whoa', '2007-04-20 15:57:02', '2007-04-20 15:57:02', 15),
(23, 10, 0, 'guest', 'rrrr', '2007-04-20 22:07:46', '2007-04-20 22:07:46', 15),
(24, 10, 0, 'guest', 'tttt', '2007-04-20 22:08:44', '2007-04-20 22:08:44', 15),
(25, 10, 0, 'guest', 'gege', '2007-04-21 13:20:27', '2007-04-21 13:20:27', 15),
(26, 8, 1, 'emma', 'hi', '2007-04-21 19:02:07', '2007-04-21 19:02:07', 15),
(28, 10, 7, 'dragon', ':)', '2007-04-24 17:20:12', '2007-04-24 17:20:12', 15),
(29, 10, 7, 'dragon', 'test #######you\n\nLast edited by dragon on 2007-04-26 18:46:08 GMT', '2007-04-26 18:45:36', '2007-04-26 18:46:08', 15);

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
  PRIMARY KEY  (`post_id`),
  KEY `is_public` (`is_public`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_blog_id`,`is_public`,`status`),
  KEY `key2` (`fk_user_id`,`is_public`,`status`),
  FULLTEXT KEY `text_key` (`title`,`post_content`)
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `dsb_blog_posts`
-- 

INSERT INTO `dsb_blog_posts` (`post_id`, `date_posted`, `fk_user_id`, `_user`, `fk_blog_id`, `is_public`, `title`, `post_content`, `allow_comments`, `status`, `post_url`, `stat_views`, `stat_comments`, `last_changed`, `reject_reason`) VALUES (1, '2007-04-16 19:20:25', 1, 'emma', 1, 1, 'The Bicentennial Man', 'by Isaac Asimov\n\n[b]The Three Laws of Robotics[/b]\n.\n.\n.\n[quote]A robot may not injure a human being, or, through inaction, allow a human being to come to harm.[/quote]\n\n[quote]A robot must obey the orders given it by human beings except where such orders would conflict with the First Law.[/quote]\n\n[quote]A robot must protect its own existence as long as such protection does not conflict with the First or Second Law.[/quote]\n\nAndrew Martin said, "[u]Thank you[/u]," and took the seat offered him. He didn''t look driven to the last resort, but he had been.\n\nHe didn''t, actually, look anything, for there was a smooth blankness, to his face, except for the sadness one imagined one saw in his eyes. His hair was smooth, light brown, rather fine; and he had no facial hair. He looked freshly and cleanly shaved. His clothes were distinctly old-fashioned, but neat, and predominantly a velvety red-purple in color.\n\nFacing him from behind the desk was the surgeon The nameplate on the desk included a fully identifying series of letters and numbers which Andrew didn''t bother with. To call him Doctor would be quite enough\n\n"When can the operation be carried through, Doctor?" he asked.\n\nSoftly, with that certain inalienable note of respect that a robot always used to a human being, the surgeon said, "I am not certain, sir, that I understand how or upon whom such an operation could be performed."\n\nThere might have been a look of respectful intransigence on the surgeon''s face, if a robot of his sort, in lightly bronzed stainless steel, could have such an expression-or any expression.\n\nAndrew Martin studied the robot''s right hand, his cutting hand, as it lay motionless on the desk. The fingers were long and were shaped into artistically metallic, looping curves so -graceful and appropriate that one could imagine a scalpel fitting them and becoming, temporarily, one piece with them. There would be no hesitation in his work, no stumbling, no quivering, no mistakes. That confidence came with specialization, of course, a specialization so fiercely desired by humanity that few robots were, any longer, independently brained. A surgeon, of course, would have to be. But this one, though brained, was so limited in his capacity that he did not recognize Andrew, had probably never heard of him .\n\n[quote]Have you ever thought you would like to be a man?[/quote] Andrew asked.\n\nThe surgeon hesitated a moment, as though the question fitted nowhere in his allotted positronic pathways. "But I am a robot, sir."\n\n[quote]Would it be better to be a man?[/quote]', 1, 15, '', 0, 1, '2007-04-16 19:24:35', ''),
(2, '2007-04-16 20:50:21', 10, 'strawberries', 3, 1, 'Fluffy say''s ''Hi''', 'Hi everyone.  Welcome to Fluffy''s blog.', 1, 15, '', 0, 2, '2007-04-18 01:05:21', ''),
(3, '2007-04-17 12:08:28', 7, 'dragon', 5, 1, 'Now what?', 'Ok, so now that a basic preview is up for you to see and test, we''ll update it daily with whatver we''ll be working on. So stay tuned for more news.\nThere are some things to fix with blogs, we need to finish the friendship connections feature (right now you can only add another member to your favorites network and request to be a friend of another member).\n\nWe need to also finish the flirts...\nAs many of you have requested, you want to be able to reply with a flirt to a flirt and this is now possible.\n\nAs you might have seen, the flirts can include both text and images and even sounds if you want. It''s up to the admin''s imagination to create some creative flirts.\n\nAnother thing that needs to be finished is the news system. There are 2 parts here:\nYou can include news read by a rss reader from any published rss feed and you will also have site news - published by admin for the site members. The news will appear on the home page (the page after login) by default but you can put it anywhere you want in your site, even on the front page.\n', 1, 15, '', 12, 5, '2007-04-17 12:19:12', ''),
(4, '2007-04-17 21:45:04', 10, 'strawberries', 3, 1, 'Fluffy, fluffy, fluffy', 'The greatest cat in the land.', 1, 15, '', 0, 1, '2007-04-17 21:45:04', ''),
(5, '2007-04-18 21:26:37', 7, 'dragon', 5, 1, 'Today''s update', '- fixed a bug with account confirmation\n- finished the connection feature\n- finished the friendship requests pages\n- added the list of friends in the profile page - left menu\n- fixed some bugs with edit/delete links in my_folders/my_filters\n- finished the member block/unblock feature\n- fixed 2 bugs with message counting in the left menus of mail related pages\n- fixed some bugs in the crons\n- added the mail_crlf option in site options to select between \\r\\n and \\n line endings in emails\n- fixed some js errors', 1, 15, '', 0, 1, '2007-04-18 21:26:37', ''),
(6, '2007-04-18 23:30:54', 19, 'pkusa', 6, 1, 'About MyOrg, Inc', 'Why Choose Us?\nWhy would someone choose one provider over another?  When it comes to Internet Connectivity, how well does one really know any provider? \n\nRegardless of how many server racks are maintained, or how low the monthly price is for hosting your site, we believe that ultimately, our customers choose us because they have gotten to know us.  They know that we will help them connect to the maze that is the Internet and, should they get lost, they know that we will be there to help them find their way.\n\nWe are dedicated to ensuring that whatever service you choose with us, should it be: Web Hosting Solutions, Domain Names, SSL, Co-Location or Web Development we always are courteous, knowledgeable and quick to respond.\n\nIn order to stay ahead of the competition, MyOrg, Inc. and its family of companies have embarked on providing our consumer and corporate customers with true quality-of-service initiatives, focused on making our customers'' Internet experience the best it can be.\n[b][/b][b][/b][b][/b][b][/b][quote][/quote]', 1, 15, '', 0, 1, '2007-04-18 23:30:54', ''),
(7, '2007-04-19 05:01:39', 12, 'a_l_f', 7, 1, 'A design resource kit', 'I look forward to the design resource kit as i cannot leave things alone :-)\ni wonder how long it will take me to crash the new install when its released :-0', 1, 15, '', 0, 1, '2007-04-19 05:01:39', ''),
(8, '2007-04-19 09:57:59', 1, 'emma', 1, 1, 'Testing blogs', 'Hello\n\n[quote]everybody[/quote]\n\n[b]how[/b]\n\n[u]are[/u]\n\n[url=http://www.datemill.com/friendy/profile.php?user=emma]you[/url]?', 1, 15, '', 0, 5, '2007-04-19 09:57:59', ''),
(9, '2007-04-19 21:40:05', 7, 'dragon', 5, 1, 'Today''s update take 2', 'Ok, another update today - we focused on bugs and overall stability but a couple of features were added too. :)\n- You should be able to send (and receive) messages, flirts, etc.\n- You will receive new message email notifications when you get a new message (if you said you want to receive notifs in your settings)\n- You will also receive message and email notifications when a new comment is made on one of your pictures or blogs.\n- The one and only cron job is active on the demo site.', 1, 15, '', 2, 4, '2007-04-24 17:24:27', ''),
(10, '2007-04-20 02:01:23', 11, 'johnboy', 4, 1, 'Testing this out', 'I''m not very familiar about blogs, so I have no idea what Im doing here, not even sure if this will post in the main blog area. Are blogs just another type of FORUMS?', 1, 15, '', 70, 7, '2007-04-20 02:01:23', '');

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

INSERT INTO `dsb_feed_cache` (`module_code`, `feed_xml`, `update_time`) VALUES (0x6f7369676e616c5f66656564, '<?xml version="1.0" encoding="UTF-8"?>\r\n<?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/rss2full.xsl" type="text/xsl" media="screen"?><?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/itemcontent.css" type="text/css" media="screen"?><rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">\r\n<channel>\r\n	<title>Original Signal - Transmitting Tech</title>\r\n	<link>http://tech.originalsignal.com</link>\r\n	<description>Orginal Signal aggregates the 15 most popular technology sites. The main purpose of the site is to provide \r\na quick glance on what''s happening without using your desktop/web RSS reader. New headlines (since your \r\nlast cookied visit) come in pretty orange, visited ones are grey. All credits go to the authors of these weblogs. \r\nWithout their hard work Original Signal would not exist. Original Signal was inspired by Popurls and the Web 2.0 Workgroup.</description>\r\n	<pubDate>Sat, 21 Apr 2007 11:48:50 CEST</pubDate>\r\n	<language>en</language>\r\n	\r\n	  <atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" href="http://feeds.feedburner.com/OriginalSignal/tech" type="application/rss+xml" /><item>\r\n  <title>DD-WRT: Sliding down the slippery slope... (Give ''em hell, Digg users!)</title>\r\n  <link>http://tech.originalsignal.com/article/47044/dd-wrt-sliding-down-the-slippery-slope-give-em-hell-digg-users.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 11:38:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47044/dd-wrt-sliding-down-the-slippery-slope-give-em-hell-digg-users.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  DD-WRT, the firmware for the Linksys WRT54G series of routers is based on Linux.  Despite being based on code licensed under the GPL, the coordinator of the project is claiming he is the primary author and is now currently charging for licenses.  More info here: http://xwrt.blogspot.com/2007/02/dd-wrt-continues-to-exploit-free-open.html  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Microsoft is Sued for Patent Violation over .NET</title>\r\n  <link>http://tech.originalsignal.com/article/47043/microsoft-is-sued-for-patent-violation-over-net.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 11:38:02 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47043/microsoft-is-sued-for-patent-violation-over-net.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  randomErr writes "As reported by Info World, Microsoft was issued a cease and desist order on February 7 of this year by Vertical Computer Systems. The order was for patent infringement by the current implementations of the .NET framework. Both the .NET framework and Vertical Computer Systems'' SiteFlash use XML to create component-based structures that are used to build and operate web sites. Vertical Computer Systems is requesting a full jury trial. If successful fought .NET technology implementations may completely change as we know it and Microsoft would probably have to pay out a hefty sum."Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Why Good Looking Geeks Don''t Get Girls</title>\r\n  <link>http://tech.originalsignal.com/article/47042/why-good-looking-geeks-dont-get-girls.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 11:08:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47042/why-good-looking-geeks-dont-get-girls.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  It all comes down to how one carries himself or herself for that matter.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Weekly Perl 6 mailing list summary for 01-07 April, 2007</title>\r\n  <link>http://tech.originalsignal.com/article/47041/weekly-perl-6-mailing-list-summary-for-01-07-april-2007.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 10:08:33 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47041/weekly-perl-6-mailing-list-summary-for-01-07-april-2007.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  This week on the Perl 6 mailing lists&#8220;developers shouldn&#8217;t live in fear of $^O&#8221;&#8211; Jerry Gay, in &#8216;Use of English pragma&#8217;LanguageSecond Perl 6 Microgrant - Phil Crow on Java to Perl 6 declaration converterLeon Brocard announced that the second Perl 6 microgrant had been awardedto Phil Crow for his proposal to convert Java declarations to Perl 6.More information about his proposal can be found in the grant application text. Details aboutthe microgrant application procedureare available for those interested in submitting a proposal. typoJonathan Lang submitted a patch which corrects a typo in S03. Range SemanticsJonathan Lang suggested a change to S03 which was inspired by the newarray indexing syntax from S09.  This would make Ranges in an arrayindex a natural outgrowth of the standard Range semantics. Negation metaoperatorJonathan Lang had a proposal to generalize the negated relationaloperators to apply to any infix operator which returns a boolean.Larry Wall rejected the suggestion because the relationals Jonathannamed are typed to return Any, not Bool.Parrot Porters Make disassemble useful. Kevin Tew created ticket  with a patch and requestedfeedback&#8230;back in July of 2006.  Paul Cochrane applied it as r17922on April 1st.: Failures in 5 tests during ''make test''; partially patched James Keenan created ticket  to report errors on5 different tests.  Later he closed the ticket because the failingtests were identified as part of ongoing work and the tests were nolonger failing. Run Parrot under Coverity Prevent Paul Cochrane wanted to get Coverity to run Prevent on Parrot.This was ticket .More recently, Paul reported that he&#8217;d received a reply stating thatParrot could certainly be added, but that no date estimate was availableat the moment.Syntax for Constructing new Objects (and classes?)This thread began with Klaas-Jan Stol asking about the syntax forconstructing new objects because he thought that it was going tobe changed.  Allison Randal replied with examples of the two proposalsfor the new syntax.  This led Alek Storm to ask if new() would bea vtable method or a PCCMETHOD.  Jonathan Worthington confirmedthat it is a PCCMETHOD.  Allison elaborated that it is a class method.More recently, Alek replied that he thought that new should be keptan opcode, and new() should be a vtable method which is called frominside the new opcode.  Jonathan Worthington was confused by Alek&#8217;sproposal.  He suggested making BaseClass implement all the vtablevariants of a method, and Class can inherit from it and provide a saneinterface.  Alek thought that Jonathan&#8217;s suggestion was similar toone he had made in  but that Jonathan&#8217;s explanationwas superior.  Allison also added her thoughts to this subthread.In the main thread, Jonathan proposed deprecating the old syntaxin the next release and removing support for it in the following release.Allison agreed to marking it as deprecated now.  Joshua Isom wantedthe dot syntax to still work so that tests wouldn&#8217;t need to be rewritten.Allison explained that there were several reasons to remove constantsfor types and the class registry.compilers/pirc continued&#8230;Klaas-Jan Stol added a vtable to the parser in compilers/pirc.He proposed looking at the bcg project to see if it would fit inwith compilers/pirc.  Allison Randal thought it was worth exploring. r17907 - trunk/docs/pdds/draftA commit by Allison Randal integrated some questions and commentsinto the Objects PDD.  Jonathan Worthington had a few comments,such as noting that resolve is sugar rather than something new,and that comments about offsets should go away.  Allison notedthat resolve is the preferred way of dealing with conflictresolution, and removed references to offsets. Extensive failures in t/compilers/imcc/imcpasm/*.t and t/pmc/sub.t James Keenan posted some failures in make test in ticket.  Paul Cochrane reported that he was unable toreplicate the failures with r17914 and asked James to check hisrevision.  James looked into it further and concluded that the nameof the sandbox was affecting the test results.  He wondered if anyonecould explain why that was happening. r17921 - trunk/docs/pddsA commit by Paul Cochrane noted that Perl code with __END__or __DATA__ blocks should not get an emacs or vim coda.  Shawn MMoore suggested putting the coda at the top of the file, but Paulexplained that this had been attempted but proved to be a poor solutionfor several reasons. Work out how to encourage good editor-independent formatting habits Paul Cochrane created ticket  with the mission&#8220;Figure out how to encourage good formatting habits, without assumingthat everyone uses emacs or vim, and with minimal clutter in oursource code.  Also write a coding standards test to codify this.&#8221; generated files with timezoneless times In ticket , Will Coleda wanted tools which insert messagesinto generated files to include a timezone (which should always be UTC).Paul Cochrane said that generated files are checked for timestamps incodingstd/gmt_utc.t and that config/init/defaults.pm was updatedin r17923 to pass the test. - add a Perl::Critic policy to look for FIXME&#124;TODO&#124;XXX In ticket , Paul Cochrane suggested adding a checkfor &#8216;FIXME&#8217;/''TODO&#8217;/etc for the Perl source files, as there is currentlyonly a check for the C files.  This was done in r17925.Chris Dolan suggested using an existing Perl::Critic policy.  WillColeda asked if Andy Lester would accept a patch which would optionallypermit &#8216;TODO&#8217; comments which included ticket numbers and reject thosewhich don&#8217;t include ticket numbers?  Andy replied that he didn&#8217;t wantto build exceptions, and proposed that people just write &#8216;RT #12345&#8242;instead of &#8216;TODO (#12345)&#8217;.  Paul Cochrane agreed with this suggestion.Link''n''Load on WindowsRon Blaschke reported that he was looking into errors in linking andloading on Windows.  With linking, there were some symbols whichweren&#8217;t exported.  He planned to provide a patch to export them.To solve the loading issue, he wanted to change several files toinclude the full path.Current State of Building Parrot on CygwinRon Blaschke reminded the list that it is necessary to include theabsolute path to blib/lib in the PATH environment variable to buildon Cygwin.  He also explained that some problems could be due to afile having Windows line endings.Eric Hanchrow wondered if it was possible to make Parrot less fussy aboutthe line endings.  Ron wanted to see that happen, but explained thathis suggestions were workarounds until the problem was resolved.Steve Peters said he would be looking at how to improve the process.Ron described his thoughts in more detail in&#8216;Link&#8217;n''Load on Windows.&#8217;  chromatic asked if itwas possible to pass flags to the linker to hint at the path, whichis how the Linux version works.  Ron thought that was handy, but hadn&#8217;tseen something similar for Windows.:  Configure.pl:  refactor options processing functionality In ticket , James Keenan posted a patch which removescommand-line option processing from Configure.pl and puts it in alibrary.  This was done to make testing easier.  The patch was appliedas r17983 and 17984. Borland C++ cleanups In ticket , Steve Peters submitted a patch whichcleans up Parrot for Borland C++ on Windows.  This patch does notcompletely solve compilation issues because it seems to create newissues on Linux.  chromatic applied it as r18134.Hash iteration questionJonathan Worthington showed two examples of iterating through a hash,and noted that in his first example you will only get the first keyin the hash.  He believes that the hash and iterator code are closelytied and isn&#8217;t thread safe.Allison Randal replied that the significant differences are down tothe iterator&#8217;s shift_pmc throwing an exception if the key is -1,while shift_string doesn&#8217;t check.  She agreed that the code wasexcessively mixed and needed review.  Leopold Toetsch added a furtherexplanation on the implementation.PDD15: newclassJonathan Worthington had a comment on PDD 15 and how it describedthe creation of a new class.  He wondered about the implementation.Allison Randal replied that the newclass opcode will be a simpleopcode.  Jonathan implemented what Allison described and createda test for it as well.:  pmc2c.pl:  Does anyone (know how to) use the ''no-body'' option? In ticket , James Keenan noted that nobody had spokenup in favor of retaining the no-body option.  He said that he wouldtake the ticket and assign the revisions to participants of the PerlSeminar Phalanx Phoneix project in NY. Parrot cleanups - part 2 Steve Peters submitted a patch in ticket  whichhad some cleanups to make Parrot work better with different Ccompilers.  It was applied as r17952.Paper on Software Patterns in ParrotKlaas-Jan Stol mentioned that he had recently taken a course onsoftware patterns and co-authored a paper on patterns in Parrot,which he put on the wiki.: Parrot::Pmc2c::PCCMETHOD constants should be autogenerated Jerry Gay noted that lib/Parrot/Pmc2c/PCCMETHOD.pm contains a numberof constants, which should be generated during the configure processand included.  Ticket  discusses this request. MMD needs to be in a PDD Jonathan Worthington wanted multi-method dispatch to be documentedin one of the PDDs or get its own PDD.  The request was made in ticket. modify PCCMETHOD syntax to more closely match PDD03 In ticket , Jerry Gay requested that the PCCMETHODsyntax be modified to put it in line with PDD 03.  chromatic offereda patch. NCI methods now name-mangledJonathan Worthington explained that he was starting to move classfunctionality into vtable methods, but ran into the issue that youcannot have a METHOD or PCCMETHOD with the same name as a vtable method.This is, however, required to implement the interface specified in PDD 15.He made some changes to get Parrot working again, which affected theComplex PMC.Fran&#231;ois PERRADreported that this change breaks Lua.Allison Randal observed the same thing, and asked Jonathan to eitherfix the problem for Lua or revert the change from the trunk.Jonathan apologized for breaking things and said that it was fixedin r17982.Leopold Toetsch suggested that perhaps language maintainers who areusing &#8220;features&#8221; of Parrot could submit core tests for these features,so that this type of problem won&#8217;t occur in the future.  JoshuaIsom thought this could be expanded to anyone using Parrot where a&#8220;feature&#8221; isn&#8217;t tested.  He proposed an open repository for tests.chromatic wondered if it was that difficult to mail in patches.Joshua offered some arguments in favor of it.chromaticparrotcode updates.Will Coleda solicited for suggestions concerning parrotcode.org becausethe current system requires that he do quite a bit of work every timethat directories are moved or files are added.  He proposed having adirectory on the site which is a checked in version of the docs/htmldirectory after a make html.  He also suggested having docs for thelatest release as well as for the &#8217;svn head&#8217;. t/doc/pod.t vs. tools/doc/pod_errors.pl In ticket , Will Coleda suggested removing the scripttools/doc/pod_errors.pl because the functionality is already replicatedin t/doc/pod.t.  Also, the script finds errors which the test doesnot, which requires investigation.Joshua Isom reported that he got a segmentation fault when he tried torun the script.  When he persisted with running it, he found thatit appeared to have a stricter POD checker.  Jerry Gay thought that perhapsthe script and the test were checking different files.Paul Cochrane found that on Gentoo the t/codingstd/c_indent.ttest caused Perl to segfault, but with a new Perl compilation itworked.  He was curious because he wondered if the error was dueto Gentoo Perl.  Joshua replied that he was using Darwin.Paul also remarked that the script reported where the errors werein the file, which the test didn&#8217;t do.  Jerry Gay and Nicholas Clarkcommented on this.James E Keenan reposted a call for hackathons which was publishedfor YAPC::Europe 2007.  He felt that the Chicago Hackathon had beengood for Parrot, and hoped that someone would lead a related Hackathonat Y::E.  More information about the offer can be foundon the Y::E website.Re:  AutoReply: $P symbolic register allocation bugYehoshua Sapir remarked that the code in  workedon OS X. t/pmc/sub.t: test for creation of lex by clone op Yehoshua Sapir submitted ticket  which contains apatch to test the creation of lex by the clone op.:  Configure.pl:  Move STDOUT messages to Parrot::Configure::Messages James Keenan created ticket  to submit a patchwhich makes lib/Parrot/Configure/Messages.pm output messages toSTDOUT (previously this was done by Configure.pl).  This willmake testing easier.  It was applied as r18027. r17987 - in trunk: . docs/pdds docs/pdds/draftAllison Randal made a commit which moved PDD 15 (objects) out ofthe draft directory.Use of English pragmaJames E Keenan noted that Configure.pl uses one of the English variables,namely $OUTPUT_AUTOFLUSH.  He wondered if there was a policy regardingthe use of the named variables instead of their short versions.  JerryGay felt that it was wrong to use the English variables.James said he would convert the variable while refactoring.  He createdticket  to remind him of this task.  Later this wasdone and applied as r18033. PMC documentation guidelines draftJerry Gay remarked that he had committed a draft of PMC documentationguidelines in r17998.  The document is meant to explain the style ofdocumentation which should be used for core PMCs.  Klaas-Jan Stolhad some additions, borrowed from &#8216;Perl Best Practices&#8217;.  Joshua Isomquestioned Klaas-Jan on some of the points, and Klaas-Jan elaborated. src/pmc/os.pmc: bad use of stat(2) and lstat(2) In ticket , the output of t/pmc/os.t which failson Solaris 10 was attached. improper casting to void * in src/dynext.c Ticket  contained a small patch to changeload_func from a void pointer to NULL.  Leopold Toetschexplained that the cast was being used.  Nicholas Clark answeredthat the casting isn&#8217;t allowed under strict ANSI C, but that hepreferred assigning a plain 0 rather than a NULL. Memory leak with String pmc Mehmet Yavuz Selim Soyturk reported a memory leak in some example code.This was mentioned in ticket .vtable cleanup and questionsJonathan Worthington reported that he is adding the new vtableentries required for PDD 15.  He wanted to know if become_parent couldbe removed, or if it needed a standard deprecation cycle.  He alsonoted that the subclass vtable method needed to be removed, but thatit was in use by ParrotClass and ParrotObject.Jonathan also noticed PMC* new_singleton() and PMC* get_anonymous_subclass(), which don&#8217;t appear to be used.  Hewondered if they should be removed.  Finally, he wanted to know ifget_attr and set_attr should be deprecated.Nicholas Clark confirmed that become_parent isn&#8217;t used anywhere.Will Coleda voted for a standard deprecation cycle.  Allison Randalconfirmed this.She also agreed that the subclass vtable method won&#8217;t be needed inthe future, and that get_attr and set_attr could bedeprecated when the full change to PDD 15 is complete.new_singleton() and get_anonymous_subclass()were described as &#8220;a case of being overly prepared for possiblefuture needs.&#8221;Minor notes/suggestions on PDD15Klaas-Jan Stol had some suggestions for PDD 15.  He had some suggestionsfor improving consistency.  Additional comments were on opportunitiesfor syntactic sugar and a proposal that operators such as find_methodhave the option of throwing an exception rather than returning a NULLPMC on failure.  Allison Randal remarked that the exception questionwas under consideration in I/O as well, and that the consistencyissue would be considered when she looked at the Opcodes PDD.Joshua Isom had a few suggestions as well.AcknowledgementsThis summary was prepared usingMail::Summary::Tools,available on CPAN.If you appreciate Perl, consider contributing to the PerlFoundation to help support thedevelopment of Perl.Thank you to everyone who has pointed out mistakes and offeredsuggestions for improving this series.  Comments on this summary can besent to Ann Barcomb, &#107;&#117;&#100;r&#97;&#64;&#x64;&#111;&#109;&#x61;&#x69;&#110;&#x74;&#x6A;&#101;&#46;&#99;&#111;&#x6D;.DistributionThis summary can be found in the following places:use.perl.orgThe Pugs blogThe perl6-announce mailing listONLampSee Also  Perl Foundation activities  Perl 6 Development  Planet Perl Six  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Glow In The Dark Bra Allows U to Find the Goods Easier -</title>\r\n  <link>http://tech.originalsignal.com/article/47040/glow-in-the-dark-bra-allows-u-to-find-the-goods-easier.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 10:08:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47040/glow-in-the-dark-bra-allows-u-to-find-the-goods-easier.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  THIS hot model looks a bit of all bright - posing in the world''s first ever glow-in-the-dark bra.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Apple: A Romance (Leaving Apple)</title>\r\n  <link>http://tech.originalsignal.com/article/47039/apple-a-romance-leaving-apple.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 09:38:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47039/apple-a-romance-leaving-apple.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  "Buzz Andersen has written an emotional and truthful announcement of his departure from Apple." - http://www.red-sweater.com/blog/326/saying-goodbye-to-apple  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Text Messages Used To Monitor Elections</title>\r\n  <link>http://tech.originalsignal.com/article/47038/text-messages-used-to-monitor-elections.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 09:38:03 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47038/text-messages-used-to-monitor-elections.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  InternetVoting writes "The upcoming historic Nigerian elections are going to be defended by an army of observers armed, not with guns, but with text messages. Every one of the observers will be outfitted with a cell phone to report vote tampering. The volunteers are a part of the Network of Mobile Election Monitors, and they use freeware to do what they do. From the article: ''NMEM is using a free system called Frontline SMS, developed by programmer Ken Banks, to keep track of all of the texts. Originally developed for conservationists to keep in touch with communities in National Parks in South Africa, the system allows mass-messaging to mobile phones and crucially the ability to reply to a central computer. It has already been used in countries such as Zimbabwe as a way of bypassing broadcast restrictions and distributing information to rural communities.''"Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Next Motorola Q</title>\r\n  <link>http://tech.originalsignal.com/article/47035/next-motorola-q.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 08:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47035/next-motorola-q.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Motorola has changed the designation-preparing for a series of Qs?  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Mac OS X Business Tools: There Are More Than You Think</title>\r\n  <link>http://tech.originalsignal.com/article/47036/mac-os-x-business-tools-there-are-more-than-you-think.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 08:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47036/mac-os-x-business-tools-there-are-more-than-you-think.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Macs have never been considered business machines and yet record numbers of small and mid-size businesses are running on Mac OS X today. Ryan Faas illustrates why the idea that Macs aren''t up to the needs of business computing is indeed a myth by pointing out top business tools that exist for Mac users.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Motorola tries Digg and Delicious to launch new product</title>\r\n  <link>http://tech.originalsignal.com/article/47037/motorola-tries-digg-and-delicious-to-launch-new-product.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 08:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47037/motorola-tries-digg-and-delicious-to-launch-new-product.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Motorola has decided to try Digg and Delicious to spread news about their new phones. Now their product pages feature "Digg it" and "Save to Delicious " buttons  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>MacBook Hacked In Contest Via Zero-Day Hole in Safari</title>\r\n  <link>http://tech.originalsignal.com/article/47034/macbook-hacked-in-contest-via-zero-day-hole-in-safari.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 08:08:02 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47034/macbook-hacked-in-contest-via-zero-day-hole-in-safari.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  EMB Numbers writes "Shane Macaulay just won a MacBook as a prize for successfully hacking OS X at CanSecWest conference in Vancouver, BC. The hack was based on a Safari vulnerability found by Dai Zovi and written in about 9 hours. CanSecWest organizers actually had to relax the contest rules to make the hack possible, because initially nobody at the event could breach the computers under the original restrictions. ''Dai Zovi plans to apply for a $10,000 bug bounty TippingPoint announced on Thursday if a previously unknown Apple bug was used. "Shane can have the laptop, I want the money," Dai Zovi said in a telephone interview from New York. TippingPoint runs the Zero Day Initiative bug bounty program.''"Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Burning CDs in GNOME</title>\r\n  <link>http://tech.originalsignal.com/article/47032/burning-cds-in-gnome.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 07:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47032/burning-cds-in-gnome.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  GNOME applications that make CD burning easy  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>HandBrake 0.8.5b1 Released</title>\r\n  <link>http://tech.originalsignal.com/article/47033/handbrake-0-8-5b1-released.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 07:08:04 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47033/handbrake-0-8-5b1-released.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  After a 14-month hiatus, a brand new development team, a project forked then unforked, and literally hundreds of revisions… a new (beta) version of HandBrake is available! (HandBrake is an open-source, GPL-licensed, multiplatform, multithreaded DVD to MPEG-4 converter available for MacOS X, Linux, and Windows.)  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Outsource your dry cleaning and dog walking with DoMyStuff</title>\r\n  <link>http://tech.originalsignal.com/article/47031/outsource-your-dry-cleaning-and-dog-walking-with-domystuff.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 06:38:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47031/outsource-your-dry-cleaning-and-dog-walking-with-domystuff.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Kramer from Seinfeld had the right idea when he got an intern for his daily tasks, and DoMyStuff is looking to help busy people find assistants online for any variety of tasks.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Teens Actually Do Protect Their Online Profiles</title>\r\n  <link>http://tech.originalsignal.com/article/47030/teens-actually-do-protect-their-online-profiles.html</link>\r\n  <pubDate>Sat, 21 Apr 2007 06:08:02 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/47030/teens-actually-do-protect-their-online-profiles.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Thib writes "A study from the Pew Internet and American Life Project reveals that the majority of teens pay attention to what they are revealing about themselves in their online social profiles. For instance, while many routinely use their first name or include a picture, ''fewer than a third of teens with profiles use their last names, and a similar number include their e-mail addresses. Only 2 percent list their cell phone numbers.'' The study comes to light just as state legislatures once again begin to mutter about the dangers of online predators. From the article: ''According to Pew, 45 percent of online teens do not have profiles at all, a figure that contradicts widespread perceptions that the nation''s youths are continually on MySpace.''"Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    	\r\n	</channel>\r\n</rss>', '20070421100257');

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

INSERT INTO `dsb_flirts` (`flirt_id`, `flirt_text`, `flirt_type`) VALUES (1, 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', 0),
(2, 'Aye aye, mate!', 0),
(3, 'Let''s rock and roll!', 0);

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
(0x6465665f757365725f7072656673, 'Default User Preferences', 'The default user preferences', 0, 1.00);

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
  UNIQUE KEY `sess` (`sess`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_online`
-- 

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES (0, '20070427172400', 0x3532653932346336363763376338326661363238663566306432336666306131),
(0, '20070427164955', 0x3663666363633731653338306438343630323235346638636535343930343763),
(0, '20070427165631', 0x3038633435313465633563643332353331303735383164383262326536316238),
(0, '20070427172420', 0x3534616631656431363132316462323365626138633465646165366563616135),
(7, '20070427201603', 0x3132343434303039373538643231353063623161663763633961396532663635),
(7, '20070427195504', 0x6230396537346533623435666438346239353637383664636233373361616238);

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
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_photo_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_photo_comments`
-- 

INSERT INTO `dsb_photo_comments` (`comment_id`, `fk_photo_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 1, 6, 'cocacola', 'hi thetr', '2007-04-16 18:54:48', '2007-04-16 18:54:48', 15),
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
  `fk_user_id_profile` int(10) unsigned NOT NULL default '0',
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `_user` varchar(48) NOT NULL default '',
  `comment` text NOT NULL,
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_user_id_profile`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_profile_comments`
-- 

INSERT INTO `dsb_profile_comments` (`comment_id`, `fk_user_id_profile`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 1, 7, 'dragon', 'hi emma', '2007-04-27 16:03:07', '2007-04-27 16:03:07', 15),
(2, 1, 7, 'dragon', 'hi emma', '2007-04-27 16:20:27', '2007-04-27 16:20:27', 15),
(3, 1, 7, 'dragon', 'hi emma', '2007-04-27 16:20:47', '2007-04-27 16:20:47', 15),
(4, 1, 7, 'dragon', 'whoa', '2007-04-27 16:25:29', '2007-04-27 16:25:29', 15);

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

INSERT INTO `dsb_profile_fields` (`pfield_id`, `fk_lk_id_label`, `html_type`, `searchable`, `search_type`, `for_basic`, `fk_lk_id_search`, `at_registration`, `reg_page`, `required`, `editable`, `visible`, `dbfield`, `fk_lk_id_help`, `fk_pcat_id`, `access_level`, `accepted_values`, `default_value`, `default_search`, `fn_on_change`, `order_num`) VALUES (1, 3, 4, 0, 1, 0, 4, 1, 2, 0, 1, 1, 0x6631, 5, 1, 0, '', '', '', '', 5),
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

INSERT INTO `dsb_queue_email` (`mail_id`, `to`, `subject`, `message_body`, `date_added`) VALUES (1, 'newdsb@sco.ro', 'Web Application: One of your photos was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Nexus  /  R i t m o / friendy</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', '20070425192948'),
(2, 'newdsb@sco.ro', 'Web Application: One of your photos was not approved', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <p>Hai sictir</p>\r\n    </body>\r\n</html>', '20070425212636');

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

INSERT INTO `dsb_queue_message` (`mail_id`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 1, 0, '', 'New comment on one of your photos', 'dragon posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=18">Click here</a> to view the comment', '2007-04-24 17:19:32', 2),
(2, 11, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10">Click here</a> to view the comment', '2007-04-24 17:20:12', 2),
(3, 11, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10#comm29">Click here</a> to view the comment', '2007-04-26 18:45:36', 2),
(4, 1, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm2">Click here</a> to view the comment', '2007-04-27 16:20:27', 2),
(5, 1, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm3">Click here</a> to view the comment', '2007-04-27 16:20:47', 2),
(6, 1, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm4">Click here</a> to view the comment', '2007-04-27 16:25:29', 2);

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

INSERT INTO `dsb_site_log` (`log_id`, `fk_user_id`, `user`, `m_value`, `fk_level_id`, `ip`, `time`) VALUES (1, 0, 'dragon', 1, 1, 2130706433, '20070427164403'),
(2, 0, 'dragon', 1, 1, 2130706433, '20070427173638'),
(3, 0, 'dragon', 1, 1, 2130706433, '20070427185007'),
(4, 0, 'dragon', 1, 1, 2130706433, '20070427195459');

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
(8, 0x6d616e75616c5f70686f746f5f617070726f76616c, '0', 'New uploaded photos require manual approval before being displayed on the site?', 9, 0x636f72655f70686f746f, 0),
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
(55, 0x70726f66696c655f636f6d6d656e7473, '1', 'Allow comments on my profile?', 9, 0x6465665f757365725f7072656673, 1);

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

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES ('520c89949ed6f447de06f30f224903f3', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"uid";s:3:"uid";s:1:"1";}', '8,1', 7, '20070427174306'),
('f137ef4b54ddc91c2b3a984ac05b7206', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"uid";s:3:"uid";s:2:"13";}', '', 7, '20070427174503'),
('d9c125814e6d01cc042129671cc93b19', 1, 'a:2:{s:11:"acclevel_id";i:16;s:2:"st";s:6:"online";}', '', 7, '20070425102353'),
('cbe514451b3e7e980d1d4f0d53ed348a', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"uid";s:3:"uid";s:1:"7";}', '9,5,3', 1, '20070425180113'),
('40cd750bba9870f18aada2478b24840a', 2, 'a:0:{}', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19', 0, '20070425192902'),
('04a51b8634d9c552baeb60eb5109b481', 2, 'a:1:{s:4:"stat";s:1:"5";}', '', 0, '20070425212034'),
('2c9d72b084c7ce058d2c28e15c871e08', 2, 'a:1:{s:4:"stat";s:2:"10";}', '18', 0, '20070425212042'),
('2a6155930fda31f53eed73137039685c', 2, 'a:1:{s:3:"uid";s:1:"1";}', '2,3,4,14,15,16,17,18,19', 0, '20070425212128'),
('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16', 0, '20070425212445');

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

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES (1, 0x656d6d61, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '02912174c5cc19dedd90bc498af2d9b0', '20070425195507'),
(2, 0x6b65697468, 0x3166333837306265323734663663343962336533316130633637323839353766, 15, 2, 'newdsb@sco.ro', '', '2fba765ee1b6ade5287073584ec894e4', '20070418125301'),
(3, 0x73686f6e323035, 0x3037373631303430363739346562326133633736393139396439346131613739, 15, 2, 'newdsb@sco.ro', '', 'b3d7ac7336a79fe5a4b01a88f5727b13', '20070418125301'),
(4, 0x6d6176657269636b, 0x3839393636303063333130383863366137363436346365333864393635636364, 15, 2, 'newdsb@sco.ro', '', '87a6ce818365e8b3bef125df92826702', '20070418125301'),
(5, 0x313030343537, 0x3836653236636161363936356531313066666461353936373465373163643264, 15, 2, 'newdsb@sco.ro', '', 'c5cb27f0e77add4c24ffc48562a0706d', '20070418125301'),
(6, 0x636f6361636f6c61, 0x6361323466306531653366663730316661346633336335336639303566396461, 15, 2, 'newdsb@sco.ro', '', '7f2fe99deb74578bf4f6e5e26dd99ae0', '20070418125301'),
(7, 0x647261676f6e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '1dc9d7dbc613c521a8efeae0f7706f2e', '20070427171603'),
(8, 0x616e676b617361, 0x3634383530366161333739666566623930323663376530313664386463626663, 15, 2, 'newdsb@sco.ro', '', '36e12348474821624874994164838526', '20070418125301'),
(9, 0x72616e65676c6f, 0x3739343063343139653932336166306265373963623835613432663836343964, 15, 2, 'newdsb@sco.ro', '', '83f1200b9f1bdd252e2aebc2dfffe34f', '20070418125301'),
(10, 0x737472617762657272696573, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '68779ace53ca9ba01a52efa82e53bf86', '20070418125301'),
(11, 0x6a6f686e626f79, 0x6463613961313066636662396434346365346265393037393965633762623738, 15, 2, 'newdsb@sco.ro', '', '4173da6fd2bf9e455e18cdd70953f0b6', '20070418125301'),
(12, 0x615f6c5f66, 0x3530393636333531626362373264373463636466636634313038613734613664, 15, 2, 'newdsb@sco.ro', '', 'c846ec67e081e672e99c7a14d167b463', '20070418125301'),
(13, 0x6a6f686e626f7932, 0x6463613961313066636662396434346365346265393037393965633762623738, 15, 2, 'newdsb@sco.ro', '', 'f446c1ae194fc4ed6309e1b60f177ebb', '20070418125301'),
(14, 0x7470616e647470, 0x3639336538313066663237363034653664613237346461346337376531333663, 15, 2, 'newdsb@sco.ro', '', '76b41bfad8b909b658e578f08f7a6244', '20070418125301'),
(15, 0x626c61636b7761746572, 0x6166623332376439386230316537323035383035323233613331313363303563, 15, 2, 'newdsb@sco.ro', '', 'bc217753a490dd732c4e992340db665c', '20070418125301'),
(16, 0x746573746572, 0x6234663062656231303130343734626564396366303234303231366333313464, 10, 2, 'newdsb@sco.ro', '', 'cf3b1718be2b5af79bf2c189eb0bbedf', '20070418125301');

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
(4, 11, 'Testing Testing the Blog', 'Testing Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing', 1, '', ''),
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
(40, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(41, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(36, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(37, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(38, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(39, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(14, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(15, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(16, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(17, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(18, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(19, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(20, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(21, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(22, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(23, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(50, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(49, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(48, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(47, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(46, 1, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(44, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(35, 1, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 2, 0, 1),
(25, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(26, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(27, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(28, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(29, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(30, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(31, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(32, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(33, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(34, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(51, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(52, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(53, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(54, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(55, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(56, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(57, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(58, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(59, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(60, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(61, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(62, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(63, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(64, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(65, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(66, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(67, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(68, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(69, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(70, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(71, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(72, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(73, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(74, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(75, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(76, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(77, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(78, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(79, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(80, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(81, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(82, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(83, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(84, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(85, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(86, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(87, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(88, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(89, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(90, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(91, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(92, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(93, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(94, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(95, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(96, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(97, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(98, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(99, 0, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(100, 0, 7, 1, 'emma', 'Connection request from emma', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 19:37:13', 0, 0, 0),
(101, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-16 19:58:56', 1, 0, 0),
(102, 0, 7, 10, 'strawberries', 'Connection request from strawberries', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:09:02', 0, 0, 0),
(103, 0, 7, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 21:28:34', 0, 0, 0),
(104, 0, 7, 11, 'johnboy', 'johnboy sent you a flirt', 'Aye aye, mate!', '2007-04-16 21:30:01', 1, 0, 0),
(105, 0, 11, 13, 'johnboy2', 'Connection request from johnboy2', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:25:52', 0, 0, 0),
(106, 0, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-17 05:28:16', 1, 0, 0),
(107, 0, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(108, 0, 11, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-17 06:26:57', 1, 0, 0),
(109, 0, 11, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:22:20', 1, 0, 0),
(110, 0, 7, 1, 'emma', 'frfrfrf', 'asdasd\r\nasd\r\nasd\r\nas\r\nda\r\ndsa\r\nsd', '2007-04-19 10:44:28', 0, 0, 0),
(111, 1, 7, 0, '', 'New comment on your photos', 'emma posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id= 5">Click here</a> to view the comment', '2007-04-19 14:25:46', 2, 0, 0),
(112, 0, 12, 1, 'emma', 'xxx', 'yyy', '2007-04-20 15:46:28', 0, 0, 0),
(113, 0, 12, 1, 'emma', 'emma sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-20 15:46:36', 1, 0, 0),
(114, 0, 11, 0, '', 'New comment on one of your blogs', 'guest posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10">Click here</a> to view the comment', '2007-04-20 15:57:02', 2, 0, 0),
(115, 0, 11, 0, '', 'New comment on one of your blogs', 'guest posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=10">Click here</a> to view the comment', '2007-04-20 22:08:44', 2, 0, 0);

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

INSERT INTO `dsb_user_outbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (16385, 0, 1, 12, 'a_l_f', 'xxx', 'yyy', '2007-04-20 15:46:28', 0);

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
  `flagged` tinyint(1) unsigned NOT NULL default '0',
  `reject_reason` text NOT NULL,
  `stat_views` int(10) unsigned NOT NULL default '0',
  `stat_votes` int(4) unsigned NOT NULL default '0',
  `stat_votes_total` int(5) unsigned NOT NULL default '0',
  `stat_comments` int(5) unsigned NOT NULL default '0',
  `date_posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_changed` datetime NOT NULL default '0000-00-00 00:00:00',
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

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `allow_rating`, `caption`, `status`, `del`, `flagged`, `reject_reason`, `stat_views`, `stat_votes`, `stat_votes_total`, `stat_comments`, `date_posted`, `last_changed`) VALUES (1, 4, 'maverick', '7/4_11176746548.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 20, 2, 9, 3, '2007-04-16 18:02:29', '2007-04-16 18:08:52'),
(2, 1, 'emma', '4/1_11176750826.jpg', 0, 0, 1, 1, 'ruuuuun, enemies are coming!!', 15, 0, 0, '', 15, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-21 13:37:40'),
(3, 1, 'emma', '2/1_21176750826.jpg', 0, 0, 1, 1, 'daddy''s girl', 15, 0, 0, '', 8, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-21 13:37:55'),
(4, 1, 'emma', '1/1_31176750826.jpg', 0, 0, 1, 1, 'hey, look, I can walk...sort of :)', 15, 0, 0, '', 6, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-21 13:47:02'),
(5, 7, 'dragon', '0/7_11176751977.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 24, 3, 15, 2, '2007-04-16 19:32:59', '2007-04-16 19:33:32'),
(6, 10, 'strawberries', '3/10_11176756425.jpg', 1, 0, 1, 1, 'Fluffy, she who must be obeyed', 15, 0, 0, '', 9, 0, 0, 1, '2007-04-16 20:47:07', '2007-04-16 20:48:32'),
(7, 10, 'strawberries', '3/10_21176756425.jpg', 0, 0, 1, 1, 'Fluffy loves getting her picture taken', 15, 0, 0, '', 6, 0, 0, 0, '2007-04-16 20:47:07', '2007-04-16 20:48:32'),
(8, 11, 'johnboy', '0/11_11176758735.jpg', 1, 1, 1, 1, '', 15, 0, 0, '', 13, 1, 5, 0, '2007-04-16 21:25:35', '2007-04-17 12:27:01'),
(9, 13, 'johnboy2', '6/13_11176787947.jpg', 1, 0, 1, 1, 'Out the front of my house', 15, 0, 0, '', 6, 1, 4, 2, '2007-04-17 05:32:29', '2007-04-17 05:33:50'),
(10, 13, 'johnboy2', '8/13_21176787947.jpg', 0, 0, 1, 1, 'A poor little bird freezing its butt off.', 15, 0, 0, '', 1, 0, 0, 0, '2007-04-17 05:32:29', '2007-04-17 05:33:50'),
(11, 8, 'angkasa', '2/8_11176790979.jpg', 1, 0, 1, 1, 'fgfgfgfgf', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 06:23:02', '2007-04-17 06:24:21'),
(12, 15, 'blackwater', '1/15_11176795306.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 07:35:07', '2007-04-17 07:36:26'),
(13, 12, 'a_l_f', '9/12_11176812224.jpg', 1, 0, 1, 1, 'Here Kitty Kitty', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 12:17:04', '2007-04-17 12:17:31'),
(14, 1, 'emma', '2/1_11176990562.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 13:49:25', '2007-04-19 13:50:02'),
(15, 1, 'emma', '7/1_11176990694.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 13:51:39', '2007-04-19 13:51:43'),
(16, 1, 'emma', '0/1_11176991248.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 14:00:51', '2007-04-19 14:03:04'),
(17, 1, 'emma', '3/1_21176991248.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 14:00:51', '2007-04-19 14:03:19'),
(18, 1, 'emma', '8/1_21176991707.jpg', 1, 0, 1, 0, 'auuu, my eye, my eyeee!', 15, 0, 0, '', 39, 0, 0, 6, '2007-04-19 14:08:29', '2007-04-25 19:54:58'),
(19, 1, 'emma', '8/1_41176991707.jpg', 0, 0, 1, 0, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-19 14:08:29', '2007-04-19 14:08:38');

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
  `f1` varchar(100) NOT NULL default '',
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

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `del`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6_country`, `f6_state`, `f6_city`, `f6_zip`, `f7`, `f8`, `f9`, `f10`, `f11`, `f12`, `f13`, `f14`, `f15`) VALUES (1, 1, 15, 0, '2007-04-19 14:08:29', '2007-04-16 14:42:06', '', 'emma', '', 0.0000000000, 0.0000000000, 26, '', 2, '|1|', 1, '1989-06-05', 165, 0, 0, '', 3, 3, 2, 3, 1, 1, 1, 1, ''),
(2, 2, 15, 0, '2007-04-16 12:42:01', '2007-04-16 17:09:23', '', 'keith', '', 0.0000000000, 0.0000000000, 0, 'Testing', 1, '|2|', 1, '1976-05-12', 217, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, '|1|4|7|'),
(3, 3, 15, 0, '2007-04-16 17:29:10', '2007-04-16 17:24:38', '', 'shon205', '', -81.6286010742, 41.4509010315, 0, '', 1, '|2|', 1, '1971-01-06', 218, 41, 1620, '44105', 1, 1, 1, 1, 1, 1, 1, 1, ''),
(4, 4, 15, 0, '2007-04-16 12:41:49', '2007-04-16 17:46:34', '', 'maverick', '', 0.0000000000, 0.0000000000, 4, '', 1, '|2|', 33, '0000-00-00', 218, 0, 0, '86314', 3, 3, 2, 3, 1, 1, 2, 2, '|1|'),
(5, 5, 15, 0, '2007-04-16 12:41:45', '2007-04-16 18:26:47', '', '100457', '', 0.0000000000, 0.0000000000, 5, 'cool me', 1, '|2|', 32, '1978-04-07', 218, 0, 0, '49504', 5, 1, 3, 1, 1, 1, 1, 1, '|1|7|'),
(6, 6, 15, 0, '2007-04-16 12:41:53', '2007-04-16 18:50:27', '', 'cocacola', '', 0.0000000000, 0.0000000000, 5, 'Im cool', 1, '|2|', 38, '1969-01-01', 186, 0, 0, '07631', 3, 1, 1, 1, 1, 1, 1, 1, '|1|'),
(7, 7, 15, 0, '2007-04-16 12:41:56', '2007-04-16 19:26:41', '', 'dragon', '0/7_11176751977.jpg', 0.0000000000, 0.0000000000, 53, 'oops, this should have been a textarea, not a textfield. Easy to fix!', 1, '|2|', 58, '1976-11-01', 165, 0, 0, '', 3, 5, 2, 5, 1, 2, 4, 1, '|2|'),
(8, 8, 15, 0, '2007-04-17 06:23:17', '2007-04-16 19:54:09', '', 'angkasa', '2/8_11176790979.jpg', 0.0000000000, 0.0000000000, 5, 'I''m Cool', 1, '|2|', 47, '1980-09-18', 181, 0, 0, '', 2, 3, 1, 2, 3, 2, 5, 3, '|1|7|'),
(9, 9, 15, 0, '2007-04-16 19:56:29', '2007-04-16 19:55:41', '', 'raneglo', '', 0.0000000000, 0.0000000000, 0, 'I am me!', 1, '|1|', 34, '1961-07-29', 218, 0, 0, '77584', 3, 3, 6, 5, 1, 2, 4, 1, '|4|5|'),
(10, 10, 15, 0, '2007-04-16 20:48:32', '2007-04-16 20:26:23', '', 'strawberries', '3/10_11176756425.jpg', 0.0000000000, 0.0000000000, 2, '', 1, '|2|', 38, '1964-12-31', 217, 0, 0, '', 3, 3, 2, 5, 1, 2, 3, 1, '|1|'),
(11, 11, 15, 0, '2007-04-17 04:54:56', '2007-04-16 21:10:00', '', 'johnboy', '0/11_11176758735.jpg', 0.0000000000, 0.0000000000, 10, 'Hello everyone, at last we can see what the new DSB is about, I LIKE IT, very clean.', 1, '|2|', 39, '1958-01-17', 145, 0, 0, '', 3, 1, 2, 9, 3, 2, 2, 2, '|7|'),
(12, 12, 15, 0, '2007-04-17 12:17:31', '2007-04-17 00:26:40', '', 'a_l_f', '9/12_11176812224.jpg', 0.0000000000, 0.0000000000, 0, 'Computer Junkie that loves kung fu and motor bikes', 1, '|2|', 29, '1959-07-28', 11, 0, 0, '', 3, 3, 6, 2, 3, 2, 3, 1, '|3|'),
(13, 13, 15, 0, '2007-04-17 12:32:53', '2007-04-17 05:13:27', '', 'johnboy2', '6/13_11176787947.jpg', 0.0000000000, 0.0000000000, 5, 'Testing this out', 1, '|2|', 39, '1958-01-17', 145, 0, 0, '', 3, 1, 2, 9, 3, 2, 2, 2, '|7|'),
(14, 14, 15, 0, '2007-04-17 05:48:43', '2007-04-17 05:48:17', '', 'tpandtp', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 1, '0000-00-00', 11, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, ''),
(15, 15, 15, 0, '2007-04-17 07:36:26', '2007-04-17 07:27:52', '', 'blackwater', '1/15_11176795306.jpg', 0.0000000000, 0.0000000000, 0, 'şşşş ğğğğğ ııııı', 1, '|2|', 51, '1977-04-28', 210, 0, 0, '', 3, 3, 1, 2, 2, 2, 3, 1, '|1|7|'),
(16, 16, 15, 0, '2007-04-17 10:36:09', '2007-04-17 10:35:07', '', 'tester', '', 0.0000000000, 0.0000000000, 0, 'about-me...looking at this softa\\ware', 1, '|2|', 50, '1969-11-30', 186, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, '|4|6|7|');

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
(33, 1, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(32, 1, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 0x636f7265),
(31, 1, 0x646174655f666f726d6174, '%m/%d/%Y', 0x636f7265),
(34, 1, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(35, 1, 0x726174655f6d795f70686f746f73, '0', 0x6465665f757365725f7072656673),
(36, 1, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(37, 7, '', '', ''),
(38, 7, 0x6e6f746966795f6d65, '1', ''),
(39, 1, '', '', ''),
(40, 1, 0x70726f66696c655f636f6d6d656e7473, '1', '');

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
(1, 'comments', 7),
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
(1, 'pviews', 28),
(8, 'pviews', 1),
(1, 'mess_sent', 2),
(7, 'comments', 4),
(11, 'pviews', 1),
(7, 'comments_made', 3),
(1, 'profile_comments', 3);
