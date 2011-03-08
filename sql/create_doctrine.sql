delimiter $$

CREATE TABLE `dct_document_entries` (
  `uuid` varchar(36) NOT NULL,
  `discr` varchar(15) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `codeFormat` varchar(45) DEFAULT NULL,
  `author` varchar(45) DEFAULT NULL,
  `text` text,
  `prevVersion` varchar(36) DEFAULT NULL,
  `nextVersion` varchar(36) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_document_forms` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_document_items` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `document_id` varchar(36) DEFAULT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  `entry_id` varchar(36) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_document_metadata` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `shortDesc` varchar(255) DEFAULT NULL,
  `longDesc` text,
  `discr` varchar(10) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `code_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_document_metadata_collection_items` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `metadata_id` varchar(36) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_documents` (
  `uuid` varchar(36) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `patient_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_keyword_code_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keywords` (`keyword_id`),
  KEY `codes` (`code_id`)
) ENGINE=MyISAM AUTO_INCREMENT=86334 DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `dct_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=5079 DEFAULT CHARSET=utf8$$

