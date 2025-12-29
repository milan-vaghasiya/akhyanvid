-- 27-12-2025 --
ALTER TABLE `stock_trans` ADD `batch_no` VARCHAR(100) NOT NULL DEFAULT 'GENERAL' AFTER `location_id`;
ALTER TABLE `grn_trans` ADD `batch_no` VARCHAR(100) NULL DEFAULT NULL AFTER `price`;

ALTER TABLE stock_trans CONVERT TO CHARACTER SET latin1 COLLATE latin1_general_ci;

-- 29-12-2025 --
ALTER TABLE `issue_register` ADD `batch_no` VARCHAR(20) NULL DEFAULT NULL AFTER `issue_number`;