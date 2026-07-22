-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 22, 2026 at 11:14 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hindustansystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_accounts`
--

DROP TABLE IF EXISTS `hindustansystem_accounts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_accounts_system_id_code_unique` (`system_id`,`code`),
  KEY `hindustansystem_accounts_parent_id_foreign` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_accounts`
--

INSERT INTO `hindustansystem_accounts` (`id`, `system_id`, `code`, `name`, `type`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'BRK-ACC-01', 'Broker Commissions Payable', 'liability', NULL, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(2, 1, 'PRT-ACC-01', 'Basheer Capital', 'liability', NULL, 1, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(3, 1, 'PRT-ACC-02', 'Pavoor Capital', 'liability', NULL, 1, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(5, 1, 'SUP-ACC-0003', 'GANESH (CEMENT) (Payable)', 'Liability', NULL, 1, '2026-07-16 00:55:23', '2026-07-16 00:55:23'),
(6, 1, 'SUP-ACC-0005', 'RAMESH (Payable)', 'Liability', NULL, 1, '2026-07-16 01:01:15', '2026-07-16 01:01:15'),
(7, 1, 'BANK-KAR-213', 'Karnataka Bank 213 Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(8, 1, 'CASH-HAND', 'Cash-in-Hand', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(9, 1, 'EXP-ADV', 'Advertisement Expense Payable', 'Expense', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(10, 1, 'EXP-SITE', 'Site Expenses', 'Expense', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(11, 1, 'EXP-SAL', 'Salary Payable', 'Expense', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(12, 1, 'INC-SALES', 'Flat Sales Revenue', 'Income', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(13, 1, 'BANK-ICICIBAN-8', 'ICICI Bank Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(14, 1, 'BANK-INDUSIND-9', 'INDUS IND BANK Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(15, 1, 'BANK-PNB-10', 'PNB Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(16, 1, 'BANK-SBI-11', 'SBI Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(17, 1, 'BANK-FEDERAL-12', 'FEDERAL Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(18, 1, 'BANK-HDFC-13', 'HDFC Account', 'Asset', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(19, 1, 'CUST-REC-4', 'Koval Ahmed Haji m (Receivable)', 'Liability', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(20, 1, 'CUST-REC-5', 'Vijayan (Receivable)', 'Liability', NULL, 1, '2026-07-16 04:43:01', '2026-07-16 04:43:01'),
(21, 1, 'CUST-REC-8', 'Athira (Receivable)', 'Liability', NULL, 1, '2026-07-17 04:18:34', '2026-07-17 04:18:34'),
(22, 1, 'CUST-REC-9', 'Aradhya (Receivable)', 'Liability', NULL, 1, '2026-07-17 04:18:34', '2026-07-17 04:18:34'),
(23, 1, 'LOAN-LN1234235', 'Loan Account - Union Bank (LN1234235)', 'liability', NULL, 1, '2026-07-17 05:51:21', '2026-07-17 05:51:21'),
(24, 1, 'BANK-UNIONBAN-14', 'Union Bank Account', 'Asset', NULL, 1, '2026-07-20 00:01:48', '2026-07-20 00:01:48'),
(25, 1, 'BRK-493C18', 'Nandhana Commission Payable Account', 'liability', NULL, 1, '2026-07-20 00:29:32', '2026-07-20 00:29:32'),
(26, 1, 'LOAN-LN32145766587', 'Loan Account - Union Bank (LN32145766587)', 'liability', NULL, 1, '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(27, 1, 'LOAN-LN80976', 'Loan Account - INDUS IND BANK (LN80976)', 'liability', NULL, 1, '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(28, 1, 'EXP-LOAN-INT', 'Bank Loan Interest Expense', 'expense', NULL, 1, '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(29, 1, 'LOAN-LN3423', 'Loan Account - ICICI Bank (LN3423)', 'liability', NULL, 1, '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(30, 1, 'LOAN-LN809', 'Loan Account - IUB (LN809)', 'liability', NULL, 1, '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(31, 1, 'BANK-IUB-15', 'IUB Account', 'Asset', NULL, 1, '2026-07-20 01:43:07', '2026-07-20 01:43:07'),
(32, 1, 'LOAN-LN23232', 'Loan Account - IUB (LN23232)', 'liability', NULL, 1, '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(33, 1, 'BRK-BF4F8D', 'test Commission Payable Account', 'liability', NULL, 1, '2026-07-21 04:28:26', '2026-07-21 04:28:26');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_activity_logs`
--

DROP TABLE IF EXISTS `hindustansystem_activity_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_activity_logs_user_id_foreign` (`user_id`),
  KEY `hindustansystem_activity_logs_system_id_foreign` (`system_id`),
  KEY `hindustansystem_activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_activity_logs`
--

INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, NULL, 'System Booted', NULL, NULL, 'System initialized and default seed data populated.', '127.0.0.1', 'Symfony', '2026-07-06 03:53:49'),
(2, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 4, 'Unit B2 transitioned from \'available\' to \'blocked\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 03:42:34'),
(3, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 4, 'Unit B2 transitioned from \'blocked\' to \'booked\'. Reason: Booked under Booking #BK-740030FF', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 03:42:51'),
(4, 1, 1, 'booking.created', 'App\\Models\\Booking', 2, 'Created Booking #BK-740030FF for customer Neha Kapoor on Unit B2 (₹5,017,040.00).', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 03:42:51'),
(5, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 4, 'Unit  transitioned from \'booked\' to \'sold\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 03:50:17'),
(6, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 3, 'Unit  transitioned from \'available\' to \'blocked\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 03:57:12'),
(7, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 6, 'Unit  transitioned from \'available\' to \'blocked\'. Reason: Temporarily blocked during booking process', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 04:08:17'),
(8, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 6, 'Unit  transitioned from \'blocked\' to \'booked\'. Reason: Booked under Booking #BK-8DD17A3E', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 04:08:17'),
(9, 1, 1, 'booking.created', 'App\\Models\\Booking', 3, 'Created Booking #BK-8DD17A3E for customer Neha Kapoor on Unit D9 (₹160,685,479.48).', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 04:08:17'),
(10, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 3, 'Unit  transitioned from \'blocked\' to \'available\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-07 04:12:03'),
(11, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 3, 'Unit D66 transitioned from \'available\' to \'blocked\'. Reason: Temporarily blocked during booking process', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-08 07:05:54'),
(12, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 3, 'Unit D66 transitioned from \'blocked\' to \'booked\'. Reason: Booked under Booking #BK-6F8BA391', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-08 07:05:54'),
(13, 1, 1, 'booking.created', 'App\\Models\\Booking', 4, 'Created Booking #BK-6F8BA391 for customer Neha Kapoor on Unit D66 (₹4,123,497,000.00).', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-08 07:05:54'),
(14, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 42, 'Unit 2 transitioned from \'sold\' to \'available\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-10 00:15:28'),
(15, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 46, 'Unit 6 transitioned from \'available\' to \'blocked\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-10 03:32:26'),
(16, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 46, 'Unit 6 transitioned from \'blocked\' to \'available\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-10 03:32:29'),
(17, 1, 1, 'broker.updated', NULL, NULL, 'Updated broker \'Apex Realty Brokers\' details. Commission changed from 2.5% to 2%.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-10 04:15:52'),
(18, 1, 1, 'broker.updated', NULL, NULL, 'Updated broker \'Metro Homes Agents\' details. Commission changed from 1.75% to 1.5%.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-10 04:15:59'),
(19, 1, 1, 'broker.created', NULL, NULL, 'Registered new broker \'Nandhana\' with default commission of 2%. Linked ledger account: BRK-493C18.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-20 00:29:32'),
(20, 1, 1, 'broker.created', NULL, NULL, 'Registered new broker \'test\' with default commission of 2%. Linked ledger account: BRK-BF4F8D.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 04:28:26'),
(21, 1, 1, 'broker.deleted', NULL, NULL, 'Deleted broker \'test\'.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 04:28:38'),
(22, 1, 1, 'broker.payout', NULL, NULL, 'Bulk commission payout across 1 deal(s) to broker \'Nandhana\'.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 06:58:07'),
(23, 1, 1, 'broker.payout', NULL, NULL, 'Bulk commission payout across 2 deal(s) to broker \'Metro Homes Agents\'.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 01:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approvals`
--

DROP TABLE IF EXISTS `hindustansystem_approvals`;
CREATE TABLE IF NOT EXISTS `hindustansystem_approvals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `approvable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvable_id` bigint UNSIGNED DEFAULT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_approvals_approvable_type_approvable_id_index` (`approvable_type`,`approvable_id`),
  KEY `hindustansystem_approvals_requested_by_foreign` (`requested_by`),
  KEY `hindustansystem_approvals_approved_by_foreign` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approval_requests`
--

DROP TABLE IF EXISTS `hindustansystem_approval_requests`;
CREATE TABLE IF NOT EXISTS `hindustansystem_approval_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `requester_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approval_rules`
--

DROP TABLE IF EXISTS `hindustansystem_approval_rules`;
CREATE TABLE IF NOT EXISTS `hindustansystem_approval_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_role` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `threshold_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_approval_rules`
--

INSERT INTO `hindustansystem_approval_rules` (`id`, `module`, `min_role`, `threshold_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'discount', 'Owner', 100000.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_banks`
--

DROP TABLE IF EXISTS `hindustansystem_banks`;
CREATE TABLE IF NOT EXISTS `hindustansystem_banks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_banks`
--

INSERT INTO `hindustansystem_banks` (`id`, `bank_name`, `ifsc_code`, `status`, `created_at`, `updated_at`) VALUES
(8, 'ICICI Bank', 'ICICI1243212', 'active', '2026-07-15 06:29:40', '2026-07-15 06:29:40'),
(9, 'INDUS IND BANK', 'IND2376544', 'active', '2026-07-15 06:30:08', '2026-07-15 06:30:08'),
(10, 'PNB', 'PNB218907', 'active', '2026-07-15 06:30:27', '2026-07-15 06:30:27'),
(11, 'SBI', 'SBI128657', 'active', '2026-07-15 06:30:40', '2026-07-15 06:30:40'),
(12, 'FEDERAL', 'FDRL3289090', 'active', '2026-07-15 06:30:53', '2026-07-15 06:30:53'),
(13, 'HDFC', 'HDFC87564', 'active', '2026-07-15 06:31:12', '2026-07-15 06:31:12'),
(14, 'Union Bank', 'U2312334000', 'active', '2026-07-17 05:50:07', '2026-07-17 05:50:07'),
(15, 'IUB', 'IUB12321', 'active', '2026-07-20 01:42:11', '2026-07-20 01:42:11');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bills`
--

DROP TABLE IF EXISTS `hindustansystem_bills`;
CREATE TABLE IF NOT EXISTS `hindustansystem_bills` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `payee_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `bill_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_of_supply` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_amount` decimal(15,2) NOT NULL,
  `final_amount` decimal(15,2) NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_approval',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_bills_system_id_bill_number_unique` (`system_id`,`bill_number`),
  KEY `hindustansystem_bills_payee_id_foreign` (`payee_id`),
  KEY `hindustansystem_bills_project_id_foreign` (`project_id`),
  KEY `hindustansystem_bills_approved_by_foreign` (`approved_by`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_bills`
--

INSERT INTO `hindustansystem_bills` (`id`, `system_id`, `payee_id`, `project_id`, `bill_number`, `bill_type`, `payment_terms`, `place_of_supply`, `expense_head`, `bill_file`, `bill_amount`, `final_amount`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, 'G12', NULL, NULL, NULL, NULL, NULL, 10000.00, 10200.00, 'approved_unpaid', NULL, NULL, '2026-07-16 01:27:05', '2026-07-16 01:27:05'),
(2, 1, 5, 1, 'BN755376/76D', 'Labor Works', '30 Days', 'Kerala (32)', 'Cement', 'bills/slGWTBy6QfW1NLNRFHS3N8SyYfHaLHfZafqrvxwB.jpg', 100000.00, 100000.00, 'paid', NULL, NULL, '2026-07-17 00:31:47', '2026-07-17 01:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bill_payments`
--

DROP TABLE IF EXISTS `hindustansystem_bill_payments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_bill_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `bill_id` bigint UNSIGNED DEFAULT NULL,
  `payee_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_bill_payments_system_id_foreign` (`system_id`),
  KEY `hindustansystem_bill_payments_voucher_id_foreign` (`voucher_id`),
  KEY `bp_bill_fk` (`bill_id`),
  KEY `bp_payee_fk` (`payee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_bill_payments`
--

INSERT INTO `hindustansystem_bill_payments` (`id`, `system_id`, `bill_id`, `payee_id`, `amount`, `date`, `voucher_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 5, 50000.00, '2026-07-10', 3, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(2, 1, 2, 5, 50000.00, '2026-07-10', 4, '2026-07-17 01:22:02', '2026-07-17 01:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bookings`
--

DROP TABLE IF EXISTS `hindustansystem_bookings`;
CREATE TABLE IF NOT EXISTS `hindustansystem_bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `sales_executive_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `broker_id` bigint UNSIGNED DEFAULT NULL,
  `sale_rate_per_sqft` decimal(15,2) DEFAULT NULL,
  `gst_behavior` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_bookings_booking_number_unique` (`booking_number`),
  KEY `hindustansystem_bookings_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_bookings_project_id_foreign` (`project_id`),
  KEY `hindustansystem_bookings_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_bookings_sales_executive_id_foreign` (`sales_executive_id`),
  KEY `hindustansystem_bookings_broker_id_foreign` (`broker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_brokerages`
--

DROP TABLE IF EXISTS `hindustansystem_brokerages`;
CREATE TABLE IF NOT EXISTS `hindustansystem_brokerages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `sale_unit_id` bigint UNSIGNED DEFAULT NULL,
  `broker_id` bigint UNSIGNED NOT NULL,
  `commission_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `commission_percent` decimal(5,2) DEFAULT NULL,
  `commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_brokerages_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_brokerages_broker_id_foreign` (`broker_id`),
  KEY `hindustansystem_brokerages_sale_unit_id_foreign` (`sale_unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokerages`
--

INSERT INTO `hindustansystem_brokerages` (`id`, `sale_id`, `sale_unit_id`, `broker_id`, `commission_type`, `commission_percent`, `commission_amount`, `paid_amount`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'fixed', NULL, 10000.00, 0.00, 'paid', 'Auto-created at sale', '2026-07-09 23:30:21', '2026-07-09 23:30:21'),
(2, 13, NULL, 2, 'percentage', 2.00, 71000.00, 71000.00, 'paid', 'Auto-created at sale', '2026-07-10 04:05:55', '2026-07-22 01:23:30'),
(3, 21, NULL, 3, 'percentage', 7.50, 30000.00, 30000.00, 'paid', NULL, '2026-07-20 02:04:45', '2026-07-21 06:58:07'),
(4, 22, NULL, 2, 'percentage', 1.50, 6000.00, 6000.00, 'paid', NULL, '2026-07-20 06:31:36', '2026-07-22 01:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_brokers`
--

DROP TABLE IF EXISTS `hindustansystem_brokers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_brokers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_commission_pct` decimal(5,2) NOT NULL,
  `linked_account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_brokers_system_id_foreign` (`system_id`),
  KEY `hindustansystem_brokers_linked_account_id_foreign` (`linked_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokers`
--

INSERT INTO `hindustansystem_brokers` (`id`, `system_id`, `name`, `default_commission_pct`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Apex Realty Brokers', 2.00, 1, '2026-07-06 03:53:48', '2026-07-10 04:15:52'),
(2, 1, 'Metro Homes Agents', 1.50, 1, '2026-07-06 03:53:48', '2026-07-10 04:15:59'),
(3, 1, 'Nandhana', 2.00, 25, '2026-07-20 00:29:32', '2026-07-20 00:29:32');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache`
--

DROP TABLE IF EXISTS `hindustansystem_cache`;
CREATE TABLE IF NOT EXISTS `hindustansystem_cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `hindustansystem_cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_cache`
--

INSERT INTO `hindustansystem_cache` (`key`, `value`, `expiration`) VALUES
('hindustanerp-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:13:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"vouchers.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"expenses.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"expenses.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"collections.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"sales.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"sales.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:22:\"sales.discount.request\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"units.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"units.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"projects.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"projects.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:17:\"units.rate.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Owner\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"Sales\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"Site\";s:1:\"c\";s:3:\"web\";}}}', 1784802773);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache_locks`
--

DROP TABLE IF EXISTS `hindustansystem_cache_locks`;
CREATE TABLE IF NOT EXISTS `hindustansystem_cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `hindustansystem_cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_commission_entries`
--

DROP TABLE IF EXISTS `hindustansystem_commission_entries`;
CREATE TABLE IF NOT EXISTS `hindustansystem_commission_entries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `deal_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Accrued',
  `triggered_at` timestamp NULL DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_commission_entries_system_id_foreign` (`system_id`),
  KEY `hindustansystem_commission_entries_voucher_id_foreign` (`voucher_id`),
  KEY `ce_deal_fk` (`deal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customers`
--

DROP TABLE IF EXISTS `hindustansystem_customers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `id_proof_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system` enum('india','uae') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'india',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customers`
--

INSERT INTO `hindustansystem_customers` (`id`, `name`, `email`, `phone`, `avatar_url`, `created_at`, `updated_at`, `address`, `id_proof_type`, `id_proof_number`, `system`, `is_active`) VALUES
(4, 'Koval Ahmed Haji m', 'koval@gmail.com', '1234567890', NULL, '2026-07-09 03:33:03', '2026-07-10 03:51:08', 'UAE', NULL, NULL, 'india', 1),
(5, 'Vijayan', 'viju@gmail.com', '9823451234', NULL, '2026-07-09 03:34:23', '2026-07-10 03:50:27', 'Kasargod', NULL, NULL, 'india', 1),
(8, 'Athira', 'ath@hmail.com', '23354565', NULL, '2026-07-17 03:36:05', '2026-07-17 03:36:05', 'adsdewrere', NULL, NULL, 'india', 1),
(9, 'Aradhya', 'ar@gmail.com', '5665767768', NULL, '2026-07-17 04:05:52', '2026-07-17 04:05:52', NULL, NULL, NULL, 'india', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customer_installments`
--

DROP TABLE IF EXISTS `hindustansystem_customer_installments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_customer_installments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `installment_no` int NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `schedule_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed_emi',
  `rescheduled_from_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_customer_installments_sale_id_foreign` (`sale_id`),
  KEY `cust_inst_rescheduled_fk` (`rescheduled_from_id`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customer_installments`
--

INSERT INTO `hindustansystem_customer_installments` (`id`, `sale_id`, `installment_no`, `label`, `due_date`, `amount`, `paid_amount`, `status`, `schedule_type`, `rescheduled_from_id`, `created_at`, `updated_at`) VALUES
(27, 7, 0, 'Down Payment', '2026-07-10', 1000000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(28, 7, 1, 'EMI 1', '2026-08-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(29, 7, 2, 'EMI 2', '2026-09-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(30, 7, 3, 'EMI 3', '2026-10-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(31, 7, 4, 'EMI 4', '2026-11-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(32, 7, 5, 'EMI 5', '2026-12-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(33, 7, 6, 'EMI 6', '2027-01-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(34, 7, 7, 'EMI 7', '2027-02-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(35, 7, 8, 'EMI 8', '2027-03-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(36, 7, 9, 'EMI 9', '2027-04-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(37, 7, 10, 'EMI 10', '2027-05-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(38, 7, 11, 'EMI 11', '2027-06-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(39, 7, 12, 'EMI 12', '2027-07-10', 213750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(93, 13, 0, 'Down Payment', '2026-07-10', 1775000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(94, 13, 1, 'EMI 1 (Month 1)', '2026-08-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(95, 13, 2, 'EMI 2 (Month 2)', '2026-09-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(96, 13, 3, 'EMI 3 (Month 3)', '2026-10-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(97, 13, 4, 'EMI 4 (Month 4)', '2026-11-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(98, 13, 5, 'EMI 5 (Month 5)', '2026-12-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(99, 13, 6, 'EMI 6 (Month 6)', '2027-01-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(100, 13, 7, 'EMI 7 (Month 7)', '2027-02-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(101, 13, 8, 'EMI 8 (Month 8)', '2027-03-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(102, 13, 9, 'EMI 9 (Month 9)', '2027-04-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(103, 13, 10, 'EMI 10 (Month 10)', '2027-05-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(104, 13, 11, 'EMI 11 (Month 11)', '2027-06-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(105, 13, 12, 'EMI 12 (Month 12)', '2027-07-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(106, 13, 13, 'EMI 13 (Month 13)', '2027-08-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(107, 13, 14, 'EMI 14 (Month 14)', '2027-09-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(108, 13, 15, 'EMI 15 (Month 15)', '2027-10-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(109, 13, 16, 'EMI 16 (Month 16)', '2027-11-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(110, 13, 17, 'EMI 17 (Month 17)', '2027-12-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(111, 13, 18, 'EMI 18 (Month 18)', '2028-01-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(112, 13, 19, 'EMI 19 (Month 19)', '2028-02-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(113, 13, 20, 'EMI 20 (Month 20)', '2028-03-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(114, 13, 21, 'EMI 21 (Month 21)', '2028-04-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(115, 13, 22, 'EMI 22 (Month 22)', '2028-05-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(116, 13, 23, 'EMI 23 (Month 23)', '2028-06-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(117, 13, 24, 'EMI 24 (Month 24)', '2028-07-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(118, 13, 25, 'EMI 25 (Month 25)', '2028-08-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(119, 13, 26, 'EMI 26 (Month 26)', '2028-09-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(120, 13, 27, 'EMI 27 (Month 27)', '2028-10-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(121, 13, 28, 'EMI 28 (Month 28)', '2028-11-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(122, 13, 29, 'EMI 29 (Month 29)', '2028-12-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(123, 13, 30, 'EMI 30 (Month 30)', '2029-01-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(124, 13, 31, 'EMI 31 (Month 31)', '2029-02-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(125, 13, 32, 'EMI 32 (Month 32)', '2029-03-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(126, 13, 33, 'EMI 33 (Month 33)', '2029-04-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(127, 13, 34, 'EMI 34 (Month 34)', '2029-05-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(128, 13, 35, 'EMI 35 (Month 35)', '2029-06-10', 41764.71, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(129, 13, 36, 'Plinth Stage (Milestone 1)', '2027-05-10', 104411.76, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(130, 13, 37, 'Roof Stage (Milestone 2)', '2028-05-10', 104411.76, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(131, 13, 38, 'Handover & Registry (Milestone 3)', '2029-07-10', 104411.63, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(132, 14, 0, 'Down Payment', '2026-07-10', 1511400.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(133, 14, 1, 'EMI 1 (Month 1)', '2026-08-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(134, 14, 2, 'EMI 2 (Month 2)', '2026-09-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(135, 14, 3, 'EMI 3 (Month 3)', '2026-10-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(136, 14, 4, 'EMI 4 (Month 4)', '2026-11-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(137, 14, 5, 'EMI 5 (Month 5)', '2026-12-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(138, 14, 6, 'EMI 6 (Month 6)', '2027-01-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(139, 14, 7, 'EMI 7 (Month 7)', '2027-02-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(140, 14, 8, 'EMI 8 (Month 8)', '2027-03-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(141, 14, 9, 'EMI 9 (Month 9)', '2027-04-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(142, 14, 10, 'EMI 10 (Month 10)', '2027-05-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(143, 14, 11, 'EMI 11 (Month 11)', '2027-06-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(144, 14, 12, 'EMI 12 (Month 12)', '2027-07-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(145, 14, 13, 'EMI 13 (Month 13)', '2027-08-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(146, 14, 14, 'EMI 14 (Month 14)', '2027-09-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(147, 14, 15, 'EMI 15 (Month 15)', '2027-10-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(148, 14, 16, 'EMI 16 (Month 16)', '2027-11-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(149, 14, 17, 'EMI 17 (Month 17)', '2027-12-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(150, 14, 18, 'EMI 18 (Month 18)', '2028-01-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(151, 14, 19, 'EMI 19 (Month 19)', '2028-02-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(152, 14, 20, 'EMI 20 (Month 20)', '2028-03-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(153, 14, 21, 'EMI 21 (Month 21)', '2028-04-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(154, 14, 22, 'EMI 22 (Month 22)', '2028-05-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(155, 14, 23, 'EMI 23 (Month 23)', '2028-06-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(156, 14, 24, 'EMI 24 (Month 24)', '2028-07-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(157, 14, 25, 'EMI 25 (Month 25)', '2028-08-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(158, 14, 26, 'EMI 26 (Month 26)', '2028-09-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(159, 14, 27, 'EMI 27 (Month 27)', '2028-10-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(160, 14, 28, 'EMI 28 (Month 28)', '2028-11-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(161, 14, 29, 'EMI 29 (Month 29)', '2028-12-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(162, 14, 30, 'EMI 30 (Month 30)', '2029-01-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(163, 14, 31, 'EMI 31 (Month 31)', '2029-02-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(164, 14, 32, 'EMI 32 (Month 32)', '2029-03-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(165, 14, 33, 'EMI 33 (Month 33)', '2029-04-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(166, 14, 34, 'EMI 34 (Month 34)', '2029-05-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(167, 14, 35, 'EMI 35 (Month 35)', '2029-06-10', 53343.53, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(168, 14, 36, 'Plinth Stage (Milestone 1)', '2027-05-10', 133358.82, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(169, 14, 37, 'Roof Stage (Milestone 2)', '2028-05-10', 133358.82, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(170, 14, 38, 'Handover & Registry (Milestone 3)', '2029-07-10', 133358.81, 0.00, 'pending', 'combo_fixed_36', NULL, '2026-07-10 05:36:54', '2026-07-10 05:36:54'),
(171, 17, 0, 'Down Payment', '2026-07-17', 1426000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-17 03:38:11', '2026-07-17 03:38:11'),
(172, 17, 1, 'EMI 1', '2026-07-17', 285200.00, 0.00, 'partial', 'fixed_emi', NULL, '2026-07-17 03:38:11', '2026-07-17 03:39:18'),
(173, 17, 2, 'EMI 2', '2026-08-17', 285200.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:38:11', '2026-07-17 03:38:11'),
(174, 17, 3, 'EMI 3', '2026-09-17', 285200.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:38:11', '2026-07-17 03:38:11'),
(175, 17, 4, 'EMI 4', '2026-10-17', 285200.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:38:11', '2026-07-17 03:38:11'),
(176, 17, 5, 'EMI 5', '2026-11-17', 285200.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:38:11', '2026-07-17 03:38:11'),
(177, 18, 0, 'Down Payment', '2026-07-17', 150000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(178, 18, 1, 'EMI 1', '2026-07-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(179, 18, 2, 'EMI 2', '2026-08-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(180, 18, 3, 'EMI 3', '2026-09-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(181, 18, 4, 'EMI 4', '2026-10-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(182, 18, 5, 'EMI 5', '2026-11-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(183, 18, 6, 'EMI 6', '2026-12-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(184, 18, 7, 'EMI 7', '2027-01-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(185, 18, 8, 'EMI 8', '2027-02-17', 18750.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(186, 19, 0, 'Down Payment', '2026-07-17', 200000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(187, 19, 1, 'EMI 1', '2026-08-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(188, 19, 2, 'EMI 2', '2026-09-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(189, 19, 3, 'EMI 3', '2026-10-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(190, 19, 4, 'EMI 4', '2026-11-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(191, 19, 5, 'EMI 5', '2026-12-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(192, 19, 6, 'EMI 6', '2027-01-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(193, 19, 7, 'EMI 7', '2027-02-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(194, 19, 8, 'EMI 8', '2027-03-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(195, 20, 0, 'Down Payment', '2026-07-17', 200000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(196, 20, 1, 'EMI 1', '2026-08-17', 25000.00, 0.00, 'partial', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:17:47'),
(197, 20, 2, 'EMI 2', '2026-09-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(198, 20, 3, 'EMI 3', '2026-10-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(199, 20, 4, 'EMI 4', '2026-11-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(200, 20, 5, 'EMI 5', '2026-12-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(201, 20, 6, 'EMI 6', '2027-01-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(202, 20, 7, 'EMI 7', '2027-02-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(203, 20, 8, 'EMI 8', '2027-03-17', 25000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(204, 21, 0, 'Down Payment', '2026-07-20', 300000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-20 02:04:45'),
(205, 21, 1, 'EMI 1', '2026-08-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(206, 21, 2, 'EMI 2', '2026-09-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(207, 21, 3, 'EMI 3', '2026-10-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(208, 21, 4, 'EMI 4', '2026-11-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(209, 21, 5, 'EMI 5', '2026-12-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(210, 21, 6, 'EMI 6', '2027-01-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(211, 21, 7, 'EMI 7', '2027-02-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(212, 21, 8, 'EMI 8', '2027-03-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(213, 21, 9, 'EMI 9', '2027-04-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(214, 21, 10, 'EMI 10', '2027-05-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(215, 21, 11, 'EMI 11', '2027-06-20', 8333.33, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(216, 21, 12, 'EMI 12', '2027-07-20', 8333.37, 0.00, 'partial', 'fixed_emi', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15'),
(217, 22, 0, 'Down Payment', '2026-07-20', 200000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 06:31:36'),
(218, 22, 1, 'EMI 1', '2026-08-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(219, 22, 2, 'EMI 2', '2026-09-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(220, 22, 3, 'EMI 3', '2026-10-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(221, 22, 4, 'EMI 4', '2026-11-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(222, 22, 5, 'EMI 5', '2026-12-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(223, 22, 6, 'EMI 6', '2027-01-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(224, 22, 7, 'EMI 7', '2027-02-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(225, 22, 8, 'EMI 8', '2027-03-20', 17222.22, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(226, 22, 9, 'EMI 9', '2027-04-20', 17222.24, 5000.00, 'partial', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10'),
(227, 22, 10, 'EMI 10', '2027-05-20', 20000.00, 20000.00, 'paid', 'fixed_emi', NULL, '2026-07-20 06:31:36', '2026-07-20 07:25:55'),
(228, 23, 0, 'Down Payment', '2026-07-22', 1000000.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(229, 23, 1, 'EMI 1', '2026-08-22', 581639.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(230, 23, 2, 'EMI 2', '2026-09-22', 581639.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(231, 23, 3, 'EMI 3', '2026-10-22', 581639.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(232, 23, 4, 'EMI 4', '2026-11-22', 581639.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(233, 23, 5, 'EMI 5', '2026-12-22', 581639.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(234, 23, 6, 'EMI 6', '2027-01-22', 581639.35, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(235, 24, 0, 'Down Payment', '2026-07-22', 1861150.00, 0.00, 'paid', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04'),
(236, 24, 1, 'EMI 1', '2026-08-22', 310191.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04'),
(237, 24, 2, 'EMI 2', '2026-09-22', 310191.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04'),
(238, 24, 3, 'EMI 3', '2026-10-22', 310191.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04'),
(239, 24, 4, 'EMI 4', '2026-11-22', 310191.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04'),
(240, 24, 5, 'EMI 5', '2026-12-22', 310191.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04'),
(241, 24, 6, 'EMI 6', '2027-01-22', 310191.65, 0.00, 'pending', 'fixed_emi', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_deals`
--

DROP TABLE IF EXISTS `hindustansystem_deals`;
CREATE TABLE IF NOT EXISTS `hindustansystem_deals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `broker_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `sale_value` decimal(15,2) NOT NULL,
  `commission_pct_override` decimal(5,2) DEFAULT NULL,
  `trigger_condition` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_collection',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_deals_system_id_foreign` (`system_id`),
  KEY `hindustansystem_deals_broker_id_foreign` (`broker_id`),
  KEY `hindustansystem_deals_project_id_foreign` (`project_id`),
  KEY `hindustansystem_deals_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_emi_reschedule_logs`
--

DROP TABLE IF EXISTS `hindustansystem_emi_reschedule_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_emi_reschedule_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `action_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `old_schedule_snapshot` json NOT NULL,
  `new_schedule_snapshot` json NOT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_emi_reschedule_logs_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_emi_reschedule_logs_performed_by_foreign` (`performed_by`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_emi_reschedule_logs`
--

INSERT INTO `hindustansystem_emi_reschedule_logs` (`id`, `sale_id`, `action_type`, `reason`, `old_schedule_snapshot`, `new_schedule_snapshot`, `performed_by`, `created_at`, `updated_at`) VALUES
(1, 22, 'prepayment_reduce_tenure', 'Prepayment - reduce_tenure', '[{\"id\": 218, \"label\": \"EMI 1\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-08-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 1, \"rescheduled_from_id\": null}, {\"id\": 219, \"label\": \"EMI 2\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-09-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 2, \"rescheduled_from_id\": null}, {\"id\": 220, \"label\": \"EMI 3\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-10-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 3, \"rescheduled_from_id\": null}, {\"id\": 221, \"label\": \"EMI 4\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-11-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 4, \"rescheduled_from_id\": null}, {\"id\": 222, \"label\": \"EMI 5\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-12-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 5, \"rescheduled_from_id\": null}, {\"id\": 223, \"label\": \"EMI 6\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-01-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 6, \"rescheduled_from_id\": null}, {\"id\": 224, \"label\": \"EMI 7\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-02-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 7, \"rescheduled_from_id\": null}, {\"id\": 225, \"label\": \"EMI 8\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-03-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 8, \"rescheduled_from_id\": null}, {\"id\": 226, \"label\": \"EMI 9\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-04-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 9, \"rescheduled_from_id\": null}, {\"id\": 227, \"label\": \"EMI 10\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-05-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 10, \"rescheduled_from_id\": null}]', '[{\"id\": 218, \"label\": \"EMI 1\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-08-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 1, \"rescheduled_from_id\": null}, {\"id\": 219, \"label\": \"EMI 2\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-09-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 2, \"rescheduled_from_id\": null}, {\"id\": 220, \"label\": \"EMI 3\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-10-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 3, \"rescheduled_from_id\": null}, {\"id\": 221, \"label\": \"EMI 4\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-11-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 4, \"rescheduled_from_id\": null}, {\"id\": 222, \"label\": \"EMI 5\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-12-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 5, \"rescheduled_from_id\": null}, {\"id\": 223, \"label\": \"EMI 6\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-01-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 6, \"rescheduled_from_id\": null}, {\"id\": 224, \"label\": \"EMI 7\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-02-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 7, \"rescheduled_from_id\": null}, {\"id\": 225, \"label\": \"EMI 8\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-03-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 8, \"rescheduled_from_id\": null}, {\"id\": 226, \"label\": \"EMI 9\", \"amount\": \"20000.00\", \"status\": \"partial\", \"sale_id\": 22, \"due_date\": \"2027-04-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:55:55.000000Z\", \"paid_amount\": \"5000.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 9, \"rescheduled_from_id\": null}, {\"id\": 227, \"label\": \"EMI 10\", \"amount\": \"20000.00\", \"status\": \"paid\", \"sale_id\": 22, \"due_date\": \"2027-05-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:55:55.000000Z\", \"paid_amount\": \"20000.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 10, \"rescheduled_from_id\": null}]', 1, '2026-07-20 07:25:55', '2026-07-20 07:25:55'),
(2, 22, 'prepayment_reduce_emi', 'Prepayment - reduce_emi', '[{\"id\": 218, \"label\": \"EMI 1\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-08-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 1, \"rescheduled_from_id\": null}, {\"id\": 219, \"label\": \"EMI 2\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-09-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 2, \"rescheduled_from_id\": null}, {\"id\": 220, \"label\": \"EMI 3\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-10-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 3, \"rescheduled_from_id\": null}, {\"id\": 221, \"label\": \"EMI 4\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-11-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 4, \"rescheduled_from_id\": null}, {\"id\": 222, \"label\": \"EMI 5\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-12-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 5, \"rescheduled_from_id\": null}, {\"id\": 223, \"label\": \"EMI 6\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-01-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 6, \"rescheduled_from_id\": null}, {\"id\": 224, \"label\": \"EMI 7\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-02-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 7, \"rescheduled_from_id\": null}, {\"id\": 225, \"label\": \"EMI 8\", \"amount\": \"20000.00\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-03-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:01:36.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 8, \"rescheduled_from_id\": null}, {\"id\": 226, \"label\": \"EMI 9\", \"amount\": \"20000.00\", \"status\": \"partial\", \"sale_id\": 22, \"due_date\": \"2027-04-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:55:55.000000Z\", \"paid_amount\": \"5000.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 9, \"rescheduled_from_id\": null}]', '[{\"id\": 218, \"label\": \"EMI 1\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-08-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 1, \"rescheduled_from_id\": null}, {\"id\": 219, \"label\": \"EMI 2\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-09-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 2, \"rescheduled_from_id\": null}, {\"id\": 220, \"label\": \"EMI 3\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-10-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 3, \"rescheduled_from_id\": null}, {\"id\": 221, \"label\": \"EMI 4\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-11-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 4, \"rescheduled_from_id\": null}, {\"id\": 222, \"label\": \"EMI 5\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2026-12-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 5, \"rescheduled_from_id\": null}, {\"id\": 223, \"label\": \"EMI 6\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-01-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 6, \"rescheduled_from_id\": null}, {\"id\": 224, \"label\": \"EMI 7\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-02-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 7, \"rescheduled_from_id\": null}, {\"id\": 225, \"label\": \"EMI 8\", \"amount\": \"17222.22\", \"status\": \"pending\", \"sale_id\": 22, \"due_date\": \"2027-03-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"0.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 8, \"rescheduled_from_id\": null}, {\"id\": 226, \"label\": \"EMI 9\", \"amount\": \"17222.24\", \"status\": \"partial\", \"sale_id\": 22, \"due_date\": \"2027-04-20T00:00:00.000000Z\", \"created_at\": \"2026-07-20T12:01:36.000000Z\", \"updated_at\": \"2026-07-20T12:56:10.000000Z\", \"paid_amount\": \"5000.00\", \"schedule_type\": \"fixed_emi\", \"installment_no\": 9, \"rescheduled_from_id\": null}]', 1, '2026-07-20 07:26:10', '2026-07-20 07:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_emi_schedules`
--

DROP TABLE IF EXISTS `hindustansystem_emi_schedules`;
CREATE TABLE IF NOT EXISTS `hindustansystem_emi_schedules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `installment_no` int NOT NULL,
  `due_date` date NOT NULL,
  `emi_amount` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_date` date DEFAULT NULL,
  `principal_component` decimal(15,2) NOT NULL,
  `interest_component` decimal(15,2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Due',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_emi_schedules_system_id_foreign` (`system_id`),
  KEY `es_loan_fk` (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_emi_schedules`
--

INSERT INTO `hindustansystem_emi_schedules` (`id`, `system_id`, `loan_id`, `installment_no`, `due_date`, `emi_amount`, `amount_paid`, `paid_date`, `principal_component`, `interest_component`, `status`, `created_at`, `updated_at`) VALUES
(185, 1, 6, 1, '2026-08-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:52'),
(186, 1, 6, 2, '2026-09-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(187, 1, 6, 3, '2026-10-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(188, 1, 6, 4, '2026-11-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(189, 1, 6, 5, '2026-12-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(190, 1, 6, 6, '2027-01-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(191, 1, 6, 7, '2027-02-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(192, 1, 6, 8, '2027-03-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(193, 1, 6, 9, '2027-04-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(194, 1, 6, 10, '2027-05-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(195, 1, 6, 11, '2027-06-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(196, 1, 6, 12, '2027-07-15', 9333.33, 0.00, NULL, 8333.33, 1000.00, 'Due', '2026-07-15 06:50:54', '2026-07-15 06:54:53'),
(197, 1, 7, 1, '2026-08-16', 22703.38, 0.00, NULL, 17703.38, 5000.00, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(198, 1, 7, 2, '2026-09-16', 22703.38, 0.00, NULL, 17880.41, 4822.97, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(199, 1, 7, 3, '2026-10-16', 22703.38, 0.00, NULL, 18059.21, 4644.16, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(200, 1, 7, 4, '2026-11-16', 22703.38, 0.00, NULL, 18239.81, 4463.57, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(201, 1, 7, 5, '2026-12-16', 22703.38, 0.00, NULL, 18422.20, 4281.17, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(202, 1, 7, 6, '2027-01-16', 22703.38, 0.00, NULL, 18606.43, 4096.95, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(203, 1, 7, 7, '2027-02-16', 22703.38, 0.00, NULL, 18792.49, 3910.89, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(204, 1, 7, 8, '2027-03-16', 22703.38, 0.00, NULL, 18980.42, 3722.96, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(205, 1, 7, 9, '2027-04-16', 22703.38, 0.00, NULL, 19170.22, 3533.16, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(206, 1, 7, 10, '2027-05-16', 22703.38, 0.00, NULL, 19361.92, 3341.45, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(207, 1, 7, 11, '2027-06-16', 22703.38, 0.00, NULL, 19555.54, 3147.84, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(208, 1, 7, 12, '2027-07-16', 22703.38, 0.00, NULL, 19751.10, 2952.28, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(209, 1, 7, 13, '2027-08-16', 22703.38, 0.00, NULL, 19948.61, 2754.77, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(210, 1, 7, 14, '2027-09-16', 22703.38, 0.00, NULL, 20148.09, 2555.28, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(211, 1, 7, 15, '2027-10-16', 22703.38, 0.00, NULL, 20349.58, 2353.80, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(212, 1, 7, 16, '2027-11-16', 22703.38, 0.00, NULL, 20553.07, 2150.31, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(213, 1, 7, 17, '2027-12-16', 22703.38, 0.00, NULL, 20758.60, 1944.78, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(214, 1, 7, 18, '2028-01-16', 22703.38, 0.00, NULL, 20966.19, 1737.19, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(215, 1, 7, 19, '2028-02-16', 22703.38, 0.00, NULL, 21175.85, 1527.53, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(216, 1, 7, 20, '2028-03-16', 22703.38, 0.00, NULL, 21387.61, 1315.77, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(217, 1, 7, 21, '2028-04-16', 22703.38, 0.00, NULL, 21601.48, 1101.89, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(218, 1, 7, 22, '2028-05-16', 22703.38, 0.00, NULL, 21817.50, 885.88, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(219, 1, 7, 23, '2028-06-16', 22703.38, 0.00, NULL, 22035.67, 667.70, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(220, 1, 7, 24, '2028-07-16', 22703.38, 0.00, NULL, 22256.03, 447.35, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(221, 1, 7, 25, '2028-08-16', 22703.38, 0.00, NULL, 22478.59, 224.79, 'Due', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(222, 1, 8, 1, '2026-08-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(223, 1, 8, 2, '2026-09-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(224, 1, 8, 3, '2026-10-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(225, 1, 8, 4, '2026-11-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(226, 1, 8, 5, '2026-12-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(227, 1, 8, 6, '2027-01-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(228, 1, 8, 7, '2027-02-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(229, 1, 8, 8, '2027-03-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(230, 1, 8, 9, '2027-04-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(231, 1, 8, 10, '2027-05-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(232, 1, 8, 11, '2027-06-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(233, 1, 8, 12, '2027-07-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(234, 1, 8, 13, '2027-08-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(235, 1, 8, 14, '2027-09-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(236, 1, 8, 15, '2027-10-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(237, 1, 8, 16, '2027-11-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(238, 1, 8, 17, '2027-12-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(239, 1, 8, 18, '2028-01-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(240, 1, 8, 19, '2028-02-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(241, 1, 8, 20, '2028-03-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(242, 1, 8, 21, '2028-04-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(243, 1, 8, 22, '2028-05-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(244, 1, 8, 23, '2028-06-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(245, 1, 8, 24, '2028-07-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(246, 1, 8, 25, '2028-08-16', 25000.00, 0.00, NULL, 20000.00, 5000.00, 'Due', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(247, 1, 9, 1, '2026-07-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(248, 1, 9, 2, '2026-08-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(249, 1, 9, 3, '2026-09-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(250, 1, 9, 4, '2026-10-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(251, 1, 9, 5, '2026-11-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(252, 1, 9, 6, '2026-12-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(253, 1, 9, 7, '2027-01-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(254, 1, 9, 8, '2027-02-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(255, 1, 9, 9, '2027-03-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(256, 1, 9, 10, '2027-04-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(257, 1, 9, 11, '2027-05-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(258, 1, 9, 12, '2027-06-16', 36666.67, 0.00, NULL, 33333.33, 3333.33, 'Due', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(259, 1, 10, 1, '2026-07-16', 9583.33, 9583.33, '2026-07-16', 8333.33, 1250.00, 'Paid', '2026-07-16 03:36:31', '2026-07-16 04:09:47'),
(260, 1, 10, 2, '2026-08-16', 9583.33, 9583.33, '2026-07-16', 8333.33, 1250.00, 'Paid', '2026-07-16 03:36:31', '2026-07-16 04:10:09'),
(261, 1, 10, 3, '2026-09-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(262, 1, 10, 4, '2026-10-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(263, 1, 10, 5, '2026-11-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(264, 1, 10, 6, '2026-12-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(265, 1, 10, 7, '2027-01-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(266, 1, 10, 8, '2027-02-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(267, 1, 10, 9, '2027-03-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(268, 1, 10, 10, '2027-04-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(269, 1, 10, 11, '2027-05-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(270, 1, 10, 12, '2027-06-16', 9583.33, 0.00, NULL, 8333.33, 1250.00, 'Due', '2026-07-16 03:36:31', '2026-07-16 03:36:31'),
(271, 1, 11, 1, '2026-02-01', 73333.33, 73333.33, '2026-06-05', 66666.67, 6666.67, 'Paid', '2026-07-16 04:40:48', '2026-07-17 06:25:17'),
(272, 1, 11, 2, '2026-03-01', 73333.34, 73333.34, '2026-07-20', 66666.67, 6666.67, 'Paid', '2026-07-16 04:40:48', '2026-07-19 23:49:47'),
(273, 1, 11, 3, '2026-04-01', 73333.34, 73333.34, '2026-07-20', 66666.67, 6666.67, 'Paid', '2026-07-16 04:40:48', '2026-07-19 23:49:53'),
(274, 1, 11, 4, '2026-05-01', 73333.34, 73333.34, '2026-07-20', 66666.67, 6666.67, 'Paid', '2026-07-16 04:40:48', '2026-07-19 23:52:28'),
(275, 1, 11, 5, '2026-06-01', 73333.34, 73333.34, '2026-07-20', 66666.67, 6666.67, 'Paid', '2026-07-16 04:40:48', '2026-07-19 23:52:33'),
(276, 1, 11, 6, '2026-07-01', 73333.32, 73333.32, '2026-07-20', 66666.65, 6666.67, 'Paid', '2026-07-16 04:40:48', '2026-07-19 23:52:40'),
(295, 1, 13, 1, '2025-02-20', 25208.33, 25208.33, '2026-07-20', 20833.33, 4375.00, 'Paid', '2026-07-20 00:40:06', '2026-07-20 06:25:36'),
(296, 1, 13, 2, '2025-03-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(297, 1, 13, 3, '2025-04-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(298, 1, 13, 4, '2025-05-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(299, 1, 13, 5, '2025-06-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(300, 1, 13, 6, '2025-07-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(301, 1, 13, 7, '2025-08-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(302, 1, 13, 8, '2025-09-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(303, 1, 13, 9, '2025-10-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(304, 1, 13, 10, '2025-11-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(305, 1, 13, 11, '2025-12-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(306, 1, 13, 12, '2026-01-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(307, 1, 13, 13, '2026-02-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(308, 1, 13, 14, '2026-03-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(309, 1, 13, 15, '2026-04-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(310, 1, 13, 16, '2026-05-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(311, 1, 13, 17, '2026-06-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(312, 1, 13, 18, '2026-07-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(313, 1, 13, 19, '2026-08-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(314, 1, 13, 20, '2026-09-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(315, 1, 13, 21, '2026-10-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(316, 1, 13, 22, '2026-11-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(317, 1, 13, 23, '2026-12-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(318, 1, 13, 24, '2027-01-20', 25208.33, 0.00, NULL, 20833.33, 4375.00, 'Due', '2026-07-20 00:40:06', '2026-07-20 00:40:06'),
(319, 1, 14, 1, '2026-08-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(320, 1, 14, 2, '2026-09-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(321, 1, 14, 3, '2026-10-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(322, 1, 14, 4, '2026-11-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(323, 1, 14, 5, '2026-12-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(324, 1, 14, 6, '2027-01-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(325, 1, 14, 7, '2027-02-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(326, 1, 14, 8, '2027-03-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(327, 1, 14, 9, '2027-04-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(328, 1, 14, 10, '2027-05-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(329, 1, 14, 11, '2027-06-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(330, 1, 14, 12, '2027-07-01', 27500.00, 0.00, NULL, 25000.00, 2500.00, 'Due', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(331, 1, 15, 1, '2026-09-20', 229166.67, 229166.67, '2026-07-20', 208333.33, 20833.33, 'Paid', '2026-07-20 01:36:59', '2026-07-20 01:45:51'),
(332, 1, 15, 2, '2026-10-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(333, 1, 15, 3, '2026-11-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(334, 1, 15, 4, '2026-12-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(335, 1, 15, 5, '2027-01-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(336, 1, 15, 6, '2027-02-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(337, 1, 15, 7, '2027-03-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(338, 1, 15, 8, '2027-04-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(339, 1, 15, 9, '2027-05-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(340, 1, 15, 10, '2027-06-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(341, 1, 15, 11, '2027-07-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(342, 1, 15, 12, '2027-08-20', 229166.67, 0.00, NULL, 208333.33, 20833.33, 'Due', '2026-07-20 01:36:59', '2026-07-20 01:36:59'),
(343, 1, 16, 1, '2026-08-20', 9166.67, 9166.67, '2026-07-20', 8333.33, 833.33, 'Paid', '2026-07-20 01:43:00', '2026-07-20 01:45:26'),
(344, 1, 16, 2, '2026-09-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(345, 1, 16, 3, '2026-10-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(346, 1, 16, 4, '2026-11-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(347, 1, 16, 5, '2026-12-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(348, 1, 16, 6, '2027-01-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(349, 1, 16, 7, '2027-02-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(350, 1, 16, 8, '2027-03-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(351, 1, 16, 9, '2027-04-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(352, 1, 16, 10, '2027-05-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(353, 1, 16, 11, '2027-06-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(354, 1, 16, 12, '2027-07-20', 9166.67, 0.00, NULL, 8333.33, 833.33, 'Due', '2026-07-20 01:43:00', '2026-07-20 01:43:00'),
(355, 1, 17, 1, '2026-08-20', 18666.67, 18666.67, '2026-07-20', 16666.67, 2000.00, 'Paid', '2026-07-20 01:50:33', '2026-07-20 01:50:44'),
(356, 1, 17, 2, '2026-09-20', 18666.67, 18666.67, '2026-07-20', 16666.67, 2000.00, 'Paid', '2026-07-20 01:50:33', '2026-07-20 02:07:16'),
(357, 1, 17, 3, '2026-10-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(358, 1, 17, 4, '2026-11-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(359, 1, 17, 5, '2026-12-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(360, 1, 17, 6, '2027-01-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(361, 1, 17, 7, '2027-02-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(362, 1, 17, 8, '2027-03-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(363, 1, 17, 9, '2027-04-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(364, 1, 17, 10, '2027-05-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(365, 1, 17, 11, '2027-06-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33'),
(366, 1, 17, 12, '2027-07-20', 18666.67, 0.00, NULL, 16666.67, 2000.00, 'Due', '2026-07-20 01:50:33', '2026-07-20 01:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employees`
--

DROP TABLE IF EXISTS `hindustansystem_employees`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date NOT NULL,
  `salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_employees_employee_id_unique` (`employee_id`),
  KEY `hindustansystem_employees_system_id_foreign` (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_failed_jobs`
--

DROP TABLE IF EXISTS `hindustansystem_failed_jobs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_floors`
--

DROP TABLE IF EXISTS `hindustansystem_floors`;
CREATE TABLE IF NOT EXISTS `hindustansystem_floors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `floor_number` int NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_floors_project_id_floor_number_unique` (`project_id`,`floor_number`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_floors`
--

INSERT INTO `hindustansystem_floors` (`id`, `project_id`, `floor_number`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Floor 1', '2026-07-06 03:53:41', '2026-07-06 03:53:41'),
(2, 1, 2, 'Floor 2', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(3, 1, 3, 'Floor 3', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(4, 1, 4, 'Floor 4', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(5, 1, 5, 'Floor 5', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(16, 1, -1, 'Basement 1', '2026-07-08 04:39:22', '2026-07-08 04:39:22'),
(17, 1, 0, 'Ground Floor', '2026-07-09 01:20:07', '2026-07-09 01:20:07'),
(18, 1, 6, 'Floor 6', '2026-07-10 02:05:19', '2026-07-10 02:05:19'),
(19, 1, 7, 'Floor 7', '2026-07-10 02:05:26', '2026-07-10 02:05:26'),
(20, 1, 8, 'Floor 8', '2026-07-10 02:05:32', '2026-07-10 02:05:32'),
(22, 1, 9, 'Floor 9', '2026-07-14 03:37:08', '2026-07-14 03:37:08'),
(23, 1, 10, 'Floor 10', '2026-07-14 03:37:28', '2026-07-14 03:37:28'),
(24, 1, 11, 'Floor 11', '2026-07-14 03:37:34', '2026-07-14 03:37:34'),
(25, 1, 12, 'Floor 12', '2026-07-14 03:37:39', '2026-07-14 03:37:39'),
(26, 1, 13, 'Floor 13', '2026-07-14 03:37:45', '2026-07-14 03:37:45'),
(27, 1, 14, 'Floor 14', '2026-07-14 03:37:51', '2026-07-14 03:37:51'),
(28, 1, 15, 'Floor 15', '2026-07-14 03:37:56', '2026-07-14 03:37:56'),
(29, 1, 16, 'Floor 16', '2026-07-14 04:05:00', '2026-07-14 04:05:00'),
(30, 1, 17, 'Floor 17', '2026-07-14 04:05:09', '2026-07-14 04:05:09');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_hindustan_units`
--

DROP TABLE IF EXISTS `hindustansystem_hindustan_units`;
CREATE TABLE IF NOT EXISTS `hindustansystem_hindustan_units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `floor_id` bigint UNSIGNED NOT NULL,
  `unit_type_id` bigint UNSIGNED NOT NULL,
  `door_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `built_up_area` decimal(10,2) DEFAULT NULL,
  `carpet_area` decimal(10,2) DEFAULT NULL,
  `expected_rate_per_sqft` decimal(12,2) DEFAULT NULL,
  `expected_sale_amount` decimal(14,2) DEFAULT NULL,
  `sale_rate_per_sqft` decimal(12,2) DEFAULT NULL,
  `sale_amount` decimal(14,2) DEFAULT NULL,
  `difference` decimal(14,2) DEFAULT NULL,
  `gst_behavior` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('available','booked','sold','blocked','hold','reserved') COLLATE utf8mb4_unicode_ci DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `hindustansystem_hindustan_units_project_id_foreign` (`project_id`),
  KEY `hindustansystem_hindustan_units_floor_id_foreign` (`floor_id`),
  KEY `hindustansystem_hindustan_units_unit_type_id_foreign` (`unit_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_hindustan_units`
--

INSERT INTO `hindustansystem_hindustan_units` (`id`, `project_id`, `floor_id`, `unit_type_id`, `door_no`, `built_up_area`, `carpet_area`, `expected_rate_per_sqft`, `expected_sale_amount`, `sale_rate_per_sqft`, `sale_amount`, `difference`, `gst_behavior`, `gst_amount`, `status`, `created_at`, `updated_at`, `is_active`) VALUES
(41, 1, 17, 2, 'G 1', 4943.00, 3954.40, 4500.00, 22243500.00, 11500.00, 56844500.00, -34601000.00, 'none', 0.00, 'sold', '2026-07-09 01:43:27', '2026-07-15 04:12:55', 1),
(43, 1, 17, 2, 'G 3', 310.00, 248.00, 11000.00, 3410000.00, 11500.00, 3565000.00, -155000.00, 'none', 0.00, 'sold', '2026-07-09 01:44:57', '2026-07-13 04:38:30', 1),
(44, 1, 17, 2, 'G 4', 284.00, 227.20, 12000.00, 3408000.00, 12500.00, 3550000.00, -142000.00, 'none', 0.00, 'sold', '2026-07-09 01:45:38', '2026-07-13 04:38:30', 1),
(45, 1, 17, 2, 'G 5', 284.00, 227.20, 12000.00, 3408000.00, 11500.00, 3266000.00, 142000.00, 'none', 0.00, 'sold', '2026-07-09 01:47:34', '2026-07-13 04:38:30', 1),
(46, 1, 17, 2, 'G 6', 248.00, 198.40, 12000.00, 2976000.00, 12000.00, 2852000.00, 124000.00, 'none', 0.00, 'sold', '2026-07-09 01:47:59', '2026-07-17 03:38:10', 1),
(47, 1, 17, 2, 'G 7', 229.00, 183.20, 17000.00, 3893000.00, 16500.00, 3778500.00, 114500.00, 'none', 0.00, 'sold', '2026-07-09 01:48:27', '2026-07-13 04:38:30', 1),
(48, 1, 17, 2, 'G 8', 284.00, 227.20, 23000.00, 6532000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 01:49:01', '2026-07-13 04:38:30', 1),
(73, 1, 5, 5, 'FI 4', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:11:39', '2026-07-14 04:25:31', 1),
(74, 1, 5, 5, 'FI 5', NULL, NULL, NULL, 300000.00, 0.00, 400000.00, -100000.00, 'none', 0.00, 'sold', '2026-07-09 23:11:53', '2026-07-17 04:14:50', 1),
(75, 1, 1, 2, 'F 1', 976.00, 780.80, 12000.00, 11712000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:13:46', '2026-07-13 04:38:30', 1),
(76, 1, 1, 2, 'F 2', 1071.00, 856.80, 12000.00, 12852000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:14:39', '2026-07-13 04:38:30', 1),
(77, 1, 1, 2, 'F 3', 846.00, 676.80, 11000.00, 9306000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:15:49', '2026-07-13 04:38:30', 1),
(78, 1, 2, 2, 'S 1', 976.00, 780.80, 14000.00, 13664000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:16:55', '2026-07-13 04:38:30', 1),
(79, 1, 2, 2, 'S 2', 1071.00, 856.80, 14000.00, 14994000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:17:24', '2026-07-13 04:38:30', 1),
(80, 1, 2, 2, 'S 3', 846.00, 676.80, 14000.00, 11844000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:19:44', '2026-07-13 04:38:30', 1),
(81, 1, 3, 2, 'T 1', 967.00, 773.60, 15000.00, 14505000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:20:29', '2026-07-13 04:38:30', 1),
(82, 1, 3, 2, 'T 2', 1071.00, 856.80, 15000.00, 16065000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:21:03', '2026-07-13 04:38:30', 1),
(83, 1, 3, 2, 'T 3', 846.00, 676.80, 15000.00, 12690000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-09 23:21:53', '2026-07-13 04:38:30', 1),
(84, 1, 5, 5, 'FI 1', NULL, NULL, NULL, 400000.00, 0.00, 400000.00, 0.00, 'none', 0.00, 'sold', '2026-07-09 23:22:48', '2026-07-17 04:13:35', 1),
(85, 1, 5, 5, 'FI 2', NULL, NULL, NULL, 400000.00, 0.00, 400000.00, 0.00, 'none', 0.00, 'sold', '2026-07-09 23:23:11', '2026-07-20 02:04:45', 1),
(86, 1, 5, 5, 'FI 3', NULL, NULL, NULL, 400000.00, 0.00, 400000.00, 0.00, 'none', 0.00, 'sold', '2026-07-09 23:23:25', '2026-07-20 06:31:36', 1),
(87, 1, 17, 2, 'G 2', 480.00, 384.00, 11000.00, 5280000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 00:16:37', '2026-07-13 04:38:30', 1),
(88, 1, 18, 6, 'SI A', 1022.00, 817.60, 5800.00, 5927600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:07:44', '2026-07-14 05:40:08', 1),
(89, 1, 18, 6, 'SI B', 734.00, 587.20, 4700.00, 3449800.00, 4700.00, 3449800.00, 0.00, 'none', 0.00, 'sold', '2026-07-10 02:08:44', '2026-07-14 05:40:08', 1),
(90, 1, 18, 6, 'SI C', 592.00, 473.60, 3800.00, 2249600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:09:27', '2026-07-14 05:40:09', 1),
(91, 1, 18, 6, 'SI D', 741.00, 592.80, 3400.00, 2519400.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:14:02', '2026-07-14 05:40:09', 1),
(92, 1, 18, 6, 'SI E', 725.00, 580.00, 3500.00, 2537500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:15:18', '2026-07-14 05:40:09', 1),
(93, 1, 18, 6, 'SI F', 608.00, 486.40, 5800.00, 3526400.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:16:00', '2026-07-14 05:40:09', 1),
(94, 1, 19, 6, 'SE A1', 979.00, 783.20, 3500.00, 3426500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:54:31', '2026-07-14 05:40:09', 1),
(95, 1, 19, 6, 'SE B1', 1607.00, 1285.60, 3500.00, 5624500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:55:10', '2026-07-14 05:40:09', 1),
(96, 1, 19, 6, 'SE C1', 1586.00, 1268.80, 5500.00, 8723000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:56:02', '2026-07-14 05:40:09', 1),
(97, 1, 19, 6, 'SE D1', 1063.00, 850.40, 3600.00, 3826800.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-10 02:56:39', '2026-07-14 05:40:09', 1),
(99, 1, 20, 6, 'A1', 979.00, 783.00, 5800.00, 5678200.00, 3800.00, 4389836.00, 1288364.00, 'exclusive', 669636.00, 'sold', '2026-07-14 03:33:06', '2026-07-22 04:30:14', 1),
(100, 1, 20, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:33:54', '2026-07-14 03:33:54', 1),
(101, 1, 20, 6, 'C1', 1587.00, 1269.60, 4500.00, 7141500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:34:35', '2026-07-14 03:34:35', 1),
(102, 1, 20, 6, 'D1', 1058.00, 846.40, 4200.00, 4443600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:35:16', '2026-07-14 03:35:16', 1),
(103, 1, 20, 6, 'E1', 775.00, 620.00, 3800.00, 2945000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:36:00', '2026-07-14 03:36:00', 1),
(104, 1, 22, 6, 'A1', 979.00, 783.20, 3800.00, 3720200.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:38:38', '2026-07-14 03:38:38', 1),
(105, 1, 22, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:39:16', '2026-07-14 03:39:16', 1),
(106, 1, 22, 6, 'C1', 1587.00, 1269.60, 3600.00, 5713200.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:39:58', '2026-07-14 03:39:58', 1),
(107, 1, 22, 6, 'D1', 1058.00, 846.40, 4200.00, 4443600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:40:34', '2026-07-14 03:40:34', 1),
(108, 1, 22, 6, 'E1', 775.00, 620.00, 4500.00, 3487500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:41:00', '2026-07-14 03:41:00', 1),
(109, 1, 23, 6, 'A1', 979.00, 783.20, 3500.00, 3426500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:42:36', '2026-07-14 03:42:36', 1),
(110, 1, 23, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:43:10', '2026-07-14 03:43:10', 1),
(111, 1, 23, 6, 'C1', 1587.00, 1269.60, 4500.00, 7141500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:43:51', '2026-07-14 03:43:51', 1),
(112, 1, 23, 6, 'D1', 1058.00, 846.40, 5000.00, 5290000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:44:58', '2026-07-14 03:44:58', 1),
(113, 1, 23, 6, 'E1', 775.00, 620.00, 4800.00, 3720000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:45:24', '2026-07-14 03:45:24', 1),
(114, 1, 24, 6, 'A1', 979.00, 783.20, 3700.00, 3622300.00, 3700.00, 3622300.00, 0.00, 'none', 0.00, 'sold', '2026-07-14 03:45:58', '2026-07-22 04:44:04', 1),
(115, 1, 24, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:46:29', '2026-07-14 03:46:29', 1),
(116, 1, 24, 6, 'C1', 1587.00, 1269.60, 3500.00, 5554500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:47:07', '2026-07-14 03:47:07', 1),
(117, 1, 24, 6, 'D1', 1058.00, 846.40, 3300.00, 3491400.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:47:47', '2026-07-14 03:47:47', 1),
(118, 1, 24, 6, 'E1', 775.00, 620.00, 5500.00, 4262500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:48:23', '2026-07-14 03:48:23', 1),
(119, 1, 25, 6, 'A1', 979.00, 783.20, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:50:24', '2026-07-14 03:50:24', 1),
(120, 1, 25, 6, 'C1', 1587.00, 1269.60, 4000.00, 6348000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:51:14', '2026-07-14 03:51:14', 1),
(121, 1, 25, 6, 'D1', 1058.00, 846.40, 3600.00, 3808800.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:51:56', '2026-07-14 03:51:56', 1),
(122, 1, 25, 6, 'E1', 775.00, 620.00, 5500.00, 4262500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:52:24', '2026-07-14 03:52:24', 1),
(123, 1, 26, 6, 'A1', 979.00, 783.20, 3500.00, 3426500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:53:01', '2026-07-14 03:53:01', 1),
(124, 1, 26, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:53:37', '2026-07-14 03:53:37', 1),
(125, 1, 26, 6, 'C1', 1587.00, 1269.60, 3500.00, 5554500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:54:25', '2026-07-14 03:54:25', 1),
(126, 1, 26, 6, 'D1', 1058.00, 846.40, 4800.00, 5078400.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:55:09', '2026-07-14 03:55:09', 1),
(127, 1, 26, 6, 'E1', 775.00, 620.00, 4500.00, 3487500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:55:40', '2026-07-14 03:55:40', 1),
(128, 1, 27, 6, 'A1', 979.00, 783.20, 4000.00, 3916000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:57:53', '2026-07-14 03:57:53', 1),
(129, 1, 27, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:58:33', '2026-07-14 03:58:33', 1),
(130, 1, 27, 6, 'C1', 1587.00, 1269.60, 3600.00, 5713200.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:59:14', '2026-07-14 03:59:14', 1),
(131, 1, 27, 6, 'D1', 1058.00, 846.40, 3600.00, 3808800.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 03:59:57', '2026-07-14 03:59:57', 1),
(132, 1, 27, 6, 'E1', 775.00, 620.00, 6000.00, 4650000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:00:29', '2026-07-14 04:00:29', 1),
(133, 1, 28, 6, 'A1', 979.00, 783.20, 3800.00, 3720200.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:02:10', '2026-07-14 04:02:10', 1),
(134, 1, 28, 6, 'B1', 1572.00, 1257.60, 5800.00, 9117600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:02:38', '2026-07-14 04:02:38', 1),
(135, 1, 28, 6, 'C1', 1587.00, 1269.60, 5800.00, 9204600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:03:32', '2026-07-14 04:03:32', 1),
(136, 1, 28, 6, 'D1', 1058.00, 846.40, 5500.00, 5819000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:04:00', '2026-07-14 04:04:00', 1),
(137, 1, 28, 6, 'E1', 775.00, 620.00, 3400.00, 2635000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:04:26', '2026-07-14 04:04:26', 1),
(138, 1, 29, 6, 'A2', 1107.00, 885.60, 3800.00, 4206600.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:06:58', '2026-07-14 04:06:58', 1),
(139, 1, 29, 6, 'B2', 1712.00, 1369.60, 4000.00, 6848000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:07:26', '2026-07-14 04:07:26', 1),
(140, 1, 29, 6, 'C2', 1785.00, 1428.00, 3500.00, 6247500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:08:00', '2026-07-14 04:08:00', 1),
(141, 1, 29, 6, 'D2', 1013.00, 810.40, 3500.00, 3545500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:08:43', '2026-07-14 04:08:43', 1),
(142, 1, 30, 6, 'A2', 1107.00, 885.60, 3600.00, 3985200.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:09:28', '2026-07-14 04:09:28', 1),
(143, 1, 30, 6, 'B2', 1712.00, 1369.60, 3500.00, 5992000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:10:10', '2026-07-14 04:10:10', 1),
(144, 1, 30, 6, 'C2', 1785.00, 1428.00, 4000.00, 7140000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:10:37', '2026-07-14 04:10:37', 1),
(145, 1, 30, 6, 'D2', 1013.00, 810.40, 4500.00, 4558500.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:11:08', '2026-07-14 04:11:08', 1),
(155, 1, 4, 5, 'FO 1', NULL, NULL, NULL, 300000.00, 0.00, 300000.00, 0.00, 'none', 0.00, 'sold', '2026-07-14 04:38:05', '2026-07-17 03:58:53', 1),
(156, 1, 4, 5, 'FO 2', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(157, 1, 4, 5, 'FO 3', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(158, 1, 4, 5, 'FO 4', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(159, 1, 4, 5, 'FO 5', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(160, 1, 4, 5, 'FO 6', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(161, 1, 4, 5, 'FO 7', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(162, 1, 4, 5, 'FO 8', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(163, 1, 4, 5, 'FO 9', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(164, 1, 4, 5, 'FO 10', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(165, 1, 4, 5, 'FO 11', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(166, 1, 4, 5, 'FO 12', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(167, 1, 4, 5, 'FO 13', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(168, 1, 4, 5, 'FO 14', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(169, 1, 4, 5, 'FO 15', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(170, 1, 4, 5, 'FO 16', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(171, 1, 4, 5, 'FO 17', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(172, 1, 4, 5, 'FO 18', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(173, 1, 4, 5, 'FO 19', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(174, 1, 4, 5, 'FO 20', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(175, 1, 4, 5, 'FO 21', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(176, 1, 4, 5, 'FO 22', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(177, 1, 4, 5, 'FO 23', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(178, 1, 4, 5, 'FO 24', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(179, 1, 4, 5, 'FO 25', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(180, 1, 4, 5, 'FO 26', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(181, 1, 4, 5, 'FO 27', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(182, 1, 4, 5, 'FO 28', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(183, 1, 4, 5, 'FO 29', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(184, 1, 4, 5, 'FO 30', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(185, 1, 4, 5, 'FO 31', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(186, 1, 4, 5, 'FO 32', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(187, 1, 4, 5, 'FO 33', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(188, 1, 4, 5, 'FO 34', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(189, 1, 4, 5, 'FO 35', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(190, 1, 4, 5, 'FO 36', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1),
(191, 1, 4, 5, 'FO 37', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-07-14 04:38:05', '2026-07-14 05:40:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_jobs`
--

DROP TABLE IF EXISTS `hindustansystem_jobs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_job_batches`
--

DROP TABLE IF EXISTS `hindustansystem_job_batches`;
CREATE TABLE IF NOT EXISTS `hindustansystem_job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ledger_entries`
--

DROP TABLE IF EXISTS `hindustansystem_ledger_entries`;
CREATE TABLE IF NOT EXISTS `hindustansystem_ledger_entries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `voucher_id` bigint UNSIGNED NOT NULL,
  `voucher_line_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `running_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_ledger_entries_system_id_foreign` (`system_id`),
  KEY `hindustansystem_ledger_entries_account_id_foreign` (`account_id`),
  KEY `hindustansystem_ledger_entries_voucher_id_foreign` (`voucher_id`),
  KEY `hindustansystem_ledger_entries_voucher_line_id_foreign` (`voucher_line_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ledger_entries`
--

INSERT INTO `hindustansystem_ledger_entries` (`id`, `system_id`, `account_id`, `voucher_id`, `voucher_line_id`, `date`, `debit`, `credit`, `running_balance`, `created_at`, `updated_at`) VALUES
(5, 1, 18, 3, 5, '2026-07-10', 10000000.00, 0.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(6, 1, 19, 3, 6, '2026-07-10', 0.00, 10000000.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(7, 1, 2, 3, 7, '2026-07-10', 5433750.00, 0.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(8, 1, 18, 3, 8, '2026-07-10', 0.00, 5433750.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(9, 1, 3, 3, 9, '2026-07-10', 4016250.00, 0.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(10, 1, 18, 3, 10, '2026-07-10', 0.00, 4016250.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(11, 1, 6, 3, 11, '2026-07-10', 50000.00, 0.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(12, 1, 18, 3, 12, '2026-07-10', 0.00, 50000.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(13, 1, 20, 3, 13, '2026-07-10', 500000.00, 0.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(14, 1, 18, 3, 14, '2026-07-10', 0.00, 500000.00, 0.00, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(15, 1, 18, 4, 15, '2026-07-10', 3000000.00, 0.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(16, 1, 20, 4, 16, '2026-07-10', 0.00, 3000000.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(17, 1, 2, 4, 17, '2026-07-10', 1696250.00, 0.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(18, 1, 18, 4, 18, '2026-07-10', 0.00, 1696250.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(19, 1, 3, 4, 19, '2026-07-10', 1253750.00, 0.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(20, 1, 18, 4, 20, '2026-07-10', 0.00, 1253750.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(21, 1, 6, 4, 21, '2026-07-10', 50000.00, 0.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(22, 1, 18, 4, 22, '2026-07-10', 0.00, 50000.00, 0.00, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(23, 1, 8, 5, 23, '2026-07-20', 0.00, 18666.67, 0.00, '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(24, 1, 32, 5, 24, '2026-07-20', 16666.67, 0.00, 0.00, '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(25, 1, 28, 5, 25, '2026-07-20', 2000.00, 0.00, 0.00, '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(26, 1, 9, 6, 26, '2026-07-20', 20000.00, 0.00, 0.00, '2026-07-20 06:06:45', '2026-07-20 06:06:45'),
(27, 1, 13, 6, 27, '2026-07-20', 0.00, 20000.00, 0.00, '2026-07-20 06:06:45', '2026-07-20 06:06:45'),
(28, 1, 7, 7, 28, '2026-07-20', 0.00, 25208.33, 0.00, '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(29, 1, 26, 7, 29, '2026-07-20', 20833.33, 0.00, 0.00, '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(30, 1, 1, 7, 30, '2026-07-20', 4375.00, 0.00, 0.00, '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(31, 1, 10, 8, 31, '2026-07-20', 10200.00, 0.00, 0.00, '2026-07-20 06:26:31', '2026-07-20 06:26:31'),
(32, 1, 13, 8, 32, '2026-07-20', 0.00, 10200.00, 0.00, '2026-07-20 06:26:31', '2026-07-20 06:26:31'),
(37, 1, 2, 11, 37, '2026-07-20', 36666.67, 0.00, 0.00, '2026-07-20 07:03:33', '2026-07-20 07:03:33'),
(38, 1, 17, 11, 38, '2026-07-20', 0.00, 36666.67, 0.00, '2026-07-20 07:03:33', '2026-07-20 07:03:33'),
(39, 1, 25, 13, 41, '2026-07-21', 30000.00, 0.00, 0.00, '2026-07-21 06:58:07', '2026-07-21 06:58:07'),
(40, 1, 8, 13, 42, '2026-07-21', 0.00, 30000.00, 0.00, '2026-07-21 06:58:07', '2026-07-21 06:58:07'),
(41, 1, 1, 14, 43, '2026-07-22', 77000.00, 0.00, 0.00, '2026-07-22 01:23:30', '2026-07-22 01:23:30'),
(42, 1, 8, 14, 44, '2026-07-22', 0.00, 77000.00, 0.00, '2026-07-22 01:23:30', '2026-07-22 01:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loans`
--

DROP TABLE IF EXISTS `hindustansystem_loans`;
CREATE TABLE IF NOT EXISTS `hindustansystem_loans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `loan_account_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lender_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `tenure_months` int NOT NULL,
  `start_date` date NOT NULL,
  `schedule_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outstanding_balance` decimal(15,2) NOT NULL,
  `ledger_account_id` bigint UNSIGNED NOT NULL,
  `interest_account_id` bigint UNSIGNED NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_loans_system_id_foreign` (`system_id`),
  KEY `hindustansystem_loans_project_id_foreign` (`project_id`),
  KEY `l_ledger_fk` (`ledger_account_id`),
  KEY `l_interest_fk` (`interest_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_loans`
--

INSERT INTO `hindustansystem_loans` (`id`, `system_id`, `project_id`, `loan_account_no`, `lender_name`, `principal_amount`, `interest_rate`, `tenure_months`, `start_date`, `schedule_type`, `outstanding_balance`, `ledger_account_id`, `interest_account_id`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 'LN89023412', 'FEDERAL', 100000.00, 12.00, 12, '2026-07-15', 'flat', 100000.00, 3, 3, 'Active', '2026-07-15 06:50:54', '2026-07-15 06:54:52'),
(7, 1, 1, 'LN8923143', 'FEDERAL', 500000.00, 12.00, 25, '2026-07-16', 'reducing_balance', 500000.00, 2, 2, 'Active', '2026-07-15 23:51:55', '2026-07-15 23:51:55'),
(8, 1, 1, 'LN675689', 'HDFC', 500000.00, 12.00, 25, '2026-07-16', 'flat', 500000.00, 2, 2, 'Active', '2026-07-15 23:53:29', '2026-07-15 23:53:29'),
(9, 1, 1, 'LN232123', 'HDFC', 400000.00, 10.00, 12, '2026-06-16', 'flat', 400000.00, 2, 2, 'Active', '2026-07-16 03:34:51', '2026-07-16 03:34:51'),
(10, 1, 1, 'LN64578098', 'ICICI Bank', 100000.00, 15.00, 12, '2026-06-16', 'flat', 83333.34, 5, 5, 'Active', '2026-07-16 03:36:31', '2026-07-16 04:10:09'),
(11, 1, 1, 'LN123456', 'SBI', 800000.00, 10.00, 12, '2026-01-01', 'flat', 0.00, 2, 2, 'Closed', '2026-07-16 04:40:48', '2026-07-19 23:52:40'),
(13, 1, 1, 'LN32145766587', 'Union Bank', 500000.00, 10.50, 24, '2025-01-20', 'flat', 479166.67, 26, 1, 'Active', '2026-07-20 00:40:06', '2026-07-20 06:25:36'),
(14, 1, 1, 'LN80976', 'INDUS IND BANK', 300000.00, 10.00, 12, '2026-07-01', 'flat', 300000.00, 27, 28, 'Active', '2026-07-20 01:25:10', '2026-07-20 01:25:10'),
(15, 1, 1, 'LN3423', 'ICICI Bank', 2500000.00, 10.00, 12, '2026-08-20', 'flat', 2291666.67, 29, 9, 'Active', '2026-07-20 01:36:59', '2026-07-20 01:45:51'),
(16, 1, 1, 'LN809', 'IUB', 100000.00, 10.00, 12, '2026-07-20', 'flat', 91666.67, 30, 28, 'Active', '2026-07-20 01:43:00', '2026-07-20 01:45:26'),
(17, 1, 1, 'LN23232', 'IUB', 200000.00, 12.00, 12, '2026-07-20', 'flat', 166666.66, 32, 28, 'Active', '2026-07-20 01:50:33', '2026-07-20 02:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loan_interest_logs`
--

DROP TABLE IF EXISTS `hindustansystem_loan_interest_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_loan_interest_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `loan_id` bigint UNSIGNED NOT NULL,
  `old_interest_rate` decimal(5,2) NOT NULL,
  `new_interest_rate` decimal(5,2) NOT NULL,
  `interest_period` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'annual',
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_loan_interest_logs_loan_id_index` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loan_prepayments`
--

DROP TABLE IF EXISTS `hindustansystem_loan_prepayments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_loan_prepayments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `loan_id` bigint UNSIGNED NOT NULL,
  `prepayment_amount` decimal(15,2) NOT NULL,
  `prepayment_date` date NOT NULL,
  `reschedule_option` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_outstanding` decimal(15,2) NOT NULL,
  `new_outstanding` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_loan_prepayments_loan_id_foreign` (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_loan_prepayments`
--

INSERT INTO `hindustansystem_loan_prepayments` (`id`, `loan_id`, `prepayment_amount`, `prepayment_date`, `reschedule_option`, `previous_outstanding`, `new_outstanding`, `created_at`, `updated_at`) VALUES
(4, 11, 400000.00, '2026-07-20', 'reduce_tenure', 733333.33, 333333.33, '2026-07-19 23:40:39', '2026-07-19 23:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_migrations`
--

DROP TABLE IF EXISTS `hindustansystem_migrations`;
CREATE TABLE IF NOT EXISTS `hindustansystem_migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_migrations`
--

INSERT INTO `hindustansystem_migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_01_000001_create_erp_core_tables', 1),
(5, '2026_07_02_101751_create_permission_tables', 1),
(6, '2026_07_02_200000_create_module_one_tables', 1),
(7, '2026_07_02_300000_create_module_two_tables', 1),
(8, '2026_07_03_400000_create_modules_five_to_eight_tables', 1),
(9, '2026_07_06_143821_add_gst_and_broker_fields_to_bookings_table', 1),
(10, '2026_07_07_055342_create_units_table', 2),
(11, '2026_07_01_000001_create_erp_core_tables-old', 3),
(12, '2026_07_07_082944_add_image_url_to_projects_table', 4),
(13, '2026_07_07_090052_add_fields_to_customers_table', 5),
(14, '2026_07_07_091226_add_fields_to_is_active_hindustansystem_hindustan_units_table', 5),
(15, '2026_07_07_120000_add_is_active_to_hindustan_units_table', 5),
(16, '2026_07_07_130000_create_unit_logs_tables', 6),
(17, '2026_07_08_160000_add_project_id_to_unit_types_table', 6),
(18, '2026_07_08_180000_add_gst_fields_to_hindustan_units_table', 7),
(19, '2026_07_09_150000_fix_foreign_keys_to_hindustan_units', 8),
(20, '2026_07_08_180102_create_sales_table', 9),
(21, '2026_07_09_055416_add_gst_behavior_to_sales_table', 9),
(22, '2026_07_09_055700_create_receipts_and_brokerages_restructure_sales_table', 9),
(23, '2026_07_09_072219_add_fields_to_agreement_date_to_sales_table', 9),
(24, '2026_07_09_074258_add_broker_involved_to_sales_table', 9),
(25, '2026_07_09_082109_add_payment_plan_to_sales_table', 9),
(26, '2026_07_09_090319_add_remaining_balance_to_sales_table', 9),
(27, '2026_07_09_110622_add_payment_fields_to_emi_schedules_table', 10),
(28, '2026_07_09_200000_create_customer_installments_table', 11),
(29, '2026_07_09_210000_recreate_customer_installments_table', 11),
(30, '2026_07_09_113249_add_account_and_status_to_loans_table', 12),
(31, '2026_07_09_220000_add_return_fields_to_sales_table', 13),
(32, '2026_07_10_044223_add_partner_id_to_receipts_table', 13),
(33, '2026_07_10_051500_sync_sold_units_pricing_details', 14),
(34, '2026_07_10_062850_add_emi_plan_type_to_sales_table', 15),
(35, '2026_07_13_000000_create_banks_table', 16),
(36, '2026_07_13_110000_rename_existing_units_door_numbers', 17),
(37, '2026_07_13_120000_update_units_door_no_space_and_dynamic', 18),
(38, '2026_07_13_130000_apply_hybrid_unit_naming_convention', 19),
(39, '2026_07_13_000002_add_bank_id_to_sales_and_receipts_tables', 20),
(40, '2026_07_14_110714_update_existing_units_naming_conventions', 21),
(41, '2026_07_14_160000_create_sale_units_and_update_brokerages', 22),
(42, '2026_07_14_171000_update_sales_table_for_dynamic_emi', 23),
(43, '2026_07_15_065523_create_sale_extra_works_table', 23),
(44, '2026_07_16_054421_create_employees_table', 24),
(45, '2026_07_16_054744_add_details_to_payees_table', 24),
(46, '2026_07_16_100852_change_reference_no_to_text_in_vouchers_table', 25),
(47, '2026_07_17_053730_add_additional_fields_to_bills_table', 25),
(48, '2026_07_17_000001_make_payment_id_nullable_in_partner_allocations', 26),
(49, '2026_07_17_120817_create_loan_interest_logs_table', 27),
(50, '2026_07_20_054341_add_payment_type_to_receipts_table', 28),
(51, '2026_07_20_062134_create_emi_reschedule_logs_table', 28),
(52, '2026_07_20_113000_update_brokerages_status_enum', 29),
(53, '2026_07_20_124603_add_paid_amount_and_rescheduled_from_id_to_customer_installments_table', 30);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_permissions`
--

DROP TABLE IF EXISTS `hindustansystem_model_has_permissions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_roles`
--

DROP TABLE IF EXISTS `hindustansystem_model_has_roles`;
CREATE TABLE IF NOT EXISTS `hindustansystem_model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_model_has_roles`
--

INSERT INTO `hindustansystem_model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_partner_allocations`
--

DROP TABLE IF EXISTS `hindustansystem_partner_allocations`;
CREATE TABLE IF NOT EXISTS `hindustansystem_partner_allocations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `partner_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `allocated_amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_partner_allocations_system_id_foreign` (`system_id`),
  KEY `hindustansystem_partner_allocations_project_id_foreign` (`project_id`),
  KEY `hindustansystem_partner_allocations_payment_id_foreign` (`payment_id`),
  KEY `hindustansystem_partner_allocations_voucher_id_foreign` (`voucher_id`),
  KEY `pa_partner_fk` (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_partner_allocations`
--

INSERT INTO `hindustansystem_partner_allocations` (`id`, `system_id`, `partner_id`, `project_id`, `payment_id`, `allocated_amount`, `date`, `voucher_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 5433750.00, '2026-07-10', 3, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(2, 1, 2, 1, NULL, 4016250.00, '2026-07-10', 3, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(3, 1, 1, 1, NULL, 1696250.00, '2026-07-10', 4, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(4, 1, 2, 1, NULL, 1253750.00, '2026-07-10', 4, '2026-07-17 01:22:02', '2026-07-17 01:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_partner_shares`
--

DROP TABLE IF EXISTS `hindustansystem_partner_shares`;
CREATE TABLE IF NOT EXISTS `hindustansystem_partner_shares` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `partner_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `share_pct` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ps_sys_proj_part_unique` (`system_id`,`project_id`,`partner_id`),
  KEY `hindustansystem_partner_shares_project_id_foreign` (`project_id`),
  KEY `ps_partner_fk` (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_partner_shares`
--

INSERT INTO `hindustansystem_partner_shares` (`id`, `system_id`, `partner_id`, `project_id`, `share_pct`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 57.50, '2026-07-06 03:53:49', '2026-07-10 04:12:40'),
(2, 1, 2, 1, 42.50, '2026-07-06 03:53:49', '2026-07-10 04:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_password_reset_tokens`
--

DROP TABLE IF EXISTS `hindustansystem_password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `hindustansystem_password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payees`
--

DROP TABLE IF EXISTS `hindustansystem_payees`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gstin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `linked_account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payees_system_id_foreign` (`system_id`),
  KEY `hindustansystem_payees_linked_account_id_foreign` (`linked_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payees`
--

INSERT INTO `hindustansystem_payees` (`id`, `system_id`, `type`, `name`, `phone`, `email`, `gstin`, `pan`, `address`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Partner', 'Basheer', NULL, NULL, NULL, NULL, NULL, 2, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(2, 1, 'Partner', 'Pavoor', NULL, NULL, NULL, NULL, NULL, 3, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(4, 1, 'Supplier', 'GANESH (CEMENT)', '465687787', 'g2@gmail.com', '453545778', '1223454556', 'Kerala, India', 5, '2026-07-16 00:55:23', '2026-07-16 00:55:23'),
(5, 1, 'Supplier', 'RAMESH', '455466776', 'r2@gmail.com', '45343434', '2334345', 'Kerala, India', 6, '2026-07-16 01:01:15', '2026-07-16 01:01:15');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payments`
--

DROP TABLE IF EXISTS `hindustansystem_payments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_payments_receipt_number_unique` (`receipt_number`),
  KEY `hindustansystem_payments_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_payments_project_id_foreign` (`project_id`),
  KEY `hindustansystem_payments_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_permissions`
--

DROP TABLE IF EXISTS `hindustansystem_permissions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_permissions`
--

INSERT INTO `hindustansystem_permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'vouchers.manage', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(2, 'expenses.manage', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(3, 'expenses.approve', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(4, 'collections.view', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(5, 'reports.view', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(6, 'sales.create', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(7, 'sales.view', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(8, 'sales.discount.request', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(9, 'units.view', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(10, 'units.manage', 'web', '2026-07-06 03:53:37', '2026-07-06 03:53:37'),
(11, 'projects.manage', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(12, 'projects.view', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(13, 'units.rate.manage', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_petty_cash_accounts`
--

DROP TABLE IF EXISTS `hindustansystem_petty_cash_accounts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_petty_cash_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `float_limit` decimal(15,2) NOT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ledger_account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_petty_cash_accounts_system_id_project_id_unique` (`system_id`,`project_id`),
  KEY `hindustansystem_petty_cash_accounts_project_id_foreign` (`project_id`),
  KEY `pca_ledger_fk` (`ledger_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_petty_cash_entries`
--

DROP TABLE IF EXISTS `hindustansystem_petty_cash_entries`;
CREATE TABLE IF NOT EXISTS `hindustansystem_petty_cash_entries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `petty_cash_account_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_petty_cash_entries_system_id_foreign` (`system_id`),
  KEY `hindustansystem_petty_cash_entries_voucher_id_foreign` (`voucher_id`),
  KEY `pce_pca_fk` (`petty_cash_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_projects`
--

DROP TABLE IF EXISTS `hindustansystem_projects`;
CREATE TABLE IF NOT EXISTS `hindustansystem_projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_or_emirate` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rera_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_floors` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_projects_system_id_code_unique` (`system_id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_projects`
--

INSERT INTO `hindustansystem_projects` (`id`, `system_id`, `name`, `code`, `location`, `city`, `state_or_emirate`, `country`, `rera_number`, `total_floors`, `start_date`, `expected_completion_date`, `status`, `description`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tabasco Hindustan Infra Developers Pvt. Ltd', 'HEV-01', 'Kanhangad', 'Kasaragod', 'Kerala', 'India', NULL, 20, '2026-07-07', '2026-07-27', 'ongoing', 'The 1st RERA Approved property in Kasargod. \r\nTabasco Mall is positioned at the heart of a well-known city named Kanhangad, being very close to popular\r\nTourist Hubs such as the BekalFort, PallikereBeach, etc.\r\nIn addition, Railway stations, Bus stands, Schools, Colleges, Hospitals, and many other essential\r\ninfrastructures are integrated with in the city. The city is also\r\nlocated precisely in the middle of two International Airports, 90km away from each.', 'projects/f8nEZt3rX1tSCk9XueiVdcAzZFMApZp06TgWV4H3.jpg', 1, '2026-07-06 03:53:41', '2026-07-13 06:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_receipts`
--

DROP TABLE IF EXISTS `hindustansystem_receipts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_receipts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `receipt_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `partner_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_receipts_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_receipts_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_receipts_project_id_foreign` (`project_id`),
  KEY `hindustansystem_receipts_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_receipts_created_by_foreign` (`created_by`),
  KEY `hindustansystem_receipts_partner_id_foreign` (`partner_id`),
  KEY `hindustansystem_receipts_bank_id_foreign` (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipts`
--

INSERT INTO `hindustansystem_receipts` (`id`, `sale_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `payment_mode`, `payment_type`, `reference_no`, `bank_id`, `remarks`, `created_by`, `partner_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, 41, '2026-07-10', 10000000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-09 23:30:21', '2026-07-09 23:30:21'),
(2, 1, 4, 1, 41, '2026-07-10', 46844500.00, 'Bank Transfer', 'regular', NULL, NULL, NULL, 1, NULL, '2026-07-09 23:56:17', '2026-07-09 23:56:17'),
(4, 6, 5, 1, 87, '2026-07-10', 3000000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-10 00:19:21', '2026-07-10 00:19:21'),
(5, 6, 5, 1, 87, '2026-07-10', 2520000.00, 'Cash', 'regular', NULL, NULL, NULL, 1, 1, '2026-07-10 00:21:12', '2026-07-10 00:21:12'),
(6, 7, 4, 1, 43, '2026-07-10', 1000000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(14, 13, 5, 1, 44, '2026-07-10', 1775000.00, 'Cheque', 'regular', '1234', NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(15, 13, 5, 1, 44, '2026-07-10', 1065000.00, 'Cheque', 'regular', '1234', NULL, 'Share of collection (60%) from receipt #14', 1, 1, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(16, 13, 5, 1, 44, '2026-07-10', 710000.00, 'Cheque', 'regular', '1234', NULL, 'Share of collection (40%) from receipt #14', 1, 2, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(17, 14, 4, 1, 47, '2026-07-10', 1511400.00, 'Bank Transfer', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(18, 14, 4, 1, 47, '2026-07-10', 869055.00, 'Bank Transfer', 'regular', NULL, NULL, 'Share of collection (57.5%) from receipt #17', 1, 1, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(19, 14, 4, 1, 47, '2026-07-10', 642345.00, 'Bank Transfer', 'regular', NULL, NULL, 'Share of collection (42.5%) from receipt #17', 1, 2, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(20, 17, 8, 1, 46, '2026-07-17', 1426000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-17 03:38:11', '2026-07-17 03:38:11'),
(21, 17, 8, 1, 46, '2026-07-17', 85200.00, 'Cheque', 'regular', 'cq364', NULL, 'Optional', 1, NULL, '2026-07-17 03:39:18', '2026-07-17 03:39:18'),
(22, 18, 8, 1, 155, '2026-07-17', 150000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(23, 19, 8, 1, 84, '2026-07-17', 200000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(24, 20, 8, 1, 74, '2026-07-17', 200000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(25, 20, 8, 1, 74, '2026-07-17', 10000.00, 'Cash', 'regular', NULL, NULL, NULL, 1, NULL, '2026-07-17 04:17:47', '2026-07-17 04:17:47'),
(26, 21, 8, 1, 85, '2026-07-20', 300000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-20 02:04:45', '2026-07-20 02:04:45'),
(27, 22, 5, 1, 86, '2026-07-20', 200000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-20 06:31:36', '2026-07-20 06:31:36'),
(32, 22, 5, 1, 86, '2026-07-20', 25000.00, 'Cash', 'regular', NULL, NULL, 'ytfty', 1, NULL, '2026-07-20 07:25:55', '2026-07-20 07:25:55'),
(33, 22, 5, 1, 86, '2026-07-20', 25000.00, 'Cash', 'regular', NULL, NULL, 'fd6tr', 1, NULL, '2026-07-20 07:26:10', '2026-07-20 07:26:10'),
(34, 21, 8, 1, 85, '2026-07-21', 100000.00, 'Cash', 'regular', NULL, NULL, 'test brokerage', 1, NULL, '2026-07-21 06:51:15', '2026-07-21 06:51:15'),
(35, 23, 8, 1, 99, '2026-07-22', 1000000.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(36, 24, 9, 1, 114, '2026-07-22', 1861150.00, 'Cash', 'regular', NULL, NULL, 'Initial payment at sale creation', 1, NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_replenishment_requests`
--

DROP TABLE IF EXISTS `hindustansystem_replenishment_requests`;
CREATE TABLE IF NOT EXISTS `hindustansystem_replenishment_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `petty_cash_account_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_replenishment_requests_system_id_foreign` (`system_id`),
  KEY `hindustansystem_replenishment_requests_requested_by_foreign` (`requested_by`),
  KEY `hindustansystem_replenishment_requests_approved_by_foreign` (`approved_by`),
  KEY `hindustansystem_replenishment_requests_voucher_id_foreign` (`voucher_id`),
  KEY `rr_pca_fk` (`petty_cash_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_roles`
--

DROP TABLE IF EXISTS `hindustansystem_roles`;
CREATE TABLE IF NOT EXISTS `hindustansystem_roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_roles`
--

INSERT INTO `hindustansystem_roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Owner', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(2, 'Accountant', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(3, 'Sales', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(4, 'Site', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_role_has_permissions`
--

DROP TABLE IF EXISTS `hindustansystem_role_has_permissions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `hindustansystem_role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_role_has_permissions`
--

INSERT INTO `hindustansystem_role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(9, 2),
(13, 2),
(4, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(9, 4),
(10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sales`
--

DROP TABLE IF EXISTS `hindustansystem_sales`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sales` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `broker_id` bigint UNSIGNED DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `rate_per_sqft` decimal(15,2) DEFAULT NULL,
  `sale_amount` decimal(15,2) NOT NULL,
  `gst_applicable` tinyint(1) NOT NULL DEFAULT '0',
  `gst_type` enum('none','inclusive','exclusive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `base_amount` decimal(15,2) DEFAULT NULL,
  `gst_percentage` decimal(5,2) DEFAULT NULL,
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL,
  `sale_date` date NOT NULL,
  `status` enum('active','cancelled','returned','exchanged','resale') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remaining_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `broker_involved` tinyint(1) NOT NULL DEFAULT '0',
  `initial_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_mode` enum('cash','cheque','bank_transfer','upi','demand_draft') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `payment_plan` enum('lump_sum','emi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lump_sum',
  `emi_type` enum('equal','milestone') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emi_installment_count` int DEFAULT NULL,
  `emi_frequency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_installment_date` date DEFAULT NULL,
  `original_sale_id` bigint UNSIGNED DEFAULT NULL,
  `is_resale` tinyint(1) NOT NULL DEFAULT '0',
  `cancellation_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `refund_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_sales_sale_number_unique` (`sale_number`),
  KEY `hindustansystem_sales_project_id_foreign` (`project_id`),
  KEY `hindustansystem_sales_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_sales_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_sales_broker_id_foreign` (`broker_id`),
  KEY `hindustansystem_sales_original_sale_id_foreign` (`original_sale_id`),
  KEY `hindustansystem_sales_created_by_foreign` (`created_by`),
  KEY `hindustansystem_sales_bank_id_foreign` (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales`
--

INSERT INTO `hindustansystem_sales` (`id`, `sale_number`, `project_id`, `unit_id`, `customer_id`, `broker_id`, `agreement_date`, `registration_date`, `rate_per_sqft`, `sale_amount`, `gst_applicable`, `gst_type`, `base_amount`, `gst_percentage`, `gst_amount`, `total_amount`, `sale_date`, `status`, `remaining_balance`, `broker_involved`, `initial_payment`, `payment_mode`, `reference_no`, `bank_id`, `remarks`, `payment_plan`, `emi_type`, `emi_installment_count`, `emi_frequency`, `first_installment_date`, `original_sale_id`, `is_resale`, `cancellation_reason`, `cancelled_at`, `cancellation_fee`, `refund_amount`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SL-6A507C65855A7', 1, 41, 4, 1, '2026-07-10', '2026-07-11', 11500.00, 56844500.00, 0, 'none', 56844500.00, NULL, 0.00, 56844500.00, '2026-07-10', 'active', 0.00, 1, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0.00, 'Cash Tight', 1, '2026-07-09 23:30:21', '2026-07-09 23:56:17', NULL),
(4, 'SL-6A507F1415321', 1, 43, 4, NULL, '2026-07-10', NULL, 11000.00, 3410000.00, 1, 'exclusive', 3410000.00, 18.00, 613800.00, 4023800.00, '2026-07-10', 'cancelled', 4023800.00, 0, 0.00, NULL, NULL, NULL, NULL, 'lump_sum', NULL, NULL, NULL, NULL, NULL, 0, 'Customer changed mind', '2026-07-09 23:41:48', 0.00, 0.00, 'Exchanged from sale SL-6A507F13EFDBF. Customer requested a different floor', 1, '2026-07-09 23:41:48', '2026-07-09 23:41:48', '2026-07-09 23:41:48'),
(6, 'SL-6A5087E165A06', 1, 87, 5, NULL, '2026-07-10', '2026-07-10', 11500.00, 5520000.00, 0, 'none', 5520000.00, NULL, 0.00, 5520000.00, '2026-07-10', 'cancelled', 0.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, 'ni', '2026-07-10 03:57:51', 0.00, 500000.00, 'EMI installments', 1, '2026-07-10 00:19:21', '2026-07-17 01:16:22', NULL),
(7, 'SL-6A5088EA6FF3C', 1, 43, 4, NULL, '2026-07-10', '2026-07-10', 11500.00, 3565000.00, 0, 'none', 3565000.00, NULL, 0.00, 3565000.00, '2026-07-10', 'active', 2565000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0.00, NULL, 1, '2026-07-10 00:23:46', '2026-07-10 00:23:46', NULL),
(8, 'SL-6A5097362AD28', 1, 44, 5, NULL, '2026-07-10', '2026-07-10', 11500.00, 3266000.00, 0, 'none', 3266000.00, NULL, 0.00, 3266000.00, '2026-07-10', 'exchanged', 3266000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, 'financial issue', '2026-07-10 03:54:19', 0.00, 0.00, NULL, 1, '2026-07-10 01:24:46', '2026-07-10 03:54:19', NULL),
(13, 'SL-6A50BCFB68F78', 1, 44, 5, 2, '2026-07-10', '2026-07-10', 12500.00, 3550000.00, 0, 'none', 3550000.00, NULL, 0.00, 3550000.00, '2026-07-10', 'active', 1775000.00, 1, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0.00, 'test case', 1, '2026-07-10 04:05:55', '2026-07-10 04:05:55', NULL),
(14, 'SL-6A50D24DD9AD0', 1, 47, 4, NULL, '2026-07-10', '2026-07-10', 16500.00, 3778500.00, 0, 'none', 3778500.00, NULL, 0.00, 3778500.00, '2026-07-10', 'active', 2267100.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0.00, 'na', 1, '2026-07-10 05:36:53', '2026-07-10 05:36:53', NULL),
(15, 'SL-6A58C2140352F', 1, 45, 4, 1, '2026-07-10', '2026-07-11', 11500.00, 3408000.00, 0, 'none', 3408000.00, NULL, 0.00, 3408000.00, '2026-07-10', 'active', 0.00, 1, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0.00, 'Cash Tight', 1, '2026-07-16 06:05:48', '2026-07-16 06:05:48', NULL),
(16, 'SL-6A58C21409A8B', 1, 89, 5, 1, '2026-07-10', '2026-07-11', 11500.00, 3449800.00, 0, 'none', 3449800.00, NULL, 0.00, 3449800.00, '2026-07-10', 'active', 0.00, 1, 0.00, NULL, NULL, NULL, NULL, 'emi', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0.00, 'Cash Tight', 1, '2026-07-16 06:05:48', '2026-07-16 06:05:48', NULL),
(17, 'SL-6A59F0FADDCC5', 1, 46, 8, NULL, '2026-07-17', '2026-07-17', 12000.00, 2852000.00, 0, 'none', 2852000.00, NULL, 0.00, 2852000.00, '2026-07-17', 'active', 1340800.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 5, 'monthly', '2026-07-17', NULL, 0, NULL, NULL, 0.00, 0.00, 'Optional sale', NULL, '2026-07-17 03:38:10', '2026-07-17 03:39:18', NULL),
(18, 'SL-6A59F5D5ABE94', 1, 155, 8, NULL, '2026-07-17', '2026-07-17', 0.00, 300000.00, 0, 'none', 300000.00, NULL, 0.00, 300000.00, '2026-07-17', 'active', 150000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 8, 'monthly', '2026-07-17', NULL, 0, NULL, NULL, 0.00, 0.00, 'Parking sold out...', NULL, '2026-07-17 03:58:53', '2026-07-17 03:58:53', NULL),
(19, 'SL-6A59F947C8911', 1, 84, 8, NULL, '2026-07-17', '2026-07-17', 0.00, 400000.00, 0, 'none', 400000.00, NULL, 0.00, 400000.00, '2026-07-17', 'active', 200000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 8, 'monthly', '2026-08-17', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-07-17 04:13:35', '2026-07-17 04:13:35', NULL),
(20, 'SL-6A59F9922F8BA', 1, 74, 8, NULL, '2026-07-17', NULL, 0.00, 400000.00, 0, 'none', 400000.00, NULL, 0.00, 400000.00, '2026-07-17', 'active', 190000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 8, 'monthly', '2026-08-17', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-07-17 04:14:50', '2026-07-17 04:17:47', NULL),
(21, 'SL-6A5DCF9520957', 1, 85, 8, 3, '2026-07-20', '2026-07-20', 0.00, 400000.00, 0, 'none', 400000.00, NULL, 0.00, 400000.00, '2026-07-20', 'active', 0.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 12, 'monthly', '2026-08-20', NULL, 0, NULL, NULL, 0.00, 0.00, 'sale for checking brokerage', NULL, '2026-07-20 02:04:45', '2026-07-21 06:51:15', NULL),
(22, 'SL-6A5E0E200AC5C', 1, 86, 5, 2, '2026-07-20', '2026-07-20', 0.00, 400000.00, 0, 'none', 400000.00, NULL, 0.00, 400000.00, '2026-07-20', 'active', 150000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-08-20', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-07-20 06:31:36', '2026-07-20 07:26:10', NULL),
(23, 'SL-6A6094AE86CC9', 1, 99, 8, NULL, '2026-07-22', NULL, 3800.00, 3820200.00, 0, 'exclusive', 3820200.00, 18.00, 669636.00, 4489836.00, '2026-07-22', 'active', 3489836.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 6, 'monthly', '2026-08-22', NULL, 0, NULL, NULL, 0.00, 0.00, 'ygfy', NULL, '2026-07-22 04:30:14', '2026-07-22 04:30:14', NULL),
(24, 'SL-6A6097EC6AC99', 1, 114, 9, NULL, '2026-07-22', '2026-07-22', 3700.00, 3722300.00, 0, 'none', 3722300.00, NULL, 0.00, 3722300.00, '2026-07-22', 'active', 1861150.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 6, 'monthly', '2026-08-22', NULL, 0, NULL, NULL, 0.00, 0.00, 'hygtyhgyh', NULL, '2026-07-22 04:44:04', '2026-07-22 04:44:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sales_executives`
--

DROP TABLE IF EXISTS `hindustansystem_sales_executives`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sales_executives` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_sales_executives_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales_executives`
--

INSERT INTO `hindustansystem_sales_executives` (`id`, `name`, `email`, `avatar_url`, `created_at`, `updated_at`) VALUES
(1, 'Vikram Sharma', 'vikram@hindustan.com', 'VS', '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(2, 'Priya Nair', 'priya@hindustan.com', 'PN', '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sale_extra_works`
--

DROP TABLE IF EXISTS `hindustansystem_sale_extra_works`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sale_extra_works` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `gst_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT '18.00',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_sale_extra_works_sale_id_foreign` (`sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_extra_works`
--

INSERT INTO `hindustansystem_sale_extra_works` (`id`, `sale_id`, `description`, `amount`, `gst_type`, `gst_percentage`, `gst_amount`, `line_total`, `created_at`, `updated_at`) VALUES
(1, 23, 'fcgtfdt', 100000.00, 'none', 0.00, 0.00, 100000.00, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(2, 24, 'hghghyg', 100000.00, 'none', 0.00, 0.00, 100000.00, '2026-07-22 04:44:04', '2026-07-22 04:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sale_status_logs`
--

DROP TABLE IF EXISTS `hindustansystem_sale_status_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sale_status_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `from_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_sale_status_logs_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_sale_status_logs_performed_by_foreign` (`performed_by`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_status_logs`
--

INSERT INTO `hindustansystem_sale_status_logs` (`id`, `sale_id`, `from_status`, `to_status`, `event_type`, `reason`, `performed_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'active', 'created', NULL, 1, '2026-07-09 23:30:21', '2026-07-09 23:30:21'),
(8, 6, NULL, 'active', 'created', NULL, 1, '2026-07-10 00:19:21', '2026-07-10 00:19:21'),
(9, 7, NULL, 'active', 'created', NULL, 1, '2026-07-10 00:23:46', '2026-07-10 00:23:46'),
(10, 8, NULL, 'active', 'created', NULL, 1, '2026-07-10 01:24:46', '2026-07-10 01:24:46'),
(16, 8, 'active', 'exchanged', 'exchanged', 'financial issue', 1, '2026-07-10 03:54:19', '2026-07-10 03:54:19'),
(18, 6, 'active', 'cancelled', 'cancelled', 'ni', 1, '2026-07-10 03:57:51', '2026-07-10 03:57:51'),
(19, 13, NULL, 'active', 'created', NULL, 1, '2026-07-10 04:05:55', '2026-07-10 04:05:55'),
(20, 14, NULL, 'active', 'created', NULL, 1, '2026-07-10 05:36:53', '2026-07-10 05:36:53'),
(21, 17, NULL, 'active', 'created', NULL, 1, '2026-07-17 03:38:10', '2026-07-17 03:38:10'),
(22, 18, NULL, 'active', 'created', NULL, 1, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(23, 19, NULL, 'active', 'created', NULL, 1, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(24, 20, NULL, 'active', 'created', NULL, 1, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(25, 21, NULL, 'active', 'created', NULL, 1, '2026-07-20 02:04:45', '2026-07-20 02:04:45'),
(26, 22, NULL, 'active', 'created', NULL, 1, '2026-07-20 06:31:36', '2026-07-20 06:31:36'),
(27, 23, NULL, 'active', 'created', NULL, 1, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(28, 24, NULL, 'active', 'created', NULL, 1, '2026-07-22 04:44:04', '2026-07-22 04:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sale_units`
--

DROP TABLE IF EXISTS `hindustansystem_sale_units`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sale_units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `wing` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_per_sqft` decimal(15,2) NOT NULL,
  `area_sqft` decimal(15,2) NOT NULL,
  `base_amount` decimal(15,2) NOT NULL,
  `gst_type` enum('none','inclusive','exclusive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(15,2) NOT NULL,
  `brokerage_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brokerage_value` decimal(15,2) DEFAULT NULL,
  `brokerage_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_sale_units_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_sale_units_unit_id_foreign` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_units`
--

INSERT INTO `hindustansystem_sale_units` (`id`, `sale_id`, `unit_id`, `wing`, `rate_per_sqft`, `area_sqft`, `base_amount`, `gst_type`, `gst_percentage`, `gst_amount`, `line_total`, `brokerage_type`, `brokerage_value`, `brokerage_amount`, `created_at`, `updated_at`) VALUES
(1, 17, 46, NULL, 12000.00, 248.00, 2852000.00, 'none', 0.00, 0.00, 2852000.00, NULL, NULL, 0.00, '2026-07-17 03:38:10', '2026-07-17 03:38:10'),
(2, 18, 155, NULL, 0.00, 1.00, 300000.00, 'none', 0.00, 0.00, 300000.00, NULL, NULL, 0.00, '2026-07-17 03:58:53', '2026-07-17 03:58:53'),
(3, 19, 84, NULL, 0.00, 1.00, 400000.00, 'none', 0.00, 0.00, 400000.00, NULL, NULL, 0.00, '2026-07-17 04:13:35', '2026-07-17 04:13:35'),
(4, 20, 74, NULL, 0.00, 1.00, 400000.00, 'none', 0.00, 0.00, 400000.00, NULL, NULL, 0.00, '2026-07-17 04:14:50', '2026-07-17 04:14:50'),
(5, 21, 85, NULL, 0.00, 1.00, 400000.00, 'none', 0.00, 0.00, 400000.00, NULL, NULL, 0.00, '2026-07-20 02:04:45', '2026-07-20 02:04:45'),
(6, 22, 86, NULL, 0.00, 1.00, 400000.00, 'none', 0.00, 0.00, 400000.00, NULL, NULL, 0.00, '2026-07-20 06:31:36', '2026-07-20 06:31:36'),
(7, 23, 99, NULL, 3800.00, 979.00, 3720200.00, 'exclusive', 18.00, 669636.00, 4389836.00, NULL, NULL, 0.00, '2026-07-22 04:30:14', '2026-07-22 04:30:14'),
(8, 24, 114, NULL, 3700.00, 979.00, 3622300.00, 'none', 0.00, 0.00, 3622300.00, NULL, NULL, 0.00, '2026-07-22 04:44:04', '2026-07-22 04:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sessions`
--

DROP TABLE IF EXISTS `hindustansystem_sessions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_sessions_user_id_index` (`user_id`),
  KEY `hindustansystem_sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sessions`
--

INSERT INTO `hindustansystem_sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EN8h6k4PpQb0IvrqjedZ3n0OfkEfVjfMRbvWoMh7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJkZnpJNDRsT2lEOUt5d2VDVlJaS0RINEdEck5QekRIWGpaVllsQlZjIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zYWxlcyIsInJvdXRlIjoic2FsZXMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1784718819);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_systems`
--

DROP TABLE IF EXISTS `hindustansystem_systems`;
CREATE TABLE IF NOT EXISTS `hindustansystem_systems` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `vat_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_systems_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_systems`
--

INSERT INTO `hindustansystem_systems` (`id`, `name`, `code`, `country`, `currency_code`, `gst_enabled`, `vat_enabled`, `timezone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'India System', 'IN', 'India', 'INR', 1, 0, 'Asia/Kolkata', 1, '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(2, 'UAE System', 'AE', 'UAE', 'AED', 0, 1, 'Asia/Dubai', 1, '2026-07-06 03:53:38', '2026-07-06 03:53:38');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_units`
--

DROP TABLE IF EXISTS `hindustansystem_units`;
CREATE TABLE IF NOT EXISTS `hindustansystem_units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `floor_id` bigint UNSIGNED NOT NULL,
  `unit_type_id` bigint UNSIGNED NOT NULL,
  `unit_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bua_area` decimal(10,2) NOT NULL,
  `carpet_area` decimal(10,2) DEFAULT NULL,
  `area_unit` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sqft',
  `facing` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `base_rate` decimal(15,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_units_project_id_unit_number_unique` (`project_id`,`unit_number`),
  KEY `hindustansystem_units_floor_id_foreign` (`floor_id`),
  KEY `hindustansystem_units_unit_type_id_foreign` (`unit_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_rate_logs`
--

DROP TABLE IF EXISTS `hindustansystem_unit_rate_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_unit_rate_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` bigint UNSIGNED NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `effective_from` date NOT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `reason` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_unit_rate_logs_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_unit_rate_logs_changed_by_foreign` (`changed_by`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_rate_logs`
--

INSERT INTO `hindustansystem_unit_rate_logs` (`id`, `unit_id`, `rate`, `effective_from`, `changed_by`, `reason`, `created_at`) VALUES
(44, 41, 10000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:43:27'),
(46, 43, 11000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:44:57'),
(47, 44, 12000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:45:38'),
(48, 45, 12000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:47:34'),
(49, 46, 12000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:47:59'),
(50, 47, 17000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:48:27'),
(51, 48, 23000.00, '2026-07-09', 1, 'Initial Rate', '2026-07-09 01:49:01'),
(74, 73, 300000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:11:39'),
(75, 74, 300000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:11:53'),
(76, 75, 12000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:13:46'),
(77, 76, 12000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:14:39'),
(78, 77, 11000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:15:49'),
(79, 78, 14000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:16:55'),
(80, 79, 14000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:17:24'),
(81, 80, 14000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:19:44'),
(82, 81, 15000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:20:29'),
(83, 82, 15000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:21:03'),
(84, 83, 15000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:21:53'),
(85, 84, 400000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:22:48'),
(86, 85, 400000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:23:11'),
(87, 86, 400000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-09 23:23:25'),
(88, 87, 11000.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 00:16:37'),
(89, 88, 5800.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:07:44'),
(90, 89, 4700.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:08:44'),
(91, 90, 3800.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:09:27'),
(92, 91, 3400.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:14:02'),
(93, 92, 3500.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:15:18'),
(94, 93, 5800.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:16:00'),
(95, 94, 3500.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:54:31'),
(96, 95, 3500.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:55:10'),
(97, 96, 5500.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:56:02'),
(98, 97, 3600.00, '2026-07-10', 1, 'Initial Rate', '2026-07-10 02:56:39'),
(100, 99, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:33:06'),
(101, 100, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:33:54'),
(102, 101, 4500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:34:35'),
(103, 102, 4200.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:35:16'),
(104, 103, 3800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:36:00'),
(105, 104, 3800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:38:38'),
(106, 105, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:39:16'),
(107, 106, 3600.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:39:58'),
(108, 107, 4200.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:40:34'),
(109, 108, 4500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:41:00'),
(110, 109, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:42:36'),
(111, 110, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:43:10'),
(112, 111, 4500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:43:51'),
(113, 112, 5000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:44:58'),
(114, 113, 4800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:45:24'),
(115, 114, 3700.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:45:58'),
(116, 115, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:46:29'),
(117, 116, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:47:07'),
(118, 117, 3300.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:47:47'),
(119, 118, 5500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:48:23'),
(120, 119, 5000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:50:24'),
(121, 120, 4000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:51:14'),
(122, 121, 3600.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:51:56'),
(123, 122, 5500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:52:24'),
(124, 123, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:53:01'),
(125, 124, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:53:37'),
(126, 125, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:54:25'),
(127, 126, 4800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:55:09'),
(128, 127, 4500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:55:40'),
(129, 128, 4000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:57:53'),
(130, 129, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:58:33'),
(131, 130, 3600.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:59:14'),
(132, 131, 3600.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 03:59:57'),
(133, 132, 6000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:00:29'),
(134, 133, 3800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:02:10'),
(135, 134, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:02:38'),
(136, 135, 5800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:03:32'),
(137, 136, 5500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:04:00'),
(138, 137, 3400.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:04:26'),
(139, 138, 3800.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:06:58'),
(140, 139, 4000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:07:26'),
(141, 140, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:08:00'),
(142, 141, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:08:43'),
(143, 142, 3600.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:09:28'),
(144, 143, 3500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:10:10'),
(145, 144, 4000.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:10:37'),
(146, 145, 4500.00, '2026-07-14', 1, 'Initial Rate', '2026-07-14 04:11:08'),
(156, 155, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(157, 156, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(158, 157, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(159, 158, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(160, 159, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(161, 160, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(162, 161, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(163, 162, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(164, 163, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(165, 164, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(166, 165, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(167, 166, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(168, 167, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(169, 168, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(170, 169, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(171, 170, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(172, 171, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(173, 172, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(174, 173, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(175, 174, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(176, 175, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(177, 176, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(178, 177, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(179, 178, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(180, 179, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(181, 180, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(182, 181, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(183, 182, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(184, 183, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(185, 184, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(186, 185, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(187, 186, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(188, 187, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(189, 188, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(190, 189, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(191, 190, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(192, 191, 300000.00, '2026-07-14', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(193, 41, 4500.00, '2026-07-15', 1, NULL, '2026-07-15 04:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_status_logs`
--

DROP TABLE IF EXISTS `hindustansystem_unit_status_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_unit_status_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` bigint UNSIGNED NOT NULL,
  `from_status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `reason` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_unit_status_logs_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_unit_status_logs_changed_by_foreign` (`changed_by`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_status_logs`
--

INSERT INTO `hindustansystem_unit_status_logs` (`id`, `unit_id`, `from_status`, `to_status`, `changed_by`, `reason`, `created_at`) VALUES
(93, 41, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:43:27'),
(95, 43, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:44:57'),
(96, 44, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:45:38'),
(97, 45, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:47:34'),
(98, 46, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:47:59'),
(99, 47, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:48:27'),
(100, 48, NULL, 'available', 1, 'Unit creation', '2026-07-09 01:49:01'),
(123, 73, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:11:39'),
(124, 74, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:11:53'),
(125, 75, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:13:46'),
(126, 76, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:14:39'),
(127, 77, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:15:49'),
(128, 78, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:16:55'),
(129, 79, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:17:24'),
(130, 80, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:19:44'),
(131, 81, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:20:29'),
(132, 82, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:21:03'),
(133, 83, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:21:53'),
(134, 84, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:22:48'),
(135, 85, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:23:11'),
(136, 86, NULL, 'available', 1, 'Unit creation', '2026-07-09 23:23:25'),
(138, 87, NULL, 'available', 1, 'Unit creation', '2026-07-10 00:16:37'),
(139, 88, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:07:44'),
(140, 89, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:08:44'),
(141, 90, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:09:27'),
(142, 91, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:14:02'),
(143, 92, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:15:18'),
(144, 93, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:16:00'),
(145, 94, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:54:31'),
(146, 95, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:55:10'),
(147, 96, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:56:02'),
(148, 97, NULL, 'available', 1, 'Unit creation', '2026-07-10 02:56:39'),
(149, 46, 'available', 'blocked', 1, NULL, '2026-07-10 03:32:26'),
(150, 46, 'blocked', 'available', 1, NULL, '2026-07-10 03:32:29'),
(152, 99, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:33:06'),
(153, 100, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:33:54'),
(154, 101, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:34:35'),
(155, 102, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:35:16'),
(156, 103, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:36:00'),
(157, 104, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:38:38'),
(158, 105, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:39:16'),
(159, 106, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:39:58'),
(160, 107, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:40:34'),
(161, 108, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:41:00'),
(162, 109, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:42:36'),
(163, 110, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:43:10'),
(164, 111, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:43:51'),
(165, 112, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:44:58'),
(166, 113, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:45:24'),
(167, 114, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:45:58'),
(168, 115, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:46:29'),
(169, 116, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:47:07'),
(170, 117, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:47:47'),
(171, 118, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:48:23'),
(172, 119, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:50:24'),
(173, 120, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:51:14'),
(174, 121, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:51:56'),
(175, 122, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:52:24'),
(176, 123, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:53:01'),
(177, 124, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:53:37'),
(178, 125, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:54:25'),
(179, 126, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:55:09'),
(180, 127, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:55:40'),
(181, 128, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:57:53'),
(182, 129, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:58:33'),
(183, 130, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:59:14'),
(184, 131, NULL, 'available', 1, 'Unit creation', '2026-07-14 03:59:57'),
(185, 132, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:00:29'),
(186, 133, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:02:10'),
(187, 134, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:02:38'),
(188, 135, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:03:32'),
(189, 136, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:04:00'),
(190, 137, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:04:26'),
(191, 138, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:06:58'),
(192, 139, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:07:26'),
(193, 140, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:08:00'),
(194, 141, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:08:43'),
(195, 142, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:09:28'),
(196, 143, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:10:10'),
(197, 144, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:10:37'),
(198, 145, NULL, 'available', 1, 'Unit creation', '2026-07-14 04:11:08'),
(208, 155, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(209, 156, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(210, 157, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(211, 158, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(212, 159, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(213, 160, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(214, 161, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(215, 162, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(216, 163, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(217, 164, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(218, 165, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(219, 166, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(220, 167, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(221, 168, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(222, 169, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(223, 170, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(224, 171, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(225, 172, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(226, 173, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(227, 174, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(228, 175, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(229, 176, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(230, 177, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(231, 178, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(232, 179, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(233, 180, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(234, 181, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(235, 182, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(236, 183, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(237, 184, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(238, 185, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(239, 186, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(240, 187, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(241, 188, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(242, 189, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(243, 190, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05'),
(244, 191, NULL, 'available', 1, 'Bulk creation', '2026-07-14 04:38:05');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_types`
--

DROP TABLE IF EXISTS `hindustansystem_unit_types`;
CREATE TABLE IF NOT EXISTS `hindustansystem_unit_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_unit_types_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_types`
--

INSERT INTO `hindustansystem_unit_types` (`id`, `project_id`, `name`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'Shop', 'commercial', 1, '2026-07-06 03:53:39', '2026-07-08 04:40:33'),
(5, 1, 'Parking', 'parking', 1, '2026-07-06 03:53:39', '2026-07-08 04:40:39'),
(6, 1, 'Apartment', 'residential', 1, '2026-07-10 02:06:51', '2026-07-10 02:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_users`
--

DROP TABLE IF EXISTS `hindustansystem_users`;
CREATE TABLE IF NOT EXISTS `hindustansystem_users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_users_email_unique` (`email`),
  UNIQUE KEY `hindustansystem_users_employee_code_unique` (`employee_code`),
  KEY `hindustansystem_users_system_id_foreign` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_users`
--

INSERT INTO `hindustansystem_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `system_id`, `phone`, `employee_code`, `status`, `must_change_password`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Owner', 'owner@hindustan.com', '2026-07-06 03:53:39', '$2y$12$q1CE0lNCVgHBiMgVS2WQyeWdk0VYl/OyTRq9GEQrxj0tWuulWSCoq', 'chw8m63JCpW9351Nj0K2AWeHGFozbVewPHQcIfozxw3VV32Wwi5WFeV31E86', '2026-07-06 03:53:39', '2026-07-06 03:53:39', 1, '+91 99999 99999', 'EMP-001', 'active', 0, NULL, NULL),
(2, 'Rajesh Accountant (IN)', 'accountant.in@hindustan.com', '2026-07-06 03:53:40', '$2y$12$rVofIQZI2tIVy/jHVFLVPOyEJC2tVyvtMlUSsiLb.mLVUQRVh/W6m', NULL, '2026-07-06 03:53:40', '2026-07-09 03:35:53', 1, '+91 98765 00001', 'EMP-IN-ACC01', 'active', 0, NULL, NULL),
(3, 'Omar Accountant (UAE)', 'accountant.ae@hindustan.com', '2026-07-06 03:53:40', '$2y$12$nhCUlYVdehjhoRDNzzde9.stFLNk3mZ/j0du.HsDo9mMygrk2oi5y', NULL, '2026-07-06 03:53:40', '2026-07-06 03:53:40', 2, '+971 50 123 4567', 'EMP-AE-ACC01', 'active', 0, NULL, NULL),
(4, 'Vikram Sales (IN)', 'sales.in@hindustan.com', '2026-07-06 03:53:41', '$2y$12$1AO5aTilUgSLi2SM1tPv4uYtmKrQH1a7/Dr6n7rLEM1X4nil2G2HO', NULL, '2026-07-06 03:53:41', '2026-07-06 03:53:41', 1, '+91 98765 00002', 'EMP-IN-SAL01', 'active', 0, NULL, NULL),
(5, 'Amit Site (IN)', 'site.in@hindustan.com', '2026-07-06 03:53:41', '$2y$12$juyU2BYL8M9a3OyCdSiE.e/1wV6hv4dbJdsRgRyPPOTk9atNE45cy', NULL, '2026-07-06 03:53:41', '2026-07-08 23:56:15', 1, '+91 98765 00003', 'EMP-IN-SIT01', 'active', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_user_dashboard_layouts`
--

DROP TABLE IF EXISTS `hindustansystem_user_dashboard_layouts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_user_dashboard_layouts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `layout_settings` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_user_dashboard_layouts_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_vouchers`
--

DROP TABLE IF EXISTS `hindustansystem_vouchers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_vouchers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `voucher_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `narration` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_no` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `edited_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `reversal_of_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_vouchers_system_id_voucher_number_unique` (`system_id`,`voucher_number`),
  KEY `hindustansystem_vouchers_created_by_foreign` (`created_by`),
  KEY `hindustansystem_vouchers_edited_by_foreign` (`edited_by`),
  KEY `hindustansystem_vouchers_reversal_of_id_foreign` (`reversal_of_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_vouchers`
--

INSERT INTO `hindustansystem_vouchers` (`id`, `system_id`, `voucher_number`, `type`, `date`, `narration`, `reference_no`, `created_by`, `edited_by`, `status`, `reversal_of_id`, `created_at`, `updated_at`) VALUES
(3, 1, 'RC-2026-00001', 'Receipt', '2026-07-10', 'test...', '{\"project_id\":\"1\",\"payment_mode\":null,\"gst_rate\":0,\"split_active\":true,\"source_receipt_id\":1,\"allocations\":[{\"type\":\"partner\",\"target_id\":1,\"amount\":5433750,\"remarks\":\"Partner Share (57.5%) allocation\"},{\"type\":\"partner\",\"target_id\":2,\"amount\":4016250,\"remarks\":\"Partner Share (42.5%) allocation\"},{\"type\":\"supplier\",\"target_id\":2,\"amount\":50000,\"remarks\":\"Supplier liability clearing\"},{\"type\":\"refund\",\"target_id\":6,\"amount\":500000,\"remarks\":\"Customer cancellation refund\"}]}', 1, NULL, 'Posted', NULL, '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(4, 1, 'RC-2026-202600002', 'Receipt', '2026-07-10', '4rr3e', '{\"project_id\":\"1\",\"payment_mode\":null,\"gst_rate\":0,\"split_active\":true,\"source_receipt_id\":4,\"allocations\":[{\"type\":\"partner\",\"target_id\":1,\"amount\":1696250,\"remarks\":\"Partner Share (57.5%) allocation\"},{\"type\":\"partner\",\"target_id\":2,\"amount\":1253750,\"remarks\":\"Partner Share (42.5%) allocation\"},{\"type\":\"supplier\",\"target_id\":2,\"amount\":50000,\"remarks\":\"Supplier liability clearing\"}]}', 1, NULL, 'Posted', NULL, '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(5, 1, 'PAY-LOAN-17-1784533037', 'Payment', '2026-07-20', 'Bank Loan EMI Payment - Inst #2', NULL, 1, NULL, 'Posted', NULL, '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(6, 1, 'PV-2026-00001', 'Payment', '2026-07-20', '<p>For cement</p>', '{\"payee_id\":\"5\",\"gst_rate\":0,\"tds_rate\":0}', 1, NULL, 'Posted', NULL, '2026-07-20 06:06:45', '2026-07-20 06:06:45'),
(7, 1, 'PAY-LOAN-13-1784548536', 'Payment', '2026-07-20', 'Bank Loan EMI Payment - Inst #1', NULL, 1, NULL, 'Posted', NULL, '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(8, 1, 'PV-2026-00002', 'Payment', '2026-07-20', NULL, '{\"payee_id\":\"4\",\"gst_rate\":0,\"tds_rate\":0}', 1, NULL, 'approved', NULL, '2026-07-20 06:26:31', '2026-07-20 06:26:31'),
(11, 1, 'PV-2026-00003', 'Payment', '2026-07-20', '<p>fdf</p>', '{\"payee_id\":null,\"gst_rate\":0,\"tds_rate\":0}', 1, NULL, 'approved', NULL, '2026-07-20 07:03:33', '2026-07-20 07:03:33'),
(12, 1, 'PV-2026-00004', 'Payment', '2026-07-20', NULL, '{\"payee_id\":null,\"gst_rate\":0,\"tds_rate\":0}', 1, NULL, 'pending', NULL, '2026-07-20 07:12:41', '2026-07-20 07:12:41'),
(13, 1, 'PV-BROKER-3-1784636887', 'Payment', '2026-07-21', 'Bulk commission payout across 1 deal(s) to broker \'Nandhana\'.', NULL, 1, NULL, 'Posted', NULL, '2026-07-21 06:58:07', '2026-07-21 06:58:07'),
(14, 1, 'PV-BROKER-2-1784703210', 'Payment', '2026-07-22', 'Bulk commission payout across 2 deal(s) to broker \'Metro Homes Agents\'.', NULL, 1, NULL, 'Posted', NULL, '2026-07-22 01:23:30', '2026-07-22 01:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_voucher_lines`
--

DROP TABLE IF EXISTS `hindustansystem_voucher_lines`;
CREATE TABLE IF NOT EXISTS `hindustansystem_voucher_lines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `voucher_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `line_narration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_voucher_lines_voucher_id_foreign` (`voucher_id`),
  KEY `hindustansystem_voucher_lines_account_id_foreign` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_voucher_lines`
--

INSERT INTO `hindustansystem_voucher_lines` (`id`, `voucher_id`, `account_id`, `debit`, `credit`, `line_narration`, `created_at`, `updated_at`) VALUES
(5, 3, 18, 10000000.00, 0.00, 'Debit to Destination Account', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(6, 3, 19, 0.00, 10000000.00, 'Credit to Customer Ledger', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(7, 3, 2, 5433750.00, 0.00, 'Partner payout share drawings: Basheer (Partner Share (57.5%) allocation)', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(8, 3, 18, 0.00, 5433750.00, 'Credit bank for Partner share drawings allocation', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(9, 3, 3, 4016250.00, 0.00, 'Partner payout share drawings: Pavoor (Partner Share (42.5%) allocation)', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(10, 3, 18, 0.00, 4016250.00, 'Credit bank for Partner share drawings allocation', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(11, 3, 6, 50000.00, 0.00, 'Debit Supplier ledger for bill #BN755376/76D (Supplier liability clearing)', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(12, 3, 18, 0.00, 50000.00, 'Credit bank for supplier bill payment', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(13, 3, 20, 500000.00, 0.00, 'Debit customer ledger for cancellation refund on unit G 2 (Customer cancellation refund)', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(14, 3, 18, 0.00, 500000.00, 'Credit bank for customer cancellation refund', '2026-07-17 01:16:22', '2026-07-17 01:16:22'),
(15, 4, 18, 3000000.00, 0.00, 'Debit to Destination Account', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(16, 4, 20, 0.00, 3000000.00, 'Credit to Customer Ledger', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(17, 4, 2, 1696250.00, 0.00, 'Partner payout share drawings: Basheer (Partner Share (57.5%) allocation)', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(18, 4, 18, 0.00, 1696250.00, 'Credit bank for Partner share drawings allocation', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(19, 4, 3, 1253750.00, 0.00, 'Partner payout share drawings: Pavoor (Partner Share (42.5%) allocation)', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(20, 4, 18, 0.00, 1253750.00, 'Credit bank for Partner share drawings allocation', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(21, 4, 6, 50000.00, 0.00, 'Debit Supplier ledger for bill #BN755376/76D (Supplier liability clearing)', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(22, 4, 18, 0.00, 50000.00, 'Credit bank for supplier bill payment', '2026-07-17 01:22:02', '2026-07-17 01:22:02'),
(23, 5, 8, 0.00, 18666.67, 'Paid Loan EMI', '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(24, 5, 32, 16666.67, 0.00, 'Loan Principal Repayment', '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(25, 5, 28, 2000.00, 0.00, 'Loan Interest Expense', '2026-07-20 02:07:17', '2026-07-20 02:07:17'),
(26, 6, 9, 20000.00, 0.00, 'Debit Expense Ledger', '2026-07-20 06:06:45', '2026-07-20 06:06:45'),
(27, 6, 13, 0.00, 20000.00, 'Credit Bank/Cash', '2026-07-20 06:06:45', '2026-07-20 06:06:45'),
(28, 7, 7, 0.00, 25208.33, 'Paid Loan EMI', '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(29, 7, 26, 20833.33, 0.00, 'Loan Principal Repayment', '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(30, 7, 1, 4375.00, 0.00, 'Loan Interest Expense', '2026-07-20 06:25:36', '2026-07-20 06:25:36'),
(31, 8, 10, 10200.00, 0.00, 'Debit Expense Ledger', '2026-07-20 06:26:31', '2026-07-20 06:26:31'),
(32, 8, 13, 0.00, 10200.00, 'Credit Bank/Cash', '2026-07-20 06:26:31', '2026-07-20 06:26:31'),
(37, 11, 2, 36666.67, 0.00, 'Debit Expense Ledger', '2026-07-20 07:03:33', '2026-07-20 07:03:33'),
(38, 11, 17, 0.00, 36666.67, 'Credit Bank/Cash', '2026-07-20 07:03:33', '2026-07-20 07:03:33'),
(39, 12, 29, 229166.67, 0.00, 'Debit Expense Ledger', '2026-07-20 07:12:41', '2026-07-20 07:12:41'),
(40, 12, 17, 0.00, 229166.67, 'Credit Bank/Cash', '2026-07-20 07:12:41', '2026-07-20 07:12:41'),
(41, 13, 25, 30000.00, 0.00, 'Debit Broker commission payable', '2026-07-21 06:58:07', '2026-07-21 06:58:07'),
(42, 13, 8, 0.00, 30000.00, 'Credit Cash for commission payout', '2026-07-21 06:58:07', '2026-07-21 06:58:07'),
(43, 14, 1, 77000.00, 0.00, 'Debit Broker commission payable', '2026-07-22 01:23:30', '2026-07-22 01:23:30'),
(44, 14, 8, 0.00, 77000.00, 'Credit Cash for commission payout', '2026-07-22 01:23:30', '2026-07-22 01:23:30');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hindustansystem_accounts`
--
ALTER TABLE `hindustansystem_accounts`
  ADD CONSTRAINT `hindustansystem_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_accounts_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_activity_logs`
--
ALTER TABLE `hindustansystem_activity_logs`
  ADD CONSTRAINT `hindustansystem_activity_logs_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_approvals`
--
ALTER TABLE `hindustansystem_approvals`
  ADD CONSTRAINT `hindustansystem_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_approvals_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_bills`
--
ALTER TABLE `hindustansystem_bills`
  ADD CONSTRAINT `hindustansystem_bills_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_bills_payee_id_foreign` FOREIGN KEY (`payee_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bills_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bills_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_bill_payments`
--
ALTER TABLE `hindustansystem_bill_payments`
  ADD CONSTRAINT `bp_bill_fk` FOREIGN KEY (`bill_id`) REFERENCES `hindustansystem_bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bp_payee_fk` FOREIGN KEY (`payee_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bill_payments_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bill_payments_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_bookings`
--
ALTER TABLE `hindustansystem_bookings`
  ADD CONSTRAINT `hindustansystem_bookings_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `hindustansystem_brokers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bookings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bookings_sales_executive_id_foreign` FOREIGN KEY (`sales_executive_id`) REFERENCES `hindustansystem_sales_executives` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_bookings_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_brokerages`
--
ALTER TABLE `hindustansystem_brokerages`
  ADD CONSTRAINT `hindustansystem_brokerages_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `hindustansystem_brokers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_brokerages_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_brokerages_sale_unit_id_foreign` FOREIGN KEY (`sale_unit_id`) REFERENCES `hindustansystem_sale_units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_brokers`
--
ALTER TABLE `hindustansystem_brokers`
  ADD CONSTRAINT `hindustansystem_brokers_linked_account_id_foreign` FOREIGN KEY (`linked_account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_brokers_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_commission_entries`
--
ALTER TABLE `hindustansystem_commission_entries`
  ADD CONSTRAINT `ce_deal_fk` FOREIGN KEY (`deal_id`) REFERENCES `hindustansystem_deals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_commission_entries_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_commission_entries_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_customer_installments`
--
ALTER TABLE `hindustansystem_customer_installments`
  ADD CONSTRAINT `cust_inst_rescheduled_fk` FOREIGN KEY (`rescheduled_from_id`) REFERENCES `hindustansystem_customer_installments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_customer_installments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_deals`
--
ALTER TABLE `hindustansystem_deals`
  ADD CONSTRAINT `hindustansystem_deals_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `hindustansystem_bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_deals_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `hindustansystem_brokers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_deals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_deals_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_emi_reschedule_logs`
--
ALTER TABLE `hindustansystem_emi_reschedule_logs`
  ADD CONSTRAINT `hindustansystem_emi_reschedule_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_emi_reschedule_logs_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_emi_schedules`
--
ALTER TABLE `hindustansystem_emi_schedules`
  ADD CONSTRAINT `es_loan_fk` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_emi_schedules_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employees`
--
ALTER TABLE `hindustansystem_employees`
  ADD CONSTRAINT `hindustansystem_employees_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_floors`
--
ALTER TABLE `hindustansystem_floors`
  ADD CONSTRAINT `hindustansystem_floors_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_hindustan_units`
--
ALTER TABLE `hindustansystem_hindustan_units`
  ADD CONSTRAINT `hindustansystem_hindustan_units_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `hindustansystem_floors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_hindustan_units_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_hindustan_units_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `hindustansystem_unit_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_ledger_entries`
--
ALTER TABLE `hindustansystem_ledger_entries`
  ADD CONSTRAINT `hindustansystem_ledger_entries_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_ledger_entries_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_ledger_entries_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_ledger_entries_voucher_line_id_foreign` FOREIGN KEY (`voucher_line_id`) REFERENCES `hindustansystem_voucher_lines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_loans`
--
ALTER TABLE `hindustansystem_loans`
  ADD CONSTRAINT `hindustansystem_loans_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_loans_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `l_interest_fk` FOREIGN KEY (`interest_account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `l_ledger_fk` FOREIGN KEY (`ledger_account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_loan_interest_logs`
--
ALTER TABLE `hindustansystem_loan_interest_logs`
  ADD CONSTRAINT `hindustansystem_loan_interest_logs_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_loan_prepayments`
--
ALTER TABLE `hindustansystem_loan_prepayments`
  ADD CONSTRAINT `hindustansystem_loan_prepayments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_model_has_permissions`
--
ALTER TABLE `hindustansystem_model_has_permissions`
  ADD CONSTRAINT `hindustansystem_model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `hindustansystem_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_model_has_roles`
--
ALTER TABLE `hindustansystem_model_has_roles`
  ADD CONSTRAINT `hindustansystem_model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `hindustansystem_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_partner_allocations`
--
ALTER TABLE `hindustansystem_partner_allocations`
  ADD CONSTRAINT `hindustansystem_partner_allocations_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `hindustansystem_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_partner_allocations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_partner_allocations_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_partner_allocations_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pa_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_partner_shares`
--
ALTER TABLE `hindustansystem_partner_shares`
  ADD CONSTRAINT `hindustansystem_partner_shares_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_partner_shares_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ps_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_payees`
--
ALTER TABLE `hindustansystem_payees`
  ADD CONSTRAINT `hindustansystem_payees_linked_account_id_foreign` FOREIGN KEY (`linked_account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_payees_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_payments`
--
ALTER TABLE `hindustansystem_payments`
  ADD CONSTRAINT `hindustansystem_payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `hindustansystem_bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_payments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_petty_cash_accounts`
--
ALTER TABLE `hindustansystem_petty_cash_accounts`
  ADD CONSTRAINT `hindustansystem_petty_cash_accounts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_petty_cash_accounts_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pca_ledger_fk` FOREIGN KEY (`ledger_account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_petty_cash_entries`
--
ALTER TABLE `hindustansystem_petty_cash_entries`
  ADD CONSTRAINT `hindustansystem_petty_cash_entries_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_petty_cash_entries_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pce_pca_fk` FOREIGN KEY (`petty_cash_account_id`) REFERENCES `hindustansystem_petty_cash_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_projects`
--
ALTER TABLE `hindustansystem_projects`
  ADD CONSTRAINT `hindustansystem_projects_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_receipts`
--
ALTER TABLE `hindustansystem_receipts`
  ADD CONSTRAINT `hindustansystem_receipts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `hindustansystem_banks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_receipts_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_receipts_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_receipts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_replenishment_requests`
--
ALTER TABLE `hindustansystem_replenishment_requests`
  ADD CONSTRAINT `hindustansystem_replenishment_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_replenishment_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_replenishment_requests_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_replenishment_requests_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rr_pca_fk` FOREIGN KEY (`petty_cash_account_id`) REFERENCES `hindustansystem_petty_cash_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_role_has_permissions`
--
ALTER TABLE `hindustansystem_role_has_permissions`
  ADD CONSTRAINT `hindustansystem_role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `hindustansystem_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `hindustansystem_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_sales`
--
ALTER TABLE `hindustansystem_sales`
  ADD CONSTRAINT `hindustansystem_sales_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `hindustansystem_banks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_sales_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `hindustansystem_brokers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_sales_original_sale_id_foreign` FOREIGN KEY (`original_sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_sales_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_sales_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_sale_extra_works`
--
ALTER TABLE `hindustansystem_sale_extra_works`
  ADD CONSTRAINT `hindustansystem_sale_extra_works_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_sale_status_logs`
--
ALTER TABLE `hindustansystem_sale_status_logs`
  ADD CONSTRAINT `hindustansystem_sale_status_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_sale_status_logs_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_sale_units`
--
ALTER TABLE `hindustansystem_sale_units`
  ADD CONSTRAINT `hindustansystem_sale_units_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_sale_units_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_units`
--
ALTER TABLE `hindustansystem_units`
  ADD CONSTRAINT `hindustansystem_units_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `hindustansystem_floors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_units_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_units_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `hindustansystem_unit_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_unit_rate_logs`
--
ALTER TABLE `hindustansystem_unit_rate_logs`
  ADD CONSTRAINT `hindustansystem_unit_rate_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_unit_rate_logs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_unit_status_logs`
--
ALTER TABLE `hindustansystem_unit_status_logs`
  ADD CONSTRAINT `hindustansystem_unit_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_unit_status_logs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_unit_types`
--
ALTER TABLE `hindustansystem_unit_types`
  ADD CONSTRAINT `hindustansystem_unit_types_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_users`
--
ALTER TABLE `hindustansystem_users`
  ADD CONSTRAINT `hindustansystem_users_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_user_dashboard_layouts`
--
ALTER TABLE `hindustansystem_user_dashboard_layouts`
  ADD CONSTRAINT `hindustansystem_user_dashboard_layouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `hindustansystem_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_vouchers`
--
ALTER TABLE `hindustansystem_vouchers`
  ADD CONSTRAINT `hindustansystem_vouchers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_vouchers_edited_by_foreign` FOREIGN KEY (`edited_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_vouchers_reversal_of_id_foreign` FOREIGN KEY (`reversal_of_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_vouchers_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_voucher_lines`
--
ALTER TABLE `hindustansystem_voucher_lines`
  ADD CONSTRAINT `hindustansystem_voucher_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_voucher_lines_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
