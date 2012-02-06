delimiter $$

CREATE TABLE `dct_patient_events` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


CREATE TABLE `dct_patient_events_statuses` (
  `id` int(11) NOT NULL,
  `text` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

