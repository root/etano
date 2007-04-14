-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 14, 2007 at 10:34 PM
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

INSERT INTO `dsb_blog_comments` (`comment_id`, `fk_post_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 1, 1, 'test', 'Fratii-ostasi nici macar nu incercau sa-si disimuleze nemultumirea. De cind Abatele hotarise sa pronunte consacrari numai in afara Fortaretei, paznicii nu incetasera sa se plinga, argumentind ca o asemenea schimbare nu numai ca nu avea precedent in istoria de 47 de generatii a Abatiei dar reprezenta si o invitatie la violenta pentru hoardele de barbari care bintuiau cimpiile arse ale vechii Terre.\r\n     - Nu mai exista credinta, Isidor! Fiecare dintre noi e convins ca poate hotari pentru propria persoana, ca in nimicnicia lui, poate schimba cumva cuvintul lui Dumnezeu, vorbi cu blindete Abatele privind cu indulgenta spre candidat.\r\n     Staretul era un barbat in putere, cu fata acoperita de o barba imensa, peste care trona un nas proeminent, ce tisnea parca dintre doi ochi cenusii, de culoarea unui cer de toamna rece.\r\n     - Uita-te la fratii care ne insotesc si spune-mi ce vezi?\r\n     Tinarul privi inapoi, tulburat de situatia delicata in care il punea Staretul. Nu avea cum sa nu supere pe cineva... Stia ca Abatele astepta un raspuns in defavoarea fratilor-ostasi, renumiti pentru ritualurile lor sadice de initiere a candidatilor.\r\n     Sintem sub Dumnezeu!\r\n     Isidor zimbi insa unui gind tainic si rosti, masurindu-si cu atentie vorbele:\r\n     - Nu par convinsi ca Domnul nostru vegheaza asupra-ne si ca nu va lasa sa ni se intimple nimic rau. Iar cind Dumnezeu pravale furtuna asupra stincii, o face doar pentru a-i incerca taria!\r\n     Abatele zimbi. Barba sa impunatoare abia daca se misca, ascunzindu-i multumirea, atunci cind raspunse.\r\n     - Putini dintre predecesorii mei au avut parte de rugaciuni si luminare la capatii. Majoritatea au avut morti violente, il recompensa Abatele pe tinar.\r\n     Bucuros ca Staretul ii daduse ocazia sa citeze din Texte si chinuindu-se sa-si aduca aminte despre vreun novice care sa mai fi avut sansa asta, tinarul rosti repede:\r\n     - Dar lucrarea Lui a dainuit; printre zidurile Abatiei inca nu vijiie timpul.\r\n     Ochii Abatelui se intunecara. Printre novici se spunea ca un citat din Texte, strecurat in timpul consacrarii, era sinonim cu reusita. Tocmai de aceea Abatele nu obisnuia prea des sa dea tinerilor prilejul de a cita din Revelatiile Sfintului Augustin. Iar in rarele ocazii cind oferea aceasta ocazie, spera tainic ca ucenicul va ignora acest avantaj, spunind ca un text e doar un text, o insiruire de cuvinte, sipet aurit pentru o credinta adevarata, a carei esenta ramine insa inexprimabila.\r\n     - Sirguinta, baiete, sta la baza tuturor lucrurilor. Amestecata cu tot atita smerenie, ea formeaza lutul cel bun din care sint plamaditi cei mai alesi frati ai nostri, rosti Abatele de parca ar fi fost o masina de dictat, incercind sa-l faca pe candidat sa inteleaga ca victoria ii era inca departe.\r\n     Nici macar Dumnezeu nu e un cu adevarat sirguincios continua in gind Abatele. Se prea poate ca acum sa priveasca in cu totul alta parte si pe noi sa ne ucida vreo hoarda de barbari. Si care ar fi invatatura Lui pentru sufletele noastre? Sa nu fim trufasi, desigur...\r\n     - Spune-mi ce crezi despre Augustin Intemeietorul.\r\n     Candidatul incepu sa recite pe un ton egal un text pe care, cu multe decenii in urma, si Abatele il rostise intr-o ceremonie similara.\r\n     - Augustin, Intemeietorul si Intii Facatorul de Dumnezeu, a pornit lucrarea sfinta a Abatiei acum patruzeci si sapte de generatii. Strabatind desertul vechii Europe, din praful Pannoniei i s-a aratat Dumnezeu, care luase chipul unui hoinar incalecat pe o ticaloasa masinarie. "De ce te temi, Augustine?" a intrebat Domnul. "De minia ta, Doamne!" "Ba nu Augustine", i-a raspuns atunci Domnul, "te temi de suferinta pe care stii ca o poti indura in numele Meu. Ti-e frica de incercarile prin care Eu iti arat drumul. Dar oare, Augustine, te-ai gindit vreodata cit de mici sint fricile tale pe linga spaima Mea?..."\r\n     Eu sint Domnul Dumnezeu tau si mi-e frica numai de propria-mi nemarginire murmura in gind Abatele, dimpreuna cu Isidor.\r\n     - ... iar faptul ca te cheama Augustin, ca pe un discipol drag, e Intiia Mea lucrare. A doua sa fie o Abatie pe care sa o cladesti chiar in acest loc! a grait Domnul.\r\n     Si, in timp ce novicele depana cu voce egala Intemeierea, primul capitol al Revelatiilor, Abatele isi ingadui sa mediteze la aceasta istorie. In anii care trecusera de la moartea Sfintului Augustin, Abatia devenise un pilon important al Regatului Celor o Mie de Stele. Cele mai eficiente Lumi Agricole functionau numai datorita religiilor distilate de Abatie. Desi serviciile calugarilor terrani nu erau deloc ieftine, nici o familie care se respecta nu mai apela la vechile mijloace de socio-terraformare. Credintele importate din Abatie erau recunoscute ca fiind cel mai sigur mod de echilibrare durabila a comunitatilor rurale de clone, singura populatie acceptata pe Lumile Agricole.\r\n     Fara indoiala ca insusi Dumnezeu ne-a dat putere asupra fiilor sai, de vreme ce indeplinim cu totii lucrarea Lui.\r\n     Abatele privi spre cerul inalt, de vara. Fulgii unei zapezi timide ii intrara in ochi. Usturime. Cauta apoi spre tinarul care nu mai termina de recitat. Il intrerupse cu un gest si intreba pe un ton bolovanos:\r\n     - De ce crezi ca se teme Dumnezeu, baiete? Ca isi va atinge nemarginirea sau ca nu-si va afla niciodata limitele?\r\n     Naucit, novicele il privi deznadajduit pe Abate. Dogmele se invatau abia la zece ani de la consacrare si nu puteau fi nicidecum subiectul unei conversatii scolaresti. Pentru citeva clipe, Isidor spera ca Staretul incercase o gluma pe seama lui. Ochii stapinului sau straluceau reci si intrebatori.\r\n     - Sa nu indraznesti sa-mi raspunzi ca Domnului ii este frica de ambele extreme!\r\n     Tinarul inghiti in sec, miscindu-si spasmodic marul lui Adam.\r\n     - Cred ca Dumnezeu e mai mult decit cuvinte si noi trebuie sa ne judecam cu smerenie locul. Natura sentimentelor Lui nu poate fi nicicum confundata cu trairile noastre. Domnului ii este frica pentru lucrarea Lui ultima, pentru Omul caruia i-a jertfit atitia fii.\r\n     Staretul isi ingusta privirea, cugetind la cuvintele pronuntate cu o stranie insufletire:\r\n     - Adevarata semnificatie a cuvintelor Domnului catre Augustin, Intii Fauritorul de Dumnezeu, este aceea ca, El va fi prezent in tot ceea ce va realiza si oriunde se va duce Omul. Si tocmai de cararile intortocheate ale devenirii si ale mintii omenesti ii este frica lui Dumnezeu! Ii e teama ca il vom cauta in locuri in care nu vrea sa ajunga si ca il vom ignora pina si in bisericile Sale cele mai falnice!\r\n     Dintr-un motiv obscur, Isidor il nelinistea pe Abate. Perspectiva pe care o aruncase asupra Revelatiilor era, fireste, falsa, naiva, dar nu se potrivea nicicum cu ceea ce ii spusesera fratii arhivari despre Isidor. Ceva era strimb...\r\n     - Vorbele tale suna ca o erezie crestina! Fii atent, baiete! Nu m-am omorit niciodata dupa religia asta, il preveni Staretul pe novice.\r\n     Tinarul puse capul in pamint si se cufunda in mutenie. Staretului ii displacu tacerea care se lasase peste micul lor grup. Ii accentua o stare de neliniste care izvora de undeva de dincolo de constienta si de logica.\r\n     - Spune-mi ce stii despre economia Abatiei.\r\n     Isidor incepu sa vorbeasca repede:\r\n     - In ultimii zece ani, rata de rentabilitate a programelor noastre s-a imbunatatit cu 62 la suta, mai ales pe seama scaderii cu doua generatii a timpului minim de constructie a templelor. Pe Marish V si Dilin III au aparut temple chiar dupa trei generatii de la Insamintare, ceea ce a redus...\r\n     Novicele stia carte. Dar consacrarea avea legile ei clare, pe care nici macar Staretul nu putea sa le eludeze. Isidor trebuia sa recite. Abatele se cufunda din nou in gindurile sale. Citise undeva ca primii agricultori insamintau aruncind pe ogor seminte in niste brazde scrijelite cu pluguri primitive. De fiecare data cind se gindea la similitudinea dintre munca fratilor sai si aceea a primilor agricultori, Abatele era cuprins de un respect vecin cu duiosia.\r\n     E singurul timp in care sint sigur de esenta mea profund religioasa. Si noi insamintam cu virusii nostri. Prin voia Domnului, eu arunc semintele... Si dintr-o gloata replicata sinistru in maternitatile familiilor, clonele se transforma in oameni, ridicind dintre ei Mintuitorul. Mereu acelasi, mereu jertfit, mereu fauritor al Caii spre Dumnezeu. Si devin credinciosi, religiosi, muncitori, multumiti de statutul lor, fara ambitie, fara veleitarism, fara talente... In lipsa Lor, Regatul Celor o Mie de Stele ar muri de foame. Iar clonele il slavesc pe Dumnezeul nostru, care intuneca vederea Familiilor, nedeslusindu-le Lucrarea Lui: numai un om poate fi religios! Restul e Armaghedon.\r\n     Novicele terminase de citava vreme si astepta cuviincios ca Staretul sa-si reia intrebarile:\r\n     - O sa fii un frate bun, Isidor. Te vom primi printre noi si fi-vei ostean al Sfintului Augustin, Intii Facatorul de Dumnezeu.\r\n     Baiatul strinse tare din pumni si zimbi larg, lasind pentru o clipa impresia ca va incepe sa topaie de bucurie precum un copil.\r\n     - Iti voi pune insa o ultima intrebare, deja ca intre frati. Daca vrei, poti sa nu-mi raspunzi... De ce crezi ca exista Abatia? intreba Starostele aratind spre zidurile intunecate ale Fortaretei.\r\n     Baiatul cazu pe ginduri, dadu sa plece dar apoi isi lua inima in dinti.\r\n     Ciudat, ciudat...\r\n     - Misiunea noastra este sa-L sadim pe Dumnezeu acolo unde nu exista, sa luminam mintea celor care nu-L vor fi aflat inca...\r\n     Staretul clatina din cap, ignorindu-si cu greu senzatia clara a unui pericol iminent.\r\n     - Nu. nu, nu... Noi nu facem decit sa ii gasim pe fii lui Dumnezeu, care se jertfesc in numele Lui, care pun iertarea si umilinta mai presus de fiinta lor. Asta facem noi aici, baiete. Cautam intr-un sac de seminte pentru a o alege pe aceea din care va creste floarea cea mai frumoasa, aceea care va arata intregii gradini splendoarea unica a Dumnezeirii. Asta facem! Nu mi-ai raspuns la intrebare!\r\n     Zimbind larg tinarul rosti cu credinta:\r\n     - Pentru ca Dumnezeu nu se teme decit de propria-i nemarginire!\r\n     - Pe sandalele Sfintului Augustin, ce vrei sa spui?', '2007-04-12 09:32:45', '2007-04-12 09:32:45', 15);

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
(0x736b696e5f6261736963, 'Basic', 'The first skin of the site', 4, 1.00),
(0x646967675f74656368, 'Digg Tech Feed', 'Retrieves the latest digg tech stories', 3, 1.00),
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

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES (0, '20070406234932', 0x6363393231353033353538393831383662616538393661643531383038663261),
(0, '20070407113710', 0x6632333830316133316463363537346433636333313962643062636333633461),
(0, '20070410233953', 0x6230323863383935653633326466336639346663336663616639663332373935),
(0, '20070413163544', 0x3563316335343234323035626666343266323366303031396366656535343333),
(0, '20070409205632', 0x3534336338376362653435653863356138373738306162393132653861636562),
(0, '20070410133144', 0x6131636430643436376663623031383365353464396334626266663137346436),
(0, '20070410130410', 0x3361653036346338633663623033356135313631393236646131633962323364),
(0, '20070411095037', 0x3138313630636135663231396330303133306164666436306536343038373834),
(0, '20070414190327', 0x6332326535343838336435303762386631356233626539343231643231323839),
(0, '20070411131812', 0x6165616165306165313861616566663034633132636565326662383365303834),
(0, '20070414135330', 0x3738313162663266653164316564663732666564666231373266336432613130),
(0, '20070411152355', 0x3238373566323863303636616666333833613162346435626663303261396535),
(0, '20070411152456', 0x3132636130356539363037643265343065343564356433323234643231636135),
(0, '20070414152729', 0x6630346536663830633631363835386234306636616365303563343463366563),
(0, '20070414184004', 0x3066646538633531373237636239386234343961633765646236383638313136),
(0, '20070412163833', 0x6637663662653366386136666165313834366133616331616366313636393937),
(0, '20070414223354', 0x6465343363303666626466376633656534393331306335313166653331316636);

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

INSERT INTO `dsb_photo_comments` (`comment_id`, `fk_photo_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (7, 14, 1, 'test', 'beeeton', '2007-04-14 10:38:08', '2007-04-14 10:38:08', 15),
(6, 79, 2, 'test', 'bleah\r\n[b]cahhhhhhhhhhh[/b]', '2007-03-22 21:00:15', '2007-03-22 21:00:15', 15);

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

INSERT INTO `dsb_photo_ratings` (`fk_photo_id`, `fk_user_id`, `vote`, `date_voted`) VALUES (3, 1, 3, '2007-04-11 14:24:28'),
(3, 1, 3, '2007-04-11 14:24:54'),
(3, 1, 3, '2007-04-11 14:25:17'),
(3, 1, 3, '2007-04-11 14:25:21'),
(3, 1, 3, '2007-04-11 14:25:21'),
(3, 1, 4, '2007-04-11 14:25:45'),
(3, 1, 5, '2007-04-11 14:38:00'),
(3, 1, 4, '2007-04-11 14:38:05'),
(3, 1, 3, '2007-04-11 14:38:09'),
(3, 1, 2, '2007-04-11 14:38:18'),
(3, 1, 1, '2007-04-11 14:40:21'),
(3, 1, 2, '2007-04-11 14:40:27'),
(3, 1, 1, '2007-04-11 14:40:29'),
(3, 1, 1, '2007-04-11 14:40:32'),
(3, 1, 5, '2007-04-11 14:40:37'),
(3, 1, 4, '2007-04-11 14:42:00'),
(3, 1, 4, '2007-04-11 14:42:36'),
(3, 1, 4, '2007-04-11 14:42:53'),
(3, 1, 5, '2007-04-11 14:42:58'),
(3, 1, 5, '2007-04-11 14:42:59'),
(3, 1, 5, '2007-04-11 14:43:05'),
(3, 1, 5, '2007-04-11 14:43:07'),
(3, 1, 5, '2007-04-11 14:43:08'),
(3, 1, 5, '2007-04-11 14:43:09'),
(3, 1, 5, '2007-04-11 14:43:09'),
(3, 1, 5, '2007-04-11 14:43:11'),
(3, 1, 5, '2007-04-11 14:43:12'),
(3, 1, 5, '2007-04-11 14:43:13'),
(3, 1, 5, '2007-04-11 14:43:15'),
(3, 1, 5, '2007-04-11 14:43:16'),
(3, 1, 5, '2007-04-11 14:43:18'),
(3, 1, 5, '2007-04-11 14:43:19'),
(3, 1, 5, '2007-04-11 14:43:21'),
(3, 1, 5, '2007-04-11 14:43:22'),
(3, 1, 5, '2007-04-11 14:43:22'),
(3, 1, 5, '2007-04-11 14:43:22'),
(3, 1, 5, '2007-04-11 14:43:22'),
(3, 1, 5, '2007-04-11 14:43:23'),
(3, 1, 5, '2007-04-11 14:43:23'),
(3, 1, 5, '2007-04-11 14:44:29'),
(3, 1, 5, '2007-04-11 14:44:32'),
(3, 1, 5, '2007-04-11 14:44:33'),
(3, 1, 5, '2007-04-11 14:44:35'),
(3, 1, 1, '2007-04-11 14:59:54'),
(3, 1, 2, '2007-04-11 14:59:57'),
(3, 1, 4, '2007-04-11 15:00:00'),
(3, 1, 1, '2007-04-11 15:00:03'),
(3, 1, 5, '2007-04-11 15:00:06'),
(14, 1, 4, '2007-04-12 12:25:45'),
(15, 2, 5, '2007-04-12 12:29:00'),
(1, 1, 4, '2007-04-14 08:56:26'),
(15, 1, 4, '2007-04-14 12:16:29'),
(14, 1, 1, '2007-04-14 12:20:02');

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

INSERT INTO `dsb_profile_fields` (`pfield_id`, `fk_lk_id_label`, `html_type`, `searchable`, `search_type`, `for_basic`, `fk_lk_id_search`, `at_registration`, `reg_page`, `required`, `editable`, `visible`, `dbfield`, `fk_lk_id_help`, `fk_pcat_id`, `access_level`, `accepted_values`, `default_value`, `default_search`, `fn_on_change`, `order_num`) VALUES (1, 3, 2, 0, 1, 0, 4, 1, 2, 0, 1, 1, 0x6631, 5, 1, 0, '', '', '', '', 5),
(2, 8, 3, 1, 10, 1, 9, 1, 1, 1, 1, 1, 0x6632, 10, 1, 0, '|6|7|', '|0|', '|1|', '', 1),
(3, 13, 10, 1, 10, 1, 14, 1, 1, 1, 1, 1, 0x6633, 15, 1, 0, '|11|12|', '|1|', '|0|', '', 2),
(4, 22, 3, 1, 108, 0, 23, 1, 2, 0, 1, 1, 0x6634, 24, 2, 0, '|49|50|51|52|46|47|48|53|54|55|57|58|106|59|60|61|62|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|', '', '|0|59|', '', 7),
(5, 25, 103, 1, 108, 1, 26, 1, 3, 0, 1, 1, 0x6635, 27, 1, 0, '|1930|1989|', '', '|18|75|', '', 3),
(6, 28, 107, 1, 107, 1, 29, 1, 1, 0, 1, 1, 0x6636, 30, 1, 0, '', '|218|', '', 'update_location', 4),
(7, 43, 3, 1, 10, 0, 44, 1, 2, 0, 1, 1, 0x6637, 45, 2, 0, '|34|35|36|37|38|39|40|41|42|', '', '|0|', '', 6),
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
  KEY `user_id_2` (`fk_user_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_queue_message`
-- 

INSERT INTO `dsb_queue_message` (`mail_id`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 1, 1, 'test', 'test sent you a flirt', 'Aye aye, mate!', '2007-04-11 09:09:41', 1);

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

INSERT INTO `dsb_site_log` (`log_id`, `fk_user_id`, `user`, `m_value`, `fk_level_id`, `ip`, `time`) VALUES (1, 0, 'test', 1, 1, 2130706433, '20070407114023'),
(2, 0, 'test', 1, 1, 2130706433, '20070407114247'),
(3, 0, 'test', 1, 1, 2130706433, '20070407121752'),
(4, 0, 'test', 1, 1, 2130706433, '20070407121753'),
(5, 0, 'test', 1, 1, 2130706433, '20070407124935'),
(6, 0, 'test', 1, 1, 2130706433, '20070409191932'),
(7, 0, 'test', 1, 1, 2130706433, '20070409191934'),
(8, 0, 'test', 1, 1, 2130706433, '20070409200222'),
(9, 0, 'test', 1, 1, 2130706433, '20070409200246'),
(10, 0, 'test', 1, 1, 2130706433, '20070409204827'),
(11, 0, 'test', 1, 1, 2130706433, '20070409205019'),
(12, 0, 'test3', 1, 1, 2130706433, '20070409205128'),
(13, 0, 'test', 1, 1, 2130706433, '20070409224216'),
(14, 0, 'test', 1, 1, 2130706433, '20070410000316'),
(15, 0, 'test', 1, 1, 2130706433, '20070410111510'),
(16, 0, 'test', 1, 1, 2130706433, '20070410112117'),
(17, 0, 'test', 1, 1, 2130706433, '20070410112122'),
(18, 0, 'test', 1, 1, 2130706433, '20070410192008'),
(19, 0, 'test', 1, 1, 2130706433, '20070410192334'),
(20, 0, 'test', 1, 1, 2130706433, '20070410192645'),
(21, 0, 'test', 1, 1, 2130706433, '20070411103642'),
(22, 0, 'test', 1, 1, 2130706433, '20070411155439'),
(23, 0, 'test', 1, 1, 2130706433, '20070411172428'),
(24, 0, 'test', 1, 1, 2130706433, '20070411195947'),
(25, 0, 'test', 1, 1, 2130706433, '20070412084633'),
(26, 0, 'test', 1, 1, 2130706433, '20070412140306'),
(27, 0, 'test2', 1, 1, 2130706433, '20070412151629'),
(28, 0, 'test2', 1, 1, 2130706433, '20070412151640'),
(29, 0, 'test', 1, 1, 2130706433, '20070412151714'),
(30, 0, 'test3', 1, 1, 2130706433, '20070412152122'),
(31, 0, 'test', 1, 1, 2130706433, '20070412152428'),
(32, 0, 'test', 1, 1, 2130706433, '20070412152445'),
(33, 0, 'test', 1, 1, 2130706433, '20070412152538'),
(34, 0, 'test2', 1, 1, 2130706433, '20070412152724'),
(35, 0, 'test', 1, 1, 2130706433, '20070412153049'),
(36, 0, 'test2', 1, 1, 2130706433, '20070412163847'),
(37, 0, 'test', 1, 1, 2130706433, '20070413133346'),
(38, 0, 'test2', 1, 1, 2130706433, '20070413163521'),
(39, 0, 'test3', 1, 1, 2130706433, '20070413163539'),
(40, 0, 'test', 1, 1, 2130706433, '20070413163548'),
(41, 0, 'test', 1, 1, 2130706433, '20070414113652'),
(42, 0, 'test2', 1, 1, 2130706433, '20070414134035'),
(43, 0, 'test', 1, 1, 2130706433, '20070414134959'),
(44, 0, 'test2', 1, 1, 2130706433, '20070414135021'),
(45, 0, 'test', 1, 1, 2130706433, '20070414135027'),
(46, 0, 'test', 1, 1, 2130706433, '20070414175445'),
(47, 0, 'test', 1, 1, 2130706433, '20070414185411');

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
(3, 0x6d616e75616c5f70726f66696c655f617070726f76616c, '1', 'New profiles or changes to existing profiles require manual approval from an administrator before being displayed on site?', 9, 0x636f7265, 0),
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
(19, 0x736563726574, 'terebentina', 'The secret word you set in your 2co account', 2, 0x74776f636865636b6f7574, 0),
(20, 0x6c6963656e73655f6b6579, '1234', 'Your Maxmind license key', 2, 0x6d61786d696e64, 0),
(21, 0x7573655f7175657565, '1', 'Use the message queue (recommended) or send the messages directly?', 9, 0x636f7265, 0),
(22, 0x6d61696c5f66726f6d, 'dan@rdsct.ro', 'Email address to send emails from', 2, 0x636f7265, 0),
(23, 0x6262636f64655f70726f66696c65, '1', 'Use BBcode in profile fields? (like about me, about you)', 9, 0x636f7265, 0),
(24, 0x6262636f64655f636f6d6d656e7473, '1', 'Use BBcode in comments?', 9, 0x636f7265, 0),
(25, 0x736b696e5f646972, 'basic', 'Skin folder name in the skins folder.', 0, 0x736b696e5f6261736963, 0),
(26, 0x736b696e5f6e616d65, 'Basic', '', 0, 0x736b696e5f6261736963, 0),
(27, 0x666b5f6c6f63616c655f6964, '11', '', 0, 0x736b696e5f6261736963, 0),
(28, 0x69735f64656661756c74, '0', 'Is this skin the default site skin?', 0, 0x736b696e5f6261736963, 0),
(32, 0x6d696e5f73697a65, '0', 'Minimum photo file size in bytes (use 0 for not limited).', 104, 0x636f72655f70686f746f, 0),
(33, 0x6d61785f73697a65, '0', 'Maximum photo file size in bytes (use 0 for server default).', 104, 0x636f72655f70686f746f, 0),
(34, 0x6262636f64655f6d657373616765, '1', 'Allow BBCode in member to member messages?', 9, 0x636f7265, 0),
(35, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 'Date and time format', 2, 0x636f7265, 1),
(36, 0x726f756e645f636f726e657273, '1', 'Use round corners for user photos?', 9, 0x636f72655f70686f746f, 0),
(37, 0x656e61626c6564, '1', 'Enable this widget?', 9, 0x646967675f74656368, 0),
(38, 0x666565645f75726c, 'http://digg.com/rss/containertechnology.xml', 'The url of the feed', 2, 0x646967675f74656368, 0),
(39, 0x736b696e5f646972, 'def', '', 0, 0x736b696e5f646566, 0),
(40, 0x736b696e5f6e616d65, 'Default', '', 0, 0x736b696e5f646566, 0),
(41, 0x666b5f6c6f63616c655f6964, '11', '', 0, 0x736b696e5f646566, 0),
(42, 0x69735f64656661756c74, '1', '', 0, 0x736b696e5f646566, 0),
(43, 0x696e6163746976655f74696d65, '5', 'Time of inactivity in minutes after a member is considered offline', 104, 0x636f7265, 0),
(44, 0x6262636f64655f626c6f6773, '1', 'Allow bbcode in blog posts?', 9, 0x636f72655f626c6f67, 0),
(45, 0x73656e645f616c6572745f696e74657276616c, '2', 'How often do you want to receive your search matches? (days)', 104, 0x6465665f757365725f7072656673, 1),
(46, 0x726174655f6d795f70726f66696c65, '1', 'Allow your profile to be rated?', 9, 0x6465665f757365725f7072656673, 1),
(47, 0x726174655f6d795f70686f746f73, '1', 'Allow your photos to be rated?', 9, 0x6465665f757365725f7072656673, 1),
(48, 0x70757267655f756e7665726966696564, '7', 'Purge unverified accounts after how many days?', 104, 0x636f7265, 0),
(50, 0x6e6f746966795f6d65, '1', 'Send me email notifications when I receive messages?', 9, 0x6465665f757365725f7072656673, 1);

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

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES ('58a4fac5fe804a4e068ea95e0ca1029d', 1, 'a:8:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f4_min";s:1:"1";s:6:"f4_max";s:1:"6";s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";}', '', 0, '20070409144626'),
('f229dff8e9bf417f8a116f6c693f4864', 1, 'a:4:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}}', '', 1, '20070410125422'),
('5f129e4520ac00fc5177c7b7a26c2ca9', 1, 'a:8:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f4_min";s:1:"1";s:6:"f4_max";s:1:"6";s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";}', '', 1, '20070410200426'),
('8816b2c8b45df99a22880b00513d7867', 1, 'a:1:{s:5:"pstat";s:1:"5";}', '1,2,3', 0, '20070410200527'),
('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,3', 0, '20070410202713'),
('0449abf79c5a3f2f76d5f0ec03a8dd25', 1, 'a:2:{s:11:"acclevel_id";i:16;s:2:"st";s:6:"latest";}', '3,2,1', 2, '20070410232101'),
('04a51b8634d9c552baeb60eb5109b481', 2, 'a:1:{s:4:"stat";s:1:"5";}', '', 0, '20070411103830'),
('d9c125814e6d01cc042129671cc93b19', 1, 'a:2:{s:11:"acclevel_id";i:16;s:2:"st";s:6:"online";}', '1,2', 1, '20070411114514'),
('9e0e733657e4978c3fb5fcdea3f0dc2b', 1, 'a:8:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:2:"f2";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f4_min";s:1:"1";s:6:"f4_max";s:1:"6";s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";}', '', 1, '20070411230259'),
('654d666a7ef3cdf3a31d36d7676f898e', 1, 'a:8:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:2:"f2";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:2:"f3";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:6:"f4_min";s:1:"1";s:6:"f4_max";s:1:"6";s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";}', '1', 1, '20070411230306'),
('a02a5083e81539264df53de31964da9a', 2, 'a:1:{s:7:"flagged";s:1:"1";}', '', 0, '20070412091012'),
('40cd750bba9870f18aada2478b24840a', 2, 'a:0:{}', '1,2,3,4,5,6,7', 0, '20070412091016'),
('7e81e2de7df88ab99c15c4a8971b2b9d', 1, 'a:2:{s:11:"acclevel_id";i:16;s:2:"st";s:3:"new";}', '3,2,1', 1, '20070412151925'),
('79a2ea62a425ff437f970e3b3407db76', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:9:"computers";}', '2', 2, '20070412225204'),
('31b8ba2df63d76c2dae7c1d5e7146838', 3, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:5:"views";}', '1,2,3,4', 2, '20070412225212'),
('c6109b40aa58997744213128291c7b53', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:5:"earth";}', '2', 1, '20070413133346'),
('4bb808db25e5349571c321a8d4ab0bec', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:6:"уея";}', '', 1, '20070413134539'),
('65bf4d8448f34c3fd5e985dcb5cd9608', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:32:"специализируется";}', '', 1, '20070413135650'),
('8a03fb895f0b3a9bbd3185ef5553bb07', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:6:"mobile";}', '3', 1, '20070413140022'),
('9d53c19391ff02a9daa4fe9d6d7b7c56', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:10:"играх";}', '', 1, '20070413140032'),
('824507ed8719f0a811ef06eb6bec9759', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:12:"firstnothing";}', '', 1, '20070413143401'),
('9a4e65874be908e020256e8c214ea02d', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:5:"first";}', '', 1, '20070413143426'),
('b44883f63f012af69a37147257a70cec', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:13:"computerssort";}', '', 1, '20070413143555'),
('e36283738a5f31bc36497d06cd21b753', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:14:"computers sort";}', '2', 1, '20070413143841'),
('0259caf83d0105367947ec0bdd101338', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:17:"result committees";}', '2', 1, '20070413143915'),
('2e3f882251d90c3bdd28e3ce0977eb96', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:4:"asda";}', '', 1, '20070413144123'),
('feff091ed1dfafe7304140b433319b11', 3, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:4:"comm";}', '1,2,3,4', 1, '20070413144136'),
('eba15f899a6a6d0596b3b3f1c4af00fe', 3, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"new";}', '4,3,2,1', 1, '20070413144201'),
('51ed9256e4c8850c602134f7d0cad419', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:16:"computer special";}', '2', 1, '20070413150019'),
('ff48ff27034b9a71bd2ce49c8b7bfcc5', 3, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"ggg";}', '4,3,2,1', 1, '20070413150435'),
('a5b56d003363affd31ccfddaa65dc425', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:7:"nothing";}', '', 1, '20070413150449'),
('1f1431fc4bc46ad030c1a33fa4d9e758', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:20:"blog  could  nothing";}', '1', 1, '20070413150519'),
('c821195d8de01feb4bf9074f32209e64', 3, 'a:3:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"tag";s:4:"tags";s:4:"test";}', '', 1, '20070413163647'),
('4cd40acb49a545cc090e3988dc5f55b2', 1, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:6:"latest";}', '2,3', 1, '20070414135949'),
('9057d3e596c42fff29b9476632483e16', 1, 'a:7:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";s:10:"f6_country";s:3:"218";}', '', 0, '20070414223326');

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

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES (1, 0x74657374, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', 'bd7adef567b68d45680681c80caea0d5', '20070414185949'),
(2, 0x7465737432, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', 'e48ff8c5b0ad60f79815afeae1b91cb2', '20070414135021'),
(3, 0x7465737433, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 2, 'newdsb@sco.ro', '', '8261d480f9450587f39447d310f0d529', '20070413163541');

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
  `fk_user_id_friend` int(10) unsigned NOT NULL default '0',
  `nconn_status` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`nconn_id`),
  UNIQUE KEY `unique1` (`fk_user_id`,`fk_net_id`,`fk_user_id_friend`),
  KEY `index1` (`fk_user_id`,`fk_net_id`,`nconn_status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_networks`
-- 


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

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `allow_rating`, `caption`, `status`, `del`, `flagged`, `reject_reason`, `stat_views`, `stat_votes`, `stat_votes_total`, `stat_comments`, `date_posted`, `last_changed`) VALUES (1, 2, 'test2', '8/2_11176235928.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 4, 1, 4, 0, '2007-04-10 20:12:09', '2007-04-10 20:12:21'),
(2, 1, 'test', '0/1_11176277054.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 2, 0, 0, 0, '2007-04-11 07:37:49', '2007-04-11 07:52:11'),
(3, 1, 'test', '6/1_21176277054.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 3, 42, 174, 0, '2007-04-11 07:37:49', '2007-04-11 07:38:14'),
(4, 1, 'test', '5/1_31176277054.jpg', 0, 0, 0, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-11 07:37:49', '2007-04-11 07:38:14'),
(5, 1, 'test', '3/1_41176277054.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-11 07:37:49', '2007-04-11 07:38:14'),
(6, 1, 'test', '0/1_51176277054.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-11 07:37:49', '2007-04-12 06:15:52'),
(7, 1, 'test', '4/1_61176277054.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-11 07:37:49', '2007-04-11 07:38:14'),
(8, 1, 'test', '0/1_11176380289.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:18:13', '2007-04-12 12:18:29'),
(9, 1, 'test', '9/1_21176380289.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:18:13', '2007-04-12 12:18:29'),
(10, 1, 'test', '1/1_31176380289.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:18:13', '2007-04-12 12:18:37'),
(11, 1, 'test', '3/1_41176380289.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:18:13', '2007-04-12 12:18:29'),
(12, 1, 'test', '3/1_51176380289.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:18:13', '2007-04-12 12:18:29'),
(13, 1, 'test', '0/1_61176380289.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:18:13', '2007-04-12 12:18:29'),
(14, 3, 'test3', '4/3_11176380554.jpg', 1, 0, 1, 1, '', 15, 0, 0, '', 8, 2, 5, 1, '2007-04-12 12:22:49', '2007-04-12 12:23:04'),
(15, 3, 'test3', '4/3_21176380554.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 5, 2, 9, 0, '2007-04-12 12:22:49', '2007-04-12 12:23:04'),
(16, 3, 'test3', '0/3_31176380554.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 1, 0, 0, 0, '2007-04-12 12:22:49', '2007-04-12 12:23:04'),
(17, 3, 'test3', '4/3_41176380554.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:22:49', '2007-04-12 12:23:04'),
(18, 3, 'test3', '3/3_51176380554.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:22:49', '2007-04-12 12:23:04'),
(19, 3, 'test3', '6/3_61176380554.jpg', 0, 0, 1, 1, '', 15, 0, 0, '', 0, 0, 0, 0, '2007-04-12 12:22:49', '2007-04-12 12:23:04');

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
  KEY `key1` (`status`,`del`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_profiles`
-- 

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `del`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6_country`, `f6_state`, `f6_city`, `f6_zip`, `f7`, `f8`, `f9`, `f10`, `f11`, `f12`, `f13`, `f14`, `f15`) VALUES (1, 1, 5, 0, '2007-04-14 15:59:49', '2007-04-06 21:01:10', '', 'test', '1/1_31176380289.jpg', 0.0000000000, 0.0000000000, 13, 'ala babala portocala', 1, '|2|', 4, '1976-11-01', 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(2, 2, 15, 0, '2007-04-10 20:12:21', '2007-04-07 08:46:16', '', 'test2', '8/2_11176235928.jpg', 0.0000000000, 0.0000000000, 15, 'alandala', 1, '|2|', 3, '0000-00-00', 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, ''),
(3, 3, 15, 0, '2007-04-12 12:23:04', '2007-04-07 10:20:56', '', 'test3', '4/3_11176380554.jpg', 0.0000000000, 0.0000000000, 2, '', 2, '|1|2|', 3, '0000-00-00', 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '');

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

INSERT INTO `dsb_user_stats` (`fk_user_id`, `stat`, `value`) VALUES (2, 'total_photos', 1),
(1, 'total_photos', 12),
(1, 'flirts_sent', 1),
(1, 'blog_posts', 3),
(3, 'total_photos', 6),
(2, 'blog_posts', 1),
(1, 'comments', 1);
