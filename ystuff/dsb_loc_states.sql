-- phpMyAdmin SQL Dump
-- version 2.8.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 13, 2007 at 10:21 AM
-- Server version: 4.0.18
-- PHP Version: 4.4.2
-- 
-- Database: `newdsb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `dsb_loc_states`
-- 

DROP TABLE IF EXISTS `dsb_loc_states`;
CREATE TABLE `dsb_loc_states` (
  `state_id` int(10) unsigned NOT NULL auto_increment,
  `fk_country_id` int(2) unsigned NOT NULL default '0',
  `iso3166` char(2) NOT NULL default '',
  `state` varchar(200) NOT NULL default '',
  `num_cities` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`state_id`),
  KEY `fk_country_id` (`fk_country_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `dsb_loc_states`
-- 

INSERT INTO `dsb_loc_states` (`state_id`, `fk_country_id`, `iso3166`, `state`, `num_cities`) VALUES (1, 218, 'AK', 'Alaska', 696),
(2, 218, 'AL', 'Alabama', 5304),
(3, 218, 'AS', 'American Samoa', 0),
(4, 218, 'AR', 'Arkansas', 4328),
(5, 218, 'AZ', 'Arizona', 1994),
(6, 218, 'CA', 'California', 6415),
(7, 218, 'CO', 'Colorado', 1613),
(8, 218, 'CT', 'Connecticut', 1060),
(9, 218, 'DC', 'District of Columbia', 177),
(10, 218, 'DE', 'Delaware', 1588),
(11, 218, 'FM', 'Federated States Of Micronesia', 0),
(12, 218, 'FL', 'Florida', 3441),
(13, 218, 'GA', 'Georgia', 6759),
(14, 218, 'GU', 'Guam', 0),
(15, 218, 'HI', 'Hawaii', 529),
(16, 218, 'IA', 'Iowa', 2504),
(17, 218, 'ID', 'Idaho', 1327),
(18, 218, 'IL', 'Illinois', 4094),
(19, 218, 'IN', 'Indiana', 3443),
(20, 218, 'KS', 'Kansas', 1674),
(21, 218, 'KY', 'Kentucky', 4479),
(22, 218, 'LA', 'Louisiana', 3614),
(23, 218, 'MA', 'Massachusetts', 2297),
(24, 218, 'MH', 'Marshall Islands', 0),
(25, 218, 'MD', 'Maryland', 8247),
(26, 218, 'ME', 'Maine', 2135),
(27, 218, 'MI', 'Michigan', 2848),
(28, 218, 'MN', 'Minnesota', 2363),
(29, 218, 'MO', 'Missouri', 4397),
(30, 218, 'MS', 'Mississippi', 3152),
(31, 218, 'MT', 'Montana', 2041),
(32, 218, 'MP', 'Northern Mariana Islands', 0),
(33, 218, 'NC', 'North Carolina', 5324),
(34, 218, 'ND', 'North Dakota', 889),
(35, 218, 'NE', 'Nebraska', 1252),
(36, 218, 'NH', 'New Hampshire', 997),
(37, 218, 'NJ', 'New Jersey', 2572),
(38, 218, 'NM', 'New Mexico', 1699),
(39, 218, 'NV', 'Nevada', 1096),
(40, 218, 'NY', 'New York', 6242),
(41, 218, 'OH', 'Ohio', 5934),
(42, 218, 'OK', 'Oklahoma', 1921),
(43, 218, 'OR', 'Oregon', 1545),
(44, 218, 'PW', 'Palau', 0),
(45, 218, 'PA', 'Pennsylvania', 9749),
(46, 218, 'PR', 'Puerto Rico', 0),
(47, 218, 'RI', 'Rhode Island', 397),
(48, 218, 'SC', 'South Carolina', 4302),
(49, 218, 'SD', 'South Dakota', 983),
(50, 218, 'TN', 'Tennessee', 6570),
(51, 218, 'TX', 'Texas', 8500),
(52, 218, 'UT', 'Utah', 1973),
(53, 218, 'VA', 'Virginia', 7938),
(54, 218, 'VI', 'Virgin Islands', 0),
(55, 218, 'VT', 'Vermont', 886),
(56, 218, 'WA', 'Washington', 2565),
(57, 218, 'WI', 'Wisconsin', 2436),
(58, 218, 'WV', 'West Virginia', 4143),
(59, 218, 'WY', 'Wyoming', 584);
