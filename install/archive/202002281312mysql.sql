-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2020 at 01:11 PM
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
-- Database: `tailor_feed`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `creationdate` int(11) NOT NULL,
  `target` varchar(55) DEFAULT NULL,
  `action` varchar(55) NOT NULL DEFAULT 'undefined',
  `targetid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `uniqueidentifier` varchar(45) NOT NULL,
  `creationdate` int(11) NOT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `articles_topics`
--

DROP TABLE IF EXISTS `articles_topics`;
CREATE TABLE `articles_topics` (
  `id` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'auth_method', 'default'),
(2, 'json_secret', 'default'),
(3, 'json_public', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `media_links`
--

DROP TABLE IF EXISTS `media_links`;
CREATE TABLE `media_links` (
  `id` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `url` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'photo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `reference` varchar(55) NOT NULL,
  `screenname` varchar(45) NOT NULL DEFAULT 'Update this name',
  `type` varchar(45) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `imagesource` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sources_config`
--

DROP TABLE IF EXISTS `sources_config`;
CREATE TABLE `sources_config` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sources_config`
--

INSERT INTO `sources_config` (`id`, `type`, `name`, `value`) VALUES
(1, 'twitter', 'update_articles', 'true'),
(2, 'twitter', 'update_sources', 'true'),
(3, 'twitter', 'class', 'Twitter'),
(4, 'twitter', 'cron', '60'),
(5, 'twitter', 'last_cron', 'never'),
(6, 'twitter_api', 'oauth_access_token', 'default'),
(7, 'twitter_api', 'oauth_access_token_secret', 'default'),
(8, 'twitter_api', 'consumer_key', 'default'),
(9, 'twitter_api', 'consumer_secret', 'default'),
(10, 'facebook', 'update_articles', 'true'),
(11, 'facebook', 'update_sources', 'true'),
(12, 'facebook', 'class', 'Facebook'),
(13, 'facebook', 'cron', '120'),
(14, 'facebook', 'last_cron', 'never'),
(15, 'facebook_api', 'client_id', 'default'),
(16, 'facebook_api', 'client_secret', 'default'),
(17, 'facebook_api', 'access_token', 'default'),
(18, 'rss', 'update_articles', 'true'),
(19, 'rss', 'update_sources', 'true'),
(20, 'rss', 'class', 'Rss'),
(21, 'rss', 'cron', '3600'),
(22, 'rss', 'last_cron', 'never');

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_sources`
--

DROP TABLE IF EXISTS `subscribed_sources`;
CREATE TABLE `subscribed_sources` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `sourceid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_topics`
--

DROP TABLE IF EXISTS `subscribed_topics`;
CREATE TABLE `subscribed_topics` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(105) NOT NULL DEFAULT 'No description available',
  `status` varchar(12) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `givenname` varchar(55) DEFAULT NULL,
  `role` varchar(1) NOT NULL DEFAULT 'u',
  `lastlogin` int(11) DEFAULT NULL,
  `password` char(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `givenname`, `role`, `lastlogin`, `password`) VALUES
(1, 'default', 'Guest', 'u', NULL, NULL),
(2, 'admin', 'Admin', 'a', NULL, '$2y$10$eiwKgjEQgxM5af.iAqz26uw94fsRXvpgItag26BYSUZ3XZCNfc8jq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_userid_users_id` (`userid`),
  ADD KEY `idx_targetid` (`targetid`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifier` (`id`,`uniqueidentifier`),
  ADD KEY `fk_articles_sources1_idx` (`sourceid`),
  ADD KEY `idx_creationdate` (`creationdate`);

--
-- Indexes for table `articles_topics`
--
ALTER TABLE `articles_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_topics` (`articleid`,`topicid`),
  ADD KEY `fk_articles_has_topics_topics1_idx` (`topicid`),
  ADD KEY `fk_articles_has_topics_articles1_idx` (`articleid`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_links`
--
ALTER TABLE `media_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_articleid_article_id` (`articleid`);

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ref_type` (`reference`,`type`);

--
-- Indexes for table `sources_config`
--
ALTER TABLE `sources_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribed_sources`
--
ALTER TABLE `subscribed_sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sourceid_sources_id` (`sourceid`),
  ADD KEY `fk_subs_sources_userid_users_id` (`userid`);

--
-- Indexes for table `subscribed_topics`
--
ALTER TABLE `subscribed_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subs_topics_userid_users_id` (`userid`),
  ADD KEY `fk_topicid_topics_id` (`topicid`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_idx` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articles_topics`
--
ALTER TABLE `articles_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media_links`
--
ALTER TABLE `media_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sources_config`
--
ALTER TABLE `sources_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `subscribed_sources`
--
ALTER TABLE `subscribed_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribed_topics`
--
ALTER TABLE `subscribed_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
