/*
 Navicat MySQL Data Transfer

 Source Server         : sql.bembel.de
 Source Server Version : 50163
 Source Host           : 172.16.1.33
 Source Database       : webshare

 Target Server Version : 50163
 File Encoding         : utf-8

 Date: 05/30/2013 21:59:00 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `files`
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `f_id` int(9) NOT NULL AUTO_INCREMENT,
  `f_hash` char(6) NOT NULL,
  `s_id` int(9) DEFAULT NULL,
  `status` int(9) DEFAULT NULL,
  `label` int(1) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `mime` varchar(250) DEFAULT NULL,
  `extension` varchar(4) DEFAULT NULL,
  `size` int(20) DEFAULT '0',
  `downloads` int(9) NOT NULL DEFAULT '0',
  `time_create` int(10) NOT NULL,
  `time_change` int(10) NOT NULL,
  `time_last_download` int(10) NOT NULL,
  PRIMARY KEY (`f_id`,`f_hash`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `shares`
-- ----------------------------
DROP TABLE IF EXISTS `shares`;
CREATE TABLE `shares` (
  `s_id` int(9) NOT NULL AUTO_INCREMENT,
  `s_hash` char(6) NOT NULL DEFAULT '',
  `job_no` char(14) DEFAULT NULL,
  `job_name` varchar(250) DEFAULT 'No Name given',
  `admin` varchar(250) DEFAULT NULL,
  `status` tinyint(9) NOT NULL DEFAULT '1',
  `allow_upload` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`s_id`,`s_hash`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
