UPDATE `sources_config` SET `name` = 'articles_cron' WHERE `sources_config`.`id` = 21;
UPDATE `sources_config` SET `name` = 'articles_last_cron' WHERE `sources_config`.`id` = 22;
UPDATE `sources_config` SET `name` = 'articles_cron' WHERE `sources_config`.`id` = 13;
UPDATE `sources_config` SET `name` = 'articles_last_cron' WHERE `sources_config`.`id` = 14;
UPDATE `sources_config` SET `name` = 'articles_cron' WHERE `sources_config`.`id` = 4;
UPDATE `sources_config` SET `name` = 'articles_last_cron' WHERE `sources_config`.`id` = 5;
INSERT INTO `sources_config` (`id`, `type`, `name`, `value`) VALUES (NULL, 'twitter', 'sources_cron', '86400'), (NULL, 'twitter', 'sources_last_cron', '0'), (NULL, 'facebook', 'sources_cron', '86400'), (NULL, 'facebook', 'sources_last_cron', '0'), (NULL, 'rss', 'sources_cron', '86400'), (NULL, 'rss', 'sources_last_cron', '0')
