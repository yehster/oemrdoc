delimiter $$

CREATE TABLE `libre_event` (
  `uuid` varchar(34) NOT NULL,
  `created` datetime DEFAULT NULL,
  `file` varchar(34) DEFAULT NULL,
  `event_info` varchar(45) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`uuid`),
  KEY `FILE_IDX` (`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `libre_file` (
  `filename` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `original_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$



