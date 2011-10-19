-- MySQL dump 10.13  Distrib 5.5.13, for Win64 (x86)
--
-- Host: localhost    Database: openemr
-- ------------------------------------------------------
-- Server version	5.5.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dct_document_entries`
--

DROP TABLE IF EXISTS `dct_document_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_document_entries` (
  `uuid` varchar(36) NOT NULL,
  `discr` varchar(15) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `code_type` varchar(45) DEFAULT NULL,
  `author` varchar(45) DEFAULT NULL,
  `text` text,
  `copiedFrom_id` varchar(36) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `metadata_id` varchar(36) DEFAULT NULL,
  `locked` datetime DEFAULT NULL,
  `OEMRListItem` bigint(20) DEFAULT NULL,
  `attr1` varchar(255) DEFAULT NULL,
  `attr2` varchar(255) DEFAULT NULL,
  `attr3` varchar(255) DEFAULT NULL,
  `num1` float DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  KEY `Patient_idx` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_document_items`
--

DROP TABLE IF EXISTS `dct_document_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_document_items` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `locked` datetime DEFAULT NULL,
  `document_id` varchar(36) DEFAULT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  `entry_id` varchar(36) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `root_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  KEY `entry_id` (`entry_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_document_metadata`
--

DROP TABLE IF EXISTS `dct_document_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_document_metadata` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `shortDesc` varchar(255) DEFAULT NULL,
  `longDesc` text,
  `discr` varchar(10) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `code_type` varchar(10) DEFAULT NULL,
  `metadata` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_document_metadata_collection_items`
--

DROP TABLE IF EXISTS `dct_document_metadata_collection_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_document_metadata_collection_items` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `metadata_id` varchar(36) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_documents`
--

DROP TABLE IF EXISTS `dct_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_documents` (
  `uuid` varchar(36) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `locked` datetime DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `metadata_id` varchar(36) DEFAULT NULL,
  `lockedBy` varchar(255) DEFAULT NULL,
  `XMLContent` text,
  `lockHash` varchar(255) DEFAULT NULL,
  `encounter_id` int(11) DEFAULT NULL,
  `removed` datetime DEFAULT NULL,
  `removedBy` varchar(255) DEFAULT NULL,
  `dateofservice` datetime DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  KEY `AUTHOR_IDX` (`author`),
  KEY `PATIENT_ID` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_drug_attributes`
--

DROP TABLE IF EXISTS `dct_drug_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_drug_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `RXCUI` varchar(45) DEFAULT NULL,
  `ATN` varchar(45) DEFAULT NULL,
  `ATV` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUENESS_RXCUI_ATN` (`RXCUI`,`ATN`),
  KEY `RXCUI` (`RXCUI`)
) ENGINE=InnoDB AUTO_INCREMENT=134486 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_drug_route_options`
--

DROP TABLE IF EXISTS `dct_drug_route_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_drug_route_options` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DRTA` varchar(45) DEFAULT NULL,
  `OPTION` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_keyword_code_map`
--

DROP TABLE IF EXISTS `dct_keyword_code_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_keyword_code_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codes` (`code_id`),
  KEY `keywords` (`keyword_id`)
) ENGINE=InnoDB AUTO_INCREMENT=97038 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_keywords`
--

DROP TABLE IF EXISTS `dct_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_UNIQUE` (`content`)
) ENGINE=InnoDB AUTO_INCREMENT=5931 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_observation_metadata`
--

DROP TABLE IF EXISTS `dct_observation_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_observation_metadata` (
  `uuid` varchar(36) NOT NULL,
  `discr` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `code_type` varchar(45) DEFAULT NULL,
  `text` varchar(45) DEFAULT NULL,
  `classification` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_statuses`
--

DROP TABLE IF EXISTS `dct_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_statuses` (
  `uuid` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `document_entry_id` varchar(36) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_vocab_mappings`
--

DROP TABLE IF EXISTS `dct_vocab_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_vocab_mappings` (
  `UUID` varchar(34) NOT NULL,
  `relationship` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `source_code` varchar(45) DEFAULT NULL,
  `source_code_type` varchar(45) DEFAULT NULL,
  `target_code` varchar(45) DEFAULT NULL,
  `target_code_type` varchar(45) DEFAULT NULL,
  `text` varchar(1000) DEFAULT NULL,
  `classification` varchar(45) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `attr1` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`UUID`),
  KEY `idx_source_code` (`source_code`),
  KEY `idx_target_code` (`target_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dct_vocab_ordering`
--

DROP TABLE IF EXISTS `dct_vocab_ordering`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dct_vocab_ordering` (
  `classification` varchar(255) NOT NULL,
  `seq` int(11) DEFAULT NULL,
  PRIMARY KEY (`classification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnnames`
--

DROP TABLE IF EXISTS `rxnnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnnames` (
  `RXAUI` varchar(8) NOT NULL,
  `RXCUI` varchar(8) DEFAULT NULL,
  `STR` varchar(3000) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`RXAUI`),
  KEY `STR` (`STR`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-18 23:13:09
