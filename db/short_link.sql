SET NAMES utf8mb4;

CREATE TABLE `short_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `suffix` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `link` text COLLATE utf8mb4_general_ci NOT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `ban` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `suffix` (`suffix`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='链.ml';