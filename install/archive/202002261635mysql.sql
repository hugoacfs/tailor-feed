-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2020 at 04:35 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `week_6`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `creationdate` int(11) NOT NULL,
  `target` varchar(55) DEFAULT NULL,
  `action` varchar(55) NOT NULL DEFAULT 'undefined',
  `targetid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_admin_userid_users_id` (`userid`),
  KEY `idx_targetid` (`targetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceid` int(11) NOT NULL,
  `uniqueidentifier` varchar(45) NOT NULL,
  `creationdate` int(11) NOT NULL,
  `body` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`id`,`uniqueidentifier`),
  KEY `fk_articles_sources1_idx` (`sourceid`),
  KEY `idx_creationdate` (`creationdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `articles_topics`
--

DROP TABLE IF EXISTS `articles_topics`;
CREATE TABLE IF NOT EXISTS `articles_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_topics` (`articleid`,`topicid`),
  KEY `fk_articles_has_topics_topics1_idx` (`topicid`),
  KEY `fk_articles_has_topics_articles1_idx` (`articleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `media_links`
--

DROP TABLE IF EXISTS `media_links`;
CREATE TABLE IF NOT EXISTS `media_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL,
  `url` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'photo',
  PRIMARY KEY (`id`),
  KEY `fk_articleid_article_id` (`articleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
CREATE TABLE IF NOT EXISTS `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(55) NOT NULL,
  `screenname` varchar(45) NOT NULL DEFAULT 'Update this name',
  `type` varchar(45) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `imagesource` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ref_type` (`reference`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_sources`
--

DROP TABLE IF EXISTS `subscribed_sources`;
CREATE TABLE IF NOT EXISTS `subscribed_sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `sourceid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sourceid_sources_id` (`sourceid`),
  KEY `fk_subs_sources_userid_users_id` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_topics`
--

DROP TABLE IF EXISTS `subscribed_topics`;
CREATE TABLE IF NOT EXISTS `subscribed_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subs_topics_userid_users_id` (`userid`),
  KEY `fk_topicid_topics_id` (`topicid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(105) NOT NULL DEFAULT 'No description available',
  `status` varchar(12) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `givenname` varchar(55) DEFAULT NULL,
  `role` varchar(1) NOT NULL DEFAULT 'u',
  `lastlogin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_idx` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `fk_admin_userid_users_id` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_sources1` FOREIGN KEY (`sourceid`) REFERENCES `sources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `media_links`
--
ALTER TABLE `media_links`
  ADD CONSTRAINT `fk_articleid_article_id` FOREIGN KEY (`articleid`) REFERENCES `articles` (`id`);

--
-- Constraints for table `subscribed_sources`
--
ALTER TABLE `subscribed_sources`
  ADD CONSTRAINT `fk_sourceid_sources_id` FOREIGN KEY (`sourceid`) REFERENCES `sources` (`id`),
  ADD CONSTRAINT `fk_subs_sources_userid_users_id` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `subscribed_topics`
--
ALTER TABLE `subscribed_topics`
  ADD CONSTRAINT `fk_subs_topics_userid_users_id` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_topicid_topics_id` FOREIGN KEY (`topicid`) REFERENCES `topics` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
