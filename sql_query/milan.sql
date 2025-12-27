-- 27-12-2025 --
-- batch_history table created --
ALTER TABLE `stock_trans` ADD `batch_no` VARCHAR(100) NOT NULL DEFAULT 'GENERAL' AFTER `location_id`;
ALTER TABLE `grn_trans` ADD `batch_no` VARCHAR(100) NULL DEFAULT NULL AFTER `price`;

ALTER TABLE stock_trans CONVERT TO CHARACTER SET latin1 COLLATE latin1_general_ci;
ALTER TABLE batch_history CONVERT TO CHARACTER SET latin1 COLLATE latin1_general_ci;
