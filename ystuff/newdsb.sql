-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 05, 2007 at 10:29 PM
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

INSERT INTO `dsb_blog_comments` (`comment_id`, `fk_post_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (1, 19, 2, 'test', 'bine baaaa', '2007-03-17 15:25:50', '2007-03-17 15:25:50', 15),
(2, 19, 2, 'test', 'toata viata mea\r\ndoar iubirea ta\r\nma poate salvaaaaaa\r\n\r\nnanananan nanan an ana nan an a\r\n', '2007-03-17 15:27:26', '2007-03-17 15:27:26', 15),
(3, 19, 2, 'test', 'se spune ca vremea-i frumoasa atunci cand in suflet e soare\r\nse spune ca ploaia-i mai rece atunci cand in suflet te doar\r\nse supune ca vantul nu bate in loc atunci cand iubesti', '2007-03-17 15:28:36', '2007-03-17 15:28:36', 15),
(4, 19, 2, 'test', '[b]te voi astepta[/b]\r\ntoata viata [quote]meaaaa[/quote]\r\ndoar iubirea taaaa\r\nma poate salvaaaaa', '2007-03-17 15:29:27', '2007-03-17 15:29:27', 15);

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
  KEY `fk_user_id` (`fk_user_id`),
  KEY `is_public` (`is_public`),
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_blog_id`,`fk_user_id`)
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `dsb_blog_posts`
-- 

INSERT INTO `dsb_blog_posts` (`post_id`, `date_posted`, `fk_user_id`, `_user`, `fk_blog_id`, `is_public`, `title`, `post_content`, `allow_comments`, `status`, `post_url`, `stat_views`, `stat_comments`, `last_changed`, `reject_reason`) VALUES (1, '2006-10-25 14:02:46', 2, 'test', 1, 1, 'test titlu', 'ala bala portocala', 1, 15, '', 0, 0, '2006-10-25 14:02:46', ''),
(2, '2006-10-25 20:55:04', 2, 'test', 1, 1, 'second post', '// get the input we need and sanitize it\r\n	foreach ($blog_posts_default[''types''] as $k=>$v) {\r\n		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$blog_posts_default[''defaults''][$k]);\r\n	}\r\n	$input[''fk_user_id'']=$_SESSION[''user''][''user_id''];\r\n	$input[''posted_by'']=$_SESSION[''user''][''user''];\r\n\r\n	if (!$error) {\r\n		if (!empty($input[''post_id''])) {\r\n			unset($input[''date_posted'']);\r\n			$query="UPDATE `$blog_posts` SET `last_changed`=''".gmdate(''YmdHis'')."''";\r\n			if (get_site_option(''manual_blog_approval'',2)==1) {\r\n				$query.=",`status`=''".PSTAT_PROCESSING."''";\r\n			} else {\r\n				$query.=",`status`=''".PSTAT_APPROVED."''";\r\n			}\r\n			foreach ($blog_posts_default[''defaults''] as $k=>$v) {\r\n				if (isset($input[$k])) {\r\n					$query.=",`$k`=''".$input[$k]."''";\r\n				}\r\n			}\r\n			$query.=" WHERE `post_id`=''".$input[''post_id'']."''";\r\n			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}\r\n			$topass[''message''][''type'']=MESSAGE_INFO;\r\n			$topass[''message''][''text'']=''Post changed successfully.'';\r\n		} else {\r\n			$input[''date_posted'']=date(''Y-m-d H:i:s'');\r\n			$query="INSERT INTO `$blog_posts` SET `last_changed`=''".gmdate(''YmdHis'')."''";\r\n			if (get_site_option(''manual_blog_approval'',2)==1) {\r\n				$query.=",`status`=''".PSTAT_PROCESSING."''";\r\n			} else {\r\n				$query.=",`status`=''".PSTAT_APPROVED."''";\r\n			}\r\n			foreach ($blog_posts_default[''defaults''] as $k=>$v) {\r\n				if (isset($input[$k])) {\r\n					$query.=",`$k`=''".$input[$k]."''";\r\n				}\r\n			}\r\n			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}\r\n			$topass[''message''][''type'']=MESSAGE_INFO;\r\n			$topass[''message''][''text'']=''Post saved.'';\r\n		}\r\n	} else {\r\n		$nextpage=''blogs_addedit.php'';\r\n		$topass[''input'']=$input;\r\n	}\r\n}\r\nredirect2page($nextpage,$topass,$qs);\r\n?>', 1, 15, '', 0, 0, '2007-03-16 13:02:39', ''),
(3, '2007-03-11 13:39:52', 2, 'test', 1, 1, 'alt titlu la oha', 'si continut lipsa', 1, 15, '', 0, 0, '2007-03-16 13:03:13', ''),
(4, '2007-03-11 13:44:03', 2, 'test', 1, 1, 'titlu aiurea', 'content aiurea', 0, 15, '', 0, 0, '2007-03-16 13:02:56', ''),
(5, '2007-03-11 14:58:00', 2, 'test', 1, 1, 'primul blog', 'testing testing paratesting', 1, 15, '', 0, 0, '2007-03-16 13:49:53', ''),
(6, '2007-03-11 14:59:56', 2, 'test', 1, 1, 'test2', 'test2', 0, 15, '', 0, 0, '2007-03-16 14:10:50', ''),
(7, '2007-03-11 16:25:29', 2, 'test', 3, 1, 'test second life', 'ala bala\r\n\r\n\r\nportocala', 0, 15, '', 0, 0, '2007-03-11 16:25:29', ''),
(8, '2007-03-11 16:28:05', 2, 'test', 3, 1, 'inca unu si ma duc', 'gata', 0, 15, '', 0, 0, '2007-03-11 16:28:05', '');
INSERT INTO `dsb_blog_posts` (`post_id`, `date_posted`, `fk_user_id`, `_user`, `fk_blog_id`, `is_public`, `title`, `post_content`, `allow_comments`, `status`, `post_url`, `stat_views`, `stat_comments`, `last_changed`, `reject_reason`) VALUES (9, '2007-03-11 16:28:37', 2, 'test', 3, 1, 'Pletele Sfintului Augustin', '1.\r\n\r\nFratii-ostasi nici macar nu incercau sa-si disimuleze nemultumirea. De cind Abatele hotarise sa pronunte consacrari numai in afara Fortaretei, paznicii nu incetasera sa se plinga, argumentind ca o asemenea schimbare nu numai ca nu avea precedent in istoria de 47 de generatii a Abatiei dar reprezenta si o invitatie la violenta pentru hoardele de barbari care bintuiau cimpiile arse ale vechii Terre.\r\n\r\n- Nu mai exista credinta, Isidor! Fiecare dintre noi e convins ca poate hotari pentru propria persoana, ca in nimicnicia lui, poate schimba cumva cuvintul lui Dumnezeu, vorbi cu blindete Abatele privind cu indulgenta spre candidat.\r\nStaretul era un barbat in putere, cu fata acoperita de o barba imensa, peste care trona un nas proeminent, ce tisnea parca dintre doi ochi cenusii, de culoarea unui cer de toamna rece.\r\n- Uita-te la fratii care ne insotesc si spune-mi ce vezi?\r\nTinarul privi inapoi, tulburat de situatia delicata in care il punea Staretul. Nu avea cum sa nu supere pe cineva... Stia ca Abatele astepta un raspuns in defavoarea fratilor-ostasi, renumiti pentru ritualurile lor sadice de initiere a candidatilor.\r\n     Sintem sub Dumnezeu!\r\n     Isidor zimbi insa unui gind tainic si rosti, masurindu-si cu atentie vorbele:\r\n     - Nu par convinsi ca Domnul nostru vegheaza asupra-ne si ca nu va lasa sa ni se intimple nimic rau. Iar cind Dumnezeu pravale furtuna asupra stincii, o face doar pentru a-i incerca taria!\r\n     Abatele zimbi. Barba sa impunatoare abia daca se misca, ascunzindu-i multumirea, atunci cind raspunse.\r\n     - Putini dintre predecesorii mei au avut parte de rugaciuni si luminare la capatii. Majoritatea au avut morti violente, il recompensa Abatele pe tinar.\r\n     Bucuros ca Staretul ii daduse ocazia sa citeze din Texte si chinuindu-se sa-si aduca aminte despre vreun novice care sa mai fi avut sansa asta, tinarul rosti repede:\r\n     - Dar lucrarea Lui a dainuit; printre zidurile Abatiei inca nu vijiie timpul.\r\n     Ochii Abatelui se intunecara. Printre novici se spunea ca un citat din Texte, strecurat in timpul consacrarii, era sinonim cu reusita. Tocmai de aceea Abatele nu obisnuia prea des sa dea tinerilor prilejul de a cita din Revelatiile Sfintului Augustin. Iar in rarele ocazii cind oferea aceasta ocazie, spera tainic ca ucenicul va ignora acest avantaj, spunind ca un text e doar un text, o insiruire de cuvinte, sipet aurit pentru o credinta adevarata, a carei esenta ramine insa inexprimabila.\r\n     - Sirguinta, baiete, sta la baza tuturor lucrurilor. Amestecata cu tot atita smerenie, ea formeaza lutul cel bun din care sint plamaditi cei mai alesi frati ai nostri, rosti Abatele de parca ar fi fost o masina de dictat, incercind sa-l faca pe candidat sa inteleaga ca victoria ii era inca departe.\r\n     Nici macar Dumnezeu nu e un cu adevarat sirguincios continua in gind Abatele. Se prea poate ca acum sa priveasca in cu totul alta parte si pe noi sa ne ucida vreo hoarda de barbari. Si care ar fi invatatura Lui pentru sufletele noastre? Sa nu fim trufasi, desigur...\r\n     - Spune-mi ce crezi despre Augustin Intemeietorul.\r\n     Candidatul incepu sa recite pe un ton egal un text pe care, cu multe decenii in urma, si Abatele il rostise intr-o ceremonie similara.\r\n     - Augustin, Intemeietorul si Intii Facatorul de Dumnezeu, a pornit lucrarea sfinta a Abatiei acum patruzeci si sapte de generatii. Strabatind desertul vechii Europe, din praful Pannoniei i s-a aratat Dumnezeu, care luase chipul unui hoinar incalecat pe o ticaloasa masinarie. "De ce te temi, Augustine?" a intrebat Domnul. "De minia ta, Doamne!" "Ba nu Augustine", i-a raspuns atunci Domnul, "te temi de suferinta pe care stii ca o poti indura in numele Meu. Ti-e frica de incercarile prin care Eu iti arat drumul. Dar oare, Augustine, te-ai gindit vreodata cit de mici sint fricile tale pe linga spaima Mea?..."\r\n     Eu sint Domnul Dumnezeu tau si mi-e frica numai de propria-mi nemarginire murmura in gind Abatele, dimpreuna cu Isidor.\r\n     - ... iar faptul ca te cheama Augustin, ca pe un discipol drag, e Intiia Mea lucrare. A doua sa fie o Abatie pe care sa o cladesti chiar in acest loc! a grait Domnul.\r\n     Si, in timp ce novicele depana cu voce egala Intemeierea, primul capitol al Revelatiilor, Abatele isi ingadui sa mediteze la aceasta istorie. In anii care trecusera de la moartea Sfintului Augustin, Abatia devenise un pilon important al Regatului Celor o Mie de Stele. Cele mai eficiente Lumi Agricole functionau numai datorita religiilor distilate de Abatie. Desi serviciile calugarilor terrani nu erau deloc ieftine, nici o familie care se respecta nu mai apela la vechile mijloace de socio-terraformare. Credintele importate din Abatie erau recunoscute ca fiind cel mai sigur mod de echilibrare durabila a comunitatilor rurale de clone, singura populatie acceptata pe Lumile Agricole.\r\n     Fara indoiala ca insusi Dumnezeu ne-a dat putere asupra fiilor sai, de vreme ce indeplinim cu totii lucrarea Lui.\r\n     Abatele privi spre cerul inalt, de vara. Fulgii unei zapezi timide ii intrara in ochi. Usturime. Cauta apoi spre tinarul care nu mai termina de recitat. Il intrerupse cu un gest si intreba pe un ton bolovanos:\r\n     - De ce crezi ca se teme Dumnezeu, baiete? Ca isi va atinge nemarginirea sau ca nu-si va afla niciodata limitele?\r\n     Naucit, novicele il privi deznadajduit pe Abate. Dogmele se invatau abia la zece ani de la consacrare si nu puteau fi nicidecum subiectul unei conversatii scolaresti. Pentru citeva clipe, Isidor spera ca Staretul incercase o gluma pe seama lui. Ochii stapinului sau straluceau reci si intrebatori.\r\n     - Sa nu indraznesti sa-mi raspunzi ca Domnului ii este frica de ambele extreme!\r\n     Tinarul inghiti in sec, miscindu-si spasmodic marul lui Adam.\r\n     - Cred ca Dumnezeu e mai mult decit cuvinte si noi trebuie sa ne judecam cu smerenie locul. Natura sentimentelor Lui nu poate fi nicicum confundata cu trairile noastre. Domnului ii este frica pentru lucrarea Lui ultima, pentru Omul caruia i-a jertfit atitia fii.\r\n     Staretul isi ingusta privirea, cugetind la cuvintele pronuntate cu o stranie insufletire:\r\n     - Adevarata semnificatie a cuvintelor Domnului catre Augustin, Intii Fauritorul de Dumnezeu, este aceea ca, El va fi prezent in tot ceea ce va realiza si oriunde se va duce Omul. Si tocmai de cararile intortocheate ale devenirii si ale mintii omenesti ii este frica lui Dumnezeu! Ii e teama ca il vom cauta in locuri in care nu vrea sa ajunga si ca il vom ignora pina si in bisericile Sale cele mai falnice!\r\n     Dintr-un motiv obscur, Isidor il nelinistea pe Abate. Perspectiva pe care o aruncase asupra Revelatiilor era, fireste, falsa, naiva, dar nu se potrivea nicicum cu ceea ce ii spusesera fratii arhivari despre Isidor. Ceva era strimb...\r\n     - Vorbele tale suna ca o erezie crestina! Fii atent, baiete! Nu m-am omorit niciodata dupa religia asta, il preveni Staretul pe novice.\r\n     Tinarul puse capul in pamint si se cufunda in mutenie. Staretului ii displacu tacerea care se lasase peste micul lor grup. Ii accentua o stare de neliniste care izvora de undeva de dincolo de constienta si de logica.\r\n     - Spune-mi ce stii despre economia Abatiei.\r\n     Isidor incepu sa vorbeasca repede:\r\n     - In ultimii zece ani, rata de rentabilitate a programelor noastre s-a imbunatatit cu 62 la suta, mai ales pe seama scaderii cu doua generatii a timpului minim de constructie a templelor. Pe Marish V si Dilin III au aparut temple chiar dupa trei generatii de la Insamintare, ceea ce a redus...\r\n     Novicele stia carte. Dar consacrarea avea legile ei clare, pe care nici macar Staretul nu putea sa le eludeze. Isidor trebuia sa recite. Abatele se cufunda din nou in gindurile sale. Citise undeva ca primii agricultori insamintau aruncind pe ogor seminte in niste brazde scrijelite cu pluguri primitive. De fiecare data cind se gindea la similitudinea dintre munca fratilor sai si aceea a primilor agricultori, Abatele era cuprins de un respect vecin cu duiosia.\r\n     E singurul timp in care sint sigur de esenta mea profund religioasa. Si noi insamintam cu virusii nostri. Prin voia Domnului, eu arunc semintele... Si dintr-o gloata replicata sinistru in maternitatile familiilor, clonele se transforma in oameni, ridicind dintre ei Mintuitorul. Mereu acelasi, mereu jertfit, mereu fauritor al Caii spre Dumnezeu. Si devin credinciosi, religiosi, muncitori, multumiti de statutul lor, fara ambitie, fara veleitarism, fara talente... In lipsa Lor, Regatul Celor o Mie de Stele ar muri de foame. Iar clonele il slavesc pe Dumnezeul nostru, care intuneca vederea Familiilor, nedeslusindu-le Lucrarea Lui: numai un om poate fi religios! Restul e Armaghedon.\r\n     Novicele terminase de citava vreme si astepta cuviincios ca Staretul sa-si reia intrebarile:\r\n     - O sa fii un frate bun, Isidor. Te vom primi printre noi si fi-vei ostean al Sfintului Augustin, Intii Facatorul de Dumnezeu.\r\n     Baiatul strinse tare din pumni si zimbi larg, lasind pentru o clipa impresia ca va incepe sa topaie de bucurie precum un copil.\r\n     - Iti voi pune insa o ultima intrebare, deja ca intre frati. Daca vrei, poti sa nu-mi raspunzi... De ce crezi ca exista Abatia? intreba Starostele aratind spre zidurile intunecate ale Fortaretei.\r\n     Baiatul cazu pe ginduri, dadu sa plece dar apoi isi lua inima in dinti.\r\n     Ciudat, ciudat...\r\n     - Misiunea noastra este sa-L sadim pe Dumnezeu acolo unde nu exista, sa luminam mintea celor care nu-L vor fi aflat inca...\r\n     Staretul clatina din cap, ignorindu-si cu greu senzatia clara a unui pericol iminent.\r\n     - Nu. nu, nu... Noi nu facem decit sa ii gasim pe fii lui Dumnezeu, care se jertfesc in numele Lui, care pun iertarea si umilinta mai presus de fiinta lor. Asta facem noi aici, baiete. Cautam intr-un sac de seminte pentru a o alege pe aceea din care va creste floarea cea mai frumoasa, aceea care va arata intregii gradini splendoarea unica a Dumnezeirii. Asta facem! Nu mi-ai raspuns la intrebare!\r\n     Zimbind larg tinarul rosti cu credinta:\r\n     - Pentru ca Dumnezeu nu se teme decit de propria-i nemarginire!\r\n     - Pe sandalele Sfintului Augustin, ce vrei sa spui?\r\n     - Fara Abatie, religiile vechii Terre...\r\n     Religiile?!\r\n     -... ar fi doar amintire. Fumul de tamiie al templelor care au ridicat Omul din animal s-a risipit in nimicul negru dintre cele o mie de stele! Cit de nemarginit ar fi Omul fara Dumnezeul sau?\r\n     ... si cit de speriat ar fi Domnul nostru atunci.\r\n     - Folosesti vorbe cu mult dincolo de puterea ta de intelegere.\r\n     - Domnul e deasupra mea si lumina Lui ma calauzeste in tot cea ce spun.\r\n     - Nu trebuie sa te joci cu blasfemia! Domnul nostru nu e nicicum hainul zeu crestin. El e in orice piatra pe care o atingem, e in taisul sabiei cu care ii macelarim pe necredinciosi, e in navele cu care strabatem nimicul negru spre planetele de Insamintat. Dumnezeu nu e numai in oameni ci in tot ceea ce ating ei cu miinile sau cu mintea!\r\n     Un muschi de pe fata novicelui tresari necontrolat:\r\n     Nu putea fi! Nu vor indrazni!\r\n     - Sa fie oare Dumnezeu si in zidurile Abatiei atunci?\r\n     Violenta?\r\n     - Cu siguranta!\r\n     Tinarul scoase un hohot de ris gutural si porni o miscare fluida, de luptator incercat. Se feri de halebardele celor doi frati-ostasi din spate, implintind mici sageti de lemn in grumazurile celor doi din fata. Calugarii se prabusira inainte de a realiza ce li se intimpla. Isidor se rasuci cu iuteala la atacatori si rupse dintr-o lovitura ambele picioare ale unuia din ei, numai pentru a se folosi de greutatea naturala a omului ca sa ajunga cu degetele sale ascutite a niste cangi, pe sub barbie, direct in creierul nefericitului frate-ostas. Ultimul dintre calugari incerca sa-si foloseasca transmitatorul subvocal dar dispozitivul ramase brusc fara curentul generat de bateria din spatele urechii... Capul fusese despartit de trup cu o sabie subtire cit o pelicula de film pe care Isidor o smulsese de sub pielea bratului sting, care atirna acum sinistru, insingerata, ca un afis vechi, cojit de pe zid.\r\n     Abatele inghiti in sec si isi ridica ochii spre cer, asteptind lovitura. Insa, in loc de suferinta, Staretul primi in obraz vorbele batjocoritoare ale lui Isidor\r\n     - Popo, daca Dumnezeu e pitit intre zidurile manastirii, ar fi trebuit sa-ti fi spus demult cine ti-a calcat pragul...\r\n     Isidor isi scoase de pe fata o masca...\r\n     Dumnezeule, au domesticit un simbiont lipunian!\r\n     ... si se prezenta scurt:\r\n     - Capitan de rang inalt Rimio de Vassur, corpul intii al armatei maiestatii sale. Sint aici din ordinul...\r\n     - Stii prea bine ca ne vor omori pe amindoi daca vor banui ca reprezentam chiar si cea mai mica amenintare la adresa Abatiei. Sintem dincolo de ziduri si...\r\n     Rimio schita un gest de nepasare:\r\n     - Asta o sa vedem.\r\n     Scoase un strigat inuman de ascutit si, din ascunzatori subpamintene, navalira spre ei zece soldati regali, echipati de lupta. Se apucara cu frenezie sa monteze ceva ce semana izbitor cu un laser de mare putere...\r\n     - Sfintul Tratat din Eridani garanteaza inviolabilitatea Cetatii noastre. Toate familiile din Adunarea Planetelor ne vor sari in ajutor...\r\n     Cu un gest scurt, militarul comanda foc. Chiuind de bucurie, soldatii lui pulverizara zidul Fortaretei.\r\n     Staretul mai gasi doar forta de a ridica ochii spre cer:\r\n     Armatele tale Doamne nasc Apocalipsa. Imbraca-voi armura Armaghedonului si strivi-voi pe necredinciosi! Nu-Ti fie teama!\r\n     \r\n     2.\r\n\r\n     Ultimele doua zile nu fusesera prea fericite pentru Abate. Era convins ca secretul atit de bine pazit al felului in care replicau religiile se va afla cum si se va raspindi in toate colturile regatului. Chiar daca ei aveau sa scape cu viata, Abatia era pierduta.\r\n     Sperase totusi ca Vassur e un fel de ariergarda. Se asteptase la zeci de savanti barbosi si cu ochelari de moda veche, care sa scotoceasca prin cele mai intunecate tainite ale Cetatii. Gindise ca, in fata miilor de tomuri in care erau descrise procedeele de clonare si de selectare a materialului genetic, spionii regelui se vor sparge in mici formatiuni care vor sfirsi prin a se uri reciproc si, in final, nu vor mai comunica si nu vor afla niciodata esenta divina a sfintei Insamintari.\r\n     Nu fusese insa asa. Dupa ce cei zece soldati ai lui Vassur intrasera in Abatie, si executasera pe loc pe oricine purta o arma, in interiorul Cetatii, pe terenul de linga piersicul inflorit aterizase o naveta simpla, din care coborise un grup restrins de oameni. Toti aveau un mers militaros in afara celui care statea in mijlocul lor. Era un barbat mic de statura, care pasea hotarit dar degajat, ca un om obisnuit sa comande. Miscarile ii erau amplificate de faldurile mantiei largi, care flutura pe linga uniforma regala, strinsa pe corp. In cele doua zile scurse de la inceputul agresiunii, seful navalitorilor nu dorise sa ii vorbeasca. Acum insa Abatele il astepta in sala de consiliu, pentru ceea ce parea a fi o intilnire decisiva. Si, dintr-un motiv pe care nu si-l putea explica, era linistit fiindca stia ca numai un singur motiv ar fi putut cautiona o asemenea purtare brutala din partea Curtii Regale. Mascarada trebuia insa jucata pina la capat.\r\n     - Slavit fie Dumnezeu tau, Abate! Sint Diribal de Carant, consilier regal sef pe probleme stiintifice...\r\n     - Stiu cine esti, ii taie Staretul cuvintele. Si mai stiu ca nu faci decit sa incalci vreo trei tratate si, daca asta mai inseamna ceva pentru uniforma regala, legile curtoaziei si ale politetii. Eu si fratii mei...\r\n     - Sinteti de-a dreptul depasiti de situatie! Tratatele pe care le invoci contin, stii bine, un amendament general...\r\n     Clipa Armaghedonului sticleste dintre stele...\r\n     Abatele clipi de citeva ori, calculind cu frenezie implicatiile insinuarii lui Diribal. Incerca sa traga de timp desi stia ca e zadarnic.\r\n     - Nu am auzit ca omul sa fi avut vreun contact cu vreo civilizatie extraterestra, cu vreun semen inteligent.\r\n     Diribal se ridica si incepu sa se plimbe repede in jurul mesei din sala de consiliu a Abatiei.\r\n     - Nu e vina nimanui ca v-ati izolat aici, la marginea civilizatiei. La curte se stie de mai bine de doua saptamini ca am descoperit o rasa inteligenta, undeva linga Noua Andromeda. Sint fiinte inteligente, cu toate ca dezvoltarea lor tehnologica e mult inferioara...\r\n     Abatele se ridica de pe scaun si izbi cu pumnii in masa.\r\n     - Asta tot nu justifica agresiunea regala! Sintem un asezamint sfint, un templu al creatiei. Fara noi lumea asa cum o cunoastem nu ar mai avea nici un sens. Deja pierderile pe care ni le-ati pricinuit pun in pericol...\r\n     Dupa un schimb scurt de priviri cu Diribal, Vassur zvicni fulgerator din bratul sting. O sageata din lemn de succu se infipse in mina abatelui tintuind-o de masa. Imediat, calugarul simti in rana arsura crunta a semintelor care ii patrundeau in carne.\r\n     Diribal rinji amuzat:\r\n     - Nu te ingrijora! Nu sint seminte ci doar unul dintre virusii prietenosi, de casa, ai regelui nostru. O sa doara fix o suta de ore. Obisnuim sa pedepsim cu el nevestele infidele. Li-l administram intr-un gingas act sexual... Nu gasesti, popo, ca si tu ne esti cumva infidel?\r\n     Smulgindu-si mina ranita, Staretul se aseza pe scaun. Folosirea fara rezerve a violentei asupra lui era semnul spaimei de inceput de lume care cuprinsese curtea regala.\r\n     Pericolul nu e iminent, dar e clar ca se simt amenintati. Iar natura acestei provocari e atit de straina de orice altceva au intilnit vreodata incit risca orice. Stiu ca daca ne omoara, Lumile Agricole nu vor mai insemna nimic. Si totusi risca! Sfinte Augustine, pastrator al tainelor, fie-ti mila de noi si calauzeste-ne!\r\n     - Sa revenim, popo, susura calm Diribal. Acum doua saptamini, o expeditie pe care regele o credea deja pierduta s-a intors cu citeva imagini absolut tulburatoare: planeta Z. I-am spus asa pentru ca pur si simplu ne e teama sa-i dam vreun nume. E locuita de fiinte cu metabolism carbonic, in mare masura apropiat de al nostru. Ne intrec insa cam de trei ori ca talie, rezista la temperaturi cuprinse intre -100 si +150 de grade Celsius, nu respira dar pot zbura si, mai ales sint extrem de rezistente la radiatii.\r\n     - Slavit fie Domnul si creatiile lui! rosti Abatele impreunindu-si miinile cu o grimasa de durere.\r\n     - Cam asa am zis si noi... Ne gindeam chiar ca am facut rost de lucratori perfecti pentru cel putin o duzina de noi feluri de Lumi Agricole. Zetii pot trai practic oriunde, sint mult mai puternici, mult mai harnici decit clonele standard, nu maninca aproape nimic... Si va veneam si voua de hac, eliminind dependenta lumii civilizate de aroganta cu care o tratati de generatii! Apoi am descoperit ceva ciudat...Se pare ca zetii sint conditionati din nastere intru imbratisarea unei religii.\r\n     Minunat, Doamne! Caile tale ma umplu de smerenie!\r\n     - Citesc pe fata ta, popo, o satisfactie inexplicabila. Dumnezeu si sfintul ala al vostru...\r\n     - Augustin...\r\n     - Ma rog! Nu v-au invatat ei oare caile pe care le poate lua religia? Ati uitat exemplul istoric al Sfintei sirguinte a invataturilor materiale, cartea de capatii a primilor exploratori? Preotii scientisti proslaveau creatia pura...\r\n     Din spatele stapinului sau, Vassur izbucni in ris si completa ironic.\r\n     - Asta cind nu macelareau crestini si mahomedani...\r\n     - Cam asta este si credinta zetilor. Cu o regularitate infricosatoare, fiecare al saizeci si treilea copil e un fel de Mesia, un ales al sortii. Sau poate al sortilor? Inca de mic el stie ca la virsta maturitatii se va transforma intr-un creier imens, invaluit de un cocon roz, o masa cornoasa care incepe sa-i creasca inca din primii ani de viata. Planeta Z e plina de astfel de coconi, din care zetii care s-au jertfit gindesc si comunica rezultatele celor de-afara. Iar prostimea ii venereaza ca pe niste invatatori. Nu e greu de imaginat pentru tine, popo, cui se inchina ei...\r\n     - Primului cocon?\r\n     - Esti periculos de destept, popo! zimbi Diribal. Ce sa mai vorbim, in esenta, zetii proslavesc cunoasterea pura. Primele proiectii arata ca ne vor depasi din punct de vedere stiintific in numai trei generatii, din punct de vedere tehnologic in maximum cinci, iar economic in cel mult zece generatii. Pur si simplu, blegul ala de Dumnezeu al tau i-a facut mai destepti si mai rezistenti decit noi!\r\n     Dumnezeul meu nu greseste niciodata! Stinca stie asta.\r\n     Abatele incerca sa mediteze la implicatiile celor dezvaluite de slugile regelui. Daca nu era mintit, lucrurile erau cu adevarat extrem de grave. Agresiunea asupra Abatiei putea fi considerata o masura disperata, justificata de amenintarea stingerii umanitatii, iar fratii sai ucisi cu singe rece erau doar pierderi de razboi. Tratatele pe care se sprijinea Regatul aveau fara exceptie un codicil care mentiona trei situatii in care regele capata puteri absolute. Contactul cu o civilizatie neumana era una din ele.\r\n     - Si ce-am putea face noi? Si mai ales acum cind ne-ati distrus mai toate laboratoarele si consistoriile?\r\n     Diribal se apropie atit de mult incit Staretul putu sa-i simta respiratia grea.\r\n     - Va trebui, popo, sa ne inveti cum sa fabricam o secta pentru lumea asta. Si fiindca niciodata nu am avut incredere in metodele voastre, o voi face personal, cu oamenii mei. Voi veti fi... aaa... doar consultanti.\r\n     - Dar vom face toata treaba...\r\n     - Vezi ca ne intelegem!? O sa incepem prin a ne explica exact cum obtineti zeama aia cu care infectati...\r\n     - E pacat sa vorbesti asa despre sfinta taina a Insamintarii...\r\n     Diribal zimbi strimb:\r\n     - Iti place sa te auzi, popo, dar mie mi se pare ca nici macar tu nu crezi in ceea ce spui. Intii o sa-mi arati cum functioneaza tehnica in cazul Lumilor Agricole si apoi o sa incercam sa punem la cale o religie pentru zeti.\r\n     Abatele incerca sa protesteze:\r\n     - Dar nu stim nimic despre ei! Cum am putea sa gasim...\r\n     - Am sa-ti explic pe intelesul tau, popo. E sadit in genomul oricarui barbat sa caute perpetuu sa se acupleze cu cit mai multe femei. Esti de acord?\r\n     - Da, dar asta...\r\n     - Cu toate acestea, continua calm Diribal, exista si monahi, asa ca voi. Ati facut legamint de castitate pentru ca sinteti convinsi ca Dumnezeu v-o cere. O asemenea anomalie motivata religios asteapta cele o mie de stele de la voi: sa inventati un Dumnezeu pe care zetii sa-l urmeze in bezna unor dogme care sa dureze macar o suta de generatii.\r\n     - Bine, dar de ce nu pirjoliti pur si simplu planeta Anticristului. Transformati-o in desert radioactiv, ca aici pe Terra...\r\n     Diribal isi ciocani scurt zarul tatuat in palma dreapta, blazonul casei regale.\r\n     - Ne place sa jucam, popo! Chiar si un om desprins de cele lumesti isi poate da extrem de lesne seama cit de pretioasa ar fi o populatie de zeti usor controlabila... Ce muncitori minunati ar fi! Si numai pentru noi!\r\n     Fu rindul Abatelui sa zimbeasca:\r\n     - Nu va pot ajuta nicicum!\r\n     - Inseamna ca ai sa mori incercind, popo! rise scurt Vassur.\r\n     Grauntele de nisip nu stie ca in caderea lui cerne vremurile. La rindul ei, clepsidra nu e constienta decit de propria goliciune. Cea care descatuseaza cu adevarat timpul este mina care intoarce clepsidra. Asa inteleg eu divinitatea.\r\n     \r\n     3.\r\n\r\n     Satul clonelor era construit pe un gorgan care acoperea intreaga aripa de vest a Abatiei. Privit de departe, parea un fel de meterez din care razbateau cind si cind zgomote enigmatice.\r\n     - Clone, repeta a treia oara Diribal, abia potolindu-si risul. Vrei sa spui ca Dumnezeii pe care ii creezi...\r\n     - Mesia... Dumnezeu e numai unul...\r\n     - Ma rog, cum spui... Sint cu totii clone?\r\n     Abatele isi ingusta ochii. Ceremonia de consacrare a fiecarui Staret, avea ca miez exact ceea ce trebuia el sa faca acum. Din motive care abia acum i se desluseau, fusese pus, spre marea lui surpriza, sa fie ghid al unui ipotetic turist prin Abatie. "Va trebui sa-mi spui destule ca sa fie interesant dar mai putine decit imi trebuie ca sa inteleg intregul", enuntase predecesorul sau incercarea. Reusise cu brio, minunindu-se insa atunci de inutilitatea unui asemenea test. Incepu sa vorbeasca cu voce joasa, egala:\r\n     - Noi credem in invataturile sfintului Augustin.\r\n     - Da, stiu, vagabondul care a intemeiat Abatia.\r\n     - Nu, nu, de data asta ma refeream la un crestin, Augustin. Nu sintem insa nicicum crestini, se grabi sa precizeze Abatele la vederea dezaprobarii din ochii lui Vassur.\r\n     - Te joci cu focul, popo!?\r\n     - Dupa Augustin, care a trait acum mai bine de cinci mii de ani, crestinismul nu a mai cunoscut decit o singura figura importanta, Sf. Toma. El a trait...\r\n     - Lasa prostiile! Zi-mi de clone!\r\n     - Sfintul Augustin spunea ca Dumnezeu a creat lumea intr-o determinare absoluta. Inca de cind a inceput sa cladeasca, stia exact citi sfinti va avea, ba chiar ii si alesese pentru Lucrarea Lui. De aceea, intiiul nostru Staret, care se numea prin minunata Lucrare divina tot Augustin, a inteles ca putem sa ne imaginam oricind orice religie, dar sfintii nu-i vom putea multiplica niciodata pe cai dumnezeiesti. Cu atit mai putin pe copii lui Dumnezeu, profetii care duc invatatura Lui pe Lumile Agricole. Geniul Intii Facatorului nostru de Dumnezeu a constat tocmai in intelegerea misiunii sale si a cuvintului Domnului, relevat lui in cimpia Pannoniei... Dumnezeu nu mai are sfinti pentru noi, dar ii putem oricind replica pe cei vechi. Dar cum moastele nici unui profet nu contineau vreo urma de material genetic, aveam o mare problema...\r\n     - N-am inteles nimic, se plinse Vassur. Si mi-am stricat capul sase ani in gaoaza asta de Abatie!\r\n     Staretul il fulgera cu privirea:\r\n     - Nu intelegi pentru ca nu e nimic de inteles inca. Cea mai insemnata revelatie a Fratelui Augustin a fost aceea ca omenirea a epuizat sfintii si profetii pe linia frusta a existentei liniare. Dar oare o populatie de clone crescute departe de civilizatia moderna, in conditii identice acelora din Asia Mica de la inceputul erei crestine, nu va sfirsi ea oare prin a-si produce un nou sfint, un nou Mesia, daca ii este dat sa retraiasca experientele de la inceputul religiilor? Nu ne mai trebuiau moastele sfintilor adevarati...\r\n     Diribal isi freca barbia cu un gest nervos, In mintea lui incepuse sa biziie de citva timp un sentiment ciudat, dar pe care il putea defini. Avea senzatia ca este pacalit cu nerusinare, dus de nas intr-o maniera insidioasa, subtila si extrem de periculoasa.\r\n     - Pe parul blond al maica-mii! O erezie perfecta! isi plesni miinile Diribal. Stiam eu ca nu sinteti decit niste poponari fatarnici!\r\n     - Hulesti din ignoranta, spuse calm Abatele. Dumnezeu a prevazut inca inainte de facerea lumii toate actiunile noastre. Nimic din ceea ce facem nu iese din ordinea Lui. De aceea ne si deosebim de crestini. Noi credem in razbunare, in moarte ca invatatura, acceptam relatiile homosexuale, credem ca daca ai reusit sa furi un lucru atunci tot universul a convers spre solutia unica de a-ti oferi acel lucru... Atita doar ca unii sintem sortiti a impartasi adevarata cunoastere, sa devenim sfinti, iar altii, cei mai multi dintre noi, purtam sadit in fiinta noastra pacatul originar si nu vom ajunge niciodata sa-i stea Domnului alaturi... E dumnezeiesc de simplu.\r\n     Vassur interveni surescitat in conversatie, in ciuda privirii ucigatoare pe care i-o arunca superiorul lui:\r\n     - Vrei sa spui ca voi, pur si simplu, adunati aici oameni care au mai existat si ii puneti sa traiasca impreuna sperind ca societatea asta o sa... o sa... secrete la un moment dat un Mesia?\r\n     - Ai fi fost un frate bun, Isidor, incuviinta Abatele. Si folosim clone pentru simplul fapt ca originalele, oamenii care au purtat genele celor din sat, nu mai aveau cum sa degaje un sfint, fiindca, sfintii lor s-au terminat. S-au epuizat precum apa dintr-o plosca.\r\n     Abatele a continuat o vreme fara sa fie intrerupt. Diribal si Vassur ascultau fascinati detaliile facerii de Dumnezeu.\r\n     - Imediat ce se manifesta si sintem siguri de vocatia lui, Mesia este prins intr-un paienjenis de situatii si de experiente care il modeleaza dupa trebuintele Lumii Agricole pe care o va insaminta. In general, el corespunde cumva cu modelul crestin: incurajeaza supunerea si munca, condamna crima, incestul si hotia, are grija de semenii sai si mai ales este gata sa-si dea viata intru mintuirea lor de un pacat pe care tot el il defineste. Imediat ce s-a cristalizat personalitatea mesianica, ii manipulam existenta in asa fel incit el intra de bunavoie in rezonatoarele noastre, unde structura sa mentala este replicata fidel intr-o matrita organica de ordin cinci. Ea este implantata apoi unui virus purtator clasic, de tipul celor folosite in Capitala pentru vaccinurile aeriene anuale, caruia i s-a exacerbat insa selectivitatea la maximum, asa incit va infecta numai un individ, cel mai apropiat ca structura mentala de aceea a Mintuitorului pregatit de noi. De obicei, transformarea dureaza intre trei si cinci ani. Dupa aceea, clona noastra si cu cea infectata ajung sa gindeasca perfect identic. Simplu, elegant... E lucrarea lui Dumnezeu.\r\n     - E eretic, e total impotriva legilor regatului si e profund imoral. Poate ca n-oi fi fiind eu o persoana prea religioasa dar nu cred ca Dumnezeul crestin ar fi aprobat clonarea si manipularea religiei sale...\r\n     - Noi nu sintem crestini! Apreciem doar ca valabile concluziile sfintului Augustin... si partial pe acelea ale sfintululi Toma. Dar in rest... Singura noastra credinta este aceea ca Dumnezeu se teme doar de propria nemarginire, care va ramine vesnic neexplorata. Niciodata nu se vor fi nascut toti copiii Lui, nici chiar atunci cind vestea despre Lucrarea Lui de aici se va fi raspindit. Pur si simplu, infinitatea lui Dumnezeu nu va putea fi niciodata egalata de noi...\r\n     - Sinteti fatarnici, lacomi...\r\n     - Nu mai mult decit familiile care creeaza clonele din Lumile Agricole, combinind puterea politica si pe aceea religioasa in cea mai crunta forma de sclavie cunoscuta vreodata.\r\n     - Familiile nu au pretentia de a fi morale. Ele cauta doar sa traiasca din ce in ce mai bine. Dogma asta poate fi la fel de valabila ca oricare alta, zimbi Diribal.\r\n     - Si atunci de ce crezi ca noi avem obligatia de a fi morali? Iti repet, in ciuda impresiei generale, nu sintem crestini.\r\n     - Dar religiile pe care le fabricati? intreba Vassur.\r\n     - Poate ca se apropie de proto-crestinism. Dar cum altfel ar fi putut fi religia Lumilor Agricole decit una pseudo-crestina? Crezi ca scormonim de buna voie in scursorile istoriei? Nu trebuie sa fii cine stie ce argat spilcuit de la Curtea regala ca sa fii liber cugetator, in pas cu vremurile! Ajunge sa te uiti in jur, spuse Abatele facind un gest larg, de parca ar fi dorit sa cuprinda intreaga Terra. Desertul care ne inconjoara este expresia a doua milenii si ceva in care crestinismul si-a uniformizat geniile si a otravit diversitatea umana si, mai ales, a subordonat toate actiunile sale unei morale dogmatice, absolute, creind un surplus scirbos de bunatate si o avalansa de vieti fara vreo valoare intrinseca. Si, chiar daca am fi oarecum crestini, avem o scuza! Cind lucrezi cu asemenea scirbavnice materiale, uneori, e greu sa te feresti de o usoara contaminare.\r\n     Ca si cum s-ar fi dedublat brusc, Diribal dadu sa intrebe ceva, dar in drumul ei spre vorbire, ideea se topi si se ascunse undeva in subconstient.\r\n     Atita vreme cit nu a inteles cum curge timpul, Ahile nu a prins niciodata broasca, fiindca se impiedica perpetuu de jumatatea distantei dintre ei... Crestinii stiu asta. Ei au cautat broasca aproape un mileniu, nestiind ca o tin in buzunar. Iata de ce, in locul vostru nu as dispretui experienta crestina si lectiile ei despre viltorile Timpului. Fiindca intiiul invatamint al crestinismului este furia distrugatoare cu care a inceput atunci Omul sa recupereze timpul pierdut.\r\n     \r\n     4.\r\n\r\n     - Nu stiu la ce v-ati asteptat? La o retorta din care sa distilam Dumnezei? Metodele noastre sint cele care sint... Si nu cred ca e vina noastra ca nu ne-am gindit ca la un moment dat va trebui sa cream religii si pentru alte fiinte decit pentru clonele Lumilor Agricole. Stim foarte bine ceea ce facem... Insamintam o populatie de clone proaspete, apare un Mintuitor, inspira o religie care proslaveste munca si condamna savurarea roadelor ei. Asta facem de patruzeci si doua de generatii si nimeni nu s-a plins. Il recream mereu si mereu pe Mesia. Dar chiar si asa am avut nevoie de cinci generatii pentru a pune la punct prima Insamintare! Dumnezeu stie cit ar putea dura pina sa reusim cu zetii!\r\n     Staretul incerca aproape o saptamina sa le explice invadatorilor ca ii este imposibil sa fabrice o religie pentru o rasa despre care nu stia nimic. De citeva ori fusese extrem de aproape de moarte si-si citise sfirsitul in ochii rosii ai lui Vassur.\r\n     - Trebuie sa fie o cale! exclama Diribal. Poate daca aducem unii aici si ii studiem... sa incercam... sa vedem...\r\n     Stateau in curtea interioara a Abatiei, linga piersicul firav care abia ce inflorise. Cu un frison usor, Abatele se ridica si isi strinse hainele lungi pe linga corp. Incepu sa se plimbe cu pasi mari, vorbind cu glas egal:\r\n     - Am putea, desigur, incerca sa modificam religia pe care zetii o au deja... Dar mi-e frica. A doua pogorire a lui Cristos e intotdeauna un eveniment de care ne ferim, o necunoscuta ancestrala.\r\n     Ce-ti face blazonul din palma, Diribal? Si noua ne place sa jucam. Stii totul acum si totusi nu stii nimic!\r\n     - O singura data am incercat sa modificam o religie... rezultatele au fost lamentabile si din planeta aceea s-a ales un desert mai cumplit chiar si decit vechea Terra.\r\n     - Quintrium? isi ridica ochii Diribal dintre hirtiile sale.\r\n     - Da. Fiindca nu ni se dadusera toate particularitatile lumii respective, am esuat lamentabil.\r\n     Diribal zimbi subtire:\r\n     - Unde iti e credinta, popo? Hotaraste-te: ori esti unealta infailibilului tau Dumnezeu si atunci esecul e din vrerea Lui, ori dimpotriva esti un necredincios fioros care nu se ridica niciodata...\r\n     Staretul se minie. Miinile sale noduroase se inclestara pe toiagul incovoiat de care nu se despartea niciodata.\r\n     - Daca barbarii tai de soldati iti dau dreptul sa imi batjocoresti Abatia, daca asasinul tau perfect ma poate tine la respect, asta nu inseamna ca stii si ce este credinta mea. Nu incerca niciodata puterea martiriului! Sa-ti spun eu unde e Dumnezeu? E acolo unde tu nu poti nici macar privi, e la capatul lantului nefericit de evenimente care a dus la nasterea ta. Acolo unde nu era nici Cuvintul, El exista pentru a declansa tot ceea ce este...\r\n     - Ho, popo! urla Diribal. Nu te-aprinde ca te tradezi. Furia ta arata clar ca am nimerit un punct sensibil! Lasa prostiile si vorbeste-mi de Quintrium!\r\n     Incercind sa se stapineasca, Staretul isi relua locul pe banca de piatra.\r\n     Alegem in fiecare clipa. Fara sa stim, fara sa vrem. Dar oare ce-am alege daca ne-am da seama ca avem dreptul de a alege? Poate ca timpul ar deveni stufos ca un papuris numai bun de ascunzis pentru pradatori care ne pindesc pasarile cintatoare.Ce-am alege sa fim atunci, pradator sau prada? Si, mai ales, cit de mult am fi dispusi sa platim ca sa ni se implineasca vrerea?\r\n     - Cronicile noastre povestesc totul cu amanuntime, spuse Abatele. Familia Census nu ne-a vorbit nici macar o clipa despre imensul complex de pesteri al planetei. Si poate ca acest lucru n-ar fi fost atit de important daca intreg labirintul acela n-ar fi avut decit o singura poarta de comunicare cu exteriorul. In interiorul unui vulcan! Un asemenea dualism la o populatie primitiva de clone nu are cum sa nu nasca legende si mituri secundare, perturbatoare... Din pacate, noi gindisem o religie care sa venereze soarele si munca si sa ameninte cu damnarea in iadul subpamintean. Ne mai trebuiau doar citiva ani pentru a izbindi, cind o clona a venit si a racnit in piata centrala a capitalei: "Am fost in Iad. E mult mai bine ca aici. Veniti cu mine si n-o sa va para rau!" Subteranele erau pline cu fel de fel de ciuperci comestibile, care cresteau aparent din niciunde. Unele reuseau sa induca si stari euforice, altele erau incredibil de hranitoare si gustoase si absolut toate erau afrodisiace. Iadul nostru n-ar fi trebuit sa arate asa. Pina si Mintuitorul planuit de noi a sfirsit prin a-si face culcus undeva in adinc... La insistentele familiei Census, am Insamintat a doua oara, desi multi dintre fratii nostri s-au opus. Al doilea Mintuitor a patruns in pesteri si, intr-un fel sau in altul ne-a scapat de sub control. Ca si cum viata sub pamint l-ar fi transformat in antiteza sa, a devenit un fel de fanatic militar. Ce s-a intimplat apoi stiti...\r\n     Vassur stia intr-adevar povestea. Unul dintre stramosii sai isi daduse viata incercind sa patrunda prin poarta Iadului de pe Quintrium, strapungind o aparare disperata de clone care luptau dincolo de sine, drogate cu o ciuperca verde...\r\n     - Vrei sa spui ca Familia Census a mai Insamintat o data?! exclama Diribal.\r\n     - Da. Desi lucrul asta ar fi trebuit sa ramina secret...\r\n     - Atunci isi merita soarta. In fond, dupa Quintrium n-au mai apucat niciodata sa cumpere o Lume Agricola. Mizeria lor actuala, dealtfel meritata, nu va rezolva insa problema zetilor.\r\n     Staretul tusi scurt, isi drese glasul si rosti cu o voce pierita:\r\n     - N-am adus din intimplare vorba despre Quintrium...\r\n     Diribal il privi cu gura cascata:\r\n     - Vrei sa sugerezi ca ar trebui sa ardem Z?\r\n     - Eu unul nu vad alta solutie.\r\n     - Esti nebun, popo! Religia ar trebui pur si simplu interzisa! Vrei sa spui ca Dumnezeul tau stia ca ii vom descoperi pe zeti si a aranjat lucrurile in asa fel incit sa ii nimicim? Singura specie inteligenta din Universul cunoscut? Esti dincolo de orice furie! Esti gretos! striga Vassur.\r\n     - Am fost trimisi aici tocmai pentru a ocoli varianta uciderii zetilor! rosti calm Diribal.\r\n     - Si pentru a ne smulge secretele, completa incet Abatele.\r\n     - Da si pentru asta, il sfida Diribal. E dreptul regelui nostru. Primul contact al omului cu o specie inteligenta e mult mai important decit misterul ieftin cu care iti inconjori clonele.\r\n     Am vrut odata sa creez o religie care sa preamareasca riscul. Cum ar fi oare un Dumnezeu cartofor si vicios? M-am razgindit fiindca preotii din templele Lui nu ar putea fi decit trisori. Spre deosebire de zeul lor, ei nu-si pot permite sa piarda niciodata. Si ce mai reprezinta riscul daca excludem din start varianta infringerii? Asemenea scrupule morale sint insa pentru vremuri de pace.\r\n     - Am putea pastra oricind citeva exemplare si ceva material genetic pentru clonare, spuse in treacat Staretul. Cu timpul, poate vom reusi sa gasim o solutie impreuna.\r\n     Diribal si Vassur il privira cu ochi stralucitori.\r\n     \r\n     5.\r\n\r\n     - Popo, de ce am impresia ca imi ascunzi ceva?\r\n     - Fiindca asa este, zimbi Abatele. Iti ascund supararea mea pentru ca mi-ai omorit o mare parte din frati, iti ascund disperarea si obida mea, iti ascund dezamagirea ca unul dintre cei mai promitatori novici pe care i-am vazut vreodata s-a dovedit a fi un spion asasin...\r\n     Vassur rise cu pofta:\r\n     - E si in asta o lectie, mosule. Poate ca nu iti cauti novicii acolo unde trebuie!\r\n     Staretul zimbi la rindul lui.\r\n     - O sa va intoarceti. O sa aveti nevoie de Abatie ca sa va duceti planurile la bun sfirsit, Nimeni, in tot regatul, nu se pricepe mai bine ca noi sa fabrice religii. Sinteti siguri ca nu doriti sa ne incredintati noua zetii?\r\n     Bratul lui Vassur zvicni scurt si se opri la un milimetru de beregata Abatelui:\r\n     - Singurul motiv pentru care te las in viata este faptul ca Regele nostru si-a exprimat speranta ca vei repune sandramaua asta pe picioare si ne vei putea ajuta daca dam gres. Dar nu inainte ca noi sa ne recunoastem neputinta. Si nu uita niciodata ca acum secretul Abatiei e in miinile noastre! Nu ne interereseaza sa va luam negotul cu Dumnezei. E o treaba murdara, sub demnitatea curtii regale. Dar sa nu va imaginati ca veti mai putea sfida vreodata pe regele meu asa cum ati facut-o pina acum. Ne veti asculta orbeste! De asta mai esti inca in viata, Tu si fratii tai fatalai!\r\n     - Veti avea nevoie de stiinta noastra. Va e predestinat, comenta Abatele, deloc impresionat de violenta celuilalt.\r\n     - Sa-i zicem polita de asigurare, domnilor, spuse Diribal. Traiesti ca sa ne ajuti. Si cel mai bun lucru care ti s-ar putea intimpla este ca noi sa esuam si sa avem nevoie de tine. Roaga-te la Dumnezeul tau sa fi planuit lucrurile in asa fel inca de la inceputul Timpului.\r\n     - Folosesti vorbe mari, Diribal. Timp, Dumnezeu...\r\n     - Iar tu te joci cu vorbele mari, popo!\r\n     Cu un gest scurt, consilierul regal inchise usa navetei care decola cu un fisiit puternic. Nava se pierdu repede printre norii verzi care pluteau vesnic deasupra Abatiei. Staretul se aseza pe o banca de piatra de linga piersic. Privi spre zidul ciobit al Fortaretei. Legenda caracterului inexpugnabil al cetatii calugarilor terrani se spulberase... Desi nu aveau cum sa fie inarmati cu lasere militare grele, barbarii vechii Terre nu vor prididi sa incerce sa-i imite pe soldatii regali.\r\n     "Secretul facerii tale de Dumnezei va fi pastrat, popo! Regele nu are nici un interes sa destabilizeze Lumile Agricole. Nu am pretentia ca am inteles exact cum si de ce ti se pare ca ii creezi pe Mintuitori din adunatura ta de clone, dar sint convins ca mijloacele voastre primitive nu vor putea nicicind sa ne puna in pericol. Un timp o sa va lasam in pace" il asigurase Diribal.\r\n     Secretul meu? Secretul Tau, Doamne. Lucrarea Ta cea mai tainica!\r\n     Adia un vint usor dinspre cimpia arsa a Pannoniei. Piersicul din curtea Abatiei inflorise doar cu o seara inainte, bucurindu-i pe fratii care inca ii mai jeleau pe cei sase sute dintre ei care fusesera ucisi de garzile regale. Lucrurile intrasera oarecum in normal. Abatele gindi ca era prima zi obisnuita in Fortareata din seara in care iesise sa-l consacre pe Isidor-Vassur.\r\n     O zi numai buna de stirnit Armaghedonul.\r\n     \r\n     6.\r\n\r\n     In singuratatea chiliei sale, Abatele isi aranja hainele intr-o boccea modesta. Barba imensa, care ii mascase pina acum citeva ore fata, disparuse iar obrajii pastrau inca amintirea sarutului pe care i-l daduse Mirial inainte de plecare, novicele cu care isi impartise noptile timp de mai bine de trei ani. Matura mobilele simple cu o ultima privire si inchise usa de lemn negru in urma lui.\r\n     Bocancii grei stirneau zvonuri sinistre pe culoarele pustii ale Abatiei. Ajuns in cel mai intunecos dintre beciuri, Staretul isi aseza palma stinga si ochiul drept in fata unor fante abia ghicite in zidaria veche. Cu un scrisnet de uscaciune, un bloc imens de piatra se dadu la o parte lasindu-l pe barbat inauntru si apoi culisa la loc. Cu gesturile unui om care stie ce face, Abatele lua din boccea o cutie ferecata in aur si argint. Din ea scoase citeva suvite de par si incepu sa plaseze cite un fir in fiecare Ou. Stia ca avea nevoie de exact 643 de fire de par.\r\n     Ouale erau de fapt mici sonde spatiale autonome. Ele fusesera proiectate si construite cu scopul de a permite, in timpul calatoriei spatiale, dezvoltarea in interiorul lor a unei clone umane mature, perfect formate. Desi timp de patruzeci si cinci de generatii, lumea le spusese Oua, Abatele trebui sa recunoasca fata de sine insusi ca le gasea asemanatoare mai mult cu niste cosciuge. Si poate ca era chiar in firea lucrurilor sa fie asa. La urma-urmei Mintuitorul avea sa se ridice din morti, dintr-un cosciug.\r\n     Fiecare Ou avea inscris pe el destinatia: Delta IV, Pruzz, Nova Betelgeuse... Toate cele 643 de lumi agricole pe care le insamintase Abatia. Aproape o suta de miliarde de suflete. Ce tavalug urias aveau sa stirneasca aceste Oua, ce forta dumnezeiasca aveau pletele Sfintului Augustin! Abatele terminase de inchis Ouale si se aseza sa le mai contemple inca o data;\r\n     - Sase sute patruzeci si trei. Si totusi numai unul. Clona Sfintului Augustin. Oare cum o sa i se spuna?\r\n     Cartea Abatelui nu era extrem de clara in privinta celui care initiase proiectul pe care il denumea "Pletele Sfintului Augustin". Incepuse sa fie scrisa abia de al cincilea Abate, dupa supunerea primelor hoarde de barbari si ridicarea zidurilor imprejmuitoare. Se stia insa ca primul care isi daduse seama de importanta revelatiilor lui Augustin intemeietorul, fusese cel de-al saptelea Abate. El gasise intr-o veche biblioteca europeana scrierile unui sfint crestin pe care il chema Augustin si care avusese revelatia ultima a celor sase Timpuri care se incheiau tocmai cu a doua venire a lui Cristos. Armagedonul, Timpul incertitudinii! Dincolo de el era nemarginirea de care se teme Dumnezeu.\r\n     Dar nemarginire mai sint si Apocalipsa si Anticristul... Controversele teologice durasera doua generatii si facusera mai bine de o mie de victime. Razboiul fratricid purificator avusese insa doua consecinte divine: intii se pusesera la punct tehnicile Insamintarii ca arma impotriva calugarilor razvratiti si se scrisesera primele lucrari de ingineria mintuirii si apoi incoltise ideea crearii unui singure esente mesianice, care sa imbrace mereu alte forme.\r\n     Cind au construit satul de clone, calugarii au constatat ca distilarea unui mintuitor e destul de grea. S-au gindit atunci ca in sat ar trebui sa existe si clona unei constiinte cu adevarat religioase. Si cum singurul astfel de om era Sf. Augustin, au decis sa-l cloneze la infinit. Aveau la dispozitie pletele sale, pe care Intemeietorul si le taiase cu o zi inainte de a muri, insistind sa fie pastrate ca moaste, intr-o racla de aur. Parul purta cu el pretioasele informatii genetice ale celui ce fusese Sf. Augustin si calugarii au profitat: un fir de par, o noua clona pentru satul din care isi obtineau Mintuitorii. Si, fara nici o exceptie, clonele Sf Augustin se dovedisera Mintuitorul de care avusesera nevoie calugarii. Fara sa stie una de alta, toate Lumile Agricole se inchinau aceluiasi Mintuitor, Sfintului Augustin. Iar acum el avea sa revina!\r\n     Mereu altul si mereu acelasi! ce definitie sublima pentru un Mintuitor!\r\n     Niciodata vreo alta clona nu ajunsese inaintea Sf. Augustin sa treaca purgatoriul si sa Insaminteze vreo lume.\r\n     Fara ca cineva sa banuiasca, Abatia crease religii plecind de la esenta unui singur profet. Iar acum, Cristos avea sa vina din nou! Din pletele Sf Augustin, Ouale aveau sa-l nasca pe Augustin si sa-l duca din nou pe Lumile Agricole, acelasi fiu al lui Dumnezeu, intruparea reinvierii primului Mintuitor. Era a doua venire a lui Cristos pe Pamint. Dincolo de asta, urma nemarginirea, locul in care pina si lui Dumnezeu ii este greu sa priveasca.\r\n     Abatele se ridica si incepu sa se plimbe nervos. Ce altceva ar fi putut face din moment ce nemarginirea dumnezeiasca se revarsase peste oameni? Zetii erau altceva, erau haul de care ii era frica lui Dumnezeu. Extraterestrii erau Anticristul! Abatii stabilisera asta inca din urma cu treizeci si trei de generatii si pregatisera Ouale intru Lucrarea ultima a Domnului: a doua venire a lui Cristos. Insufletit de acest semn divin, omul trebuia sa hotarasca in locul lui Dumnezeu, trebuia sa infrunte nemarginirea de care ii era frica Domnului sau. Era timpul ca fiul sa-l apere pe tata. Clonele Lumilor Agricole, se vor ridica din nimicnicia propriei existente si ii vor zdrobi pe zeti. Sf Augustin, unit in cele 643 de intrupari ale sale va fi arhanghelul care va suna din goarna razbunarii.\r\n     - Dupa chipul si asemanarea Ta, Doamne! rosti simplu Abatele apasind o maneta. O imensa placa de beton culisa in curtea din centrul Abatiei, strivind piersicul inflorit. Ca la un semn, Ouale isi luara zborul spre a doua Insamintare. Erau atit de mici incit puteau patrunde lesne prin apararile orbitale extrem de rarefiate ale Lumilor Agricole. Si, pe fiecare planeta inrobita il vor aduce din nou pe Mesia, incheind un Timp, stirnind o nemarginire, pornind razboiul ultim.\r\n     Abia a doua zi, cind ajunsese la o distanta sigura, Abatele scoase un emitator micut. Pe locul Abatiei rasari un soare. Antica arma nucleara isi facu datoria, stergind orice urma a Fortaretei.\r\n     Urmau timpuri interesante. Crist si Anticrist, oameni si extraterestri se vor lupta pe viata si pe moarte. Armaghedon! Abatele privi cu incredere zarea verde. Nu era chiar neajutorat... In intestinele sale exista o bacterie capabila sa secrete toxine din cele mai mortale, la care el insa era imun, ochii sai adinci mai erau inca in stare sa hipnotizeze pina la isterie, era mai bogat decit insusi regele... Si, undeva in bocceaua de pe umar, mai avea, intr-o cirpa ordinara, si firele care mai ramasesera din pletele Sf. Augustin. La urma urmei, nu poti sti niciodata cind, in viata asta mizerabila, poti sa ai nevoie de un Mesia.\r\n     Nici un Dumnezeu de rezerva nu are cum strica.', 0, 15, '', 0, 0, '2007-03-15 11:58:22', '');
INSERT INTO `dsb_blog_posts` (`post_id`, `date_posted`, `fk_user_id`, `_user`, `fk_blog_id`, `is_public`, `title`, `post_content`, `allow_comments`, `status`, `post_url`, `stat_views`, `stat_comments`, `last_changed`, `reject_reason`) VALUES (10, '2007-03-11 16:33:16', 2, 'test', 1, 1, 'grgrgrg', 'hhrhrhrhrhr[u][quote]test[/quote][/u]', 0, 15, '', 0, 0, '2007-03-11 16:34:00', ''),
(11, '2007-03-16 14:12:19', 2, 'test', 0, 1, 'fifi', 'return to innocence', 1, 15, '', 0, 0, '2007-03-16 14:12:19', ''),
(12, '2007-03-16 14:14:19', 2, 'test', 1, 1, 'cucurigu', 'carcalacu', 1, 15, '', 0, 0, '2007-03-16 14:14:19', ''),
(13, '2007-03-16 14:16:16', 2, 'test', 1, 1, 'farfalau', 'bumbisor', 1, 15, '', 0, 0, '2007-03-16 14:16:16', ''),
(14, '2007-03-16 14:17:42', 2, 'test', 1, 1, 'morcovel', 'patrunjica', 1, 15, '', 0, 0, '2007-03-16 14:17:42', ''),
(15, '2007-03-16 14:19:12', 2, 'test', 1, 1, 'vasilica', 'morticel', 1, 15, '', 0, 0, '2007-03-16 14:19:12', ''),
(16, '2007-02-16 14:20:35', 2, 'test', 1, 1, 'post in februarie 2007', 'ciocardan', 1, 15, '', 0, 0, '2007-02-16 14:20:35', ''),
(17, '2007-04-16 13:21:21', 2, 'test', 1, 1, 'post in aprilie', 'hehe', 1, 15, '', 0, 0, '2007-04-16 13:21:21', ''),
(18, '2006-03-16 14:23:13', 2, 'test', 1, 1, 'post in 2006', 'circomac', 1, 15, '', 0, 0, '2006-03-16 14:23:13', ''),
(19, '2008-03-16 14:24:25', 2, 'test', 1, 1, 'post in 2008', 'offf', 1, 15, '', 0, 4, '2008-03-16 14:24:25', '');

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
(16, 2, 'Field value', 1),
(17, 2, 'Field value', 1),
(18, 2, 'Field value', 1),
(19, 2, 'Field value', 1),
(20, 2, 'Field value', 1),
(21, 2, 'Field value', 1),
(22, 2, 'Label for f4 field', 0),
(23, 2, 'Search label for f4 field', 0),
(24, 4, 'Help text for f4 field', 0),
(25, 2, 'Label for f5 field', 0),
(26, 2, 'Search label for f5 field', 0),
(27, 4, 'Help text for f5 field', 0);

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
(2, 2, 'skin_def', 'Looks'),
(3, 3, 'skin_def', 'About me'),
(4, 4, 'skin_def', ''),
(5, 5, 'skin_def', ''),
(6, 6, 'skin_def', 'Man'),
(7, 7, 'skin_def', 'Woman'),
(8, 8, 'skin_def', 'Gender'),
(9, 9, 'skin_def', 'Find'),
(10, 10, 'skin_def', ''),
(11, 11, 'skin_def', 'Men'),
(12, 12, 'skin_def', 'Women'),
(13, 13, 'skin_def', 'Looking for'),
(14, 14, 'skin_def', 'Looking for'),
(15, 15, 'skin_def', ''),
(16, 16, 'skin_def', 'Under 1m 50cm'),
(17, 17, 'skin_def', '1m 60cm'),
(18, 18, 'skin_def', '1m 70cm'),
(19, 19, 'skin_def', '1m 80cm'),
(20, 20, 'skin_def', '1m 90cm'),
(21, 21, 'skin_def', 'Over 2m'),
(22, 22, 'skin_def', 'Height'),
(23, 23, 'skin_def', 'Height'),
(24, 24, 'skin_def', ''),
(25, 25, 'skin_def', 'Birthdate'),
(26, 26, 'skin_def', 'Age'),
(27, 27, 'skin_def', '');

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
(2, 2, 2, 0x6669656c645f3436, '2', -3);

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

INSERT INTO `dsb_online` (`fk_user_id`, `last_activity`, `sess`) VALUES (0, '20070403144004', 0x3966303230373536646464363733316166366133353365326365643831616262),
(0, '20070318120332', 0x3538353565346462653034396534633263313163636638366332616335343739),
(0, '20070402182130', 0x6463316330303161626561386163386365653165636637373533643139393836),
(0, '20070402181259', 0x3365316437656461333236343434326461653562396330306561326264653561),
(0, '20070315223451', 0x3535383234663432323038323335366665386166323365623734613332353465),
(0, '20070314234459', 0x3031323366363166326566653232336436343063633066326632313338323236),
(0, '20070402181145', 0x3934386665373062383636386338383336326133643064656235333539643336),
(0, '20070321224708', 0x6433336562643866643037613765366266376438623263363636386431623431),
(0, '20070402181138', 0x6638363831373862653363613362326230646563396635333233653066623730),
(0, '20070402180129', 0x3435333161333364366165336237393433383934323336323838343632363734),
(0, '20070326164813', 0x3765623935386664313466323664366631613438346438376132393461666131),
(0, '20070402155640', 0x3131616536373861616631313461383664666261323735393339303064623532),
(0, '20070402155621', 0x3662643061363134363837636365643935336232306662623531316130613338),
(2, '20070402182126', 0x3439373764353631363761653361356236623736613262653932306539323864),
(0, '20070401233705', 0x3161343336663732363338396635656566633535373830326238353835666461),
(0, '20070402154849', 0x6461303231626536363363623163313737303264666239386162663163626630),
(0, '20070331130412', 0x6161373938363865356132336637373266653966346430346338613331356236),
(2, '20070404235019', 0x3639633062393865343737396266366261643331346436336263636430656331),
(0, '20070404172436', 0x6330623430316165393963666564303362663236356433643838303531383363),
(2, '20070405214231', 0x3737623935303639393363386164643138316436313834393661656636303638);

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
  KEY `date_posted` (`date_posted`),
  KEY `key1` (`fk_photo_id`,`status`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_photo_comments`
-- 

INSERT INTO `dsb_photo_comments` (`comment_id`, `fk_photo_id`, `fk_user_id`, `_user`, `comment`, `date_posted`, `last_changed`, `status`) VALUES (6, 79, 2, 'test', 'bleah\r\n[b]cahhhhhhhhhhh[/b]', '2007-03-22 21:00:15', '2007-03-22 21:00:15', 15);

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
(2, 2, 7);

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

INSERT INTO `dsb_profile_fields` (`pfield_id`, `fk_lk_id_label`, `html_type`, `searchable`, `search_type`, `for_basic`, `fk_lk_id_search`, `at_registration`, `reg_page`, `required`, `editable`, `visible`, `dbfield`, `fk_lk_id_help`, `fk_pcat_id`, `access_level`, `accepted_values`, `default_value`, `default_search`, `fn_on_change`, `order_num`) VALUES (1, 3, 2, 0, 1, 0, 4, 0, 1, 0, 1, 1, 0x6631, 5, 1, 0, '', '', '', '', 3),
(2, 8, 3, 1, 10, 1, 9, 1, 1, 1, 1, 1, 0x6632, 10, 1, 0, '|6|7|', '|0|', '|1|', '', 1),
(3, 13, 10, 1, 10, 1, 14, 1, 1, 1, 1, 1, 0x6633, 15, 1, 0, '|11|12|', '|1|', '|0|', '', 2),
(4, 22, 3, 1, 108, 1, 23, 0, 1, 0, 1, 1, 0x6634, 24, 2, 0, '|16|17|18|19|20|21|', '|2|', '|0|5|', '', 4),
(5, 25, 103, 1, 108, 1, 26, 1, 1, 0, 1, 1, 0x6635, 27, 1, 0, '|1930|1989|', '', '|18|75|', '', 5);

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
(6, 1, 2, 'test', 'You have received a flirt from test', 'Let''s rock and roll!', '2007-02-11 18:56:11', 2),
(7, 2, 2, 'test', 'xxx', 'xxx\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 13:04:24', 0),
(8, 2, 2, 'test', 'Re: Spam test message', '\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 14:22:35', 0),
(9, 2, 2, 'test', 'Re: Spam test message', '\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 14:31:01', 0),
(10, 2, 2, 'test', 'Re: Spam test message', '\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 14:31:25', 0),
(11, 2, 2, 'test', 'test sent you a flirt', 'Hello, baby!<img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-03-09 15:15:02', 1),
(12, 2, 2, 'test', 'test sent you a flirt', 'Hello, baby!<img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-03-09 15:15:17', 1),
(13, 2, 2, 'test', 'test sent you a flirt', 'Hello, baby!<img src="http://forum.datemill.com/Themes/default/images/off.gif" />', '2007-03-09 15:18:03', 1);

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

INSERT INTO `dsb_site_options3` (`config_id`, `config_option`, `config_value`, `config_diz`, `option_type`, `fk_module_code`, `per_user`) VALUES (1, 0x64626669656c645f696e646578, '6', 'The last index of the custom profile fields (field_xx)', 0, 0x636f7265, 0),
(2, 0x7573655f63617074636861, '1', 'Use the dynamic image text to keep spam bots out?', 9, 0x636f7265, 0),
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
(32, 0x6d696e5f73697a65, '', 'Minimum photo file size in bytes (use 0 for not limited).', 104, 0x636f72655f70686f746f, 0),
(33, 0x6d61785f73697a65, '', 'Maximum photo file size in bytes (use 0 for server default).', 104, 0x636f72655f70686f746f, 0),
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
(47, 0x726174655f6d795f70686f746f73, '1', 'Allow your photos to be rated?', 9, 0x6465665f757365725f7072656673, 1);

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

INSERT INTO `dsb_site_searches` (`search_md5`, `search_type`, `search`, `results`, `fk_user_id`, `date_posted`) VALUES ('98c401243f35f2828d504ef24ade5567', 1, 'a:9:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:8:"field_46";a:1:{i:0;s:1:"2";}s:8:"field_47";a:1:{i:0;s:1:"1";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";s:16:"field_50_country";s:3:"218";s:3:"f51";s:1:"1";s:3:"f52";s:1:"1";}', '2', 2, '20070317161512'),
('8be66aca9a1a9003f72585c258c916a5', 1, 'a:1:{s:11:"acclevel_id";i:17;}', '1,2,209', 2, '20070320174312'),
('116e715ab88dedbb4506f1fa4462bdc2', 1, 'a:7:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:8:"field_46";a:1:{i:0;s:1:"2";}s:8:"field_47";a:1:{i:0;s:1:"1";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";s:16:"field_50_country";s:3:"218";}', '2', 0, '20070321115208'),
('d027bb15d2e91eb9c221e6feeabc5ba4', 1, 'a:6:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:8:"field_46";a:1:{i:0;s:1:"2";}s:8:"field_47";a:1:{i:0;s:1:"1";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";}', '2,209', 0, '20070321115420'),
('0737b04b4eca702e7d0ab22b1526269c', 1, 'a:8:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:8:"field_46";a:1:{i:0;s:1:"2";}s:8:"field_47";a:1:{i:0;s:1:"1";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";s:3:"f51";s:1:"1";s:3:"f52";s:1:"1";}', '2', 2, '20070321120722'),
('5fec338e05a67bc77d31ab370dc02453', 1, 'a:6:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:8:"field_46";a:1:{i:0;s:1:"2";}s:8:"field_47";a:1:{i:0;s:1:"1";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"57";}', '2,209', 2, '20070321120751'),
('04a51b8634d9c552baeb60eb5109b481', 2, 'a:1:{s:4:"stat";s:1:"5";}', '', 0, '20070321125629'),
('40cd750bba9870f18aada2478b24840a', 1, 'a:0:{}', '1,2,209', 0, '20070321125631'),
('435529d231ba67d0c3917ecf7fa0dbd0', 1, 'a:2:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";}', '1,2,209', 2, '20070321130820'),
('a58953208bfc1e285a9feb0acee320fe', 1, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";}', '1,2,209', 2, '20070321131118'),
('a9ed76f7355b148cf3a870e8745aa764', 1, 'a:6:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";s:8:"field_47";a:1:{i:0;s:1:"2";}s:12:"field_48_min";s:2:"18";s:12:"field_48_max";s:2:"35";s:16:"field_50_country";s:3:"218";}', '', 2, '20070321204732'),
('', 1, 'a:2:{s:11:"acclevel_id";i:17;s:2:"st";s:3:"adv";}', '2,1,209', 2, '20070321225655'),
('8816b2c8b45df99a22880b00513d7867', 1, 'a:1:{s:5:"pstat";s:1:"5";}', '210,211,212,213', 0, '20070322112801'),
('7a05c5fb87b1158778adbc2a4867b009', 1, 'a:4:{s:11:"acclevel_id";i:16;s:2:"st";s:3:"net";s:10:"fk_user_id";s:1:"2";s:9:"fk_net_id";s:1:"2";}', '1', 2, '20070322205702'),
('0143a1c3121c434d9acefa1a1f1f772d', 1, 'a:4:{s:11:"acclevel_id";i:16;s:2:"st";s:3:"net";s:10:"fk_user_id";s:1:"2";s:9:"fk_net_id";s:1:"1";}', '1,2', 2, '20070322205716'),
('0449abf79c5a3f2f76d5f0ec03a8dd25', 1, 'a:2:{s:11:"acclevel_id";i:16;s:2:"st";s:6:"latest";}', '1,2,209,210,211,212,213', 0, '20070323154544'),
('512c7839f3bc946c119a95cc478fb513', 1, 'a:4:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}}', '', 0, '20070405205931'),
('e63727e333610e210864e40b8444bee8', 1, 'a:6:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";}', '', 2, '20070405213203'),
('58a4fac5fe804a4e068ea95e0ca1029d', 1, 'a:8:{s:11:"acclevel_id";i:16;s:2:"st";s:5:"basic";s:2:"f2";a:1:{i:0;s:1:"2";}s:2:"f3";a:1:{i:0;s:1:"1";}s:6:"f4_min";s:1:"1";s:6:"f4_max";s:1:"6";s:6:"f5_min";s:2:"18";s:6:"f5_max";s:2:"75";}', '', 2, '20070405214229');

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

INSERT INTO `dsb_user_accounts` (`user_id`, `user`, `pass`, `status`, `membership`, `email`, `skin`, `temp_pass`, `last_activity`) VALUES (1, 0x64616e, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@sco.ro', '', '', '20070402155030'),
(2, 0x74657374, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@rdsct.ro', 'basic', '', '20070405214231'),
(209, 0x7465737432, 0x3931383062346461336630633765383039373566616436383566376631333465, 15, 4, 'dan@rdsct.ro', '', '', '20070402155030'),
(210, 0x7465737433, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'test@sco.ro', '', '', '20070402164955'),
(211, 0x7465737434, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'test@sco.ro', '', '', '20070402155030'),
(212, 0x7465737435, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'test@sco.ro', '', '', '20070402155030'),
(213, 0x7465737436, 0x3931383062346461336630633765383039373566616436383566376631333465, 10, 2, 'test@sco.ro', '', '', '20070402155030');

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

INSERT INTO `dsb_user_blogs` (`blog_id`, `fk_user_id`, `blog_name`, `blog_diz`, `stat_posts`, `blog_skin`, `blog_url`) VALUES (1, 2, 'My life', 'How much wood would a woodchuck chuck if a woodchuck could chuck wood?', 9, '', ''),
(3, 2, 'Second life', '', 3, '', '');

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

INSERT INTO `dsb_user_folders` (`folder_id`, `fk_user_id`, `folder`) VALUES (2, 2, ''' "');

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

INSERT INTO `dsb_user_inbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`, `fk_folder_id`, `del`) VALUES (4, 0, 1, 0, 'Admin', 'crcr', 'mrmr [b]ala bala[/b] [u]portocala[/u]', '2007-01-24 14:26:46', 2, 0, 0),
(5, 1, 2, 0, 'Admin', 'crcr', 'mrmr [b]ala bala[/b] [u]portocala[/u]', '2007-01-24 14:26:46', 2, 0, 0),
(6, 0, 209, 0, 'Admin', 'crcr', 'mrmr [b]ala bala[/b] [u]portocala[/u]', '2007-01-24 14:26:46', 2, 0, 0),
(7, 1, 2, 2, 'test', 'Spam test message', '[u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.', '2007-01-12 19:00:00', 0, 0, 0);

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
(3, 2, 'sdsd', 'zxc'),
(4, 2, 'Re%3A%20Spam%20test%20message', '%0A%5Bquote%5D%5Bu%5DThis%5B/u%5D%20%5Bb%5Dis%20a%5B/b%5D%20%5Bquote%5Dspam%20mes%5B/quote%5Dsage%20test.%5B/quote%5D'),
(5, 2, 'Re: Spam test message', '\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]');

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

INSERT INTO `dsb_user_networks` (`nconn_id`, `fk_user_id`, `fk_net_id`, `fk_user_id_friend`, `nconn_status`) VALUES (1, 2, 1, 1, 1),
(2, 2, 1, 2, 1),
(3, 2, 2, 1, 1);

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

INSERT INTO `dsb_user_outbox` (`mail_id`, `is_read`, `fk_user_id`, `fk_user_id_other`, `_user_other`, `subject`, `message_body`, `date_sent`, `message_type`) VALUES (1, 0, 2, 2, 'test', 'Outbox test message', 'This is an outbox test message.', '2007-01-12 17:00:00', 0),
(4, 1, 2, 2, 'test', 'test subj', 'mamma mia\r\n\r\n[quote]test body\r\n[/quote]', '2007-01-15 19:48:40', 0),
(5, 0, 2, 2, 'test', 'Re: sdsd', '\r\n[quote]asdasd[/quote]', '2007-01-15 19:51:25', 0),
(10, 0, 2, 2, 'test', 'Re: Spam test message', '\r\n1\r\n\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 10:59:36', 0),
(7, 1, 2, 0, 'qqqq', 'You have received a flirt from test', 'Let''s rock and roll!', '2007-02-11 18:44:11', 2),
(8, 1, 2, 1, 'dan', 'hello again', 'this is a hello message\r\n', '2007-02-11 18:48:49', 0),
(9, 1, 2, 1, 'dan', 'You have received a flirt from test', 'Let''s rock and roll!', '2007-02-11 18:56:11', 1),
(11, 0, 2, 2, 'test', 'Re: Spam test message', '\r\n1\r\n\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 12:13:02', 0),
(12, 0, 2, 2, 'test', 'ASD', '1\r\nr\r\n"''\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 13:02:11', 0),
(13, 1, 2, 2, 'test', 'GIGI', 'mimi\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 13:03:15', 0),
(14, 0, 2, 2, 'test', 'xxx', 'xxx\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 13:04:24', 0),
(15, 0, 2, 2, 'test', 'Re: Spam test message', '\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 14:22:35', 0),
(16, 1, 2, 2, 'test', 'Re: Spam test message', '\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 14:31:01', 0),
(17, 0, 2, 2, 'test', 'Re: Spam test message', '\r\n[quote][u]This[/u] [b]is a[/b] [quote]spam mes[/quote]sage test.[/quote]', '2007-03-06 14:31:25', 0);

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
  KEY `del` (`del`),
  KEY `date_posted` (`date_posted`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_photos`
-- 

INSERT INTO `dsb_user_photos` (`photo_id`, `fk_user_id`, `_user`, `photo`, `is_main`, `is_private`, `allow_comments`, `caption`, `status`, `reject_reason`, `stat_views`, `stat_comments`, `date_posted`, `last_changed`, `del`) VALUES (82, 210, 'test3', '9/210_31175518453.jpg', 1, 0, 1, 'screen captures of a nice arkanoid clone', 15, '', 0, 0, '2007-04-02 12:54:25', '2007-04-02 12:55:05', 0),
(67, 1, 'dan', '9/1_21171197584.jpg', 1, 0, 0, '', 15, '', 0, 0, '2007-02-11 12:39:49', '2007-02-11 12:40:00', 0),
(66, 1, 'dan', '1/1_11171197584.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 12:39:49', '2007-02-11 12:40:00', 0),
(69, 2, 'test', '6/2_21171208327.jpg', 1, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-04-02 12:34:12', 0),
(70, 2, 'test', '8/2_31171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(81, 210, 'test3', '6/210_21175518453.jpg', 0, 0, 1, 'screen captures of a nice arkanoid clone', 15, '', 0, 0, '2007-04-02 12:54:25', '2007-04-02 12:55:05', 0),
(73, 2, 'test', '3/2_61171208327.jpg', 0, 0, 0, '', 15, '', 0, 0, '2007-02-11 15:38:55', '2007-02-11 15:39:03', 0),
(80, 210, 'test3', '8/210_11175518453.jpg', 0, 0, 1, 'screen captures of a nice arkanoid clone', 15, '', 0, 0, '2007-04-02 12:54:25', '2007-04-02 12:55:05', 0),
(75, 211, 'test4', '7/211_11174509897.jpg', 1, 0, 1, '', 15, '', 0, 0, '2007-03-21 20:44:58', '2007-03-21 20:45:14', 0),
(76, 212, 'test5', '6/212_11174509951.jpg', 1, 0, 1, '', 15, '', 0, 0, '2007-03-21 20:45:53', '2007-03-21 20:46:05', 0),
(77, 213, 'test6', '8/213_51174510004.jpg', 1, 0, 1, '', 15, '', 0, 0, '2007-03-21 20:46:47', '2007-03-21 20:47:02', 0),
(79, 2, 'test', '8/2_11174596557.jpg', 0, 0, 1, 'babe', 15, '', 0, 1, '2007-03-22 20:49:18', '2007-04-02 12:16:49', 0);

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
  `f1` varchar(100) NOT NULL default '',
  `f2` int(5) NOT NULL default '0',
  `f3` text NOT NULL,
  `f4` int(5) NOT NULL default '0',
  `f5` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`profile_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `_user` (`_user`),
  KEY `status` (`status`),
  KEY `del` (`del`),
  KEY `score` (`score`),
  KEY `longitude` (`longitude`,`latitude`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_user_profiles`
-- 

INSERT INTO `dsb_user_profiles` (`profile_id`, `fk_user_id`, `status`, `last_changed`, `date_added`, `reject_reason`, `_user`, `_photo`, `longitude`, `latitude`, `score`, `del`, `f1`, `f2`, `f3`, `f4`, `f5`) VALUES (1, 1, 15, '2007-02-11 12:40:00', '2007-02-11 14:40:00', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'dan', '9/1_21171197584.jpg', 0.0000000000, 0.0000000000, 1, 0, '', 0, '', 0, '0000-00-00'),
(3, 2, 15, '2007-04-02 12:34:12', '2007-02-11 14:37:59', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test', '6/2_21171208327.jpg', -93.6367034912, 42.0276985168, 243, 0, '', 0, '', 0, '0000-00-00'),
(620, 209, 15, '2007-02-07 15:57:51', '2007-02-07 15:57:51', '<html>\r\n    <head>\r\n        <title>Your profile has not been approved</title>\r\n        <link href="http://dating.sco.ro/newdsb/skins/basic/styles/screen.css" media="screen" type="text/css" rel="stylesheet" />\r\n    </head>\r\n    <body>\r\n        <div id="trim">\r\n        <div id="content">\r\n        <p>Thank you for joining <a href="http://dating.sco.ro/newdsb">Web Application</a>.</p>\r\n        <p>Unfortunately we are unable to publish your profile on the site yet because it doesn''t contain enough information to be of interest.</p>\r\n        </div>\r\n        </div>\r\n    </body>\r\n</html>', 'test2', '', 0.0000000000, 0.0000000000, 0, 0, '', 0, '', 0, '0000-00-00'),
(621, 210, 15, '2007-04-02 12:55:05', '0000-00-00 00:00:00', '', 'test3', '9/210_31175518453.jpg', 0.0000000000, 0.0000000000, 5, 0, '', 0, '', 0, '0000-00-00'),
(622, 211, 15, '2007-03-22 11:28:35', '0000-00-00 00:00:00', '', 'test4', '7/211_11174509897.jpg', 0.0000000000, 0.0000000000, 0, 0, '', 0, '', 0, '0000-00-00'),
(623, 212, 15, '2007-03-22 11:28:25', '0000-00-00 00:00:00', '', 'test5', '6/212_11174509951.jpg', 0.0000000000, 0.0000000000, 0, 0, '', 0, '', 0, '0000-00-00'),
(624, 213, 15, '2007-03-22 11:28:28', '0000-00-00 00:00:00', '', 'test6', '8/213_51174510004.jpg', 0.0000000000, 0.0000000000, 0, 0, '', 0, '', 0, '0000-00-00');

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

INSERT INTO `dsb_user_searches` (`search_id`, `fk_user_id`, `title`, `is_default`, `search_qs`, `alert`, `alert_last_id`) VALUES (5, 2, 'latest', 0, 'st=latest', 0, 0),
(9, 2, 'default adv search', 1, 'st=adv&field_46%5B0%5D=2&field_47%5B0%5D=1&field_48_min=18&field_48_max=35&field_50_country=218&f51=1&f52=1', 0, 0);

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

INSERT INTO `dsb_user_settings2` (`config_id`, `fk_user_id`, `config_option`, `config_value`, `fk_module_code`) VALUES (9, 2, 0x646174655f666f726d6174, '%m/%d/%Y', 0x636f7265),
(10, 2, 0x6461746574696d655f666f726d6174, '%m/%d/%Y %r', 0x636f7265),
(11, 2, 0x73656e645f616c6572745f696e74657276616c, '5', 0x6465665f757365725f7072656673);

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

INSERT INTO `dsb_user_stats` (`fk_user_id`, `stat`, `value`) VALUES (2, 'pviews', 20),
(2, 'total_messages', 2),
(2, 'new_messages', 0),
(2, 'total_photos', 7),
(210, 'total_photos', 2);
