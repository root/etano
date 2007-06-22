-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 22, 2007 at 12:15 PM
-- Server version: 4.0.18
-- PHP Version: 4.4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
  UNIQUE KEY `level_code_2` (`level_code`),
  KEY `level_code` (`level_code`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_access_levels`
-- 

INSERT INTO `dsb_access_levels` (`level_id`, `level_code`, `level_diz`, `level`, `disabled_level`) VALUES 
(1, 0x6c6f67696e, 'when someone tries to login', 1, 65534),
(2, 0x70726f66696c655f76696577, 'View a member profile', 7, 0),
(21, 0x7365617263685f70686f746f, 'Search/browse/latest/most commented on photo feature', 7, 0),
(4, 0x6d6573736167655f72656164, 'Read messages', 6, 1),
(5, 0x6d6573736167655f7772697465, 'Write messages', 6, 1),
(6, 0x666c6972745f72656164, 'Read flirts', 6, 1),
(7, 0x666c6972745f73656e64, 'Send flirts', 6, 1),
(8, 0x75706c6f61645f70686f746f73, 'Upload photos', 6, 1),
(9, 0x77726974655f636f6d6d656e7473, 'Post comments on photos, profiles, blogs', 6, 0),
(10, 0x726561645f626c6f6773, 'Read blogs', 7, 0),
(11, 0x77726974655f626c6f6773, 'Write own blogs', 6, 1),
(12, 0x766965775f616c62756d, 'Who''s allowed to view the list of photos in a photo album', 7, 0),
(13, 0x766965775f70686f746f, 'View a single photo with a bigger size and photo comments', 7, 0),
(14, 0x6d616e6167655f666f6c64657273, 'Add/Edit/Delete personal mail folders', 6, 1),
(15, 0x73617665645f6d65737361676573, 'Use the saved responses feature', 6, 1),
(16, 0x7365617263685f6261736963, 'Who is allowed to use the basic search forms to search for other members?', 7, 0),
(17, 0x7365617263685f616476616e636564, 'Who is allowed to use the advanced member search form?', 6, 0),
(18, 0x6d616e6167655f6e6574776f726b73, 'Who is allowed to add/remove members in their networks?', 4, 1),
(19, 0x736176655f7365617263686573, 'Who is allowed to save personal searches?', 6, 1),
(22, 0x696e626f78, 'See the list of messages in inbox or other folders', 6, 1),
(23, 0x7365617263685f626c6f67, 'Search/browse/latest/most commented on blog feature', 6, 0),
(24, 0x636f6e74616374, 'Who can send us messages using the contact form?', 7, 0);

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

INSERT INTO `dsb_admin_accounts` (`admin_id`, `user`, `pass`, `name`, `status`, `dept_id`, `email`) VALUES 
(1, 0x61646d696e, 0x6665303163653261376662616338666166616564376339383261303465323239, 'Adrian', 15, 4, 'adi@sco.ro');

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

INSERT INTO `dsb_admin_mtpls` (`amtpl_id`, `amtpl_name`, `subject`, `message_body`, `amtpl_type`) VALUES 
(1, 'Reject member profile', 'Your profile was not approved', '<html><head><title>Your profile has not been approved</title>   <link href="{tplvars.baseurl}/skins/def/styles/common.css" media="screen" type="text/css" rel="stylesheet" /> </head><body> <div id="trim"> 	<div id="content"> 		<p>Thank you for joining <a href="{tplvars.baseurl}">{tplvars.sitename}</a>.</p> 		<p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest to other members.</p><p>Please update your profile with relevant information.<br /></p> 	</div> </div> </body></html>', 1),
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

INSERT INTO `dsb_banned_words` (`word_id`, `word`) VALUES 
(5, 'fuck'),
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
  KEY `key1` (`fk_parent_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_blog_comments`
-- 

INSERT INTO `dsb_blog_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`, `processed`) VALUES 
(1, 1, 10, 'strawberries', 'a reply.\r\n\r\nHi Emma!', '2007-04-16 21:13:30', '2007-04-16 21:13:30', 15, 0),
(2, 2, 10, 'strawberries', 'welcome to the hairball''s abode.', '2007-04-17 21:46:30', '2007-04-17 21:46:30', 15, 0),
(3, 3, 10, 'strawberries', 'It looks excellent, Dan.  I love it.  It looks so clean and covers all the bases. \r\n \r\nI''ll be keen to see the rss look.....for me, it will be important to have more content on the index page.  \r\n\r\nI was messing around with the css stylesheet tonight in my firefox browser.  This is a great script.  Great work.', '2007-04-17 21:51:51', '2007-04-17 21:51:51', 15, 0),
(4, 4, 10, 'strawberries', '''woof woof'' said the big bad wolf', '2007-04-18 14:03:21', '2007-04-18 14:03:21', 15, 0),
(5, 3, 7, 'dragon', 'after 3 or 4 versions of the skin and lots of fights between the designer and us - the developers, it better be good. We really tried to make everything easy to customize: the php code, the html, css...', '2007-04-18 21:31:08', '2007-04-18 21:31:08', 15, 0),
(6, 3, 12, 'a_l_f', 'Kewl  \r\n        so now my filrts can have\r\n send \r\n(1) i have the weekend free wanna catch-up\r\n(2) im intrested \r\n(3) lets meet for coffee \r\n\r\nReply \r\n\r\n1. no thanks\r\n2. great im free also\r\n3. Sure lets meet \r\n\r\nLoving it thus far and it can only get better', '2007-04-19 05:13:36', '2007-04-19 05:13:36', 15, 0),
(7, 3, 10, 'strawberries', 'looking good, Dan, looking good!  And I see you''ve done a fair bit of work yesterday.\r\n\r\na_l_f, thanks for the flirt message.  I was alerted to your flirt via email thi morning.  Though when I clicked on the url link in the email, the webpage came up as an error....404 not found  index.php.\r\n\r\nBut when i went to the friendy site and signed in, i could go to my inbox and see your flirt message.  I guess the problem was to do with me not being signed in.\r\n\r\nJust a wee bug.\r\n', '2007-04-19 07:38:12', '2007-04-19 07:38:12', 15, 0),
(8, 5, 10, 'strawberries', 'excellent work, Dan.\r\n\r\nIt''s all coming together nicely.  It''s starting to snowball.  \r\n\r\nBTW You can see a small error that i had this morning (see your other blog thread for details).', '2007-04-19 07:40:39', '2007-04-19 07:40:39', 15, 0),
(9, 8, 7, 'dragon', 'howdy kid?', '2007-04-19 14:49:34', '2007-04-19 14:49:34', 15, 0),
(10, 8, 10, 'strawberries', 'howdo emma', '2007-04-19 14:52:01', '2007-04-19 14:52:01', 15, 0),
(11, 7, 10, 'strawberries', 'I wonder what the design resource kit will contain.\r\n\r\n', '2007-04-19 14:53:07', '2007-04-19 14:53:07', 15, 0),
(169, 18, 10, 'strawberries', 'can''t wait to see the admin.\r\n\r\nFluffy and moi are keen to have a walk on the dark side.', '2007-05-18 11:51:07', '2007-05-18 11:51:07', 15, 0),
(13, 8, 7, 'dragon', 'test blog comment notifs', '2007-04-19 15:27:10', '2007-04-19 15:27:10', 15, 0),
(14, 8, 7, 'dragon', 'test blog comment notifs take 2', '2007-04-19 19:53:42', '2007-04-19 19:53:42', 15, 0),
(15, 9, 10, 'strawberries', 'excellentae!\r\n\r\nI replied to  a_l_f''s test flirt yesterday morning, but i had an error page.  I''ll resend the message now.\r\n\r\nIt''s great that we now that get notified via email when any posts are made re our pictures or blogs.', '2007-04-19 23:43:46', '2007-04-19 23:43:46', 15, 0),
(16, 2, 10, 'strawberries', 'test message', '2007-04-20 00:10:19', '2007-04-20 00:10:19', 15, 0),
(17, 9, 13, 'johnboy2', 'Wow this bits cool, only just discovered it today.:)', '2007-04-20 01:52:19', '2007-04-20 01:52:19', 15, 0),
(18, 9, 13, 'johnboy2', 'Can a user edit or delete these comments they leave?', '2007-04-20 01:53:10', '2007-04-20 01:53:10', 15, 0),
(19, 9, 10, 'strawberries', 'no ability to edit that i can see, johnboy\r\n\r\nwonder if possible to see the time/date of posted messages.\r\n', '2007-04-20 02:50:11', '2007-04-20 02:50:11', 15, 0),
(20, 3, 12, 'a_l_f', 'LOL strawberries\r\n               yes just a wee bug or simple \r\njust not yet ready togo As the crew did say they would work on it each day :-)\r\nWhat would be great is a few simleys for the blog tooo :-)', '2007-04-20 03:35:55', '2007-04-20 03:35:55', 15, 0),
(21, 10, 7, 'dragon', 'Think of this like user stories with bells and whistles. Instead of having some predefined topics, the user creates his/her topics and keeps talking. Plus others can comment on their posts. In this application, they''re supposed to replace the user stories.', '2007-04-20 12:58:50', '2007-04-20 12:58:50', 15, 0),
(22, 3, 23, 'zooki', 'Hi, cool..', '2007-04-21 11:35:52', '2007-04-21 11:35:52', 15, 0),
(23, 3, 23, 'zooki', 'I got this error on Photos page when clicking Female photos (and similar happened with Male photos):\r\n\r\n: Undefined index: uid\r\n', '2007-04-21 11:48:08', '2007-04-21 11:48:08', 15, 0),
(26, 12, 7, 'dragon', 'test notifications :)', '2007-04-21 12:41:37', '2007-04-21 20:38:31', 15, 0),
(27, 9, 1, 'emma', 'test notifications', '2007-04-21 12:48:00', '2007-04-21 12:48:00', 15, 0),
(28, 10, 4, 'maverick', 'Yes a blog is very much like a forum, whereas they are both used as discussion platforms but presented in a different manner. The main difference is Blogs aren''t typically placed in categories or sub-categories based on topics the way forums are.\r\n\r\nBlogs are basically a personal journal that are typically used for publishing news and articles or for expressing your opinions or observations about a subject, which could be political statements or views,  providing tutorials, or simply telling the world all about your favorite movie star or musical group.\r\n\r\nIn a way it''s sort of like each member having their own personal mini forum which they are in total control of the topics.', '2007-04-21 15:44:07', '2007-04-21 15:44:07', 15, 0),
(29, 12, 0, 'guest', 'asdads', '2007-04-21 20:35:26', '2007-04-21 20:35:26', 15, 0),
(30, 11, 7, 'dragon', 'Oh, good thing you told us, I was already missing my coffee :P', '2007-04-21 21:07:30', '2007-04-21 21:07:30', 15, 0),
(24, 3, 23, 'zooki', 'There needs to be a way of deleting posts ! :) Perhaps i should have sent my last post as a Private Message... sorry', '2007-04-21 11:48:59', '2007-04-21 11:48:59', 15, 0),
(25, 11, 23, 'zooki', 'BTW, its just a test blog... lol.', '2007-04-21 11:59:43', '2007-04-21 11:59:43', 15, 0),
(31, 10, 11, 'johnboy', 'OK I understand now about the blogs, that seems really cool feature, but call me dumb, but I can''t figure out how to put a blog on the mainpage of FRIENDLY', '2007-04-21 23:24:25', '2007-04-21 23:24:25', 15, 0),
(32, 13, 10, 'strawberries', 'late here and wine onboard...but let''s see....maybe:\r\n\r\nin My Settings\r\nhave you added:\r\nHow often do you want to receive your search matches? (days) \r\n\r\nin My Photos\r\nhave you added number of results to show?\r\nResults to show: 6 12 24 48\r\n \r\nin My Connections \r\nI now see nice gallery lists of:\r\nFriends\r\nFavorites\r\n\r\nin My Saved Searches \r\nLatest members is now in the left hand menu....clicking it brings up all the newest members\r\n\r\nthere is RSS Feed on front page - with little dotted line between articles\r\n\r\non the PHOTOS tab, there is gallery listed aspects, i.e.\r\nNewest Photos\r\nMost Voted Photos\r\nMost Discussed Photos\r\n\r\ni''ve probably missed something big....but very late here.', '2007-04-22 03:13:39', '2007-04-22 03:13:39', 15, 0),
(33, 13, 10, 'strawberries', 'i also suspect that my saved search, i.e. those saved search criteria, are now populating the results that I see across other pages\r\n\r\nI must recheck this in the morning.\r\n\r\n', '2007-04-22 03:31:05', '2007-04-22 03:31:05', 15, 0),
(34, 13, 7, 'dragon', 'These were all there from the start so try again :)\n\nLast edited by dragon on 2007-04-22 17:27:40 GMT', '2007-04-22 06:25:18', '2007-04-22 17:27:40', 15, 0),
(35, 10, 4, 'maverick', 'You ain&#039;t dumb johnboy, you have no control as a user for your blog appearing on the mainpage. Blogs appear randomly by most popular or most recent, etc. The reason you may not be seeing it on the mainpage could be because of your browser cache. I had this problem, I wasn&#039;t even seeing any of the blogs on the mainpage, so I tried with firefox and then they appeared, then I realized it was a caching issue with IE.\r\n\r\nCaching is a good thing, however it can cause problems on pages where the content is frequently updated. I don&#039;t know if Dan knows of any server side tricks to address this besides using meta no-cache statements such as \r\n<META HTTP-EQUIV="Pragma" CONTENT="no-cache">\r\n<META HTTP-EQUIV="Expires" CONTENT="-1">\r\n\r\n', '2007-04-22 09:32:31', '2007-04-22 10:09:19', 15, 0),
(36, 13, 10, 'strawberries', 'LOL  \r\n\r\ndon''t surf drunk i guess is my future motto here\r\n\r\n(well at least don''t POST while drunk anyroads)  :)', '2007-04-22 10:24:22', '2007-04-22 10:24:22', 15, 0),
(37, 13, 4, 'maverick', 'I&#039;ll take a guess.\r\n\r\nIn the photo section you can now browse by Men or Women.\r\n\r\nIn the Blogs section you added a search field box at the top.\r\n\r\nAnd the members online search feature now works under the search tab\r\n\r\nand the list of friends now shows up in a members profile', '2007-04-22 10:24:51', '2007-04-22 10:53:23', 15, 0),
(38, 13, 10, 'strawberries', 'just after looking at the Regiustration page - and I now see it is different from when i first registered (last Monday).\r\n\r\nTo sign-up, a person has to Verify registration by entering the random characaters that the site throws up.....thus avoiding robots/spammers.\r\n\r\nAnd a registrant also has to agree, by checking a button, to the terms and conditions (which can be clicked to pop-up)\r\n', '2007-04-22 10:28:24', '2007-04-22 10:28:24', 15, 0),
(39, 13, 10, 'strawberries', 'good spot Maverick, re the search box now at the top of the BLOGS page.\r\n', '2007-04-22 10:30:07', '2007-04-22 10:30:07', 15, 0),
(40, 13, 10, 'strawberries', 'Online Members, when I click it here, shows *no-one* online.  I expected to see maverick online at least.', '2007-04-22 10:31:33', '2007-04-22 10:31:33', 15, 0),
(41, 13, 4, 'maverick', 'Hey strawberries, you showed up when I clicked the "Online Members" link, that''s how I knew it now works, never did before.', '2007-04-22 10:37:53', '2007-04-22 10:37:53', 15, 0),
(42, 13, 10, 'strawberries', 'ahhhhh, maybe\r\n\r\npeople can now EDIT their blog posts?\r\n\r\n\n\nLast edited by strawberries on 2007-04-22 18:11:52 GMT', '2007-04-22 11:06:52', '2007-04-22 18:11:52', 15, 0),
(44, 13, 10, 'strawberries', 'ahhhhh, maybe\r\n\r\npeople can now EDIT their blog posts?', '2007-04-22 18:12:14', '2007-04-22 18:12:14', 15, 0),
(43, 13, 7, 'dragon', 'Man, right under your nose and you don''t see it. Unless you have an old cached page or something though I doubt it.\r\nSo ok, I''ll tell you. In the next blog post.', '2007-04-22 17:29:05', '2007-04-22 17:29:05', 15, 0),
(45, 13, 11, 'johnboy', 'In MY PHOTOS you added &#039;change&#039; and &#039;Delete&#039; buttons?????\r\n\r\nAlso ''Last edited by'' is on the end of blogs when you edit them\r\n\r\nLast edited by johnboy on 2007-04-23 04:10:38 GMT\n\nLast edited by johnboy on 2007-04-23 04:42:08 GMT', '2007-04-23 04:09:49', '2007-04-23 04:42:08', 15, 0),
(46, 13, 11, 'johnboy', 'I wonder if you can delete them...... testing testing\r\n\r\nI thought maybe you could by using the editing button and taking out all the text.\r\n\r\nIt didn''t work :(\n\nLast edited by johnboy on 2007-04-23 04:45:09 GMT', '2007-04-23 04:43:13', '2007-04-23 04:45:09', 15, 0),
(47, 10, 11, 'johnboy', 'It must have been my cache as I see my blog  now. Before I could not see it in either Firefox or IE.', '2007-04-23 04:50:08', '2007-04-23 04:50:08', 15, 0),
(48, 13, 11, 'johnboy', 'What is the difference between friends and favourites? The only difference I can see is you have to get permission to be a friend.\r\nWhat is the advantage of having 2 similar categories instead of one?', '2007-04-23 05:10:25', '2007-04-23 05:10:25', 15, 0),
(49, 13, 10, 'strawberries', 'ok Dan, i think it''s safe to say that we give up!  LOL\r\n\r\nStop teasing - tell us what it is.  :3)\r\n', '2007-04-23 20:13:48', '2007-04-23 20:13:48', 15, 0),
(58, 2, 10, 'strawberries', 'new test', '2007-04-24 20:55:48', '2007-04-24 20:55:48', 15, 0),
(51, 13, 4, 'maverick', 'Hi Johnboy; The difference between friends and favorites is, people in your friends list is a buddy list of people you are actual friends with or connect or chat with on a regular basis. Whereas favorites are like bookmarking a member, say for example you''re browsing profiles and you come across a member you might be interested in, you can add them to your favorites list for future reference and continue browsing.\n\nLast edited by maverick on 2007-04-23 23:14:35 GMT', '2007-04-23 23:11:41', '2007-04-23 23:14:35', 15, 0),
(52, 13, 4, 'maverick', 'This brings up a question regarding the friends list being openly displayed in a member''s profile, this is cool for SNS or friendship based sites but may not be advantageous for a dating type site. It&#039;s quite common for members to be chatting and feeling out multiple potential candidates and it might not look so good if someone you may be interested in sees you''ve got a harem going on. You could easily get branded as being player. So is this something that can be hidden or turned off, either globally through the admin or by individual user preferences?\n\nLast edited by maverick on 2007-04-23 23:35:21 GMT', '2007-04-23 23:33:58', '2007-04-23 23:35:21', 15, 0),
(53, 13, 10, 'strawberries', 'Maverick - i was gonna air the same feelings too regarding one&#039;s friends list being openly displayed to anyone looking at your profile.&nbsp;&nbsp;\r\n\r\nI do know of one massive dating site that does allow such a thing. But I am not comfortable with it at all.&nbsp;&nbsp; \r\n\r\nA person&#039;s friends list is their own private area.&nbsp;&nbsp;I don&#039;t think it should be viewable by every person who is on the website.\r\n\n\nLast edited by strawberries on 2007-04-24 03:19:30 GMT', '2007-04-24 00:50:38', '2007-04-24 03:19:30', 15, 0),
(55, 13, 7, 'dragon', 'Not a problem,      it is easy to turn it off if you don''t like it. It was created with "friend of a friend" type of sites in mind where if you like someone you might try to see if a common friend can introduce you to her.\n\nLast edited by dragon on 2007-04-24 14:58:23 GMT', '2007-04-24 06:08:05', '2007-04-24 14:58:23', 15, 0),
(56, 13, 10, 'strawberries', 'test comment\r\n\r\nI will try editting text in the paragraphbelow, to see if i can replciate funny text that came up last night, after an edit.\r\n\r\nrandom text to edit goes here. This is the very first paragraph of this experient.&nbsp;&nbsp;More paragraphs to follow.\r\n\r\na second line of randon text appears here.\r\n\r\nand a third line.\n\nLast edited by strawberries on 2007-04-24 12:51:00 GMT', '2007-04-24 12:49:37', '2007-04-24 12:51:00', 15, 0),
(57, 13, 10, 'strawberries', 'ok, I merely had to click on the edit button - I didn''t have to change a thing in the text.\r\n\r\nUp, in my text box, came the gobblygook amongst my old text.  I saved and posted (see above).\r\n', '2007-04-24 12:53:27', '2007-04-24 12:53:27', 15, 0),
(59, 14, 10, 'strawberries', 'excellent work!\r\n\r\nMaverick, Johnboy and myself were pretty close with our guesses! :)\r\n\r\nThe one thing I did notice there now, and i wonder if it is entirely necessary - is the seconds (in the time of each post).  Would it benot be  sufficient to have just the hours and minutes of the post.\r\n\r\ne.g. 14:47 PM PDT\r\nas opposed to:  14:47:12 PM PDT', '2007-04-24 21:00:03', '2007-04-24 21:00:03', 15, 0),
(60, 14, 10, 'strawberries', 'hmmm, tho it has posted above post - when i hit send, i had an error screen on blog\r\n\r\nhttp://www.datemill.com/friendy/processors/blog_comments.php\r\n\r\n: Undefined index: comment_id\r\nLast query run: SELECT `fk_user_id` FROM `dsb_blog_posts` WHERE `post_id`=''14''\r\n\r\nArray\r\n(\r\n    [0] => Array\r\n        (\r\n            [function] => general_error\r\n        )\r\n\r\n    [1] => Array\r\n        (\r\n            [file] => /var/www/htdocs/datemill/html/friendy/processors/blog_comments.php\r\n            [line] => 112\r\n            [function] => unknown\r\n        )\r\n\r\n)\n\nLast edited by strawberries on 2007-04-24 21:02:34 GMT', '2007-04-24 21:01:23', '2007-04-24 21:02:34', 15, 0),
(61, 14, 7, 'dragon', 'Fixed. Just a file that I forgot to upload.\r\n\r\nThe way the times and dates looks can be customized in your settings. The application is using a general setting now, regardless of what you set there but this will be fixed in an update soon.\r\n\r\nLast edited by dragon on 2007-04-24 21:26:52 GMT\n\nLast edited by dragon on 2007-05-07 21:17:46 GMT', '2007-04-24 21:21:25', '2007-05-07 21:17:46', 15, 0),
(62, 14, 10, 'strawberries', 'Great stuff, Dan.\r\n\r\nThe script is starting to look really good.\r\n\r\nI like the new thing that you have added - tho i will have to have a think as to what I can include on it, on a regular basis.\r\n\r\nOK, something else that I thought of:\r\nOn the home page, a person can see the: \r\n\r\nNewest Members\r\nand also\r\nNewest Photos\r\n\r\nI wonder if it would also be good to have a further row/listing showing new members that meet the person''s saved search criteria?  \r\n\r\nThat would provide them with immediate interest on going to that home page each day.\r\n\r\nAnd/or maybe they could get a periodic email that the site sends, telling them of new listings that meet their criteria.', '2007-04-24 22:23:25', '2007-04-24 22:23:25', 15, 0),
(63, 14, 4, 'maverick', 'Coming along nicely Dan :)\r\n\r\nSo far I like how you''re keeping things simple, clean and well organized, makes it real user friendly. Also like the news feature on the members home page, can actually see myself using this instead of mass emailing newsletters, I''ve personally never been big on sending out newsletters.', '2007-04-24 22:28:13', '2007-04-24 22:28:13', 15, 0),
(64, 14, 10, 'strawberries', 'i agree maverick.\r\n\r\nNewsletters are not my thing either.\r\n\r\nIt''s far better to have a place to say it on the website instead.', '2007-04-24 23:01:41', '2007-04-24 23:01:41', 15, 0),
(65, 14, 11, 'johnboy', 'Ok I think the site has some great features compared to the old DSB, BUT, I am so lost with the navigation and how to ultilise all the great features.\r\n\r\nAt the top there are 4 sections with Tabs, and when you go into each section, the menu on the left hand frame changes to whats relevant in that section. Thats a great idea, EXCEPT for people like me with bad memory and short attention span.\r\n\r\nEG: I will be in say the photos section, and wonder how I can get back to the whos online page. To do this I have to click on SEARCH(If I remember that its in that section) and then Click on Whos online.\r\n\r\nIs it possible to have some sort of navigation that shows you ALL the sections of the site, or even a site map, or even better a help section to show you how to use all the features.\r\n\r\nI know the site is still in its infancy and more will be done soon, I just really want to know if I(by paying Dan) can impliment these features.', '2007-04-25 02:21:04', '2007-04-25 02:21:04', 15, 0),
(66, 14, 4, 'maverick', 'Hi Johnboy:\r\n\r\nI hear what you''re saying about the navigation, but it''s pretty easy to add items or create your own navigation system. For most things, when you click the link and go to that page, just use the URL that appears in your address field and add it to your navigation system. The default navigation code should be fairly easy to locate in the files, it''s usually in the index or  header file (either a php or html file depending on the template system). In DSB 2 look in the index.html file under \\skins\\basic.\r\n\r\nOnce you find it, it''s pretty easy to figure out the structure. Then just have to copy the section that creates the blog tab for example and paste it below that and rename the tab part to "Who''s Online" and change the URL. This should then create a new tab link.\n\nLast edited by maverick on 2007-04-25 05:27:01 GMT', '2007-04-25 05:10:17', '2007-04-25 05:27:01', 15, 0),
(67, 14, 7, 'dragon', '[quote]I wonder if it would also be good to have a further row/listing showing new members that meet the person''s saved search criteria?[/quote]\r\n\r\nGreat idea.\r\n\r\n[quote]And/or maybe they could get a periodic email that the site sends, telling them of new listings that meet their criteria.[/quote]\r\n\r\nThat''s coming based on the alert checkbox in my_searches.\r\n\r\n[quote] I''ve personally never been big on sending out newsletters.[/quote]\r\n\r\nNewsletters have their place. A little reminder that the site exists and there are a lot of new matches for them is very useful in keeping members coming back. For example I wouldn''t go to sitepoint just to see what''s new, instead I rely on ''new message'' notifications.\r\n\r\n[quote]I am so lost with the navigation and how to ultilise all the great features[/quote]\r\n\r\nWe were thinking of putting the ''My Account'' menu on all pages for easy access to account features.\r\nBut as Maverick said it''s more than easy to add links somewhere or tabs. I added the who''s online tab for you to see - it was a line of html code.', '2007-04-25 07:28:26', '2007-04-25 07:28:26', 15, 0),
(68, 14, 10, 'strawberries', 'Great work, Dan.  This is turning into a helluva awesome script. It is so clean and so functional.\r\n\r\nI do agree with Johnboy - in that i too have trouble remembering where some of the links are at - i.e. what pages they are on.\r\n\r\nGlad you liked the idea for a third row, to compliment NEWEST MEMBERS and NEWEST PHOTOS.  I don''t know what you''d call that third row - maybe NEWEST MATCHES\r\n\r\nI claim my prize!!  :)', '2007-04-25 10:44:19', '2007-04-25 10:44:19', 15, 0),
(69, 14, 4, 'maverick', 'I agree that newsletters have their place, for years I used to send out newsletters and for a time they worked very well for creating stickiness and increasing sales, but once the anti-spam software began to become more prominent and sophisticated, it started to become more of a pain in the a** than it was worth, so we just quit doing it. Part of it too was, I think I just kind''a got burned out from creating and sending out mass newsletters.\r\n\r\nHowever, things like allowing members to choose to receive notifications when they get new messages etc. is a real good idea.\r\n\r\nI think putting the ''My Account'' menu on all pages is an excellent idea, and something I''d actually been pondering over for the last few days. Maybe also include where the ''My Messages'' link is, something that lets them know when they have new messages, maybe even a simple blinking gif icon that says ''New Messages''. By having the ''My Account'' menu on all pages along with a notification or alert when a new messages arrives allows users to immediately know when they get a message, no matter where they are on the site.\n\nLast edited by maverick on 2007-04-25 11:19:41 GMT', '2007-04-25 10:47:42', '2007-04-25 11:19:41', 15, 0),
(70, 14, 10, 'strawberries', 'Yes, perhaps a simple blinking icon, to alert the user immediately when a new message arrives, would be good.  \r\n\r\nThen again, I already get an immediate alert here via my email (immediate pop up from my task bar).\r\n\r\nre website ''stickiness'' - I think the automated sending out (if turned on) of new messages (re a person''s blog, individual picture comments, flirt, etc) as well as the new feature Dan mentioned (regular email alerting the user of newest matches) is gonna mean people are gonna keep coming back.\r\n\r\nI am really excited about this script.', '2007-04-25 13:02:34', '2007-04-25 13:02:34', 15, 0),
(71, 4, 10, 'strawberries', 'no scary-de-catz here', '2007-04-25 14:21:31', '2007-04-25 14:21:31', 15, 0),
(72, 14, 4, 'maverick', '[quote]Then again, I already get an immediate alert here via my email (immediate pop up from my task bar).[/quote]\r\n\r\nYes this is true, however you''re going to get a good portion of your members signing up with web mail accounts (non POP3) such as Yahoo and Hotmail etc. where they have to login to see if they have mail, which they may only check once or twice a day.', '2007-04-25 17:59:38', '2007-04-25 17:59:38', 15, 0),
(73, 14, 10, 'strawberries', 'true, maverick.\r\n\r\nmy account is with yahoo.  I have yahoo messenger going most of the day, thus i always get a pop-up when new mail arrives.\r\n\r\nbut i know what you mean....some folks will have other free email accounts, that they sign up with, and they won''t check them that much.\n\nLast edited by strawberries on 2007-04-25 20:59:46 GMT', '2007-04-25 20:27:17', '2007-04-25 20:59:46', 15, 0),
(75, 15, 10, 'strawberries', 'hi there, cocacola!\r\n\r\nGreetings from [u]Ireland[/u]!', '2007-04-25 22:30:46', '2007-04-25 22:30:46', 15, 0),
(76, 12, 23, 'zooki', 'notifications work', '2007-04-26 10:45:41', '2007-04-26 10:45:41', 15, 0),
(77, 14, 10, 'strawberries', 'A TINY BUG\r\n\r\nnot that important - but when i get a message, and go to reply.  The person''s quoted text  e.g. [quote]Hi strawberries, how''s it going![/quote]\r\n\r\nalways starts on the second line.  The top line is blank for some reason.', '2007-04-26 11:25:41', '2007-04-26 11:25:41', 15, 0),
(78, 14, 7, 'dragon', '...and we thought it was a feature :P\r\nWe even thought of usability there: the subject field is selected by default and when you hit <tab> the cursor goes to the message body, above the quoted text.\r\n\r\nIn the regular email client we use to reply above the quoted text. The blank line is there to invite you to write above. Not a biggie if you''re not used to do it this way it can be changed.', '2007-04-26 12:06:39', '2007-04-26 12:06:39', 15, 0),
(79, 14, 10, 'strawberries', 'ahhhh I see....i always by default type BELOW quoted text.\r\n\r\nIt just doesn''t read correctly otherwise, in my opinion.  Things should follow on. imho\r\n\r\nI actually came across the most bizarre discussion board earlier this week.  It was a massive massive board, been going for many years, with tens of thousands of members, but the threads took me a minute or two to figure out.  For some reason, you had to read the thread back to front, bottom to top.  \r\n\r\ni.e. say for example a thread was 5 pages long, the original post that started the thread off, was at the bottom of page 5.  And you had to read upwards, and then backwards, onto page 4, 3, etc.  Strange strange setup.  I have seen thousands of forums in the last 15 years, but never one like that.  lol\n\nLast edited by strawberries on 2007-04-26 12:24:13 GMT', '2007-04-26 12:23:15', '2007-04-26 12:24:13', 15, 0),
(80, 14, 11, 'johnboy', 'Just personally, I hate replying underneath the original message, I prefer it at the top of the page, so its the 1st thing the recipient see''s. I cannot understand why people do it underneath. Some say so you can read the whole thing out later from top to bottom, But how often do you read all the replies once you have already read them?\r\n\r\nAlso thanks Maverick for the explaination about the links, much appreciated, and I have to agree with you about the blinking email icon, doesnt even have to be blinking, just have it somewhere on ALL pages that changes when there is an email.\r\neg: you have (0) emails                              you have (2) emails.\r\n\r\nTo Dan:\r\nI like the whos online tab :)\r\nand having the my account links on everypage is a great idea. Also I''m starting to like these blogs and can see they will be an excellent feature for my members.\r\n\r\nOne other thing..... How come you cannot see your own profile on the whos online page anymore? The old DSB does.\r\n\r\nOtherwise I say WOW!!! great new script and well worth the wait.', '2007-04-26 13:23:01', '2007-04-26 13:23:01', 15, 0),
(81, 14, 7, 'dragon', 'For a forum where people might not be there from the start of the discussion this is, indeed, a bad thing. \r\nFor email (which is supposed to be a dialogue between 2 people) I find it annoying to have to scroll down to read one''s answer because I already know what I said in my email, I don''t need to see it again in his/her reply. So I made a rule here - the customer support answers above quoted text.\r\nBut if you want I''ll write a tutorial on how to reply below the quoted text. :lol:', '2007-04-26 13:29:34', '2007-04-26 13:29:34', 15, 0),
(82, 14, 10, 'strawberries', 'friggin ell, I KNEW we shouldn''t have let the Romanians into the EU!  :-} :D :-D', '2007-04-26 18:55:50', '2007-04-26 18:55:50', 15, 0),
(83, 14, 7, 'dragon', 'Hey, now you can use words like #######, ####### and ####### :P', '2007-04-26 19:27:38', '2007-04-26 19:27:38', 15, 0),
(84, 14, 10, 'strawberries', 'LOL  \r\n\r\nDan, your english is better than mine.  Is english taught in schools there as the main second language?\r\n\r\nPS - I wish I had access to your emoticons - i had to go google for my previous post''s emoticon.', '2007-04-26 19:46:36', '2007-04-26 19:46:36', 15, 0),
(85, 14, 7, 'dragon', '>:( > : (\r\n:D : D \r\no.O o . O \r\n|o | o \r\n-.- - . - \r\n8) 8 ) \r\n:~( : ~ ( \r\n>:) > : ) \r\n:doh: : doh : \r\n<.< < . < \r\n:grr: : grr : \r\n^,^ ^ , ^ \r\n:h: : h : \r\n:huh: : huh : \r\n:lol: : lol : \r\n:x : x \r\n:, : , \r\n:O : O \r\n:r: : r : \r\n:( : ( \r\n:) : ) \r\n:t: : t : \r\n:P : P \r\n:u: : u : \r\n:w: : w : \r\n:. : . \r\n;) ; ) \r\n:!: : ! :\r\n\r\nSee the chars at the icon''s right. Just don''t use spaces between chars.\r\n\r\nEnglish is indeed main second language here. Almost everybody understands it and most of us speak ok.\n\nLast edited by dragon on 2007-04-26 20:30:26 GMT', '2007-04-26 20:22:43', '2007-04-26 20:30:26', 15, 0),
(86, 14, 10, 'strawberries', 'yee-haaaa!\r\n\r\n:!:\n\nLast edited by strawberries on 2007-04-26 20:28:23 GMT', '2007-04-26 20:27:21', '2007-04-26 20:28:23', 15, 0),
(87, 14, 10, 'strawberries', ':t:\n\nLast edited by strawberries on 2007-04-26 20:29:06 GMT', '2007-04-26 20:27:49', '2007-04-26 20:29:06', 15, 0),
(88, 14, 10, 'strawberries', ':!:', '2007-04-26 20:28:08', '2007-04-26 20:28:08', 15, 0),
(89, 14, 10, 'strawberries', 'Dan, with things preogressing so well, would it be amiss of me to ask that old question that you so dearly love?  :P\r\n\r\nWhen do you imagine you will release the script?\r\n\r\n<runs for cover behind maverick, traderjoe and johnboy>', '2007-04-27 10:55:43', '2007-04-27 10:55:43', 15, 0),
(90, 14, 7, 'dragon', 'Not counting an installer, docs and the backend (for license management and your-site-our-server interconnection ) I would say that in 2 weeks we could have a version that certain customers might be allowed to beta test.', '2007-04-27 11:57:49', '2007-04-27 11:57:49', 15, 0),
(91, 14, 10, 'strawberries', 'That''s excellent, Dan.  \r\n\r\nMay I ask if the instant messenger plugin is already made, or is still work-in-progress?\r\n\r\nAlso, with the new datemill, if your server is down, does it effect my ability to log-in and administer my own website?\r\n\r\nAlso, I was thinking today, re the NEWEST  rows - i.e. you have two so far - Newest Members, Newest Photos.\r\n\r\nI suggested a third last week - Newest Matches.  \r\n\r\nI was thinking today that we will probably need a fourth row for FEATURED MEMBERS.  i.e. those who will pay a premium to be on the front page of the site.\n\nLast edited by strawberries on 2007-04-27 14:01:06 GMT', '2007-04-27 14:00:27', '2007-04-27 14:01:06', 15, 0),
(92, 14, 7, 'dragon', '[quote]Also, with the new datemill, if your server is down, does it effect my ability to log-in and administer my own website?[/quote]\r\n\r\nNo way! We''re not control freaks. The communication is for news from us directly in you admin panel, to download new mods/addons without leaving your admin panel, to be announced if critical patches are available for you, etc.\r\nYou will not be able to read news if our server is down, but your site should function without problems.\r\n\r\nThe IM was created for a customer 6 months ago. We just need to update the code to work with Ritmo/Nexus/Friendy, polish it a little, add a couple of new things to it and release it.', '2007-04-27 14:35:02', '2007-04-27 14:35:02', 15, 0),
(93, 14, 10, 'strawberries', 'wooow, we can download new plug-ins and updates, from datemill, directly from our website''s admin.  Excellentae.\r\n\r\nWill plug-ins be free, Dan?\n\nLast edited by strawberries on 2007-04-27 14:51:25 GMT', '2007-04-27 14:49:39', '2007-04-27 14:51:25', 15, 0),
(94, 14, 10, 'strawberries', 'Another idea worth considering, is having a feature in the admin panel, that allows the webmaster to automatically put up temporary homepage (useful when there is a problem with the site, and/or he is updating it, or has it down for maintenance)\r\n\r\ne.g. a temp page saying something like ''sorry for the inconvenience, we''re doing some maintenance, back shortly''\r\n\r\njoomla has that feature.....very cool.', '2007-04-27 15:22:57', '2007-04-27 15:22:57', 15, 0),
(95, 14, 14, 'tpandtp', 'Not sure if this is an oversight, I have just noticed that there is no way to delete your account. ( Not that we would want to :) ) but some OTHER people do. *g*\r\nJust another tid bit that I have added to my message board that would be handy for blogs is a spell checkerrrr.\n\nLast edited by tpandtp on 2007-04-29 13:37:29 GMT', '2007-04-29 13:28:50', '2007-04-29 13:37:29', 15, 0),
(96, 9, 8, 'angkasa', 'I like that, cool Feature Dan. :)\n\nLast edited by angkasa on 2007-04-29 17:43:08 GMT', '2007-04-29 17:42:13', '2007-04-29 17:43:08', 15, 0),
(97, 3, 8, 'angkasa', 'COol, Hooray! Nice feature!', '2007-04-29 17:46:01', '2007-04-29 17:46:01', 15, 0),
(98, 15, 6, 'cocacola', 'hi from NJ USA', '2007-04-30 22:00:43', '2007-04-30 22:00:43', 15, 0),
(99, 14, 10, 'strawberries', 'wondering how things are progressing.', '2007-05-03 10:14:39', '2007-05-03 10:14:39', 15, 0),
(100, 14, 7, 'dragon', 'I heard things are going very well :) I am not in town now, that''s why you didn''t see any update on the demo site but we''re working on not so visible things anyway.\r\nI''ll update the site in the weekend, when I return. The admin part of the site should be available for demo soon.', '2007-05-03 18:59:49', '2007-05-03 18:59:49', 15, 0),
(101, 14, 10, 'strawberries', 'It''s looking so good, Dan.  You have all been doing great work.  I imagine once the admin part of the site is up and running, you are almost on the home straight (as we say here in the UK).\r\n\r\nI love how ''clean'' the whole script looks.  You have built in so many great features, and yet it looks very simple (because of good layout and pre-planning).  \r\n\r\nI wonder how many members the script could cope with.....i.e. registrants/users.  BTW, does it use one database or two?  (thinking maybe a second database for the blogs section)', '2007-05-04 07:52:23', '2007-05-04 07:52:23', 15, 0),
(102, 14, 16, 'tester', 'Hi all. I just figured the forum moved into the blog here:)\r\n\r\nI have a search question. Well 2 questions:)\r\nfirstly can you make a "any" option in the education drop down choices, and also in the     marital status drop down,?\r\n\r\nSecondly how about an option for a simple search by basic criteria such as age,location,gender so that the user does not have to fill in the whole form on the advanced search each time they want to find a search result.\r\n\r\nThanks and regards\r\nClive', '2007-05-05 18:21:25', '2007-05-05 18:21:25', 15, 0),
(103, 14, 4, 'maverick', 'Hi Clive;\r\n\r\nI agree that it would be a good idea to have both a basic/quick search along with an advanced search appear by default when you click the main search button. Although it would be fairly easy to copy the snip of code for the basic search from the main home page and add it pretty much anywhere you want.\r\n\r\nOne thing the blogs needs to have is only so many comments loaded per page, maybe somewhere in the area of 24 - 36 ?\n\nLast edited by maverick on 2007-05-05 23:14:03 GMT', '2007-05-05 22:44:09', '2007-05-05 23:14:03', 15, 0),
(104, 14, 10, 'strawberries', 'yes, the search page isn''t that useful as it stands.\r\n\r\nfor example, i don''t want to have to enter in an ''eye color'' choice.  \r\n\r\ni think, like maverick, that there should be a simple search thing on the same page, above the more advanced search feature.  \r\n\r\nAnd rather than choose an eye color, from a drop down, have any features/choice options as boxes......thus if you leave some aspect  blank (e.g. eye colour), that feature gets ignored in the search.', '2007-05-06 12:05:28', '2007-05-06 12:05:28', 15, 0),
(105, 14, 7, 'dragon', 'If the field is searchable with a select box or with multiple checkboxes it is up to the admin. It''s a matter of changing the search type in the field definition menu in the admin panel.\r\nAs for the ''ANY'' option being available in the search select boxes, that''s indeed an omission and will be fixed before the release.', '2007-05-06 13:29:36', '2007-05-06 13:29:36', 15, 0),
(106, 14, 17, 'shadowmachine', 'Have space, will beta.\r\n\r\nI did some validation and compatibility testing in a past life. I''d love to beta test this script when you get ready. Looks good so far.\r\n\r\nInteresting comments posted here everybody. I''m looking forward to the release.', '2007-05-07 09:58:42', '2007-05-07 09:58:42', 15, 0),
(107, 14, 10, 'strawberries', 'really looking forward to this new version.    It is so much more advanced than the previous version (and it was good).  It will be wonderful if people can come up with plugins and ideas for plugins, in the future.  Keen to see what the instant messenger will look like.', '2007-05-07 10:24:07', '2007-05-07 10:24:07', 15, 0),
(108, 14, 10, 'strawberries', 'just reminds me - is there any way where when you post your comment, that the newly reloaded page automaticlaly scrolls down to  your new comment.  I have this on my vanilla chat script - very cool.  \r\n\r\nOtherwise at the present time, I have to scroll down myself to see my newly added comment.', '2007-05-07 10:26:12', '2007-05-07 10:26:12', 15, 0),
(109, 14, 7, 'dragon', 'It''s already implemented, you will get it when I update the site...probably tonight', '2007-05-07 10:34:12', '2007-05-07 10:34:12', 15, 0),
(110, 14, 10, 'strawberries', 'excellentae!\r\n\r\nYou mentioned the admin section a few days ago, Dan.  Is it close to being ready?', '2007-05-07 12:10:35', '2007-05-07 12:10:35', 15, 0),
(111, 4, 10, 'strawberries', 'test', '2007-05-08 02:22:27', '2007-05-08 02:22:27', 15, 0),
(112, 4, 10, 'strawberries', 'test 2', '2007-05-08 02:22:45', '2007-05-08 02:22:45', 15, 0),
(113, 14, 10, 'strawberries', '[quote]Dan said: As for the ''ANY'' option being available in the search select boxes, that''s indeed an omission and will be fixed before the release.[/quote]\r\nit is even better to have it that if none of the boxes are ticked in a section (e.g. for eye colour), that that counts as ANY.....or else that aspect is not included in the search automatically (i.e. when you don''t click a box)\n\nLast edited by strawberries on 2007-05-09 16:54:03 GMT', '2007-05-09 16:53:26', '2007-05-09 16:54:03', 15, 0),
(114, 14, 7, 'dragon', 'if nothing is checked in a checkbox set, that field is not included in the search but I was talking about selects (drop down fields).\r\n\r\nI''m trying for 2 days now to post a new blog saying that I''ve updated the demo site but had no time yet. So I''ve updated the demo site :)', '2007-05-09 17:01:38', '2007-05-09 17:01:38', 15, 0),
(115, 14, 10, 'strawberries', 'apologies, I understand you now  re needing an [b]ANY[/b] choice in the ''drop down fields''\r\n\r\nyeee-haaaa re the new update.  Will we notice any obvious changes?  Thinking maybe just minor bug fixes.\r\n\r\nHow is the admin part of the script coming along, if I may ask.', '2007-05-09 18:49:01', '2007-05-09 18:49:01', 15, 0),
(116, 14, 10, 'strawberries', 'The [b]WHO''S ONLINE[/b] page always comes up blank for me i.e.\r\n\r\n[quote][u]Search Results[/u]\r\n\r\nNo results found matching your search criteria. Please refine your search and try again. [/quote]\r\n\r\nThis is obviously linked to my saved search features.  But I deliberately kept these very broad.  \r\n\r\nI wonder how useful the [b]Who''s Online[/b] page will be, given that it is cumbersome to edit the saved features (i.e. you have to go off to the search page, and create a new search, save it, which then has to be made the default.  \r\n\r\nAnd then you can come back again to the [b]Who''s Online[/b] page to see if you have any matches online.  It seems very cumbersome and I''m not sure how useful it is, as it stands.', '2007-05-11 08:28:51', '2007-05-11 08:28:51', 15, 0),
(117, 14, 7, 'dragon', 'The who''s online search does not depend on your preferences. It shows who is online now, except for yourself.\r\nFor example it shows me that strawberries is online right now ;)\r\nAnd now maverick\n\nLast edited by dragon on 2007-05-11 08:42:30 GMT', '2007-05-11 08:41:25', '2007-05-11 08:42:30', 15, 0),
(118, 14, 4, 'maverick', 'I figured it would work as you mentioned, however I don''t see who''s online, all I see is what stawberries is seeing ... [quote]No results found matching your search criteria. Please refine your search and try again.[/quote]\r\n\r\nIt seemed to work for me a couple times a few weeks ago.', '2007-05-11 08:49:26', '2007-05-11 08:49:26', 15, 0),
(119, 14, 4, 'maverick', 'Noticed you implemented Thickbox, so far I''ve only noticed it being used for the reason photos are rejected. I''m using Greybox for the pop ups on my development, for things like the TOS, Site Policies, Contact form etc.\r\n\r\nIf you plan on using Thickbox for other things, such as photo galleries etc. maybe I''ll switch and implement Thickbox for what I''ve already done to keep things consistent. I looked at Thickbox about a year ago but it lacked some features that Greybox had at the time.\n\nLast edited by maverick on 2007-05-11 09:20:08 GMT', '2007-05-11 09:06:42', '2007-05-11 09:20:08', 15, 0),
(120, 14, 7, 'dragon', '[quote]Noticed you implemented Thickbox[/quote]\r\nYeah, the reason we chose thickbox is because it is based on the jquery library which we all love. \r\nPersonally, I am not a big fan of ajax or javascript effects because they depend on javascript being enabled in the browser but I wanted to prove that the skin can be customized to look and behave just the way you want.\r\n\r\nRegarding the who''s online - check now you still can''t see anyone?', '2007-05-11 09:21:07', '2007-05-11 09:21:07', 15, 0),
(121, 14, 10, 'strawberries', 'no, still nobody online :(\r\n\r\n(Maverick, I too saw you listed as online once, but that was 2 weeks ago)\r\n\r\nThe feature just always shows nobody online.', '2007-05-11 09:41:28', '2007-05-11 09:41:28', 15, 0),
(122, 14, 10, 'strawberries', 'ahhh, success - MAVERICK is now shown as online!!!\r\n\r\nWhat did you change, Dan?', '2007-05-11 09:43:50', '2007-05-11 09:43:50', 15, 0),
(123, 14, 4, 'maverick', 'Ya it seems to be working for me now. Strawberries, try refreshing your browser after you go to the who''s online page.', '2007-05-11 09:45:28', '2007-05-11 09:45:28', 15, 0),
(124, 14, 7, 'dragon', '[quote]What did you change, Dan?[/quote]\r\n\r\nThe caching of the page and some timeout parameters...', '2007-05-11 09:49:42', '2007-05-11 09:49:42', 15, 0),
(125, 14, 4, 'maverick', 'I agree, I try to avoid using javascript toys as much as possible. The only thing I liked about using the Thickbox or Greybox as a pop solution is they tend not to be affected by browser popup blockers and it also puts and keeps the popup in the forefront and closes if you click on the main page rather than staying open in the background as second browser window.', '2007-05-11 10:23:54', '2007-05-11 10:23:54', 15, 0),
(126, 14, 10, 'strawberries', 'thickbox and greybox are great solutions.\r\n\r\nRegarding WHO''s ONLINE though - I''m not convinced as to how beneficial it is going to be.  \r\n\r\nIf I have hundreds, or one or two thousand members...I want to see online people who I might like to send a message to e.g. female, 30 to 40 years old.  I don''t have any need or interest in wading through page after page of males who are online.  They have no interest to me.\r\n\r\nI had a look over on the Advanced Search page, and it also doesn''t allow me to search for online people (e.g. females) either.\r\n\r\nI am probably missing something obvious here (usual for me)', '2007-05-11 15:28:49', '2007-05-11 15:28:49', 15, 0),
(127, 17, 10, 'strawberries', 'That sounded like an awful lot of work......and very mundane and time consuming too.  I like being creative too, and can empathise with you on such a mindnumbingly boring activity.\r\n\r\nGood job you spotted the html tags aspect.  It is crucial that the code can be editted in the likes of Dreamweaver.\r\n\r\nWow, it will be great if you have a preview version of the ADMIN section next week.  And then we can get into the beta versions!  Yeee-haaaa!', '2007-05-12 07:52:00', '2007-05-12 07:52:00', 15, 0),
(128, 17, 8, 'angkasa', 'Yee-haaaaaaaaa too.hehehe', '2007-05-13 17:52:34', '2007-05-13 17:52:34', 15, 0),
(129, 17, 10, 'strawberries', 'all quiet on the western front', '2007-05-15 08:55:18', '2007-05-15 08:55:18', 15, 0),
(130, 15, 10, 'strawberries', 'wow, NJ....i was in the USA twice....and the first time, in the 1980s, i stayed/worked for a week or so down in wildwood NJ!', '2007-05-15 09:37:11', '2007-05-15 09:37:11', 15, 0),
(131, 17, 7, 'dragon', 'We''re in east so you can''t be talking about us :)\r\nWe keep updating the site, fixing various things, etc. I bet you haven''t noticed the message templates (management of and using) - when you send messages.\r\n\r\nThe plan is still on, this week we''ll open the demo of the admin side to the public.\r\nIt''s going to be a bit more difficult for us to periodically update it because I want to disable most of the processors in the admin - I don''t want your tests to break anything.', '2007-05-15 09:41:23', '2007-05-15 09:41:23', 15, 0),
(132, 17, 10, 'strawberries', 'we Northern Ireland folks like to break things and rough things up!  lol\r\n\r\nI had a look there now at the messages section.  I do see a Messages Template thingie.  Though I am unsure how to create a message template.  I also see a message filter thingie (spam).  I can''t recall if that is new too.\r\n\r\nIt will be brilliant if the Admin section is open this week.  Yeee-haaaaa.', '2007-05-15 16:22:18', '2007-05-15 16:22:18', 15, 0),
(133, 17, 7, 'dragon', 'when you send a message to someone you have the option of saving the message you wrote as a ''message template''. Next time when you send a message you could get one of the saved templates, change some stuff and send that.\r\nIt''s like a breed between messages and flirts.', '2007-05-15 16:35:30', '2007-05-15 16:35:30', 15, 0),
(134, 17, 10, 'strawberries', 'That is so cool!!!!!  Great idea, Dan!\r\n\r\nI wonder if people will easily catch on to what it is about, and how to use it.', '2007-05-15 20:38:52', '2007-05-15 20:38:52', 15, 0),
(135, 17, 4, 'maverick', 'I think the message templates is a cool feature but it should be called something like "canned messages" or "canned responses" which are the common terms used for stored SMS messages and for help desk and chat responses. The word template will confuse many users as most people relate to templates as being designs or layouts, which I know is technically what you''re doing when saving a message, but most people have become rather hard wired in their interpretations of certain common terms.\n\nLast edited by maverick on 2007-05-15 21:49:09 GMT', '2007-05-15 21:37:14', '2007-05-15 21:49:09', 15, 0),
(136, 17, 4, 'maverick', 'Not sure if you have any plans for this or not, but something that shows that a member is a premium member?\r\n\r\nI''ve seen this on a few sites and what I like about it is that it allows members to identify which members have premium status. I believe it also can help in encouraging free members to upgrade, it makes some people feel special or privileged.\r\n\r\nHere''s a screenshot showing an example of what I mean http://desktopmates.com/temp2/screenshot.jpg', '2007-05-15 22:07:42', '2007-05-15 22:07:42', 15, 0),
(137, 17, 10, 'strawberries', 'Yes, template in this context is a confusing word, maverick.  It makes me scared to tweak or mess with anything, in case I screw up.  \r\n\r\nSomething like ''canned messages'' sounds better.  Tho I admit that ''canned'' must be an american word - i can guess at it;s meaning....but it is not widely known or used in the UK/Europe.', '2007-05-15 22:33:15', '2007-05-15 22:33:15', 15, 0),
(138, 17, 10, 'strawberries', 'The premium member icon is a very good idea.\r\n\r\nI have seen a similar membership status on another major site - where they have ''gold membership''; ''silver membership'' and ''standard membership''.  \r\n\r\nThey don''t have icons, but you can see on each person''s profile page (and also on search results pages) what status they enjoy on the site. \r\n\r\nI think that is a very good idea.  It encourages you to wonder what extra benefits they get with their status (and to maybe upgrade).', '2007-05-15 22:36:48', '2007-05-15 22:36:48', 15, 0);
INSERT INTO `dsb_blog_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`, `processed`) VALUES 
(139, 17, 4, 'maverick', 'Never really considered if canned was an American term or not, but it very well could be. I know SMS text message services in North America uses the term canned for stored text messages. I was thinking it should be something generic or universally used as a term for stored messages and assumed "canned" was rather universal. What term do they use for stored or saved text messages in Europe? \r\n\r\nMost online dictionaries define the term "canned" as something that has been produced and conserved and can be released on demand, such as ... canned food, canned music,  canned response, canned air, canned email replies.\r\n\r\nMaybe something like "stored" messages  would also work as well.', '2007-05-15 23:57:31', '2007-05-15 23:57:31', 15, 0),
(140, 17, 10, 'strawberries', 'I am not a big mobile phone user, thus I am not the best person to ask re the term used for saved text messages. \r\n\r\nI do dust off a mobile phone here that I use when I go on holiday. I don''t know where my charger is at, or i would charge the phone and look up the menu system.  But I bet that it is ''saved'' or ''stored'' that we use here.\r\n\r\nSaved Nessages or Stored Messages is better - but hints at the place where all your old received messages from others are located.  \r\n\r\nI wonder if there is a better term - one that does not conflict with the saved message archive part of that menu system.', '2007-05-16 00:39:03', '2007-05-16 00:39:03', 15, 0),
(141, 17, 10, 'strawberries', 'thinking out loud here:\r\n\r\nSaved Responses\r\n\r\nStored Responses\r\n\r\nCanned Responses', '2007-05-16 00:41:42', '2007-05-16 00:41:42', 15, 0),
(142, 17, 4, 'maverick', 'Either Stored Responses or Canned Responses is probably the best, I think the real key might be in using the word "responses" as it would identify their purpose and separate any confusions with messages being saved or stored in the mail boxes.', '2007-05-16 01:10:12', '2007-05-16 01:10:12', 15, 0),
(143, 17, 7, 'dragon', 'Ok, we''ll change it to responses. Do you think Drafts is any better?\r\n\r\nRegarding the premium icon - that''s what the little icon next to each user name is for. It''s the same for everyone now but it''s supposed to change with membership.\r\nPutting another one (bigger) on the profile page is easy.\n\nLast edited by dragon on 2007-05-16 05:27:49 GMT', '2007-05-16 05:21:48', '2007-05-16 05:27:49', 15, 0),
(144, 17, 4, 'maverick', 'Drafts might be ok too, but typically a draft to most people is a rough outline or preliminary copy used for reviewing and editing before making a final copy. I personally think responses might be the most universally understood, at least in this particular case.\r\n\r\nInteresting about the icon next to each user name, I figured this was simply just going to show which members we''re online or offline. So the premium members will display a different icon than standard or free members? Will these icons next to the user''s name still be used to display if they are online or offline, as in a grayed out version when offline?\r\n\r\nPutting an additional bigger one on the profile page IMO I think would be a nice touch as it''s just another small way to award those that pay for a premium membership by giving them a distinctive and recognizable status.\r\n\r\nThis new version is actually starting to exceed my expectations, mostly due to your insights and inclusions to even the smallest of details, so many nice little features, yet it appears you''re managing to keep things relatively well organized and simple. Often it''s just the simple small little details that can make a program standout from the rest.', '2007-05-16 08:00:19', '2007-05-16 08:00:19', 15, 0),
(145, 17, 7, 'dragon', 'The icons next to user names should be different for each membership AND be grayed out or not depending on whether the member is online or not. At least that was the original plan.\r\nAs the application is taking  shape, I am having second thoughts about this - it might be too much for some shared servers under load. We came up with some alternatives but none are striking me as THE solution. We''ll see about that.', '2007-05-16 09:22:50', '2007-05-16 09:22:50', 15, 0),
(146, 17, 10, 'strawberries', '[b]Stored Responses[/b] is the best I have seen so far  (''drafts'' is not that suitable for the reasons maverick explained).\r\n\r\nDan that is interesting regarding icons maybe putting too much load on some servers.  That being the case, then I think that personally I wouldn''t want it. The leaner the better for me.  \r\n\r\nIt would be nice tho if it could be turned on/off in the admin area.', '2007-05-16 12:35:49', '2007-05-16 12:35:49', 15, 0),
(147, 17, 10, 'strawberries', '[quote]This new version is actually starting to exceed my expectations, mostly due to your insights and inclusions to even the smallest of details, so many nice little features, yet it appears you''re managing to keep things relatively well organized and simple.[/quote]\r\nagreed, it is looking superb.\r\n\r\nIt would be good tho, to go through each respective page and look at the left hand column menu/links.  \r\n\r\nI get confused on many pages, and do not remember where certain menu links are at.  I''d like that to be clearer and more straightforward.', '2007-05-16 12:39:34', '2007-05-16 12:39:34', 15, 0),
(148, 17, 4, 'maverick', 'That makes sense, no point in going that route if it''s going to create excess server load. \r\n\r\nI agree with strawberries, I think just going with a single generic icon next to the username just to show who''s online or offline would be suffice and maybe just adding a premium icon thing in the profile might be the way to go, sometimes simple is best. IMO only premium members need to display a special icon in their profile, there''s really not much point or benefit in adding an icon to show a free or standard member.', '2007-05-16 12:52:37', '2007-05-16 12:52:37', 15, 0),
(149, 17, 4, 'maverick', 'I think Dan is going to eventually add the account menu items to all pages. It''s already been included when you visit a user''s profile.', '2007-05-16 12:58:35', '2007-05-16 12:58:35', 15, 0),
(150, 17, 10, 'strawberries', 'Just after checking out and seeing the Account Menu items there now, on the profile pages.  Looking good, looking good.\r\n\r\nI like things readily at hand, and simple to figure out.  Simple is best.', '2007-05-16 13:49:34', '2007-05-16 13:49:34', 15, 0),
(151, 17, 7, 'dragon', 'We''ve added the ''My Account'' menu to more pages - you will see this on the demo when we update the site.\r\nThe problem with the current skin is that the left menu cannot be longer than the main content and in some pages adding the extra menu would break this rule\r\n(it''s just a limitation of this design, not of the application. Another design might overcome this problem).\r\n\r\nAfter release, maybe we will add [url=http://en.wikipedia.org/wiki/Breadcrumb_(navigation)]breadcrumb trails[/url] to help in navigation.\n\nLast edited by dragon on 2007-05-16 14:05:34 GMT', '2007-05-16 14:01:32', '2007-05-16 14:05:34', 15, 0),
(152, 17, 11, 'johnboy', 'Back to the email system, on one New Zealand dating site they have a GOLD member feature in the email system which I think is really good. The member can go into his SENT box and see if the email he/she sent has been opened unopened or put in the trash. Will it be possible to do that with this new script?\r\n\r\nI would pay extra to have that feature as it is really good if you are wondering about an email you have sent and heard no reply.', '2007-05-17 00:32:19', '2007-05-17 00:32:19', 15, 0),
(153, 17, 7, 'dragon', 'Yes, it is possible. There is a request for  this feature in the ''feature requests'' forum, we thought of adding it into the core version but we decided not to. It''s going to be made as an addon or custom work for whoever wants this.', '2007-05-17 08:02:29', '2007-05-17 08:02:29', 15, 0),
(154, 17, 4, 'maverick', 'Hi Dan;\r\n\r\nI know what you mean about the content in the left column can''t exceed the right column content or it will overflow into the footer. \r\n\r\nI''ve ran into this issue before and is one of the problems with trying to do 100% CSS layouts. There''s only two workarounds that I know of, one is floating the left and right content columns and then using the clear:both; tag in the footer. However, I don''t really like this method because it usually only works for 2 column layouts, and often doesn''t work properly in some browsers such as Safari as well as some older versions of IE and FF, at least without adding some messy hacks. The best solution I''ve found is using tables for the left and right content placed inside the main content div, this method works in all browsers. I love CSS but it does have some limitations.\n\nLast edited by maverick on 2007-05-17 08:25:45 GMT', '2007-05-17 08:19:13', '2007-05-17 08:25:45', 15, 0),
(155, 17, 4, 'maverick', 'If you''re interested here''s an example of one possible solution on fixing the issue in CSS.\r\n\r\n[div id=''header'']\r\nheader goes here\r\n[/div]\r\n[div id=''content_wrapper'']\r\n[div id=''content'']\r\ncontent here\r\n[/div]\r\n[/div]\r\n[div id=''left menu'']\r\nmenu items here\r\n[/div]\r\n[div id=''footer'']\r\nfooter goes here\r\n[/div]\r\n\r\nthe css:\r\n\r\n#header {\r\nclear: both;\r\n}\r\n#content_wrapper {\r\nfloat: left;\r\nwidth: 100%;\r\nmargin-left: -200px;\r\n}\r\n#content {\r\nmargin-right: 200px;\r\n}\r\n#leftmenu {\r\nfloat: right;\r\nwidth: 240px;\r\n}\r\n#footer {\r\nclear: both;\r\n}\r\n\r\nThis method works by shifting the content_wrapper left by the width of your navigation block (200px), then shifting it back in the content block.', '2007-05-17 08:43:27', '2007-05-17 08:43:27', 15, 0),
(156, 17, 7, 'dragon', 'the biggest problem with tables in my opinion is that you can''t switch the left and right columns without actually changing the html. Plus the left column would have to come first in the source code and since it is not relevant for search engines, that might hurt your ranking. Plus, this skin uses a nice trick for the footer that I don''t think can be done if it had a clear:both attribute: the footer stays on the bottom  of the screen if the content is smaller than the screen and on the bottom of the content if the content is larger than your screen.\r\nAnyway, I wanted a pure CSS solution for this initial skin so a designer would not have to touch the html files, only the CSS.  Html files are filled with template tags, markers, they are split into frame+left+middle content and are, in general, more difficult to work with.\r\nNot to mention that we might build a CSS layout editor if all goes well :)\r\n\r\nI suppose that your solution is for a liquid layout. That clear: both in footer solves the problem if you work with floats but we tried to avoid floats as much as possible. Plus there''s the problem of footer, I didn''t wanted the footer to get higher than the bottom of the screen.\n\nLast edited by dragon on 2007-05-17 09:02:54 GMT', '2007-05-17 08:47:23', '2007-05-17 09:02:54', 15, 0),
(157, 17, 10, 'strawberries', 'Just in case you aren''t already aware of it, Dan.\r\n\r\nOn my HOME page, I apparently have 1 friend  (yeee-haaaa!) lol\r\n\r\nAnyways, when I click on the link, and go to: http://www.datemill.com/friendy/my_networks.php\r\n\r\n[b]How I am connected[/b]\r\n\r\n[b]No members in this network[/b]\r\n\r\n[b]<!--/loop name="networks">   [/b]', '2007-05-17 11:02:42', '2007-05-17 11:02:42', 15, 0),
(158, 17, 10, 'strawberries', 'By the way, for the first time ever, after posting the above comment, the reloaded page automaticlaly scrolled to the bottom!  Yeee-haaaaa!!!', '2007-05-17 11:03:36', '2007-05-17 11:03:36', 15, 0),
(159, 17, 7, 'dragon', 'I know about that strawberries, but thanks for letting me know. It''s already fixed and  I will update the site today.', '2007-05-17 11:53:17', '2007-05-17 11:53:17', 15, 0),
(160, 17, 4, 'maverick', 'I''m not that big on using floats either, but there are some instances where I''ve found them useful.\r\n\r\nThis solution was just a quick example of one way that could possibly work (rarely use it myself) and you''re right as it''s shown it''s for a liquid layout but it can also be made into a fixed layout as well. There are also a few hacks that can be used for addressing the footer issue so it''s always at the bottom of the screen even when using floats along with the clear:both, but I find it a fairly complex and somewhat messy as it''s usually not 100% cross-browser compliant, especially with Mac browsers. It''s the battle of the damn browsers that makes a designers life miserable.\r\n\r\nHere''s an example of 3 column fixed layout using similar techniques  http://www.pmob.co.uk/temp/3col/3col-abs4.htm\r\n\r\nThere''s no end to the various ways CSS can be used to accomplish various layouts, and from peeking at some of your CSS your''s is probably just as good as any. I''m personally not too worried about any small issues I may come across when Integrating my site and layout, with a bit of swearing and banging my head against the wall I''ve always managed to find solutions to make things work the way I want.', '2007-05-17 12:23:18', '2007-05-17 12:23:18', 15, 0),
(161, 17, 10, 'strawberries', 'I am not big into programming, so I''ll have to leave you guys to that area.\r\n\r\nBut in just looking over the site again - I still love the idea of a third row - to go along with Latest Photos, Latest Members, etc\r\n\r\ni.e. a row of  [b]LATEST MATCHES[/b]', '2007-05-17 12:39:00', '2007-05-17 12:39:00', 15, 0),
(162, 17, 7, 'dragon', 'that''s added to the todo list strawberries but since it''s one of the latest requests, it will have to wait its turn. But it''s coming.', '2007-05-17 12:42:45', '2007-05-17 12:42:45', 15, 0),
(163, 17, 10, 'strawberries', 'Also, it would be great if one was able to create a regular webpage, from the template.  \r\n\r\nFor example, if I have a news story - say a dater who has written an article about the DOs & DON''TS of dating.\r\n\r\nI''d like to be able to click to go to see the article, on it''s own page.\r\n\r\nHope that makes sense.', '2007-05-17 12:43:34', '2007-05-17 12:43:34', 15, 0),
(164, 17, 10, 'strawberries', 'And I maybe asked you this a long time ago Dan (memory is poor here), but how feasable is it to use the new script for a real estate listings type of website?  \r\n\r\ni.e. rather than people sign up to date, they are signing up to advertise their house for sale, or their ocean villa for  rental.\r\n\r\nI thought it would work fine, but then I wondered about the person (organisation) who wants to list several villas (listings) under their single user name.  I wondered how/if that would work.', '2007-05-17 12:48:27', '2007-05-17 12:48:27', 15, 0),
(165, 17, 7, 'dragon', '[quote]For example, if I have a news story - say a dater who has written an article about the DOs & DON''TS of dating.\r\nI''d like to be able to click to go to see the article, on it''s own page.[/quote]\r\n\r\nYou could do that with a blog post: each post (just like the one at the top of this page) is on its own page. With the seo module you should be able to access the page like say www.site.com/blog/dragon/today_i_hate_programming\r\nAnd if you want it to be visible to anyone, make sure you allow access to all from admin.\r\n\r\n[quote]how feasable is it to use the new script for a real estate listings type of website?[/quote]\r\n\r\nLike companies instead of users and listings with photos and text instead of blogs?\r\nI think it''s just a matter of renaming the features.\r\nI plan to start a tourism portal based on this engine (when the day will have 50 hours and I will have the time).\n\nLast edited by dragon on 2007-05-17 14:21:28 GMT', '2007-05-17 13:03:22', '2007-05-17 14:21:28', 15, 0),
(166, 17, 10, 'strawberries', '[quote]Like companies instead of users and listings with photos and text instead of blogs?\r\nI think it''s just a matter of renaming the features.\r\nI plan to start a tourism portal based on this engine (when the day will have 50 hours and I will have the time).[/quote]\r\n\r\ngreat minds!  \r\n\r\nYes, a user might be a real estate agency or an individual.  The agency will need to be able to advertise several listings under their username.  Whereas the individual will usually only be listing one property.\r\n\r\nThe pictures may need a different layout, than what we see here.\r\n\r\nBut I like the idea of leaving the blogs in.  I like the interactivity and stickiness that blogs and forums have.  \r\n\r\nFor example, you may have someone who has a villa in the caribbean, doing a great article/blog on Saint Lucia.  That would be fascinating reading for many people on the site.\r\n\r\nLast edited by strawberries on 2007-05-17 13:58:57 GMT\n\nLast edited by strawberries on 2007-05-17 13:59:49 GMT', '2007-05-17 13:58:27', '2007-05-17 13:59:49', 15, 0),
(167, 17, 10, 'strawberries', 'hmmm, tried three times, and quotes didn''t work above.', '2007-05-17 14:00:28', '2007-05-17 14:00:28', 15, 0),
(168, 18, 10, 'strawberries', 'yeee-haaaaa!\r\n\r\nwaw-hide!', '2007-05-17 17:15:34', '2007-05-17 17:15:34', 15, 0),
(170, 18, 10, 'strawberries', 'I was pottering around today, on a few dating sites.  \r\n\r\nAnd on a small to medium sized site I know, which has been growing very successfully since going online about 18 months, the webmaster has a latest news page....and I see he/she saying that given the interest by many, they are going to introduce blogs to the site.\r\n\r\nSo obviously blogging is the way to go.  Folks are asking for it these days.', '2007-05-18 17:57:33', '2007-05-18 17:57:33', 15, 0),
(171, 18, 10, 'strawberries', '[u]MORE PLUGIN/EXTENSION IDEAS[/u]\r\n\r\nTodays Birthdays\r\n\r\nWho''s Viewing Me', '2007-05-18 17:58:43', '2007-05-18 17:58:43', 15, 0),
(172, 18, 10, 'strawberries', '[b][u]ANOTHER PLUGIN IDEA[/u][/b]\r\nrss feed showing latest x number of new blog posts', '2007-05-18 20:43:58', '2007-05-18 20:43:58', 15, 0),
(173, 17, 11, 'johnboy', '[quote]Yes, it is possible. There is a request for  this feature in the ''feature requests'' forum, we thought of adding it into the core version but we decided not to. [/quote]\r\n\r\nGreat!!!! I can''t wait', '2007-05-22 22:25:06', '2007-05-22 22:25:06', 15, 0),
(174, 14, 11, 'johnboy', 'Is there a reason for not being able to see yourself online, you could on the old DSB, why not on this script?', '2007-05-22 22:38:18', '2007-05-22 22:38:18', 15, 0),
(175, 18, 10, 'strawberries', 'Champions league final tonight (3 hours time).  Come on Liverpool!!! :)', '2007-05-23 15:32:07', '2007-05-23 15:32:07', 15, 0),
(176, 18, 7, 'dragon', 'is that a plugin request? :P', '2007-05-23 16:04:36', '2007-05-23 16:04:36', 15, 0),
(177, 18, 10, 'strawberries', 'LOL  me no like football no more.  \r\n\r\nBad bad AC Milan.', '2007-05-24 02:00:39', '2007-05-24 02:00:39', 15, 0),
(178, 18, 4, 'maverick', 'I''m sure these are just things you haven''t had time to get around to yet, but I thought I''d mention them anyway.\r\n\r\nUnder "My Connections" I assume there will be a means of removing members from your Blocked list, and also be able to delete profiles from your favorites list?\r\n\r\nAlso under the "Saved Responses" at the moment the delete button doesn''t work. I think the saved responses is actually a very cool feature, but what would make it even better is being able to create new responses and/or edit existing ones. Unless I''m overlooking something, presently the only way of creating new responses is by going to a member''s profile and sending a message.', '2007-05-24 02:24:09', '2007-05-24 02:24:09', 15, 0),
(179, 18, 10, 'strawberries', 'Yes, re [b]Saved Responses[/b].  At the minute, the only way of creating a new one, is by first of all clicking to send a message to another member.  \r\n\r\nThis may confuse a lot of users.', '2007-05-24 16:53:01', '2007-05-24 16:53:01', 15, 0),
(180, 18, 10, 'strawberries', 'yabba dabba do.', '2007-05-25 14:54:00', '2007-05-25 14:54:00', 15, 0),
(181, 18, 17, 'shadowmachine', 'A couple of things I''ve noticed, in case you aren''t aware of them.\r\n\r\nUsername is case sensitive. I can sign up as ShadowMachine, but I must login as shadowmachine or I get incorrect login error. This really shouldn''t be case sensiteive.\r\n\r\nSome of the search pull downs have initial values set, such as hair color or eye color, (one of those), level of education, etc. The initial values should be no preference, or any.\r\n\r\nThe zip code radius search is too limited. I think a text field would be better here, but 10 miles is too small a radius in any case. Also, I have tested the zip code radius search and it doesn''t appear to work.', '2007-05-26 06:54:46', '2007-05-26 06:54:46', 15, 0),
(182, 18, 10, 'strawberries', 'very good points.\r\n\r\nI especially would emphasis one of the areas you covered, i.e. in the search pull-downs, that the first choice should be [b]No Preference[/b] or [b]Any[/b]. \r\n\r\nI quickly gave up using the [b]SEARCH[/b] feature in this demo because of that problem.  \r\n\r\nAny searches that I did do came up with no results.  And I gave up going back and forth trying different hair colours tied to different country locations and heights, etc.  \r\n\r\n[b]No Preference[/b] has to be the top value in those fields. Otherwise people won''t use Search.\r\n\r\nEDIT - I had a look there at a few of the bigger sites.  And forget what i said above.  When a person does a search, if he/she doesn''t click on any values in a particular field, then that effectivelymeans he/she has no preference.  \r\n\r\nSo for example, if I leave all the boxes in HAIR COLOUR blank, then the search results should come back with results that include people of all hair colours.\n\nLast edited by strawberries on 2007-05-26 15:21:49 GMT', '2007-05-26 14:51:35', '2007-05-26 15:21:49', 15, 0),
(183, 18, 17, 'shadowmachine', 'My point is, I think that if you leave HAIR COLOR blank, or set to any or no preference, search results should return users with all hair colors. IMHO, if a user loads the search page and does nothing else but hit the search button, leaving all the fields at default, then the search should return all of the users on the site (in theory) with perhaps an exception for default gender preference. In other words, it should start off wide open and narrow as the user selects his or her preferences.', '2007-05-27 14:46:01', '2007-05-27 14:46:01', 15, 0),
(184, 18, 10, 'strawberries', 'I totally agree (and that is what i was trying to say above).', '2007-05-27 20:24:17', '2007-05-27 20:24:17', 15, 0),
(185, 18, 17, 'shadowmachine', 'I''m pretty sure that this is on the list of things to fix, at least some part of it.\r\n\r\nThere is a Firefox glitch that I have noticed. Mervyn mentioned it on the forum. When I load the mailbox.php (My Messages) page in Firefox, the blue footer covers part of the menu. I know from past experience that CSS sometimes behaves very differently in IE and Firefox, and that might be what is causing that on mailbox.php.', '2007-05-28 07:55:09', '2007-05-28 07:55:09', 15, 0),
(186, 18, 10, 'strawberries', 'just to add, that same issue is also on a few of the pages\r\n\r\ne.g. on Manage Filters -  filters.php, \r\n\r\non my_searches.php\r\n\r\non Manage Folders - folders.php', '2007-05-28 11:51:42', '2007-05-28 11:51:42', 15, 0),
(187, 18, 10, 'strawberries', 'maverick and myself by chance noticed a little bug last night.\r\n\r\nfirefox and explorer see the online status icon differently.  \r\n\r\nMaverick''s explorer browser saw the little icon (to the left of users names below their thumbnail on the main page) as dark red/pink (i.e. everybody appeared online to him in explorer)\r\n\r\nMeanwhile in Firefox, everybody appears light red/pink, i.e. offline.\r\n\r\nhttp://www.datemill.com/friendy/skins_site/def/images/member-offline-small.gif\r\n\r\nThe two browsers see things the opposite way round.\n\nLast edited by strawberries on 2007-05-28 13:24:09 GMT', '2007-05-28 11:57:33', '2007-05-28 13:24:09', 15, 0),
(188, 18, 10, 'strawberries', '[b]scooby scooby dooooooo!!![/b]', '2007-05-29 20:19:15', '2007-05-29 20:19:15', 15, 0),
(189, 18, 17, 'shadowmachine', 'I can''t see any difference strawberries. I pulled them up side by side in explorer and firefox and everybody looks offline to me. If you are looking at it on two different monitors that would probably make a difference. No two are alike.', '2007-05-30 08:52:55', '2007-05-30 08:52:55', 15, 0),
(190, 18, 33, 'alterego', 'I get this error every time I try to access the Home page after I log in. It might be an odd glitch that can never be duplicated as there seemed to be some database issues with the server when I was signing up (slow response times, a database error when I tried to access the forum a few moments later, etc.). I just wanted to report it in case somebody else sees it and/or it becomes an issue.\r\n\r\n<error>\r\n: Undefined index: date_added \r\nLast query run: SELECT `_photo` as `photo`,UNIX_TIMESTAMP(`date_added`) as `date_added` FROM `dsb_user_profiles` WHERE `fk_user_id`=''33'' \r\n\r\nArray\r\n(\r\n    [0] => Array\r\n        (\r\n            [function] => general_error\r\n        )\r\n\r\n    [1] => Array\r\n        (\r\n            [file] => /var/www/htdocs/datemill/html/friendy/home.php\r\n            [line] => 30\r\n            [function] => unknown\r\n        )\r\n\r\n)\r\n</error>\r\n\r\nuh... the smilies are curly brackets\r\n\r\nThanks,\r\nShadowMachine''s Alter Ego\n\nLast edited by alterego on 2007-05-30 09:01:39 GMT', '2007-05-30 08:58:20', '2007-05-30 09:01:39', 15, 0),
(191, 18, 4, 'maverick', 'As strawberries mentioned in his post, we both stumbled across this by accident while chatting online and both tried it with IE and Firefox and got the same results ... with IE the member icons appear online (dark red) and in Firefox they appear offline (light pink). I know it''s not a browser cache issue because I have a program setup to auto clean my entire system''s cache and temp files every 24 hrs. It''s also definitely not a monitor issue as strawberries and myself did the same thing you did, brought up both IE and Firefox side by side on our respective systems.\r\n\r\nYou can check to see the color difference between the offline and online icons that stawberries and I are talking about.\r\n\r\nhttp://www.datemill.com/friendy/skins_site/def/images/member-online-small.gif\r\n\r\nhttp://www.datemill.com/friendy/skins_site/def/images/member-offline-small.gif\n\nLast edited by maverick on 2007-05-30 11:52:34 GMT', '2007-05-30 11:17:36', '2007-05-30 11:52:34', 15, 0),
(192, 18, 4, 'maverick', 'The error message you''re sometimes receiving when clicking the home page after login, there were similar errors on a variety of pages shortly after Dan installed the admin which was corrected. Maybe you''re sometimes getting an older cached version. When testing an alpha or beta site that''s frequently updated, it''s best to clear your system cache on a frequent basis to be sure you''re viewing the most recent versions (for both IE and Firefox).', '2007-05-30 11:38:49', '2007-05-30 11:38:49', 15, 0),
(193, 18, 10, 'strawberries', '[quote]I can''t see any difference strawberries. I pulled them up side by side in explorer and firefox and everybody looks offline to me. If you are looking at it on two different monitors that would probably make a difference. No two are alike.[/quote]\r\nshadow, take a look at the two images that maverick posted links to, i.e.\r\n[url=http://www.datemill.com/friendy/skins_site/def/images/member-online-small.gif][/url]\r\n\r\n[url=http://www.datemill.com/friendy/skins_site/def/images/member-offline-small.gif][/url]\r\n\r\nWe both see the same issue between both browsers.\n\nLast edited by strawberries on 2007-05-30 16:15:34 GMT', '2007-05-30 16:14:39', '2007-05-30 16:15:34', 15, 0),
(194, 18, 10, 'strawberries', 'argh, when i editted my post above, to use the hyperlink button to make the image url''s clickable, the above happened......i.e.e disappeared/white space.\r\n\r\nthe urls are:\r\nhttp://www.datemill.com/friendy/skins_site/def/images/member-online-small.gif\r\n\r\nhttp://www.datemill.com/friendy/skins_site/def/images/member-offline-small.gif', '2007-05-30 16:17:29', '2007-05-30 16:17:29', 15, 0),
(195, 18, 7, 'dragon', 'We know about the online/offline issue in ff/ie. We didn''t bother yet but it will be fixed when we add the online/offline functionality we discussed a while ago.\r\n\r\nYou will not see any updates on the site this week as I am attending a seminar in another town and I want to be present when something changes on the site.\r\nThe layout problem was fixed, some more things added, we started working on the installer (probably sometime next week we are going to release the closed beta).\r\nI''ll update the site on sunday.\r\n\r\n@alterego - we''ll check and let you know.\n\nLast edited by dragon on 2007-05-30 16:23:29 GMT', '2007-05-30 16:19:57', '2007-05-30 16:23:29', 15, 0),
(196, 18, 17, 'shadowmachine', 'Maverick and strawberries: I already did all that but I double-checked it just now. I can''t see any difference in those images when viewed in Firefox 2.0.0.3 and IE 7.0.5730.00. What I do see is on the home page -- everybody appears to be offline by icon color, including myself.', '2007-05-31 02:55:53', '2007-05-31 02:55:53', 15, 0),
(197, 18, 33, 'alterego', 'In addition to the error generated while attempting to access the home page, I get a VERY long error when I try to access My Profile. It starts with this line:\r\n\r\n: Undefined index: \r\nLast query run: SELECT * FROM `dsb_user_profiles` WHERE `fk_user_id`=''33'' \r\n\r\nThe error is too long to post here. I also don''t appear in the member''s list and my photo doesn''t show up. IMHO, I really think the database problems that I saw when I was ceating this account caused this problem.', '2007-05-31 03:05:46', '2007-05-31 03:05:46', 15, 0),
(198, 18, 10, 'strawberries', 'shadow, my versions are:\r\n\r\nFirefox version 2.0.0.3\r\nExplorer version 6.0.2900.2180\r\n\r\nMaybe the fact that you have a more recent version of explorer is the difference.\r\n\r\nMaybe it''s just us ''oldies'' that are seeing the error.  lol', '2007-05-31 09:27:20', '2007-05-31 09:27:20', 15, 0),
(199, 18, 4, 'maverick', 'as far as the online/offline icon differences between IE and Firefox. It''s rather irrelevant at this point since Dan himself has stated he''s aware of this already and technically the online/offline isn''t really implemented yet.\r\n\r\nshadowmachine, the reason it''s appearing the same in both your browsers is because you''re using IE version 7 which processes CSS a bit different than version 6.\r\n\r\nI personally won''t use IE 7 as it is now, it''s another one of Microsoft''s big blunders, in fact there''s some rumors circulating around that Microsoft may try purchasing rights to Firefox to use as a replacement browser for IE 7.\n\nLast edited by maverick on 2007-05-31 12:20:24 GMT', '2007-05-31 12:13:51', '2007-05-31 12:20:24', 15, 0),
(200, 18, 7, 'dragon', 'Maverick, I haven''t heard about that, just that the ff team was invited to Microsoft for some talks but that would be [b]AWSOME[/b]. Imagine a standard compliant browser coming from m$!!!!', '2007-05-31 18:12:08', '2007-05-31 18:12:08', 15, 0),
(201, 18, 4, 'maverick', 'Dan, I don''t know how credible the rumors are, if I''m not mistaken the first place I came across them was in the Firefox forums maybe a month ago. Like you, I think it would be AWESOME, that would mean over 90% of users would be using one standards compliant browser, which means web developers for the first time could pretty much develop around one browser, instead of 2, 3 or even 4 different browsers.\r\n\r\nI think it may happen because FF is inching towards gaining 50% of the market and Mr. Gates, like him or hate him, isn''t a stupid business man.\r\n\r\nIf it does happen I''m hoping Microsoft just buys the rights to include a branded version as part of the Microsoft Windows package but the actual development remains with the Firefox team so it doesn''t eventually turn into another piece of bloatware.\n\nLast edited by maverick on 2007-05-31 19:07:47 GMT', '2007-05-31 18:56:26', '2007-05-31 19:07:47', 15, 0),
(202, 18, 10, 'strawberries', 'Not sure how I feel about MS possibly buying Firefox.  I love my FF and I sorta don''t want it becoming just another part of the evil empire.  :)\r\n\r\nIt woul dbe good for developers tho.  but it woudl also mean that hackers would turn their sole attention to firefox (whereas they tend to focus on explorer).', '2007-05-31 19:42:45', '2007-05-31 19:42:45', 15, 0),
(203, 18, 17, 'shadowmachine', 'Maverick: I don''t use IE 7 except for testing. I do keep both browsers updated. It is irrelevant since users with updated browsers won''t see the problem. Both browsers generally update automatically by default.\r\n\r\nRE: If Microsoft becomes involved with Firefox, I doubt that Firefox would remain a compliant browser.', '2007-06-01 05:19:11', '2007-06-01 05:19:11', 15, 0),
(204, 18, 4, 'maverick', '[b]UPDATE![/b]\r\n\r\nApparently Microsoft has already released their own version of Firefox. They never bought out or took over Firefox, they just created their own version based on Firefox''s source code, along with some of thier own features added. I think it''s a good thing as long as it remains where users will still have the option of using either the standard open source version or Microsoft''s version.\r\n\r\nThis doesn''t surprise me because IE 7 has been a total disaster for Microsoft and since it''s release Firefox''s popularity has sky rocketed.\r\n\r\nYou can check it out here ...\r\nhttp://www.msfirefox.com/microsoft-firefox/index.html\r\n\r\nhttp://www.theregister.co.uk/2006/11/14/ms_firefox/\n\nLast edited by maverick on 2007-06-01 10:08:01 GMT', '2007-06-01 09:58:27', '2007-06-01 10:08:01', 15, 0),
(205, 18, 17, 'shadowmachine', 'I''m not sure I''d be all that surprised if that really happened. Good one. :D', '2007-06-01 10:57:26', '2007-06-01 10:57:26', 15, 0),
(206, 18, 10, 'strawberries', 'is this a wind-up!  :))\r\n\r\nI can''t believe those buggers at MS have taken over the one true good thing out there in the browser market. (well ok, I''d never be surprised at anything the evil empire gets up to)\r\n\r\nPlease say it ain''t so, Joe!\r\n\r\nPS - maverick, unsure what is up re messenger....sent messages, but no reply.', '2007-06-01 11:38:13', '2007-06-01 11:38:13', 15, 0),
(207, 18, 4, 'maverick', 'Ya kind''a funny wasn''t it :)\r\n\r\nBut I think something may be brewing behind closed doors.\r\n\r\nLike the rest of you, even though I''d like to see a standarized browser that was used by the masses, I have my concerns about a total buyout or takeover by MS.', '2007-06-01 11:44:09', '2007-06-01 11:44:09', 15, 0),
(208, 18, 10, 'strawberries', 'I had fallen for it!  :)\r\n\r\nMS to me is the devil and a bloated inefficient monopoly personified.\r\n\r\nLike google, MS was wonderfully innovative/forward thinking in it''s early days.  But it grew into a monster. \r\n\r\nGoogle has gone the same way (tho I still continue to use it all the time, and have done for over 10 years).\r\n\r\nAh well, tis time here to go get some breakfast....err lunch.', '2007-06-03 12:51:20', '2007-06-03 12:51:20', 15, 0),
(209, 18, 10, 'strawberries', 'scooby scooby do!!!!!', '2007-06-04 10:47:33', '2007-06-04 10:47:33', 15, 0),
(210, 19, 7, 'dragon', 'test', '2007-06-19 17:45:08', '2007-06-19 17:45:08', 15, 1),
(211, 19, 7, 'dragon', '>:):doh:<.<:lol::w::!:|o', '2007-06-21 20:38:21', '2007-06-21 20:38:21', 15, 1),
(212, 19, 7, 'dragon', ':!:', '2007-06-21 21:03:15', '2007-06-21 21:03:15', 15, 1);

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
  FULLTEXT KEY `text_key` (`title`,`post_content`)
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `dsb_blog_posts`
-- 

INSERT INTO `dsb_blog_posts` (`post_id`, `date_posted`, `fk_user_id`, `_user`, `fk_blog_id`, `is_public`, `title`, `post_content`, `allow_comments`, `status`, `post_url`, `stat_views`, `stat_comments`, `last_changed`, `reject_reason`, `processed`) VALUES 
(1, '2007-04-16 19:20:25', 1, 'emma', 1, 1, 'The Bicentennial Man', 'by Isaac Asimov\n\n[b]The Three Laws of Robotics[/b]\n.\n.\n.\n[quote]A robot may not injure a human being, or, through inaction, allow a human being to come to harm.[/quote]\n\n[quote]A robot must obey the orders given it by human beings except where such orders would conflict with the First Law.[/quote]\n\n[quote]A robot must protect its own existence as long as such protection does not conflict with the First or Second Law.[/quote]\n\nAndrew Martin said, "[u]Thank you[/u]," and took the seat offered him. He didn''t look driven to the last resort, but he had been.\n\nHe didn''t, actually, look anything, for there was a smooth blankness, to his face, except for the sadness one imagined one saw in his eyes. His hair was smooth, light brown, rather fine; and he had no facial hair. He looked freshly and cleanly shaved. His clothes were distinctly old-fashioned, but neat, and predominantly a velvety red-purple in color.\n\nFacing him from behind the desk was the surgeon The nameplate on the desk included a fully identifying series of letters and numbers which Andrew didn''t bother with. To call him Doctor would be quite enough\n\n"When can the operation be carried through, Doctor?" he asked.\n\nSoftly, with that certain inalienable note of respect that a robot always used to a human being, the surgeon said, "I am not certain, sir, that I understand how or upon whom such an operation could be performed."\n\nThere might have been a look of respectful intransigence on the surgeon''s face, if a robot of his sort, in lightly bronzed stainless steel, could have such an expression-or any expression.\n\nAndrew Martin studied the robot''s right hand, his cutting hand, as it lay motionless on the desk. The fingers were long and were shaped into artistically metallic, looping curves so -graceful and appropriate that one could imagine a scalpel fitting them and becoming, temporarily, one piece with them. There would be no hesitation in his work, no stumbling, no quivering, no mistakes. That confidence came with specialization, of course, a specialization so fiercely desired by humanity that few robots were, any longer, independently brained. A surgeon, of course, would have to be. But this one, though brained, was so limited in his capacity that he did not recognize Andrew, had probably never heard of him .\n\n[quote]Have you ever thought you would like to be a man?[/quote] Andrew asked.\n\nThe surgeon hesitated a moment, as though the question fitted nowhere in his allotted positronic pathways. "But I am a robot, sir."\n\n[quote]Would it be better to be a man?[/quote]', 1, 15, '', 2, 1, '2007-04-16 19:24:35', '', 0),
(2, '2007-04-16 20:50:21', 10, 'strawberries', 3, 1, 'Fluffy say''s ''Hi''', 'Hi everyone.  Welcome to Fluffy''s blog.', 1, 15, '', 2, 4, '2007-04-18 01:05:21', '', 0),
(3, '2007-04-17 12:08:28', 7, 'dragon', 5, 1, 'Now what?', 'Ok, so now that a basic preview is up for you to see and test, we''ll update it daily with whatver we''ll be working on. So stay tuned for more news.\nThere are some things to fix with blogs, we need to finish the friendship connections feature (right now you can only add another member to your favorites network and request to be a friend of another member).\n\nWe need to also finish the flirts...\nAs many of you have requested, you want to be able to reply with a flirt to a flirt and this is now possible.\n\nAs you might have seen, the flirts can include both text and images and even sounds if you want. It''s up to the admin''s imagination to create some creative flirts.\n\nAnother thing that needs to be finished is the news system. There are 2 parts here:\nYou can include news read by a rss reader from any published rss feed and you will also have site news - published by admin for the site members. The news will appear on the home page (the page after login) by default but you can put it anywhere you want in your site, even on the front page.\n', 1, 15, '', 8, 9, '2007-04-17 12:19:12', '', 0),
(4, '2007-04-17 21:45:04', 10, 'strawberries', 3, 1, 'Fluffy, fluffy, fluffy', 'The greatest cat in the land.', 1, 15, '', 1, 4, '2007-04-17 21:45:04', '', 0),
(5, '2007-04-18 21:26:37', 7, 'dragon', 5, 1, 'Today''s update', '- fixed a bug with account confirmation\n- finished the connection feature\n- finished the friendship requests pages\n- added the list of friends in the profile page - left menu\n- fixed some bugs with edit/delete links in my_folders/my_filters\n- finished the member block/unblock feature\n- fixed 2 bugs with message counting in the left menus of mail related pages\n- fixed some bugs in the crons\n- added the mail_crlf option in site options to select between \\r\\n and \\n line endings in emails\n- fixed some js errors', 1, 15, '', 1, 1, '2007-04-18 21:26:37', '', 0),
(6, '2007-04-18 23:30:54', 19, 'pkusa', 6, 1, 'About MyOrg, Inc', 'Why Choose Us?\nWhy would someone choose one provider over another?  When it comes to Internet Connectivity, how well does one really know any provider? \n\nRegardless of how many server racks are maintained, or how low the monthly price is for hosting your site, we believe that ultimately, our customers choose us because they have gotten to know us.  They know that we will help them connect to the maze that is the Internet and, should they get lost, they know that we will be there to help them find their way.\n\nWe are dedicated to ensuring that whatever service you choose with us, should it be: Web Hosting Solutions, Domain Names, SSL, Co-Location or Web Development we always are courteous, knowledgeable and quick to respond.\n\nIn order to stay ahead of the competition, MyOrg, Inc. and its family of companies have embarked on providing our consumer and corporate customers with true quality-of-service initiatives, focused on making our customers'' Internet experience the best it can be.\n[b][/b][b][/b][b][/b][b][/b][quote][/quote]', 1, 15, '', 1, 1, '2007-04-18 23:30:54', '', 0),
(7, '2007-04-19 05:01:39', 12, 'a_l_f', 7, 1, 'A design resource kit', 'I look forward to the design resource kit as i cannot leave things alone :-)\ni wonder how long it will take me to crash the new install when its released :-0', 1, 15, '', 0, 1, '2007-04-19 05:01:39', '', 0),
(8, '2007-04-19 09:57:59', 1, 'emma', 1, 1, 'Testing blogs', 'Hello\n\n[quote]everybody[/quote]\n\n[b]how[/b]\n\n[u]are[/u]\n\n[url=http://www.datemill.com/friendy/profile.php?user=emma]you[/url]?', 1, 15, '', 1, 4, '2007-04-19 09:57:59', '', 0),
(9, '2007-04-19 21:40:05', 7, 'dragon', 5, 1, 'Today''s update take 2', 'Ok, another update today - we focused on bugs and overall stability but a couple of features were added too.\n- You should be able to send (and receive) messages, flirts, etc.\n- You will receive new message email notifications when you get a new message (if you said you want to receive notifs in your settings)\n- You will also receive message and email notifications when a new comment is made on one of your pictures or blogs.\n- The one and only cron job is active on the demo site.\n', 1, 15, '', 12, 6, '2007-04-19 21:40:05', '', 0),
(10, '2007-04-20 02:01:23', 11, 'johnboy', 4, 1, 'Testing this out', 'I''m not very familiar about blogs, so I have no idea what Im doing here, not even sure if this will post in the main blog area. Are blogs just another type of FORUMS?', 1, 15, '', 22, 5, '2007-04-20 02:01:23', '', 0),
(11, '2007-04-21 11:43:23', 23, 'zooki', 8, 1, 'Testing the Waters of the Grainy underworld that is known as Earth', '[b]Some people dont get it [/b]\n\n[u]Why fight and make wars?? [/u]\n\nIts about time we stood up to those who wage wars under false pretences... Is it really about WMDs, Post Apocalyptic fall out, saving some nation, Religion, or is it about making a huge amount of personal wealth, power and chocolate.\n\nChocolate and coffee growing farmers are those to look out for as they have the most control over people. However, the world''s attention is drawn to Oil producers and Petroleum companies and those Ministers and Politicians who are linked with them.\n\nPerhaps you feel my blog is nonsense, but let me tell you that the truth always hurts. \n\nGive up your Chocolate and your Coffee, you will thank me one day. We must revolt.. and save the bears of the Cocoa forests of New Guinea. \n\nOk?', 1, 15, '', 4, 2, '2007-04-21 11:43:23', '', 0),
(12, '2007-04-21 11:52:14', 23, 'zooki', 8, 1, 'The wave that destroyed Atlantis', 'http://news.bbc.co.uk/1/hi/sci/tech/6568053.stm\n\nThe legend of Atlantis, the country that disappeared under the sea, may be more than just a myth. Research on the Greek island of Crete suggests Europe''s earliest civilisation was destroyed by a giant tsunami.\nTidal wave\n\nVideo reconstruction of the tsunami\nUntil about 3,500 years ago, a spectacular ancient civilisation was flourishing in the Eastern Mediterranean.\n\nThe ancient Minoans were building palaces, paved streets and sewers, while most Europeans were still living in primitive huts.\n\nBut around 1500BC the people who spawned the myths of the Minotaur and the Labyrinth abruptly disappeared. Now the mystery of their cataclysmic end may finally have been solved.\n\nThe wave would have been as powerful as the one that devastated the coastlines of Thailand and Sri Lanka on Boxing day 2004 leading to the loss of over 250,000 lives\nA group of scientists have uncovered new evidence that the island of Crete was hit by a massive tsunami at the same time that Minoan culture disappeared.\n\n"The geo-archaeological deposits contain a number of distinct tsunami signatures," says Dutch-born geologist Professor Hendrik Bruins of the Ben-Gurion University of the Negev in Israel.\n\n"Minoan building material, pottery and cups along with food residue such as isolated animal bones were mixed up with rounded beach pebbles and sea shells and microscopic marine fauna.\n\nVolcanic eruption\nThe Santorini eruption may have sparked the tsunami\n"The latter can only have been scooped up from the sea-bed by one mechanism - a powerful tsunami, dumping all these materials together in a destructive swoop," says Professor Bruins.\n\nThe deposits are up to seven metres above sea level, well above the normal reach of storm waves.\n\n"An event of ferocious force hit the coast of Crete and this wasn''t just a Mediterranean storm," says Professor Bruins.\n\nBig wave\n\nThe Minoans were sailors and traders. Most of their towns were along the coast, making them especially vulnerable to the effects of a tsunami.\n\nOne of their largest settlements was at Palaikastro on the eastern edge of the island, one of the sites where Canadian archaeologist Sandy MacGillivray has been excavating for 25 years.\n\nHere, he has found other tell-tale signs such as buildings where the walls facing the sea are missing but side walls which could have survived a giant wave are left intact.\n\n"All of a sudden a lot of the deposits began making sense to us," says MacGillivary.\n\n"Even though the town of Palaikastro is a port it stretched hundreds of metres into the hinterland and is, in places, at least 15 metres above sea level. This was a big wave."\n\nTidal wave\nHow it might have looked as the wave approached the town\nBut if this evidence is so clear why has it not been discovered before now?\n\nTsunami expert Costas Synolakis, from the University of Southern California, says that the study of ancient tsunamis is in its infancy and people have not, until now, really known what to look for.\n\nMany scientists are still of the view that these waves only blasted material away and did not leave much behind in the way of deposits.\n\nBut observation of the Asian tsunami of 2004 changed all that.\n\n"If you remember the video footage," says Costas, "some of it showed tonnes of debris being carried along by the wave and much of it was deposited inland."\n\nVolcanic eruption\n\nCostas Synolakis has come to the conclusion that the wave would have been as powerful as the one that devastated the coastlines of Thailand and Sri Lanka on Boxing day 2004 leading to the loss of over 250,000 lives.\n\nAfter decades studying the Minoans, MacGillivray is struck by the scale of the destruction.\n\n"The Minoans are so confident in their navy that they''re living in unprotected cities all along the coastline. Now, you go to Bande Aceh [in Indonesia] and you find that the mortality rate is 80%. If we''re looking at a similar mortality rate, that''s the end of the Minoans."\n\nBut what caused the tsunami? The scientists have obtained radiocarbon dates for the deposits that show the tsunami could have hit the coast at exactly the same time as an eruption of the Santorini volcano, 70 km north of Crete, in the middle of the second millennium BC.\n\nMinoan art\nThe Minoans were Europe''s first great civilisation\nRecent scientific work has established that the Santorini eruption was up to 10 times more powerful than the eruption of Krakatoa in 1883. It caused massive climatic disruption and the blast was heard over 3000 miles away.\n\nCostas Synolakis thinks that the collapse of Santorini''s giant volcanic cone into the sea during the eruption was the mechanism that generated a wave large enough to destroy the Minoan coastal towns.\n\nIt is not clear if the tsunami could have reached inland to the Minoan capital at Knossos, but the fallout from the volcano would have carried other consequences - massive ash falls and crop failure. With their ports, trading fleet and navy destroyed, the Minoans would never have fully recovered.\n\nThe myth of Atlantis, the city state that was lost beneath the sea, was first mentioned by Plato over 2000 years ago.\n\nIt has had a hold on the popular imagination for centuries.\n\nPerhaps we now have an explanation of its origin - a folk memory of a real ancient civilisation swallowed by the sea.\n\nTimewatch: The wave that destroyed Atlantis is on BBC Two at 2100BST on Friday 20 April, 2007.', 1, 15, '', 12, 3, '2007-04-21 11:52:14', '', 0),
(13, '2007-04-21 21:12:36', 7, 'dragon', 5, 1, 'I am pretty, oh so pretty...', 'Another big update today, we didn''t want you to miss us.\nThis time I''ll let you discover the changes we did, hehehe.\nThe first to report all changes wins. Something. Maybe.\n', 1, 15, '', 143, 23, '2007-04-21 21:12:36', '', 0),
(14, '2007-04-24 19:50:49', 7, 'dragon', 5, 1, 'Update time, again', 'A new update is in place.\nSince I let you guess last time, I''ll include here the changes from the previous update too:\n- Overall stability improvements. It becomes harder and harder to find bugs :)\n- You can edit your own comments made on photos/blogs/profiles (js and non-js solutions)\n- The owner of a photo/blog/profile can delete any comment made there\n- Added smilies to all user generated content (blogs/comments/profile fields/messages). Just the rendering part, you don''t have a smiley select box yet but you can write them manually.\n- You can now see the date of the comments\n- Site news rendered in html on the home page and rss feed.\n- The link in the message notification telling you that you have a new comment on your blog/photo/profile takes you directly to that comment instead of the blog/photo/profile.\n- Comments are shown even if you are not logged in.\n- The name of the site started to change :)', 1, 15, '', 427, 61, '2007-04-24 19:55:28', '', 0),
(15, '2007-04-25 21:18:04', 6, 'cocacola', 9, 1, 'Hello there', 'Wow a blog', 1, 15, '', 27, 3, '2007-04-25 21:18:19', '', 0),
(16, '2007-05-04 22:56:57', 17, 'shadowmachine', 10, 1, 'Other script is O-U-T out', 'I fumbled around with another script for a few days... not naming any names... I''ll just say it is one of the free ones...\n\nIt worked okay at first... until I uploaded a 20 kiloByte profile picture. Then it kinda went haywire. PHP memory errors = fatality. Site is still dead so...\n\nI have a nice domain name and plenty of web space... I know a little about PHP and HTML... and I am very thorough with an eye for detail (read OBSESSIVE COMPULSIVE).\n\nSo, Dan, when you''re ready I can launch a beta.\n\n10... 9... 8... 7...\n\nNo pressure.', 1, 15, '', 22, 0, '2007-05-04 22:56:57', '', 0),
(17, '2007-05-11 21:30:09', 7, 'dragon', 5, 1, 'Today I hate programming...', 'It was one of those days when a boring, repetitive task was assigned to me (ok, auto-assigned - someone had to do it) and I would do anything but this. Yeah, programming is like this sometimes. All the joy and fun of creating some uber-script-to-rule-the-world is gone when you have to replace a piece of text with another in 1000000 places.\nWe discovered that some html editors are removing our template-specific tags so we had to replace all tags in all files with something that would look like comments to those editors.\nWe''re not using html editors, we write all the code in a simple text editor but some of you might use dreamweaver/frontpage/etc to add a comma and then boom, nothing would work anymore.\n\nThis was one of the many changes we''ve done lately. Even if you haven''t seen anything new in the member interface, we''ve worked hard on things like this and on the admin interface.\nThe plan is to have a preview version of the admin next week, then soon after - a closed beta, then, while we work on our own site, a public beta. I''m tempted to hang a sign with a big yellow [b]BETA[/b] sign next to the logo :)', 1, 15, '', 251, 41, '2007-05-11 21:30:09', '', 0),
(18, '2007-05-17 16:40:23', 7, 'dragon', 5, 1, 'Site updated', '1. admin work, admin work, admin work.\n2. message templates -> my saved responses\n3. ''my account'' menu in more pages to keep [url=http://www.datemill.com/friendy/profile.php?uid=10]strawberries[/url] happy :)', 0, 15, '', 394, 39, '2007-06-19 11:24:50', '', 0),
(19, '2007-06-04 11:37:46', 7, 'dragon', 5, 1, 'tweaks', 'I''ve updated the demo site. We''re moving back to member area this week to finish the pending issues and unfinished features.\nThis update was for admin area too - mostly tweaks, interface improvements and bug fixes.\nWe added event handling in most of the processors - if you know about event based programming you should know that this is a very handy development feature. It allows you to create your own functions to be run when a user does something on the site (comments/updates profile/adds photo/etc) without messing with the rest of the script.\n\nWe created a workaround for the left menu issue - for now it works on the inbox page but we will apply it to all pages with problems.\n\nThe search was also partially fixed - it allows selecting nothing in select boxes but the same fix should be applied to range searches (like height/weight/etc). TODO', 1, 15, '', 227, 3, '2007-06-04 11:37:46', '', 0);

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

INSERT INTO `dsb_feed_cache` (`module_code`, `feed_xml`, `update_time`) VALUES 
(0x6f7369676e616c5f66656564, '<?xml version="1.0" encoding="UTF-8"?>\r\n<?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/rss2full.xsl" type="text/xsl" media="screen"?><?xml-stylesheet href="http://feeds.feedburner.com/~d/styles/itemcontent.css" type="text/css" media="screen"?><rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">\r\n<channel>\r\n	<title>Original Signal - Transmitting Tech</title>\r\n	<link>http://tech.originalsignal.com</link>\r\n	<description>Orginal Signal aggregates the 15 most popular technology sites. The main purpose of the site is to provide \r\na quick glance on what''s happening without using your desktop/web RSS reader. New headlines (since your \r\nlast cookied visit) come in pretty orange, visited ones are grey. All credits go to the authors of these weblogs. \r\nWithout their hard work Original Signal would not exist. Original Signal was inspired by Popurls and the Web 2.0 Workgroup.</description>\r\n	<pubDate>Thu, 21 Jun 2007 12:19:35 CEST</pubDate>\r\n	<language>en</language>\r\n	\r\n	  <atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" href="http://feeds.feedburner.com/OriginalSignal/tech" type="application/rss+xml" /><item>\r\n  <title>SEC poised to accept IFRS from foreign-based plcs</title>\r\n  <link>http://tech.originalsignal.com/article/57549/sec-poised-to-accept-ifrs-from-foreign-based-plcs.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 12:09:00 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57549/sec-poised-to-accept-ifrs-from-foreign-based-plcs.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Larry Schlesinger, Accountancy Age, Thursday 21 June 2007 at 00:00:00SEC proposal major step towards accepting IFRS statementsA new SEC proposal has paved the way for foreign public companies listed in the US to choose international accounting standards or US rules when filing data with the regulator...&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>DARPA Looking Into Invisible, Shoot-Through, Self-Healing Armor</title>\r\n  <link>http://tech.originalsignal.com/article/57548/darpa-looking-into-invisible-shoot-through-self-healing-armor.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 12:08:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57548/darpa-looking-into-invisible-shoot-through-self-healing-armor.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Yeah, we''re talking armor that soldiers can see and fire through on one side, but is invisible and impenetrable on the other.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Judge Tells RIAA: Irreparable Harm Doesn''t Mean What You Think It Means</title>\r\n  <link>http://tech.originalsignal.com/article/57547/judge-tells-riaa-irreparable-harm-doesnt-mean-what-you-think-it-means.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 10:38:54 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57547/judge-tells-riaa-irreparable-harm-doesnt-mean-what-you-think-it-means.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  The recording industry loves to throw around the term "irreparable harm" in its various lawsuits -- as if someone hearing a song they didn''t pay for will mortally wound the industry.  While some say that this is just standard legalese and we shouldn''t read too much into it, it looks like a judge in New Mexico disagrees.  In denying the RIAA''s request to have the University of Mexico simply hand over info on someone using their network (without letting that individual fight back against the request for info), the judge notes: "While the Court does not dispute that infringement of a copyright results in harm, it requires a Coleridgian ''suspension of disbelief'' to accept that the harm is irreparable, especially when monetary damages can cure any alleged violation."  However, the judge argues, turning over someone''s private info without giving them a chance to defend themselves and protest could cause irreparable harm: "the harm related to disclosure of confidential information in a student or faculty member’s Internet files can be equally harmful."  Nice to see the judge recognize that just because someone may have listened to a song without paying for it, it doesn''t mean that they lose all other rights.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>OS X Leopard Got Pirated...Already!?</title>\r\n  <link>http://tech.originalsignal.com/article/57546/os-x-leopard-got-pirated-already.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 10:38:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57546/os-x-leopard-got-pirated-already.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Oopsie daisy! It looks like that developers build of OS X Leopard has made its way into the grabby hands of Internet pirates. It looks like it, at least. There''s a torrent on a popular private (well, it was) torrent tracker that has a few hundred people pigpiled on.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Microsoft Flip-flopping on Virtualization License</title>\r\n  <link>http://tech.originalsignal.com/article/57545/microsoft-flip-flopping-on-virtualization-license.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 10:38:02 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57545/microsoft-flip-flopping-on-virtualization-license.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Cole writes "Microsoft came within a few hours of reversing its EULA-based ban on the virtualization of Vista Basic and Premium, only to cancel the announcement at the last minute. The company reached out to media and bloggers about the announcement and was ready to celebrate "user choice" before pulling the plug, apparently clinging to security excuses. From the article, "The threat of hypervisor malware affects Ultimate and Business editions just as much as Home Premium and Basic. As such, the only logical explanation is that Microsoft is using pricing to discourage users from virtualizing those OSes. Since when is a price tag an effective means of combating malware?" Something else must be going on here."Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Big Ag Enlists Robots to Pick High-Hanging Fruit</title>\r\n  <link>http://tech.originalsignal.com/article/57544/big-ag-enlists-robots-to-pick-high-hanging-fruit.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 10:08:24 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57544/big-ag-enlists-robots-to-pick-high-hanging-fruit.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  As if the debate over immigration and guest worker programs wasn''t complicated enough, now a couple of robots are rolling into the middle of it.     ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Hyper-Personal Search ''Possible''</title>\r\n  <link>http://tech.originalsignal.com/article/57543/hyper-personal-search-possible.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 08:38:05 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57543/hyper-personal-search-possible.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Google would consider keeping a user''s search data for longer than 18 months if they had explicitly consented, one of the firm''s key executives has said.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Having Accurate Broadband Data... Will Slow Down Broadband Growth?</title>\r\n  <link>http://tech.originalsignal.com/article/57542/having-accurate-broadband-data-will-slow-down-broadband-growth.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 08:08:51 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57542/having-accurate-broadband-data-will-slow-down-broadband-growth.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Many broadband providers have resisted providing detailed broadband penetration data, since it would suggest that things aren''t as rosy as the FCC insists with its bizarre counting system that everyone knows doesn''t paint an accurate portrait of broadband in the US.  However, with Congress looking to force the collection of more accurate broadband availability data, some broadband providers are protesting.  Apparently, the American Cable Association is claiming that providing such information would harm broadband growth.  Why?  Because collecting that info would take time and effort away from providing more broadband. Of course, that suggests that these providers don''t already know where they provide broadband, which is hard to believe.  It also ignores that one of the suggestions for getting better broadband data is to provide a user-generated mapping tool that won''t require the broadband providers'' involvement at all.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>June 21, 2004: SpaceShipOne Proves (Capitalist) Pigs Can Fly</title>\r\n  <link>http://tech.originalsignal.com/article/57541/june-21-2004-spaceshipone-proves-capitalist-pigs-can-fly.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 08:08:24 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57541/june-21-2004-spaceshipone-proves-capitalist-pigs-can-fly.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  The private sector joins the space community with a successful sub-orbital flight above the Mojave Desert.     ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>How they steal your card AND pin at ATM''s</title>\r\n  <link>http://tech.originalsignal.com/article/57540/how-they-steal-your-card-and-pin-at-atms.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 08:08:12 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57540/how-they-steal-your-card-and-pin-at-atms.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  Using black film which blends with the black plastic of an ATM theives can steal your card along and capture your pin number.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>FAA Plans to Clean Up the Skies</title>\r\n  <link>http://tech.originalsignal.com/article/57539/faa-plans-to-clean-up-the-skies.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 08:08:02 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57539/faa-plans-to-clean-up-the-skies.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  coondoggie writes "On top of its recently announced plan to reduce flight delays, Federal Aviation Administration officials today launched what they hope will be pan U.S. and European Union joint action plan to cut greenhouse gas emissions from aircraft. Specifically the group announced the Atlantic Interoperability Initiative to Reduce Emissions or AIRE &mdash; the first large-scale environmental plan aimed at uniting aviation players from both sides of the Atlantic."Read more of this story at Slashdot.  ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Ex-Torex chairman may be FSA''s iSoft witness</title>\r\n  <link>http://tech.originalsignal.com/article/57537/ex-torex-chairman-may-be-fsas-isoft-witness.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 07:08:45 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57537/ex-torex-chairman-may-be-fsas-isoft-witness.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  AccountancyAge.com, Accountancy Age, Thursday 21 June 2007 at 00:00:00FSA looking to call on Christopher MooreThe Financial Services Authority investigation into accounting irregularities uncovered at iSoft, the NHS software supplier could see Christopher Moore, the former chairman at Torex Retail, being called as a witness....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Deloitte chief: ''Genuine effort'' needed by governments</title>\r\n  <link>http://tech.originalsignal.com/article/57538/deloitte-chief-genuine-effort-needed-by-governments.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 07:08:45 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57538/deloitte-chief-genuine-effort-needed-by-governments.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  AccountancyAge.com, Accountancy Age, Thursday 21 June 2007 at 00:00:00Deloitte boss comments on SEC announcementGovernments and regulators must continue with genuine efforts to achieve consistency in the development and application of global financial reporting standards to protect investors, Deloitte global CEO James H....&gt;&nbsp;Read the full article&nbsp;&nbsp;&nbsp;&nbsp;    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>Blu-ray content protection agency certifies BD+</title>\r\n  <link>http://tech.originalsignal.com/article/57536/blu-ray-content-protection-agency-certifies-bd.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 07:08:24 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57536/blu-ray-content-protection-agency-certifies-bd.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  The company providing the additional BD+ copy protection to Blu-ray discs has just announced the finalization of the BD+ spec.  The announcement opens the door for more movie studios to release content on Blu-ray, but raises many questions about just what the studios plan to do with the power that BD+ provides.Read More...    ]]></content:encoded>\r\n  </item>\r\n    <item>\r\n  <title>The Pirate Bay Launches Uncensored Image Hosting</title>\r\n  <link>http://tech.originalsignal.com/article/57534/the-pirate-bay-launches-uncensored-image-hosting.html</link>\r\n  <pubDate>Thu, 21 Jun 2007 07:08:06 CEST</pubDate>\r\n  <guid isPermaLink="false">http://tech.originalsignal.com/article/57534/the-pirate-bay-launches-uncensored-image-hosting.html</guid>\r\n  <author />\r\n  <content:encoded><![CDATA[\n  BayImg, an uncensored image hosting service, is the latest side-project from The Pirate Bay folks. The main difference compared to other image hosting services is that they pretty much allow everything on there, freedom of speech above all.  ]]></content:encoded>\r\n  </item>\r\n    	\r\n	</channel>\r\n</rss>', '20070621103354'),
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

INSERT INTO `dsb_flirts` (`flirt_id`, `flirt_text`, `flirt_type`) VALUES 
(5, 'Hey sexy!', 0),
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

INSERT INTO `dsb_lang_keys` (`lk_id`, `lk_type`, `lk_diz`, `lk_use`) VALUES 
(1, 2, 'Category name', 1),
(2, 2, 'Category name', 1),
(3, 2, 'Label for f1 field', 1),
(4, 2, 'Search label for f1 field', 1),
(5, 4, 'Help text for f1 field', 1),
(6, 2, 'Field value', 1),
(7, 2, 'Field value', 1),
(8, 2, 'Label for f2 field', 1),
(9, 2, 'Search label for f2 field', 1),
(10, 4, 'Help text for f2 field', 1),
(11, 2, 'Field value', 1),
(12, 2, 'Field value', 1),
(13, 2, 'Label for f3 field', 1),
(14, 2, 'Search label for f3 field', 1),
(15, 4, 'Help text for f3 field', 1),
(46, 2, 'Field value', 1),
(47, 2, 'Field value', 1),
(48, 2, 'Field value', 1),
(49, 2, 'Field value', 1),
(22, 2, 'Label for f4 field', 1),
(23, 2, 'Search label for f4 field', 1),
(24, 4, 'Help text for f4 field', 1),
(25, 2, 'Label for f5 field', 1),
(26, 2, 'Search label for f5 field', 1),
(27, 4, 'Help text for f5 field', 1),
(28, 2, 'Label for f6 field', 1),
(29, 2, 'Search label for f6 field', 1),
(30, 4, 'Help text for f6 field', 1),
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
(43, 2, 'Label for f7 field', 1),
(44, 2, 'Search label for f7 field', 1),
(45, 4, 'Help text for f7 field', 1),
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
(116, 2, 'Label for f8 field', 1),
(117, 2, 'Search label for f8 field', 1),
(118, 4, 'Help text for f8 field', 1),
(119, 2, 'Field value', 1),
(120, 2, 'Field value', 1),
(121, 2, 'Field value', 1),
(122, 2, 'Field value', 1),
(123, 2, 'Field value', 1),
(124, 2, 'Field value', 1),
(125, 2, 'Label for f9 field', 1),
(126, 2, 'Search label for f9 field', 1),
(127, 4, 'Help text for f9 field', 1),
(128, 2, 'Field value', 1),
(129, 2, 'Field value', 1),
(130, 2, 'Field value', 1),
(131, 2, 'Field value', 1),
(132, 2, 'Field value', 1),
(133, 2, 'Field value', 1),
(134, 2, 'Field value', 1),
(135, 2, 'Field value', 1),
(136, 2, 'Field value', 1),
(137, 2, 'Label for f10 field', 1),
(138, 2, 'Search label for f10 field', 1),
(139, 4, 'Help text for f10 field', 1),
(140, 2, 'Field value', 1),
(141, 2, 'Field value', 1),
(142, 2, 'Field value', 1),
(143, 2, 'Label for f11 field', 1),
(144, 2, 'Search label for f11 field', 1),
(145, 4, 'Help text for f11 field', 1),
(146, 2, 'Field value', 1),
(147, 2, 'Field value', 1),
(148, 2, 'Field value', 1),
(149, 2, 'Label for f12 field', 1),
(150, 2, 'Search label for f12 field', 1),
(151, 4, 'Help text for f12 field', 1),
(152, 2, 'Field value', 1),
(153, 2, 'Field value', 1),
(154, 2, 'Field value', 1),
(155, 2, 'Field value', 1),
(156, 2, 'Field value', 1),
(157, 2, 'Label for f13 field', 1),
(158, 2, 'Search label for f13 field', 1),
(159, 4, 'Help text for f13 field', 1),
(160, 2, 'Field value', 1),
(161, 2, 'Field value', 1),
(162, 2, 'Field value', 1),
(163, 2, 'Field value', 1),
(164, 2, 'Label for f14 field', 1),
(165, 2, 'Search label for f14 field', 1),
(166, 4, 'Help text for f14 field', 1),
(167, 2, 'Field value', 1),
(168, 2, 'Field value', 1),
(169, 2, 'Field value', 1),
(170, 2, 'Field value', 1),
(171, 2, 'Field value', 1),
(172, 2, 'Field value', 1),
(173, 2, 'Field value', 1),
(174, 2, 'Label for f15 field', 1),
(175, 2, 'Search label for f15 field', 1),
(176, 4, 'Help text for f15 field', 1),
(177, 2, 'Field value', 1),
(181, 2, 'Error message for a limit', 0),
(182, 2, 'Error message for a limit', 0),
(183, 2, 'Error message for a limit', 0),
(184, 2, 'Ban reason', 2),
(185, 2, 'Ban reason', 2),
(186, 2, 'Ban reason', 2),
(187, 2, 'Ban reason', 2);

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

INSERT INTO `dsb_lang_strings` (`ls_id`, `fk_lk_id`, `skin`, `lang_value`) VALUES 
(1, 1, 'skin_def', 'Basic Info'),
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
(199, 137, 'skin_def', 'My hair is'),
(200, 138, 'skin_def', 'Hair'),
(201, 139, 'skin_def', ''),
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
(176, 176, 'skin_def', ''),
(177, 177, 'skin_def', 'e'),
(188, 182, 'skin_def', 'bah'),
(187, 181, 'skin_def', 'asta e, incerci mai tarziu'),
(189, 183, 'skin_def', 'grgr'),
(190, 184, 'skin_def', 'de jmeker'),
(194, 185, 'skin_def', 'asa'),
(193, 186, 'skin_def', 'mumu'),
(195, 187, 'skin_def', 'tets');

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

INSERT INTO `dsb_locales` (`locale_id`, `locale_name`, `codes`) VALUES 
(1, 'Arabic (Algeria)', 'ar_DZ,arabic'),
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

INSERT INTO `dsb_memberships` (`m_id`, `m_name`, `m_value`, `is_custom`) VALUES 
(1, 'Non Members', 1, 0),
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

INSERT INTO `dsb_message_filters` (`filter_id`, `filter_type`, `fk_user_id`, `field`, `field_value`, `fk_folder_id`) VALUES 
(2, 1, 23, '', '7', 5),
(5, 1, 6, '', '16', -3),
(6, 1, 4, '', '15', -3);

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

INSERT INTO `dsb_modules` (`module_code`, `module_name`, `module_diz`, `module_type`, `version`) VALUES 
(0x636f7265, 'Basic features', '', 0, 1.00),
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

INSERT INTO `dsb_networks` (`net_id`, `network`, `is_bidi`, `max_users`) VALUES 
(1, 'Friends', 1, 0),
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

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES 
(0, '20070621213959', 0x3131326462393263616431316561616263643631326336306662303535353037),
(0, '20070621201106', 0x6434623833303233333033303638353530656163653161666562396437343735),
(0, '20070622090932', 0x3238636332353435313037656562363636666165363332666565383166386665),
(0, '20070621112440', 0x6231353563393831643934353866666164303732303230323332303165333130);

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
  `refunded` float(10,2) NOT NULL default '0.00',
  `paid_from` date NOT NULL default '0000-00-00',
  `paid_until` date NOT NULL default '0000-00-00',
  `is_suspect` tinyint(1) unsigned NOT NULL default '0',
  `suspect_reason` text NOT NULL,
  `date` timestamp(14) NOT NULL,
  PRIMARY KEY  (`payment_id`),
  KEY `date` (`date`)
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
  KEY `key1` (`fk_parent_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_photo_comments`
-- 

INSERT INTO `dsb_photo_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`, `processed`) VALUES 
(1, 1, 6, 'cocacola', 'hi thetr', '2007-04-16 18:54:48', '2007-04-16 18:54:48', 15, 0),
(2, 1, 9, 'raneglo', 'Hello Mr Watermark', '2007-04-16 19:59:47', '2007-04-16 19:59:47', 15, 0),
(3, 6, 10, 'strawberries', 'party on, dude!', '2007-04-16 21:12:23', '2007-04-16 21:12:23', 15, 0),
(4, 1, 11, 'johnboy', 'Hi Maverick, your pic looks cool :)', '2007-04-17 04:46:16', '2007-04-17 04:46:16', 15, 0),
(5, 5, 11, 'johnboy', 'I wonder if you might be Dan.... are you?', '2007-04-17 04:49:33', '2007-04-17 04:49:33', 15, 0),
(6, 9, 11, 'johnboy', 'Looks pretty cold there', '2007-04-17 05:35:36', '2007-04-17 05:35:36', 15, 0),
(7, 9, 13, 'johnboy2', 'YES it is', '2007-04-17 12:29:32', '2007-04-17 12:29:32', 15, 0),
(8, 5, 11, 'johnboy', 'Can a user delete their comments? as I''m pretty sure you are Dan.', '2007-04-17 12:36:11', '2007-04-17 12:36:11', 15, 0),
(9, 13, 10, 'strawberries', 'meooooooooowwwwww', '2007-04-17 21:15:06', '2007-04-17 21:15:06', 15, 0),
(10, 9, 10, 'strawberries', 'cooooooooooooolddddddddd, like here in Ireland in winter\r\n\r\nmeeeeeeeeeooooooooow', '2007-04-17 21:17:15', '2007-04-17 21:17:15', 15, 0),
(11, 11, 10, 'strawberries', 'nice car.', '2007-04-17 22:52:26', '2007-04-17 22:52:26', 15, 0),
(12, 9, 4, 'maverick', 'Hi johnboy, I grew up in Canada so I know what freeeezing cold winters are like, but now I live in Arizona :)', '2007-04-17 22:53:39', '2007-04-17 22:53:39', 15, 0),
(13, 5, 7, 'dragon', 'It''s me, it''s me.\r\nThe damn script wouln''t let me use a 3 chars username so I had to go with dragon :)\r\nAn admin can delete any comment. Haven''t thought about users but it''s easy to add a delete link.', '2007-04-18 15:50:35', '2007-04-18 15:50:35', 15, 0),
(14, 5, 1, 'emma', 'I love my dad :))', '2007-04-18 22:39:08', '2007-04-18 22:39:08', 15, 0),
(15, 5, 1, 'emma', 'test comment notification', '2007-04-19 14:27:49', '2007-04-19 14:27:49', 15, 0),
(16, 5, 11, 'johnboy', '[b]Dan aka Dragon said[/b][quote] "but it''s easy to add a delete link." [/quote]Thats [b]GREAT[/b] to hear, as webdate had a feature like that and the user could not delete nasty comments left by small minded people, and they had to write to me and I would have to manually delete them.... which was a pain for the user and for me.', '2007-04-20 02:34:02', '2007-04-20 02:34:02', 15, 0),
(17, 9, 12, 'a_l_f', 'Thank heavens i live in QLD OZ :-) sunny one day perfect the next :-)', '2007-04-20 03:40:44', '2007-04-20 03:40:44', 15, 0),
(18, 9, 13, 'johnboy2', 'Hehehe I lived in Brisbane QLD for a year and really got sick of blue skies every single day. I missed the 4 seasons of NZ :)', '2007-04-20 04:19:10', '2007-04-20 04:19:10', 15, 0),
(19, 17, 2, 'keith', 'test', '2007-04-20 22:07:15', '2007-04-20 22:07:15', 15, 0),
(20, 6, 23, 'zooki', 'That cats got nice home', '2007-04-21 11:36:39', '2007-04-21 11:36:39', 15, 0),
(21, 21, 7, 'dragon', 'mmmm looks cool', '2007-04-21 12:15:18', '2007-04-21 12:15:18', 15, 0),
(22, 6, 10, 'strawberries', 'Fluffy has passed in her thanks, zooki!\r\n\r\nShe would post herself, but unfortunately due to a late night on the tiles, she is sleeping at the moment. :)\r\n\r\n', '2007-04-21 14:24:36', '2007-04-21 14:24:36', 15, 0),
(23, 6, 10, 'strawberries', 'sorry, typos, grrr\r\n\r\n''passed [b]on[/b] her thanks''', '2007-04-21 14:25:32', '2007-04-21 14:25:32', 15, 0),
(24, 5, 10, 'strawberries', 'good point\r\n\r\ngot me thinking\r\n\r\nit would be really cool if you could have a setting, whereby if a certain number of posters (e.g. 3) thought a comment was inappropriate they could delete the comment, by clicking on a link beside the comment.  Once the default level had been reached - e.g. say three posters clicking on INAPPOPRIATE......then the comment would be removed automatically from the thread (and sit in a box for the webmaster''s perusal at a later point.\r\n\r\nThis would be self-policing.  It woudl save the webmaster a lot of time and mean less insceuirty.\r\n\r\nAnd also by the removed comment still sitting in a private place for him to look at later, he could decide if the poster was being unfairly picked on by others, or whatever.  \r\n', '2007-04-22 03:23:18', '2007-04-22 03:23:18', 15, 0),
(26, 7, 10, 'strawberries', 'test', '2007-04-25 13:49:23', '2007-04-25 13:49:23', 15, 0),
(27, 20, 10, 'strawberries', 'cute bear!', '2007-04-27 09:08:45', '2007-04-27 09:08:45', 15, 0),
(28, 22, 8, 'angkasa', 'cool picture', '2007-04-29 17:16:04', '2007-04-29 17:16:04', 15, 0),
(29, 9, 10, 'strawberries', 'New Zealand, wow, only after seeing that you are in NZ, johnboy!\r\n\r\ni was close to moving to NZ a year or so ago.  I have aunts out there, since the late 50s and mid 60s.....one in Orewa and one further north.\r\n\r\nWhere roughly are you at?  NZ looks awesome......the most beautiful place i have seen (from pics).  And colleagues here have said that it is the best place they have ever visited.\r\n\r\nBTW I am in N Ireland.', '2007-04-30 00:37:38', '2007-04-30 00:37:38', 15, 0),
(30, 9, 13, 'johnboy2', 'Well I am an Australian originally, but moved here 23 years ago to Dunedin, its in the bottom half of the South Island. SPECTACULAR scenery. It''s the worlds BEST KEPT SECRET.', '2007-04-30 04:08:26', '2007-04-30 04:08:26', 15, 0),
(31, 9, 13, 'johnboy2', 'Maverick said:[quote]I grew up in Canada so I know what freeeezing cold winters are like[/quote]\r\n\r\nYES ...so i have heard Mav, I had a friend that lived in toronto I think, and he said it gets much colder than here in NZ', '2007-04-30 04:10:58', '2007-04-30 04:10:58', 15, 0),
(32, 9, 10, 'strawberries', 'Dunedin, yes, my next door neighbour was there, way back in the 70s IIRC.  He LOVED it - told me recently that Dunedin was like Scotland - lots of scottish influence.\r\n\r\nYes, that south island apparently is amazing.  I had a colleague, who has travelled the world, and he ranked south island as the most beautiful scenery he has ever seen on this planet.', '2007-04-30 15:47:14', '2007-04-30 15:47:14', 15, 0),
(33, 9, 13, 'johnboy2', 'I hear Ireland is pretty spectacular too and they have the best accent in the world.', '2007-04-30 22:10:40', '2007-04-30 22:10:40', 15, 0),
(34, 29, 8, 'angkasa', 'Yeah, Rock Never Die Mate!', '2007-05-11 07:39:17', '2007-05-11 07:39:17', 15, 0),
(35, 20, 29, 'traderjoe', 'Definitely!', '2007-05-12 22:33:19', '2007-05-12 22:33:19', 15, 0),
(36, 11, 32, 'mervyn', 'agree..... lovely car indeed, wish I can own one someday', '2007-05-25 23:52:15', '2007-05-25 23:52:15', 15, 0);

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

INSERT INTO `dsb_photo_ratings` (`fk_photo_id`, `fk_user_id`, `vote`, `date_voted`) VALUES 
(1, 6, 4, '2007-04-16 18:54:40'),
(5, 1, 5, '2007-04-16 19:45:27'),
(5, 8, 5, '2007-04-16 20:16:18'),
(1, 11, 5, '2007-04-17 04:46:33'),
(5, 11, 5, '2007-04-17 04:49:46'),
(8, 13, 5, '2007-04-17 05:22:43'),
(9, 11, 4, '2007-04-17 05:35:43'),
(13, 10, 5, '2007-04-17 21:15:12'),
(9, 10, 5, '2007-04-17 21:16:47'),
(11, 10, 5, '2007-04-17 22:52:08'),
(16, 19, 3, '2007-04-19 13:08:11'),
(1, 19, 4, '2007-04-19 13:08:27'),
(11, 19, 3, '2007-04-19 13:08:38'),
(5, 19, 5, '2007-04-19 13:09:19'),
(10, 11, 4, '2007-04-20 02:27:29'),
(5, 11, 5, '2007-04-20 02:41:27'),
(14, 12, 1, '2007-04-20 03:37:42'),
(9, 12, 5, '2007-04-20 03:38:32'),
(16, 10, 5, '2007-04-20 05:47:55'),
(13, 23, 1, '2007-04-21 11:32:31'),
(21, 7, 5, '2007-04-21 12:15:22'),
(21, 11, 5, '2007-04-23 04:47:39'),
(10, 11, 5, '2007-04-23 05:03:09'),
(20, 10, 5, '2007-04-27 09:08:52'),
(25, 8, 5, '2007-04-27 19:42:53'),
(25, 11, 5, '2007-04-29 13:01:01'),
(25, 14, 5, '2007-04-29 13:32:02'),
(27, 11, 4, '2007-04-29 22:07:00'),
(25, 6, 4, '2007-04-30 22:01:21'),
(20, 29, 5, '2007-05-12 22:33:33'),
(21, 29, 5, '2007-05-12 22:40:09'),
(32, 11, 5, '2007-05-22 22:26:21'),
(33, 11, 5, '2007-05-22 22:27:43'),
(29, 19, 4, '2007-05-25 13:42:00'),
(25, 10, 5, '2007-05-27 23:51:19'),
(24, 10, 1, '2007-05-27 23:51:52'),
(27, 10, 5, '2007-06-01 15:35:23');

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

INSERT INTO `dsb_profile_categories` (`pcat_id`, `fk_lk_id_pcat`, `access_level`) VALUES 
(1, 1, 7),
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
  `processed` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_parent_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_profile_comments`
-- 

INSERT INTO `dsb_profile_comments` (`comment_id`, `fk_parent_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`, `processed`) VALUES 
(1, 19, 8, 'angkasa', 'Nice pose mate.heheh', '2007-05-13 17:48:27', '2007-05-13 17:48:27', 15, 0),
(2, 32, 32, 'mervyn', 'test', '2007-05-25 23:57:46', '2007-05-25 23:57:46', 15, 0),
(3, 10, 10, 'strawberries', 'test', '2007-05-28 11:48:10', '2007-05-28 11:48:10', 15, 0),
(4, 7, 33, 'alterego', 'fff', '2007-06-04 14:09:56', '2007-06-04 14:09:56', 15, 1);

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

INSERT INTO `dsb_profile_fields` (`pfield_id`, `fk_lk_id_label`, `field_type`, `searchable`, `search_type`, `for_basic`, `fk_lk_id_search`, `at_registration`, `reg_page`, `required`, `editable`, `visible`, `dbfield`, `fk_lk_id_help`, `fk_pcat_id`, `access_level`, `accepted_values`, `default_value`, `default_search`, `fn_on_change`, `order_num`) VALUES 
(1, 3, 4, 0, 1, 0, 4, 1, 2, 0, 1, 1, 0x6631, 5, 1, 0, '1000', '', '', '', 5),
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

INSERT INTO `dsb_queue_message` (`mail_id`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES 
(9, 0, 0, '', 'New comment on your profile', 'dragon posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm5">Click here</a> to view the comment', '2007-05-15 09:28:36', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_rate_limiter`
-- 

DROP TABLE IF EXISTS `dsb_rate_limiter`;
CREATE TABLE `dsb_rate_limiter` (
  `rate_id` int(10) unsigned NOT NULL auto_increment,
  `level_code` varchar(30) binary NOT NULL default '',
  `m_value` int(10) unsigned NOT NULL default '0',
  `limit` int(5) unsigned NOT NULL default '0',
  `interval` int(10) unsigned NOT NULL default '0',
  `punishment` tinyint(1) unsigned NOT NULL default '0',
  `fk_lk_id_error_message` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rate_id`),
  KEY `thekey` (`level_code`,`m_value`)
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
  `fk_lk_id_reason` int(5) unsigned NOT NULL default '0',
  `since` timestamp(14) NOT NULL,
  PRIMARY KEY  (`ban_id`),
  UNIQUE KEY `key1` (`ban_type`,`what`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_bans`
-- 

INSERT INTO `dsb_site_bans` (`ban_id`, `ban_type`, `what`, `fk_lk_id_reason`, `since`) VALUES 
(2, 3, '3232235521', 186, '20070613205155');

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
  `level_code` varchar(30) binary NOT NULL default '',
  `ip` int(12) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `sess` varchar(32) binary NOT NULL default '',
  PRIMARY KEY  (`log_id`),
  KEY `user` (`user`),
  KEY `fk_user_id` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_site_log`
-- 


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

INSERT INTO `dsb_site_news` (`news_id`, `news_title`, `news_body`, `date_posted`) VALUES 
(1, 'first news', '<p>N<font size="1">e</font><font size="2">w</font><font size="3">s</font> <font size="4">b</font><font size="5">o</font><font size="6">d</font><font size="7">y</font></p>', '0000-00-00 00:00:00'),
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
  `choices` text NOT NULL,
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

INSERT INTO `dsb_site_options3` (`config_id`, `config_option`, `config_value`, `config_diz`, `option_type`, `choices`, `fk_module_code`, `per_user`) VALUES 
(1, 0x64626669656c645f696e646578, '16', 'The last index of the custom profile fields (field_xx)', 0, '', 0x636f7265, 0),
(2, 0x7573655f63617074636861, '1', 'Use the dynamic image text (captcha image) to keep spam bots out?', 9, '', 0x636f7265, 0),
(3, 0x6d616e75616c5f70726f66696c655f617070726f76616c, '0', 'New profiles or changes to existing profiles require manual approval from an administrator before being displayed on site?', 9, '', 0x636f7265, 0),
(4, 0x646174655f666f726d6174, '%m/%d/%Y', 'Default date format', 2, '', 0x6465665f757365725f7072656673, 1),
(5, 0x74315f7769647468, '100', 'The width in pixels of the smallest thumbnail generated for each user photo', 104, '', 0x636f72655f70686f746f, 0),
(6, 0x74325f7769647468, '500', 'The width in pixels of the larger thumbnail generated for each user photo', 104, '', 0x636f72655f70686f746f, 0),
(7, 0x7069635f7769647468, '800', 'The maximum width in pixels of any picture uploaded by a member', 104, '', 0x636f72655f70686f746f, 0),
(8, 0x6d616e75616c5f70686f746f5f617070726f76616c, '0', 'New uploaded photos require manual approval before being displayed on the site?', 9, '', 0x636f72655f70686f746f, 0),
(9, 0x6d616e75616c5f626c6f675f617070726f76616c, '0', 'New blog posts or changes to existing posts require manual approval from an administrator before being displayed on site?', 9, '', 0x636f72655f626c6f67, 0),
(10, 0x6d616e75616c5f636f6d5f617070726f76616c, '0', 'Comments to profiles, photos, blogs need approval from admin?', 9, '', 0x636f7265, 0),
(11, 0x77617465726d61726b5f74657874, 'watermark text', 'The text to stamp the user photos with', 2, '', 0x636f72655f70686f746f, 0),
(12, 0x77617465726d61726b5f746578745f636f6c6f72, 'FFFFFF', 'Color of the text watermark', 2, '', 0x636f72655f70686f746f, 0),
(13, 0x6d6f64756c655f616374697665, '1', 'Module active?', 9, '', 0x70617970616c, 0),
(14, 0x70617970616c5f656d61696c, 'dan@rdsct.ro', 'Your paypal email address', 2, '', 0x70617970616c, 0),
(15, 0x6d6f64756c655f616374697665, '1', 'Is this module active?', 9, '', 0x74776f636865636b6f7574, 0),
(16, 0x736964, '117760', 'Your 2co seller ID', 2, '', 0x74776f636865636b6f7574, 0),
(17, 0x64656d6f5f6d6f6465, '1', 'Enable test mode? Don''t enable this on a live site!', 9, '', 0x74776f636865636b6f7574, 0),
(18, 0x64656d6f5f6d6f6465, '1', 'Enable test mode? Don''t enable this on a live site!', 9, '', 0x70617970616c, 0),
(19, 0x736563726574, 'secret_word', 'The secret word you set in your 2co account', 2, '', 0x74776f636865636b6f7574, 0),
(20, 0x6c6963656e73655f6b6579, '1234', 'Your Maxmind license key', 2, '', 0x6d61786d696e64, 0),
(21, 0x7573655f7175657565, '1', 'Use the message queue (recommended) or send the messages directly?', 9, '', 0x636f7265, 0),
(22, 0x6d61696c5f66726f6d, 'support@datemill.com', 'Email address to send emails from', 2, '', 0x636f7265, 0),
(23, 0x6262636f64655f70726f66696c65, '1', 'Use BBcode in profile fields? (like about me, about you)', 9, '', 0x636f7265, 0),
(24, 0x6262636f64655f636f6d6d656e7473, '1', 'Use BBcode in comments?', 9, '', 0x636f7265, 0),
(32, 0x6d696e5f73697a65, '0', 'Minimum photo file size in bytes (use 0 for not limited).', 104, '', 0x636f72655f70686f746f, 0),
(33, 0x6d61785f73697a65, '0', 'Maximum photo file size in bytes (use 0 for server default).', 104, '', 0x636f72655f70686f746f, 0),
(34, 0x6262636f64655f6d657373616765, '1', 'Allow BBCode in member to member messages?', 9, '', 0x636f7265, 0),
(35, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %I:%M %p', 'Date and time format', 2, '', 0x6465665f757365725f7072656673, 1),
(36, 0x726f756e645f636f726e657273, '1', 'Use round corners for user photos?', 9, '', 0x636f72655f70686f746f, 0),
(37, 0x656e61626c6564, '1', 'Enable this widget?', 9, '', 0x6f7369676e616c5f66656564, 0),
(38, 0x666565645f75726c, 'http://feeds.feedburner.com/OriginalSignal/tech', 'The url of the feed', 2, '', 0x6f7369676e616c5f66656564, 0),
(39, 0x736b696e5f646972, 'def', '', 0, '', 0x736b696e5f646566, 0),
(40, 0x736b696e5f6e616d65, 'Default', '', 0, '', 0x736b696e5f646566, 0),
(41, 0x666b5f6c6f63616c655f6964, '11', '', 0, '', 0x736b696e5f646566, 0),
(42, 0x69735f64656661756c74, '1', '', 0, '', 0x736b696e5f646566, 0),
(43, 0x696e6163746976655f74696d65, '5', 'Time of inactivity in minutes after a member is considered offline', 104, '', 0x636f7265, 0),
(44, 0x6262636f64655f626c6f6773, '1', 'Allow bbcode in blog posts?', 9, '', 0x636f72655f626c6f67, 0),
(45, 0x73656e645f616c6572745f696e74657276616c, '2', 'How often do you want to receive your search matches? (days)', 104, '', 0x6465665f757365725f7072656673, 1),
(46, 0x726174655f6d795f70726f66696c65, '1', 'Allow my profile to be rated?', 9, '', 0x6465665f757365725f7072656673, 1),
(47, 0x726174655f6d795f70686f746f73, '1', 'Allow my photos to be rated?', 9, '', 0x6465665f757365725f7072656673, 1),
(48, 0x70757267655f756e7665726966696564, '7', 'Purge unverified accounts after how many days?', 104, '', 0x636f7265, 0),
(50, 0x6e6f746966795f6d65, '1', 'Send me email notifications when I receive messages?', 9, '', 0x6465665f757365725f7072656673, 1),
(51, 0x6d61696c5f63726c66, '1', 'Check or uncheck this option if you can''t send emails out to members', 9, '', 0x636f7265, 0),
(52, 0x7573655f736d696c696573, '1', 'Allow smilies in profile fields?', 9, '', 0x636f7265, 0),
(53, 0x736d696c6965735f636f6d6d, '1', 'Allow smilies in user comments?', 9, '', 0x636f7265, 0),
(54, 0x7573655f736d696c696573, '1', 'Allow smilies in blogs?', 9, '', 0x636f72655f626c6f67, 0),
(55, 0x70726f66696c655f636f6d6d656e7473, '1', 'Allow comments on my profile?', 9, '', 0x6465665f757365725f7072656673, 1),
(56, 0x74615f6c656e, '1000', 'Maximum number of characters users may enter in textareas (use 0 for unlimited)', 104, '', 0x636f7265, 0),
(58, 0x666565645f75726c, 'http://www.datemill.com/remote/feeds/admin.xml', 'The url of the feed', 2, '', 0x646174656d696c6c5f66656564, 0),
(59, 0x70757267655f696e626f78, '30', 'Purge old messages from member inboxes after how many days? (0 for never)', 104, '', 0x636f7265, 0),
(60, 0x70757267655f7472617368, '14', 'Purge old messages from member spam boxes and trash after how many days? (0 for never)', 104, '', 0x636f7265, 0),
(61, 0x70757267655f666f6c64657273, '30', 'Purge old messages from member personal folders after how many days? (0 for never)', 104, '', 0x636f7265, 0),
(62, 0x70757267655f6f7574626f78, '7', 'Purge old messages from member outboxes after how many days? (0 for never)', 104, '', 0x636f7265, 0),
(63, 0x74696d655f6f6666736574, '0', 'Select your timezone (<a href="javascript:;" id="auto_detect_tz">auto detect</a>)', 3, 'a:26:{i:-43200;s:7:"GMT -12";i:-39600;s:7:"GMT -11";i:-36000;s:7:"GMT -10";i:-32400;s:6:"GMT -9";i:-28800;s:6:"GMT -8";i:-25200;s:6:"GMT -7";i:-21600;s:6:"GMT -6";i:-18000;s:6:"GMT -5";i:-14400;s:6:"GMT -4";i:-10800;s:6:"GMT -3";i:-7200;s:6:"GMT -2";i:-3600;s:6:"GMT -1";i:0;s:3:"GMT";i:3600;s:6:"GMT +1";i:7200;s:6:"GMT +2";i:10800;s:6:"GMT +3";i:1440;s:6:"GMT +4";i:18000;s:6:"GMT +5";i:21600;s:6:"GMT +6";i:25200;s:6:"GMT +7";i:28800;s:6:"GMT +8";i:32400;s:6:"GMT +9";i:36000;s:7:"GMT +10";i:39600;s:7:"GMT +11";i:43200;s:7:"GMT +12";i:46800;s:7:"GMT +13";}', 0x6465665f757365725f7072656673, 1);

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

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES 
('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,34', 0, '20070622000855'),
('8d47520feff17b8632f86ffaec79cfad', 1, 'a:9:{s:13:"acclevel_code";s:15:"search_advanced";s:2:"st";s:3:"adv";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f5_max";s:2:"75";s:10:"f6_country";i:0;s:2:"f9";i:0;s:3:"f13";i:0;s:3:"f14";i:0;}', '1,22', 7, '20070621125946'),
('4b7448aab16257e1c55485520b157743', 1, 'a:1:{s:4:"user";s:6:"shadow";}', '17', 0, '20070622105435');

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

INSERT INTO `dsb_stats_dot` (`dot_id`, `dataset`, `value`, `time`) VALUES 
(780, 'paid_members', 25, 20070520),
(779, 'paid_members', 25, 20070519),
(778, 'paid_members', 25, 20070518),
(777, 'paid_members', 22, 20070517),
(776, 'paid_members', 24, 20070516),
(775, 'paid_members', 22, 20070515),
(774, 'paid_members', 24, 20070514),
(773, 'paid_members', 21, 20070513),
(772, 'paid_members', 18, 20070512),
(771, 'paid_members', 17, 20070511),
(770, 'paid_members', 14, 20070510),
(769, 'paid_members', 12, 20070509),
(768, 'paid_members', 10, 20070508),
(767, 'paid_members', 12, 20070507),
(766, 'paid_members', 10, 20070506),
(765, 'paid_members', 12, 20070505),
(764, 'paid_members', 13, 20070504),
(763, 'paid_members', 11, 20070503),
(762, 'paid_members', 11, 20070502),
(761, 'paid_members', 11, 20070501),
(760, 'paid_members', 10, 20070430),
(759, 'paid_members', 12, 20070429),
(758, 'paid_members', 11, 20070428),
(757, 'paid_members', 12, 20070427),
(756, 'paid_members', 11, 20070426),
(755, 'paid_members', 13, 20070425),
(754, 'paid_members', 12, 20070424),
(753, 'paid_members', 9, 20070423),
(752, 'paid_members', 8, 20070422),
(751, 'paid_members', 6, 20070421),
(750, 'paid_members', 7, 20070420),
(749, 'paid_members', 5, 20070419),
(748, 'paid_members', 7, 20070418),
(747, 'paid_members', 5, 20070417),
(746, 'paid_members', 4, 20070416),
(745, 'paid_members', 3, 20070415),
(744, 'paid_members', 1, 20070414),
(743, 'paid_members', 2, 20070413),
(742, 'paid_members', 3, 20070412),
(741, 'paid_members', 0, 20070411),
(740, 'paid_members', 0, 20070410),
(42, 'num_users', 5, 20070410),
(43, 'num_users', 9, 20070411),
(44, 'num_users', 16, 20070412),
(45, 'num_users', 19, 20070413),
(46, 'num_users', 32, 20070414),
(47, 'num_users', 51, 20070415),
(48, 'num_users', 51, 20070416),
(49, 'num_users', 53, 20070417),
(50, 'num_users', 63, 20070418),
(51, 'num_users', 64, 20070419),
(52, 'num_users', 66, 20070420),
(53, 'num_users', 86, 20070421),
(54, 'num_users', 103, 20070422),
(55, 'num_users', 110, 20070423),
(56, 'num_users', 119, 20070424),
(57, 'num_users', 123, 20070425),
(58, 'num_users', 123, 20070426),
(59, 'num_users', 128, 20070427),
(60, 'num_users', 131, 20070428),
(61, 'num_users', 138, 20070429),
(62, 'num_users', 155, 20070430),
(63, 'num_users', 166, 20070501),
(64, 'num_users', 170, 20070502),
(65, 'num_users', 173, 20070503),
(66, 'num_users', 188, 20070504),
(67, 'num_users', 192, 20070505),
(68, 'num_users', 193, 20070506),
(69, 'num_users', 199, 20070507),
(70, 'num_users', 218, 20070508),
(71, 'num_users', 237, 20070509),
(72, 'num_users', 245, 20070510),
(73, 'num_users', 249, 20070511),
(74, 'num_users', 268, 20070512),
(75, 'num_users', 269, 20070513),
(76, 'num_users', 288, 20070514),
(77, 'num_users', 307, 20070515),
(78, 'num_users', 307, 20070516),
(79, 'num_users', 311, 20070517),
(80, 'num_users', 320, 20070518),
(81, 'num_users', 337, 20070519),
(82, 'num_users', 352, 20070520),
(84, 'online_users', 6, 20070410),
(85, 'online_users', 10, 20070411),
(86, 'online_users', 6, 20070412),
(87, 'online_users', 3, 20070413),
(88, 'online_users', 5, 20070414),
(89, 'online_users', 2, 20070415),
(90, 'online_users', 3, 20070416),
(91, 'online_users', 8, 20070417),
(92, 'online_users', 8, 20070418),
(93, 'online_users', 10, 20070419),
(94, 'online_users', 5, 20070420),
(95, 'online_users', 8, 20070421),
(96, 'online_users', 6, 20070422),
(97, 'online_users', 9, 20070423),
(98, 'online_users', 10, 20070424),
(99, 'online_users', 9, 20070425),
(100, 'online_users', 10, 20070426),
(101, 'online_users', 9, 20070427),
(102, 'online_users', 2, 20070428),
(103, 'online_users', 8, 20070429),
(104, 'online_users', 9, 20070430),
(105, 'online_users', 7, 20070501),
(106, 'online_users', 4, 20070502),
(107, 'online_users', 8, 20070503),
(108, 'online_users', 6, 20070504),
(109, 'online_users', 9, 20070505),
(110, 'online_users', 0, 20070506),
(111, 'online_users', 4, 20070507),
(112, 'online_users', 10, 20070508),
(113, 'online_users', 8, 20070509),
(114, 'online_users', 2, 20070510),
(115, 'online_users', 6, 20070511),
(116, 'online_users', 2, 20070512),
(117, 'online_users', 3, 20070513),
(118, 'online_users', 9, 20070514),
(119, 'online_users', 5, 20070515),
(120, 'online_users', 4, 20070516),
(121, 'online_users', 0, 20070517),
(122, 'online_users', 10, 20070518),
(123, 'online_users', 5, 20070519),
(124, 'online_users', 7, 20070520),
(781, 'online_users', 1, 20070521),
(782, 'num_users', 31, 20070521),
(783, 'online_users', 2, 20070522),
(784, 'num_users', 31, 20070522),
(785, 'online_users', 1, 20070523),
(786, 'num_users', 31, 20070523),
(787, 'online_users', 2, 20070524),
(788, 'num_users', 31, 20070524),
(789, 'online_users', 2, 20070525),
(790, 'num_users', 32, 20070525),
(791, 'online_users', 2, 20070526),
(792, 'num_users', 32, 20070526),
(793, 'online_users', 2, 20070527),
(794, 'num_users', 32, 20070527),
(795, 'online_users', 3, 20070528),
(796, 'num_users', 33, 20070528),
(797, 'online_users', 3, 20070529),
(798, 'num_users', 33, 20070529),
(799, 'online_users', 2, 20070530),
(800, 'num_users', 33, 20070530),
(801, 'online_users', 2, 20070531),
(802, 'num_users', 33, 20070531),
(803, 'online_users', 2, 20070601),
(804, 'num_users', 33, 20070601),
(805, 'online_users', 2, 20070602),
(806, 'num_users', 33, 20070602),
(807, 'online_users', 1, 20070603),
(808, 'num_users', 33, 20070603),
(809, 'online_users', 2, 20070604),
(810, 'online_users', 1, 20070621);

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

INSERT INTO `dsb_subscriptions` (`subscr_id`, `subscr_name`, `subscr_diz`, `price`, `currency`, `is_recurent`, `m_value_from`, `m_value_to`, `duration`, `duration_units`, `is_visible`) VALUES 
(1, '30$ / month', '', 30.00, 'USD', 0, 2, 4, 30, 'DAY', 1),
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

INSERT INTO `dsb_subscriptions_auto` (`asubscr_id`, `dbfield`, `field_value`, `fk_subscr_id`, `date_start`) VALUES 
(1, 'f2', 2, 3, '0000-00-00'),
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

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES 
(1, 0x656d6d61, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'ema@sco.ro', '', '02912174c5cc19dedd90bc498af2d9b0', '20070614105036'),
(2, 0x6b65697468, 0x3166333837306265323734663663343962336533316130633637323839353766, 15, 2, 'mr-w@planetnet.karoo.co.uk', '', '2fba765ee1b6ade5287073584ec894e4', '20070420220720'),
(3, 0x73686f6e323035, 0x3037373631303430363739346562326133633736393139396439346131613739, 15, 2, 'shon205@sbcglobal.net', '', 'b3d7ac7336a79fe5a4b01a88f5727b13', '20070428125929'),
(4, 0x6d6176657269636b, 0x3839393636303063333130383863366137363436346365333864393635636364, 15, 2, 'maverick@desktopmates.com', '', '87a6ce818365e8b3bef125df92826702', '20070603075251'),
(5, 0x313030343537, 0x3436373536623938396231303530613331373235386536643565386539383931, 15, 2, 'rican499@yahoo.com', '', '404e5eff79512807ddbed6602139d841', '20070510064834'),
(6, 0x636f6361636f6c61, 0x6361323466306531653366663730316661346633336335336639303566396461, 15, 2, 'simcola@hotmail.com', '', '7f2fe99deb74578bf4f6e5e26dd99ae0', '20070430220154'),
(7, 0x647261676f6e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'dan@sco.ro', '', '45c95c2d1f50c9e3999316e83fff2893', '20070621213528'),
(8, 0x616e676b617361, 0x3634383530366161333739666566623930323663376530313664386463626663, 15, 2, 'boboangkasa@hotmail.com', '', '36e12348474821624874994164838526', '20070528120050'),
(9, 0x72616e65676c6f, 0x3739343063343139653932336166306265373963623835613432663836343964, 15, 2, 'bob@robertangelo.name', '', '83f1200b9f1bdd252e2aebc2dfffe34f', '20070416130815'),
(10, 0x737472617762657272696573, 0x3665643631643462383062623066383139333762333234313865393861646361, 15, 2, 'love2escape2000@yahoo.com', '', '68779ace53ca9ba01a52efa82e53bf86', '20070604113437'),
(11, 0x6a6f686e626f79, 0x6463613961313066636662396434346365346265393037393965633762623738, 15, 2, 'john@dunedin-direct.co.nz', '', '4173da6fd2bf9e455e18cdd70953f0b6', '20070602231331'),
(12, 0x615f6c5f66, 0x3530393636333531626362373264373463636466636634313038613734613664, 15, 2, 'rpicton@optusnet.com.au', '', 'c846ec67e081e672e99c7a14d167b463', '20070513004543'),
(13, 0x6a6f686e626f7932, 0x6463613961313066636662396434346365346265393037393965633762623738, 15, 2, 'john@johnmuir.co.nz', '', 'f446c1ae194fc4ed6309e1b60f177ebb', '20070525005452'),
(14, 0x7470616e647470, 0x3639336538313066663237363034653664613237346461346337376531333663, 15, 2, 'manager@adelaideplaytime.com', '', '76b41bfad8b909b658e578f08f7a6244', '20070531015806'),
(15, 0x626c61636b7761746572, 0x6166623332376439386230316537323035383035323233613331313363303563, 15, 2, 'yagiz.karasu@vodafone.com', '', 'bc217753a490dd732c4e992340db665c', '20070417003723'),
(16, 0x746573746572, 0x6234663062656231303130343734626564396366303234303231366333313464, 15, 2, 'clive@phonehome.co.za', '', 'cf3b1718be2b5af79bf2c189eb0bbedf', '20070530195951'),
(17, 0x736861646f776d616368696e65, 0x3133653065373165393738646338313364613236333630323432383835616364, 15, 2, 'kilbey@bellsouth.net', '', '4b53fc447e46b2c856535a430e581828', '20070622090934'),
(18, 0x626f756469656f7a, 0x3432653631326437363530353231363432393466633132346138633363393930, 15, 2, 'aando@iinet.net.au', '', '338aaba691f5a1e118e0838c17c9d9fe', '20070417195645'),
(19, 0x706b757361, 0x3638613030393962336634353335373739383633396133306335666533313534, 15, 2, 'pkling@cul.net', '', '12305e20c5085f60b221dab4ccea51e9', '20070525134452'),
(20, 0x636f6f6c6d65313437, 0x3436373536623938396231303530613331373235386536643565386539383931, 15, 2, 'noidle2000@yahoo.com', '', '12a21391d3ab03902820305a505132cb', '20070419235518'),
(21, 0x636861726c696533383636, 0x3136333635616433386361366365643136353663653034336664633364383561, 15, 2, 'charlie3866@yahoo.com', '', 'bdbca8949090f7b6088086b9448d137e', '20070604115245'),
(22, 0x73696c6c79776162626974, 0x3361336638653766323761303430613135363064376334303662386438366133, 15, 2, 'jamiesgirl@prayerfullyyours.com', '', 'c3bf9209bf6a701cdffa3ab38a7d54d5', '20070528011626'),
(23, 0x7a6f6f6b69, 0x6331326636313666333265333634663866333634646565366636333333626134, 15, 2, 'jediknight82@gmail.com', '', '3f9f7c1e8bff52ed7344fa73e0b65889', '20070426105034'),
(24, 0x726472616b65, 0x6531306164633339343962613539616262653536653035376632306638383365, 15, 2, 'uba2000@hotmail.com', '', '89ebe890814e541abb94ef9c44aa6030', '20070422072606'),
(25, 0x7465737431, 0x3366376263643062336561383232363833626261386663353330663135316264, 10, 2, 'webspinner@hotmail.com', '', 'caad4dcb3413f2c9b9cc8a37f9d8406d', '20070425014047'),
(26, 0x726963686765796572, 0x3663356263343362343433393735623830363734306438653431313436343739, 15, 4, 'richgeyer_10@hotmail.com', '', 'c62058c0597a9822aab8e5a95c3dd63f', '20070510031159'),
(27, 0x616d795f636f6c6c696e34636869636b73, 0x6431353966653264653731316631336437373139363462373264363063316637, 15, 4, 'nawtypics@hotmail.com', '', '148ec1fedc3465a15cf14090806923bf', '20070510052743'),
(28, 0x726f62657274373835, 0x3666623034313231613864306432653363353066363661323161643563376165, 15, 4, 'robert785m@yahoo.com', '', '1d5435b854ed7de130187d11909484ff', '20070511105510'),
(29, 0x7472616465726a6f65, 0x6637386134663035656630373064366134303334653430313430313662323039, 15, 4, 'talking2you@verizon.net', '', '83ec729ea50d136f2dae7622011361d0', '20070603035438'),
(30, 0x6c6f6c6f626f6e64, 0x3838313633313432303264386664393563386432613931643832396331326662, 15, 4, 'juntando@coqui.net', '', '0703616bc9d0b9d69f5636304e967ed5', '20070512212337'),
(31, 0x626f64696361, 0x3963326236663565343263356361616633623536396164303938373063373232, 15, 4, 'bodica100@gmail.com', '', '1acc044507d289459a11e103f161335c', '20070513155032'),
(32, 0x6d657276796e, 0x6239663434316162316233393830393630663362613335313838343138343961, 15, 4, 'mervyng@yahoo.com', '', '742d6048918e63ab42c5dfe1ed45ffd7', '20070528072846'),
(33, 0x616c74657265676f, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'alterego@bamamatch.com', '', '6820635487eb95ef66aeea4191161446', '20070604154311'),
(34, 0x6b6b6e313233, 0x3137363331326263323937336635303334613837323335343039656261313734, 15, 4, 'kevinn@fmi.co.za', '', '8d05c1b54359f87f6aca7825a8c7cbfc', '20070528071613');

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

INSERT INTO `dsb_user_blogs` (`blog_id`, `fk_user_id`, `blog_name`, `blog_diz`, `stat_posts`, `blog_skin`, `blog_url`) VALUES 
(1, 1, 'My first ever blog', 'What can I break today?', 2, '', ''),
(3, 10, 'Fluffy''s Blog', 'The life and times of Fluffy', 2, '', ''),
(4, 11, 'Testing Testing the Blog', 'Testing Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing the BlogTesting Testing', 1, '', ''),
(5, 7, 'My words of wisdom', '', 8, '', ''),
(6, 19, 'TEST BLOG', 'This is Just a test Blog to see how it all works', 1, '', ''),
(7, 12, 'Yahooo the new look', 'Great to see that the new look is a goer :-)', 1, '', ''),
(8, 23, 'World Peace', 'How to achieve world peace in a few simple steps', 2, '', ''),
(9, 6, 'Test', 'Test', 1, '', ''),
(10, 17, 'I want to test this script', 'Have license, will upload', 1, '', '');

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

INSERT INTO `dsb_user_folders` (`folder_id`, `fk_user_id`, `folder`) VALUES 
(1, 6, 'Test'),
(2, 11, 'My Sexy Email'),
(3, 10, 'test folder'),
(4, 12, 'intrested'),
(5, 23, 'cool'),
(6, 32, 'test folder'),
(7, 34, 'st');

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
  KEY `key1` (`fk_user_id`,`fk_folder_id`,`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_inbox`
-- 

INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES 
(1, 1, 1, 4, 'maverick', 'Connection request from maverick', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-16 17:53:37', 0, 0, 1),
(83, 1, 10, 0, '', 'New comment on one of your photos', 'strawberries posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=6">Click here</a> to view the comment', '2007-04-21 14:24:36', 2, 0, 1),
(84, 1, 10, 0, '', 'New comment on one of your photos', 'strawberries posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=6">Click here</a> to view the comment', '2007-04-21 14:25:32', 2, 0, 1),
(9, 1, 13, 11, 'johnboy', 'Connection request from johnboy', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-17 05:37:24', 0, 0, 0),
(12, 1, 13, 11, 'johnboy', 'johnboy sent you a flirt', 'Let''s rock and roll!', '2007-04-17 12:39:33', 1, 0, 0),
(14, 1, 12, 10, 'strawberries', 'strawberries sent you a flirt', 'Aye aye, mate!', '2007-04-18 01:55:37', 1, 0, 0),
(16, 1, 6, 16, 'tester', 'tester sent you a flirt', 'Aye aye, mate!', '2007-04-18 11:18:25', 1, 0, 0),
(17, 0, 6, 16, 'tester', 'Connection request from tester', '<a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-18 11:32:26', 0, 0, 0),
(18, 1, 19, 8, 'angkasa', 'Connection request from angkasa', 'angkasa wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-19 02:19:36', 2, 0, 1),
(19, 1, 12, 8, 'angkasa', 'Connection request from angkasa', 'angkasa wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-19 02:21:50', 2, 0, 0),
(20, 1, 12, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-19 02:22:08', 1, 4, 0),
(21, 1, 10, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Aye aye, mate!', '2007-04-19 04:45:50', 1, 3, 0),
(150, 0, 7, 8, 'angkasa', 'angkasa sent you a flirt', 'Let''s rock and roll!', '2007-04-27 19:43:08', 1, 0, 1),
(28, 1, 8, 19, 'pkusa', 'Re: Connection request from angkasa', 'HI There\r\n\r\nHow are you doing?&nbsp;&nbsp; Thanks for sending the request!&nbsp;&nbsp;I&#039;m hoping that we can find every big and little bug for Dan.\r\n\r\n\r\nPeter\r\n----------------\r\n\r\nangkasa wants to be your friend.', '2007-04-19 13:03:34', 0, 0, 0),
(88, 0, 19, 8, 'angkasa', 'angkasa sent you a flirt', 'Let''s rock and roll!', '2007-04-21 22:17:07', 1, 0, 1),
(89, 1, 19, 8, 'angkasa', 'Connection request from angkasa', 'angkasa wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-21 22:17:58', 2, 0, 1),
(30, 1, 1, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid= 8">Click here</a> to view the comment', '2007-04-19 15:27:10', 2, 0, 1),
(31, 1, 1, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=8">Click here</a> to view the comment', '2007-04-19 19:53:42', 2, 0, 1),
(32, 1, 1, 7, 'dragon', 't', 't', '2007-04-19 21:26:18', 0, 0, 1),
(33, 1, 1, 7, 'dragon', 'dragon sent you a flirt', 'Let''s rock and roll!', '2007-04-19 21:26:28', 1, 0, 1),
(87, 1, 23, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=11">Click here</a> to view the comment', '2007-04-21 21:07:30', 2, 0, 0),
(35, 1, 12, 10, 'strawberries', 'strawberries sent you a flirt', 'Aye aye, mate!', '2007-04-20 00:06:45', 1, 0, 0),
(73, 1, 10, 12, 'a_l_f', 'Re: a_l_f sent you a flirt', 'Hi strawberries\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;oh so true a little script tidy up like the&quot;&amp;nbsp&quot; that are in the replies \r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;and i also look forward to the IM working, also even the old features like whois online \r\nI really cant wait to get my hands on the script so i can start playing with it and give it my look for my site \r\nhehehehe and see how quick i can crash it on my system :-)\r\nTTFN\r\nRalph\r\n\r\n&nbsp;&nbsp;&nbsp;&nbsp;\r\n[quote]\r\n[quote]LOL yes i did have the same thing yesterday, so i didnot bother \r\nbut tis now working fine yahoooo[/quote]\r\n\r\nyeee-haaaa!\r\n\r\ngreat to see all the bells and whistles starting to work.\r\n\r\ni love the nice clean look of this script.&amp;nbsp;&amp;nbsp;It looked a bit bare at the start...but with more people signing up, and testing&amp;nbsp;&amp;nbsp;the blogs and pictures....and email system, and notification system, i am starting to fall for this script.\r\n\r\nand it will look even better when the instant messenger feature is included.\r\n\r\n\r\n[/quote]', '2007-04-20 21:35:16', 0, 0, 1),
(37, 1, 10, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=2">Click here</a> to view the comment', '2007-04-20 00:10:19', 2, 0, 0),
(38, 1, 4, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 00:16:08', 2, 0, 1),
(39, 1, 1, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 00:16:53', 2, 0, 1),
(86, 1, 23, 0, '', 'New comment on one of your blogs', 'guest posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=12">Click here</a> to view the comment', '2007-04-21 20:35:26', 2, 0, 0),
(41, 1, 4, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 00:18:17', 2, 0, 1),
(42, 1, 10, 4, 'maverick', 'Howdy', 'Just testing the email system.\r\n\r\nI was going to try the flirt thing too but that just didn&#039;t seem right sending another guy a flirt message, I didn&#039;t want you to think I was hitting on you!', '2007-04-20 00:23:51', 0, 0, 0),
(43, 1, 4, 10, 'strawberries', 'Re: Howdy', '\r\n[quote]Just testing the email system.\r\n\r\nI was going to try the flirt thing too but that just didn&#039;t seem right sending another guy a flirt message, I didn&#039;t want you to think I was hitting on you![/quote]\r\n\r\nLOL&nbsp;&nbsp;i wrestled with that as well, nate.......i sent a_l_f&nbsp;&nbsp;a test flirt there today, after he sent me one this morning......just great to check out the system here.\r\n\r\nI am really loving this new script, more and more.\r\n\r\nare you on yahoo messenger?\r\n\r\ni am&nbsp;&nbsp; love2escape2000\r\n\r\n\r\n\r\n&nbsp;&nbsp;', '2007-04-20 00:35:16', 0, 0, 1),
(44, 1, 13, 11, 'johnboy', 'Hi Johnboy2', 'Hi Johnboy2\r\nThis is Johnboy1 testing this out', '2007-04-20 01:28:47', 0, 0, 0),
(45, 1, 13, 11, 'johnboy', 'Re: Connection request from johnboy2', '\r\n[quote]&lt;a class=&quot;content-link simple&quot; href=&quot;friendship_requests.php&quot;&gt;Click here&lt;/a&gt; to see all friendship requests[/quote]', '2007-04-20 01:29:36', 0, 0, 0),
(47, 1, 13, 11, 'johnboy', 'Testing to see if an email icon lights up at your end', 'Hi JB2\r\nTesting to see if an email icon lights up at your end.\r\n\r\nCheers\r\nJB1', '2007-04-20 01:40:39', 0, 0, 0),
(50, 1, 13, 11, 'johnboy', 'Connection request from johnboy', 'johnboy wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 02:11:44', 2, 0, 0),
(51, 1, 13, 11, 'johnboy', 'Connection request from johnboy', 'johnboy wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 02:17:55', 2, 0, 0),
(94, 1, 7, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 06:25:18', 2, 0, 1),
(54, 1, 10, 12, 'a_l_f', 'Re: strawberries sent you a flirt', '\r\n[quote]Aye aye, mate![/quote]\r\nWell thanks for that \r\nand now that this side is now working \r\nI can reply although not in the way i had hoped \r\nbut this may yet be in the admin settings', '2007-04-20 03:23:15', 0, 0, 0),
(55, 1, 10, 12, 'a_l_f', 'Re: a_l_f sent you a flirt', 'LOL yes i did have the same thing yesterday, so i didnot bother \r\nbut tis now working fine yahoooo\r\n[quote]\r\n[quote]Aye aye, mate![/quote]\r\n\r\ntest message re your flirt.....hope this sends ok&amp;nbsp;&amp;nbsp;(error this morning when i tried to send)[/quote]', '2007-04-20 03:24:50', 0, 0, 0),
(56, 1, 14, 12, 'a_l_f', 'Connection request from a_l_f', 'a_l_f wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 03:25:31', 2, 0, 1),
(57, 1, 14, 12, 'a_l_f', 'Adeliade how is it', 'Hi there how is it down your way in Adeliade \r\nnice to see the new software :-)\r\n', '2007-04-20 03:26:57', 0, 0, 1),
(93, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 03:31:05', 2, 0, 1),
(61, 1, 13, 0, '', 'New comment on one of your photos', 'a_l_f posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=9">Click here</a> to view the comment', '2007-04-20 03:40:44', 2, 0, 0),
(62, 1, 12, 11, 'johnboy', 'johnboy sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-20 04:08:36', 1, 0, 0),
(70, 1, 12, 10, 'strawberries', 'Re: a_l_f sent you a flirt', '\r\n[quote]LOL yes i did have the same thing yesterday, so i didnot bother \r\nbut tis now working fine yahoooo[/quote]\r\n\r\nyeee-haaaa!\r\n\r\ngreat to see all the bells and whistles starting to work.\r\n\r\ni love the nice clean look of this script.&nbsp;&nbsp;It looked a bit bare at the start...but with more people signing up, and testing&nbsp;&nbsp;the blogs and pictures....and email system, and notification system, i am starting to fall for this script.\r\n\r\nand it will look even better when the instant messenger feature is included.\r\n\r\n\r\n', '2007-04-20 19:53:57', 0, 0, 0),
(64, 1, 13, 0, '', 'New comment on one of your photos', 'johnboy2 posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=9">Click here</a> to view the comment', '2007-04-20 04:19:10', 2, 0, 0),
(67, 0, 6, 16, 'tester', 'test', 'testnig this email story\r\ncheers\r\nclive\r\n', '2007-04-20 10:18:28', 0, 0, 0),
(68, 1, 6, 16, 'tester', 'Connection request from tester', 'tester wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-20 11:30:35', 2, 0, 0),
(71, 1, 1, 6, 'cocacola', 'Hello', 'sdfsdf', '2007-04-20 21:26:49', 0, 0, 1),
(72, 1, 1, 6, 'cocacola', 'cocacola sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-20 21:27:00', 1, 0, 1),
(74, 1, 12, 0, '', 'New comment on one of your photos', 'keith posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=17">Click here</a> to view the comment', '2007-04-20 22:07:15', 2, 0, 0),
(75, 0, 6, 1, 'emma', 'Re: Hello', 'Hi there\r\n\r\n[quote]sdfsdf[/quote]', '2007-04-20 22:17:11', 0, 0, 0),
(76, 1, 1, 23, 'zooki', 'zooki sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-21 11:32:17', 1, 0, 0),
(77, 1, 10, 0, '', 'New comment on one of your photos', 'zooki posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=6">Click here</a> to view the comment', '2007-04-21 11:36:39', 2, 0, 0),
(92, 1, 7, 0, '', 'New comment on one of your photos', 'strawberries posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=5">Click here</a> to view the comment', '2007-04-22 03:23:18', 2, 0, 1),
(91, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 03:13:39', 2, 0, 1),
(96, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 10:24:22', 2, 0, 1),
(97, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 10:24:51', 2, 0, 1),
(98, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 10:28:24', 2, 0, 1),
(99, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 10:30:07', 2, 0, 1),
(100, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 10:31:33', 2, 0, 1),
(101, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 10:37:53', 2, 0, 1),
(102, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 11:06:52', 2, 0, 1),
(103, 1, 7, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 17:29:05', 2, 0, 1),
(104, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-22 18:12:14', 2, 0, 1),
(105, 0, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-23 04:09:49', 2, 0, 1),
(106, 0, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-23 04:43:13', 2, 0, 1),
(108, 1, 13, 0, '', 'New comment on one of your photos', 'johnboy posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=10">Click here</a> to view the comment', '2007-04-23 05:03:30', 2, 0, 0),
(109, 1, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-23 05:10:25', 2, 0, 1),
(110, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-23 20:13:48', 2, 0, 1),
(111, 1, 10, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=2">Click here</a> to view the comment', '2007-04-23 20:18:39', 2, 0, 1),
(112, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-23 23:11:41', 2, 0, 1),
(113, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-23 23:33:58', 2, 0, 1),
(114, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-24 00:50:38', 2, 0, 1),
(115, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-24 03:20:16', 2, 0, 1),
(116, 1, 7, 0, '', 'New comment on one of your blogs', 'dragon posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-24 06:08:05', 2, 0, 1),
(117, 1, 10, 7, 'dragon', '&amp;nbsp;&amp;nbsp;', 'Hey, how did you produce those &amp;nbsp;&amp;nbsp; in the comments to my blog?', '2007-04-24 06:10:38', 0, 0, 0),
(118, 1, 1, 11, 'johnboy', 'johnboy sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-24 08:36:54', 1, 0, 0),
(120, 1, 7, 10, 'strawberries', 'Re: &amp;amp;nbsp;&amp;amp;nbsp;', '[quote]Hey, how did you produce those &amp;amp;nbsp;&amp;amp;nbsp; in the comments to my blog?[/quote]\r\n\r\nDan, my original post in your blog had looked fine.\r\n\r\nIt was only once I editted the post last night (see text below), that &#039;&amp;amp;nbsp;&amp;amp;nbsp;&#039;&nbsp;&nbsp; appeared.&nbsp;&nbsp;\r\n\r\nI only editted the last sentence, and yet that strange text appeared after each full-stop across the post.\r\n\r\nI deliberately posted it again, in another post, to draw your attention to it.\r\n\r\nI have seen similar text in emails in here.&nbsp;&nbsp;I think Maverick noticed it first IIRC. \r\n\r\nBest,\r\n\r\nWill\r\n\r\nBy the way, a tiny bug - but when I go to reply to an email here, the original person&#039;s quoted text (eg yours in this email) starts not on the top line, but one line down.&nbsp;&nbsp;I change it each time, and delete the empty top line.\r\n\r\n\r\n\r\n*********************\r\nMaverick - i was gonna air the same feelings too regarding one&#039;s friends list being openly displayed to anyone looking at your profile.&amp;nbsp;&amp;nbsp;\r\n\r\nI do know of one massive dating site that does allow such a thing. But I am not comfortable with it at all.&amp;nbsp;&amp;nbsp;\r\n\r\nA person&#039;s friends list is their own private area.&amp;nbsp;&amp;nbsp;I don&#039;t think it should be viewable by every person who is on the website.', '2007-04-24 10:57:57', 0, 0, 1),
(121, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-24 12:49:37', 2, 0, 1),
(122, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=13">Click here</a> to view the comment', '2007-04-24 12:53:27', 2, 0, 1),
(123, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm62">Click here</a> to view the comment', '2007-04-24 22:23:25', 2, 0, 1),
(124, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm63">Click here</a> to view the comment', '2007-04-24 22:28:13', 2, 0, 1),
(125, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm64">Click here</a> to view the comment', '2007-04-24 23:01:41', 2, 0, 1),
(126, 0, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm65">Click here</a> to view the comment', '2007-04-25 02:21:04', 2, 0, 1),
(127, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm66">Click here</a> to view the comment', '2007-04-25 05:10:17', 2, 0, 1),
(128, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm68">Click here</a> to view the comment', '2007-04-25 10:44:19', 2, 0, 1),
(129, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm69">Click here</a> to view the comment', '2007-04-25 10:47:42', 2, 0, 1),
(130, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm70">Click here</a> to view the comment', '2007-04-25 13:02:34', 2, 0, 1),
(131, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm72">Click here</a> to view the comment', '2007-04-25 17:59:38', 2, 0, 1),
(132, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm73">Click here</a> to view the comment', '2007-04-25 20:27:17', 2, 0, 1),
(133, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm74">Click here</a> to view the comment', '2007-04-25 20:43:25', 2, 0, 1),
(134, 1, 6, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=15#comm75">Click here</a> to view the comment', '2007-04-25 22:30:46', 2, 0, 0),
(135, 1, 10, 23, 'zooki', 'zooki sent you a flirt', 'Aye aye, mate!', '2007-04-26 10:49:39', 1, 0, 0),
(136, 0, 23, 10, 'strawberries', 'Re: zooki sent you a flirt', '[quote]Aye aye, mate![/quote]\r\n\r\nhi zooki! &nbsp;\r\n\r\nThis new datemill a great script.', '2007-04-26 11:23:34', 0, 0, 0),
(137, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm77">Click here</a> to view the comment', '2007-04-26 11:25:41', 2, 0, 1),
(138, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm79">Click here</a> to view the comment', '2007-04-26 12:23:15', 2, 0, 1),
(139, 0, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm80">Click here</a> to view the comment', '2007-04-26 13:23:01', 2, 0, 1),
(140, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm82">Click here</a> to view the comment', '2007-04-26 18:55:50', 2, 0, 1),
(141, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm84">Click here</a> to view the comment', '2007-04-26 19:46:36', 2, 0, 1),
(142, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm86">Click here</a> to view the comment', '2007-04-26 20:27:21', 2, 0, 1),
(143, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm87">Click here</a> to view the comment', '2007-04-26 20:27:50', 2, 0, 1),
(144, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm88">Click here</a> to view the comment', '2007-04-26 20:28:08', 2, 0, 1),
(145, 0, 23, 0, '', 'New comment on one of your photos', 'strawberries posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=20#comm27">Click here</a> to view the comment', '2007-04-27 09:08:45', 2, 0, 0),
(146, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm89">Click here</a> to view the comment', '2007-04-27 10:55:43', 2, 0, 1),
(147, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm91">Click here</a> to view the comment', '2007-04-27 14:00:27', 2, 0, 1),
(148, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm93">Click here</a> to view the comment', '2007-04-27 14:49:39', 2, 0, 1),
(149, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm94">Click here</a> to view the comment', '2007-04-27 15:22:57', 2, 0, 1),
(152, 1, 7, 0, '', 'New comment on one of your blogs', 'tpandtp posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm95">Click here</a> to view the comment', '2007-04-29 13:28:50', 2, 0, 1),
(153, 0, 7, 14, 'tpandtp', 'tpandtp sent you a flirt', 'Aye aye, mate!', '2007-04-29 13:32:39', 1, 0, 1),
(154, 0, 23, 0, '', 'New comment on one of your photos', 'angkasa posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=22#comm28">Click here</a> to view the comment', '2007-04-29 17:16:05', 2, 0, 0),
(155, 1, 14, 8, 'angkasa', 'angkasa sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-29 17:17:13', 1, 0, 1),
(156, 1, 7, 0, '', 'New comment on one of your blogs', 'angkasa posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=9#comm96">Click here</a> to view the comment', '2007-04-29 17:42:13', 2, 0, 1),
(157, 1, 7, 0, '', 'New comment on one of your blogs', 'angkasa posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=3#comm97">Click here</a> to view the comment', '2007-04-29 17:46:01', 2, 0, 1),
(158, 0, 14, 8, 'angkasa', 'angkasa sent you a flirt', 'Aye aye, mate!', '2007-04-29 17:46:56', 1, 0, 1),
(159, 1, 14, 8, 'angkasa', 'Connection request from angkasa', 'angkasa wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-29 17:47:04', 2, 0, 1),
(160, 0, 14, 8, 'angkasa', 'Connection request from angkasa', 'angkasa wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-04-29 17:47:11', 2, 0, 1),
(161, 1, 13, 0, '', 'New comment on one of your photos', 'strawberries posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=9#comm29">Click here</a> to view the comment', '2007-04-30 00:37:38', 2, 0, 0),
(162, 1, 13, 0, '', 'New comment on one of your photos', 'strawberries posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=9#comm32">Click here</a> to view the comment', '2007-04-30 15:47:14', 2, 0, 0),
(163, 1, 13, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-05-03 10:11:37', 2, 0, 0),
(164, 1, 7, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-05-03 10:12:02', 2, 0, 1),
(165, 0, 23, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-05-03 10:13:35', 2, 0, 0),
(166, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm99">Click here</a> to view the comment', '2007-05-03 10:14:39', 2, 0, 1),
(167, 1, 4, 10, 'strawberries', 'Connection request from strawberries', 'strawberries wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-05-03 17:46:28', 2, 0, 0),
(168, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm101">Click here</a> to view the comment', '2007-05-04 07:52:23', 2, 0, 1),
(169, 1, 7, 0, '', 'New comment on one of your blogs', 'tester posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm102">Click here</a> to view the comment', '2007-05-05 18:21:25', 2, 0, 1),
(170, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm103">Click here</a> to view the comment', '2007-05-05 22:44:09', 2, 0, 1),
(171, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm104">Click here</a> to view the comment', '2007-05-06 12:05:28', 2, 0, 1),
(172, 0, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm106">Click here</a> to view the comment', '2007-05-07 09:58:42', 2, 0, 1),
(173, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm107">Click here</a> to view the comment', '2007-05-07 10:24:07', 2, 0, 1),
(174, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm108">Click here</a> to view the comment', '2007-05-07 10:26:12', 2, 0, 1),
(175, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm110">Click here</a> to view the comment', '2007-05-07 12:10:35', 2, 0, 1),
(176, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm113">Click here</a> to view the comment', '2007-05-09 16:53:26', 2, 0, 1),
(177, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm115">Click here</a> to view the comment', '2007-05-09 18:49:01', 2, 0, 1),
(178, 1, 11, 0, '', 'New comment on one of your photos', 'angkasa posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=29#comm34">Click here</a> to view the comment', '2007-05-11 07:39:17', 2, 0, 1),
(179, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm116">Click here</a> to view the comment', '2007-05-11 08:28:51', 2, 0, 1),
(180, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm118">Click here</a> to view the comment', '2007-05-11 08:49:26', 2, 0, 1),
(181, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm119">Click here</a> to view the comment', '2007-05-11 09:06:42', 2, 0, 1),
(182, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm121">Click here</a> to view the comment', '2007-05-11 09:41:28', 2, 0, 1),
(183, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm122">Click here</a> to view the comment', '2007-05-11 09:43:50', 2, 0, 1),
(184, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm123">Click here</a> to view the comment', '2007-05-11 09:45:28', 2, 0, 1),
(185, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm125">Click here</a> to view the comment', '2007-05-11 10:23:54', 2, 0, 1),
(186, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm126">Click here</a> to view the comment', '2007-05-11 15:28:49', 2, 0, 1),
(187, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm127">Click here</a> to view the comment', '2007-05-12 07:52:00', 2, 0, 1),
(188, 0, 23, 0, '', 'New comment on one of your photos', 'traderjoe posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=20#comm35">Click here</a> to view the comment', '2007-05-12 22:33:19', 2, 0, 0),
(189, 1, 19, 0, '', 'New comment on your profile', 'angkasa posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm1">Click here</a> to view the comment', '2007-05-13 17:48:27', 2, 0, 1),
(190, 1, 19, 8, 'angkasa', 'angkasa sent you a flirt', 'Let''s rock and roll!', '2007-05-13 17:48:54', 1, 0, 1),
(191, 1, 7, 0, '', 'New comment on one of your blogs', 'angkasa posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm128">Click here</a> to view the comment', '2007-05-13 17:52:34', 2, 0, 1),
(192, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm129">Click here</a> to view the comment', '2007-05-15 08:55:18', 2, 0, 1),
(193, 0, 6, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=15#comm130">Click here</a> to view the comment', '2007-05-15 09:37:11', 2, 0, 0),
(194, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm132">Click here</a> to view the comment', '2007-05-15 16:22:18', 2, 0, 1),
(195, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm134">Click here</a> to view the comment', '2007-05-15 20:38:52', 2, 0, 1),
(196, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm135">Click here</a> to view the comment', '2007-05-15 21:37:14', 2, 0, 1),
(197, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm136">Click here</a> to view the comment', '2007-05-15 22:07:42', 2, 0, 1),
(198, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm137">Click here</a> to view the comment', '2007-05-15 22:33:15', 2, 0, 1),
(199, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm138">Click here</a> to view the comment', '2007-05-15 22:36:48', 2, 0, 1),
(200, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm139">Click here</a> to view the comment', '2007-05-15 23:57:31', 2, 0, 1),
(201, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm140">Click here</a> to view the comment', '2007-05-16 00:39:03', 2, 0, 1),
(202, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm141">Click here</a> to view the comment', '2007-05-16 00:41:42', 2, 0, 1),
(203, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm142">Click here</a> to view the comment', '2007-05-16 01:10:12', 2, 0, 1),
(204, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm144">Click here</a> to view the comment', '2007-05-16 08:00:19', 2, 0, 1),
(205, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm146">Click here</a> to view the comment', '2007-05-16 12:35:49', 2, 0, 1),
(206, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm147">Click here</a> to view the comment', '2007-05-16 12:39:34', 2, 0, 1),
(207, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm148">Click here</a> to view the comment', '2007-05-16 12:52:37', 2, 0, 1),
(208, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm149">Click here</a> to view the comment', '2007-05-16 12:58:35', 2, 0, 1),
(209, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm150">Click here</a> to view the comment', '2007-05-16 13:49:34', 2, 0, 1),
(210, 1, 13, 11, 'johnboy', 'Testing Messages out', 'This is a template, just testing it out.\r\nCheers\r\nJohnboy1', '2007-05-17 00:26:10', 0, 0, 0),
(211, 1, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm152">Click here</a> to view the comment', '2007-05-17 00:32:19', 2, 0, 1),
(212, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm154">Click here</a> to view the comment', '2007-05-17 08:19:13', 2, 0, 1),
(213, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm155">Click here</a> to view the comment', '2007-05-17 08:43:27', 2, 0, 1),
(214, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm157">Click here</a> to view the comment', '2007-05-17 11:02:42', 2, 0, 1),
(215, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm158">Click here</a> to view the comment', '2007-05-17 11:03:36', 2, 0, 1),
(216, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm160">Click here</a> to view the comment', '2007-05-17 12:23:18', 2, 0, 1),
(217, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm161">Click here</a> to view the comment', '2007-05-17 12:39:00', 2, 0, 1),
(218, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm163">Click here</a> to view the comment', '2007-05-17 12:43:34', 2, 0, 1),
(219, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm164">Click here</a> to view the comment', '2007-05-17 12:48:27', 2, 0, 1),
(220, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm166">Click here</a> to view the comment', '2007-05-17 13:58:27', 2, 0, 1),
(221, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm167">Click here</a> to view the comment', '2007-05-17 14:00:28', 2, 0, 1),
(222, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm168">Click here</a> to view the comment', '2007-05-17 17:15:34', 2, 0, 1),
(223, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm169">Click here</a> to view the comment', '2007-05-18 11:51:07', 2, 0, 1),
(224, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm170">Click here</a> to view the comment', '2007-05-18 17:57:33', 2, 0, 1),
(225, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm171">Click here</a> to view the comment', '2007-05-18 17:58:43', 2, 0, 1),
(226, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm172">Click here</a> to view the comment', '2007-05-18 20:43:58', 2, 0, 1),
(227, 1, 11, 13, 'johnboy2', 'johnboy2 sent you a flirt', 'Hey sexy!', '2007-05-21 20:27:12', 1, 0, 1),
(228, 1, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=17#comm173">Click here</a> to view the comment', '2007-05-22 22:25:06', 2, 0, 1),
(229, 1, 4, 11, 'johnboy', 'Connection request from johnboy', 'johnboy wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests', '2007-05-22 22:27:47', 2, 0, 0),
(230, 1, 7, 0, '', 'New comment on one of your blogs', 'johnboy posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=14#comm174">Click here</a> to view the comment', '2007-05-22 22:38:18', 2, 0, 1),
(231, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm175">Click here</a> to view the comment', '2007-05-23 15:32:07', 2, 0, 1),
(232, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm177">Click here</a> to view the comment', '2007-05-24 02:00:39', 2, 0, 1),
(233, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm178">Click here</a> to view the comment', '2007-05-24 02:24:09', 2, 0, 1),
(234, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm179">Click here</a> to view the comment', '2007-05-24 16:53:01', 2, 0, 1),
(235, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm180">Click here</a> to view the comment', '2007-05-25 14:54:00', 2, 0, 1),
(236, 1, 8, 0, '', 'New comment on one of your photos', 'mervyn posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=11#comm36">Click here</a> to view the comment', '2007-05-25 23:52:15', 2, 0, 0),
(237, 0, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm181">Click here</a> to view the comment', '2007-05-26 06:54:46', 2, 0, 1),
(238, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm182">Click here</a> to view the comment', '2007-05-26 14:51:35', 2, 0, 1),
(239, 1, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm183">Click here</a> to view the comment', '2007-05-27 14:46:01', 2, 0, 1),
(240, 1, 22, 29, 'traderjoe', 'Nice profile', 'Nice test profile', '2007-05-27 18:32:18', 0, 0, 0),
(241, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm184">Click here</a> to view the comment', '2007-05-27 20:24:17', 2, 0, 1),
(242, 1, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm185">Click here</a> to view the comment', '2007-05-28 07:55:09', 2, 0, 1),
(243, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm186">Click here</a> to view the comment', '2007-05-28 11:51:42', 2, 0, 1),
(244, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm187">Click here</a> to view the comment', '2007-05-28 11:57:33', 2, 0, 1);
INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES 
(245, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm188">Click here</a> to view the comment', '2007-05-29 20:19:15', 2, 0, 1),
(246, 0, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm189">Click here</a> to view the comment', '2007-05-30 08:52:55', 2, 0, 1),
(247, 0, 7, 0, '', 'New comment on one of your blogs', 'alterego posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm190">Click here</a> to view the comment', '2007-05-30 08:58:20', 2, 0, 1),
(248, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm191">Click here</a> to view the comment', '2007-05-30 11:17:36', 2, 0, 1),
(249, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm192">Click here</a> to view the comment', '2007-05-30 11:38:49', 2, 0, 1),
(250, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm193">Click here</a> to view the comment', '2007-05-30 16:14:39', 2, 0, 1),
(251, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm194">Click here</a> to view the comment', '2007-05-30 16:17:29', 2, 0, 1),
(252, 0, 34, 16, 'tester', 'howzit', 'hi\r\nwhat do you think of datemill?\r\nCheers\r\nClive', '2007-05-30 19:45:47', 0, 0, 0),
(253, 0, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm196">Click here</a> to view the comment', '2007-05-31 02:55:53', 2, 0, 1),
(254, 0, 7, 0, '', 'New comment on one of your blogs', 'alterego posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm197">Click here</a> to view the comment', '2007-05-31 03:05:46', 2, 0, 1),
(255, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm198">Click here</a> to view the comment', '2007-05-31 09:27:20', 2, 0, 1),
(256, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm199">Click here</a> to view the comment', '2007-05-31 12:13:51', 2, 0, 1),
(257, 1, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm201">Click here</a> to view the comment', '2007-05-31 18:56:26', 2, 0, 1),
(258, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm202">Click here</a> to view the comment', '2007-05-31 19:42:45', 2, 0, 1),
(259, 0, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm203">Click here</a> to view the comment', '2007-06-01 05:19:11', 2, 0, 1),
(260, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm204">Click here</a> to view the comment', '2007-06-01 09:58:27', 2, 0, 1),
(261, 0, 7, 0, '', 'New comment on one of your blogs', 'shadowmachine posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm205">Click here</a> to view the comment', '2007-06-01 10:57:26', 2, 0, 1),
(262, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm206">Click here</a> to view the comment', '2007-06-01 11:38:13', 2, 0, 1),
(263, 0, 7, 0, '', 'New comment on one of your blogs', 'maverick posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm207">Click here</a> to view the comment', '2007-06-01 11:44:09', 2, 0, 1),
(264, 1, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm208">Click here</a> to view the comment', '2007-06-03 12:51:20', 2, 0, 1),
(265, 0, 7, 0, '', 'New comment on one of your blogs', 'strawberries posted a comment on one of your blog posts.<br><a class="content-link simple" href="blog_post_view.php?pid=18#comm209">Click here</a> to view the comment', '2007-06-04 10:47:33', 2, 0, 1),
(266, 0, 7, 0, '', 'New comment on your profile', 'alterego posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm4">Click here</a> to view the comment', '2007-06-04 14:09:56', 2, 0, 0);

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

INSERT INTO `dsb_user_mtpls` (`mtpl_id`, `fk_user_id`, `subject`, `message_body`) VALUES 
(1, 11, 'Testing Messages out', 'This is a template, just testing it out.\nCheers\nJohnboy1'),
(2, 10, 'Re: zooki sent you a flirt', 'example of saved template'),
(3, 4, 'Testing Saved Responses', 'Just testing to see how the saved responses works!'),
(4, 4, 'Testing Saved Responses', 'Just testing to see how the saved responses works!');

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

INSERT INTO `dsb_user_networks` (`nconn_id`, `fk_user_id`, `fk_net_id`, `fk_user_id_other`, `nconn_status`) VALUES 
(1, 4, 1, 1, 1),
(2, 4, 3, 1, 1),
(3, 1, 1, 7, 1),
(4, 10, 1, 7, 1),
(5, 11, 1, 7, 1),
(6, 10, 3, 10, 1),
(27, 11, 3, 7, 1),
(8, 13, 1, 11, 1),
(9, 11, 3, 13, 1),
(10, 11, 1, 13, 1),
(11, 16, 1, 6, 0),
(12, 16, 3, 6, 1),
(13, 7, 1, 10, 1),
(14, 7, 1, 11, 1),
(15, 8, 3, 19, 1),
(16, 8, 1, 19, 1),
(17, 8, 3, 12, 1),
(18, 8, 1, 12, 1),
(19, 12, 1, 8, 1),
(20, 12, 3, 8, 1),
(21, 1, 1, 4, 1),
(22, 19, 1, 8, 1),
(23, 10, 1, 4, 1),
(24, 10, 1, 1, 1),
(25, 10, 3, 1, 1),
(26, 4, 1, 10, 1),
(28, 13, 3, 4, 1),
(29, 12, 1, 14, 1),
(30, 12, 1, 11, 1),
(31, 11, 1, 12, 1),
(32, 10, 3, 12, 1),
(34, 1, 1, 10, 1),
(35, 23, 1, 7, 1),
(36, 7, 1, 23, 1),
(38, 11, 2, 13, 1),
(39, 11, 3, 1, 1),
(40, 8, 1, 11, 1),
(41, 14, 1, 12, 1),
(42, 8, 1, 14, 1),
(43, 8, 3, 14, 1),
(44, 6, 2, 16, 1),
(45, 10, 1, 13, 1),
(46, 10, 1, 23, 0),
(47, 13, 1, 10, 1),
(48, 11, 1, 8, 1),
(49, 14, 1, 8, 1),
(50, 4, 2, 15, 1),
(51, 11, 1, 4, 1),
(52, 4, 1, 11, 1),
(53, 29, 3, 22, 1);

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

INSERT INTO `dsb_user_outbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES 
(1, 0, 1, 7, 'dragon', 'hi', '[b]hi[/b]', '2007-04-19 09:20:24', 0),
(16384, 0, 1, 7, 'dragon', 'test confirmation emails', 'test test', '2007-04-19 10:22:41', 255),
(16385, 0, 1, 7, 'dragon', 'ala bala portocala', 'dada', '2007-04-19 10:51:58', 0),
(16386, 0, 19, 8, 'angkasa', 'Re: Connection request from angkasa', 'HI There\r\n\r\nHow are you doing?&nbsp;&nbsp; Thanks for sending the request!&nbsp;&nbsp;I&#039;m hoping that we can find every big and little bug for Dan.\r\n\r\n\r\nPeter\r\n----------------\r\n\r\nangkasa wants to be your friend.', '2007-04-19 13:03:34', 0),
(16388, 0, 10, 12, 'a_l_f', 'Re: a_l_f sent you a flirt', '\r\n[quote]Aye aye, mate![/quote]\r\n\r\ntest message re your flirt.....hope this sends ok&nbsp;&nbsp;(error this morning when i tried to send)', '2007-04-20 00:07:34', 0),
(16390, 0, 10, 4, 'maverick', 'Re: Howdy', '\r\n[quote]Just testing the email system.\r\n\r\nI was going to try the flirt thing too but that just didn&#039;t seem right sending another guy a flirt message, I didn&#039;t want you to think I was hitting on you![/quote]\r\n\r\nLOL&nbsp;&nbsp;i wrestled with that as well, nate.......i sent a_l_f&nbsp;&nbsp;a test flirt there today, after he sent me one this morning......just great to check out the system here.\r\n\r\nI am really loving this new script, more and more.\r\n\r\nare you on yahoo messenger?\r\n\r\ni am&nbsp;&nbsp; love2escape2000\r\n\r\n\r\n\r\n&nbsp;&nbsp;', '2007-04-20 00:35:16', 0),
(16391, 1, 11, 13, 'johnboy2', 'Hi Johnboy2', 'Hi Johnboy2\r\nThis is Johnboy1 testing this out', '2007-04-20 01:28:47', 0),
(16392, 1, 11, 13, 'johnboy2', 'Re: Connection request from johnboy2', '\r\n[quote]&lt;a class=&quot;content-link simple&quot; href=&quot;friendship_requests.php&quot;&gt;Click here&lt;/a&gt; to see all friendship requests[/quote]', '2007-04-20 01:29:36', 0),
(16393, 1, 13, 11, 'johnboy', 'Re: Connection request from johnboy', '\r\n[quote]&lt;a class=&quot;content-link simple&quot; href=&quot;friendship_requests.php&quot;&gt;Click here&lt;/a&gt; to see all friendship requests[/quote]\r\nthis is from Johnboy2 to JB1', '2007-04-20 01:33:46', 0),
(16394, 1, 11, 13, 'johnboy2', 'Testing to see if an email icon lights up at your end', 'Hi JB2\r\nTesting to see if an email icon lights up at your end.\r\n\r\nCheers\r\nJB1', '2007-04-20 01:40:39', 0),
(16395, 0, 12, 10, 'strawberries', 'Re: strawberries sent you a flirt', '\r\n[quote]Aye aye, mate![/quote]\r\nWell thanks for that \r\nand now that this side is now working \r\nI can reply although not in the way i had hoped \r\nbut this may yet be in the admin settings', '2007-04-20 03:23:15', 0),
(16396, 0, 12, 10, 'strawberries', 'Re: a_l_f sent you a flirt', 'LOL yes i did have the same thing yesterday, so i didnot bother \r\nbut tis now working fine yahoooo\r\n[quote]\r\n[quote]Aye aye, mate![/quote]\r\n\r\ntest message re your flirt.....hope this sends ok&amp;nbsp;&amp;nbsp;(error this morning when i tried to send)[/quote]', '2007-04-20 03:24:50', 0),
(16397, 0, 12, 14, 'tpandtp', 'Adeliade how is it', 'Hi there how is it down your way in Adeliade \r\nnice to see the new software :-)\r\n', '2007-04-20 03:26:57', 0),
(16398, 0, 12, 11, 'johnboy', 'Hi there', 'Hi John \r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;hows life in NZ you must be cooling off by now\r\n', '2007-04-20 03:28:10', 0),
(16399, 1, 11, 12, 'a_l_f', 'Re: Hi there', '\r\n[quote]Hi John \r\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;hows life in NZ you must be cooling off by now\r\n[/quote]\r\n\r\nYes it&#039;s Bloody cold here, not as warm as Aussie', '2007-04-20 04:10:00', 0),
(16400, 0, 12, 11, 'johnboy', 'Re: johnboy sent you a flirt', 'Hey John how is Nz connect going ,,, well i hope \r\n\r\n[quote]Hello, baby! &lt;img src=&quot;http://forum.datemill.com/Themes/default/images/off.gif&quot; /&gt;[/quote]', '2007-04-20 06:20:26', 0),
(16401, 0, 12, 11, 'johnboy', 'Re: Hi there', 'I bet mate \r\nTis why i moved from the cold hole of Stanthorpe as i thought that it was cold enough there even thou it ratly snowed but it did get bloody cold \r\nThe lowest temp i saw was -12 ,,, everything just froze burrrrrrrrrr&nbsp;&nbsp;:-)\r\n\r\n[quote]\r\n[quote]Hi John \r\n&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;hows life in NZ you must be cooling off by now\r\n[/quote]\r\n\r\nYes it&#039;s Bloody cold here, not as warm as Aussie[/quote]', '2007-04-20 06:22:53', 0),
(16402, 0, 16, 6, 'cocacola', 'test', 'testnig this email story\r\ncheers\r\nclive\r\n', '2007-04-20 10:18:28', 0),
(16403, 0, 10, 12, 'a_l_f', 'Re: a_l_f sent you a flirt', '\r\n[quote]LOL yes i did have the same thing yesterday, so i didnot bother \r\nbut tis now working fine yahoooo[/quote]\r\n\r\nyeee-haaaa!\r\n\r\ngreat to see all the bells and whistles starting to work.\r\n\r\ni love the nice clean look of this script.&nbsp;&nbsp;It looked a bit bare at the start...but with more people signing up, and testing&nbsp;&nbsp;the blogs and pictures....and email system, and notification system, i am starting to fall for this script.\r\n\r\nand it will look even better when the instant messenger feature is included.\r\n\r\n\r\n', '2007-04-20 19:53:57', 0),
(16404, 0, 6, 1, 'emma', 'Hello', 'sdfsdf', '2007-04-20 21:26:49', 0),
(16405, 0, 12, 10, 'strawberries', 'Re: a_l_f sent you a flirt', 'Hi strawberries\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;oh so true a little script tidy up like the&quot;&amp;nbsp&quot; that are in the replies \r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;and i also look forward to the IM working, also even the old features like whois online \r\nI really cant wait to get my hands on the script so i can start playing with it and give it my look for my site \r\nhehehehe and see how quick i can crash it on my system :-)\r\nTTFN\r\nRalph\r\n\r\n&nbsp;&nbsp;&nbsp;&nbsp;\r\n[quote]\r\n[quote]LOL yes i did have the same thing yesterday, so i didnot bother \r\nbut tis now working fine yahoooo[/quote]\r\n\r\nyeee-haaaa!\r\n\r\ngreat to see all the bells and whistles starting to work.\r\n\r\ni love the nice clean look of this script.&amp;nbsp;&amp;nbsp;It looked a bit bare at the start...but with more people signing up, and testing&amp;nbsp;&amp;nbsp;the blogs and pictures....and email system, and notification system, i am starting to fall for this script.\r\n\r\nand it will look even better when the instant messenger feature is included.\r\n\r\n\r\n[/quote]', '2007-04-20 21:35:16', 0),
(16406, 0, 1, 6, 'cocacola', 'Re: Hello', 'Hi there\r\n\r\n[quote]sdfsdf[/quote]', '2007-04-20 22:17:11', 0),
(16407, 1, 23, 7, 'dragon', 'Hello', 'I saw that you are online,\r\n\r\njust testing this message thingy \r\n\r\n', '2007-04-21 11:46:33', 0),
(16409, 1, 7, 10, 'strawberries', '&amp;nbsp;&amp;nbsp;', 'Hey, how did you produce those &amp;nbsp;&amp;nbsp; in the comments to my blog?', '2007-04-24 06:10:38', 0),
(16410, 0, 1, 11, 'johnboy', 'Re: johnboy sent you a flirt', 'Hey there!', '2007-04-24 10:13:45', 0),
(16411, 0, 10, 7, 'dragon', 'Re: &amp;amp;nbsp;&amp;amp;nbsp;', '[quote]Hey, how did you produce those &amp;amp;nbsp;&amp;amp;nbsp; in the comments to my blog?[/quote]\r\n\r\nDan, my original post in your blog had looked fine.\r\n\r\nIt was only once I editted the post last night (see text below), that &#039;&amp;amp;nbsp;&amp;amp;nbsp;&#039;&nbsp;&nbsp; appeared.&nbsp;&nbsp;\r\n\r\nI only editted the last sentence, and yet that strange text appeared after each full-stop across the post.\r\n\r\nI deliberately posted it again, in another post, to draw your attention to it.\r\n\r\nI have seen similar text in emails in here.&nbsp;&nbsp;I think Maverick noticed it first IIRC. \r\n\r\nBest,\r\n\r\nWill\r\n\r\nBy the way, a tiny bug - but when I go to reply to an email here, the original person&#039;s quoted text (eg yours in this email) starts not on the top line, but one line down.&nbsp;&nbsp;I change it each time, and delete the empty top line.\r\n\r\n\r\n\r\n*********************\r\nMaverick - i was gonna air the same feelings too regarding one&#039;s friends list being openly displayed to anyone looking at your profile.&amp;nbsp;&amp;nbsp;\r\n\r\nI do know of one massive dating site that does allow such a thing. But I am not comfortable with it at all.&amp;nbsp;&amp;nbsp;\r\n\r\nA person&#039;s friends list is their own private area.&amp;nbsp;&amp;nbsp;I don&#039;t think it should be viewable by every person who is on the website.', '2007-04-24 10:57:57', 0),
(16412, 0, 10, 23, 'zooki', 'Re: zooki sent you a flirt', '[quote]Aye aye, mate![/quote]\r\n\r\nhi zooki! &nbsp;\r\n\r\nThis new datemill a great script.', '2007-04-26 11:23:34', 0),
(16413, 1, 11, 13, 'johnboy2', 'Testing Messages out', 'This is a template, just testing it out.\r\nCheers\r\nJohnboy1', '2007-05-17 00:26:10', 0),
(16414, 1, 29, 22, 'sillywabbit', 'Nice profile', 'Nice test profile', '2007-05-27 18:32:18', 0),
(16415, 0, 16, 34, 'kkn123', 'howzit', 'hi\r\nwhat do you think of datemill?\r\nCheers\r\nClive', '2007-05-30 19:45:47', 0);

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

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `allow_rating`, `caption`, `status`, `del`, `flagged`, `reject_reason`, `stat_views`, `stat_votes`, `stat_votes_total`, `stat_comments`, `date_posted`, `last_changed`, `processed`) VALUES 
(1, 4, 'maverick', '7/4_11176746548.jpg', 0, 1, 1, 1, '', 10, 0, 0, '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://www.datemill.com/friendy/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <p>Maverick,</p>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for the photo you uploaded on nexus / r i t m o / Friendy.<br />\r\n        Unfortunately we cannot approve it because testing testing</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 29, 3, 13, 3, '2007-04-16 18:02:29', '2007-05-16 00:24:13', 0),
(2, 1, 'emma', '4/1_11176750826.jpg', 0, 0, 1, 1, 'ruuuuun, enemies are coming!!', 15, 0, 0, '', 17, 0, 0, 0, '2007-04-16 19:13:53', '2007-05-20 09:28:14', 0),
(3, 1, 'emma', '2/1_21176750826.jpg', 1, 0, 1, 1, 'daddy''s girl', 15, 0, 0, '', 11, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-16 19:15:28', 0),
(4, 1, 'emma', '1/1_31176750826.jpg', 0, 0, 1, 1, 'hey, look, I can walk...sort of :)', 15, 0, 0, '', 9, 0, 0, 0, '2007-04-16 19:13:53', '2007-04-16 19:15:28', 0),
(5, 7, 'dragon', '0/7_11176751977.jpg', 1, 0, 0, 1, '', 15, 0, 0, '', 71, 5, 25, 7, '2007-04-16 19:32:59', '2007-06-19 11:55:32', 0),
(6, 10, 'strawberries', '3/10_11176756425.jpg', 0, 0, 1, 1, 'Fluffy, she who must be obeyed', 10, 0, 0, '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://www.datemill.com/friendy/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <p>Hi,</p>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for the photo you uploaded on nexus / r i t m o / Friendy.<br />\r\n        Unfortunately we cannot approve it because</p>\r\n        <p>you asked what''s new in the latest patch :)</p>\r\n        <p>This is one of the new things that you can see. Also check out your rejected photo and click on the link saying that photo was rejected</p>\r\n        <p>Dan</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 13, 0, 0, 4, '2007-04-16 20:47:07', '2007-05-10 14:17:26', 0),
(7, 10, 'strawberries', '3/10_21176756425.jpg', 1, 0, 1, 1, 'Fluffy loves getting her picture taken', 15, 0, 0, '', 13, 0, 0, 1, '2007-04-16 20:47:07', '2007-05-10 14:39:48', 0),
(8, 11, 'johnboy', '0/11_11176758735.jpg', 0, 1, 1, 1, '', 10, 0, 0, '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://www.datemill.com/friendy/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body spellcheck="false">\r\n        <p>Hi,</p>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for the photo you uploaded on nexus / r i t m o / Friendy.<br />\r\n        Unfortunately we cannot approve it because</p>\r\n        <p>just playing with the admin interface :)</p>\r\n        <p>Dan</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 13, 1, 5, 0, '2007-04-16 21:25:35', '2007-05-10 21:53:44', 0),
(9, 13, 'johnboy2', '6/13_11176787947.jpg', 1, 0, 1, 1, 'Out the front of my house', 15, 0, 0, '', 36, 3, 14, 11, '2007-04-17 05:32:29', '2007-04-17 05:33:50', 0),
(10, 13, 'johnboy2', '8/13_21176787947.jpg', 0, 0, 1, 1, 'A poor little bird freezing its butt off.', 15, 0, 0, '', 15, 2, 9, 1, '2007-04-17 05:32:29', '2007-04-17 05:33:50', 0),
(11, 8, 'angkasa', '2/8_11176790979.jpg', 1, 0, 1, 1, 'My Favourite Car', 15, 0, 0, '', 12, 2, 8, 2, '2007-04-17 06:23:02', '2007-05-01 22:45:49', 0),
(12, 15, 'blackwater', '1/15_11176795306.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-17 07:35:07', '2007-04-17 07:36:26', 0),
(13, 12, 'a_l_f', '9/12_11176812224.jpg', 1, 0, 1, 1, 'Here Kitty Kitty', 15, 0, 0, '', 13, 2, 6, 1, '2007-04-17 12:17:04', '2007-04-17 12:17:31', 0),
(14, 16, 'tester', '5/16_11176897029.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 5, 1, 1, 0, '2007-04-18 11:50:30', '2007-04-18 11:51:12', 0),
(15, 19, 'pkusa', '2/19_11176938760.jpg', 1, 0, 1, 1, 'Just me hanging out', 15, 0, 0, '', 13, 0, 0, 0, '2007-04-18 23:26:01', '2007-04-18 23:26:19', 0),
(16, 12, 'a_l_f', '3/12_11176962520.jpg', 0, 0, 1, 1, 'where are those damm cats', 15, 0, 0, '', 18, 2, 8, 0, '2007-04-19 06:02:01', '2007-04-19 06:04:09', 0),
(17, 12, 'a_l_f', '6/12_21176962520.jpg', 0, 0, 1, 1, 'i havent seen the cat   would i lie', 15, 0, 0, '', 9, 0, 0, 1, '2007-04-19 06:02:01', '2007-04-19 06:04:09', 0),
(18, 12, 'a_l_f', '4/12_11177105273.jpg', 0, 1, 1, 1, 'Lainey my better half', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-20 21:41:15', '2007-04-20 21:42:00', 0),
(19, 12, 'a_l_f', '1/12_21177105273.jpg', 0, 1, 1, 1, 'haha my ugly mug', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-20 21:41:15', '2007-04-20 21:42:00', 0),
(20, 23, 'zooki', '5/23_11177155235.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 11, 2, 10, 2, '2007-04-21 11:33:57', '2007-04-21 11:34:06', 0),
(21, 23, 'zooki', '5/23_11177156929.jpg', 0, 0, 1, 1, 'Yummy', 15, 0, 0, '', 11, 3, 15, 1, '2007-04-21 12:02:13', '2007-04-21 12:02:56', 0),
(22, 23, 'zooki', '8/23_21177156929.jpg', 0, 0, 1, 1, 'Kashmir', 15, 0, 0, '', 11, 0, 0, 1, '2007-04-21 12:02:13', '2007-04-21 12:02:56', 0),
(23, 23, 'zooki', '1/23_31177156929.jpg', 0, 0, 1, 1, 'Waterfall Was An animated GIF file', 15, 0, 0, '', 5, 0, 0, 0, '2007-04-21 12:02:13', '2007-04-21 12:02:56', 0),
(24, 7, 'dragon', '5/7_11177683564.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 10, 1, 1, 0, '2007-04-27 14:19:28', '2007-04-27 14:19:42', 0),
(25, 7, 'dragon', '2/7_21177683564.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 29, 5, 24, 0, '2007-04-27 14:19:28', '2007-04-27 14:19:42', 0),
(26, 7, 'dragon', '0/7_31177683564.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-27 14:19:28', '2007-04-27 14:19:42', 0),
(27, 14, 'tpandtp', '7/14_11177853960.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 18, 2, 9, 0, '2007-04-29 13:39:22', '2007-04-29 13:39:41', 0),
(28, 8, 'angkasa', '9/8_11177867139.jpg', 0, 0, 1, 1, 'SIngapore train', 15, 0, 0, '', 3, 0, 0, 0, '2007-04-29 17:18:59', '2007-04-29 17:19:19', 0),
(29, 11, 'johnboy', '9/11_11177884303.jpg', 1, 0, 1, 1, 'Doing a solo gig 2005', 15, 0, 0, '', 18, 1, 4, 1, '2007-04-29 22:05:05', '2007-05-10 21:53:31', 0),
(30, 17, 'shadowmachine', '5/17_11178319458.jpg', 1, 0, 1, 1, 'Happy Birthday to me', 15, 0, 0, '', 8, 0, 0, 0, '2007-05-04 22:57:39', '2007-05-04 22:58:08', 0),
(32, 4, 'maverick', '7/4_11179276136.jpg', 0, 0, 1, 1, 'My Granddaughter "Emma"', 15, 0, 0, '', 7, 1, 5, 0, '2007-05-16 00:42:18', '2007-05-19 19:33:30', 0),
(33, 4, 'maverick', '3/4_11179276378.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 21, 1, 5, 0, '2007-05-16 00:46:18', '2007-05-19 19:33:35', 0),
(34, 32, 'mervyn', '8/32_11180137910.jpg', 1, 0, 1, 1, 'My current car', 5, 0, 0, '', 0, 0, 0, 0, '2007-05-26 00:05:11', '2007-05-26 00:05:46', 0),
(35, 33, 'alterego', '2/33_11180579813.jpg', 1, 0, 1, 1, '', 5, 0, 0, '', 0, 0, 0, 0, '2007-05-31 02:50:25', '2007-05-31 02:50:25', 0);

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
  KEY `key1` (`status`,`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_profiles`
-- 

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `del`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6_country`, `f6_state`, `f6_city`, `f6_zip`, `f7`, `f8`, `f9`, `f10`, `f11`, `f12`, `f13`, `f14`, `f15`) VALUES 
(1, 1, 15, 0, '2007-04-16 22:53:50', '2007-04-16 14:42:06', '', 'emma', '2/1_21176750826.jpg', 0.0000000000, 0.0000000000, 29, '', 2, '|1|', 1, '1989-06-05', 165, 0, 0, '', 3, 3, 2, 3, 1, 1, 1, 1, ''),
(2, 2, 15, 0, '2007-04-16 12:42:01', '2007-04-16 17:09:23', '', 'keith', '', 0.0000000000, 0.0000000000, 0, 'Testing', 1, '|2|', 1, '1976-05-12', 217, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, '|1|4|7|'),
(3, 3, 15, 0, '2007-04-16 17:29:10', '2007-04-16 17:24:38', '', 'shon205', '', -81.6286010742, 41.4509010315, 1, '', 1, '|2|', 1, '1971-01-06', 218, 41, 1620, '44105', 1, 1, 1, 1, 1, 1, 1, 1, ''),
(4, 4, 15, 0, '2007-04-17 21:53:31', '2007-04-16 17:46:34', '', 'maverick', '3/4_11179276378.jpg', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 33, '0000-00-00', 218, 0, 0, '86314', 3, 3, 2, 3, 1, 1, 2, 2, '|1|'),
(5, 5, 15, 0, '2007-04-16 12:41:45', '2007-04-16 18:26:47', '', '100457', '', 0.0000000000, 0.0000000000, 0, 'cool me', 1, '|2|', 32, '1978-04-07', 218, 0, 0, '49504', 5, 1, 3, 1, 1, 1, 1, 1, '|1|7|'),
(6, 6, 15, 0, '2007-04-16 12:41:53', '2007-04-16 18:50:27', '', 'cocacola', '', 0.0000000000, 0.0000000000, 0, 'Im cool', 1, '|2|', 38, '1969-01-01', 186, 0, 0, '07631', 3, 1, 1, 1, 1, 1, 1, 1, '|1|'),
(7, 7, 15, 0, '2007-06-19 13:22:04', '2007-04-16 19:26:41', '', 'dragon', '0/7_11176751977.jpg', 0.0000000000, 0.0000000000, 81, '', 1, '|2|', 58, '1976-11-01', 218, 0, 0, '', 3, 5, 2, 5, 1, 2, 4, 1, '|2|'),
(8, 8, 15, 0, '2007-04-17 06:23:17', '2007-04-16 19:54:09', '', 'angkasa', '2/8_11176790979.jpg', 0.0000000000, 0.0000000000, 0, 'I''m Cool', 1, '|2|', 47, '1980-09-18', 181, 0, 0, '', 2, 3, 1, 2, 3, 2, 5, 3, '|1|7|'),
(9, 9, 15, 0, '2007-04-16 19:56:29', '2007-04-16 19:55:41', '', 'raneglo', '', 0.0000000000, 0.0000000000, 0, 'I am me!', 1, '|1|', 34, '1961-07-29', 218, 0, 0, '77584', 3, 3, 6, 5, 1, 2, 4, 1, '|4|5|'),
(10, 10, 15, 0, '2007-05-28 22:39:49', '2007-04-16 20:26:23', '', 'strawberries', '3/10_21176756425.jpg', 0.0000000000, 0.0000000000, 0, 'What to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself. What to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself.\r\n\r\nWhat to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself. What to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself.\r\n\r\nWhat to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself. What to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself.\r\n\r\nWhat to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who likes music and movies. And I like to repeat myself. What to say.  Lessee.  I''m an 18 feet tall mad mundane mountain monkey who ', 1, '|2|', 38, '1965-01-01', 217, 0, 0, '', 3, 3, 2, 5, 1, 2, 3, 1, '|1|'),
(11, 11, 15, 0, '2007-05-10 21:53:31', '2007-04-16 21:10:00', '', 'johnboy', '9/11_11177884303.jpg', 0.0000000000, 0.0000000000, 4, 'Hello everyone, at last we can see what the new DSB is about, I LIKE IT, very clean.', 1, '|2|', 39, '1958-01-17', 145, 0, 0, '', 3, 1, 2, 9, 3, 2, 2, 2, '|7|'),
(12, 12, 15, 0, '2007-04-19 04:52:56', '2007-04-17 00:26:40', '', 'a_l_f', '9/12_11176812224.jpg', 0.0000000000, 0.0000000000, 1, 'Computer Junkie that loves CATS yummmm  \r\nkung fu and motor bikes', 1, '|2|', 5, '1959-07-28', 11, 0, 0, '', 9, 6, 1, 5, 3, 2, 3, 1, '|2|4|7|'),
(13, 13, 15, 0, '2007-05-17 03:43:15', '2007-04-17 05:13:27', '', 'johnboy2', '6/13_11176787947.jpg', 0.0000000000, 0.0000000000, 0, 'Testing this out', 1, '|2|', 39, '1958-01-17', 145, 0, 0, '', 3, 1, 2, 9, 1, 2, 2, 2, '|7|'),
(14, 14, 15, 0, '2007-04-29 13:39:22', '2007-04-17 05:48:17', '', 'tpandtp', '7/14_11177853960.jpg', 0.0000000000, 0.0000000000, 4, '', 1, '|2|', 1, '1967-11-20', 11, 0, 0, '', 3, 1, 1, 1, 3, 3, 2, 1, '|7|'),
(15, 15, 15, 0, '2007-04-17 07:36:26', '2007-04-17 07:27:52', '', 'blackwater', '1/15_11176795306.jpg', 0.0000000000, 0.0000000000, 0, 'şşşş ğğğğğ ııııı', 1, '|2|', 51, '1977-04-28', 210, 0, 0, '', 3, 3, 1, 2, 2, 2, 3, 1, '|1|7|'),
(16, 16, 15, 0, '2007-04-18 11:51:12', '2007-04-17 10:35:07', '', 'tester', '5/16_11176897029.jpg', 0.0000000000, 0.0000000000, 4, 'about-me...looking at this softa\\ware', 1, '|2|', 50, '1969-11-30', 186, 0, 0, '', 3, 1, 1, 1, 1, 1, 1, 1, '|4|6|7|'),
(17, 17, 15, 0, '2007-06-01 07:40:02', '2007-04-17 22:54:59', '', 'shadowmachine', '5/17_11178319458.jpg', 0.0000000000, 0.0000000000, 0, 'I''m a guy. I like chicks.', 1, '|2|', 44, '1964-05-14', 218, 0, 0, '35803', 3, 8, 3, 5, 1, 2, 4, 2, '|2|6|'),
(19, 19, 15, 0, '2007-04-18 23:26:19', '2007-04-18 23:20:44', '', 'pkusa', '2/19_11176938760.jpg', 0.0000000000, 0.0000000000, 3, '', 1, '|1|2|', 38, '1963-01-03', 218, 0, 0, '32837', 3, 7, 6, 4, 1, 2, 3, 1, '|5|'),
(18, 18, 15, 0, '2007-04-18 02:38:42', '2007-04-18 02:37:23', '', 'boudieoz', '', 0.0000000000, 0.0000000000, 0, 'I am ok', 1, '|2|', 5, '1971-07-03', 11, 0, 0, '', 3, 3, 3, 4, 2, 1, 5, 1, '|6|'),
(20, 20, 15, 0, '2007-04-20 06:45:46', '2007-04-20 06:45:46', '', 'coolme147', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 0, '1978-04-22', 218, 0, 0, '49001', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(21, 21, 15, 0, '2007-04-20 09:32:19', '2007-04-20 09:30:59', '', 'charlie3866', '', 0.0000000000, 0.0000000000, 2, 'Just trying out the new software and it looks pretty good to me!', 1, '|2|', 35, '1966-08-03', 217, 0, 0, '', 3, 3, 2, 3, 1, 1, 3, 1, '|1|'),
(22, 22, 15, 0, '2007-04-20 10:26:31', '2007-04-20 10:24:42', '', 'sillywabbit', '', 0.0000000000, 0.0000000000, 2, 'testing testing testing testing testing', 2, '|1|', 28, '1969-06-28', 218, 0, 0, '30188', 3, 3, 2, 3, 3, 1, 3, 2, '|2|'),
(23, 23, 15, 0, '2007-04-21 12:00:33', '2007-04-21 11:24:23', '', 'zooki', '5/23_11177155235.jpg', 0.0000000000, 0.0000000000, 0, 'Im happy. Very. .happy... test..... me.. Im happy. Very. .happy... test..... me.. Im happy. Very. .h', 1, '|2|', 37, '1983-02-02', 217, 0, 0, '', 6, 4, 3, 5, 1, 1, 4, 1, '|1|'),
(24, 24, 15, 0, '2007-04-22 07:17:15', '2007-04-22 07:15:30', '', 'rdrake', '', 0.0000000000, 0.0000000000, 0, 'cool', 1, '|2|', 33, '1976-11-16', 218, 0, 0, '90046', 3, 1, 1, 1, 3, 2, 5, 1, '|1|'),
(25, 25, 15, 0, '2007-04-25 08:42:02', '2007-04-25 08:40:47', '', 'test1', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 38, '0000-00-00', 217, 0, 0, '', 3, 1, 2, 5, 1, 2, 3, 4, '|1|'),
(26, 26, 15, 0, '2007-05-10 02:57:19', '2007-05-10 02:56:39', '', 'richgeyer', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 41, '1975-08-10', 11, 0, 0, '5000', 3, 1, 6, 4, 1, 1, 5, 1, '|5|'),
(27, 27, 15, 0, '2007-05-10 05:12:36', '2007-05-10 05:10:33', '', 'amy_collin4chicks', '', 0.0000000000, 0.0000000000, 0, '', 2, '|2|', 27, '1986-07-05', 11, 0, 0, '', 3, 3, 5, 2, 1, 2, 1, 1, '|5|'),
(28, 28, 15, 0, '2007-05-11 10:48:44', '2007-05-11 10:47:26', '', 'robert785', '', 0.0000000000, 0.0000000000, 0, 'm 49 seaparated very open minded and loves fun', 1, '|1|2|', 32, '1958-01-03', 11, 0, 0, '', 3, 7, 2, 3, 2, 1, 1, 4, '|1|'),
(29, 29, 15, 0, '2007-05-12 19:07:26', '2007-05-12 18:33:01', '', 'traderjoe', '', 0.0000000000, 0.0000000000, 3, '', 1, '|2|', 55, '1940-12-01', 218, 0, 0, '00000', 3, 3, 3, 4, 1, 1, 4, 1, '|1|'),
(30, 30, 15, 0, '2007-05-12 21:20:40', '2007-05-12 21:19:22', '', 'lolobond', '', 0.0000000000, 0.0000000000, 0, 'blablablablablablablabla', 1, '|2|', 34, '1952-10-28', 218, 0, 0, '00767', 5, 1, 1, 7, 2, 2, 5, 1, '|1|7|'),
(31, 31, 15, 0, '2007-05-13 15:11:39', '2007-05-13 15:10:37', '', 'bodica', '', 0.0000000000, 0.0000000000, 0, 'just testing....', 1, '|2|', 33, '1970-01-01', 213, 0, 0, '', 5, 3, 4, 5, 1, 2, 4, 1, '|5|'),
(32, 32, 15, 0, '2007-05-25 23:37:26', '2007-05-25 23:35:43', '', 'mervyn', '', 0.0000000000, 0.0000000000, 0, 'hmm... what do I say?', 1, '|2|', 33, '1976-04-16', 186, 0, 0, '', 2, 3, 1, 2, 1, 1, 3, 1, '|3|'),
(33, 34, 15, 0, '2007-05-28 07:04:50', '2007-05-28 07:04:50', '', 'kkn123', '', 0.0000000000, 0.0000000000, 0, '', 1, '|2|', 0, '1976-07-08', 186, 0, 0, 'EQ', 0, 0, 0, 0, 0, 0, 0, 0, '');

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
  `search` text NOT NULL,
  `alert` tinyint(1) unsigned NOT NULL default '0',
  `alert_last_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`search_id`),
  KEY `key1` (`fk_user_id`,`is_default`),
  KEY `alert` (`alert`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_searches`
-- 

INSERT INTO `dsb_user_searches` (`search_id`, `fk_user_id`, `title`, `is_default`, `search_qs`, `search`, `alert`, `alert_last_id`) VALUES 
(1, 7, 'advdef', 0, 'acclevel_code=search_advanced&st=adv&f2%5B0%5D=2&f3%5B0%5D=1&f5_max=75&f6_country=0&f9=0&f13=0&f14=0', 'a:9:{s:13:"acclevel_code";s:15:"search_advanced";s:2:"st";s:3:"adv";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f5_max";s:2:"75";s:10:"f6_country";i:0;s:2:"f9";i:0;s:3:"f13";i:0;s:3:"f14";i:0;}', 1, 22),
(2, 7, 'onlines', 0, 'acclevel_code=search_basic&st=online', 'a:2:{s:13:"acclevel_code";s:12:"search_basic";s:2:"st";s:6:"online";}', 1, 0);

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

INSERT INTO `dsb_user_settings2` (`config_id`, `fk_user_id`, `config_option`, `config_value`, `fk_module_code`) VALUES 
(106, 10, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(107, 10, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(108, 10, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(109, 10, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(9, 11, 0x73656e645f616c6572745f696e74657276616c, '1', 0x6465665f757365725f7072656673),
(10, 11, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(11, 11, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(12, 11, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(15, 12, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(16, 12, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(17, 12, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(18, 12, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(21, 17, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(22, 17, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(23, 17, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(24, 17, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(271, 17, 0x70726f66696c655f636f6d6d656e7473, '1', 0x6465665f757365725f7072656673),
(35, 19, 0x73656e645f616c6572745f696e74657276616c, '5', 0x6465665f757365725f7072656673),
(36, 19, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(37, 19, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(38, 19, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(270, 17, 0x74696d655f6f6666736574, '0', 0x6465665f757365725f7072656673),
(53, 21, 0x73656e645f616c6572745f696e74657276616c, '5', 0x6465665f757365725f7072656673),
(54, 21, 0x726174655f6d795f70726f66696c65, '0', 0x6465665f757365725f7072656673),
(55, 21, 0x726174655f6d795f70686f746f73, '0', 0x6465665f757365725f7072656673),
(56, 21, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(269, 17, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %I:%M %p', 0x6465665f757365725f7072656673),
(268, 17, 0x646174655f666f726d6174, '%m/%d/%Y', 0x6465665f757365725f7072656673),
(61, 6, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(62, 6, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(63, 6, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(64, 6, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(110, 10, 0x70726f66696c655f636f6d6d656e7473, '1', 0x6465665f757365725f7072656673),
(142, 33, 0x646174655f666f726d6174, '%m/%d/%Y', 0x6465665f757365725f7072656673),
(143, 33, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 0x6465665f757365725f7072656673),
(144, 33, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(145, 33, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(146, 33, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(147, 33, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(148, 33, 0x70726f66696c655f636f6d6d656e7473, '1', 0x6465665f757365725f7072656673),
(257, 7, 0x646174655f666f726d6174, '%m/%d/%Y', 0x6465665f757365725f7072656673),
(258, 7, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %I:%M %p', 0x6465665f757365725f7072656673),
(259, 7, 0x73656e645f616c6572745f696e74657276616c, '2', 0x6465665f757365725f7072656673),
(260, 7, 0x726174655f6d795f70726f66696c65, '1', 0x6465665f757365725f7072656673),
(261, 7, 0x726174655f6d795f70686f746f73, '1', 0x6465665f757365725f7072656673),
(262, 7, 0x6e6f746966795f6d65, '1', 0x6465665f757365725f7072656673),
(263, 7, 0x70726f66696c655f636f6d6d656e7473, '0', 0x6465665f757365725f7072656673),
(264, 7, 0x74696d655f6f6666736574, '10800', 0x6465665f757365725f7072656673);

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

INSERT INTO `dsb_user_spambox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES 
(1, 1, 8, 12, 'a_l_f', 'a_l_f sent you a flirt', 'Hello, baby! <img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-04-19 04:46:42', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_user_stats`
-- 

DROP TABLE IF EXISTS `dsb_user_stats`;
CREATE TABLE `dsb_user_stats` (
  `fk_user_id` int(10) unsigned NOT NULL default '0',
  `stat` varchar(50) NOT NULL default '',
  `value` int(10) NOT NULL default '0',
  UNIQUE KEY `thekey` (`fk_user_id`,`stat`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_stats`
-- 

INSERT INTO `dsb_user_stats` (`fk_user_id`, `stat`, `value`) VALUES 
(1, 'pviews', 102),
(4, 'total_photos', 1),
(4, 'pviews', 46),
(6, 'comments', 2),
(1, 'total_photos', 3),
(1, 'blog_posts', 2),
(7, 'total_photos', 4),
(7, 'pviews', 143),
(6, 'pviews', 16),
(8, 'flirts_sent', 8),
(9, 'comments', 1),
(2, 'pviews', 3),
(10, 'total_photos', 2),
(10, 'blog_posts', 2),
(10, 'comments', 85),
(11, 'total_photos', 2),
(11, 'flirts_sent', 4),
(11, 'pviews', 74),
(10, 'pviews', 74),
(3, 'pviews', 14),
(11, 'comments', 14),
(13, 'flirts_sent', 3),
(13, 'total_photos', 2),
(13, 'pviews', 48),
(8, 'total_photos', 2),
(15, 'total_photos', 1),
(5, 'pviews', 5),
(8, 'pviews', 19),
(9, 'pviews', 4),
(15, 'pviews', 10),
(7, 'blog_posts', 7),
(12, 'total_photos', 5),
(12, 'flirts_sent', 3),
(13, 'comments', 5),
(12, 'pviews', 38),
(16, 'pviews', 13),
(4, 'comments', 25),
(10, 'flirts_sent', 3),
(16, 'flirts_sent', 1),
(16, 'total_photos', 1),
(7, 'comments', 31),
(1, 'comments', 2),
(19, 'total_photos', 1),
(19, 'blog_posts', 1),
(19, 'pviews', 108),
(12, 'blog_posts', 1),
(1, 'mess_sent', 5),
(1, 'flirts_sent', 1),
(19, 'mess_sent', 1),
(7, 'total_messages', -14),
(0, 'pviews', 212),
(7, 'mess_sent', 3),
(7, 'flirts_sent', 1),
(10, 'mess_sent', 5),
(4, 'mess_sent', 1),
(4, 'total_messages', -1),
(11, 'mess_sent', 5),
(13, 'mess_sent', 1),
(11, 'blog_posts', 1),
(12, 'mess_sent', 7),
(14, 'pviews', 53),
(12, 'comments', 1),
(16, 'mess_sent', 2),
(12, 'total_messages', 0),
(6, 'mess_sent', 1),
(6, 'flirts_sent', 1),
(2, 'comments', 1),
(1, 'total_messages', -3),
(23, 'flirts_sent', 2),
(23, 'total_photos', 4),
(23, 'comments', 2),
(23, 'pviews', 47),
(23, 'blog_posts', 2),
(23, 'mess_sent', 1),
(22, 'pviews', 13),
(6, 'blog_posts', 1),
(12, 'num_friends', 1),
(14, 'num_friends', 2),
(14, 'comments', 1),
(14, 'flirts_sent', 1),
(14, 'total_photos', 1),
(8, 'comments', 5),
(10, 'num_friends', 1),
(13, 'num_friends', 1),
(17, 'blog_posts', 1),
(17, 'total_photos', 1),
(17, 'pviews', 20),
(16, 'comments', 1),
(8, 'num_friends', 2),
(11, 'num_friends', 2),
(17, 'comments', 1),
(27, 'pviews', 3),
(28, 'pviews', 2),
(29, 'total_photos', -1),
(29, 'comments', 1),
(8, 'comments_made', 1),
(19, 'profile_comments', 1),
(18, 'pviews', 1),
(11, 'comments_made', 2),
(4, 'num_friends', 1),
(10, 'comments_made', 17),
(7, 'comments_made', 9),
(4, 'comments_made', 7),
(32, 'comments_made', 2),
(32, 'profile_comments', 1),
(17, 'comments_made', 7),
(29, 'mess_sent', 1),
(29, 'pviews', 2),
(10, 'profile_comments', 1),
(33, 'comments_made', 3),
(34, 'pviews', 2),
(32, 'pviews', 1),
(24, 'pviews', 1),
(25, 'pviews', 1),
(26, 'pviews', 1),
(30, 'pviews', 1),
(31, 'pviews', 1),
(7, 'profile_comments', 1),
(4000, 'pviews', 1);
