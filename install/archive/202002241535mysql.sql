-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 24, 2020 at 03:35 PM
-- Server version: 5.7.29-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chinews`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `creationdate` int(11) NOT NULL,
  `description` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `media_links`
--

CREATE TABLE `media_links` (
  `id` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `url` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `screenname` varchar(45) NOT NULL DEFAULT 'Update this name',
  `type` varchar(45) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `imagesource` varchar(255) DEFAULT NULL
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
  `lastlogin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_userid_users_id` (`userid`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;
--
-- AUTO_INCREMENT for table `articles_topics`
--
ALTER TABLE `articles_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `media_links`
--
ALTER TABLE `media_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `subscribed_sources`
--
ALTER TABLE `subscribed_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;
--
-- AUTO_INCREMENT for table `subscribed_topics`
--
ALTER TABLE `subscribed_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
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
-- Constraints for table `articles_topics`
--
ALTER TABLE `articles_topics`
  ADD CONSTRAINT `fk_articles_has_topics_articles1` FOREIGN KEY (`articleid`) REFERENCES `articles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articles_has_topics_topics1` FOREIGN KEY (`topicid`) REFERENCES `topics` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
