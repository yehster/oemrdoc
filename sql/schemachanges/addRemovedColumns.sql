ALTER TABLE `openemr`.`dct_documents` ADD COLUMN `removed` DATETIME NULL  AFTER `encounter_id` , ADD COLUMN `removedBy` VARCHAR(255) NULL  AFTER `removed` ;