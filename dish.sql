-- MySQL dump 10.13  Distrib 5.5.8, for Linux (x86_64)
--
-- Host: localhost    Database: dish
-- ------------------------------------------------------
-- Server version	5.5.8

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `balance_log`
--

DROP TABLE IF EXISTS `balance_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `balance` float NOT NULL DEFAULT '0',
  `describe` varchar(300) COLLATE utf8_swedish_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `balance_log_index_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5732 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `lunch_subsidies` float NOT NULL DEFAULT '0' COMMENT '午餐补贴',
  `dinner_subsidies` float NOT NULL DEFAULT '0' COMMENT '晚餐补贴',
  `open_dish` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `weekend_lunch_subsidies` float NOT NULL DEFAULT '0',
  `weekend_dinner_subsidies` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `food` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) COLLATE utf8_swedish_ci NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL,
  `week` varchar(15) COLLATE utf8_swedish_ci NOT NULL DEFAULT '1,2,3,4,5,6,0',
  `categories_id` int(5) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `food_index_shop_id` (`shop_id`),
  KEY `food_index_categories_id` (`categories_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1337 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_info`
--

DROP TABLE IF EXISTS `order_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` longtext COLLATE utf8_swedish_ci NOT NULL,
  `paystatus` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `canceled` int(1) NOT NULL DEFAULT '0',
  `stime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total` float NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `user_name` varchar(48) COLLATE utf8_swedish_ci NOT NULL,
  `subsidies` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order_info_index_stime` (`stime`),
  KEY `order_info_index_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5475 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop`
--

DROP TABLE IF EXISTS `shop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) COLLATE utf8_swedish_ci NOT NULL,
  `address` varchar(48) COLLATE utf8_swedish_ci DEFAULT NULL,
  `tel` varchar(48) COLLATE utf8_swedish_ci DEFAULT NULL,
  `css` longtext COLLATE utf8_swedish_ci,
  `note` varchar(250) COLLATE utf8_swedish_ci DEFAULT NULL,
  `delivery_time` varchar(10) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'all',
  `isshow` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shop_categories`
--

DROP TABLE IF EXISTS `shop_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `shop_id` int(3) NOT NULL DEFAULT '0',
  `categories` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=230 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_info`
--

DROP TABLE IF EXISTS `user_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(48) COLLATE utf8_swedish_ci NOT NULL,
  `realname` varchar(48) COLLATE utf8_swedish_ci NOT NULL,
  `balance` float NOT NULL DEFAULT '0',
  `isadmin` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`realname`),
  UNIQUE KEY `username_2` (`username`),
  UNIQUE KEY `realname` (`realname`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-25 17:00:48
