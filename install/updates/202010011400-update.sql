DROP TABLE `sources_config`;

INSERT INTO `config` (`id`, `name`, `value`) VALUES
-- Twitter basic config
(1, 'twitter_classname', 'Twitter'),
(2, 'twitter_articlescroninterval', '60'),
(3, 'twitter_sourcescroninterval', '500'),
(4, 'twitter_articleslastupdated', '0'),
(5, 'twitter_sourceslastupdated', '0'),
(6, 'twitter_articlescronenabled', '0'),
(36, 'twitter_sourcescronenabled', '0'),
-- Facebook basic config
(7, 'facebook_classname', 'Facebook'),
(8, 'facebook_articlescroninterval', '60'),
(9, 'facebook_sourcescroninterval', '500'),
(10, 'facebook_articleslastupdated', '0'),
(11, 'facebook_sourceslastupdated', '0'),
(12, 'facebook_articlescronenabled', '0'),
(37, 'facebook_sourcescronenabled', '0'),
-- RSS basic config
(13, 'rss_classname', 'Rss'),
(14, 'rss_articlescroninterval', '60'),
(15, 'rss_sourcescroninterval', '500'),
(16, 'rss_articleslastupdated', '0'),
(17, 'rss_sourceslastupdated', '0'),
(18, 'rss_articlescronenabled', '0'),
(38, 'rss_sourcescronenabled', '0'),
-- Twitter api config
(19, 'twitter_api_consumerkey', ''),
(20, 'twitter_api_consumersecret', ''),
(21, 'twitter_api_oauthaccesstoken', ''),
(22, 'twitter_api_oauthaccesstokensecret', ''),
-- Facebook api config
(23, 'facebook_api_accesstoken', ''),
(24, 'facebook_api_clientid', ''),
(25, 'facebook_api_clientsecret', ''),

(26, 'recycle_userscroninterval', '31556952'), -- Run once per year
(27, 'recycle_userslastupdated', '0'),
(28, 'recycle_userscronenabled', '0'),
(29, 'recycle_articlescroninterval', '31556952'), -- Run once per year
(30, 'recycle_articleslastupdated', '0'),
(31, 'recycle_articlescronenabled', '0'),

(32, 'auth_method', 'default'),
(33, 'debug_enabled', '0'),
(34, 'googleanalytics_id', ''),
(35, 'googleanalytics_enabled', '0');