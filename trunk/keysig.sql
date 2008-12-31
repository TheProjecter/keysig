--
-- @package Keysig
-- @copyright Brilaps, LLC (http://brilaps.com)
-- @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
--

-- 
-- Table structure for table `match_attempts`
-- 

CREATE TABLE `match_attempts` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `user_agent` varchar(2000) NOT NULL,
  `ch_array` text NOT NULL,
  `time_down_array` text NOT NULL,
  `duration_array` text NOT NULL,
  `attempt_date_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `pattern_value` varchar(255) NOT NULL,
  `ch_array` text NOT NULL,
  `time_down_array` text NOT NULL,
  `duration_array` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `users`
-- Some sample patterns to test against
-- 

INSERT INTO `users` VALUES (1, 'test', 'test', 'a:4:{i:0;s:1:"T";i:1;s:1:"E";i:2;s:1:"S";i:3;s:1:"T";}', 'a:4:{i:0;d:1219382902606;i:1;d:1219382902644;i:2;d:1219382902804;i:3;d:1219382902885;}', 'a:4:{i:0;i:46;i:1;i:48;i:2;i:80;i:3;i:47;}');
INSERT INTO `users` VALUES (2, 'test123', 'test123', 'a:7:{i:0;s:1:"T";i:1;s:1:"E";i:2;s:1:"S";i:3;s:1:"T";i:4;s:1:"1";i:5;s:1:"2";i:6;s:1:"3";}', 'a:7:{i:0;d:1219468916147;i:1;d:1219468916443;i:2;d:1219468916731;i:3;d:1219468916867;i:4;d:1219468917203;i:5;d:1219468917438;i:6;d:1219468917699;}', 'a:7:{i:0;i:64;i:1;i:72;i:2;i:80;i:3;i:67;i:4;i:88;i:5;i:93;i:6;i:88;}');