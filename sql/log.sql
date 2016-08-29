CREATE TABLE `log` (
  `logId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL DEFAULT '',
  `timeCreated` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
