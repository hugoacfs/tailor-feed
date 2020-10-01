-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2020 at 11:38 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thefeed`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

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

CREATE TABLE `articles_topics` (
  `id` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
-- Twitter basic config
(1, 'twitter_classname', 'Twitter');
(2, 'twitter_articlescroninterval', '60');
(3, 'twitter_sourcescroninterval', '500');
(4, 'twitter_articleslastupdated', '0');
(5, 'twitter_sourceslastupdated', '0');
(6, 'twitter_articlescronenabled', '0');
(36, 'twitter_sourcescronenabled', '0');
-- Facebook basic config
(7, 'facebook_classname', 'Facebook');
(8, 'facebook_articlescroninterval', '60');
(9, 'facebook_sourcescroninterval', '500');
(10, 'facebook_articleslastupdated', '0');
(11, 'facebook_sourceslastupdated', '0');
(12, 'facebook_articlescronenabled', '0');
(37, 'facebook_sourcescronenabled', '0');
-- RSS basic config
(13, 'rss_classname', 'Rss');
(14, 'rss_articlescroninterval', '60');
(15, 'rss_sourcescroninterval', '500');
(16, 'rss_articleslastupdated', '0');
(17, 'rss_sourceslastupdated', '0');
(18, 'rss_articlescronenabled', '0');
(38, 'rss_sourcescronenabled', '0');
-- Twitter api config
(19, 'twitter_api_consumerkey', '');
(20, 'twitter_api_consumersecret', '');
(21, 'twitter_api_oauthaccesstoken', '');
(22, 'twitter_api_oauthaccesstokensecret', '');
-- Facebook api config
(23, 'facebook_api_accesstoken', '');
(24, 'facebook_api_clientid', '');
(25, 'facebook_api_clientsecret', '');

(26, 'recycle_userscroninterval', '31556952'); -- Run once per year
(27, 'recycle_userslastupdated', '0');
(28, 'recycle_userscronenabled', '0');
(29, 'recycle_articlescroninterval', '31556952'); -- Run once per year
(30, 'recycle_articleslastupdated', '0');
(31, 'recycle_articlescronenabled', '0');

(32, 'auth_method', 'default');
(33, 'debug_enabled', '0');
(34, 'googleanalytics_id', '');
(35, 'googleanalytics_enabled', '0');




-- --------------------------------------------------------

--
-- Table structure for table `media_links`
--

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

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `reference` varchar(55) NOT NULL,
  `screenname` varchar(55) NOT NULL DEFAULT 'Update this name',
  `type` varchar(45) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `imagesource` varchar(255) DEFAULT 'img / default_user_icon.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_sources`
--

CREATE TABLE `subscribed_sources` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `sourceid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_topics`
--

CREATE TABLE `subscribed_topics` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

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
(1, 'default', 'Guest', 'u', 0, '$2y$10$eiwKgjEQgxM5af.iAqz26uw94fsRXvpgItag26BYSUZ3XZCNfc8jq'),
(2, 'admin', 'Admin', 'a', 1583406268, '$2y$10$eiwKgjEQgxM5af.iAqz26uw94fsRXvpgItag26BYSUZ3XZCNfc8jq');

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
  ADD UNIQUE KEY `idx_articles_uniqueidentifier` (`uniqueidentifier`),
  ADD KEY `fk_articles_sources1_idx` (`sourceid`),
  ADD KEY `idx_creationdate` (`creationdate`);

--
-- Indexes for table `articles_topics`
--
ALTER TABLE `articles_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_articles_topics_articleid_topicid` (`articleid`,`topicid`),
  ADD KEY `fk_art_top_topicid_topics_id` (`topicid`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_config_name_value` (`name`,`value`),
  ADD UNIQUE KEY `idx_config_name` (`name`);

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
  ADD UNIQUE KEY `idx_sources_reference_type` (`reference`,`type`),
  ADD KEY `idx_sources_reference` (`reference`);

--
-- Indexes for table `sources_config`
--
ALTER TABLE `sources_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name_uq` (`type`,`name`),
  ADD UNIQUE KEY `idx_sources_config_type_name` (`type`,`name`);

--
-- Indexes for table `subscribed_sources`
--
ALTER TABLE `subscribed_sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_subscribed_sources_userid_sourceid` (`userid`,`sourceid`),
  ADD KEY `fk_sub_src_sourceid_sources_id` (`sourceid`);

--
-- Indexes for table `subscribed_topics`
--
ALTER TABLE `subscribed_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_subscribed_topics_userid_topicid` (`userid`,`topicid`),
  ADD KEY `fk_sub_top_topicid_topics_id` (`topicid`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_topics_name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_idx` (`username`),
  ADD KEY `idx_users_lastlogin` (`lastlogin`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_sources1` FOREIGN KEY (`sourceid`) REFERENCES `sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `articles_topics`
--
ALTER TABLE `articles_topics`
  ADD CONSTRAINT `fk_art_top_articleid_articles_id` FOREIGN KEY (`articleid`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_art_top_topicid_topics_id` FOREIGN KEY (`topicid`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media_links`
--
ALTER TABLE `media_links`
  ADD CONSTRAINT `fk_articleid_article_id` FOREIGN KEY (`articleid`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscribed_sources`
--
ALTER TABLE `subscribed_sources`
  ADD CONSTRAINT `fk_sub_src_sourceid_sources_id` FOREIGN KEY (`sourceid`) REFERENCES `sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sub_src_userid_users_id` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscribed_topics`
--
ALTER TABLE `subscribed_topics`
  ADD CONSTRAINT `fk_sub_top_topicid_topics_id` FOREIGN KEY (`topicid`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sub_top_userid_users_id` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
