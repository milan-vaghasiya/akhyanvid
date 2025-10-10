ALTER TABLE `sq_master` ADD `project_type` INT NOT NULL DEFAULT '1' COMMENT '1=Automation ,2 =Theater' AFTER `trans_number`;
ALTER TABLE `sq_master` ADD `project_id` INT NOT NULL DEFAULT '0' AFTER `id`;

UPDATE `sub_menu_master` SET `sub_menu_name` = 'Project', `sub_controller_name` = 'project', `vou_name_long` = 'project', `vou_name_short` = 'PJ', `vou_prefix` = 'PJ/' WHERE `sub_menu_master`.`id` = 24 ;

INSERT INTO `sub_menu_master` (`id`, `menu_type`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`) VALUES (NULL, '1', '4', 'icon-Record', 'Work Progress', 'workProgress', '7', '0', '0', '0', NULL, '0,0,0', '', '', '0', '', '0', '2021-06-23 15:40:25', '0', '2021-06-23 15:40:25', '0');

INSERT INTO `sub_menu_master` (`id`, `menu_type`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`) VALUES (NULL, '1', '4', 'icon-Record', 'Work Instructions', 'workInstructions', '1', '0', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '1', '2025-01-30 18:08:55', '1', '2025-01-30 18:08:55', '0');

ALTER TABLE `work_instructions` CHANGE `title` `notes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

INSERT INTO `sub_menu_master` (`id`, `menu_type`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`) VALUES (NULL, '1', '5', 'icon-Record', 'Customer Complaints', 'customerComplaints', '7', '0', '0', '0', NULL, '0,0,0', '', '', '0', '', '0', '2021-06-23 15:40:25', '0', '2021-06-23 15:40:25', '0');

ALTER TABLE `project_info` ADD `wi_id` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Work Instruction Ids' AFTER `incharge_ids`;

INSERT INTO `sub_menu_master` (`id`, `menu_type`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`) VALUES (NULL, '1', '1', 'icon-Record', 'Project Tracking', 'reports/salesReport/projectTracking', '7', '1', '0', '0', NULL, '0,0,0', '', '', '1', '', '0', '2021-06-23 08:40:25', '0', '2021-06-23 08:40:25', '0');
INSERT INTO `sub_menu_master` (`id`, `menu_type`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`) VALUES (NULL, '1', '2', 'icon-Record', 'Task Manger', 'taskManager', '2', '0', '0', '0', NULL, '0,0,0', NULL, NULL, '0', NULL, '0', '2021-06-23 03:10:25', '0', '2021-06-23 03:10:25', '0');

INSERT INTO `sub_menu_master` (`id`, `menu_type`, `sub_menu_seq`, `sub_menu_icon`, `sub_menu_name`, `sub_controller_name`, `menu_id`, `is_report`, `is_approve_req`, `is_system`, `report_id`, `notify_on`, `vou_name_long`, `vou_name_short`, `auto_start_no`, `vou_prefix`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_delete`) VALUES (NULL, '1', '1', 'icon-Record', 'Task Report', 'reports/salesReport/taskReport', '2', '1', '0', '0', NULL, '0,0,0', '', '', '1', '', '0', '2021-06-23 08:40:25', '0', '2021-06-23 08:40:25', '0');

-- 08-10-2025 --
ALTER TABLE `customer_complaint` ADD `voice_note` TEXT NULL DEFAULT NULL AFTER `complaint_file`;

-- 09-10-2025 --
CREATE TABLE `dashboard_widget` (
  `id` int(11) NOT NULL,
  `widget_name` varchar(50) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Data ,2 = States , 3 = Chart',
  `remark` text DEFAULT NULL,
  `sys_class` char(4) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `dashboard_widget`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `is_delete` (`is_delete`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dashboard_widget`
--
ALTER TABLE `dashboard_widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;


CREATE TABLE `dashboard_permission` (
  `id` int(11) NOT NULL,
  `widget_id` int(11) NOT NULL DEFAULT 0,
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `is_read` tinyint(4) NOT NULL DEFAULT 0,
  `sys_class` char(4) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `dashboard_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `widget_id` (`widget_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `is_delete` (`is_delete`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dashboard_permission`
--
ALTER TABLE `dashboard_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

-- 10-10-2025 --
INSERT INTO `dashboard_widget` (`id`, `widget_name`, `type`, `remark`, `sys_class`, `updated_at`, `created_by`, `created_at`, `updated_by`, `is_delete`) VALUES (NULL, 'Pending Quotation', '1', NULL, 'PENQ', current_timestamp(), '0', current_timestamp(), '0', '0'), (NULL, 'On Going Projects', '1', NULL, 'ONGO', current_timestamp(), '0', current_timestamp(), '0', '0'), (NULL, 'Pending Services', '1', NULL, 'PENS', current_timestamp(), '0', current_timestamp(), '0', '0'), (NULL, 'Pending Complaint', '1', NULL, 'PENC', current_timestamp(), '0', current_timestamp(), '0', '0'), (NULL, 'Pending Task', '1', NULL, 'PENT', current_timestamp(), '0', current_timestamp(), '0', '0');

INSERT INTO `dashboard_permission` (`id`, `widget_id`, `emp_id`, `is_read`, `sys_class`, `updated_at`, `updated_by`, `created_at`, `created_by`, `is_delete`) VALUES (NULL, '1', '1', '1', 'PENQ', current_timestamp(), '0', current_timestamp(), '1', '0'), (NULL, '2', '1', '1', 'ONGO', current_timestamp(), '0', current_timestamp(), '1', '0'), (NULL, '3', '1', '1', 'PENS', current_timestamp(), '0', current_timestamp(), '1', '0'), (NULL, '4', '1', '1', 'PENC', current_timestamp(), '0', current_timestamp(), '1', '0'), (NULL, '5', '1', '1', 'PENT', current_timestamp(), '0', current_timestamp(), '1', '0');