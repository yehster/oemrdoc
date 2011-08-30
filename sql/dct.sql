
C:\Users\yehster\Documents\NetBeansProjects\oemrdoc\sql>"\Program Files\MySQL\MySQL Server 5.5\bin\mysqldump.exe" -uroot -p openemr dct_document_entries dct_document_items dct_document_metadata dct_document_metadata_collection_items dct_documents dct_observation_metadata dct_statuses dct_vocab_mappings dct_vocab_ordering 
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
  `num1` float DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  KEY `Patient_idx` (`patient_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_document_entries`
--

LOCK TABLES `dct_document_entries` WRITE;
/*!40000 ALTER TABLE `dct_document_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `dct_document_entries` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_document_items`
--

LOCK TABLES `dct_document_items` WRITE;
/*!40000 ALTER TABLE `dct_document_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `dct_document_items` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_document_metadata`
--

LOCK TABLES `dct_document_metadata` WRITE;
/*!40000 ALTER TABLE `dct_document_metadata` DISABLE KEYS */;
INSERT INTO `dct_document_metadata` VALUES ('187eb3ef-a44d-4338-98b6-c8ad1f4ede9a','2011-07-21 08:56:23','2011-07-21 08:56:23','HP','History and Physical','doc',NULL,NULL,NULL),('1a2d248b-aa83-496a-a7f3-275e843941a4','2011-07-21 08:56:23','2011-07-21 08:56:23','CC','Chief Complaint','sect','10154-3','LOINC',NULL),('a19addc1-4884-4f58-b7ab-ba579a8e8822','2011-07-21 08:56:23','2011-07-21 08:56:23','CCNAR','Chief Complaint Narrative','narmd','10154-3','LOINC',NULL),('a0751004-b9fb-4f9f-9120-58b4a26d70a3','2011-07-21 08:57:07','2011-07-21 08:57:07','HPI','History of Present Illness','sect','10164-2','LOINC',NULL),('725041e7-09b1-4457-8e7f-b8b8c30fb41f','2011-07-21 08:57:07','2011-07-21 08:57:07','HPINAR','History of Present Illness Narrative','narmd','10164-2','LOINC',NULL),('90fbc3d0-b956-4113-af77-4b3c3c18442a','2011-07-21 08:57:07','2011-07-21 08:57:07','PMH','Past Medical History','sect','11348-0','LOINC',NULL),('f8160ec9-d25f-4ede-b951-149c19953567','2011-07-21 08:57:07','2011-07-21 08:57:07','ALL','Allergies','sect','8658-7','LOINC',NULL),('b013fd9d-0295-44dc-a331-cec863a71d6b','2011-07-21 08:57:07','2011-07-21 08:57:07','ALL:DRUG','Drug Allergy','sect','A8380263','SNOMED',NULL),('9907b478-5b88-4c39-838f-a8555b9ea789','2011-07-21 08:57:07','2011-07-21 08:57:07','ALL:FOOD','Food Allergy','sect','A7873398','SNOMED',NULL),('dc53d643-e2b1-43c9-9896-faa1a2b6bdd5','2011-07-21 08:57:07','2011-07-21 08:57:07','MED','Medications','sect','52471-0','LOINC',NULL),('371890ec-9bf0-441e-9efb-d64e310fc25b','2011-07-21 08:57:07','2011-07-21 08:57:07','FAM','Family History','sect','A11740158','SNOMED',NULL),('d254b214-51cc-406d-891a-0af6a2a40ccc','2011-07-21 08:57:07','2011-07-21 08:57:07','SOC','Social History','sect','29762-2','LOINC',NULL),('76de1601-d081-4a7b-84e0-74f253a0b463','2011-07-21 08:57:07','2011-07-21 08:57:07','TOB','Tobacco Use','sect','A3210982','SNOMED',NULL),('15e12575-0590-44a8-8721-2b821610d1cc','2011-07-21 08:57:07','2011-07-21 08:57:07','ALC','Alcohol Intake','sect','A3245941','SNOMED',NULL),('3bf45ea2-f95e-483a-a943-381865da74fb','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS','Review of Systems','sect','10187-3','LOINC',NULL),('c7031074-11b9-4724-b9d5-49d062e21a28','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:CON','Review of Systems:Constitutional','sect','CON','ROS',NULL),('08944fd4-a291-4da8-8ef5-e0f5df390434','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:EYE','Review of Systems:Eyes','sect','EYE','ROS',NULL),('4ff5a08b-6202-4cfb-8a08-a0d11ccfdbec','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:ENT','Review of Systems:Ears & Nose & Mouth & Throat','sect','ENT','ROS',NULL),('bd59126b-722d-4136-a7b2-30be6ecb7e73','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:CV','Review of Systems:Cardiovascular','sect','CV','ROS',NULL),('bd5a796c-eebb-428e-bd9e-b49cd75578f8','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:RES','Review of Systems:Respiratory','sect','RES','ROS',NULL),('31f8e578-8f22-4030-b2cb-69cc82aeebea','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:GI','Review of Systems:Gastrointestinal','sect','GI','ROS',NULL),('dd1f5f3c-0c0f-418c-b833-a7148dfa26dc','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:GU','Review of Systems:Genitourinary tract','sect','GU','ROS',NULL),('7f0fda28-4bed-4c99-a8d8-a4b300e2f582','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:MS','Review of Systems:Musculoskeletal','sect','MS','ROS',NULL),('566a210a-a2ce-401b-be33-f177cc2dadcb','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:INT','Review of Systems:Integumentary','sect','INT','ROS',NULL),('f250691a-35a8-4a8b-bbe1-2233929d5224','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:NEURO','Review of Systems:Neurological','sect','NEURO','ROS',NULL),('f1b16e1e-9187-41c9-9405-44cee48f7c89','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:PSYCH','Review of Systems:Psychiatric','sect','PSYCH','ROS',NULL),('b3a9ff43-05a5-43b1-8416-16064a2bb0cb','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:ENDO','Review of Systems:Endocrine','sect','ENDO','ROS',NULL),('f8598115-cca0-4d36-8443-0c7241fe5150','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:HEM','Review of Systems:Hematologic','sect','HEM','ROS',NULL),('2d27319a-429e-4758-8aab-1e394cf61277','2011-07-21 08:57:07','2011-07-21 08:57:07','ROS:IMM','Review of Systems:Immunologic','sect','IMM','ROS',NULL),('a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd','2011-07-21 08:57:07','2011-07-21 08:57:07','PE','Physical Exam','sect','22029-3','LOINC',NULL),('1de78632-e278-4340-a207-758eb81c2069','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:VIT','Physical Exam:Vital Signs','sect','34565-2','LOINC',NULL),('c0fdb557-5653-453a-adf1-9927991ff6ff','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:GEN','Physical Exam:General','sect','32434-3','LOINC',NULL),('9f34397b-d47e-4920-b1ac-9cbebbe4b2c1','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:HEAD','Physical Exam:Head','sect','10199-8','LOINC',NULL),('4f894e8b-0bee-4836-80fb-09021d74dd58','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:EYE','Physical Exam:Eyes','sect','10197-2','LOINC',NULL),('15948d94-e4ae-4327-a2df-46dbbd4387e2','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:ENT','Physical Exam:Ears & Nose & Mouth & Throat','sect','11393-6','LOINC',NULL),('4f53dd50-68ed-42c9-a090-16f318a82ffc','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:CV','Physical Exam:Cardiovascular system','sect','11421-5','LOINC',NULL),('a394f40e-c4b8-4b46-b70b-fb1796067375','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:RES','Physical Exam:Respiratory system','sect','11412-4','LOINC',NULL),('85cfa119-2556-4e58-a10d-71a52be8a0c3','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:GI','Physical Exam:Gastrointestinal system','sect','11430-6','LOINC',NULL),('90341c54-1087-48ae-84a5-de5e6adf3004','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:MS','Physical Exam:Musculoskeletal system','sect','11410-8','LOINC',NULL),('9499b562-5a81-4e49-92ab-3ce9e281e70a','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:EXT','Physical Exam:Extremities','sect','10196-4','LOINC',NULL),('423faa95-4b5b-4a8a-9a76-15c6dfe08d9a','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:NER','Physical Exam:Nervous system','sect','10202-0','LOINC',NULL),('536dc0f3-9386-4fa8-960c-2d0b1759064d','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:SKIN','Physical Exam:Skin','sect','10206-1','LOINC',NULL),('b7846e81-6110-44e4-8016-9460616c5c37','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:LYMPH','Physical Exam:Lymphatics','sect','32450-9','LOINC',NULL),('986c03c4-8482-4f27-a4be-1abe71c9bdd4','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:GU','Physical Exam:Genitourinary tract','sect','10198-0','LOINC',NULL),('4688d3d6-1cf1-4950-a687-407c5edb1f2c','2011-07-21 08:57:07','2011-07-21 08:57:07','PE:PSYCH','Physical Exam:Psychiatric findings Observed','sect','11451-2','LOINC',NULL),('36148212-bf65-40f1-8dfa-70159a81aa62','2011-07-21 08:57:07','2011-07-21 08:57:07','STU','Studies','sect',NULL,NULL,NULL),('c204aae8-c01d-4b70-8241-29c13383d028','2011-07-21 08:57:07','2011-07-21 08:57:07','AP','Assessment/Plan','sect','51847-2','LOINC',NULL),('590d0c59-169f-4dee-b2c8-1195a72f796d','2011-07-21 08:57:07','2011-07-21 08:57:07','APNAR','Assessment/Plan Narrative','narmd','51847-2','LOINC',NULL),('bbf5cda2-22cc-40c6-858e-d2fe08179e6d','2011-07-21 08:57:07','2011-07-21 08:57:07','PROB','Problem List','sect',NULL,NULL,NULL),('1c291a1a-3c73-470c-8522-141b23adbd19','2011-07-25 13:31:48','2011-07-25 13:31:48','VIT','Vital Signs','sect','34565-2','LOINC',NULL),('2f1f3176-011e-4929-884b-29ea0beb553e','2011-07-25 13:31:48','2011-07-25 13:31:48','VS','Vital Signs','doc',NULL,NULL,NULL),('3581e8bb-9d6f-4c7f-aea1-803e066391e8','2011-08-17 11:52:15','2011-08-17 11:52:15','ML','Medications List','doc',NULL,NULL,NULL),('411328f3-58c2-4761-927d-48e2d709c2dd','2011-08-17 14:06:11','2011-08-17 14:06:11','HO','HO','doc',NULL,NULL,NULL);
/*!40000 ALTER TABLE `dct_document_metadata` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_document_metadata_collection_items`
--

LOCK TABLES `dct_document_metadata_collection_items` WRITE;
/*!40000 ALTER TABLE `dct_document_metadata_collection_items` DISABLE KEYS */;
INSERT INTO `dct_document_metadata_collection_items` VALUES ('1a2633e6-76ef-4e0d-99c7-8712f1a38b71','2011-07-21 08:57:07','2011-07-21 08:57:07','1a2d248b-aa83-496a-a7f3-275e843941a4',1,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('329fba31-2988-4252-98c7-d4a8f2b09804','2011-07-21 08:57:07','2011-07-21 08:57:07','a19addc1-4884-4f58-b7ab-ba579a8e8822',1,'1a2d248b-aa83-496a-a7f3-275e843941a4'),('555ae955-ecab-479b-81c1-c98bdf1d28b8','2011-07-21 08:57:07','2011-07-21 08:57:07','a0751004-b9fb-4f9f-9120-58b4a26d70a3',2,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('545981fa-f1a3-4986-ba35-8936111c3916','2011-07-21 08:57:07','2011-07-21 08:57:07','725041e7-09b1-4457-8e7f-b8b8c30fb41f',1,'a0751004-b9fb-4f9f-9120-58b4a26d70a3'),('7d4044c6-6ee6-4837-91c0-58a015a24e40','2011-07-21 08:57:07','2011-07-21 08:57:07','90fbc3d0-b956-4113-af77-4b3c3c18442a',3,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('37a9dbe4-9c6d-45e6-9b9f-0fc9c2c47431','2011-07-21 08:57:07','2011-07-21 08:57:07','f8160ec9-d25f-4ede-b951-149c19953567',4,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('9de1dde0-4cc6-43bd-8586-c025b9252c8c','2011-07-21 08:57:07','2011-07-21 08:57:07','b013fd9d-0295-44dc-a331-cec863a71d6b',1,'f8160ec9-d25f-4ede-b951-149c19953567'),('aa8ebcad-e312-4ab8-ae38-245b38421ef1','2011-07-21 08:57:07','2011-07-21 08:57:07','9907b478-5b88-4c39-838f-a8555b9ea789',2,'f8160ec9-d25f-4ede-b951-149c19953567'),('6b7ff912-e85c-44e3-8994-7c07d60068df','2011-07-21 08:57:07','2011-07-21 08:57:07','dc53d643-e2b1-43c9-9896-faa1a2b6bdd5',5,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('7ace7817-8f84-48f0-9ed7-613a38140fab','2011-07-21 08:57:07','2011-07-21 08:57:07','371890ec-9bf0-441e-9efb-d64e310fc25b',6,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('ddaa5430-1930-4df2-a1e6-5f9788486b91','2011-07-21 08:57:07','2011-07-21 08:57:07','d254b214-51cc-406d-891a-0af6a2a40ccc',7,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('404c42df-1cfa-45be-b7b4-d1604f616c78','2011-07-21 08:57:07','2011-07-21 08:57:07','76de1601-d081-4a7b-84e0-74f253a0b463',1,'d254b214-51cc-406d-891a-0af6a2a40ccc'),('b3de110d-fdda-48ff-a584-a90cac15cf10','2011-07-21 08:57:07','2011-07-21 08:57:07','15e12575-0590-44a8-8721-2b821610d1cc',2,'d254b214-51cc-406d-891a-0af6a2a40ccc'),('75399374-58b5-462f-89d1-eff91c78540f','2011-07-21 08:57:07','2011-07-21 08:57:07','3bf45ea2-f95e-483a-a943-381865da74fb',8,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('c0fae89b-cf27-443e-a380-fdadc6868118','2011-07-21 08:57:07','2011-07-21 08:57:07','c7031074-11b9-4724-b9d5-49d062e21a28',1,'3bf45ea2-f95e-483a-a943-381865da74fb'),('8976dc62-b9e3-4fb7-a177-6aaa83b4839d','2011-07-21 08:57:07','2011-07-21 08:57:07','08944fd4-a291-4da8-8ef5-e0f5df390434',2,'3bf45ea2-f95e-483a-a943-381865da74fb'),('dea8ffff-67c1-4070-8ef3-ae6c364dcd39','2011-07-21 08:57:07','2011-07-21 08:57:07','4ff5a08b-6202-4cfb-8a08-a0d11ccfdbec',3,'3bf45ea2-f95e-483a-a943-381865da74fb'),('553f1f6b-0d32-4f65-bc0c-70c0dc52167a','2011-07-21 08:57:07','2011-07-21 08:57:07','bd59126b-722d-4136-a7b2-30be6ecb7e73',4,'3bf45ea2-f95e-483a-a943-381865da74fb'),('e8a55b7a-9f28-4bba-91e8-07d40eb3e931','2011-07-21 08:57:07','2011-07-21 08:57:07','bd5a796c-eebb-428e-bd9e-b49cd75578f8',5,'3bf45ea2-f95e-483a-a943-381865da74fb'),('ab859c54-ba1d-4b19-b625-d0cfde2e01c0','2011-07-21 08:57:07','2011-07-21 08:57:07','31f8e578-8f22-4030-b2cb-69cc82aeebea',6,'3bf45ea2-f95e-483a-a943-381865da74fb'),('b7487390-7abf-43d9-a856-e3c4245bcf45','2011-07-21 08:57:07','2011-07-21 08:57:07','dd1f5f3c-0c0f-418c-b833-a7148dfa26dc',7,'3bf45ea2-f95e-483a-a943-381865da74fb'),('33c17658-a420-4ca5-9322-39d8c8eef443','2011-07-21 08:57:07','2011-07-21 08:57:07','7f0fda28-4bed-4c99-a8d8-a4b300e2f582',8,'3bf45ea2-f95e-483a-a943-381865da74fb'),('70333416-c096-4ae3-b8d4-441845d28b9d','2011-07-21 08:57:07','2011-07-21 08:57:07','566a210a-a2ce-401b-be33-f177cc2dadcb',9,'3bf45ea2-f95e-483a-a943-381865da74fb'),('1419f5cb-c662-44fd-9ef1-b662468efabc','2011-07-21 08:57:07','2011-07-21 08:57:07','f250691a-35a8-4a8b-bbe1-2233929d5224',10,'3bf45ea2-f95e-483a-a943-381865da74fb'),('2b5a158d-3e88-4f3e-a3c2-21ecbfef2830','2011-07-21 08:57:07','2011-07-21 08:57:07','f1b16e1e-9187-41c9-9405-44cee48f7c89',11,'3bf45ea2-f95e-483a-a943-381865da74fb'),('5107a4e0-767d-4744-bfd8-352c5d28aa8f','2011-07-21 08:57:07','2011-07-21 08:57:07','b3a9ff43-05a5-43b1-8416-16064a2bb0cb',12,'3bf45ea2-f95e-483a-a943-381865da74fb'),('a173b056-db8c-469f-a0a7-2d7187fd931d','2011-07-21 08:57:07','2011-07-21 08:57:07','f8598115-cca0-4d36-8443-0c7241fe5150',13,'3bf45ea2-f95e-483a-a943-381865da74fb'),('159836a3-0f8d-45b8-b838-5df5bcc16b91','2011-07-21 08:57:07','2011-07-21 08:57:07','2d27319a-429e-4758-8aab-1e394cf61277',14,'3bf45ea2-f95e-483a-a943-381865da74fb'),('48284494-1cb0-4565-adc8-dbd704141c77','2011-07-21 08:57:07','2011-07-21 08:57:07','a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd',9,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('9f3a2ea9-7e4a-4022-a3eb-f14ae7b315ef','2011-07-21 08:57:07','2011-07-21 08:57:07','1de78632-e278-4340-a207-758eb81c2069',1,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('2fdd475d-3132-454e-bee0-c7c66a360fcd','2011-07-21 08:57:07','2011-07-21 08:57:07','c0fdb557-5653-453a-adf1-9927991ff6ff',2,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('8fa5b068-6c84-4dbd-ad58-a2930fe853b1','2011-07-21 08:57:07','2011-07-21 08:57:07','9f34397b-d47e-4920-b1ac-9cbebbe4b2c1',3,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('846067c7-0ddf-422e-bffb-b4c8b4f2920c','2011-07-21 08:57:07','2011-07-21 08:57:07','4f894e8b-0bee-4836-80fb-09021d74dd58',4,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('cf16b644-85bf-493d-bde2-66202519d2d0','2011-07-21 08:57:07','2011-07-21 08:57:07','15948d94-e4ae-4327-a2df-46dbbd4387e2',5,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('20f20f91-0856-492a-98b6-55b898a2e05c','2011-07-21 08:57:07','2011-07-21 08:57:07','4f53dd50-68ed-42c9-a090-16f318a82ffc',6,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('55208666-9546-4d21-b179-1b7d644bcfe1','2011-07-21 08:57:07','2011-07-21 08:57:07','a394f40e-c4b8-4b46-b70b-fb1796067375',7,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('e5c1ef0f-d7fe-443f-8af9-721b6b93022a','2011-07-21 08:57:07','2011-07-21 08:57:07','85cfa119-2556-4e58-a10d-71a52be8a0c3',8,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('04a59fac-b103-4296-b059-d5d75dbf6502','2011-07-21 08:57:07','2011-07-21 08:57:07','90341c54-1087-48ae-84a5-de5e6adf3004',9,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('8c77edb2-4db0-43a8-be81-5dbe53f9cbdc','2011-07-21 08:57:07','2011-07-21 08:57:07','9499b562-5a81-4e49-92ab-3ce9e281e70a',10,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('b89d1a6c-df23-4ed6-96a7-db119b63abd2','2011-07-21 08:57:07','2011-07-21 08:57:07','423faa95-4b5b-4a8a-9a76-15c6dfe08d9a',11,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('4ae7bac0-8adb-4733-936f-cd7225993dd8','2011-07-21 08:57:07','2011-07-21 08:57:07','536dc0f3-9386-4fa8-960c-2d0b1759064d',12,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('76f65b38-006f-47e2-84d9-a6cb6616cf72','2011-07-21 08:57:07','2011-07-21 08:57:07','b7846e81-6110-44e4-8016-9460616c5c37',13,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('6d8506b9-fb86-4c43-9652-9c19f8a01494','2011-07-21 08:57:07','2011-07-21 08:57:07','986c03c4-8482-4f27-a4be-1abe71c9bdd4',14,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('f4026e42-1a22-462f-85ba-59912160a401','2011-07-21 08:57:07','2011-07-21 08:57:07','4688d3d6-1cf1-4950-a687-407c5edb1f2c',15,'a56792b0-e4cb-47e2-a8cb-7bafc9bc8bdd'),('6d5396d4-fe01-47a6-a93c-64f419edbe2a','2011-07-21 08:57:07','2011-07-21 08:57:07','36148212-bf65-40f1-8dfa-70159a81aa62',10,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('397b3822-30bd-4e7a-bac0-15f8192148e7','2011-07-21 08:57:07','2011-07-21 08:57:07','c204aae8-c01d-4b70-8241-29c13383d028',11,'187eb3ef-a44d-4338-98b6-c8ad1f4ede9a'),('13ae0f20-3164-4d0e-b1e6-2a4be02175c3','2011-07-21 08:57:07','2011-07-21 08:57:07','590d0c59-169f-4dee-b2c8-1195a72f796d',1,'c204aae8-c01d-4b70-8241-29c13383d028'),('e671d76a-36a1-4691-9ae5-da20ed4aee0e','2011-07-21 08:57:08','2011-07-21 08:57:08','bbf5cda2-22cc-40c6-858e-d2fe08179e6d',2,'c204aae8-c01d-4b70-8241-29c13383d028'),('dac073eb-ef85-4450-9c36-d28071fff190','2011-07-25 13:36:33','2011-07-25 13:36:33','1c291a1a-3c73-470c-8522-141b23adbd19',1,'2f1f3176-011e-4929-884b-29ea0beb553e'),('982dbf21-7bc9-4418-845e-d15b74e1f1f4','2011-08-17 11:52:15','2011-08-17 11:52:15','dc53d643-e2b1-43c9-9896-faa1a2b6bdd5',1,'3581e8bb-9d6f-4c7f-aea1-803e066391e8'),('60609d3f-d089-4a58-b87d-5d1f91e76e72','2011-08-17 14:06:40','2011-08-17 14:06:40','c204aae8-c01d-4b70-8241-29c13383d028',1,'411328f3-58c2-4761-927d-48e2d709c2dd');
/*!40000 ALTER TABLE `dct_document_metadata_collection_items` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`uuid`),
  KEY `AUTHOR_IDX` (`author`),
  KEY `PATIENT_ID` (`patient_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_documents`
--

LOCK TABLES `dct_documents` WRITE;
/*!40000 ALTER TABLE `dct_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `dct_documents` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_observation_metadata`
--

LOCK TABLES `dct_observation_metadata` WRITE;
/*!40000 ALTER TABLE `dct_observation_metadata` DISABLE KEYS */;
INSERT INTO `dct_observation_metadata` VALUES ('2b89aeeb-8d8e-4fb8-b00d-00afc764b733','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','32434-3','LOINC','Well Developed','normal'),('f914e9e5-1e6a-4ff5-8399-c9c04131dd71','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','32434-3','LOINC','No Acute Distress','normal'),('8955eac6-21cc-491a-b110-6c5b0c87eea7','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','32434-3','LOINC','Ill Appearing','abnormal'),('16f17340-19d5-4c05-9d7e-829334a3e8e9','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','10197-2','LOINC','PERRLA','normal'),('e9943f97-4b99-4346-9db4-f4ac8a20e164','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','10197-2','LOINC','Extraoccular Movements Intact','normal'),('a74bf630-ae8d-4cc1-af3f-fe0da98c2e8d','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','10197-2','LOINC','Normal Eyelids','normal'),('038015ad-4bfd-438e-bca9-e60e8e56d983','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','10197-2','LOINC','Normal Conjunctiva','normal'),('50a38408-590e-418c-99a5-d99050337719','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','11421-5','LOINC','Regular Rate and Rhythm','normal'),('6787ed69-67e3-4d0b-aa70-57f138a9350f','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','11421-5','LOINC','No Murmurs, Rubs or Gallops','normal'),('3cdcff26-7445-4516-b4c5-c45b4032d30c','exam','2011-03-03 17:57:15','2011-03-03 17:57:15','11421-5','LOINC','No Jugular Venous Distention','normal'),('19a493f7-cbae-402e-b0b0-39895e4ef16d','exam','2011-03-11 10:47:05','2011-03-11 10:47:05','11412-4','LOINC','Normal Respiratory Effort','normal'),('dc6963a1-72e9-4383-b010-376e4461c16a','exam','2011-03-11 10:47:05','2011-03-11 10:47:05','11412-4','LOINC','Clear to Auscultation Bilaterally','normal');
/*!40000 ALTER TABLE `dct_observation_metadata` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_statuses`
--

LOCK TABLES `dct_statuses` WRITE;
/*!40000 ALTER TABLE `dct_statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `dct_statuses` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dct_vocab_mappings`
--

LOCK TABLES `dct_vocab_mappings` WRITE;
/*!40000 ALTER TABLE `dct_vocab_mappings` DISABLE KEYS */;
INSERT INTO `dct_vocab_mappings` VALUES ('515f2ec6-d4c6-4fbc-892d-a622caec33','form','2011-03-19 01:14:08','2011-03-19 01:14:08','A3268453','SNOMED','10197-2','LOINC','Conjunctiva normal','normal',1,NULL),('b6c4f001-041b-4b37-9836-ea8b62549c','form','2011-03-19 01:14:55','2011-03-19 01:14:55','A3654338','SNOMED','10197-2','LOINC','Pupils equal, react to light and accommodation','normal',2,NULL),('19d5a112-4c21-47d1-beaf-19c92992d6','form','2011-03-19 16:27:53','2011-03-19 16:27:53','A3288027','SNOMED','32450-9','LOINC','Inguinal lymphadenopathy','abnormal',1,NULL),('f01883df-1427-40fe-8c94-3c15367754','form','2011-03-19 16:09:16','2011-03-19 16:09:16','A3285927','SNOMED','11421-5','LOINC','Normal heart rate','normal',1,NULL),('b0e71808-b14a-4ef0-9298-96e713b615','form','2011-03-20 22:28:53','2011-03-20 22:28:53','A3250814','SNOMED','32434-3','LOINC','Comfortable appearance','normal',3,NULL),('930f571d-6dcd-4fad-8601-11c695298a','form','2011-03-19 16:38:46','2011-03-19 16:38:46','A3264251','SNOMED','32450-9','LOINC','Cervical lymphadenopathy','abnormal',2,NULL),('f0831750-1d50-456e-a87b-2d5474b832','form','2011-03-19 16:44:00','2011-03-19 16:44:00','A3254482','SNOMED','32450-9','LOINC','Axillary lymphadenopathy','abnormal',3,NULL),('2d857ef4-ef5e-4cc2-8584-88ef508779','form','2011-03-19 22:02:39','2011-03-19 22:02:39','A3089208','SNOMED','11430-6','LOINC','Abdominal guarding','abnormal',2,NULL),('db52e18f-479e-4e4f-9226-0ab4317c62','form','2011-03-19 23:11:13','2011-03-19 23:11:13','A2943569','SNOMED','11430-6','LOINC','Rebound tenderness','abnormal',1,NULL),('637d2131-fcbe-41d2-8c12-e50d54440c','form','2011-03-19 23:09:25','2011-03-19 23:09:25','A3235891','SNOMED','11430-6','LOINC','Abdominal tenderness absent','normal',3,NULL),('a30c2bc2-dc03-4b7c-9de9-64aeb32f7f','form','2011-03-20 13:07:53','2011-03-20 13:07:53','A3663461','SNOMED','11421-5','LOINC','Regularity of heart rhythm','normal',2,NULL),('d8d69d83-13ef-400c-84a1-30a53b95aa','form','2011-03-20 13:08:19','2011-03-20 13:08:19','A3238617','SNOMED','11421-5','LOINC','Heart murmur absent','normal',3,NULL),('f70dc65a-d25c-4991-9cc3-511278260f','form','2011-03-20 14:21:32','2011-03-20 14:21:32','A3159669','SNOMED','11393-6','LOINC','Moist oral mucosa','normal',1,NULL),('1c4a4b4f-75b1-43fd-9fe0-7ffb84818f','form','2011-03-20 14:21:48','2011-03-20 14:21:48','A3161834','SNOMED','11393-6','LOINC','Nasal mucosa moist','normal',2,NULL),('01676b3b-e6cd-404b-a510-6bcfba3bfc','form','2011-03-20 14:25:37','2011-03-20 14:25:37','A3219116','SNOMED','10206-1','LOINC','Warm skin','normal',1,NULL),('985798a3-a8cb-43c7-b201-ce6022b3c2','form','2011-03-20 14:26:32','2011-03-20 14:26:32','A2923786','SNOMED','11451-2','LOINC','Appropriate affect','normal',1,NULL),('dc20f847-8044-4361-8da5-da455ec013','form','2011-03-20 14:46:46','2011-03-20 14:46:46','A2971116','SNOMED','11412-4','LOINC','Labored breathing','abnormal',1,NULL),('310bf61c-02ac-4c39-947b-33651fa891','form','2011-03-20 14:48:26','2011-03-20 14:48:26','A3238699','SNOMED','11412-4','LOINC','Wheeze absent','normal',1,NULL),('c0c1a9a9-345d-4594-a670-41e9ba4663','form','2011-03-20 14:49:45','2011-03-20 14:49:45','A3295201','SNOMED','10206-1','LOINC','Skin turgor normal','normal',2,NULL),('2868af97-0052-4757-9cce-6e21ea8c8c','form','2011-03-20 14:53:28','2011-03-20 14:53:28','A2974308','SNOMED','11430-6','LOINC','Normal bowel sounds','normal',1,NULL),('3e9ab6c7-b3e5-440b-bd98-b1787d06dc','form','2011-03-20 14:54:58','2011-03-20 14:54:58','A3295177','SNOMED','11412-4','LOINC','Respiratory rate normal','normal',2,NULL),('f9c5c0e9-1863-452f-b59f-88855e19b4','form','2011-03-20 14:56:45','2011-03-20 14:56:45','A2974330','SNOMED','10202-0','LOINC','Normal tendon reflex','normal',1,NULL),('33ee2108-0619-425c-a9eb-4d96749fb4','form','2011-03-20 14:57:21','2011-03-20 14:57:21','A3290203','SNOMED','10202-0','LOINC','Normal joint position sense','normal',2,NULL),('4298a52d-f8b8-44dd-9251-8e2ae66429','form','2011-03-20 14:58:35','2011-03-20 14:58:35','A3258892','SNOMED','10202-0','LOINC','Normal body position sense','normal',3,NULL),('8ebcca46-0773-45e1-9be9-72ae389da3','form','2011-03-21 11:40:13','2011-03-21 11:40:13','A2880173','SNOMED','GI','ROS','Constipation','abnormal',1,NULL),('6ee56562-cbcf-458d-9957-aa9e0e6170','form','2011-03-21 11:41:30','2011-03-21 11:41:30','A3279544','SNOMED','CON','ROS','Excessive weight gain','abnormal',3,NULL),('ab19c751-805a-4374-acbf-f1aaa55ae1','form','2011-03-21 11:41:41','2011-03-21 11:41:41','A11734218','SNOMED','CON','ROS','Unexplained weight loss','abnormal',2,NULL),('aa63319c-252c-4779-a984-ddd5717edf','form','2011-03-21 11:42:15','2011-03-21 11:42:15','A2881532','SNOMED','CON','ROS','Fatigue','abnormal',1,NULL),('42746936-c816-4dda-be49-9eeef47f85','form','2011-03-21 11:44:00','2011-03-21 11:44:00','A2950437','SNOMED','GU','ROS','Urinary incontinence','abnormal',2,NULL),('9a9b71b8-525e-4f88-b6fb-9104f156a6','form','2011-03-21 11:44:33','2011-03-21 11:44:33','A2873532','SNOMED','GU','ROS','Dysuria','abnormal',1,NULL),('2820e11e-f1e3-4fbe-8a72-bba14f0a07','form','2011-03-21 11:50:45','2011-03-21 11:50:45','A3132042','SNOMED','10196-4','LOINC','Finger clubbing','abnormal',1,NULL),('2e44b46e-b086-415c-a9ba-045677f3bb','form','2011-03-22 12:41:42','2011-03-22 12:41:42','A3105207','SNOMED','RES','ROS','C/O - cough','abnormal',1,NULL),('544c158b-2a2e-4bce-833e-5eab63e5df','form','2011-03-22 12:42:12','2011-03-22 12:42:12','A2880965','SNOMED','RES','ROS','Dyspnea','abnormal',2,NULL),('373a364e-e4bd-467c-af79-ec1509a814','form','2011-03-22 12:42:57','2011-03-22 12:42:57','A2926510','SNOMED','CV','ROS','Chest pain','abnormal',1,NULL),('0403f3e5-a372-44cf-99da-1422893fa7','form','2011-03-22 12:43:22','2011-03-22 12:43:22','A2976257','SNOMED','CV','ROS','Paroxysmal nocturnal dyspnea','abnormal',3,NULL),('6f21e93e-358e-41fd-9660-51d03a8ed3','form','2011-03-22 12:43:33','2011-03-22 12:43:33','A2873568','SNOMED','CV','ROS','Orthopnea','abnormal',4,NULL),('fa2aae71-12c5-424f-860a-6a164cf154','form','2011-03-22 12:43:44','2011-03-22 12:43:44','A2873541','SNOMED','CV','ROS','Palpitations','abnormal',2,NULL),('68a55a3b-19f2-444d-8304-73c84988a2','form','2011-03-22 12:44:03','2011-03-22 12:44:03','A2880625','SNOMED','GI','ROS','Diarrhea','abnormal',2,NULL),('d42c3eba-ed5e-4fd8-a8f0-cfce7bd366','form','2011-03-22 12:45:51','2011-03-22 12:45:51','A2921922','SNOMED','GI','ROS','Abdominal pain','abnormal',3,NULL),('2841711a-a2a8-4c3c-bb9d-6396378752','form','2011-03-22 12:46:21','2011-03-22 12:46:21','A3205077','SNOMED','GI','ROS','Stool flecked with blood','abnormal',4,NULL),('863b8f0b-faf5-432c-8dda-2b10de2930','form','2011-03-22 12:47:30','2011-03-22 12:47:30','A3137149','SNOMED','GI','ROS','Gravel rash','abnormal',7,NULL),('dfe17cf5-6279-4e64-8658-11fd0c3a42','form','2011-07-15 19:07:48','2011-07-15 19:07:48','A3163263','SNOMED','A11740158','SNOMED','No family history diabetes','normal',1,NULL),('7d57d6af-42dc-4e6b-b4a3-383a09b151','form','2011-03-22 12:48:38','2011-03-22 12:48:38','A3071217','SNOMED','INT','ROS','Skin lesion','abnormal',2,NULL),('0557242a-5363-4457-b834-baababb756','form','2011-03-22 12:48:56','2011-03-22 12:48:56','A2873463','SNOMED','INT','ROS','Photosensitivity','abnormal',3,NULL),('9826b8da-2f5f-4705-b7e4-b26b10caf4','form','2011-03-22 12:50:04','2011-03-22 12:50:04','A3158183','SNOMED','32434-3','LOINC','Mentally alert','normal',4,NULL),('4c49cdc8-398e-48bd-a7af-eb0e2abdd8','form','2011-03-22 12:50:22','2011-03-22 12:50:22','A13007689','SNOMED','32434-3','LOINC','Oriented to person, time and place','normal',1,NULL),('a7413189-28d5-4c40-a155-efab8fe48c','form','2011-03-22 12:51:50','2011-03-22 12:51:50','A3277084','SNOMED','11421-5','LOINC','O/E - no gallop rhythm','normal',4,NULL),('828d2d86-d2b8-4c11-b405-948161b2e0','form','2011-03-22 12:52:08','2011-03-22 12:52:08','A3238603','SNOMED','11421-5','LOINC','Pericardial friction rub absent','normal',5,NULL),('7ffb8462-6b15-48a1-b72c-f5660b9081','form','2011-03-23 09:33:28','2011-03-23 09:33:28','A2882197','SNOMED','NEURO','ROS','Headache','abnormal',1,NULL),('450f5ebf-c1f8-4b64-84fb-8413ec123d','form','2011-03-23 09:34:49','2011-03-23 09:34:49','A2876627','SNOMED','MS','ROS','Muscle weakness','abnormal',1,NULL),('870984b8-05c1-441b-b4d2-ed68b451c2','option','2011-04-05 22:18:58','2011-04-05 22:18:58','A3049629','SNOMED','A3210982','SNOMED','Non-smoker','exclusive',1,NULL),('b90624ce-6941-4e31-80f9-fe1f06ab22','option','2011-04-04 16:42:00','2011-04-04 16:42:00','A3302206','SNOMED','A8380263','SNOMED','Allergy to penicillin','multiple',4,NULL),('ee9b2650-358e-4142-9a65-ebc251d8ca','option','2011-04-04 16:44:14','2011-04-04 16:44:14','A6950845','SNOMED','A8380263','SNOMED','No known drug allergies','exclusive',1,NULL),('c5139105-b609-4c4d-b9f1-464628cfdd','option','2011-04-04 17:09:36','2011-04-04 17:09:36','A3247560','SNOMED','A8380263','SNOMED','Sulfasalazine allergy','multiple',8,NULL),('a410b970-201a-42dc-b3dd-f424aa6b1d','option','2011-04-04 17:15:03','2011-04-04 17:15:03','A3247086','SNOMED','A8380263','SNOMED','Iodine allergy','multiple',19,NULL),('41393569-9143-4f61-863d-e182c22fab','option','2011-04-04 17:16:08','2011-04-04 17:16:08','A3246620','SNOMED','A8380263','SNOMED','Codeine allergy','multiple',15,NULL),('afed3d90-5fcc-4540-aae1-f8f21de2a5','option','2011-04-05 22:16:33','2011-04-05 22:16:33','A7876704','SNOMED','A3245941','SNOMED','Denies alcohol abuse','exclusive',1,NULL),('9e9071bc-db53-483e-a38a-194c3ddec4','option','2011-04-05 22:16:41','2011-04-05 22:16:41','A3245944','SNOMED','A3245941','SNOMED','Alcohol intake within recommended sensible limits','exclusive',2,NULL),('8b42691f-2347-48f9-951b-a1f0496f87','option','2011-04-05 22:16:52','2011-04-05 22:16:52','A3238388','SNOMED','A3245941','SNOMED','Alcohol intake above recommended sensible limits','exclusive',3,NULL),('f37e2ee5-afcd-4509-84d1-928a9898e8','option','2011-04-05 22:17:28','2011-04-05 22:17:28','A3094337','SNOMED','A3245941','SNOMED','Alcohol consumption unknown','exclusive',4,NULL),('f4757184-5451-42fb-abf6-4a8377576f','option','2011-04-14 06:12:50','2011-04-14 06:12:50','A3247527','SNOMED','A7873398','SNOMED','Shellfish allergy','multiple',1,NULL),('0b3641fc-f811-48e0-b47f-f0db364c57','option','2011-04-14 06:12:19','2011-04-14 06:12:19','A13357721','SNOMED','A7873398','SNOMED','No known food allergy','exclusive',1,NULL),('c4006fb9-c5c0-423a-b3ff-74b37fe7d2','option','2011-04-14 05:08:23','2011-04-14 05:08:23','A3006083','SNOMED','A3210982','SNOMED','Chews tobacco','multiple',4,NULL),('8cc27da5-306d-4946-90ab-37983e5cf8','option','2011-04-14 05:05:02','2011-04-14 05:05:02','A3020292','SNOMED','A3210982','SNOMED','Ex-smoker','exclusive',2,NULL),('77b7c090-3165-4e75-a94c-3998f2856b','option','2011-04-14 05:05:16','2011-04-14 05:05:16','A3007445','SNOMED','A3210982','SNOMED','Cigarette smoker','multiple',1,NULL),('b2e196cd-b0f3-48f3-9f65-f1b84fe9af','option','2011-04-14 05:05:43','2011-04-14 05:05:43','A3007438','SNOMED','A3210982','SNOMED','Cigar smoker','multiple',3,NULL),('43e669d7-7082-4d64-977e-123a4f5ad5','option','2011-04-14 05:06:04','2011-04-14 05:06:04','A3058305','SNOMED','A3210982','SNOMED','Pipe smoker','multiple',2,NULL),('3dd565e6-7995-4cec-8de5-cbb0afd626','option','2011-04-14 06:13:10','2011-04-14 06:13:10','A3302201','SNOMED','A7873398','SNOMED','Allergy to eggs','multiple',2,NULL),('0fbed161-f974-4cec-b422-69a8cb3a71','option','2011-04-14 06:13:22','2011-04-14 06:13:22','A3302204','SNOMED','A7873398','SNOMED','Allergy to nuts','multiple',3,NULL),('637e92a5-461d-4f66-972b-24e6eb8fec','option','2011-04-14 06:13:37','2011-04-14 06:13:37','A9410064','SNOMED','A7873398','SNOMED','Allergy to wheat','multiple',4,NULL),('b27dd071-3891-45c4-8139-5e63c4fb4e','option','2011-04-14 06:16:11','2011-04-14 06:16:11','A3247599','SNOMED','A8380263','SNOMED','Tetracycline allergy','multiple',14,NULL),('3d6671c3-5860-4e92-9237-d71658ba20','option','2011-04-14 06:17:02','2011-04-14 06:17:02','A3246283','SNOMED','A8380263','SNOMED','Non-steroidal anti-inflammatory drug allergy','multiple',16,NULL),('5339e8f6-c745-4dae-b60a-439fcb7a0d','option','2011-04-14 06:17:54','2011-04-14 06:17:54','A3247400','SNOMED','A8380263','SNOMED','Phenytoin allergy','multiple',18,NULL),('8836b798-8f6d-411d-9586-423651f67e','option','2011-04-14 06:18:05','2011-04-14 06:18:05','A3246486','SNOMED','A8380263','SNOMED','Carbamazepine allergy','multiple',17,NULL),('9a63fe50-3dac-4274-a05d-099c197bd7','option','2011-04-15 10:28:37','2011-04-15 10:28:37','A3313267','SNOMED','A3210982','SNOMED','Trivial cigarette smoker (less than one cigarette/day)','multiple',5,NULL),('110bd3df-e981-49c7-b591-c6997a489d','comp','2011-04-26 16:17:48','2011-04-26 16:17:48','9279-1','LOINC','34565-2','LOINC','Respiratory rate','quantitative',2,'NRat'),('819ce252-835d-4f46-8c88-f33fd5de1a','comp','2011-04-26 16:18:55','2011-04-26 16:18:55','8480-6','LOINC','34565-2','LOINC','Systolic blood pressure','quantitative',3,'Pres'),('2062589e-9b56-4108-a6f6-d481954f53','comp','2011-04-26 16:19:17','2011-04-26 16:19:17','8462-4','LOINC','34565-2','LOINC','Diastolic blood pressure','quantitative',4,'Pres'),('e11f9ac0-798b-455f-9aa3-bad66629ca','comp','2011-04-26 16:19:52','2011-04-26 16:19:52','8310-5','LOINC','34565-2','LOINC','Body temperature','quantitative',5,'Temp'),('e47d2cc5-6d41-41ed-8597-7a73debaa3','comp','2011-04-26 16:20:39','2011-04-26 16:20:39','8302-2','LOINC','34565-2','LOINC','Body height','quantitative',6,'Len'),('4e65b123-1851-4341-9b09-4c8ecd4cf1','comp','2011-04-26 16:16:31','2011-04-26 16:16:31','8867-4','LOINC','34565-2','LOINC','Heart rate','quantitative',1,'NRat'),('575bc926-56ea-46b7-ae0f-9cd7bc69ef','comp','2011-04-26 16:20:55','2011-04-26 16:20:55','3141-9','LOINC','34565-2','LOINC','Body weight Measured','quantitative',7,'Mass'),('f7d3dd4e-423a-4e32-b3ad-5fd99abd47','comp','2011-04-27 13:00:54','2011-04-27 13:00:54','39156-5','LOINC','34565-2','LOINC','Body mass index (BMI) [Ratio]','quantitative',8,'Ratio'),('aa896996-f6fe-45d7-9ebf-e73fe25c9c','form','2011-07-15 14:32:05','2011-07-15 14:32:05','A2892436','SNOMED','EYE','ROS','Subjective visual disturbance','abnormal',2,NULL),('33d5282a-24c7-472f-aba1-bb01fc514f','form','2011-07-15 14:32:30','2011-07-15 14:32:30','A3140144','SNOMED','ENT','ROS','Has a sore throat','abnormal',1,NULL),('0d7fd95a-24ac-4918-9b0e-20b8c909fe','form','2011-07-15 14:33:11','2011-07-15 14:33:11','A3105307','SNOMED','INT','ROS','C/O: a rash','abnormal',1,NULL),('ef664e1c-57ed-4e9e-9817-3d1531de8a','form','2011-07-15 19:07:57','2011-07-15 19:07:57','A15162971','SNOMED','A11740158','SNOMED','Family history of diabetes mellitus type 1','abnormal',1,NULL),('7324773a-9515-4336-a39e-e9f74779c0','form','2011-07-15 19:07:59','2011-07-15 19:07:59','A15099685','SNOMED','A11740158','SNOMED','Family history of diabetes mellitus type 2','abnormal',2,NULL),('00f400f8-780c-4e53-8018-f9734c0beb','form','2011-07-15 19:08:54','2011-07-15 19:08:54','A2964616','SNOMED','A11740158','SNOMED','Family history of ischemic heart disease','abnormal',3,NULL),('7290ad7e-be16-442a-a369-d09acbc963','form','2011-07-15 19:08:55','2011-07-15 19:08:55','A15144555','SNOMED','A11740158','SNOMED','Family history of congestive heart failure','abnormal',5,NULL),('59f07d08-7cc6-4345-a38c-b75c5916a1','form','2011-07-15 19:08:56','2011-07-15 19:08:56','A15145190','SNOMED','A11740158','SNOMED','Family history of conduction disorder of the heart','abnormal',7,NULL),('b5dd9a4d-ba63-4d02-97d0-7d741947c8','form','2011-07-15 19:09:00','2011-07-15 19:09:00','A15118789','SNOMED','A11740158','SNOMED','Family history of heart failure','abnormal',8,NULL),('2f2c15ec-7328-495f-8db3-50fa722332','form','2011-08-29 18:33:19','2011-08-29 18:33:19','10197-2','LOINC','10197-2','LOINC','Physical findings of Eye','text',1,'Find'),('8b5f0d87-6bd7-4375-b69b-050c915482','form','2011-08-29 18:33:38','2011-08-29 18:33:38','11393-6','LOINC','11393-6','LOINC','Physical findings of Ears & Nose & Mouth & Throat','text',1,'Find'),('2f9aa435-654a-4977-abb4-dba7d8594a','form','2011-08-29 18:32:45','2011-08-29 18:32:45','10199-8','LOINC','10199-8','LOINC','Physical findings of Head','text',1,'Find'),('663e3fe1-512e-48fc-9802-48611e476f','form','2011-08-29 18:32:15','2011-08-29 18:32:15','34565-2','LOINC','34565-2','LOINC','Vital signs, weight & height panel','text',1,'-'),('df33a6a1-6f9d-44d6-9c0a-efc340fc60','form','2011-08-29 18:16:59','2011-08-29 18:16:59','32434-3','LOINC','32434-3','LOINC','General appearance','text',1,'Find'),('b3ac6ff7-931b-4306-a0cf-04b542c59d','form','2011-07-15 19:09:48','2011-07-15 19:09:48','A3280933','SNOMED','A11740158','SNOMED','Family history of neoplasm','abnormal',13,NULL),('2908cf82-eae5-4494-9bd2-fbbbfbd202','form','2011-08-29 18:34:00','2011-08-29 18:34:00','11421-5','LOINC','11421-5','LOINC','Physical findings of Cardiovascular system','text',1,'Find'),('9c33e697-114b-4301-bd55-6656d27a23','form','2011-08-29 18:34:13','2011-08-29 18:34:13','11412-4','LOINC','11412-4','LOINC','Physical findings of Respiratory system','text',1,'Find'),('bdcbc507-f5ae-4d38-bb49-d9e4a336a5','form','2011-08-29 18:34:37','2011-08-29 18:34:37','11430-6','LOINC','11430-6','LOINC','Physical findings of Gastrointestinal system','text',1,'Find'),('b9091b0d-9c9b-48a9-bf0d-a97cea2f4c','form','2011-08-29 18:35:01','2011-08-29 18:35:01','11410-8','LOINC','11410-8','LOINC','Physical findings of Musculoskeletal system','text',1,'Find'),('b6dcc33d-591c-4444-9a1b-807cbed6dd','form','2011-08-29 18:35:15','2011-08-29 18:35:15','10196-4','LOINC','10196-4','LOINC','Physical findings of Extremities','text',1,'Find'),('2f123d95-21b4-4caf-a0d1-22d5496f7b','form','2011-08-29 18:35:29','2011-08-29 18:35:29','10202-0','LOINC','10202-0','LOINC','Physical findings of Nervous system','text',1,'Find'),('7c66ce16-a7a4-4640-a54d-8940aa0fc6','form','2011-08-29 18:35:42','2011-08-29 18:35:42','10206-1','LOINC','10206-1','LOINC','Physical findings of Skin','text',1,'Find'),('52d66505-2289-4c07-8d00-e6a9e9cfb5','form','2011-08-29 18:37:13','2011-08-29 18:37:13','10198-0','LOINC','10198-0','LOINC','Physical findings of Genitourinary tract','text',1,'Find'),('9edb5735-104a-4857-85f8-204ddb5a67','form','2011-08-29 18:37:43','2011-08-29 18:37:43','11451-2','LOINC','11451-2','LOINC','Psychiatric findings','text',1,'Find');
/*!40000 ALTER TABLE `dct_vocab_mappings` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `dct_vocab_ordering`
--

LOCK TABLES `dct_vocab_ordering` WRITE;
/*!40000 ALTER TABLE `dct_vocab_ordering` DISABLE KEYS */;
INSERT INTO `dct_vocab_ordering` VALUES ('abnormal',120),('exclusive',210),('multiple',220),('normal',110),('quantitative',310),('text',320);
/*!40000 ALTER TABLE `dct_vocab_ordering` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-29 22:23:51
