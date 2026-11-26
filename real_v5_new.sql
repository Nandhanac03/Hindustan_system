-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 26, 2026 at 12:48 PM
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
-- Database: `real_v5_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_accounting_settings`
--

DROP TABLE IF EXISTS `hindustansystem_accounting_settings`;
CREATE TABLE IF NOT EXISTS `hindustansystem_accounting_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_accounting_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_accounting_settings`
--

INSERT INTO `hindustansystem_accounting_settings` (`id`, `key`, `value`, `locked_at`, `locked_by`, `created_at`, `updated_at`) VALUES
(1, 'is_opening_balance_locked', '0', NULL, NULL, '2026-09-16 11:42:15', '2026-09-16 11:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_accounts`
--

DROP TABLE IF EXISTS `hindustansystem_accounts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_accounts_system_id_code_unique` (`system_id`,`code`),
  KEY `hindustansystem_accounts_parent_id_foreign` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_accounts`
--

INSERT INTO `hindustansystem_accounts` (`id`, `system_id`, `code`, `name`, `type`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'BRK-052F0D', 'noushad Commission Payable Account', 'liability', NULL, 1, '2026-10-16 04:45:23', '2026-10-16 04:45:23'),
(2, 1, 'BANK-GEN', 'General Bank Account', 'Asset', NULL, 1, '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(3, 1, 'CUST-REC-1', 'Customer Receivable - jhancy', 'Asset', NULL, 1, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(4, 1, 'BANK-1', 'Axis Bank 9752 (9752)', 'Asset', NULL, 1, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(5, 1, 'BANK-KAR-213', 'Karnataka Bank 213 Account', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(6, 1, 'CASH-HAND', 'Cash-in-Hand', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(7, 1, 'EXP-ADV', 'Advertisement Expense Payable', 'Expense', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(8, 1, 'EXP-SITE', 'Site Expenses', 'Expense', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(9, 1, 'EXP-SAL', 'Salary Payable', 'Expense', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(10, 1, 'INC-SALES', 'Flat Sales Revenue', 'Income', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(11, 1, 'BANK-FEDERAL-23', 'Federal Account', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(12, 1, 'BANK-ICICIBAN-24', 'ICICI Bank Account', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(13, 1, 'BANK-SBI-25', 'SBI Account', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(14, 1, 'BANK-SIB-26', 'SIB Account', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(15, 1, '1089', 'Canara Bank 260 - A/c 260', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(16, 1, '1021', 'Karnataka Bank 14801', 'Asset', NULL, 1, '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(17, 1, 'BK-6191', 'Axis Bank 9752 Bank', 'Asset', NULL, 1, '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(18, 1, 'SUP-ACC-0001', 'MKH INFRASTRUCTURE (Payable)', 'Liability', NULL, 1, '2026-10-17 04:42:04', '2026-10-17 04:42:04'),
(19, 1, 'CUST-REC-4', 'Customer Receivable - Sinaj', 'Asset', NULL, 1, '2026-10-27 01:00:54', '2026-10-27 01:00:54'),
(20, 1, 'PRT-ACC-01', 'Basheer (Partner Current Account)', 'liability', NULL, 1, '2026-10-27 01:33:34', '2026-10-27 01:33:34'),
(21, 1, 'PRT-ACC-02', 'Pavoor (Partner Current Account)', 'liability', NULL, 1, '2026-10-27 01:33:34', '2026-10-27 01:33:34'),
(22, 1, 'BRK-40995B', 'Noushad Commission Payable Account', 'liability', NULL, 1, '2026-11-02 05:36:14', '2026-11-02 05:36:14'),
(23, 1, 'CUST-REC-2', 'Customer Receivable - Sinaj', 'Asset', NULL, 1, '2026-11-02 05:41:40', '2026-11-02 05:41:40'),
(24, 1, 'BRK-277AB3', 'Noushad Commission Payable Account', 'liability', NULL, 1, '2026-11-02 05:43:56', '2026-11-02 05:43:56'),
(25, 1, 'CUST-REC-3', 'Customer Receivable - Athira', 'Asset', NULL, 1, '2026-11-03 00:37:23', '2026-11-03 00:37:23'),
(26, 1, 'BRK-FFAA70', 'Noushad Commission Payable Account', 'liability', NULL, 1, '2026-11-05 06:16:44', '2026-11-05 06:16:44'),
(27, 1, 'LOAN-LOAN72473', 'Loan Account - ICICI Bank (Loan-72473)', 'liability', NULL, 1, '2026-11-08 23:30:42', '2026-11-08 23:30:42'),
(28, 1, 'EXP-LOAN-INT', 'Bank Loan Interest Expense', 'expense', NULL, 1, '2026-11-08 23:30:42', '2026-11-08 23:30:42'),
(29, 1, '1001', 'Bank Balances (Karnataka Bank)', 'Asset', NULL, 1, '2026-11-08 23:31:37', '2026-11-08 23:31:37'),
(30, 1, '2201', 'Bank Construction Loans / Project Credit Limits', 'Liability', NULL, 1, '2026-11-08 23:31:37', '2026-11-08 23:31:37'),
(31, 1, 'EXP-9426', 'Office Expense', 'Expense', NULL, 1, '2026-11-09 06:16:24', '2026-11-09 06:16:24'),
(32, 1, 'CA-8678', 'Petty Cash Box', 'Asset', NULL, 1, '2026-11-09 06:16:25', '2026-11-09 06:16:25'),
(33, 1, 'EXP-4451', 'Electricity Charge', 'Expense', NULL, 1, '2026-11-09 06:21:42', '2026-11-09 06:21:42'),
(34, 1, 'AST-LAND', 'Land Purchase', 'Asset', NULL, 1, '2026-11-09 07:18:51', '2026-11-09 07:18:51'),
(35, 1, 'EXP-PRINT', 'Printing and Stationery', 'Expense', NULL, 1, '2026-11-09 07:18:51', '2026-11-09 07:18:51'),
(36, 1, 'EXP-RATES-TAX', 'Project - Rates & Taxes', 'Expense', NULL, 1, '2026-11-09 07:18:51', '2026-11-09 07:18:51'),
(37, 1, 'EXP-GEN', 'General Expenses', 'Expense', NULL, 1, '2026-11-09 07:18:52', '2026-11-09 07:18:52'),
(38, 1, 'AST-AC', 'Aircondition', 'Asset', NULL, 1, '2026-11-09 07:18:53', '2026-11-09 07:18:53'),
(39, 1, 'AST-ELEC', 'Electrical Fittings', 'Asset', NULL, 1, '2026-11-09 07:18:54', '2026-11-09 07:18:54'),
(40, 1, 'EXP-REPAIR', 'Repairs and Maintenance', 'Expense', NULL, 1, '2026-11-09 07:18:54', '2026-11-09 07:18:54'),
(41, 1, 'EXP-OFFICE', 'Office Expenses', 'Expense', NULL, 1, '2026-11-09 07:18:55', '2026-11-09 07:18:55'),
(42, 1, 'EXP-INAUG', 'Office Inauguration Expenses', 'Expense', NULL, 1, '2026-11-09 07:18:55', '2026-11-09 07:18:55'),
(43, 1, 'AST-FURN', 'Furniture', 'Asset', NULL, 1, '2026-11-09 07:18:56', '2026-11-09 07:18:56'),
(44, 1, 'EXP-TRAVEL', 'Travelling Expenses', 'Expense', NULL, 1, '2026-11-09 07:18:58', '2026-11-09 07:18:58'),
(45, 1, 'EXP-RENT', 'Rent Paid', 'Expense', NULL, 1, '2026-11-09 07:18:59', '2026-11-09 07:18:59'),
(46, 1, 'EXP-PROMO', 'Business Promotion', 'Expense', NULL, 1, '2026-11-09 07:18:59', '2026-11-09 07:18:59'),
(47, 1, 'EXP-TEL', 'Telephone Charges', 'Expense', NULL, 1, '2026-11-09 07:19:00', '2026-11-09 07:19:00'),
(48, 1, 'EXP-NEWS', 'News Paper & Periodicals', 'Expense', NULL, 1, '2026-11-09 07:19:01', '2026-11-09 07:19:01'),
(49, 1, 'EXP-POWER', 'Electricity Expenses', 'Expense', NULL, 1, '2026-11-09 07:19:03', '2026-11-09 07:19:03'),
(50, 1, 'EXP-SEC', 'Security Charges', 'Expense', NULL, 1, '2026-11-09 07:19:04', '2026-11-09 07:19:04'),
(51, 1, 'PRT-BASHEER', 'Basheer Madathil', 'Liability', NULL, 1, '2026-11-09 07:19:14', '2026-11-09 07:19:14'),
(52, 1, 'BRK-BD66C7', 'Noushad Commission Payable Account', 'liability', NULL, 1, '2026-11-10 05:49:52', '2026-11-10 05:49:52'),
(53, 1, 'BRK-6B7644', 'test Commission Payable Account', 'liability', NULL, 1, '2026-11-20 03:41:31', '2026-11-20 03:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_activity_logs`
--

DROP TABLE IF EXISTS `hindustansystem_activity_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `screen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_seconds` int UNSIGNED DEFAULT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_activity_logs_user_id_foreign` (`user_id`),
  KEY `hindustansystem_activity_logs_system_id_foreign` (`system_id`),
  KEY `hindustansystem_activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `hindustansystem_activity_logs_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=806 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_activity_logs`
--

INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `category`, `screen`, `route_name`, `method`, `module`, `duration_seconds`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `metadata`, `created_at`) VALUES
(1, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', NULL, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 03:29:25'),
(2, 1, 1, 'Created Sale', 'record', '/sales', 'sales.store', 'POST', 'Sales & Booking', NULL, 'App\\Models\\Sale', 5, 'Created Sale #5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 5, \"notes\": \"sfdsdf\", \"status\": \"active\", \"bank_id\": \"24\", \"unit_id\": 8, \"emi_type\": \"equal\", \"gst_type\": \"none\", \"broker_id\": null, \"sale_date\": \"2026-11-16 00:00:00\", \"gst_amount\": 0, \"project_id\": \"1\", \"base_amount\": 23636000, \"customer_id\": 2, \"sale_amount\": 23636000, \"sale_number\": \"TH-SH-F4-ATHI\", \"payment_mode\": \"Cheque\", \"payment_plan\": \"emi\", \"reference_no\": \"6565\", \"total_amount\": 23636000, \"emi_frequency\": \"monthly\", \"rate_per_sqft\": 19000, \"agreement_date\": \"2026-11-16 00:00:00\", \"final_discount\": 0, \"gst_percentage\": null, \"initial_payment\": 500000, \"registration_date\": \"2026-11-16 00:00:00\", \"remaining_balance\": 23136000, \"emi_installment_count\": \"10\", \"first_installment_date\": \"2026-12-16\"}, \"old\": []}', '2026-11-16 03:30:04'),
(3, 1, 1, 'Updated Unit', 'record', '/sales', 'sales.store', 'POST', 'Projects & Configuration', NULL, 'App\\Models\\Unit', 8, 'Updated Unit #8 (sale_rate_per_sqft, sale_amount, difference, gst_behavior)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"sold\", \"difference\": 0, \"gst_amount\": 0, \"sale_amount\": 23636000, \"gst_behavior\": \"none\", \"sale_rate_per_sqft\": 19000}, \"old\": {\"status\": \"available\", \"difference\": null, \"gst_amount\": \"2363600.00\", \"sale_amount\": null, \"gst_behavior\": \"exclusive\", \"sale_rate_per_sqft\": null}}', '2026-11-16 03:30:05'),
(4, 1, 1, 'Created JournalVoucher', 'record', '/sales', 'sales.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 9, 'Created JournalVoucher #9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 9, \"is_active\": true, \"narration\": \"Booking Athira Unit F 4\", \"voucher_no\": \"JV-SB-2026-0005\", \"reference_id\": 5, \"voucher_date\": \"2026-11-16\", \"voucher_type_id\": 15}, \"old\": []}', '2026-11-16 03:30:05'),
(5, 1, 1, 'Created Receipt', 'record', '/sales', 'sales.store', 'POST', 'Customer & Collections', NULL, 'App\\Models\\Receipt', 5, 'Created Receipt #5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 5, \"amount\": 500000, \"bank_id\": \"24\", \"remarks\": \"Initial payment at sale creation\", \"sale_id\": 5, \"unit_id\": 8, \"created_by\": 1, \"project_id\": \"1\", \"customer_id\": 2, \"payment_mode\": \"Cheque\", \"receipt_date\": \"2026-11-16 00:00:00\", \"reference_no\": \"6565\"}, \"old\": []}', '2026-11-16 03:30:05'),
(6, 1, 1, 'Created / Submitted', 'action', '/sales', 'sales.store', 'POST', 'Sales & Booking', NULL, NULL, NULL, 'Created / Submitted in Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 201}', '2026-11-16 03:30:05'),
(7, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 243, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 03:34:08'),
(8, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', NULL, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:37:58'),
(9, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 132, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:40:10'),
(10, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-16 04:40:18'),
(11, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-16 04:42:08'),
(12, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-16 04:42:08'),
(13, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 2, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:42:10'),
(14, 1, 1, 'Screen Viewed', 'screen', '/units', 'units.index', 'GET', 'Projects & Configuration', 53, NULL, NULL, 'Viewed Projects & Configuration (/units)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:43:03'),
(15, 1, 1, 'Screen Viewed', 'screen', '/partners', 'partners.index', 'GET', 'Projects & Configuration', 94, NULL, NULL, 'Viewed Projects & Configuration (/partners)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:44:37'),
(16, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 82, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:45:59'),
(17, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 17, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:46:16'),
(18, 1, 1, 'Screen Viewed', 'screen', '/emi-collections', 'emi-collections.index', 'GET', 'Customer & Collections', 135, NULL, NULL, 'Viewed Customer & Collections (/emi-collections)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:48:32'),
(19, 1, 1, 'Screen Viewed', 'screen', '/emi-collections/ledger/5', 'emi-collections.ledger', 'GET', 'Customer & Collections', 27, NULL, NULL, 'Viewed Customer & Collections (/emi-collections/ledger/5)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:48:59'),
(20, 1, 1, 'Screen Viewed', 'screen', '/cheque-realization/queue', 'cheque-realization.queue', 'GET', 'Cheque Realization', 17, NULL, NULL, 'Viewed Cheque Realization (/cheque-realization/queue)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:49:17'),
(21, 1, 1, 'Screen Viewed', 'screen', '/reports/customer-ledger', 'reports.customer_ledger', 'GET', 'Reports', 49, NULL, NULL, 'Viewed Reports (/reports/customer-ledger)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:50:06'),
(22, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 27, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:50:33'),
(23, 1, 1, 'Updated Sale', 'record', '/sales/5/status', 'sales.change-status', 'POST', 'Sales & Booking', NULL, 'App\\Models\\Sale', 5, 'Updated Sale #5 (status, cancellation_reason, refund_amount)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"cancelled\", \"refund_amount\": 500000, \"cancellation_reason\": \"Financial Issue\"}, \"old\": {\"status\": \"active\", \"refund_amount\": \"0.00\", \"cancellation_reason\": null}}', '2026-11-16 04:51:40'),
(24, 1, 1, 'Created / Submitted', 'action', '/sales/5/status', 'sales.change-status', 'POST', 'Sales & Booking', 1, NULL, NULL, 'Created / Submitted in Sales & Booking (/sales/5/status)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:51:41'),
(25, 1, 1, 'Updated CompanyBankAccount', 'record', '/sales/5/customer-refund', 'sales.customer-refund', 'POST', 'Bank & Treasury', NULL, 'App\\Models\\CompanyBankAccount', 1, 'Updated CompanyBankAccount #1 (current_balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"current_balance\": 2390000}, \"old\": {\"current_balance\": \"2490000.00\"}}', '2026-11-16 04:52:09'),
(26, 1, 1, 'Created Voucher', 'record', '/sales/5/customer-refund', 'sales.customer-refund', 'POST', 'Accounting Reports', NULL, 'App\\Models\\Voucher', 5, 'Created Voucher #5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 5, \"date\": \"2026-11-16 00:00:00\", \"type\": \"Payment\", \"status\": \"Posted\", \"narration\": \"Customer Refund for cancellation on Unit F 4 (Athira) — Bank Transfer (NEFT / RTGS / IMPS) [Ref: 6565] (Customer refund processed as per cancellation agreement.)\", \"system_id\": 1, \"created_by\": 1, \"reference_no\": \"{\\\"allocations\\\":[{\\\"type\\\":\\\"refund\\\",\\\"target_id\\\":5,\\\"amount\\\":100000}],\\\"payment_mode\\\":\\\"Bank Transfer (NEFT \\\\/ RTGS \\\\/ IMPS)\\\",\\\"reference_no\\\":\\\"6565\\\",\\\"refund_date\\\":\\\"2026-11-16\\\",\\\"company_bank_account_id\\\":1,\\\"remarks\\\":\\\"Customer refund processed as per cancellation agreement.\\\"}\", \"voucher_number\": \"PY-REF-20261116-102209-705\", \"company_bank_account_id\": 1}, \"old\": []}', '2026-11-16 04:52:09'),
(27, 1, 1, 'Created JournalVoucher', 'record', '/sales/5/customer-refund', 'sales.customer-refund', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 10, 'Created JournalVoucher #10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 10, \"is_active\": true, \"narration\": \"Booking Cancellation refund to Athira (Customer refund processed as per cancellation agreement.)\", \"voucher_no\": \"JV-RF-2026-0005\", \"reference_id\": 5, \"voucher_date\": \"2026-11-16\", \"voucher_type_id\": 16}, \"old\": []}', '2026-11-16 04:52:09'),
(28, 1, 1, 'Updated Sale', 'record', '/sales/5/customer-refund', 'sales.customer-refund', 'POST', 'Sales & Booking', NULL, 'App\\Models\\Sale', 5, 'Updated Sale #5 (refund_amount)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"refund_amount\": 400000}, \"old\": {\"refund_amount\": \"500000.00\"}}', '2026-11-16 04:52:09'),
(29, 1, 1, 'Created / Submitted', 'action', '/sales/5/customer-refund', 'sales.customer-refund', 'POST', 'Sales & Booking', 1, NULL, NULL, 'Created / Submitted in Sales & Booking (/sales/5/customer-refund)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:52:10'),
(30, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 17, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:52:27'),
(31, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 54, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:53:21'),
(32, 1, 1, 'Screen Viewed', 'screen', '/contractors', 'contractors.index', 'GET', 'Contractor Desk', 28, NULL, NULL, 'Viewed Contractor Desk (/contractors)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:53:49'),
(33, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 11, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:54:00'),
(34, 1, 1, 'Created RaBill', 'record', '/expenses/ra-bills', 'expenses.ra-bills.store', 'POST', 'Contractor Desk', NULL, 'App\\Models\\RaBill', 1, 'Created RaBill #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 1, \"status\": \"submitted\", \"remarks\": \"dummy\", \"unit_id\": null, \"due_date\": \"2026-11-19 00:00:00\", \"system_id\": 1, \"unit_name\": null, \"created_by\": 1, \"project_id\": \"1\", \"paid_amount\": 0, \"submit_date\": \"2026-11-16 00:00:00\", \"gross_amount\": 400000, \"contractor_id\": \"12\", \"engineer_name\": null, \"verified_date\": null, \"balance_amount\": 400000, \"ra_bill_number\": \"rq11\", \"contractor_name\": \"MKH INFRASTRUCTURE\", \"additional_amount\": 0, \"correction_amount\": 0, \"net_approved_amount\": 400000, \"additional_percentage\": 0}, \"old\": []}', '2026-11-16 04:54:44'),
(35, 1, 1, 'Created / Submitted', 'action', '/expenses/ra-bills', 'expenses.ra-bills.store', 'POST', 'Contractor Desk', NULL, NULL, NULL, 'Created / Submitted in Contractor Desk (/expenses/ra-bills)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-16 04:54:44'),
(36, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 2, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:54:46'),
(37, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/payment-release', 'expenses.ra-bills.payment-release', 'GET', 'Contractor Desk', 16, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/payment-release)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:55:02'),
(38, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 11, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:55:13'),
(39, 1, 1, 'Updated RaBill', 'record', '/expenses/ra-bills/1/verify', 'expenses.ra-bills.verify', 'POST', 'Contractor Desk', NULL, 'App\\Models\\RaBill', 1, 'Updated RaBill #1 (additional_amount, additional_percentage, verified_date, engineer_name)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"pending\", \"engineer_name\": \"Anand Kumar (Senior Project Engineer)\", \"verified_date\": \"2026-11-16 00:00:00\", \"balance_amount\": 336000, \"additional_amount\": 36000, \"correction_amount\": 100000, \"net_approved_amount\": 336000, \"additional_percentage\": 12}, \"old\": {\"status\": \"submitted\", \"engineer_name\": null, \"verified_date\": null, \"balance_amount\": \"400000.00\", \"additional_amount\": \"0.00\", \"correction_amount\": \"0.00\", \"net_approved_amount\": \"400000.00\", \"additional_percentage\": \"0.00\"}}', '2026-11-16 04:55:53'),
(40, 1, 1, 'Created JournalVoucher', 'record', '/expenses/ra-bills/1/verify', 'expenses.ra-bills.verify', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 11, 'Created JournalVoucher #11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 11, \"is_active\": true, \"narration\": \"Verified RA Bill #rq11 - MKH INFRASTRUCTURE (Verified by: Anand Kumar (Senior Project Engineer))\", \"voucher_no\": \"JV-RA-2026-0001\", \"reference_id\": 1, \"voucher_date\": \"2026-11-16\", \"voucher_type_id\": 1}, \"old\": []}', '2026-11-16 04:55:53'),
(41, 1, 1, 'Created / Submitted', 'action', '/expenses/ra-bills/1/verify', 'expenses.ra-bills.verify', 'POST', 'Contractor Desk', NULL, NULL, NULL, 'Created / Submitted in Contractor Desk (/expenses/ra-bills/1/verify)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-16 04:55:53'),
(42, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 2, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:55:55'),
(43, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/payment-release', 'expenses.ra-bills.payment-release', 'GET', 'Contractor Desk', 13, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/payment-release)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:56:08'),
(44, 1, 1, 'Created Voucher', 'record', '/expenses/ra-bills/1/disburse', 'expenses.ra-bills.disburse', 'POST', 'Accounting Reports', NULL, 'App\\Models\\Voucher', 6, 'Created Voucher #6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 6, \"date\": \"2026-11-16 00:00:00\", \"type\": \"Payment\", \"status\": \"Posted\", \"narration\": \"Staggered RA Bill Disbursement for #rq11 (MKH INFRASTRUCTURE)\", \"system_id\": 1, \"created_by\": 1, \"reference_no\": \"asdadsa\", \"voucher_number\": \"PV-00001\"}, \"old\": []}', '2026-11-16 04:57:22'),
(45, 1, 1, 'Updated CompanyBankAccount', 'record', '/expenses/ra-bills/1/disburse', 'expenses.ra-bills.disburse', 'POST', 'Bank & Treasury', NULL, 'App\\Models\\CompanyBankAccount', 1, 'Updated CompanyBankAccount #1 (current_balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"current_balance\": 2290000}, \"old\": {\"current_balance\": \"2390000.00\"}}', '2026-11-16 04:57:22'),
(46, 1, 1, 'Created RaBillPayment', 'record', '/expenses/ra-bills/1/disburse', 'expenses.ra-bills.disburse', 'POST', 'Contractor Desk', NULL, 'App\\Models\\RaBillPayment', 1, 'Created RaBillPayment #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 1, \"status\": \"paid\", \"remarks\": null, \"system_id\": 1, \"created_by\": 1, \"ra_bill_id\": 1, \"voucher_id\": 6, \"paid_amount\": 100000, \"payment_date\": \"2026-11-16 00:00:00\", \"payment_mode\": \"BANK_TRANSFER\", \"reference_no\": \"asdadsa\", \"is_historical\": false, \"company_bank_account_id\": 1}, \"old\": []}', '2026-11-16 04:57:23'),
(47, 1, 1, 'Created JournalVoucher', 'record', '/expenses/ra-bills/1/disburse', 'expenses.ra-bills.disburse', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 12, 'Created JournalVoucher #12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 12, \"is_active\": true, \"narration\": \"Contractor Payment Release for RA Bill #rq11 to MKH INFRASTRUCTURE via Axis Bank 9752\", \"voucher_no\": \"JV-CP-2026-0001\", \"reference_id\": 1, \"voucher_date\": \"2026-11-16\", \"voucher_type_id\": 2}, \"old\": []}', '2026-11-16 04:57:23'),
(48, 1, 1, 'Updated RaBill', 'record', '/expenses/ra-bills/1/disburse', 'expenses.ra-bills.disburse', 'POST', 'Contractor Desk', NULL, 'App\\Models\\RaBill', 1, 'Updated RaBill #1 (paid_amount, balance_amount, status)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"partially_paid\", \"paid_amount\": \"100000.00\", \"balance_amount\": 236000}, \"old\": {\"status\": \"pending\", \"paid_amount\": \"0.00\", \"balance_amount\": \"336000.00\"}}', '2026-11-16 04:57:23'),
(49, 1, 1, 'Created / Submitted', 'action', '/expenses/ra-bills/1/disburse', 'expenses.ra-bills.disburse', 'POST', 'Contractor Desk', NULL, NULL, NULL, 'Created / Submitted in Contractor Desk (/expenses/ra-bills/1/disburse)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-16 04:57:23'),
(50, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/payment-release', 'expenses.ra-bills.payment-release', 'GET', 'Contractor Desk', 2, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/payment-release)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:57:25'),
(51, 1, 1, 'Screen Viewed', 'screen', '/vouchers/6/payment-voucher-print', 'vouchers.payment-voucher-print', 'GET', 'Accounting Reports', 2, NULL, NULL, 'Viewed Accounting Reports (/vouchers/6/payment-voucher-print)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:57:27'),
(52, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/ledger', 'expenses.ra-bills.ledger', 'GET', 'Contractor Desk', 22, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/ledger)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:57:49'),
(53, 1, 1, 'Updated Brokerage', 'record', '/brokers', 'brokers.index', 'GET', 'Brokerage', NULL, 'App\\Models\\Brokerage', 2, 'Updated Brokerage #2 (status)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"payable\"}, \"old\": {\"status\": \"pending\"}}', '2026-11-16 04:58:17'),
(54, 1, 1, 'Screen Viewed', 'screen', '/brokers', 'brokers.index', 'GET', 'Brokerage', 1, NULL, NULL, 'Viewed Brokerage (/brokers)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:58:18'),
(55, 1, 1, 'Screen Viewed', 'screen', '/brokers/commission-ledger', 'brokers.commission-ledger', 'GET', 'Brokerage', 19, NULL, NULL, 'Viewed Brokerage (/brokers/commission-ledger)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:58:37'),
(56, 1, 1, 'Screen Viewed', 'screen', '/brokers/payable-report', 'brokers.payable-report', 'GET', 'Brokerage', 75, NULL, NULL, 'Viewed Brokerage (/brokers/payable-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 04:59:52'),
(57, 1, 1, 'Screen Viewed', 'screen', '/petty-cash/daily-site-expenses', 'petty-cash.daily-site-expenses', 'GET', 'Petty Cash', 64, NULL, NULL, 'Viewed Petty Cash (/petty-cash/daily-site-expenses)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:00:56'),
(58, 1, 1, 'Screen Viewed', 'screen', '/site-expense-categories', 'site-expense-categories.index', 'GET', 'Site Expense Categories', 66, NULL, NULL, 'Viewed Site Expense Categories (/site-expense-categories)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:02:02'),
(59, 1, 1, 'Screen Viewed', 'screen', '/site-expenses', 'site-expenses.index', 'GET', 'Site Expense', 22, NULL, NULL, 'Viewed Site Expense (/site-expenses)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:02:24'),
(60, 1, 1, 'Screen Viewed', 'screen', '/partner-contributions', 'partner-contributions.index', 'GET', 'Partner Contributions', 8, NULL, NULL, 'Viewed Partner Contributions (/partner-contributions)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:02:32'),
(61, 1, 1, 'Screen Viewed', 'screen', '/reports/partner-outflow-ledger', 'reports.partner_outflow_ledger', 'GET', 'Reports', 45, NULL, NULL, 'Viewed Reports (/reports/partner-outflow-ledger)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:03:17'),
(62, 1, 1, 'Screen Viewed', 'screen', '/reports/partner-statements', 'reports.partner_statements', 'GET', 'Reports', 10, NULL, NULL, 'Viewed Reports (/reports/partner-statements)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:03:27'),
(63, 1, 1, 'Screen Viewed', 'screen', '/treasury/dashboard', 'treasury.dashboard', 'GET', 'Dashboard', 22, NULL, NULL, 'Viewed Dashboard (/treasury/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:03:49'),
(64, 1, 1, 'Screen Viewed', 'screen', '/treasury/report', 'treasury.report', 'GET', 'Bank & Treasury', 18, NULL, NULL, 'Viewed Bank & Treasury (/treasury/report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:04:07'),
(65, 1, 1, 'Screen Viewed', 'screen', '/vouchers/contra', 'vouchers.contra.create', 'GET', 'Accounting Reports', 13, NULL, NULL, 'Viewed Accounting Reports (/vouchers/contra)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:04:20'),
(66, 1, 1, 'Screen Viewed', 'screen', '/chart-of-accounts', 'chart-of-accounts.index', 'GET', 'Chart Of Accounts', 16, NULL, NULL, 'Viewed Chart Of Accounts (/chart-of-accounts)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:04:36'),
(67, 1, 1, 'Screen Viewed', 'screen', '/voucher-types', 'voucher-types.index', 'GET', 'Voucher Types', 20, NULL, NULL, 'Viewed Voucher Types (/voucher-types)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:04:56'),
(68, 1, 1, 'Screen Viewed', 'screen', '/reports/trial-balance', 'reports.trial_balance', 'GET', 'Reports', 14, NULL, NULL, 'Viewed Reports (/reports/trial-balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:05:10'),
(69, 1, 1, 'Screen Viewed', 'screen', '/units-matrix', 'unit-matrix.index', 'GET', 'Projects & Configuration', 211, NULL, NULL, 'Viewed Projects & Configuration (/units-matrix)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:08:41'),
(70, 1, 1, 'Screen Viewed', 'screen', '/units-matrix', 'unit-matrix.index', 'GET', 'Projects & Configuration', NULL, NULL, NULL, 'Viewed Projects & Configuration (/units-matrix)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-16 05:46:40'),
(71, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-17 23:43:01'),
(72, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-17 23:43:02'),
(73, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 00:17:21'),
(74, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-19 00:17:22'),
(75, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 04:47:50'),
(76, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-19 04:47:50'),
(77, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 3, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 04:47:53'),
(78, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 12, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 04:48:05'),
(79, 1, 1, 'Created / Submitted', 'action', '/admin/users/5/reset-password', 'admin.users.reset-password', 'POST', 'Users & Roles', 26, NULL, NULL, 'Created / Submitted in Users & Roles (/admin/users/5/reset-password)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 04:48:31'),
(80, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', NULL, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 05:22:11'),
(81, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', NULL, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:03:30'),
(82, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 121, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:05:31'),
(83, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 60, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:06:31'),
(84, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 06:08:14'),
(85, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 06:08:37'),
(86, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-19 06:08:37'),
(87, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 4, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:08:41'),
(88, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', NULL, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:53:30'),
(89, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 06:53:37'),
(90, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 06:53:47'),
(91, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-19 06:53:47'),
(92, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 2, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:53:49'),
(93, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 16, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:54:05'),
(94, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 35, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 06:54:40'),
(95, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-19 23:44:28'),
(96, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-19 23:44:28'),
(97, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 6, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-19 23:44:34'),
(98, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', NULL, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 00:46:47'),
(99, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-20 01:06:48'),
(100, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-20 01:07:05'),
(101, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 01:07:05'),
(102, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 3, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 01:07:08'),
(103, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 01:10:15'),
(104, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 01:10:15'),
(105, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 851, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 01:24:26'),
(106, 1, 1, 'Screen Viewed', 'screen', '/clear-cache', NULL, 'GET', 'Clear Cache', 146, NULL, NULL, 'Viewed Clear Cache (/clear-cache)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 01:26:52'),
(107, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 28, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 01:27:20'),
(108, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 449, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 01:34:49'),
(109, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 601, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 01:44:50'),
(110, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 02:54:47'),
(111, 2, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 02:55:04'),
(112, 2, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 02:55:05'),
(113, 2, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 6, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 02:55:11'),
(114, 2, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 35, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 02:55:46'),
(115, 2, 1, 'Created Permission', 'record', '/admin/roles/7/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 419, 'Created Permission #419', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"id\": 419, \"name\": \"activity_logs.manage.view\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-20 02:56:08'),
(116, 2, 1, 'Created / Submitted', 'action', '/admin/roles/7/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', 8, NULL, NULL, 'Created / Submitted in Users & Roles (/admin/roles/7/permissions)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 02:56:16'),
(117, 2, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 22, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 02:56:38'),
(118, 2, 1, 'Created / Submitted', 'action', '/admin/roles/7/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', 21, NULL, NULL, 'Created / Submitted in Users & Roles (/admin/roles/7/permissions)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 02:56:59'),
(119, 2, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 14, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 02:57:13'),
(120, 2, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 03:14:47'),
(121, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 03:15:01'),
(122, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 03:15:01'),
(123, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 7, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 03:15:08'),
(124, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 1397, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 03:38:25'),
(125, 1, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 152, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 03:40:57'),
(126, 1, 1, 'Created Account', 'record', '/brokers', 'brokers.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\Account', 53, 'Created Account #53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 53, \"code\": \"BRK-6B7644\", \"name\": \"test Commission Payable Account\", \"type\": \"liability\", \"is_active\": true, \"system_id\": 1}, \"old\": []}', '2026-11-20 03:41:31'),
(127, 1, 1, 'Created Broker', 'record', '/brokers', 'brokers.store', 'POST', 'Brokerage', NULL, 'App\\Models\\Broker', 2, 'Created Broker #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 2, \"name\": \"test\", \"system_id\": 1, \"linked_account_id\": 53, \"default_commission_pct\": 2}, \"old\": []}', '2026-11-20 03:41:31'),
(128, 1, 1, 'broker.created', 'action', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Registered new broker \'test\' with default commission of 2%. Linked ledger account: BRK-6B7644.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-20 03:41:31');
INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `category`, `screen`, `route_name`, `method`, `module`, `duration_seconds`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `metadata`, `created_at`) VALUES
(129, 1, 1, 'Created / Submitted', 'action', '/brokers', 'brokers.store', 'POST', 'Brokerage', NULL, NULL, NULL, 'Created / Submitted in Brokerage (/brokers)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 201}', '2026-11-20 03:41:31'),
(130, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 03:58:44'),
(131, 2, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 03:58:45'),
(132, 4, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 03:59:05'),
(133, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:07:29'),
(134, 4, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:07:30'),
(135, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 240, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:11:29'),
(136, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 04:39:06'),
(137, 4, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 04:39:39'),
(138, 4, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 04:39:39'),
(139, 4, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 66, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:40:45'),
(140, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-20 04:40:48'),
(141, 4, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 04:41:32'),
(142, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 04:41:49'),
(143, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 04:41:49'),
(144, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 5, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:41:54'),
(145, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 69, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:43:03'),
(146, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-20 04:43:19'),
(147, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 04:43:19'),
(148, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 6, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:43:25'),
(149, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 80, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:44:46'),
(150, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 55, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:45:41'),
(151, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 40, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 04:46:21'),
(152, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:15'),
(153, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:16'),
(154, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:18'),
(155, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:18'),
(156, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:20'),
(157, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:20'),
(158, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:22'),
(159, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:22'),
(160, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:26'),
(161, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:26'),
(162, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:29'),
(163, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:29'),
(164, NULL, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:31'),
(165, NULL, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 04:50:31'),
(166, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:29'),
(167, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:29'),
(168, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:31'),
(169, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:31'),
(170, 2, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:33'),
(171, 2, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:33'),
(172, 2, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:35'),
(173, 2, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:35'),
(174, 4, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:37'),
(175, 4, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:37'),
(176, 3, 2, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:39'),
(177, 3, 2, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:39'),
(178, 5, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:13:41'),
(179, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:18:13'),
(180, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:22:00'),
(181, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 281, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:26:41'),
(182, 1, 1, 'Updated User', 'record', '/admin/users/4', 'admin.users.update', 'PUT', 'Users & Roles', NULL, 'App\\Models\\User', 4, 'Updated User #4 (name, email, username)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"name\": \"Amit\", \"email\": \"amit@hindustan.com\", \"username\": \"sales\"}, \"old\": {\"name\": \"superadmin\", \"email\": \"superadmin@hindustan.com\", \"username\": \"superadmin\"}}', '2026-11-20 05:28:36'),
(183, 1, 1, 'Updated Record', 'action', '/admin/users/4', 'admin.users.update', 'PUT', 'Users & Roles', NULL, NULL, NULL, 'Updated Record in Users & Roles (/admin/users/4)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 05:28:36'),
(184, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 7, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:28:43'),
(185, 1, 1, 'Updated User', 'record', '/admin/users/5', 'admin.users.update', 'PUT', 'Users & Roles', NULL, 'App\\Models\\User', 5, 'Updated User #5 (name, email, username)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"new\": {\"name\": \"Super Admin\", \"email\": \"superadmin@hindustan.com\", \"username\": \"superadmin\"}, \"old\": {\"name\": \"Amit Site (IN)\", \"email\": \"site.in@hindustan.com\", \"username\": \"site.in\"}}', '2026-11-20 05:29:31'),
(186, 1, 1, 'Updated Record', 'action', '/admin/users/5', 'admin.users.update', 'PUT', 'Users & Roles', 1, NULL, NULL, 'Updated Record in Users & Roles (/admin/users/5)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 05:29:32'),
(187, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 7, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:29:39'),
(188, 1, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 05:30:44'),
(189, 4, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 05:31:01'),
(190, 4, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 05:31:01'),
(191, 4, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 10, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:31:11'),
(192, 4, 1, 'Screen Viewed', 'screen', '/sales', 'sales.index', 'GET', 'Sales & Booking', 24, NULL, NULL, 'Viewed Sales & Booking (/sales)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:31:35'),
(193, 4, 1, 'Screen Viewed', 'screen', '/units', 'units.index', 'GET', 'Projects & Configuration', 25, NULL, NULL, 'Viewed Projects & Configuration (/units)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:32:00'),
(194, 4, 1, 'User Logout', 'security', '/logout', 'logout', 'POST', 'Authentication', NULL, NULL, NULL, 'User signed out of Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 05:32:11'),
(195, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', NULL, '2026-11-20 05:32:39'),
(196, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 05:32:40'),
(197, 1, 1, 'Screen Viewed', 'screen', '/dashboard', 'dashboard', 'GET', 'Dashboard', 2, NULL, NULL, 'Viewed Dashboard (/dashboard)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:32:42'),
(198, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 27, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:33:09'),
(199, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 191, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:36:20'),
(200, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:52:57'),
(201, 5, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-20 05:53:08'),
(202, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 148, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 05:55:25'),
(203, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', NULL, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 06:33:36'),
(204, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 16, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 06:33:52'),
(205, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 195, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 06:37:07'),
(206, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-20 07:17:08'),
(207, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-20 07:17:08'),
(208, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 19, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-20 07:17:27'),
(209, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-22 23:34:05'),
(210, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-22 23:34:06'),
(211, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 6, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-22 23:34:12'),
(212, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 00:31:15'),
(213, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 00:33:05'),
(214, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 274, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 00:37:39'),
(215, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 11, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 00:37:50'),
(216, 1, 1, 'Created Employee', 'record', '/employees', 'employees.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 3, 'Created Employee #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"name\": \"Anchumol\", \"email\": \"anchumol366@gmail.com\", \"phone\": \"7034086180\", \"shift\": \"General Day Shift\", \"branch\": \"Head Office\", \"salary\": \"30000\", \"status\": \"active\", \"last_name\": \"\", \"system_id\": 1, \"department\": \"virtual sys\", \"first_name\": \"Anchumol\", \"work_email\": \"anchumol366@gmail.com\", \"designation\": \"Software engineer\", \"employee_id\": \"EMP-1001\", \"basic_salary\": \"30000\", \"joining_date\": \"2026-11-23 00:00:00\", \"employment_type\": \"Full-Time Permanent\"}, \"old\": []}', '2026-11-23 00:39:20'),
(217, 1, 1, 'Created / Submitted', 'action', '/employees', 'employees.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 00:39:20'),
(218, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 00:39:22'),
(219, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 00:55:10'),
(220, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 109, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 00:56:59'),
(221, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 01:05:25'),
(222, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 312, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:10:37'),
(223, 1, 1, 'Updated Employee', 'record', '/employees/3/update', 'employees.update', 'POST', 'Master', NULL, 'App\\Models\\Employee', 3, 'Updated Employee #3 (name, last_name, gender, date_of_birth)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"hra\": \"3500\", \"city\": \"Azheekal\", \"name\": \"Anchumol S\", \"email\": \"anchumol@virtualsysglobal.com\", \"state\": \"Kerala\", \"gender\": \"Female\", \"country\": \"India\", \"bank_name\": \"SBI\", \"ifsc_code\": \"sbin006458\", \"last_name\": \"S\", \"allowances\": \"4000\", \"deductions\": \"2000\", \"pan_number\": \"64576576\", \"work_email\": \"anchumol@virtualsysglobal.com\", \"blood_group\": \"A-ve\", \"nationality\": \"Indian\", \"postal_code\": \"690547\", \"basic_salary\": \"9600\", \"portal_email\": \"anchumol@virtualsysglobal.com\", \"date_of_birth\": \"1994-07-26 00:00:00\", \"work_location\": \"Cherthala, Kerala\", \"address_line_1\": \"Kochuparambil\", \"personal_email\": \"anchumol366@gmail.com\", \"bank_account_number\": \"12546576576\"}, \"old\": {\"hra\": \"0.00\", \"city\": null, \"name\": \"Anchumol\", \"email\": \"anchumol366@gmail.com\", \"state\": null, \"gender\": null, \"country\": null, \"bank_name\": null, \"ifsc_code\": null, \"last_name\": \"\", \"allowances\": \"0.00\", \"deductions\": \"0.00\", \"pan_number\": null, \"work_email\": \"anchumol366@gmail.com\", \"blood_group\": null, \"nationality\": null, \"postal_code\": null, \"basic_salary\": \"30000.00\", \"portal_email\": null, \"date_of_birth\": null, \"work_location\": null, \"address_line_1\": null, \"personal_email\": null, \"bank_account_number\": null}}', '2026-11-23 01:17:07'),
(224, 1, 1, 'Created / Submitted', 'action', '/employees/3/update', 'employees.update', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/3/update)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 01:17:07'),
(225, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:17:08'),
(226, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 21, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:17:29'),
(227, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 358, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:23:27'),
(228, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 4, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:23:31'),
(229, 1, 1, 'Created EmployeeAttendance', 'record', '/employees/3/attendances', 'employees.attendances.store', 'POST', 'Employee Attendance', NULL, 'App\\Models\\EmployeeAttendance', 3, 'Created EmployeeAttendance #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"status\": \"Present\", \"check_in\": \"09:30\", \"check_out\": \"18:30\", \"employee_id\": 3, \"attendance_date\": \"2026-11-23 00:00:00\"}, \"old\": []}', '2026-11-23 01:24:28'),
(230, 1, 1, 'Created / Submitted', 'action', '/employees/3/attendances', 'employees.attendances.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/3/attendances)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 01:24:28'),
(231, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:24:30'),
(232, 1, 1, 'Created / Submitted', 'action', '/employees/3/attendances/3/delete', 'employees.attendances.destroy', 'POST', 'Employees', 9, NULL, NULL, 'Created / Submitted in Employees (/employees/3/attendances/3/delete)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 01:24:39'),
(233, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 13, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:24:52'),
(234, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 942, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:40:34'),
(235, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 28, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:41:02'),
(236, 1, 1, 'Created EmployeeLeave', 'record', '/employees/3/leaves', 'employees.leaves.store', 'POST', 'Employee Leave', NULL, 'App\\Models\\EmployeeLeave', 3, 'Created EmployeeLeave #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"days\": \"1\", \"status\": \"Rejected\", \"to_date\": \"2026-11-23 00:00:00\", \"from_date\": \"2026-11-23 00:00:00\", \"leave_type\": \"Casual Leave\", \"employee_id\": 3}, \"old\": []}', '2026-11-23 01:41:28'),
(237, 1, 1, 'Created / Submitted', 'action', '/employees/3/leaves', 'employees.leaves.store', 'POST', 'Employees', 1, NULL, NULL, 'Created / Submitted in Employees (/employees/3/leaves)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 01:41:29'),
(238, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:41:30'),
(239, 1, 1, 'Created / Submitted', 'action', '/employees/3/leaves/3/delete', 'employees.leaves.destroy', 'POST', 'Employees', 7, NULL, NULL, 'Created / Submitted in Employees (/employees/3/leaves/3/delete)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 01:41:37'),
(240, 1, 1, 'Created / Submitted', 'action', '/employees/3/leaves/3/delete', 'employees.leaves.destroy', 'POST', 'Employees', 2, NULL, NULL, 'Created / Submitted in Employees (/employees/3/leaves/3/delete)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 01:41:39'),
(241, 1, 1, 'Screen Viewed', 'screen', '/admin/users', 'admin.users.index', 'GET', 'Users & Roles', 58, NULL, NULL, 'Viewed Users & Roles (/admin/users)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:42:37'),
(242, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 30, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:43:07'),
(243, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 466, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:50:53'),
(244, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 13, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:51:06'),
(245, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 17, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 01:51:23'),
(246, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 745, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 02:03:48'),
(247, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 20, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 02:04:08'),
(248, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 02:46:29'),
(249, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 818, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:00:07'),
(250, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 24, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:00:31'),
(251, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 16, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:00:47'),
(252, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 6, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:00:53'),
(253, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 31, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:01:24'),
(254, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:39:25'),
(255, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 03:53:09'),
(256, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 54, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:54:03'),
(257, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 8, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:54:11'),
(258, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 138, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:56:29'),
(259, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 143, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:58:52'),
(260, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 54, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 03:59:46'),
(261, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 40, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:00:26'),
(262, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 20, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:00:46'),
(263, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 14, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:01:00'),
(264, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 15, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:01:15'),
(265, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 467, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:09:02'),
(266, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 237, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:12:59'),
(267, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 1511, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:38:10'),
(268, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 495, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:46:25'),
(269, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 544, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 04:55:29'),
(270, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 1178, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 05:15:07'),
(271, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', NULL, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 05:53:55'),
(272, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 88, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 05:55:23'),
(273, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 324, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:00:48'),
(274, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 307, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:05:55'),
(275, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 501, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:14:16'),
(276, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 1510, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:39:26'),
(277, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 32, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:39:58'),
(278, 1, 1, 'Screen Viewed', 'screen', '/admin/modules', 'admin.modules.index', 'GET', 'Users & Roles', 37, NULL, NULL, 'Viewed Users & Roles (/admin/modules)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:40:35'),
(279, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 14, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:40:49'),
(280, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 06:46:13'),
(281, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 06:52:01'),
(282, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 06:56:14'),
(283, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 16, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-23 06:56:30'),
(284, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-23 06:56:32'),
(285, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-23 06:57:59'),
(286, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-23 06:58:00'),
(287, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-23 06:58:02'),
(288, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 65, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:59:07'),
(289, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 11, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 06:59:18'),
(290, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 407, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:06:05'),
(291, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 592, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:15:57'),
(292, 1, 1, 'Screen Viewed', 'screen', '/cancellation-additional-work', 'cancellation-additional-work.index', 'GET', 'Cancellation Additional Work', 60, NULL, NULL, 'Viewed Cancellation Additional Work (/cancellation-additional-work)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:16:57');
INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `category`, `screen`, `route_name`, `method`, `module`, `duration_seconds`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `metadata`, `created_at`) VALUES
(293, 1, 1, 'Created EmployeeSalaryComponent', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Employee Salary Component', NULL, 'App\\Models\\EmployeeSalaryComponent', 3, 'Created EmployeeSalaryComponent #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"name\": \"House Rent Allowance (HRA)\", \"type\": \"allowance\", \"source\": \"Master\", \"description\": null, \"employee_id\": 3, \"calculation_type\": \"Percentage\", \"configured_value\": 10, \"calculated_amount\": 960, \"salary_component_id\": null}, \"old\": []}', '2026-11-23 07:19:19'),
(294, 1, 1, 'Updated Employee', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 3, 'Updated Employee #3 (salary, allowances, deductions)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"salary\": 10560, \"allowances\": 960, \"deductions\": 0}, \"old\": {\"salary\": \"30000.00\", \"allowances\": \"4000.00\", \"deductions\": \"2000.00\"}}', '2026-11-23 07:19:19'),
(295, 1, 1, 'Created / Submitted', 'action', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/3/salary-components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 07:19:19'),
(296, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:19:21'),
(297, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 80, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:20:41'),
(298, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:20:43'),
(299, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 86, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 07:22:09'),
(300, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-23 23:31:33'),
(301, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-23 23:31:34'),
(302, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 23:31:36'),
(303, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 541, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 23:40:37'),
(304, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 62, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 23:41:39'),
(305, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 35, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 23:42:14'),
(306, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 23, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 23:42:37'),
(307, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 135, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-23 23:44:52'),
(308, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 00:11:19'),
(309, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 30, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:11:49'),
(310, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 3, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:11:52'),
(311, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 41, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 00:12:33'),
(312, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 85, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 00:13:58'),
(313, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 00:19:55'),
(314, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 11, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:20:06'),
(315, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 3, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:20:09'),
(316, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 00:34:47'),
(317, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 11, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:34:59'),
(318, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:35:01'),
(319, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 00:37:00'),
(320, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:37:01'),
(321, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', NULL, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 00:37:01'),
(322, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 186, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 00:40:07'),
(323, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 328, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 00:45:35'),
(324, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 623, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 00:55:58'),
(325, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 658, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:06:56'),
(326, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 91, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:08:27'),
(327, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 198, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:11:45'),
(328, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 250, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:15:55'),
(329, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 41, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:16:36'),
(330, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 01:25:57'),
(331, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 10, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 01:26:07'),
(332, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 01:26:09'),
(333, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 72, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:27:21'),
(334, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 288, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:32:09'),
(335, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 107, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:33:56'),
(336, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 44, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:34:40'),
(337, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 94, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:36:14'),
(338, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 866, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:50:40'),
(339, 1, 1, 'Created SalaryComponent', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Salary Component', NULL, 'App\\Models\\SalaryComponent', 17, 'Created SalaryComponent #17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 17, \"name\": \"Basic salary\", \"type\": \"allowance\", \"status\": \"active\", \"system_id\": 1, \"description\": null}, \"old\": []}', '2026-11-24 01:53:50'),
(340, 1, 1, 'Created EmployeeSalaryComponent', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Employee Salary Component', NULL, 'App\\Models\\EmployeeSalaryComponent', 14, 'Created EmployeeSalaryComponent #14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 14, \"name\": \"Basic salary\", \"type\": \"allowance\", \"source\": \"Master\", \"description\": null, \"employee_id\": 3, \"calculation_type\": \"Fixed Amount\", \"configured_value\": 12000, \"calculated_amount\": 12000, \"salary_component_id\": 17}, \"old\": []}', '2026-11-24 01:53:50'),
(341, 1, 1, 'Updated Employee', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 3, 'Updated Employee #3 (salary, allowances)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"salary\": 12960, \"allowances\": 12960}, \"old\": {\"salary\": \"10560.00\", \"allowances\": \"960.00\"}}', '2026-11-24 01:53:50'),
(342, 1, 1, 'Created / Submitted', 'action', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Employees', 1, NULL, NULL, 'Created / Submitted in Employees (/employees/3/salary-components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 01:53:51'),
(343, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:53:52'),
(344, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 01:56:31'),
(345, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 01:56:33'),
(346, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 01:56:34'),
(347, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 158, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:59:12'),
(348, 1, 1, 'Updated EmployeeSalaryComponent', 'record', '/employees/3/salary-components/3/update', 'employees.salary-components.update', 'POST', 'Employee Salary Component', NULL, 'App\\Models\\EmployeeSalaryComponent', 3, 'Updated EmployeeSalaryComponent #3 (calculation_type, configured_value, calculated_amount)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"calculation_type\": \"Fixed Amount\", \"configured_value\": 4800, \"calculated_amount\": 4800}, \"old\": {\"calculation_type\": \"Percentage\", \"configured_value\": \"10.00\", \"calculated_amount\": \"960.00\"}}', '2026-11-24 01:59:23'),
(349, 1, 1, 'Updated Employee', 'record', '/employees/3/salary-components/3/update', 'employees.salary-components.update', 'POST', 'Master', NULL, 'App\\Models\\Employee', 3, 'Updated Employee #3 (salary, allowances)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"salary\": 16800, \"allowances\": 16800}, \"old\": {\"salary\": \"12960.00\", \"allowances\": \"12960.00\"}}', '2026-11-24 01:59:23'),
(350, 1, 1, 'Created / Submitted', 'action', '/employees/3/salary-components/3/update', 'employees.salary-components.update', 'POST', 'Employees', 1, NULL, NULL, 'Created / Submitted in Employees (/employees/3/salary-components/3/update)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 01:59:24'),
(351, 1, 1, 'Created EmployeeSalaryComponent', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Employee Salary Component', NULL, 'App\\Models\\EmployeeSalaryComponent', 17, 'Created EmployeeSalaryComponent #17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 17, \"name\": \"Professional Tax (PT)\", \"type\": \"deduction\", \"source\": \"Master\", \"description\": \"State government professional tax deduction\", \"employee_id\": 3, \"calculation_type\": \"Fixed Amount\", \"configured_value\": 1000, \"calculated_amount\": 1000, \"salary_component_id\": 5}, \"old\": []}', '2026-11-24 01:59:51'),
(352, 1, 1, 'Updated Employee', 'record', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 3, 'Updated Employee #3 (salary, deductions)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"salary\": 15800, \"deductions\": 1000}, \"old\": {\"salary\": \"16800.00\", \"deductions\": \"0.00\"}}', '2026-11-24 01:59:51'),
(353, 1, 1, 'Created / Submitted', 'action', '/employees/3/salary-components', 'employees.salary-components.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/3/salary-components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 01:59:51'),
(354, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 01:59:53'),
(355, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 340, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 02:05:33'),
(356, 1, 1, 'Created EmployeePayroll', 'record', '/employees/3/payrolls', 'employees.payrolls.store', 'POST', 'Employee Payroll', NULL, 'App\\Models\\EmployeePayroll', 3, 'Created EmployeePayroll #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"status\": \"Paid\", \"allowances\": 16800, \"deductions\": 1000, \"net_salary\": 15800, \"employee_id\": 3, \"basic_salary\": 0, \"overtime_pay\": 0, \"payroll_month\": \"November 2026\"}, \"old\": []}', '2026-11-24 02:05:45'),
(357, 1, 1, 'Created / Submitted', 'action', '/employees/3/payrolls', 'employees.payrolls.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/3/payrolls)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 02:05:45'),
(358, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 273, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 02:10:18'),
(359, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 21, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 02:10:39'),
(360, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 02:54:35'),
(361, 1, 1, 'Created PayrollFinancialPeriod', 'record', '/payroll/financial-period', 'payroll.financial-period.store', 'POST', 'Payroll Financial Period', NULL, 'App\\Models\\PayrollFinancialPeriod', 1, 'Created PayrollFinancialPeriod #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 1, \"name\": \"FY 2026-2027\", \"notes\": null, \"status\": \"active\", \"end_date\": \"2027-03-24 00:00:00\", \"system_id\": 1, \"is_current\": false, \"start_date\": \"2026-11-24 00:00:00\", \"working_days\": 26}, \"old\": []}', '2026-11-24 02:55:26'),
(362, 1, 1, 'Created / Submitted', 'action', '/payroll/financial-period', 'payroll.financial-period.store', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 02:55:26'),
(363, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 02:55:27'),
(364, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 402, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:02:09'),
(365, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 807, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:15:36'),
(366, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 03:28:11'),
(367, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 8, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 03:28:19'),
(368, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 03:28:20'),
(369, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 03:28:21'),
(370, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 113, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:30:14'),
(371, 1, 1, 'Created EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Created EmployeeLeaveQuota #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 1, \"system_id\": 1, \"employee_id\": 3, \"casual_allowed\": 15, \"medical_allowed\": 10, \"financial_period_id\": 1}, \"old\": []}', '2026-11-24 03:30:37'),
(372, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 03:30:37'),
(373, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:30:38'),
(374, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', 9, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 03:30:47'),
(375, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 9, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:30:56'),
(376, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 15.5}, \"old\": {\"casual_allowed\": 15}}', '2026-11-24 03:33:42'),
(377, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:33:42'),
(378, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 15}, \"old\": {\"casual_allowed\": 15.5}}', '2026-11-24 03:33:45'),
(379, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:33:45'),
(380, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (medical_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"medical_allowed\": 9.5}, \"old\": {\"medical_allowed\": 10}}', '2026-11-24 03:33:57'),
(381, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:33:57'),
(382, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (medical_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"medical_allowed\": 10}, \"old\": {\"medical_allowed\": 9.5}}', '2026-11-24 03:33:58'),
(383, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:33:58'),
(384, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 161, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:36:39'),
(385, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 18, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:36:57'),
(386, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 6, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:37:03'),
(387, 1, 1, 'Created Employee', 'record', '/employees', 'employees.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 6, 'Created Employee #6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 6, \"hra\": 0, \"city\": \"cherthala\", \"name\": \"Athira L\", \"email\": \"athira@virtualsys.com\", \"phone\": \"+91 98765 00001\", \"shift\": \"General Day Shift\", \"state\": null, \"branch\": \"Head Office\", \"gender\": \"Female\", \"salary\": 0, \"status\": \"active\", \"country\": \"India\", \"bank_name\": \"HDFC\", \"ifsc_code\": \"hdfc56865\", \"last_name\": \"L\", \"system_id\": 1, \"allowances\": 0, \"deductions\": 0, \"department\": \"virtual sys\", \"first_name\": \"Athira\", \"pan_number\": \"ytu76yuhgjhgj\", \"photo_path\": null, \"reports_to\": null, \"uan_number\": null, \"work_email\": \"athira@virtualsys.com\", \"blood_group\": \"A-ve\", \"designation\": \"Software engineer\", \"employee_id\": \"EMP-1002\", \"nationality\": \"Indian\", \"postal_code\": null, \"basic_salary\": 0, \"joining_date\": \"2026-11-24 00:00:00\", \"portal_email\": \"athira@virtualsys.com\", \"date_of_birth\": \"2026-11-26 00:00:00\", \"notice_period\": null, \"work_location\": \"Cherthala, Kerala\", \"address_line_1\": \"test\", \"address_line_2\": null, \"personal_email\": \"athira@gmail.com\", \"alternate_phone\": null, \"employment_type\": \"Full-Time Permanent\", \"confirmation_date\": null, \"probation_end_date\": null, \"bank_account_number\": \"6557657686\"}, \"old\": []}', '2026-11-24 03:39:05'),
(388, 1, 1, 'Created / Submitted', 'action', '/employees', 'employees.store', 'POST', 'Employees', 1, NULL, NULL, 'Created / Submitted in Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 03:39:06'),
(389, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:39:15'),
(390, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 61, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:40:16'),
(391, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 15.5}, \"old\": {\"casual_allowed\": 15}}', '2026-11-24 03:40:52'),
(392, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:40:52'),
(393, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 15}, \"old\": {\"casual_allowed\": 15.5}}', '2026-11-24 03:40:53'),
(394, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:40:53'),
(395, 1, 1, 'Created EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Created EmployeeLeaveQuota #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 2, \"system_id\": 1, \"employee_id\": 6, \"casual_allowed\": 14.5, \"medical_allowed\": 10, \"financial_period_id\": 1}, \"old\": []}', '2026-11-24 03:40:56'),
(396, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:40:56'),
(397, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (casual_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 15}, \"old\": {\"casual_allowed\": 14.5}}', '2026-11-24 03:40:56'),
(398, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', 1, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:40:57'),
(399, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 73, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:42:10'),
(400, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 18, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:42:28'),
(401, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 8, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:42:36'),
(402, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 58, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:43:34'),
(403, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 379, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:49:53'),
(404, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 5}, \"old\": {\"casual_taken\": 0}}', '2026-11-24 03:51:12'),
(405, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:51:12'),
(406, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0}, \"old\": {\"casual_taken\": 5}}', '2026-11-24 03:51:26'),
(407, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:51:26'),
(408, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 5}, \"old\": {\"casual_taken\": 0}}', '2026-11-24 03:51:36'),
(409, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:51:36'),
(410, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', 5, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 03:51:41'),
(411, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:51:43'),
(412, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0}, \"old\": {\"casual_taken\": 5}}', '2026-11-24 03:51:51'),
(413, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:51:51'),
(414, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 5}, \"old\": {\"casual_taken\": 0}}', '2026-11-24 03:52:05'),
(415, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:52:05'),
(416, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 6, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:52:11'),
(417, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0}, \"old\": {\"casual_taken\": 5}}', '2026-11-24 03:52:24'),
(418, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:52:24'),
(419, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 4, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 03:52:28'),
(420, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 485, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:00:33'),
(421, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 04:07:17'),
(422, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 10, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:07:27'),
(423, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:07:29'),
(424, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:07:31'),
(425, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:07:33'),
(426, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 250, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:11:43'),
(427, 1, 1, 'Created PayrollSalaryHead', 'record', '/payroll/salary-head', 'payroll.salary-head.store', 'POST', 'Payroll Salary Head', NULL, 'App\\Models\\PayrollSalaryHead', 1, 'Created PayrollSalaryHead #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 1, \"name\": \"Hindustan erp November 2026\", \"year\": 2026, \"month\": 11, \"status\": \"Draft\", \"to_date\": \"2026-11-30 00:00:00\", \"from_date\": \"2026-11-01 00:00:00\", \"system_id\": 1, \"financial_period_id\": 1}, \"old\": []}', '2026-11-24 04:13:07'),
(428, 1, 1, 'Created / Submitted', 'action', '/payroll/salary-head', 'payroll.salary-head.store', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 04:13:07');
INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `category`, `screen`, `route_name`, `method`, `module`, `duration_seconds`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `metadata`, `created_at`) VALUES
(429, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:13:09'),
(430, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 19, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:13:28'),
(431, 1, 1, 'Created PayrollSalary', 'record', '/payroll/salary/generate', 'payroll.salary.generate', 'POST', 'Payroll Salary', NULL, 'App\\Models\\PayrollSalary', 1, 'Created PayrollSalary #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 1, \"hra\": 3500, \"status\": \"Pending\", \"system_id\": 1, \"allowances\": 16800, \"deductions\": 1000, \"leave_days\": 0, \"net_salary\": 28900, \"employee_id\": 3, \"basic_salary\": 9600, \"gross_salary\": 29900, \"overtime_pay\": 0, \"payment_date\": null, \"payment_mode\": \"Bank Transfer\", \"present_days\": 26, \"working_days\": 26, \"payroll_month\": \"Hindustan erp November 2026\", \"overtime_hours\": 0, \"salary_head_id\": 1}, \"old\": []}', '2026-11-24 04:22:11'),
(432, 1, 1, 'Created PayrollSalary', 'record', '/payroll/salary/generate', 'payroll.salary.generate', 'POST', 'Payroll Salary', NULL, 'App\\Models\\PayrollSalary', 2, 'Created PayrollSalary #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 2, \"hra\": 0, \"status\": \"Pending\", \"system_id\": 1, \"allowances\": 0, \"deductions\": 0, \"leave_days\": 0, \"net_salary\": 0, \"employee_id\": 6, \"basic_salary\": 0, \"gross_salary\": 0, \"overtime_pay\": 0, \"payment_date\": null, \"payment_mode\": \"Bank Transfer\", \"present_days\": 26, \"working_days\": 26, \"payroll_month\": \"Hindustan erp November 2026\", \"overtime_hours\": 0, \"salary_head_id\": 1}, \"old\": []}', '2026-11-24 04:22:11'),
(433, 1, 1, 'Updated PayrollSalaryHead', 'record', '/payroll/salary/generate', 'payroll.salary.generate', 'POST', 'Payroll Salary Head', NULL, 'App\\Models\\PayrollSalaryHead', 1, 'Updated PayrollSalaryHead #1 (total_employees, total_gross, total_deductions, total_net)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"total_net\": 28900, \"total_gross\": 29900, \"total_employees\": 2, \"total_deductions\": 1000}, \"old\": {\"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"total_employees\": 0, \"total_deductions\": \"0.00\"}}', '2026-11-24 04:22:11'),
(434, 1, 1, 'Created / Submitted', 'action', '/payroll/salary/generate', 'payroll.salary.generate', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/salary/generate)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 04:22:11'),
(435, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:22:13'),
(436, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 04:30:30'),
(437, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 10, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:30:40'),
(438, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:30:42'),
(439, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:30:44'),
(440, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 3, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:30:47'),
(441, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 3, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:30:50'),
(442, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 148, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:33:18'),
(443, 1, 1, 'Created / Submitted', 'action', '/payroll/salary/generate', 'payroll.salary.generate', 'POST', 'Payroll', 254, NULL, NULL, 'Created / Submitted in Payroll (/payroll/salary/generate)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 04:37:32'),
(444, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 5, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:37:37'),
(445, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 540, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:46:37'),
(446, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 04:51:03'),
(447, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 13, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:51:16'),
(448, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:51:17'),
(449, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:51:19'),
(450, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:51:20'),
(451, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 04:51:22'),
(452, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 102, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:53:04'),
(453, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 61, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 04:54:05'),
(454, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-24 05:00:51'),
(455, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 13, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 05:01:04'),
(456, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 5, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 05:01:09'),
(457, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 05:01:11'),
(458, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 05:01:12'),
(459, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 05:01:14'),
(460, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-24 05:01:15'),
(461, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 145, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:03:40'),
(462, 1, 1, 'Exported Data', 'export', '/payroll/salary-report/export', 'payroll.salary-report.export', 'GET', 'Payroll', 1585, NULL, NULL, 'Exported Payroll (/payroll/salary-report/export)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:30:05'),
(463, 1, 1, 'Exported Data', 'export', '/payroll/salary-report/export', 'payroll.salary-report.export', 'GET', 'Payroll', 37, NULL, NULL, 'Exported Payroll (/payroll/salary-report/export)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:30:42'),
(464, 1, 1, 'Screen Viewed', 'screen', '/cheque-realization/queue', 'cheque-realization.queue', 'GET', 'Cheque Realization', 47, NULL, NULL, 'Viewed Cheque Realization (/cheque-realization/queue)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:31:29'),
(465, 1, 1, 'Screen Viewed', 'screen', '/reports/customer-ledger', 'reports.customer_ledger', 'GET', 'Reports', 35, NULL, NULL, 'Viewed Reports (/reports/customer-ledger)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:32:04'),
(466, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 726, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:44:10'),
(467, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 597, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:54:07'),
(468, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 21, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:54:28'),
(469, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 254, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 05:58:42'),
(470, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 1189, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:18:31'),
(471, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 37, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:19:08'),
(472, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 6, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:19:14'),
(473, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0.5}, \"old\": {\"casual_taken\": 0}}', '2026-11-24 06:45:58'),
(474, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:45:58'),
(475, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0}, \"old\": {\"casual_taken\": 0.5}}', '2026-11-24 06:45:59'),
(476, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:45:59'),
(477, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0.5}, \"old\": {\"casual_taken\": 0}}', '2026-11-24 06:46:00'),
(478, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:00'),
(479, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 1}, \"old\": {\"casual_taken\": 0.5}}', '2026-11-24 06:46:01'),
(480, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:01'),
(481, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0.5}, \"old\": {\"casual_taken\": 1}}', '2026-11-24 06:46:05'),
(482, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:05'),
(483, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 1}, \"old\": {\"casual_taken\": 0.5}}', '2026-11-24 06:46:06'),
(484, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:06'),
(485, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 1.5}, \"old\": {\"casual_taken\": 1}}', '2026-11-24 06:46:07'),
(486, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:07'),
(487, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 2}, \"old\": {\"casual_taken\": 1.5}}', '2026-11-24 06:46:08'),
(488, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:08'),
(489, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 1.5}, \"old\": {\"casual_taken\": 2}}', '2026-11-24 06:46:09'),
(490, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:09'),
(491, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 1}, \"old\": {\"casual_taken\": 1.5}}', '2026-11-24 06:46:10'),
(492, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:10'),
(493, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0.5}, \"old\": {\"casual_taken\": 1}}', '2026-11-24 06:46:11'),
(494, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:11'),
(495, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0}, \"old\": {\"casual_taken\": 0.5}}', '2026-11-24 06:46:12'),
(496, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:12'),
(497, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0.5}, \"old\": {\"casual_taken\": 0}}', '2026-11-24 06:46:14'),
(498, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:14'),
(499, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 1}, \"old\": {\"casual_taken\": 0.5}}', '2026-11-24 06:46:14'),
(500, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', 1, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:15'),
(501, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0.5}, \"old\": {\"casual_taken\": 1}}', '2026-11-24 06:46:16'),
(502, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:16'),
(503, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 0}, \"old\": {\"casual_taken\": 0.5}}', '2026-11-24 06:46:17'),
(504, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:46:17'),
(505, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 207, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:49:44'),
(506, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 396, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:56:20'),
(507, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 14, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 06:56:34'),
(508, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 466, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:04:20'),
(509, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 148, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:06:48'),
(510, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 269, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:11:17'),
(511, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 247, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:15:24'),
(512, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 283, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:20:07'),
(513, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 28, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:20:35'),
(514, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 12, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:20:47'),
(515, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 10, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:20:57'),
(516, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 7, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:21:04'),
(517, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 8, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 07:21:12'),
(518, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-24 22:46:29'),
(519, 1, 1, 'Created / Submitted', 'action', '/login', NULL, 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-24 22:46:30'),
(520, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-24 22:46:32'),
(521, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:19:27'),
(522, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 00:49:24'),
(523, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 00:49:25'),
(524, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 111, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:51:16'),
(525, 1, 1, 'Updated CompanyBankAccount', 'record', '/payroll/salary/1/pay', 'payroll.salary.pay', 'POST', 'Bank & Treasury', NULL, 'App\\Models\\CompanyBankAccount', 1, 'Updated CompanyBankAccount #1 (current_balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"current_balance\": 2261100}, \"old\": {\"current_balance\": \"2290000.00\"}}', '2026-11-25 00:52:15'),
(526, 1, 1, 'Created ChartOfAccount', 'record', '/payroll/salary/1/pay', 'payroll.salary.pay', 'POST', 'Accounting Reports', NULL, 'App\\Models\\ChartOfAccount', 35, 'Created ChartOfAccount #35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 35, \"is_active\": true, \"account_code\": 2011, \"account_name\": \"Salary Payable\", \"account_type\": \"LIABILITY\"}, \"old\": []}', '2026-11-25 00:52:15'),
(527, 1, 1, 'Created VoucherType', 'record', '/payroll/salary/1/pay', 'payroll.salary.pay', 'POST', 'Accounting Reports', NULL, 'App\\Models\\VoucherType', 30, 'Created VoucherType #30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 30, \"code\": \"SALARY_PAYMENT\", \"name\": \"Salary Payment\", \"prefix\": \"JV-SAL\", \"is_active\": true, \"description\": \"Generated on employee salary disbursement\"}, \"old\": []}', '2026-11-25 00:52:15'),
(528, 1, 1, 'Created JournalVoucher', 'record', '/payroll/salary/1/pay', 'payroll.salary.pay', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 14, 'Created JournalVoucher #14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 14, \"is_active\": true, \"narration\": \"Salary Payment to Anchumol S (EMP-1001) for Hindustan erp November 2026 via Axis Bank 9752\", \"voucher_no\": \"JV-SAL-2026-0001\", \"reference_id\": 1, \"voucher_date\": \"2026-11-25\", \"voucher_type_id\": 30}, \"old\": []}', '2026-11-25 00:52:16'),
(529, 1, 1, 'Updated PayrollSalary', 'record', '/payroll/salary/1/pay', 'payroll.salary.pay', 'POST', 'Payroll Salary', NULL, 'App\\Models\\PayrollSalary', 1, 'Updated PayrollSalary #1 (payment_date, company_bank_account_id, payment_reference, journal_voucher_id)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"Paid\", \"payment_date\": \"2026-11-25 00:00:00\", \"payment_reference\": \"1234566\", \"journal_voucher_id\": 14, \"company_bank_account_id\": 1}, \"old\": {\"status\": \"Pending\", \"payment_date\": null, \"payment_reference\": null, \"journal_voucher_id\": null, \"company_bank_account_id\": null}}', '2026-11-25 00:52:16'),
(530, 1, 1, 'Created / Submitted', 'action', '/payroll/salary/1/pay', 'payroll.salary.pay', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/salary/1/pay)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 00:52:16'),
(531, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:52:17'),
(532, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 24, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:52:41'),
(533, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 8, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:52:49'),
(534, 1, 1, 'Created EmployeeSalaryComponent', 'record', '/employees/6/salary-components', 'employees.salary-components.store', 'POST', 'Employee Salary Component', NULL, 'App\\Models\\EmployeeSalaryComponent', 28, 'Created EmployeeSalaryComponent #28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 28, \"name\": \"Basic salary\", \"type\": \"allowance\", \"source\": \"Master\", \"description\": null, \"employee_id\": 6, \"calculation_type\": \"Fixed Amount\", \"configured_value\": 14000, \"calculated_amount\": 14000, \"salary_component_id\": 17}, \"old\": []}', '2026-11-25 00:53:10'),
(535, 1, 1, 'Updated Employee', 'record', '/employees/6/salary-components', 'employees.salary-components.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 6, 'Updated Employee #6 (salary, allowances)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"salary\": 14000, \"allowances\": 14000}, \"old\": {\"salary\": \"0.00\", \"allowances\": \"0.00\"}}', '2026-11-25 00:53:10'),
(536, 1, 1, 'Created / Submitted', 'action', '/employees/6/salary-components', 'employees.salary-components.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/6/salary-components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 00:53:10'),
(537, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:53:11'),
(538, 1, 1, 'Created EmployeeSalaryComponent', 'record', '/employees/6/salary-components', 'employees.salary-components.store', 'POST', 'Employee Salary Component', NULL, 'App\\Models\\EmployeeSalaryComponent', 29, 'Created EmployeeSalaryComponent #29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 29, \"name\": \"House Rent Allowance (HRA)\", \"type\": \"allowance\", \"source\": \"Master\", \"description\": \"Housing rent allowance eligible for tax exemption under Sec 10(13A)\", \"employee_id\": 6, \"calculation_type\": \"Fixed Amount\", \"configured_value\": 4800, \"calculated_amount\": 4800, \"salary_component_id\": 1}, \"old\": []}', '2026-11-25 00:53:24'),
(539, 1, 1, 'Updated Employee', 'record', '/employees/6/salary-components', 'employees.salary-components.store', 'POST', 'Master', NULL, 'App\\Models\\Employee', 6, 'Updated Employee #6 (salary, allowances)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"salary\": 18800, \"allowances\": 18800}, \"old\": {\"salary\": \"14000.00\", \"allowances\": \"14000.00\"}}', '2026-11-25 00:53:24'),
(540, 1, 1, 'Created / Submitted', 'action', '/employees/6/salary-components', 'employees.salary-components.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/6/salary-components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 00:53:24'),
(541, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 13, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:53:37'),
(542, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 29, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:54:06'),
(543, 1, 1, 'Created / Submitted', 'action', '/payroll/salary/generate', 'payroll.salary.generate', 'POST', 'Payroll', 7, NULL, NULL, 'Created / Submitted in Payroll (/payroll/salary/generate)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 00:54:13'),
(544, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:54:15'),
(545, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 42, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:54:57'),
(546, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 00:55:06'),
(547, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 01:02:10'),
(548, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 01:02:11'),
(549, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 01:02:11'),
(550, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 134, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:04:25'),
(551, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 21, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:04:46'),
(552, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 01:09:03'),
(553, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 01:09:04'),
(554, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 131, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:11:16'),
(555, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 27, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:11:43'),
(556, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 01:20:47'),
(557, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 19, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 01:21:06'),
(558, 1, 1, 'Screen Viewed', 'screen', '/reports/balance-sheet', 'reports.balance_sheet', 'GET', 'Reports', 95, NULL, NULL, 'Viewed Reports (/reports/balance-sheet)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:22:41'),
(559, 1, 1, 'Screen Viewed', 'screen', '/reports/trial-balance', 'reports.trial_balance', 'GET', 'Reports', 56, NULL, NULL, 'Viewed Reports (/reports/trial-balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:23:37'),
(560, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 178, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:26:35'),
(561, 1, 1, 'Screen Viewed', 'screen', '/reports/trial-balance', 'reports.trial_balance', 'GET', 'Reports', 100, NULL, NULL, 'Viewed Reports (/reports/trial-balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:28:15'),
(562, 1, 1, 'Screen Viewed', 'screen', '/voucher-types', 'voucher-types.index', 'GET', 'Voucher Types', 11, NULL, NULL, 'Viewed Voucher Types (/voucher-types)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:28:26'),
(563, 1, 1, 'Screen Viewed', 'screen', '/journal-vouchers', 'journal-vouchers.index', 'GET', 'Accounting Reports', 9, NULL, NULL, 'Viewed Accounting Reports (/journal-vouchers)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:28:35'),
(564, 1, 1, 'Screen Viewed', 'screen', '/company-bank-accounts', 'company-bank-accounts.index', 'GET', 'Bank & Treasury', 66, NULL, NULL, 'Viewed Bank & Treasury (/company-bank-accounts)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:29:41'),
(565, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 235, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:33:36'),
(566, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 01:36:03'),
(567, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 01:36:05');
INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `category`, `screen`, `route_name`, `method`, `module`, `duration_seconds`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `metadata`, `created_at`) VALUES
(568, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 48, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:36:53'),
(569, 1, 1, 'Screen Viewed', 'screen', '/journal-vouchers', 'journal-vouchers.index', 'GET', 'Accounting Reports', 76, NULL, NULL, 'Viewed Accounting Reports (/journal-vouchers)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 01:38:09'),
(570, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 02:41:07'),
(571, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 7, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 02:41:14'),
(572, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 315, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 02:46:29'),
(573, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 11, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 02:46:40'),
(574, 1, 1, 'Created ChartOfAccount', 'record', '/office-rent', 'office-rent.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\ChartOfAccount', 39, 'Created ChartOfAccount #39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 39, \"is_active\": true, \"account_code\": 4006, \"account_name\": \"Office Rent Expense\", \"account_type\": \"EXPENSE\"}, \"old\": []}', '2026-11-25 03:26:32'),
(575, 1, 1, 'Created ChartOfAccount', 'record', '/office-rent', 'office-rent.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\ChartOfAccount', 40, 'Created ChartOfAccount #40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 40, \"is_active\": true, \"account_code\": 2012, \"account_name\": \"Office Rent Payable\", \"account_type\": \"LIABILITY\"}, \"old\": []}', '2026-11-25 03:26:32'),
(576, 1, 1, 'Created ChartOfAccount', 'record', '/office-rent', 'office-rent.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\ChartOfAccount', 41, 'Created ChartOfAccount #41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 41, \"is_active\": true, \"account_code\": 2023, \"account_name\": \"TDS Payable (Sec 194I)\", \"account_type\": \"LIABILITY\"}, \"old\": []}', '2026-11-25 03:26:32'),
(577, 1, 1, 'Created VoucherType', 'record', '/office-rent', 'office-rent.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\VoucherType', 33, 'Created VoucherType #33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 33, \"code\": \"OFFICE_RENT\", \"name\": \"Office Rent Entry\", \"prefix\": \"JV-RENT\", \"is_active\": true, \"description\": \"Generated on office rent expense booking and payment\"}, \"old\": []}', '2026-11-25 03:26:32'),
(578, 1, 1, 'Created OfficeRent', 'record', '/office-rent', 'office-rent.store', 'POST', 'Office Rent', NULL, 'App\\Models\\OfficeRent', 3, 'Created OfficeRent #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"remarks\": \"tgfrgdhfd\", \"rent_year\": 2026, \"system_id\": 1, \"created_by\": 1, \"rent_month\": 11, \"tds_amount\": 100000, \"payment_date\": null, \"voucher_date\": \"2026-11-25 00:00:00\", \"landlord_name\": \"kannur\", \"payment_status\": \"Unpaid\", \"office_location\": \"HINDUSTAN\", \"gross_rent_amount\": 500000, \"payment_reference\": null, \"rent_period_label\": \"November 2026\", \"net_payable_amount\": 400000, \"company_bank_account_id\": null}, \"old\": []}', '2026-11-25 03:26:32'),
(579, 1, 1, 'Created JournalVoucher', 'record', '/office-rent', 'office-rent.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 19, 'Created JournalVoucher #19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 19, \"is_active\": true, \"narration\": \"Office Rent (HINDUSTAN) for November 2026 - Landlord: kannur | Booked as Unpaid Payable\", \"voucher_no\": \"JV-RENT-2026-0003\", \"reference_id\": 3, \"voucher_date\": \"2026-11-25\", \"voucher_type_id\": 33}, \"old\": []}', '2026-11-25 03:26:32'),
(580, 1, 1, 'Updated OfficeRent', 'record', '/office-rent', 'office-rent.store', 'POST', 'Office Rent', NULL, 'App\\Models\\OfficeRent', 3, 'Updated OfficeRent #3 (journal_voucher_id)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"journal_voucher_id\": 19}, \"old\": {\"journal_voucher_id\": null}}', '2026-11-25 03:26:32'),
(581, 1, 1, 'Created / Submitted', 'action', '/office-rent', 'office-rent.store', 'POST', 'Office Rent', NULL, NULL, NULL, 'Created / Submitted in Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 03:26:32'),
(582, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 2, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 03:26:34'),
(583, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 03:35:57'),
(584, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 17, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 03:36:14'),
(585, 1, 1, 'Screen Viewed', 'screen', '/journal-vouchers', 'journal-vouchers.index', 'GET', 'Accounting Reports', 96, NULL, NULL, 'Viewed Accounting Reports (/journal-vouchers)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 03:37:50'),
(586, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 03:49:03'),
(587, 1, 1, 'Screen Viewed', 'screen', '/journal-vouchers', 'journal-vouchers.index', 'GET', 'Accounting Reports', 1, NULL, NULL, 'Viewed Accounting Reports (/journal-vouchers)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 03:49:04'),
(588, 1, 1, 'Screen Viewed', 'screen', '/journal-vouchers/19', 'journal-vouchers.show', 'GET', 'Accounting Reports', NULL, NULL, NULL, 'Viewed Accounting Reports (/journal-vouchers/19)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 03:49:04'),
(589, 1, 1, 'Screen Viewed', 'screen', '/journal-vouchers', 'journal-vouchers.index', 'GET', 'Accounting Reports', 45, NULL, NULL, 'Viewed Accounting Reports (/journal-vouchers)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 03:49:49'),
(590, 1, 1, 'Updated CompanyBankAccount', 'record', '/office-rent/3/pay', 'office-rent.pay', 'POST', 'Bank & Treasury', NULL, 'App\\Models\\CompanyBankAccount', 1, 'Updated CompanyBankAccount #1 (current_balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"current_balance\": 1861100}, \"old\": {\"current_balance\": \"2261100.00\"}}', '2026-11-25 03:56:21'),
(591, 1, 1, 'Created VoucherType', 'record', '/office-rent/3/pay', 'office-rent.pay', 'POST', 'Accounting Reports', NULL, 'App\\Models\\VoucherType', 35, 'Created VoucherType #35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 35, \"code\": \"OFFICE_RENT_PAYMENT\", \"name\": \"Office Rent Payment\", \"prefix\": \"JV-RENTP\", \"is_active\": true, \"description\": \"Generated on settling unpaid office rent payable\"}, \"old\": []}', '2026-11-25 03:56:21'),
(592, 1, 1, 'Created JournalVoucher', 'record', '/office-rent/3/pay', 'office-rent.pay', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 23, 'Created JournalVoucher #23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 23, \"is_active\": true, \"narration\": \"Office Rent Payable settled for HINDUSTAN (November 2026) to kannur via Tabasco Hindustan - Axis Bank 9752 (A/C 9752)\", \"voucher_no\": \"JV-RENTP-2026-0003\", \"reference_id\": 3, \"voucher_date\": \"2026-11-25\", \"voucher_type_id\": 35}, \"old\": []}', '2026-11-25 03:56:21'),
(593, 1, 1, 'Updated OfficeRent', 'record', '/office-rent/3/pay', 'office-rent.pay', 'POST', 'Office Rent', NULL, 'App\\Models\\OfficeRent', 3, 'Updated OfficeRent #3 (payment_status, company_bank_account_id, payment_reference, payment_date)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"payment_date\": \"2026-11-25 00:00:00\", \"payment_status\": \"Paid\", \"payment_reference\": \"123434\", \"payment_voucher_id\": 23, \"company_bank_account_id\": 1}, \"old\": {\"payment_date\": null, \"payment_status\": \"Unpaid\", \"payment_reference\": null, \"payment_voucher_id\": null, \"company_bank_account_id\": null}}', '2026-11-25 03:56:21'),
(594, 1, 1, 'Created / Submitted', 'action', '/office-rent/3/pay', 'office-rent.pay', 'POST', 'Office Rent', NULL, NULL, NULL, 'Created / Submitted in Office Rent (/office-rent/3/pay)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 03:56:21'),
(595, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 2, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 03:56:23'),
(596, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 04:03:22'),
(597, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 12, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 04:03:34'),
(598, 1, 1, 'Exported Data', 'export', '/office-rent/export', 'office-rent.export', 'GET', 'Office Rent', NULL, NULL, NULL, 'Exported Office Rent (/office-rent/export)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 04:03:34'),
(599, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 188, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:06:42'),
(600, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 727, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:18:49'),
(601, 1, 1, 'Screen Viewed', 'screen', '/admin/roles', 'admin.roles.index', 'GET', 'Users & Roles', 60, NULL, NULL, 'Viewed Users & Roles (/admin/roles)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:19:49'),
(602, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 450, 'Created Permission #450', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 450, \"name\": \"payroll.financial_period.excel\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(603, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 451, 'Created Permission #451', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 451, \"name\": \"payroll.financial_period.pdf\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(604, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 452, 'Created Permission #452', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 452, \"name\": \"payroll.leave_quota.excel\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(605, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 453, 'Created Permission #453', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 453, \"name\": \"payroll.leave_quota.pdf\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(606, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 454, 'Created Permission #454', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 454, \"name\": \"payroll.salary_head.excel\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(607, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 455, 'Created Permission #455', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 455, \"name\": \"payroll.salary_head.pdf\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(608, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 456, 'Created Permission #456', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 456, \"name\": \"payroll.salary_report.create\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(609, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 457, 'Created Permission #457', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 457, \"name\": \"payroll.salary_report.edit\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(610, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 458, 'Created Permission #458', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 458, \"name\": \"payroll.salary_report.delete\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(611, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 459, 'Created Permission #459', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 459, \"name\": \"payroll.components.excel\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(612, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 460, 'Created Permission #460', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 460, \"name\": \"payroll.components.pdf\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(613, 1, 1, 'Created Permission', 'record', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', NULL, 'Spatie\\Permission\\Models\\Permission', 461, 'Created Permission #461', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 461, \"name\": \"office_rent.manage.pdf\", \"guard_name\": \"web\"}, \"old\": []}', '2026-11-25 04:20:17'),
(614, 1, 1, 'Created / Submitted', 'action', '/admin/roles/1/permissions', 'admin.roles.permissions', 'POST', 'Users & Roles', 33, NULL, NULL, 'Created / Submitted in Users & Roles (/admin/roles/1/permissions)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:20:50'),
(615, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 10, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:21:00'),
(616, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 04:25:28'),
(617, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 18, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 04:25:46'),
(618, 1, 1, 'Exported Data', 'export', '/office-rent/export', 'office-rent.export', 'GET', 'Office Rent', 2, NULL, NULL, 'Exported Office Rent (/office-rent/export)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 04:25:48'),
(619, 1, 1, 'Exported Data', 'export', '/office-rent/export', 'office-rent.export', 'GET', 'Office Rent', 39, NULL, NULL, 'Exported Office Rent (/office-rent/export)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:26:27'),
(620, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 27, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:26:54'),
(621, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 17, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:27:11'),
(622, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 55, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:28:06'),
(623, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 04:31:50'),
(624, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 11, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 04:32:01'),
(625, 1, 1, 'Exported Data', 'export', '/office-rent/export', 'office-rent.export', 'GET', 'Office Rent', NULL, NULL, NULL, 'Exported Office Rent (/office-rent/export)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 04:32:02'),
(626, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 78, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 04:33:20'),
(627, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 05:33:57'),
(628, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 1, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 05:33:58'),
(629, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 05:33:59'),
(630, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 05:34:01'),
(631, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 430, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 05:41:11'),
(632, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-25 05:41:17'),
(633, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 4, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 05:41:21'),
(634, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 2, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-25 05:41:23'),
(635, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-25 05:42:40'),
(636, 1, 1, 'Created / Submitted', 'action', '/login', 'generated::X01P6Sras1OgKFY0', 'POST', 'Login', NULL, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 05:42:40'),
(637, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 2, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 05:42:42'),
(638, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 10, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 05:42:52'),
(639, 1, 1, 'Screen Viewed', 'screen', '/expenses/ra-bills/verification', 'expenses.ra-bills.verification', 'GET', 'Contractor Desk', 65, NULL, NULL, 'Viewed Contractor Desk (/expenses/ra-bills/verification)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 05:43:57'),
(640, 1, 1, 'Screen Viewed', 'screen', '/cheque-realization/queue', 'cheque-realization.queue', 'GET', 'Cheque Realization', 18, NULL, NULL, 'Viewed Cheque Realization (/cheque-realization/queue)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 05:44:15'),
(641, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 1141, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 06:03:16'),
(642, 1, 1, 'Screen Viewed', 'screen', '/chart-of-accounts', 'chart-of-accounts.index', 'GET', 'Chart Of Accounts', 1674, NULL, NULL, 'Viewed Chart Of Accounts (/chart-of-accounts)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 06:31:10'),
(643, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 07:18:23'),
(644, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 16, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 07:18:39'),
(645, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 17, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 07:18:56'),
(646, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 23, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 07:19:19'),
(647, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', NULL, '2026-11-25 23:19:50'),
(648, 1, 1, 'Created / Submitted', 'action', '/login', 'generated::X01P6Sras1OgKFY0', 'POST', 'Login', 1, NULL, NULL, 'Created / Submitted in Login (/login)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-25 23:19:51'),
(649, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 1, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-25 23:19:52'),
(650, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 00:27:14'),
(651, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 6, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 00:27:20'),
(652, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 00:27:25'),
(653, 1, 1, 'Exported Data', 'export', '/office-rent/export', 'office-rent.export', 'GET', 'Office Rent', 1, NULL, NULL, 'Exported Office Rent (/office-rent/export)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 00:27:26'),
(654, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 104, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:29:10'),
(655, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 625, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:39:35'),
(656, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 50, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:40:25'),
(657, 1, 1, 'Screen Viewed', 'screen', '/payroll/financial-period', 'payroll.financial-period.index', 'GET', 'Payroll', 60, NULL, NULL, 'Viewed Payroll (/payroll/financial-period)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:41:25'),
(658, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 9, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:41:34'),
(659, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 1, 'Updated EmployeeLeaveQuota #1 (casual_taken)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_taken\": 5}, \"old\": {\"casual_taken\": 0}}', '2026-11-26 00:41:50'),
(660, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:41:50'),
(661, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-head', 'payroll.salary-head.index', 'GET', 'Payroll', 95, NULL, NULL, 'Viewed Payroll (/payroll/salary-head)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:43:25'),
(662, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 10, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:43:35'),
(663, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 33, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:44:08'),
(664, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 39, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:44:47'),
(665, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 27, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:45:14'),
(666, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 8, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:45:22'),
(667, 1, 1, 'Screen Viewed', 'screen', '/payroll/components', 'payroll.components.index', 'GET', 'Payroll', 47, NULL, NULL, 'Viewed Payroll (/payroll/components)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:46:09'),
(668, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 30, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:46:39'),
(669, 1, 1, 'Created OfficeRent', 'record', '/office-rent', 'office-rent.store', 'POST', 'Office Rent', NULL, 'App\\Models\\OfficeRent', 8, 'Created OfficeRent #8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 8, \"remarks\": null, \"rent_year\": 2026, \"system_id\": 1, \"created_by\": 1, \"rent_month\": 10, \"tds_amount\": 0, \"payment_date\": null, \"voucher_date\": \"2026-11-05 00:00:00\", \"landlord_name\": \"jhon\", \"payment_status\": \"Unpaid\", \"office_location\": \"HINDUSTAN\", \"gross_rent_amount\": 45000, \"payment_reference\": null, \"rent_period_label\": \"October 2026\", \"net_payable_amount\": 45000, \"company_bank_account_id\": null}, \"old\": []}', '2026-11-26 00:49:26'),
(670, 1, 1, 'Created JournalVoucher', 'record', '/office-rent', 'office-rent.store', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 27, 'Created JournalVoucher #27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 27, \"is_active\": true, \"narration\": \"Office Rent (HINDUSTAN) for October 2026 - Landlord: jhon | Booked as Unpaid Payable\", \"voucher_no\": \"JV-RENT-2026-0008\", \"reference_id\": 8, \"voucher_date\": \"2026-11-05\", \"voucher_type_id\": 33}, \"old\": []}', '2026-11-26 00:49:26'),
(671, 1, 1, 'Updated OfficeRent', 'record', '/office-rent', 'office-rent.store', 'POST', 'Office Rent', NULL, 'App\\Models\\OfficeRent', 8, 'Updated OfficeRent #8 (journal_voucher_id)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"journal_voucher_id\": 27}, \"old\": {\"journal_voucher_id\": null}}', '2026-11-26 00:49:27'),
(672, 1, 1, 'Created / Submitted', 'action', '/office-rent', 'office-rent.store', 'POST', 'Office Rent', NULL, NULL, NULL, 'Created / Submitted in Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 00:49:27'),
(673, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 1, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:49:28'),
(674, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 14, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:49:42'),
(675, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 8, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 00:49:50'),
(676, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 00:58:08'),
(677, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 00:58:17'),
(678, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', NULL, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 00:58:17'),
(679, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 108, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 01:00:05'),
(680, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 42, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 01:00:47'),
(681, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 40, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 01:01:27'),
(682, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 812, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 01:14:59'),
(683, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 01:19:48'),
(684, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 01:19:57'),
(685, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 35, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 01:20:32'),
(686, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 01:44:07'),
(687, 1, 1, 'Screen Viewed', 'screen', '/employees/leave-types', 'employees.leave-types.index', 'GET', 'Employees', 8, NULL, NULL, 'Viewed Employees (/employees/leave-types)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 01:44:15'),
(688, 1, 1, 'Screen Viewed', 'screen', '/chart-of-accounts', 'chart-of-accounts.index', 'GET', 'Chart Of Accounts', 1189, NULL, NULL, 'Viewed Chart Of Accounts (/chart-of-accounts)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 02:04:04'),
(689, 1, 1, 'Screen Viewed', 'screen', '/reports/balance-sheet', 'reports.balance_sheet', 'GET', 'Reports', 24, NULL, NULL, 'Viewed Reports (/reports/balance-sheet)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 02:04:28'),
(690, 1, 1, 'Created / Submitted', 'action', '/chart-of-accounts', 'chart-of-accounts.store', 'POST', 'Chart Of Accounts', 52, NULL, NULL, 'Created / Submitted in Chart Of Accounts (/chart-of-accounts)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 02:05:20'),
(691, 1, 1, 'Screen Viewed', 'screen', '/chart-of-accounts', 'chart-of-accounts.index', 'GET', 'Chart Of Accounts', 2, NULL, NULL, 'Viewed Chart Of Accounts (/chart-of-accounts)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 02:05:22'),
(692, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 02:44:52'),
(693, 1, 1, 'Screen Viewed', 'screen', '/chart-of-accounts', 'chart-of-accounts.index', 'GET', 'Chart Of Accounts', 12, NULL, NULL, 'Viewed Chart Of Accounts (/chart-of-accounts)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 02:45:04'),
(694, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 1770, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:14:34'),
(695, 1, 1, 'Screen Viewed', 'screen', '/employees/leave-types', 'employees.leave-types.index', 'GET', 'Employees', 13, NULL, NULL, 'Viewed Employees (/employees/leave-types)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:14:47'),
(696, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 130, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:16:57'),
(697, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 10, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:17:07'),
(698, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 30, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:17:37'),
(699, 1, 1, 'Screen Viewed', 'screen', '/office-rent', 'office-rent.index', 'GET', 'Office Rent', 64, NULL, NULL, 'Viewed Office Rent (/office-rent)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:18:41'),
(700, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 03:32:22'),
(701, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 10, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 03:32:32'),
(702, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 595, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:42:27'),
(703, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 27, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 03:42:54'),
(704, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 03:55:43'),
(705, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 03:59:13'),
(706, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 04:02:28'),
(707, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 04:02:37'),
(708, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 204, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:06:01'),
(709, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/employees/6/leave-quota', 'employees.leave-quota.store', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (casual_allowed, medical_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 12, \"medical_allowed\": 6}, \"old\": {\"casual_allowed\": 15, \"medical_allowed\": 10}}', '2026-11-26 04:06:33'),
(710, 1, 1, 'Created / Submitted', 'action', '/employees/6/leave-quota', 'employees.leave-quota.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/6/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 04:06:33'),
(711, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:06:34'),
(712, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 288, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:11:22'),
(713, 1, 1, 'Created EmployeeLeave', 'record', '/employees/6/leaves', 'employees.leaves.store', 'POST', 'Employee Leave', NULL, 'App\\Models\\EmployeeLeave', 4, 'Created EmployeeLeave #4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 4, \"days\": \"1\", \"status\": \"Approved\", \"to_date\": \"2026-11-26 00:00:00\", \"from_date\": \"2026-11-26 00:00:00\", \"leave_type\": \"Casual Leave\", \"employee_id\": 6}, \"old\": []}', '2026-11-26 04:13:51');
INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `category`, `screen`, `route_name`, `method`, `module`, `duration_seconds`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `metadata`, `created_at`) VALUES
(714, 1, 1, 'Created / Submitted', 'action', '/employees/6/leaves', 'employees.leaves.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/6/leaves)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 04:13:51'),
(715, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:13:52'),
(716, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 04:19:49'),
(717, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 17, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 04:20:06'),
(718, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 04:23:22'),
(719, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 04:23:31'),
(720, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 231, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:27:22'),
(721, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 04:34:19'),
(722, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 10, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 04:34:29'),
(723, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 89, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:35:58'),
(724, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/employees/6/leave-quota', 'employees.leave-quota.store', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (casual_allowed, medical_allowed)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"casual_allowed\": 15, \"medical_allowed\": 0}, \"old\": {\"casual_allowed\": 12, \"medical_allowed\": 6}}', '2026-11-26 04:36:30'),
(725, 1, 1, 'Created / Submitted', 'action', '/employees/6/leave-quota', 'employees.leave-quota.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/6/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 04:36:30'),
(726, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:36:31'),
(727, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 34, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:37:05'),
(728, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 04:53:40'),
(729, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 8, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 04:53:48'),
(730, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 158, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 04:56:26'),
(731, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 05:22:48'),
(732, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 11, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 05:22:59'),
(733, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 05:23:05'),
(734, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 05:23:06'),
(735, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 139, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:25:25'),
(736, 1, 1, 'Screen Viewed', 'screen', '/employees/3', 'employees.show', 'GET', 'Employees', 9, NULL, NULL, 'Viewed Employees (/employees/3)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:25:34'),
(737, 1, 1, 'Created EmployeeLeaveQuota', 'record', '/employees/3/leave-quota', 'employees.leave-quota.store', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Created EmployeeLeaveQuota #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 2, \"system_id\": 1, \"employee_id\": 3, \"taken_leave\": 0, \"allowed_leave\": 15, \"financial_period_id\": 1}, \"old\": []}', '2026-11-26 05:25:45'),
(738, 1, 1, 'Created / Submitted', 'action', '/employees/3/leave-quota', 'employees.leave-quota.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/3/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 05:25:45'),
(739, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 8, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:25:53'),
(740, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 18, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:26:11'),
(741, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 7, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:26:18'),
(742, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 05:30:06'),
(743, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 05:30:07'),
(744, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 187, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:33:14'),
(745, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 12, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:33:26'),
(746, 1, 1, 'Created EmployeeLeaveQuota', 'record', '/employees/6/leave-quota', 'employees.leave-quota.store', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 3, 'Created EmployeeLeaveQuota #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 3, \"system_id\": 1, \"employee_id\": 6, \"taken_leave\": 0, \"allowed_leave\": 15, \"financial_period_id\": 1}, \"old\": []}', '2026-11-26 05:33:39'),
(747, 1, 1, 'Created / Submitted', 'action', '/employees/6/leave-quota', 'employees.leave-quota.store', 'POST', 'Employees', NULL, NULL, NULL, 'Created / Submitted in Employees (/employees/6/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 05:33:39'),
(748, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 8, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:33:47'),
(749, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (taken_leave)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"taken_leave\": 6}, \"old\": {\"taken_leave\": 0}}', '2026-11-26 05:34:02'),
(750, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 05:34:02'),
(751, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:34:03'),
(752, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 388, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:40:31'),
(753, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 90, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:42:01'),
(754, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 05:50:58'),
(755, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 10, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 05:51:08'),
(756, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 46, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:51:54'),
(757, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 29, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 05:52:24'),
(758, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 06:01:52'),
(759, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 9, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 06:02:01'),
(760, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 06:02:02'),
(761, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 803, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:15:25'),
(762, 1, 1, 'Updated CompanyBankAccount', 'record', '/payroll/salary/2/pay', 'payroll.salary.pay', 'POST', 'Bank & Treasury', NULL, 'App\\Models\\CompanyBankAccount', 1, 'Updated CompanyBankAccount #1 (current_balance)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"current_balance\": 1844300}, \"old\": {\"current_balance\": \"1861100.00\"}}', '2026-11-26 06:15:51'),
(763, 1, 1, 'Created JournalVoucher', 'record', '/payroll/salary/2/pay', 'payroll.salary.pay', 'POST', 'Accounting Reports', NULL, 'App\\Models\\JournalVoucher', 29, 'Created JournalVoucher #29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 29, \"is_active\": true, \"narration\": \"Salary Payment to Athira L (EMP-1002) for Hindustan erp November 2026 from Tabasco Hindustan - Axis Bank 9752 (A/C 9752) to HDFC - A/C 6557657686 - IFSC hdfc56865\", \"voucher_no\": \"JV-SAL-2026-0002\", \"reference_id\": 2, \"voucher_date\": \"2026-11-26\", \"voucher_type_id\": 30}, \"old\": []}', '2026-11-26 06:15:51'),
(764, 1, 1, 'Updated PayrollSalary', 'record', '/payroll/salary/2/pay', 'payroll.salary.pay', 'POST', 'Payroll Salary', NULL, 'App\\Models\\PayrollSalary', 2, 'Updated PayrollSalary #2 (deductions, lop_amount, net_salary, payment_date)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"status\": \"Paid\", \"deductions\": 2000, \"lop_amount\": 2000, \"net_salary\": 16800, \"payment_date\": \"2026-11-26 00:00:00\", \"payment_reference\": \"4552343\", \"journal_voucher_id\": 29, \"company_bank_account_id\": 1}, \"old\": {\"status\": \"Pending\", \"deductions\": \"0.00\", \"lop_amount\": \"0.00\", \"net_salary\": \"18800.00\", \"payment_date\": null, \"payment_reference\": null, \"journal_voucher_id\": null, \"company_bank_account_id\": null}}', '2026-11-26 06:15:51'),
(765, 1, 1, 'Updated PayrollSalaryHead', 'record', '/payroll/salary/2/pay', 'payroll.salary.pay', 'POST', 'Payroll Salary Head', NULL, 'App\\Models\\PayrollSalaryHead', 1, 'Updated PayrollSalaryHead #1 (total_deductions, total_net)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"total_net\": 45700, \"total_deductions\": 3000}, \"old\": {\"total_net\": \"47700.00\", \"total_deductions\": \"1000.00\"}}', '2026-11-26 06:15:51'),
(766, 1, 1, 'Created / Submitted', 'action', '/payroll/salary/2/pay', 'payroll.salary.pay', 'POST', 'Payroll', 1, NULL, NULL, 'Created / Submitted in Payroll (/payroll/salary/2/pay)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 06:15:52'),
(767, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:15:52'),
(768, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 06:19:53'),
(769, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 11, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 06:20:04'),
(770, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 06:20:04'),
(771, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary', 'payroll.salary.index', 'GET', 'Payroll', 86, NULL, NULL, 'Viewed Payroll (/payroll/salary)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:21:30'),
(772, 1, 1, 'Screen Viewed', 'screen', '/payroll/salary-report', 'payroll.salary-report.index', 'GET', 'Payroll', 21, NULL, NULL, 'Viewed Payroll (/payroll/salary-report)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:21:51'),
(773, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 31, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:22:22'),
(774, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 146, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:24:48'),
(775, 1, 1, 'Screen Viewed', 'screen', '/employees', 'employees.index', 'GET', 'Employees', 489, NULL, NULL, 'Viewed Employees (/employees)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:32:57'),
(776, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 48, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:33:45'),
(777, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 32, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:34:17'),
(778, 1, 1, 'Screen Viewed', 'screen', '/petty-cash/daily-site-expenses', 'petty-cash.daily-site-expenses', 'GET', 'Petty Cash', 128, NULL, NULL, 'Viewed Petty Cash (/petty-cash/daily-site-expenses)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:36:25'),
(779, 1, 1, 'Screen Viewed', 'screen', '/categories', 'categories.index', 'GET', 'Categories', 27, NULL, NULL, 'Viewed Categories (/categories)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:36:52'),
(780, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 313, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:42:05'),
(781, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 06:48:49'),
(782, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 9, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 06:48:59'),
(783, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 240, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:52:59'),
(784, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (taken_leave)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"taken_leave\": 8}, \"old\": {\"taken_leave\": 6}}', '2026-11-26 06:53:32'),
(785, 1, 1, 'Created EmployeeLeave', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave', NULL, 'App\\Models\\EmployeeLeave', 6, 'Created EmployeeLeave #6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 6, \"days\": 2, \"reason\": \"Added via Leave Quotas\", \"status\": \"Approved\", \"to_date\": \"2027-10-01 00:00:00\", \"from_date\": \"2027-10-01 00:00:00\", \"leave_type\": \"Leave - October 2027\", \"employee_id\": 3}, \"old\": []}', '2026-11-26 06:53:32'),
(786, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 06:53:32'),
(787, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', NULL, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 06:53:32'),
(788, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 07:00:28'),
(789, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 9, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 07:00:37'),
(790, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 07:00:43'),
(791, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 2, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 07:00:45'),
(792, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 47, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 07:01:32'),
(793, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 3, 'Updated EmployeeLeaveQuota #3 (taken_leave)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"taken_leave\": 2}, \"old\": {\"taken_leave\": 0}}', '2026-11-26 07:02:13'),
(794, 1, 1, 'Created EmployeeLeave', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave', NULL, 'App\\Models\\EmployeeLeave', 8, 'Created EmployeeLeave #8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 8, \"days\": 2, \"reason\": \"Added via Leave Quotas\", \"status\": \"Approved\", \"to_date\": \"2027-10-01 00:00:00\", \"from_date\": \"2027-10-01 00:00:00\", \"leave_type\": \"Leave - October 2027\", \"employee_id\": 6}, \"old\": []}', '2026-11-26 07:02:13'),
(795, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 07:02:13'),
(796, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 07:02:14'),
(797, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 07:09:56'),
(798, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 8, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 07:10:04'),
(799, 1, 1, 'App opened', 'security', '/login', 'login', 'POST', 'Authentication', NULL, NULL, NULL, 'User logged in to Hindustan ERP', '127.0.0.1', 'Symfony', NULL, '2026-11-26 07:10:12'),
(800, 1, 1, 'Screen Viewed', 'screen', '/employees/6', 'employees.show', 'GET', 'Employees', 1, NULL, NULL, 'Viewed Employees (/employees/6)', '127.0.0.1', 'Symfony', '{\"status_code\": 200}', '2026-11-26 07:10:13'),
(801, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 47, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 07:11:00'),
(802, 1, 1, 'Updated EmployeeLeaveQuota', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave Quota', NULL, 'App\\Models\\EmployeeLeaveQuota', 2, 'Updated EmployeeLeaveQuota #2 (taken_leave)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"taken_leave\": 10}, \"old\": {\"taken_leave\": 8}}', '2026-11-26 07:11:33'),
(803, 1, 1, 'Created EmployeeLeave', 'record', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Employee Leave', NULL, 'App\\Models\\EmployeeLeave', 10, 'Created EmployeeLeave #10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"new\": {\"id\": 10, \"days\": 2, \"reason\": \"Added via Leave Quotas\", \"status\": \"Approved\", \"to_date\": \"2026-11-24 00:00:00\", \"from_date\": \"2026-11-24 00:00:00\", \"leave_type\": \"November 2026\", \"employee_id\": 3}, \"old\": []}', '2026-11-26 07:11:33'),
(804, 1, 1, 'Created / Submitted', 'action', '/payroll/leave-quota/save', 'payroll.leave-quota.save', 'POST', 'Payroll', NULL, NULL, NULL, 'Created / Submitted in Payroll (/payroll/leave-quota/save)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 302}', '2026-11-26 07:11:33'),
(805, 1, 1, 'Screen Viewed', 'screen', '/payroll/leave-quota', 'payroll.leave-quota.index', 'GET', 'Payroll', 1, NULL, NULL, 'Viewed Payroll (/payroll/leave-quota)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', '{\"status_code\": 200}', '2026-11-26 07:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approvals`
--

DROP TABLE IF EXISTS `hindustansystem_approvals`;
CREATE TABLE IF NOT EXISTS `hindustansystem_approvals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `approvable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvable_id` bigint UNSIGNED DEFAULT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
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
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `requester_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approval_rules`
--

DROP TABLE IF EXISTS `hindustansystem_approval_rules`;
CREATE TABLE IF NOT EXISTS `hindustansystem_approval_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_banks`
--

INSERT INTO `hindustansystem_banks` (`id`, `bank_name`, `ifsc_code`, `status`, `created_at`, `updated_at`) VALUES
(23, 'Federal', 'FB53767668676', 'active', '2026-09-09 03:24:47', '2026-09-09 03:24:47'),
(24, 'ICICI Bank', 'ICICI2333444', 'active', '2026-09-09 03:24:47', '2026-09-09 03:24:47'),
(25, 'SBI', 'SBIN0001234', 'active', '2026-09-09 03:24:47', '2026-09-09 03:24:47'),
(26, 'SIB', 'SIBL0000084', 'active', '2026-09-18 02:43:29', '2026-09-18 02:43:29');

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
  `bill_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_of_supply` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense_head` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_amount` decimal(15,2) NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(15,2) NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_approval',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_bills_system_id_bill_number_unique` (`system_id`,`bill_number`),
  KEY `hindustansystem_bills_payee_id_foreign` (`payee_id`),
  KEY `hindustansystem_bills_project_id_foreign` (`project_id`),
  KEY `hindustansystem_bills_approved_by_foreign` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bookings`
--

DROP TABLE IF EXISTS `hindustansystem_bookings`;
CREATE TABLE IF NOT EXISTS `hindustansystem_bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `sales_executive_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `broker_id` bigint UNSIGNED DEFAULT NULL,
  `sale_rate_per_sqft` decimal(15,2) DEFAULT NULL,
  `gst_behavior` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_bookings_booking_number_unique` (`booking_number`),
  KEY `hindustansystem_bookings_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_bookings_project_id_foreign` (`project_id`),
  KEY `hindustansystem_bookings_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_bookings_sales_executive_id_foreign` (`sales_executive_id`),
  KEY `hindustansystem_bookings_broker_id_foreign` (`broker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokerages`
--

INSERT INTO `hindustansystem_brokerages` (`id`, `sale_id`, `sale_unit_id`, `broker_id`, `commission_type`, `commission_percent`, `commission_amount`, `paid_amount`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 1, 'percentage', 2.00, 618180.00, 0.00, 'payable', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:24'),
(2, 3, NULL, 1, 'percentage', 2.00, 364140.00, 0.00, 'payable', NULL, '2026-11-16 02:54:25', '2026-11-16 04:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_brokers`
--

DROP TABLE IF EXISTS `hindustansystem_brokers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_brokers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_commission_pct` decimal(5,2) NOT NULL,
  `linked_account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_brokers_system_id_foreign` (`system_id`),
  KEY `hindustansystem_brokers_linked_account_id_foreign` (`linked_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokers`
--

INSERT INTO `hindustansystem_brokers` (`id`, `system_id`, `name`, `default_commission_pct`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Noushad', 2.00, 52, '2026-11-10 05:49:52', '2026-11-10 05:49:52'),
(2, 1, 'test', 2.00, 53, '2026-11-20 03:41:31', '2026-11-20 03:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache`
--

DROP TABLE IF EXISTS `hindustansystem_cache`;
CREATE TABLE IF NOT EXISTS `hindustansystem_cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `hindustansystem_cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache_locks`
--

DROP TABLE IF EXISTS `hindustansystem_cache_locks`;
CREATE TABLE IF NOT EXISTS `hindustansystem_cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `hindustansystem_cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_categories`
--

DROP TABLE IF EXISTS `hindustansystem_categories`;
CREATE TABLE IF NOT EXISTS `hindustansystem_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_of_account_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_hindustansystem_categories_project_id` (`project_id`),
  KEY `categories_chart_of_account_id_foreign` (`chart_of_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_categories`
--

INSERT INTO `hindustansystem_categories` (`id`, `category`, `chart_of_account_id`, `project_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Office Expense', NULL, 1, 'active', '2026-09-18 03:19:01', '2026-09-18 03:19:01'),
(2, 'News Paper Expense', NULL, 1, 'active', '2026-09-18 03:20:00', '2026-09-18 03:20:00'),
(3, 'Cleaning Charge', NULL, 1, 'active', '2026-09-20 19:30:12', '2026-09-20 19:30:12'),
(4, 'Water Charge', NULL, 1, 'active', '2026-09-20 19:30:32', '2026-09-20 19:30:32'),
(5, 'Printing & Stationary', NULL, 1, 'active', '2026-09-20 19:30:48', '2026-09-20 19:30:48'),
(6, 'Telephone Expense', NULL, 1, 'active', '2026-09-20 19:31:05', '2026-09-20 19:31:05'),
(7, 'Donation', NULL, 1, 'active', '2026-09-20 19:31:22', '2026-09-20 19:31:22'),
(8, 'Site Expense', NULL, 1, 'active', '2026-09-20 19:31:43', '2026-09-20 19:31:43'),
(9, 'Travelling Expense', NULL, 1, 'active', '2026-09-20 19:31:58', '2026-09-20 19:31:58'),
(10, 'Electricity Charge', NULL, 1, 'active', '2026-09-20 19:32:17', '2026-09-20 19:32:17'),
(13, 'Advertisement Expense', NULL, 1, 'active', '2026-09-20 19:33:53', '2026-09-20 19:33:53'),
(14, 'Repair & Maintanance', NULL, 1, 'active', '2026-09-20 19:34:09', '2026-09-20 19:34:09'),
(15, 'Bonus paid', NULL, 1, 'active', '2026-09-20 19:35:01', '2026-09-20 19:35:01'),
(16, 'Security charge', NULL, 1, 'active', '2026-09-20 19:35:19', '2026-09-20 19:36:32'),
(17, 'PAYMENT', NULL, 1, 'active', '2026-10-02 02:13:08', '2026-10-02 02:13:08'),
(18, 'Land Purchase', NULL, 1, 'active', '2026-11-09 07:18:51', '2026-11-09 07:18:51'),
(19, 'Printing and Stationery', NULL, 1, 'active', '2026-11-09 07:18:51', '2026-11-09 07:18:51'),
(20, 'Project - Rates & Taxes', NULL, 1, 'active', '2026-11-09 07:18:51', '2026-11-09 07:18:51'),
(21, 'Advertisement', NULL, 1, 'active', '2026-11-09 07:18:52', '2026-11-09 07:18:52'),
(22, 'General Expenses', NULL, 1, 'active', '2026-11-09 07:18:52', '2026-11-09 07:18:52'),
(23, 'Aircondition', NULL, 1, 'active', '2026-11-09 07:18:53', '2026-11-09 07:18:53'),
(24, 'Electrical Fittings', NULL, 1, 'active', '2026-11-09 07:18:54', '2026-11-09 07:18:54'),
(25, 'Repairs and Maintenance', NULL, 1, 'active', '2026-11-09 07:18:54', '2026-11-09 07:18:54'),
(26, 'Office Expenses', NULL, 1, 'active', '2026-11-09 07:18:55', '2026-11-09 07:18:55'),
(27, 'Office Inauguration Expenses', NULL, 1, 'active', '2026-11-09 07:18:55', '2026-11-09 07:18:55'),
(28, 'Furniture', NULL, 1, 'active', '2026-11-09 07:18:56', '2026-11-09 07:18:56'),
(29, 'Travelling Expenses', NULL, 1, 'active', '2026-11-09 07:18:58', '2026-11-09 07:18:58'),
(30, 'Rent Paid', NULL, 1, 'active', '2026-11-09 07:18:59', '2026-11-09 07:18:59'),
(31, 'Business Promotion', NULL, 1, 'active', '2026-11-09 07:18:59', '2026-11-09 07:18:59'),
(32, 'Telephone Charges', NULL, 1, 'active', '2026-11-09 07:19:00', '2026-11-09 07:19:00'),
(33, 'News Paper & Periodicals', NULL, 1, 'active', '2026-11-09 07:19:01', '2026-11-09 07:19:01'),
(34, 'Electricity Expenses', NULL, 1, 'active', '2026-11-09 07:19:03', '2026-11-09 07:19:03'),
(35, 'Security Charges', NULL, 1, 'active', '2026-11-09 07:19:04', '2026-11-09 07:19:04'),
(36, 'Salary', NULL, 1, 'active', '2026-11-09 07:19:05', '2026-11-09 07:19:05'),
(37, 'Basheer Madathil', NULL, 1, 'active', '2026-11-09 07:19:14', '2026-11-09 07:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_chart_of_accounts`
--

DROP TABLE IF EXISTS `hindustansystem_chart_of_accounts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_chart_of_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('ASSET','LIABILITY','EQUITY','REVENUE','EXPENSE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opening_balance_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DR',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  KEY `hindustansystem_chart_of_accounts_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_chart_of_accounts`
--

INSERT INTO `hindustansystem_chart_of_accounts` (`id`, `account_code`, `account_name`, `account_type`, `opening_balance`, `opening_balance_type`, `remarks`, `project_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1010', 'Customer Receivable', 'ASSET', 0.00, 'DR', NULL, NULL, 1, '2026-09-25 05:40:29', '2026-09-25 05:40:29'),
(2, '2021', 'Output CGST Payable (2.5%)', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-09-25 05:40:29', '2026-09-25 05:40:29'),
(3, '2022', 'Output SGST Payable (2.5%)', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-09-25 05:40:29', '2026-09-25 05:40:29'),
(4, '1001', 'Bank Balances', 'ASSET', 0.00, 'DR', NULL, NULL, 1, '2026-09-25 05:44:37', '2026-09-25 05:44:37'),
(5, '2201', 'Bank Construction Loans / Project Credit Limits', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-09-25 05:44:37', '2026-09-25 05:44:37'),
(7, '2003', 'Agent Payable Liability', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-09-27 23:43:57', '2026-09-27 23:43:57'),
(8, '4002', 'Contractor Work Expenses', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 00:23:17', '2026-09-28 00:23:17'),
(9, '2002', 'Contractor Payable', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 00:23:17', '2026-09-28 00:23:17'),
(10, '4005', 'Construction Material Expenses', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 01:15:24', '2026-09-28 01:15:24'),
(11, '4003', 'Site Office & Administrative', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 01:35:27', '2026-09-28 01:35:27'),
(12, '4004', 'Machinery & Heavy Equipment Rental', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 01:36:13', '2026-09-28 01:36:13'),
(13, '1002', 'Petty Cash Box', 'ASSET', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 02:14:38', '2026-09-28 02:14:38'),
(16, '3001', 'Share Capital', 'EQUITY', 0.00, 'DR', NULL, NULL, 1, '2026-09-28 05:39:16', '2026-09-28 05:39:16'),
(30, '4001', 'Agent Payable Expense', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-09-29 04:14:43', '2026-09-29 04:14:43'),
(31, '2202', 'Bank Loan Interest', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-10-01 07:48:05', '2026-10-01 07:48:05'),
(32, '2001', 'Sundry Creditors (Material & Service Vendors)', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-10-21 00:59:07', '2026-10-21 00:59:07'),
(35, '2011', 'Salary Payable', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-11-25 00:52:15', '2026-11-25 00:52:15'),
(39, '4006', 'Office Rent Expense', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(40, '2012', 'Office Rent Payable', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(41, '2023', 'TDS Payable (Sec 194I)', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(44, '2010', 'Provisions & Outstanding Payables', 'LIABILITY', 0.00, 'DR', NULL, NULL, 1, '2026-11-25 03:43:18', '2026-11-25 03:43:18'),
(45, '4106', 'Office Rent & Maintenance', 'EXPENSE', 0.00, 'DR', NULL, NULL, 1, '2026-11-25 03:43:18', '2026-11-25 03:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cheque_statuses`
--

DROP TABLE IF EXISTS `hindustansystem_cheque_statuses`;
CREATE TABLE IF NOT EXISTS `hindustansystem_cheque_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cheque_statuses_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_cheque_statuses`
--

INSERT INTO `hindustansystem_cheque_statuses` (`id`, `name`, `color_code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pending', 'amber-500', 1, NULL, NULL),
(2, 'Realized', 'emerald-500', 1, NULL, NULL),
(3, 'Bounced', 'rose-500', 1, NULL, NULL),
(4, 'Cancelled', 'slate-500', 1, NULL, NULL),
(5, 'Deposited', 'Blue', 1, '2026-08-14 03:23:32', '2026-08-14 03:23:32'),
(6, 'Cheque in Hand', 'Orange', 1, '2026-08-16 19:53:55', '2026-08-16 19:53:55'),
(7, 'In Clearing', 'Purple', 1, '2026-08-16 19:55:21', '2026-08-16 19:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_collection_reminders`
--

DROP TABLE IF EXISTS `hindustansystem_collection_reminders`;
CREATE TABLE IF NOT EXISTS `hindustansystem_collection_reminders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `installment_id` bigint UNSIGNED NOT NULL,
  `reminder_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `response` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_reminders_customer_id_foreign` (`customer_id`),
  KEY `collection_reminders_sale_id_foreign` (`sale_id`),
  KEY `collection_reminders_installment_id_foreign` (`installment_id`)
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
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Accrued',
  `triggered_at` timestamp NULL DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_commission_entries_system_id_foreign` (`system_id`),
  KEY `hindustansystem_commission_entries_voucher_id_foreign` (`voucher_id`),
  KEY `ce_deal_fk` (`deal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_company_bank_accounts`
--

DROP TABLE IF EXISTS `hindustansystem_company_bank_accounts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_company_bank_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_type` enum('Current','Savings','Overdraft','Escrow','CC') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Current',
  `ifsc_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `micr_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `upi_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_company_bank_accounts`
--

INSERT INTO `hindustansystem_company_bank_accounts` (`id`, `bank_name`, `account_name`, `account_number`, `account_type`, `ifsc_code`, `branch_name`, `swift_code`, `micr_code`, `opening_balance`, `current_balance`, `upi_id`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Axis Bank 9752', 'Tabasco Hindustan', '9752', 'Current', '1234', NULL, NULL, NULL, 0.00, 1844300.00, NULL, 'active', 0, '2026-08-27 07:34:26', '2026-11-26 06:15:51'),
(2, 'Canara Bank 260', NULL, '260', 'Current', '1234', NULL, NULL, NULL, 0.00, 0.00, NULL, 'active', 0, '2026-08-27 07:34:56', '2026-10-05 06:13:42'),
(3, 'Karnataka Bank 213', NULL, '213', 'Current', '1234', NULL, NULL, NULL, 0.00, 0.00, NULL, 'active', 0, '2026-08-27 07:36:30', '2026-09-28 00:40:27'),
(4, 'Karnataka Bank 14801', NULL, NULL, 'Current', '1234', NULL, NULL, NULL, 0.00, 0.00, NULL, 'active', 0, '2026-08-27 07:37:16', '2026-11-08 23:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customers`
--

DROP TABLE IF EXISTS `hindustansystem_customers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `id_proof_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system` enum('india','uae') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'india',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customers`
--

INSERT INTO `hindustansystem_customers` (`id`, `name`, `email`, `phone`, `avatar_url`, `created_at`, `updated_at`, `address`, `id_proof_type`, `id_proof_number`, `system`, `is_active`) VALUES
(1, 'Anchu Mol', 'anchumol366@gmail.com', '67686', NULL, '2026-11-10 01:10:38', '2026-11-10 01:10:38', NULL, NULL, NULL, 'india', 1),
(2, 'Athira', 'athira@gmail.com', '6897695', NULL, '2026-11-10 05:49:27', '2026-11-10 05:49:27', NULL, NULL, NULL, 'india', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customer_installments`
--

INSERT INTO `hindustansystem_customer_installments` (`id`, `sale_id`, `installment_no`, `label`, `due_date`, `amount`, `paid_amount`, `status`, `schedule_type`, `rescheduled_from_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'Down Payment', '2026-11-10', 1500000.00, 1500000.00, 'paid', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(2, 1, 1, 'EMI 1', '2026-12-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(3, 1, 2, 'EMI 2', '2027-01-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(4, 1, 3, 'EMI 3', '2027-02-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(5, 1, 4, 'EMI 4', '2027-03-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(6, 1, 5, 'EMI 5', '2027-04-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(7, 1, 6, 'EMI 6', '2027-05-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(8, 1, 7, 'EMI 7', '2027-06-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(9, 1, 8, 'EMI 8', '2027-07-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(10, 1, 9, 'EMI 9', '2027-08-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(11, 1, 10, 'EMI 10', '2027-09-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(12, 1, 11, 'EMI 11', '2027-10-10', 533958.33, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(13, 1, 12, 'EMI 12', '2027-11-10', 533958.37, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(14, 2, 0, 'Down Payment', '2026-11-10', 1000000.00, 1000000.00, 'paid', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(15, 2, 1, 'EMI 1', '2026-12-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(16, 2, 2, 'EMI 2', '2027-01-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(17, 2, 3, 'EMI 3', '2027-02-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(18, 2, 4, 'EMI 4', '2027-03-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(19, 2, 5, 'EMI 5', '2027-04-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(20, 2, 6, 'EMI 6', '2027-05-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(21, 2, 7, 'EMI 7', '2027-06-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(22, 2, 8, 'EMI 8', '2027-07-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(23, 2, 9, 'EMI 9', '2027-08-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(24, 2, 10, 'EMI 10', '2027-09-10', 3043700.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(25, 3, 0, 'Down Payment', '2026-11-16', 1500000.00, 1500000.00, 'paid', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(26, 3, 1, 'EMI 1', '2026-12-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(27, 3, 2, 'EMI 2', '2027-01-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(28, 3, 3, 'EMI 3', '2027-02-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(29, 3, 4, 'EMI 4', '2027-03-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(30, 3, 5, 'EMI 5', '2027-04-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(31, 3, 6, 'EMI 6', '2027-05-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(32, 3, 7, 'EMI 7', '2027-06-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:26', '2026-11-16 02:54:26'),
(33, 3, 8, 'EMI 8', '2027-07-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:26', '2026-11-16 02:54:26'),
(34, 3, 9, 'EMI 9', '2027-08-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:26', '2026-11-16 02:54:26'),
(35, 3, 10, 'EMI 10', '2027-09-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:26', '2026-11-16 02:54:26'),
(36, 3, 11, 'EMI 11', '2027-10-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:26', '2026-11-16 02:54:26'),
(37, 3, 12, 'EMI 12', '2027-11-16', 1468112.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 02:54:26', '2026-11-16 02:54:26'),
(38, 4, 0, 'Down Payment', '2026-11-16', 1000000.00, 1000000.00, 'paid', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(39, 4, 1, 'EMI 1', '2026-12-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(40, 4, 2, 'EMI 2', '2027-01-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(41, 4, 3, 'EMI 3', '2027-02-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(42, 4, 4, 'EMI 4', '2027-03-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(43, 4, 5, 'EMI 5', '2027-04-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(44, 4, 6, 'EMI 6', '2027-05-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(45, 4, 7, 'EMI 7', '2027-06-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(46, 4, 8, 'EMI 8', '2027-07-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(47, 4, 9, 'EMI 9', '2027-08-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(48, 4, 10, 'EMI 10', '2027-09-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(49, 4, 11, 'EMI 11', '2027-10-16', 1539266.67, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(50, 4, 12, 'EMI 12', '2027-11-16', 1539266.63, 0.00, 'pending', 'fixed_emi', NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_data_clear_logs`
--

DROP TABLE IF EXISTS `hindustansystem_data_clear_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_data_clear_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cleared_by` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tables_cleared_count` int NOT NULL DEFAULT '0',
  `tables_cleared` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SUCCESS',
  `cleared_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_data_clear_logs`
--

INSERT INTO `hindustansystem_data_clear_logs` (`id`, `cleared_by`, `ip_address`, `tables_cleared_count`, `tables_cleared`, `status`, `cleared_at`, `created_at`) VALUES
(1, 'Owner (owner@hindustan.com)', '127.0.0.1', 48, 'hindustansystem_collection_reminders, hindustansystem_emi_reschedule_logs, hindustansystem_customer_installments, hindustansystem_sale_status_logs, hindustansystem_sale_extra_works, hindustansystem_sale_units, hindustansystem_sales, hindustansystem_payments, hindustansystem_bookings, hindustansystem_customers, hindustansystem_sales_executives, hindustansystem_receipt_realization_logs, hindustansystem_receipt_stores, hindustansystem_receipts, hindustansystem_commission_entries, hindustansystem_deals, hindustansystem_brokerages, hindustansystem_brokers, hindustansystem_ra_bill_payments, hindustansystem_ra_bills, hindustansystem_site_expense_payments, hindustansystem_site_expenses, hindustansystem_bill_payments, hindustansystem_bills, hindustansystem_vendors, hindustansystem_petty_cash_transactions, hindustansystem_petty_cash_boxes, hindustansystem_replenishment_requests, hindustansystem_petty_cash_entries, hindustansystem_petty_cash_accounts, hindustansystem_loan_prepayments, hindustansystem_loan_disbursals, hindustansystem_loan_interest_logs, hindustansystem_emi_schedules, hindustansystem_loans, hindustansystem_partner_allocations, hindustansystem_partner_contributions, hindustansystem_ledger_entries, hindustansystem_voucher_lines, hindustansystem_vouchers, hindustansystem_journal_entries, hindustansystem_journal_vouchers, hindustansystem_dms_documents, hindustansystem_approvals, hindustansystem_approval_requests, hindustansystem_unit_rate_logs, hindustansystem_unit_status_logs, hindustansystem_activity_logs', 'SUCCESS', '2026-11-02 16:35:13', '2026-11-02 16:35:13'),
(2, 'Owner (owner@hindustan.com)', '127.0.0.1', 48, 'hindustansystem_collection_reminders, hindustansystem_emi_reschedule_logs, hindustansystem_customer_installments, hindustansystem_sale_status_logs, hindustansystem_sale_extra_works, hindustansystem_sale_units, hindustansystem_sales, hindustansystem_payments, hindustansystem_bookings, hindustansystem_customers, hindustansystem_sales_executives, hindustansystem_receipt_realization_logs, hindustansystem_receipt_stores, hindustansystem_receipts, hindustansystem_commission_entries, hindustansystem_deals, hindustansystem_brokerages, hindustansystem_brokers, hindustansystem_ra_bill_payments, hindustansystem_ra_bills, hindustansystem_site_expense_payments, hindustansystem_site_expenses, hindustansystem_bill_payments, hindustansystem_bills, hindustansystem_vendors, hindustansystem_petty_cash_transactions, hindustansystem_petty_cash_boxes, hindustansystem_replenishment_requests, hindustansystem_petty_cash_entries, hindustansystem_petty_cash_accounts, hindustansystem_loan_prepayments, hindustansystem_loan_disbursals, hindustansystem_loan_interest_logs, hindustansystem_emi_schedules, hindustansystem_loans, hindustansystem_partner_allocations, hindustansystem_partner_contributions, hindustansystem_ledger_entries, hindustansystem_voucher_lines, hindustansystem_vouchers, hindustansystem_journal_entries, hindustansystem_journal_vouchers, hindustansystem_dms_documents, hindustansystem_approvals, hindustansystem_approval_requests, hindustansystem_unit_rate_logs, hindustansystem_unit_status_logs, hindustansystem_activity_logs', 'SUCCESS', '2026-11-02 16:43:13', '2026-11-02 16:43:13'),
(3, 'Owner (owner@hindustan.com)', '127.0.0.1', 48, 'hindustansystem_collection_reminders, hindustansystem_emi_reschedule_logs, hindustansystem_customer_installments, hindustansystem_sale_status_logs, hindustansystem_sale_extra_works, hindustansystem_sale_units, hindustansystem_sales, hindustansystem_payments, hindustansystem_bookings, hindustansystem_customers, hindustansystem_sales_executives, hindustansystem_receipt_realization_logs, hindustansystem_receipt_stores, hindustansystem_receipts, hindustansystem_commission_entries, hindustansystem_deals, hindustansystem_brokerages, hindustansystem_brokers, hindustansystem_ra_bill_payments, hindustansystem_ra_bills, hindustansystem_site_expense_payments, hindustansystem_site_expenses, hindustansystem_bill_payments, hindustansystem_bills, hindustansystem_vendors, hindustansystem_petty_cash_transactions, hindustansystem_petty_cash_boxes, hindustansystem_replenishment_requests, hindustansystem_petty_cash_entries, hindustansystem_petty_cash_accounts, hindustansystem_loan_prepayments, hindustansystem_loan_disbursals, hindustansystem_loan_interest_logs, hindustansystem_emi_schedules, hindustansystem_loans, hindustansystem_partner_allocations, hindustansystem_partner_contributions, hindustansystem_ledger_entries, hindustansystem_voucher_lines, hindustansystem_vouchers, hindustansystem_journal_entries, hindustansystem_journal_vouchers, hindustansystem_dms_documents, hindustansystem_approvals, hindustansystem_approval_requests, hindustansystem_unit_rate_logs, hindustansystem_unit_status_logs, hindustansystem_activity_logs', 'SUCCESS', '2026-11-05 17:08:19', '2026-11-05 17:08:19'),
(4, 'Owner (owner@hindustan.com)', '127.0.0.1', 48, 'hindustansystem_collection_reminders, hindustansystem_emi_reschedule_logs, hindustansystem_customer_installments, hindustansystem_sale_status_logs, hindustansystem_sale_extra_works, hindustansystem_sale_units, hindustansystem_sales, hindustansystem_payments, hindustansystem_bookings, hindustansystem_customers, hindustansystem_sales_executives, hindustansystem_receipt_realization_logs, hindustansystem_receipt_stores, hindustansystem_receipts, hindustansystem_commission_entries, hindustansystem_deals, hindustansystem_brokerages, hindustansystem_brokers, hindustansystem_ra_bill_payments, hindustansystem_ra_bills, hindustansystem_site_expense_payments, hindustansystem_site_expenses, hindustansystem_bill_payments, hindustansystem_bills, hindustansystem_vendors, hindustansystem_petty_cash_transactions, hindustansystem_petty_cash_boxes, hindustansystem_replenishment_requests, hindustansystem_petty_cash_entries, hindustansystem_petty_cash_accounts, hindustansystem_loan_prepayments, hindustansystem_loan_disbursals, hindustansystem_loan_interest_logs, hindustansystem_emi_schedules, hindustansystem_loans, hindustansystem_partner_allocations, hindustansystem_partner_contributions, hindustansystem_ledger_entries, hindustansystem_voucher_lines, hindustansystem_vouchers, hindustansystem_journal_entries, hindustansystem_journal_vouchers, hindustansystem_dms_documents, hindustansystem_approvals, hindustansystem_approval_requests, hindustansystem_unit_rate_logs, hindustansystem_unit_status_logs, hindustansystem_activity_logs', 'SUCCESS', '2026-11-10 12:09:55', '2026-11-10 12:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_data_clear_passwords`
--

DROP TABLE IF EXISTS `hindustansystem_data_clear_passwords`;
CREATE TABLE IF NOT EXISTS `hindustansystem_data_clear_passwords` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `password_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Master password to clear transactional data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_data_clear_passwords`
--

INSERT INTO `hindustansystem_data_clear_passwords` (`id`, `password_key`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin@123', 'Default Data Clear Password (editable in DB table hindustansystem_data_clear_passwords)', 1, '2026-11-02 15:31:19', '2026-11-02 15:31:19');

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
  `trigger_condition` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_collection',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_deals_system_id_foreign` (`system_id`),
  KEY `hindustansystem_deals_broker_id_foreign` (`broker_id`),
  KEY `hindustansystem_deals_project_id_foreign` (`project_id`),
  KEY `hindustansystem_deals_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_dms_categories`
--

DROP TABLE IF EXISTS `hindustansystem_dms_categories`;
CREATE TABLE IF NOT EXISTS `hindustansystem_dms_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dms_categories_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_dms_categories`
--

INSERT INTO `hindustansystem_dms_categories` (`id`, `name`, `code`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Project Documents', 'project', 'office-building', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33'),
(2, 'Legal Documents', 'legal', 'scale', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33'),
(3, 'Company Documents', 'company', 'briefcase', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33'),
(4, 'HR & Employee Documents', 'hr', 'users', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33'),
(5, 'Partner & Investor Documents', 'partner', 'handshake', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33'),
(6, 'Drawings & Technical Documents', 'drawings', 'photograph', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33'),
(7, 'Templates & Standard Documents', 'templates', 'document-duplicate', 1, '2026-09-01 09:33:33', '2026-09-01 09:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_dms_documents`
--

DROP TABLE IF EXISTS `hindustansystem_dms_documents`;
CREATE TABLE IF NOT EXISTS `hindustansystem_dms_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `documentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentable_id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revision_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drawing_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tower` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_project_id` bigint UNSIGNED DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_dms_documents_system_id_index` (`system_id`),
  KEY `dms_docs_morph_idx` (`documentable_type`,`documentable_id`),
  KEY `hindustansystem_dms_documents_category_document_type_index` (`category`,`document_type`),
  KEY `dms_documents_uploaded_by_foreign` (`uploaded_by`),
  KEY `dms_documents_reference_project_id_index` (`reference_project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_dms_document_types`
--

DROP TABLE IF EXISTS `hindustansystem_dms_document_types`;
CREATE TABLE IF NOT EXISTS `hindustansystem_dms_document_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dms_category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dms_document_types_category_id_index` (`dms_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_dms_document_types`
--

INSERT INTO `hindustansystem_dms_document_types` (`id`, `dms_category_id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Approved Building Plan', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(2, 1, 'Municipality Approval', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(3, 1, 'RERA Certificate', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(4, 1, 'Fire NOC', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(5, 1, 'Environmental Clearance', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(6, 1, 'Occupancy Certificate', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(7, 1, 'Completion Certificate', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(8, 1, 'Site Layout Drawing', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(9, 1, 'Master Plan', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(10, 1, 'Other', 1, '2026-09-01 09:33:56', '2026-09-01 09:33:56'),
(11, 1, 'Approved Building Plan', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(12, 1, 'Municipality Approval', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(13, 1, 'RERA Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(14, 1, 'Fire NOC', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(15, 1, 'Environmental Clearance', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(16, 1, 'Occupancy Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(17, 1, 'Completion Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(18, 1, 'Site Layout Drawing', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(19, 1, 'Master Plan', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(20, 1, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(26, 2, 'Land Deed', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(27, 2, 'Encumbrance Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(28, 2, 'Power of Attorney', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(29, 2, 'Partnership Agreement', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(30, 2, 'Legal Opinion', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(31, 2, 'Court Document', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(32, 2, 'Government Approval', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(33, 2, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(41, 3, 'GST Registration', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(42, 3, 'PAN Card', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(43, 3, 'Trade License', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(44, 3, 'Company Incorporation Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(45, 3, 'ISO Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(46, 3, 'Insurance Policy', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(47, 3, 'Audit Report', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(48, 3, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(56, 4, 'Appointment Letter', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(57, 4, 'Employment Contract', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(58, 4, 'NDA Agreement', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(59, 4, 'Policy Document', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(60, 4, 'Employee ID Proof', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(61, 4, 'Experience Certificate', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(62, 4, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(63, 5, 'Investment Agreement', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(64, 5, 'MOU Document', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(65, 5, 'Share Allocation Document', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(66, 5, 'Settlement Agreement', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(67, 5, 'Board Resolution', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(68, 5, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(70, 6, 'Architectural Drawing', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(71, 6, 'Structural Drawing', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(72, 6, 'Electrical Layout', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(73, 6, 'Plumbing Layout', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(74, 6, 'BOQ Document', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(75, 6, 'Engineering Report', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(76, 6, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(77, 7, 'Sale Agreement Template', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(78, 7, 'Possession Letter Template', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(79, 7, 'Cancellation Letter Template', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(80, 7, 'Demand Letter Template', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(81, 7, 'Payment Reminder Template', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24'),
(82, 7, 'Other', 1, '2026-09-01 09:35:24', '2026-09-01 09:35:24');

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
  `old_schedule_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `new_schedule_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_emi_reschedule_logs_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_emi_reschedule_logs_performed_by_foreign` (`performed_by`)
) ;

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
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Due',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_emi_schedules_system_id_foreign` (`system_id`),
  KEY `es_loan_fk` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employees`
--

DROP TABLE IF EXISTS `hindustansystem_employees`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nationality` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_structure` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reports_to` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Full-Time Permanent',
  `shift` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'General Day Shift',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `portal_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date NOT NULL,
  `confirmation_date` date DEFAULT NULL,
  `probation_end_date` date DEFAULT NULL,
  `notice_period` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_location` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `basic_salary` decimal(12,2) DEFAULT '0.00',
  `hra` decimal(12,2) DEFAULT '0.00',
  `allowances` decimal(12,2) DEFAULT '0.00',
  `deductions` decimal(12,2) DEFAULT '0.00',
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uan_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'India',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_employees_employee_id_unique` (`employee_id`),
  KEY `hindustansystem_employees_system_id_foreign` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_employees`
--

INSERT INTO `hindustansystem_employees` (`id`, `system_id`, `user_id`, `employee_id`, `name`, `first_name`, `last_name`, `gender`, `date_of_birth`, `nationality`, `blood_group`, `designation`, `salary_structure`, `department`, `branch`, `reports_to`, `employment_type`, `shift`, `phone`, `alternate_phone`, `address_line_1`, `address_line_2`, `country`, `state`, `city`, `postal_code`, `email`, `personal_email`, `work_email`, `portal_email`, `joining_date`, `confirmation_date`, `probation_end_date`, `notice_period`, `work_location`, `photo_path`, `salary`, `basic_salary`, `hra`, `allowances`, `deductions`, `bank_name`, `bank_account_number`, `ifsc_code`, `pan_number`, `uan_number`, `bank_country`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 1, NULL, 'EMP-1001', 'Anchumol S', 'Anchumol', 'S', 'Female', '1994-07-26', 'Indian', 'A-ve', 'Software engineer', NULL, 'virtual sys', 'Head Office', NULL, 'Full-Time Permanent', 'General Day Shift', '7034086180', NULL, 'Kochuparambil', NULL, 'India', 'Kerala', 'Azheekal', '690547', 'anchumol@virtualsysglobal.com', 'anchumol366@gmail.com', 'anchumol@virtualsysglobal.com', 'anchumol@virtualsysglobal.com', '2026-11-23', NULL, NULL, NULL, 'Cherthala, Kerala', NULL, 15800.00, 9600.00, 3500.00, 16800.00, 1000.00, 'SBI', '12546576576', 'sbin006458', '64576576', NULL, 'India', 'active', '2026-11-23 00:39:20', '2026-11-24 01:59:51', NULL),
(6, 1, NULL, 'EMP-1002', 'Athira L', 'Athira', 'L', 'Female', '2026-11-26', 'Indian', 'A-ve', 'Software engineer', NULL, 'virtual sys', 'Head Office', NULL, 'Full-Time Permanent', 'General Day Shift', '+91 98765 00001', NULL, 'test', NULL, 'India', NULL, 'cherthala', NULL, 'athira@virtualsys.com', 'athira@gmail.com', 'athira@virtualsys.com', 'athira@virtualsys.com', '2026-11-24', NULL, NULL, NULL, 'Cherthala, Kerala', NULL, 18800.00, 0.00, 0.00, 18800.00, 0.00, 'HDFC', '6557657686', 'hdfc56865', 'ytu76yuhgjhgj', NULL, 'India', 'active', '2026-11-24 03:39:05', '2026-11-25 00:53:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_assets`
--

DROP TABLE IF EXISTS `hindustansystem_employee_assets`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_assets` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `asset_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_code` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Assigned',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_assets_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_attendances`
--

DROP TABLE IF EXISTS `hindustansystem_employee_attendances`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Present',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_attendances_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_documents`
--

DROP TABLE IF EXISTS `hindustansystem_employee_documents`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_documents_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_emergency_contacts`
--

DROP TABLE IF EXISTS `hindustansystem_employee_emergency_contacts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_emergency_contacts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_emergency_contacts_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_experiences`
--

DROP TABLE IF EXISTS `hindustansystem_employee_experiences`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_experiences` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_experiences_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_leaves`
--

DROP TABLE IF EXISTS `hindustansystem_employee_leaves`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_leaves` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `days` decimal(5,1) NOT NULL DEFAULT '1.0',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_leaves_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_employee_leaves`
--

INSERT INTO `hindustansystem_employee_leaves` (`id`, `employee_id`, `leave_type`, `from_date`, `to_date`, `days`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(4, 6, 'Casual Leave', '2026-11-26', '2026-11-26', 1.0, NULL, 'Approved', '2026-11-26 04:13:51', '2026-11-26 04:13:51'),
(6, 3, 'Leave - October 2027', '2027-10-01', '2027-10-01', 2.0, 'Added via Leave Quotas', 'Approved', '2026-11-26 06:53:32', '2026-11-26 06:53:32'),
(8, 6, 'Leave - October 2027', '2027-10-01', '2027-10-01', 2.0, 'Added via Leave Quotas', 'Approved', '2026-11-26 07:02:13', '2026-11-26 07:02:13'),
(10, 3, 'November 2026', '2026-11-24', '2026-11-24', 2.0, 'Added via Leave Quotas', 'Approved', '2026-11-26 07:11:33', '2026-11-26 07:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_leave_quotas`
--

DROP TABLE IF EXISTS `hindustansystem_employee_leave_quotas`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_leave_quotas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `financial_period_id` bigint UNSIGNED DEFAULT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `allowed_leave` decimal(5,1) NOT NULL DEFAULT '0.0',
  `taken_leave` decimal(5,1) NOT NULL DEFAULT '0.0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `elq_fp_emp_unique` (`financial_period_id`,`employee_id`),
  KEY `elq_sys_idx` (`system_id`),
  KEY `elq_fp_idx` (`financial_period_id`),
  KEY `hindustansystem_employee_leave_quotas_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_employee_leave_quotas`
--

INSERT INTO `hindustansystem_employee_leave_quotas` (`id`, `system_id`, `financial_period_id`, `employee_id`, `allowed_leave`, `taken_leave`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 3, 15.0, 10.0, '2026-11-26 05:25:45', '2026-11-26 07:11:33'),
(3, 1, 1, 6, 15.0, 2.0, '2026-11-26 05:33:39', '2026-11-26 07:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_overtimes`
--

DROP TABLE IF EXISTS `hindustansystem_employee_overtimes`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_overtimes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `overtime_date` date NOT NULL,
  `hours` decimal(6,2) NOT NULL DEFAULT '0.00',
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_overtimes_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_payrolls`
--

DROP TABLE IF EXISTS `hindustansystem_employee_payrolls`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_payrolls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `payroll_month` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `allowances` decimal(12,2) NOT NULL DEFAULT '0.00',
  `overtime_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_date` date DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_payrolls_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_employee_payrolls`
--

INSERT INTO `hindustansystem_employee_payrolls` (`id`, `employee_id`, `payroll_month`, `basic_salary`, `allowances`, `overtime_pay`, `deductions`, `net_salary`, `payment_date`, `status`, `created_at`, `updated_at`) VALUES
(3, 3, 'November 2026', 0.00, 16800.00, 0.00, 1000.00, 15800.00, NULL, 'Paid', '2026-11-24 02:05:45', '2026-11-24 02:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_salary_components`
--

DROP TABLE IF EXISTS `hindustansystem_employee_salary_components`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_salary_components` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `salary_component_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('allowance','deduction') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'allowance',
  `source` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Structure',
  `calculation_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Fixed Amount',
  `configured_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `calculated_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_salary_components_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_employee_salary_components`
--

INSERT INTO `hindustansystem_employee_salary_components` (`id`, `employee_id`, `salary_component_id`, `name`, `description`, `type`, `source`, `calculation_type`, `configured_value`, `calculated_amount`, `created_at`, `updated_at`) VALUES
(3, 3, NULL, 'House Rent Allowance (HRA)', NULL, 'allowance', 'Master', 'Fixed Amount', 4800.00, 4800.00, '2026-11-23 07:19:19', '2026-11-24 01:59:23'),
(14, 3, 17, 'Basic salary', NULL, 'allowance', 'Master', 'Fixed Amount', 12000.00, 12000.00, '2026-11-24 01:53:50', '2026-11-24 01:53:50'),
(17, 3, 5, 'Professional Tax (PT)', 'State government professional tax deduction', 'deduction', 'Master', 'Fixed Amount', 1000.00, 1000.00, '2026-11-24 01:59:51', '2026-11-24 01:59:51'),
(28, 6, 17, 'Basic salary', NULL, 'allowance', 'Master', 'Fixed Amount', 14000.00, 14000.00, '2026-11-25 00:53:10', '2026-11-25 00:53:10'),
(29, 6, 1, 'House Rent Allowance (HRA)', 'Housing rent allowance eligible for tax exemption under Sec 10(13A)', 'allowance', 'Master', 'Fixed Amount', 4800.00, 4800.00, '2026-11-25 00:53:24', '2026-11-25 00:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employee_salary_revisions`
--

DROP TABLE IF EXISTS `hindustansystem_employee_salary_revisions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_employee_salary_revisions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `structure_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `revision_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Salary Revision',
  `is_current` tinyint(1) NOT NULL DEFAULT '1',
  `base_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_allowances` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breakdown` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_employee_salary_revisions_employee_id_foreign` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_engineers`
--

DROP TABLE IF EXISTS `hindustansystem_engineers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_engineers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `engineer_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Site Engineer',
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `engineers_engineer_code_unique` (`engineer_code`),
  KEY `engineers_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_engineers`
--

INSERT INTO `hindustansystem_engineers` (`id`, `engineer_code`, `name`, `email`, `phone`, `designation`, `specialization`, `project_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ENG-001', 'Rahul Accountant', NULL, NULL, 'Site Engineer', NULL, NULL, 1, '2026-10-14 08:06:40', '2026-10-14 08:06:40'),
(2, 'ENG-002', 'Anand Kumar', NULL, NULL, 'Senior Project Engineer', NULL, NULL, 1, '2026-10-14 08:06:40', '2026-10-14 08:06:40'),
(3, 'ENG-003', 'Suresh Nair', NULL, NULL, 'Civil Engineer', NULL, NULL, 1, '2026-10-14 08:06:40', '2026-10-14 08:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_erp_modules`
--

DROP TABLE IF EXISTS `hindustansystem_erp_modules`;
CREATE TABLE IF NOT EXISTS `hindustansystem_erp_modules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'folder',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_erp_modules_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_erp_modules`
--

INSERT INTO `hindustansystem_erp_modules` (`id`, `name`, `code`, `section`, `icon`, `description`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', 'Overview', 'chart-bar', 'Executive dashboard and high-level KPI summaries', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(2, 'Projects & Configuration', 'projects_config', 'Projects & Configuration', 'building-office', 'Project configurations, unit registers, and partner shares', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(3, 'Sales & Booking', 'sales_booking', 'Sales & Booking', 'shopping-cart', 'Sales desks, bookings, availability grids, and rate histories', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(4, 'Returns & Cancellations', 'returns_cancellations', 'Sales & Booking', 'arrow-path', 'Booking cancellations, resale, and property exchange workflows', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(5, 'Customer & Collections', 'customer_collections', 'Customer & Collections', 'users', 'Customer profiles, installment schedules, and cheque clearances', 5, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(6, 'Contractor Operations', 'contractor_ops', 'Contractor Operations', 'wrench-screwdriver', 'Contractor master, Running Account (RA) bills, and site payouts', 6, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(7, 'Brokerage', 'brokerage', 'Contractor Operations', 'briefcase', 'Channel partner brokers, commission rates, and incentive payouts', 7, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(8, 'Petty Cash', 'petty_cash', 'Contractor Operations', 'banknotes', 'Daily site petty cash box, contra withdrawals, and site receipts', 8, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(9, 'Document Management (DMS)', 'dms', 'Contractor Operations', 'folder', 'Central repository for land deeds, approvals, and blueprints', 9, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(10, 'Site Expense', 'site_expense', 'Contractor Operations', 'building-storefront', 'Direct site overheads, material purchases, and vendor expenses', 10, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(11, 'Project Profitability', 'project_profitability', 'Finance & Analytics', 'chart-pie', 'Capital contributions, equity withdrawals, and margin metrics', 11, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(12, 'Bank & Treasury', 'bank_treasury', 'Finance & Analytics', 'building-library', 'Bank account masters, liquidity dashboards, and contra vouchers', 12, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(13, 'Accounting Reports', 'accounting_reports', 'Finance & Analytics', 'document-chart-bar', 'Charter of accounts, trial balances, and statutory P&L registers', 13, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(14, 'Loans & Debt Servicing', 'loans_debt', 'Loans & Debt Servicing', 'currency-rupee', 'Corporate debt, bank loan facilities, and EMI repayment schedules', 14, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(15, 'Master Configuration', 'admin_master', 'Administration', 'cog-6-tooth', 'Core entity configurations, staff masters, and physical unit types', 15, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(16, 'Users & Roles', 'admin_users_roles', 'Administration', 'user-group', 'System access control, user accounts, roles, and module permissions', 16, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(18, 'Employees', 'employees', 'Human Capital', 'users', 'Employee directory, HR profiles, attendance, payroll, documents, and assets', 17, 'active', '2026-11-23 00:20:40', '2026-11-23 00:20:40'),
(19, 'Payroll', 'payroll', 'Human Capital', 'banknotes', 'Financial periods, leave quotas, salary heads, monthly salary processing, and salary reports', 18, 'active', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(20, 'Office Rent', 'office_rent', 'Finance & Analytics', 'building-office-2', 'Office rent booking, TDS deduction, and bank disbursement', 19, 'active', '2026-11-25 02:40:31', '2026-11-25 02:40:31');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_erp_sub_modules`
--

DROP TABLE IF EXISTS `hindustansystem_erp_sub_modules`;
CREATE TABLE IF NOT EXISTS `hindustansystem_erp_sub_modules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `erp_module_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actions` json DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_erp_sub_modules_erp_module_id_foreign` (`erp_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_erp_sub_modules`
--

INSERT INTO `hindustansystem_erp_sub_modules` (`id`, `erp_module_id`, `name`, `code`, `route_name`, `actions`, `description`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Executive Dashboard', 'dashboard.view', 'dashboard', '[\"view\", \"excel\", \"pdf\"]', 'Main executive KPI statistics and collection trends', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(2, 2, 'Projects', 'projects.manage', 'projects.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Master project creation, tower specifications, and settings', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(3, 2, 'Unit Master', 'units.manage', 'units.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Unit directory, inventory availability, and pricing setup', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(4, 2, 'Partner Management', 'partners.manage', 'partners.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Joint venture partners, equity shares, and percentage allocations', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(5, 3, 'New Booking', 'sales.create', 'sales.index?action=add', '[\"create\", \"edit\"]', 'New property sales agreement and booking registration', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(6, 3, 'Sales Register', 'sales.view', 'sales.index', '[\"view\", \"create\", \"edit\", \"delete\", \"excel\", \"pdf\"]', 'Consolidated sales ledger with customer and unit records', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(7, 3, 'Unit Rate History', 'rate-revision.view', 'rate-revision.index', '[\"view\", \"create\"]', 'Track historical unit square foot pricing and revision approvals', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(8, 3, 'Availability Grid', 'unit-matrix.view', 'unit-matrix.index', '[\"view\", \"excel\", \"pdf\"]', 'Floor-by-floor interactive unit visual availability matrix', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(9, 4, 'Sales Return', 'sales.return', 'sales.index?tab=sale-return', '[\"view\", \"create\", \"process\"]', 'Process booking cancellations, forfeit charges, and customer refunds', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(10, 4, 'Unit Exchange', 'sales.exchange', 'sales.index?tab=exchange', '[\"view\", \"create\", \"process\"]', 'Swap or exchange existing booked units to new units', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(11, 4, 'Cancel Report', 'reports.sales_return', 'reports.sales_return', '[\"view\", \"excel\", \"pdf\"]', 'Audit and history of all cancelled property contracts', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(12, 4, 'Exchange Report', 'reports.exchange', 'reports.exchange_report', '[\"view\", \"excel\", \"pdf\"]', 'Audit trail for all swapped units and adjusted financials', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(13, 4, 'Additional Billing', 'cancellation.additional', 'cancellation-additional-work.index', '[\"view\", \"create\", \"edit\"]', 'Cancellation penalty fees and custom interior modification charges', 5, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(14, 5, 'Customer Directory', 'customers.manage', 'customers.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'All registered customer profiles, contact info, and documents', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(15, 5, 'Customer Ledger', 'customers.ledger', 'reports.customer_ledger', '[\"view\", \"excel\", \"pdf\"]', 'Detailed debit/credit customer transactional accounting statements', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(16, 5, 'Payment Milestone', 'emi.schedules', 'emi-collections.index', '[\"view\", \"create\", \"edit\"]', 'Construction-linked and customized installment payment schedules', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(17, 5, 'Payment Receipts', 'cheque.entry', 'cheque-receipt-entry.index', '[\"view\", \"create\", \"edit\"]', 'Inward payment voucher generation for cheques, cash, and bank transfers', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(18, 5, 'Cheque Realization', 'cheque.realize', 'cheque-realization.queue', '[\"view\", \"process\"]', 'Deposit and clear cheques into company bank accounts or mark bounce', 5, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(19, 5, 'Collection Insights', 'reports.collections', 'reports.collection_forecast', '[\"view\", \"excel\", \"pdf\"]', 'Aging analysis, overdue receivables, and collection forecasts', 6, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(20, 6, 'Contractor', 'contractors.manage', 'contractors.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Subcontractor and primary contractor vendor registration', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(21, 6, 'New RA Bill', 'ra-bills.create', 'expenses.ra-bills.verification?new=1', '[\"create\"]', 'Generate new contractor progress bill with retention & TDS', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(22, 6, 'RA Bill Verification', 'ra-bills.verify', 'expenses.ra-bills.verification', '[\"view\", \"verify\", \"approve\"]', 'Engineering review and quantity surveyor bill verification', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(23, 6, 'Contractor Payout', 'ra-bills.payout', 'expenses.ra-bills.payment-release', '[\"view\", \"disburse\"]', 'Treasury release and payment disbursement for approved RA bills', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(24, 6, 'Contractor Ledger', 'ra-bills.ledger', 'expenses.ra-bills.ledger', '[\"view\", \"excel\", \"pdf\"]', 'Historical billings, retentions, advances, and payments ledger', 5, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(25, 7, 'Broker Master', 'brokers.manage', 'brokers.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Real estate brokers directory and RERA commission agreements', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(26, 7, 'Broker Ledger', 'brokers.ledger', 'brokers.commission-ledger', '[\"view\", \"excel\", \"pdf\"]', 'Commission earned, TDS deductions, and payable balances', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(27, 7, 'Broker Payout', 'brokers.payout', 'brokers.payable-report', '[\"view\", \"disburse\"]', 'Process commission payments and voucher disbursements', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(28, 8, 'Categories', 'petty-cash.categories', 'categories.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Petty cash expense heads and GL account mappings', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(29, 8, 'Cash Withdrawals', 'petty-cash.contra', 'petty-cash.contra-withdrawal', '[\"view\", \"create\"]', 'Record bank cash withdrawals into petty cash physical boxes', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(30, 8, 'Daily Expense', 'petty-cash.daily', 'petty-cash.daily-site-expenses', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Log daily miscellaneous site expenditures and bill vouchers', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(31, 8, 'Petty Cash Report', 'petty-cash.report', 'petty-cash.balance-register', '[\"view\", \"excel\", \"pdf\"]', 'Audited opening, inflow, outflow, and closing balance register', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(32, 9, 'Document Repository', 'dms.view', 'dms.index', '[\"view\", \"download\", \"delete\"]', 'Browse, preview, and download digital project documents', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(33, 9, 'Upload Document', 'dms.upload', 'dms.index?upload=1', '[\"create\"]', 'Attach and index documents by project, category, and tags', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(34, 9, 'Document Categories', 'dms.categories', 'dms.categories.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Organize repository files by functional category classifications', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(35, 9, 'Document Types', 'dms.types', 'dms.document-types.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Configure file types, metadata schemas, and extension validations', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(36, 10, 'Expense Categories', 'site-expense.categories', 'site-expense-categories.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Material, fuel, machinery rental, and overhead category heads', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(37, 10, 'Site Expense', 'site-expense.view', 'site-expenses.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Direct site purchase bill entries and verification workflow', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(38, 10, 'Expense Payout', 'site-expense.payout', 'site-expenses.payment-release', '[\"view\", \"disburse\"]', 'Approve and release payments for verified vendor invoices', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(39, 10, 'Vendor Master', 'vendors.manage', 'vendors.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Material suppliers, service providers, and vendors directory', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(40, 11, 'Partner Contribution', 'partners.contributions', 'partner-contributions.index', '[\"view\", \"create\", \"delete\"]', 'Inward capital equity deposits made by partners into projects', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(41, 11, 'Partner Ledger', 'partners.ledger', 'reports.partner_outflow_ledger', '[\"view\", \"excel\", \"pdf\"]', 'Partner capital balances, returns, and equity accounts', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(42, 11, 'Partner Payout', 'partners.payout', 'reports.partner_statements', '[\"view\", \"disburse\"]', 'Distribution of profit dividends and capital return disbursements', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(43, 11, 'Project Margin Analysis', 'reports.margins', 'reports.project_margin_analysis', '[\"view\", \"excel\", \"pdf\"]', 'Gross revenue, direct construction cost, and net project profit analysis', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(44, 12, 'Company Bank', 'company-bank.manage', 'company-bank-accounts.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Operational checking, current, and escrow company accounts', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(45, 12, 'Treasury Dashboard', 'treasury.dashboard', 'treasury.dashboard', '[\"view\", \"excel\", \"pdf\"]', 'Real-time bank balances, daily inflows, and scheduled payouts', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(46, 12, 'Treasury Report', 'treasury.report', 'treasury.report', '[\"view\", \"excel\", \"pdf\"]', 'Consolidated bank liquidity position and historical activity', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(47, 12, 'Contra Transfers', 'vouchers.contra', 'vouchers.contra.create', '[\"view\", \"create\"]', 'Internal inter-bank transfers and cash-to-bank contra vouchers', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(48, 13, 'Chart of Accounts', 'coa.manage', 'chart-of-accounts.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'General Ledger account master, groups, and classification tree', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(49, 13, 'Voucher Types', 'voucher-types.manage', 'voucher-types.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Receipt, Payment, Journal, Contra, and Sales voucher series', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(50, 13, 'Trial Balance', 'reports.trial_balance', 'reports.trial_balance', '[\"view\", \"excel\", \"pdf\"]', 'Audited trial balance verifying debits equal credits per financial year', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(51, 13, 'Profit & Loss', 'reports.profit_loss', 'reports.profit_loss', '[\"view\", \"excel\", \"pdf\"]', 'Income, operational expenditure, and net period earnings statement', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(52, 13, 'Balance Sheet', 'reports.balance_sheet', 'reports.balance_sheet', '[\"view\", \"excel\", \"pdf\"]', 'Assets, liabilities, equity, and retained earnings register', 5, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(53, 13, 'GST Report', 'reports.gst', 'reports.gst_report', '[\"view\", \"excel\", \"pdf\"]', 'Input Tax Credit (ITC), output GST liability, and tax filing summary', 6, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(54, 14, 'Bank Master', 'bank.manage', 'bank.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Lending institutions and commercial banks register', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(55, 14, 'Company Loans', 'loans.manage', 'loans.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Project financing facilities, principal amounts, and interest terms', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(56, 14, 'Loan Outstanding Summary', 'loans.reports', 'loans.reports', '[\"view\", \"excel\", \"pdf\"]', 'Remaining debt liabilities, interest payables, and repayment calendars', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 06:16:36'),
(57, 18, 'Employees', 'employees.manage', 'employees.index', '[\"view\", \"create\", \"edit\", \"delete\", \"excel\", \"pdf\"]', 'Employee directory, HR profiles, attendance, salary, payroll, documents, and assets', 1, 'active', '2026-11-12 05:46:39', '2026-11-23 00:20:40'),
(58, 15, 'Engineer', 'engineers.manage', 'engineers.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Civil, structural, and site engineers assigned to projects', 2, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(59, 15, 'Cheque Status', 'cheque-statuses.manage', 'cheque-statuses.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Cheque clearance workflow lifecycle status states', 3, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(60, 15, 'Floor', 'floors.manage', 'floors.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Floor nomenclature (Basement, Ground, Typical Floors, Penthouse)', 4, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(61, 15, 'Unit Type', 'unit-types.manage', 'unit-types.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Unit configurations (1BHK, 2BHK, 3BHK, Commercial Shop, Office)', 5, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(62, 16, 'Users', 'users.manage', 'admin.users.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Enterprise user accounts, credentials, and role associations', 1, 'active', '2026-11-12 05:46:39', '2026-11-12 05:46:39'),
(63, 16, 'Roles & Permissions', 'roles.manage', 'admin.roles.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'RBAC security roles and fine-grained module permission gates', 2, 'active', '2026-11-12 05:46:39', '2026-11-13 05:42:49'),
(64, 16, 'Modules', 'modules.manage', 'admin.modules.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'ERP system modules and sub-module options catalog management', 3, 'active', '2026-11-12 05:46:40', '2026-11-12 05:46:40'),
(65, 16, 'Activity Logs', 'activity_logs.manage', 'admin.activity-logs.index', '[\"view\", \"delete\", \"excel\", \"pdf\"]', 'Employee activity, presence timeline, exports, and recorded changes audit', 4, 'active', '2026-11-16 04:06:57', '2026-11-16 04:06:57'),
(66, 19, 'Financial Period', 'payroll.financial_period', 'payroll.financial-period.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Configure payroll financial periods and working days', 1, 'active', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(67, 19, 'Leave Quota', 'payroll.leave_quota', 'payroll.leave-quota.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Manage annual leave quotas and monthly accruals', 2, 'active', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(68, 19, 'Salary Head', 'payroll.salary_head', 'payroll.salary-head.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Monthly payroll batches and salary head summaries', 3, 'active', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(69, 19, 'Salary', 'payroll.salary', 'payroll.salary.index', '[\"view\", \"create\", \"edit\", \"delete\", \"excel\", \"pdf\"]', 'Employee salary processing, deductions, and disbursement', 4, 'active', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(70, 19, 'Salary Report', 'payroll.salary_report', 'payroll.salary-report.index', '[\"view\", \"excel\", \"pdf\"]', 'Salary report summary by payroll head and employee', 5, 'active', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(71, 19, 'Allowance & Deduction', 'payroll.components', 'payroll.components.index', '[\"view\", \"create\", \"edit\", \"delete\"]', 'Master table for salary allowances and deductions', 6, 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(72, 20, 'Office Rent', 'office_rent.manage', 'office-rent.index', '[\"view\", \"create\", \"edit\", \"delete\", \"excel\"]', 'Manage office rent entries, TDS deductions, and landlord payments', 1, 'active', '2026-11-25 02:40:31', '2026-11-25 04:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_failed_jobs`
--

DROP TABLE IF EXISTS `hindustansystem_failed_jobs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `door_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustan_units_project_id_foreign` (`project_id`),
  KEY `hindustan_units_floor_id_foreign` (`floor_id`),
  KEY `hindustan_units_unit_type_id_foreign` (`unit_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_hindustan_units`
--

INSERT INTO `hindustansystem_hindustan_units` (`id`, `project_id`, `floor_id`, `unit_type_id`, `door_no`, `built_up_area`, `carpet_area`, `expected_rate_per_sqft`, `expected_sale_amount`, `sale_rate_per_sqft`, `sale_amount`, `difference`, `gst_behavior`, `gst_amount`, `status`, `created_at`, `updated_at`, `is_active`, `deleted_at`) VALUES
(1, 1, 17, 2, 'G 1', 325.00, 208.00, 22000.00, 7150000.00, 22000.00, 7507500.00, -357500.00, 'exclusive', 357500.00, 'sold', '2026-10-15 01:59:33', '2026-11-10 01:11:16', 1, NULL),
(2, 1, 17, 2, 'G 2', 480.00, 304.00, 22000.00, 10560000.00, 22000.00, 11088000.00, -528000.00, 'exclusive', 528000.00, 'sold', '2026-10-15 01:59:49', '2026-11-10 05:50:18', 1, NULL),
(3, 1, 17, 2, 'G 3', 310.00, 196.00, 22000.00, 6820000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:00:04', '2026-11-06 06:32:36', 1, NULL),
(4, 1, 17, 2, 'G 4', 284.00, 183.00, 22000.00, 6248000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:00:24', '2026-11-05 06:20:21', 1, NULL),
(5, 1, 1, 2, 'F 1', 976.00, 613.00, 19000.00, 18544000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:00:49', '2026-11-06 06:32:36', 1, NULL),
(6, 1, 1, 2, 'F 2', 1071.00, 696.00, 19000.00, 20349000.00, 19000.00, 20349000.00, 0.00, 'none', 0.00, 'sold', '2026-10-15 02:01:05', '2026-11-10 05:50:18', 1, NULL),
(7, 1, 1, 2, 'F 3', 846.00, 649.00, 19000.00, 16074000.00, NULL, NULL, NULL, 'exclusive', 634500.00, 'available', '2026-10-15 02:01:30', '2026-11-06 00:09:10', 1, NULL),
(8, 1, 1, 2, 'F 4', 1244.00, 708.00, 19000.00, 23636000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:01:49', '2026-11-16 04:51:40', 1, NULL),
(9, 1, 2, 2, 'S 1', 976.00, 613.00, 19000.00, 18544000.00, NULL, NULL, NULL, 'exclusive', 2108160.00, 'available', '2026-10-15 02:02:32', '2026-11-06 06:32:36', 1, NULL),
(10, 1, 2, 2, 'S 2', 1071.00, 696.00, 17000.00, 18207000.00, 17000.00, 19117350.00, -910350.00, 'exclusive', 910350.00, 'sold', '2026-10-15 02:02:54', '2026-11-16 02:54:25', 1, NULL),
(11, 1, 2, 2, 'S 3', 846.00, 649.00, 19000.00, 16074000.00, NULL, NULL, NULL, 'exclusive', 1607400.00, 'available', '2026-10-15 02:03:12', '2026-11-06 06:32:36', 1, NULL),
(12, 1, 2, 2, 'S 4', 1244.00, 708.00, 19000.00, 23636000.00, NULL, NULL, NULL, 'exclusive', 1181800.00, 'available', '2026-10-15 02:03:32', '2026-11-02 03:45:07', 1, NULL),
(13, 1, 3, 2, 'T 1', 976.00, 625.00, 19000.00, 18544000.00, 19000.00, 19471200.00, -927200.00, 'exclusive', 927200.00, 'sold', '2026-10-15 02:03:59', '2026-11-16 03:06:11', 1, NULL),
(14, 1, 3, 2, 'T 2', 1071.00, 708.00, 19000.00, 20349000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:04:22', '2026-10-15 02:04:22', 1, NULL),
(15, 1, 3, 2, 'T 3', 846.00, 660.00, 19000.00, 16074000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:04:41', '2026-10-15 02:04:41', 1, NULL),
(16, 1, 3, 2, 'T 4', 1244.00, 708.00, 19000.00, 23636000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:05:01', '2026-10-15 02:05:01', 1, NULL),
(17, 1, 18, 6, 'SI A', 1022.00, 686.00, 5000.00, 5110000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:07:04', '2026-10-16 05:07:46', 1, NULL),
(18, 1, 18, 6, 'SI B', 734.00, 507.00, 5000.00, 3670000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:09:06', '2026-10-15 02:09:06', 1, NULL),
(19, 1, 18, 6, 'SI C', 592.00, 402.00, 5000.00, 2960000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:09:26', '2026-10-15 02:09:26', 1, NULL),
(20, 1, 19, 6, 'SE A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:10:52', '2026-10-15 02:10:52', 1, NULL),
(21, 1, 19, 6, 'SE B', 1607.00, 1114.00, 5000.00, 8035000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:11:07', '2026-10-15 02:11:07', 1, NULL),
(22, 1, 19, 6, 'SE C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:11:26', '2026-10-15 02:11:26', 1, NULL),
(23, 1, 20, 6, 'E A', 949.00, 657.00, 5000.00, 4745000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:11:46', '2026-10-15 02:11:46', 1, NULL),
(24, 1, 20, 6, 'E B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:12:07', '2026-10-16 05:15:55', 1, NULL),
(25, 1, 20, 6, 'E C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:12:26', '2026-10-15 02:12:26', 1, NULL),
(26, 1, 22, 6, 'N A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:12:46', '2026-10-15 02:12:46', 1, NULL),
(27, 1, 22, 6, 'N B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:13:04', '2026-10-15 02:13:04', 1, NULL),
(28, 1, 22, 6, 'N C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:13:23', '2026-10-15 02:13:23', 1, NULL),
(29, 1, 23, 6, 'TE A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:14:13', '2026-11-04 01:25:33', 1, NULL),
(30, 1, 23, 6, 'TE B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:14:30', '2026-10-15 02:14:30', 1, NULL),
(31, 1, 23, 6, 'TE C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:14:51', '2026-10-15 02:14:51', 1, NULL),
(32, 1, 24, 6, '11 A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:15:06', '2026-10-19 02:16:13', 1, NULL),
(33, 1, 24, 6, '11 B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:15:26', '2026-10-23 03:14:20', 1, NULL),
(34, 1, 24, 6, '11 C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:15:42', '2026-10-15 02:15:42', 1, NULL),
(35, 1, 25, 6, '12 A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:16:04', '2026-10-15 02:16:04', 1, NULL),
(36, 1, 25, 6, '12 B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:16:35', '2026-10-19 02:16:13', 1, NULL),
(37, 1, 25, 6, '12 C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:16:54', '2026-10-15 02:16:54', 1, NULL),
(38, 1, 26, 6, '13 A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:17:12', '2026-10-15 02:17:12', 1, NULL),
(39, 1, 26, 6, '13 B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:17:29', '2026-10-15 02:17:29', 1, NULL),
(40, 1, 26, 6, '13 C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:17:48', '2026-10-15 02:17:48', 1, NULL),
(41, 1, 27, 6, '14 A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:18:05', '2026-10-15 02:18:05', 1, NULL),
(42, 1, 27, 6, '14 B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'exclusive', 786000.00, 'available', '2026-10-15 02:18:21', '2026-10-23 03:14:20', 1, NULL),
(43, 1, 27, 6, '14 C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:18:36', '2026-10-15 02:18:36', 1, NULL),
(44, 1, 28, 6, '15 A', 979.00, 657.00, 5000.00, 4895000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:18:58', '2026-10-15 02:18:58', 1, NULL),
(45, 1, 28, 6, '15 B', 1572.00, 1090.00, 5000.00, 7860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:19:22', '2026-10-15 02:19:22', 1, NULL),
(46, 1, 28, 6, '15 C', 1586.00, 1102.00, 5000.00, 7930000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:20:04', '2026-10-15 02:20:04', 1, NULL),
(47, 1, 29, 6, '16 A', 1107.00, 734.00, 5000.00, 5535000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:22:40', '2026-10-15 02:22:40', 1, NULL),
(48, 1, 29, 6, '16 B', 1712.00, 1175.00, 5000.00, 8560000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:22:56', '2026-10-15 02:22:56', 1, NULL),
(49, 1, 29, 6, '16 C', 1785.00, 1250.00, 5000.00, 8925000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:23:13', '2026-10-15 02:23:13', 1, NULL),
(50, 1, 30, 6, '17 A', 1107.00, 734.00, 5000.00, 5535000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:23:44', '2026-10-15 02:23:44', 1, NULL),
(51, 1, 30, 6, '17 B', 1712.00, 1162.00, 5000.00, 8560000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:24:13', '2026-10-15 02:24:13', 1, NULL),
(52, 1, 30, 6, '17 C', 1785.00, 1268.00, 5000.00, 8925000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:24:32', '2026-10-15 02:24:32', 1, NULL),
(53, 1, 4, 5, 'FO 1', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:25:58', '2026-10-16 05:07:46', 1, NULL),
(54, 1, 4, 5, 'FO 2', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:26:10', '2026-10-15 02:26:10', 1, NULL),
(55, 1, 4, 5, 'FO 3', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:26:20', '2026-10-15 02:26:20', 1, NULL),
(56, 1, 4, 5, 'FO 4', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:26:31', '2026-10-15 02:26:31', 1, NULL),
(57, 1, 4, 5, 'FO 5', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:26:41', '2026-10-15 02:26:41', 1, NULL),
(58, 1, 5, 5, 'FI 1', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-10-15 02:26:51', '2026-10-19 02:16:13', 1, NULL),
(59, 1, 5, 5, 'FI 2', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'exclusive', 20000.00, 'available', '2026-10-15 02:27:01', '2026-11-06 00:04:10', 1, NULL),
(60, 1, 5, 5, 'FI 3', NULL, NULL, NULL, 400000.00, 0.00, 400000.00, 0.00, 'none', 0.00, 'sold', '2026-10-15 02:27:13', '2026-11-10 01:11:16', 1, NULL),
(61, 1, 5, 5, 'FI 4', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'exclusive', 20000.00, 'available', '2026-10-15 02:27:25', '2026-10-16 04:49:30', 1, NULL),
(62, 1, 5, 5, 'FI 5', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'exclusive', 20000.00, 'available', '2026-10-15 02:27:35', '2026-11-05 01:43:55', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_jobs`
--

DROP TABLE IF EXISTS `hindustansystem_jobs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_journal_entries`
--

DROP TABLE IF EXISTS `hindustansystem_journal_entries`;
CREATE TABLE IF NOT EXISTS `hindustansystem_journal_entries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `voucher_id` bigint UNSIGNED NOT NULL,
  `account_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `line_narration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entries_account_id_index` (`account_id`),
  KEY `journal_entries_voucher_id_foreign` (`voucher_id`),
  KEY `journal_entries_entity_type_entity_id_index` (`entity_type`,`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_journal_entries`
--

INSERT INTO `hindustansystem_journal_entries` (`id`, `voucher_id`, `account_id`, `debit_amount`, `credit_amount`, `line_narration`, `entity_type`, `entity_id`, `created_at`, `updated_at`) VALUES
(1, 1, '1010', 7907500.00, 0.00, 'Customer Receivable (Anchu Mol)', 'CUSTOMER', 1, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(2, 1, '2021', 0.00, 178750.00, 'Output CGST Payable', NULL, NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(3, 1, '2022', 0.00, 178750.00, 'Output SGST Payable', NULL, NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(4, 2, '1001', 1500000.00, 0.00, 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-11-10 01:11:51', '2026-11-10 01:11:51'),
(5, 2, '1010', 0.00, 1500000.00, 'Customer Receivables (Anchu Mol Debt Decreases)', 'CUSTOMER', 1, '2026-11-10 01:11:51', '2026-11-10 01:11:51'),
(6, 3, '3001', 100000.00, 0.00, 'Partner Capital Liability Cleared (Basheer)', 'PARTNER', 1, '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(7, 4, '1002', 10000.00, 0.00, 'Petty Cash Box (Cash Asset Increases)', NULL, NULL, '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(8, 4, '1001', 0.00, 10000.00, 'Axis Bank 9752 (Bank Asset Decreases)', 'BANK', 1, '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(9, 5, '1010', 31437000.00, 0.00, 'Customer Receivable (Athira)', 'CUSTOMER', 2, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(10, 5, '2021', 0.00, 264000.00, 'Output CGST Payable', NULL, NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(11, 5, '2022', 0.00, 264000.00, 'Output SGST Payable', NULL, NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(12, 5, '4001', 618180.00, 0.00, 'Agent Payable Expense', NULL, NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(13, 5, '2003', 0.00, 618180.00, 'Agent Payable Liability (Noushad)', 'AGENT', 1, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(14, 6, '1001', 1000000.00, 0.00, 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-11-13 02:10:18', '2026-11-13 02:10:18'),
(15, 6, '1010', 0.00, 1000000.00, 'Customer Receivables (Athira Debt Decreases)', 'CUSTOMER', 2, '2026-11-13 02:10:18', '2026-11-13 02:10:18'),
(16, 7, '1010', 19117350.00, 0.00, 'Customer Receivable (Anchu Mol)', 'CUSTOMER', 1, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(17, 7, '2021', 0.00, 455175.00, 'Output CGST Payable', NULL, NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(18, 7, '2022', 0.00, 455175.00, 'Output SGST Payable', NULL, NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(19, 7, '4001', 364140.00, 0.00, 'Agent Payable Expense', NULL, NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(20, 7, '2003', 0.00, 364140.00, 'Agent Payable Liability (Noushad)', 'AGENT', 1, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(21, 8, '1010', 19471200.00, 0.00, 'Customer Receivable (Anchu Mol)', 'CUSTOMER', 1, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(22, 8, '2021', 0.00, 463600.00, 'Output CGST Payable', NULL, NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(23, 8, '2022', 0.00, 463600.00, 'Output SGST Payable', NULL, NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(24, 9, '1010', 23636000.00, 0.00, 'Customer Receivable (Athira)', 'CUSTOMER', 2, '2026-11-16 03:30:05', '2026-11-16 03:30:05'),
(25, 10, '1010', 100000.00, 0.00, 'Customer Receivables (Liability Cleared)', 'CUSTOMER', 2, '2026-11-16 04:52:09', '2026-11-16 04:52:09'),
(26, 10, '1001', 0.00, 100000.00, 'Axis Bank 9752 (Bank Asset Decreases)', 'BANK', 1, '2026-11-16 04:52:09', '2026-11-16 04:52:09'),
(27, 11, '4002', 336000.00, 0.00, 'Contractor Work Expenses (MKH INFRASTRUCTURE - RA #rq11)', 'CONTRACTOR', 12, '2026-11-16 04:55:53', '2026-11-16 04:55:53'),
(28, 11, '2002', 0.00, 336000.00, 'Contractor Payables Liability (MKH INFRASTRUCTURE - RA #rq11)', 'CONTRACTOR', 12, '2026-11-16 04:55:53', '2026-11-16 04:55:53'),
(29, 12, '2002', 100000.00, 0.00, 'Contractor Payables Cleared (MKH INFRASTRUCTURE - RA #rq11)', 'CONTRACTOR', 12, '2026-11-16 04:57:23', '2026-11-16 04:57:23'),
(30, 12, '1001', 0.00, 100000.00, 'Payment Disbursed via Axis Bank 9752 (Ref: asdadsa)', 'BANK', 1, '2026-11-16 04:57:23', '2026-11-16 04:57:23'),
(33, 14, '2011', 28900.00, 0.00, 'Salary Payable cleared for Anchumol S (EMP-1001) - Hindustan erp November 2026', 'STAFF', 3, '2026-11-25 00:52:16', '2026-11-25 00:52:16'),
(34, 14, '1001', 0.00, 28900.00, 'Salary disbursed via Axis Bank 9752 (Ref: 1234566)', 'BANK', 1, '2026-11-25 00:52:16', '2026-11-25 00:52:16'),
(44, 19, '4006', 500000.00, 0.00, 'Office Rent Expense (HINDUSTAN) - November 2026 | Landlord: kannur', 'OFFICE_RENT', 3, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(45, 19, '2012', 0.00, 400000.00, 'Office Rent Payable to kannur (HINDUSTAN) - November 2026', 'OFFICE_RENT', 3, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(46, 19, '2023', 0.00, 100000.00, 'TDS Sec 194I deducted on Office Rent (kannur) - November 2026', 'OFFICE_RENT', 3, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(54, 23, '2010', 400000.00, 0.00, 'Provisions & Outstanding Payables cleared for kannur (HINDUSTAN) - November 2026', 'OFFICE_RENT', 3, '2026-11-25 03:56:21', '2026-11-25 03:56:21'),
(55, 23, '1001', 0.00, 400000.00, 'Office Rent paid to kannur (HINDUSTAN) from Tabasco Hindustan - Axis Bank 9752 (A/C 9752) (Ref: 123434)', 'BANK', 1, '2026-11-25 03:56:21', '2026-11-25 03:56:21'),
(62, 27, '4106', 45000.00, 0.00, 'Office Rent & Maintenance (HINDUSTAN) - October 2026 | Landlord: jhon', 'OFFICE_RENT', 8, '2026-11-26 00:49:27', '2026-11-26 00:49:27'),
(63, 27, '2010', 0.00, 45000.00, 'Provisions & Outstanding Payables - Office Rent (jhon, HINDUSTAN) - October 2026', 'OFFICE_RENT', 8, '2026-11-26 00:49:27', '2026-11-26 00:49:27'),
(66, 29, '2011', 16800.00, 0.00, 'Salary Payable cleared for Athira L (EMP-1002) - Hindustan erp November 2026 | Employee Bank: HDFC - A/C 6557657686 - IFSC hdfc56865', 'STAFF', 6, '2026-11-26 06:15:51', '2026-11-26 06:15:51'),
(67, 29, '1001', 0.00, 16800.00, 'Salary paid to Athira L (EMP-1002) from Tabasco Hindustan - Axis Bank 9752 (A/C 9752) to HDFC - A/C 6557657686 - IFSC hdfc56865 (Ref: 4552343)', 'BANK', 1, '2026-11-26 06:15:51', '2026-11-26 06:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_journal_vouchers`
--

DROP TABLE IF EXISTS `hindustansystem_journal_vouchers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_journal_vouchers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_type_id` bigint UNSIGNED DEFAULT NULL,
  `voucher_date` date NOT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Posted',
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_vouchers_voucher_no_unique` (`voucher_no`),
  KEY `journal_vouchers_voucher_type_id_index` (`voucher_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_journal_vouchers`
--

INSERT INTO `hindustansystem_journal_vouchers` (`id`, `voucher_no`, `voucher_type_id`, `voucher_date`, `reference_id`, `reference_no`, `narration`, `status`, `is_historical`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'JV-SB-2026-0001', 15, '2026-11-10', 1, NULL, 'Booking Anchu Mol Unit G 1', 'Posted', 0, 1, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(2, 'JV-CR-2026-0001', 17, '2026-11-10', 1, NULL, 'Cheque #12345 cleared - Anchu Mol', 'Posted', 0, 1, '2026-11-10 01:11:51', '2026-11-10 01:11:51'),
(3, 'JV-PS-2026-0001', 27, '2026-11-10', 1, NULL, 'partner payout to basheer', 'Posted', 0, 1, '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(4, 'JV-PC-2026-4654', 18, '2026-11-10', NULL, NULL, 'Cash withdrawal for petty cash box', 'Posted', 0, 1, '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(5, 'JV-SB-2026-0002', 15, '2026-11-10', 2, NULL, 'Booking Athira Unit G 2 with Agent Noushad', 'Posted', 0, 1, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(6, 'JV-CR-2026-0002', 17, '2026-11-13', 2, NULL, 'Cheque #454654 cleared - Athira', 'Posted', 0, 1, '2026-11-13 02:10:18', '2026-11-13 02:10:18'),
(7, 'JV-SB-2026-0003', 15, '2026-11-16', 3, NULL, 'Booking Anchu Mol Unit S 2 with Agent Noushad', 'Posted', 0, 1, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(8, 'JV-SB-2026-0004', 15, '2026-11-16', 4, NULL, 'Booking Anchu Mol Unit T 1', 'Posted', 0, 1, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(9, 'JV-SB-2026-0005', 15, '2026-11-16', 5, NULL, 'Booking Athira Unit F 4', 'Posted', 0, 1, '2026-11-16 03:30:05', '2026-11-16 03:30:05'),
(10, 'JV-RF-2026-0005', 16, '2026-11-16', 5, NULL, 'Booking Cancellation refund to Athira (Customer refund processed as per cancellation agreement.)', 'Posted', 0, 1, '2026-11-16 04:52:09', '2026-11-16 04:52:09'),
(11, 'JV-RA-2026-0001', 1, '2026-11-16', 1, NULL, 'Verified RA Bill #rq11 - MKH INFRASTRUCTURE (Verified by: Anand Kumar (Senior Project Engineer))', 'Posted', 0, 1, '2026-11-16 04:55:53', '2026-11-16 04:55:53'),
(12, 'JV-CP-2026-0001', 2, '2026-11-16', 1, NULL, 'Contractor Payment Release for RA Bill #rq11 to MKH INFRASTRUCTURE via Axis Bank 9752', 'Posted', 0, 1, '2026-11-16 04:57:23', '2026-11-16 04:57:23'),
(14, 'JV-SAL-2026-0001', 30, '2026-11-25', 1, NULL, 'Salary Payment to Anchumol S (EMP-1001) for Hindustan erp November 2026 via Axis Bank 9752', 'Posted', 0, 1, '2026-11-25 00:52:16', '2026-11-25 00:52:16'),
(19, 'JV-RENT-2026-0003', 33, '2026-11-25', 3, NULL, 'Office Rent (HINDUSTAN) for November 2026 - Landlord: kannur | Booked as Unpaid Payable', 'Posted', 0, 1, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(23, 'JV-RENTP-2026-0003', 35, '2026-11-25', 3, NULL, 'Office Rent Payable settled for HINDUSTAN (November 2026) to kannur via Tabasco Hindustan - Axis Bank 9752 (A/C 9752)', 'Posted', 0, 1, '2026-11-25 03:56:21', '2026-11-25 03:56:21'),
(27, 'JV-RENT-2026-0008', 33, '2026-11-05', 8, NULL, 'Office Rent (HINDUSTAN) for October 2026 - Landlord: jhon | Booked as Unpaid Payable', 'Posted', 0, 1, '2026-11-26 00:49:26', '2026-11-26 00:49:26'),
(29, 'JV-SAL-2026-0002', 30, '2026-11-26', 2, NULL, 'Salary Payment to Athira L (EMP-1002) for Hindustan erp November 2026 from Tabasco Hindustan - Axis Bank 9752 (A/C 9752) to HDFC - A/C 6557657686 - IFSC hdfc56865', 'Posted', 0, 1, '2026-11-26 06:15:51', '2026-11-26 06:15:51');

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ledger_entries`
--

INSERT INTO `hindustansystem_ledger_entries` (`id`, `system_id`, `account_id`, `voucher_id`, `voucher_line_id`, `date`, `debit`, `credit`, `running_balance`, `created_at`, `updated_at`) VALUES
(1, 1, 20, 1, 1, '2026-11-10', 100000.00, 0.00, 0.00, '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(2, 1, 4, 1, 2, '2026-11-10', 0.00, 100000.00, 0.00, '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(3, 1, 33, 4, 7, '2026-11-10', 960.00, 0.00, 0.00, '2026-11-13 01:24:03', '2026-11-13 01:24:03'),
(4, 1, 32, 4, 8, '2026-11-10', 0.00, 960.00, 0.00, '2026-11-13 01:24:04', '2026-11-13 01:24:04'),
(5, 1, 38, 3, 5, '2026-11-10', 560.00, 0.00, 0.00, '2026-11-13 01:24:04', '2026-11-13 01:24:04'),
(6, 1, 32, 3, 6, '2026-11-10', 0.00, 560.00, 0.00, '2026-11-13 01:24:04', '2026-11-13 01:24:04'),
(7, 1, 17, 2, 3, '2026-11-10', 0.00, 10000.00, 0.00, '2026-11-13 01:24:04', '2026-11-13 01:24:04'),
(8, 1, 32, 2, 4, '2026-11-10', 10000.00, 0.00, 0.00, '2026-11-13 01:24:04', '2026-11-13 01:24:04'),
(9, 1, 18, 6, 11, '2026-11-16', 100000.00, 0.00, 0.00, '2026-11-16 05:04:19', '2026-11-16 05:04:19'),
(10, 1, 29, 6, 12, '2026-11-16', 0.00, 100000.00, 0.00, '2026-11-16 05:04:19', '2026-11-16 05:04:19'),
(11, 1, 23, 5, 9, '2026-11-16', 100000.00, 0.00, 0.00, '2026-11-16 05:04:19', '2026-11-16 05:04:19'),
(12, 1, 4, 5, 10, '2026-11-16', 0.00, 100000.00, 0.00, '2026-11-16 05:04:19', '2026-11-16 05:04:19');

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
  `lender_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `tenure_months` int NOT NULL,
  `start_date` date NOT NULL,
  `schedule_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loan_disbursals`
--

DROP TABLE IF EXISTS `hindustansystem_loan_disbursals`;
CREATE TABLE IF NOT EXISTS `hindustansystem_loan_disbursals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `disbursal_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disbursal_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disbursal_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `posted_by` bigint UNSIGNED DEFAULT NULL,
  `cancelled_by` bigint UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_disbursals_system_id_index` (`system_id`),
  KEY `loan_disbursals_loan_id_index` (`loan_id`),
  KEY `loan_disbursals_disbursal_no_index` (`disbursal_no`),
  KEY `loan_disbursals_created_by_foreign` (`created_by`),
  KEY `loan_disbursals_posted_by_foreign` (`posted_by`),
  KEY `loan_disbursals_cancelled_by_foreign` (`cancelled_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_migrations`
--

DROP TABLE IF EXISTS `hindustansystem_migrations`;
CREATE TABLE IF NOT EXISTS `hindustansystem_migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(53, '2026_07_20_124603_add_paid_amount_and_rescheduled_from_id_to_customer_installments_table', 30),
(54, '2026_07_28_150000_create_payment_modes_table', 31),
(55, '2026_07_29_120000_add_snapshot_data_to_sale_status_logs_table', 31),
(56, '2026_07_30_110000_add_gst_fields_to_bills_table', 32),
(57, '2026_08_06_180000_change_sales_status_to_varchar', 33),
(58, '2026_11_02_143500_change_sales_payment_mode_to_varchar', 34),
(59, '2026_10_15_171000_add_is_historical_to_vouchers_and_related_tables', 35),
(60, '2026_11_16_120000_add_audit_fields_to_activity_logs_table', 36),
(61, '2026_11_16_150000_add_activity_logs_sub_module', 37),
(62, '2026_11_20_095758_add_username_to_users_table', 38),
(63, '2026_11_23_054058_upgrade_employees_module_tables', 39),
(64, '2026_11_23_074336_create_payroll_module_tables', 40),
(65, '2026_11_23_115708_create_salary_components_and_revisions_tables', 41),
(66, '2026_11_24_000001_drop_default_value_from_salary_components_table', 42),
(67, '2026_11_24_000002_create_employee_leave_quotas_table', 43),
(68, '2026_11_24_000003_add_dates_to_payroll_salary_heads_table', 44),
(69, '2026_11_25_000001_add_payment_bank_fields_to_payroll_salaries_table', 45),
(70, '2026_11_25_000002_create_office_rents_table', 46),
(71, '2026_11_25_000003_seed_office_rent_chart_of_accounts', 47),
(72, '2026_11_25_000004_add_excel_permission_to_office_rent', 48),
(74, '2026_11_26_000002_reset_employee_leave_quotas_to_allowed_taken', 49),
(75, '2026_11_26_000003_add_lop_amount_to_payroll_salaries_table', 50);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_permissions`
--

DROP TABLE IF EXISTS `hindustansystem_model_has_permissions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_model_has_permissions`
--

INSERT INTO `hindustansystem_model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(30, 'App\\Models\\User', 4),
(31, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_roles`
--

DROP TABLE IF EXISTS `hindustansystem_model_has_roles`;
CREATE TABLE IF NOT EXISTS `hindustansystem_model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_model_has_roles`
--

INSERT INTO `hindustansystem_model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 7),
(7, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(3, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_office_rents`
--

DROP TABLE IF EXISTS `hindustansystem_office_rents`;
CREATE TABLE IF NOT EXISTS `hindustansystem_office_rents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `office_location` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `landlord_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rent_month` tinyint UNSIGNED NOT NULL,
  `rent_year` smallint UNSIGNED NOT NULL,
  `rent_period_label` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gross_rent_amount` decimal(15,2) NOT NULL,
  `tds_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_payable_amount` decimal(15,2) NOT NULL,
  `payment_status` enum('Paid','Unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `journal_voucher_id` bigint UNSIGNED DEFAULT NULL,
  `payment_voucher_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `or_sys_idx` (`system_id`),
  KEY `or_period_idx` (`rent_year`,`rent_month`),
  KEY `or_status_idx` (`payment_status`),
  KEY `or_bank_idx` (`company_bank_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_office_rents`
--

INSERT INTO `hindustansystem_office_rents` (`id`, `system_id`, `office_location`, `landlord_name`, `rent_month`, `rent_year`, `rent_period_label`, `gross_rent_amount`, `tds_amount`, `net_payable_amount`, `payment_status`, `company_bank_account_id`, `payment_reference`, `voucher_date`, `payment_date`, `journal_voucher_id`, `payment_voucher_id`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 1, 'HINDUSTAN', 'kannur', 11, 2026, 'November 2026', 500000.00, 100000.00, 400000.00, 'Paid', 1, '123434', '2026-11-25', '2026-11-25', 19, 23, 'tgfrgdhfd', 1, '2026-11-25 03:26:32', '2026-11-25 03:56:21'),
(8, 1, 'HINDUSTAN', 'jhon', 10, 2026, 'October 2026', 45000.00, 0.00, 45000.00, 'Unpaid', NULL, NULL, '2026-11-05', NULL, 27, NULL, NULL, 1, '2026-11-26 00:49:26', '2026-11-26 00:49:27');

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
  `payment_mode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_partner_allocations_system_id_foreign` (`system_id`),
  KEY `hindustansystem_partner_allocations_project_id_foreign` (`project_id`),
  KEY `hindustansystem_partner_allocations_payment_id_foreign` (`payment_id`),
  KEY `hindustansystem_partner_allocations_voucher_id_foreign` (`voucher_id`),
  KEY `pa_partner_fk` (`partner_id`),
  KEY `pa_cbank_fk` (`company_bank_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_partner_allocations`
--

INSERT INTO `hindustansystem_partner_allocations` (`id`, `system_id`, `partner_id`, `project_id`, `payment_id`, `allocated_amount`, `payment_mode`, `remarks`, `company_bank_account_id`, `date`, `voucher_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 100000.00, 'Bank Transfer (NEFT / RTGS / IMPS)', '767676', 1, '2026-11-10', 1, '2026-11-10 01:13:35', '2026-11-10 01:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_partner_contributions`
--

DROP TABLE IF EXISTS `hindustansystem_partner_contributions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_partner_contributions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT '1',
  `partner_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `company_bank_account_id` bigint UNSIGNED NOT NULL,
  `contribution_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode_id` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Posted',
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_prtc_partner` (`partner_id`),
  KEY `fk_prtc_project` (`project_id`),
  KEY `fk_prtc_bank` (`company_bank_account_id`),
  KEY `fk_prtc_payment_mode` (`payment_mode_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gstin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `linked_account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payees_system_id_foreign` (`system_id`),
  KEY `hindustansystem_payees_linked_account_id_foreign` (`linked_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payees`
--

INSERT INTO `hindustansystem_payees` (`id`, `system_id`, `type`, `name`, `phone`, `email`, `gstin`, `pan`, `address`, `is_active`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Partner', 'Basheer', NULL, NULL, NULL, NULL, NULL, 1, 20, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(2, 1, 'Partner', 'Pavoor', NULL, NULL, NULL, NULL, NULL, 1, 21, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(12, 1, 'Contractor', 'MKH INFRASTRUCTURE', '09048393993', 'jhancydinuvj@gmail.com', NULL, NULL, 'MALL OF INDIA, KANHANGAD', 1, 18, '2026-10-17 04:42:04', '2026-10-17 04:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payments`
--

DROP TABLE IF EXISTS `hindustansystem_payments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_payments_receipt_number_unique` (`receipt_number`),
  KEY `hindustansystem_payments_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_payments_project_id_foreign` (`project_id`),
  KEY `hindustansystem_payments_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payment_modes`
--

DROP TABLE IF EXISTS `hindustansystem_payment_modes`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payment_modes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `requires_reference` tinyint(1) NOT NULL DEFAULT '0',
  `requires_bank` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_payment_modes_system_id_code_unique` (`system_id`,`code`),
  KEY `hindustansystem_payment_modes_system_id_index` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payment_modes`
--

INSERT INTO `hindustansystem_payment_modes` (`id`, `system_id`, `name`, `code`, `description`, `requires_reference`, `requires_bank`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cash', 'CASH', 'Physical cash payment intake or payout.', 0, 0, 'active', '2026-07-29 02:09:06', '2026-07-29 02:09:06'),
(2, 1, 'Cheque', 'CHEQUE', 'Bank cheque payment requiring cheque number & issuing bank details.', 1, 1, 'active', '2026-07-29 02:09:06', '2026-07-29 02:09:06'),
(3, 1, 'Bank Transfer (NEFT / RTGS / IMPS)', 'BANK_TRANSFER', 'Direct wire transfer via corporate bank account with UTR reference.', 1, 1, 'active', '2026-07-29 02:09:06', '2026-07-29 02:09:06'),
(4, 1, 'UPI / Online Payment', 'UPI', 'Digital payment via Google Pay, PhonePe, Paytm, or UPI QR code.', 1, 0, 'active', '2026-07-29 02:09:06', '2026-07-29 02:09:06'),
(5, 1, 'Credit / Debit Card', 'CARD', 'POS swipe machine card transaction with Auth / Ref ID.', 1, 0, 'active', '2026-07-29 02:09:06', '2026-07-29 02:09:06'),
(6, 1, 'Demand Draft (DD)', 'DD', 'Bank demand draft requiring DD number and bank name.', 1, 1, 'active', '2026-07-29 02:09:06', '2026-07-29 02:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payroll_financial_periods`
--

DROP TABLE IF EXISTS `hindustansystem_payroll_financial_periods`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payroll_financial_periods` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `working_days` int NOT NULL DEFAULT '26',
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payroll_financial_periods_system_id_index` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payroll_financial_periods`
--

INSERT INTO `hindustansystem_payroll_financial_periods` (`id`, `system_id`, `name`, `start_date`, `end_date`, `working_days`, `is_current`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'FY 2026-2027', '2026-11-24', '2027-03-24', 26, 0, 'active', NULL, '2026-11-24 02:55:26', '2026-11-24 02:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payroll_leave_quotas`
--

DROP TABLE IF EXISTS `hindustansystem_payroll_leave_quotas`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payroll_leave_quotas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `financial_period_id` bigint UNSIGNED DEFAULT NULL,
  `leave_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annual_quota` decimal(6,1) NOT NULL DEFAULT '12.0',
  `monthly_accrual` decimal(5,2) NOT NULL DEFAULT '1.00',
  `carry_forward` tinyint(1) NOT NULL DEFAULT '0',
  `max_carry_forward` decimal(6,1) NOT NULL DEFAULT '0.0',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payroll_leave_quotas_system_id_index` (`system_id`),
  KEY `hindustansystem_payroll_leave_quotas_financial_period_id_index` (`financial_period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payroll_salaries`
--

DROP TABLE IF EXISTS `hindustansystem_payroll_salaries`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payroll_salaries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `salary_head_id` bigint UNSIGNED DEFAULT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `payroll_month` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_days` decimal(5,1) NOT NULL DEFAULT '26.0',
  `present_days` decimal(5,1) NOT NULL DEFAULT '26.0',
  `leave_days` decimal(5,1) NOT NULL DEFAULT '0.0',
  `overtime_hours` decimal(6,2) NOT NULL DEFAULT '0.00',
  `basic_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `hra` decimal(12,2) NOT NULL DEFAULT '0.00',
  `allowances` decimal(12,2) NOT NULL DEFAULT '0.00',
  `overtime_pay` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `lop_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_date` date DEFAULT NULL,
  `payment_mode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bank Transfer',
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_voucher_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payroll_salaries_employee_id_foreign` (`employee_id`),
  KEY `hindustansystem_payroll_salaries_system_id_index` (`system_id`),
  KEY `hindustansystem_payroll_salaries_salary_head_id_index` (`salary_head_id`),
  KEY `ps_bank_acc_idx` (`company_bank_account_id`),
  KEY `ps_jv_idx` (`journal_voucher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payroll_salaries`
--

INSERT INTO `hindustansystem_payroll_salaries` (`id`, `system_id`, `salary_head_id`, `employee_id`, `payroll_month`, `working_days`, `present_days`, `leave_days`, `overtime_hours`, `basic_salary`, `hra`, `allowances`, `overtime_pay`, `gross_salary`, `deductions`, `lop_amount`, `net_salary`, `payment_date`, `payment_mode`, `company_bank_account_id`, `payment_reference`, `journal_voucher_id`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'Hindustan erp November 2026', 26.0, 26.0, 0.0, 0.00, 9600.00, 3500.00, 16800.00, 0.00, 29900.00, 1000.00, 0.00, 28900.00, '2026-11-25', 'Bank Transfer', 1, '1234566', 14, 'Paid', NULL, '2026-11-24 04:22:11', '2026-11-25 00:52:16'),
(2, 1, 1, 6, 'Hindustan erp November 2026', 26.0, 26.0, 0.0, 0.00, 0.00, 0.00, 18800.00, 0.00, 18800.00, 2000.00, 2000.00, 16800.00, '2026-11-26', 'Bank Transfer', 1, '4552343', 29, 'Paid', NULL, '2026-11-24 04:22:11', '2026-11-26 06:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payroll_salary_heads`
--

DROP TABLE IF EXISTS `hindustansystem_payroll_salary_heads`;
CREATE TABLE IF NOT EXISTS `hindustansystem_payroll_salary_heads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `financial_period_id` bigint UNSIGNED DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `year` smallint UNSIGNED NOT NULL DEFAULT '2026',
  `payment_date` date DEFAULT NULL,
  `total_employees` int NOT NULL DEFAULT '0',
  `total_gross` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total_net` decimal(14,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payroll_salary_heads_system_id_index` (`system_id`),
  KEY `hindustansystem_payroll_salary_heads_financial_period_id_index` (`financial_period_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payroll_salary_heads`
--

INSERT INTO `hindustansystem_payroll_salary_heads` (`id`, `system_id`, `financial_period_id`, `from_date`, `to_date`, `name`, `month`, `year`, `payment_date`, `total_employees`, `total_gross`, `total_deductions`, `total_net`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-11-01', '2026-11-30', 'Hindustan erp November 2026', 11, 2026, NULL, 2, 48700.00, 3000.00, 45700.00, 'Draft', NULL, '2026-11-24 04:13:07', '2026-11-26 06:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_permissions`
--

DROP TABLE IF EXISTS `hindustansystem_permissions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=466 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(13, 'units.rate.manage', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(14, 'Dashboard', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(15, 'Projects & Units', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(16, 'Customers', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(17, 'Sales & Collections', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(18, 'Reports', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(19, 'Customer Ledger', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(20, 'EMI Collections', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(21, 'Sales Reports', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(22, 'View Only: Accounting', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(23, 'View Only: Treasury', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(24, 'dashboard.view.view', 'web', '2026-11-12 23:19:53', '2026-11-12 23:19:53'),
(25, 'dashboard.view.excel', 'web', '2026-11-12 23:19:53', '2026-11-12 23:19:53'),
(26, 'dashboard.view.pdf', 'web', '2026-11-12 23:19:53', '2026-11-12 23:19:53'),
(27, 'projects.manage.view', 'web', '2026-11-12 23:19:53', '2026-11-12 23:19:53'),
(28, 'projects.manage.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(29, 'projects.manage.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(30, 'units.manage.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(31, 'units.manage.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(32, 'units.manage.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(33, 'partners.manage.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(34, 'partners.manage.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(35, 'partners.manage.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(36, 'sales.create.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(37, 'sales.create.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(38, 'sales.view.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(39, 'sales.view.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(40, 'sales.view.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(41, 'sales.view.excel', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(42, 'sales.view.pdf', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(43, 'rate-revision.view.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(44, 'rate-revision.view.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(45, 'unit-matrix.view.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(46, 'unit-matrix.view.excel', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(47, 'unit-matrix.view.pdf', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(48, 'sales.return.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(49, 'sales.return.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(50, 'sales.exchange.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(51, 'sales.exchange.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(52, 'reports.sales_return.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(53, 'reports.sales_return.excel', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(54, 'reports.sales_return.pdf', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(55, 'reports.exchange.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(56, 'reports.exchange.excel', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(57, 'reports.exchange.pdf', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(58, 'cancellation.additional.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(59, 'cancellation.additional.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(60, 'cancellation.additional.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(61, 'customers.manage.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(62, 'customers.manage.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(63, 'customers.manage.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(64, 'customers.ledger.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(65, 'customers.ledger.excel', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(66, 'customers.ledger.pdf', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(67, 'emi.schedules.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(68, 'emi.schedules.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(69, 'emi.schedules.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(70, 'cheque.entry.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(71, 'cheque.entry.create', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(72, 'cheque.entry.edit', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(73, 'cheque.realize.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(74, 'reports.collections.view', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(75, 'reports.collections.excel', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(76, 'reports.collections.pdf', 'web', '2026-11-12 23:19:54', '2026-11-12 23:19:54'),
(77, 'contractors.manage.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(78, 'contractors.manage.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(79, 'contractors.manage.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(80, 'ra-bills.create.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(81, 'ra-bills.verify.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(82, 'ra-bills.payout.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(83, 'ra-bills.ledger.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(84, 'ra-bills.ledger.excel', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(85, 'ra-bills.ledger.pdf', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(86, 'brokers.manage.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(87, 'brokers.manage.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(88, 'brokers.manage.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(89, 'brokers.ledger.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(90, 'brokers.ledger.excel', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(91, 'brokers.ledger.pdf', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(92, 'brokers.payout.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(93, 'petty-cash.categories.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(94, 'petty-cash.categories.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(95, 'petty-cash.categories.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(96, 'petty-cash.contra.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(97, 'petty-cash.contra.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(98, 'petty-cash.daily.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(99, 'petty-cash.daily.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(100, 'petty-cash.daily.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(101, 'petty-cash.report.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(102, 'petty-cash.report.excel', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(103, 'petty-cash.report.pdf', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(104, 'dms.view.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(105, 'dms.upload.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(106, 'dms.categories.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(107, 'dms.categories.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(108, 'dms.categories.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(109, 'dms.types.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(110, 'dms.types.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(111, 'dms.types.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(112, 'site-expense.categories.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(113, 'site-expense.categories.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(114, 'site-expense.categories.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(115, 'site-expense.view.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(116, 'site-expense.view.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(117, 'site-expense.view.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(118, 'site-expense.payout.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(119, 'vendors.manage.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(120, 'vendors.manage.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(121, 'vendors.manage.edit', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(122, 'partners.contributions.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(123, 'partners.contributions.create', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(124, 'partners.ledger.view', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(125, 'partners.ledger.excel', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(126, 'partners.ledger.pdf', 'web', '2026-11-12 23:19:55', '2026-11-12 23:19:55'),
(127, 'partners.payout.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(128, 'reports.margins.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(129, 'reports.margins.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(130, 'reports.margins.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(131, 'company-bank.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(132, 'company-bank.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(133, 'company-bank.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(134, 'treasury.dashboard.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(135, 'treasury.dashboard.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(136, 'treasury.dashboard.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(137, 'treasury.report.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(138, 'treasury.report.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(139, 'treasury.report.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(140, 'vouchers.contra.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(141, 'vouchers.contra.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(142, 'coa.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(143, 'coa.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(144, 'coa.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(145, 'voucher-types.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(146, 'voucher-types.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(147, 'voucher-types.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(148, 'reports.trial_balance.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(149, 'reports.trial_balance.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(150, 'reports.trial_balance.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(151, 'reports.profit_loss.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(152, 'reports.profit_loss.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(153, 'reports.profit_loss.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(154, 'reports.balance_sheet.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(155, 'reports.balance_sheet.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(156, 'reports.balance_sheet.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(157, 'reports.gst.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(158, 'reports.gst.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(159, 'reports.gst.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(160, 'bank.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(161, 'bank.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(162, 'bank.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(163, 'loans.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(164, 'loans.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(165, 'loans.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(166, 'loans.reports.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(167, 'loans.reports.excel', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(168, 'loans.reports.pdf', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(169, 'employees.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(170, 'employees.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(171, 'employees.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(172, 'engineers.manage.view', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(173, 'engineers.manage.create', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(174, 'engineers.manage.edit', 'web', '2026-11-12 23:19:56', '2026-11-12 23:19:56'),
(175, 'cheque-statuses.manage.view', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(176, 'cheque-statuses.manage.create', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(177, 'cheque-statuses.manage.edit', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(178, 'floors.manage.view', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(179, 'floors.manage.create', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(180, 'floors.manage.edit', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(181, 'unit-types.manage.view', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(182, 'unit-types.manage.create', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(183, 'unit-types.manage.edit', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(184, 'users.manage.view', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(185, 'users.manage.create', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(186, 'users.manage.edit', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(187, 'roles.manage.view', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(188, 'roles.manage.create', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(189, 'roles.manage.edit', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(190, 'modules.manage.view', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(191, 'modules.manage.create', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(192, 'modules.manage.edit', 'web', '2026-11-12 23:19:57', '2026-11-12 23:19:57'),
(193, 'projects.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(194, 'units.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(195, 'partners.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(196, 'sales.view.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(197, 'sales.return.process', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(198, 'sales.exchange.process', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(199, 'customers.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(200, 'cheque.realize.process', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(201, 'contractors.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(202, 'ra-bills.verify.verify', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(203, 'ra-bills.verify.approve', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(204, 'ra-bills.payout.disburse', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(205, 'brokers.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(206, 'brokers.payout.disburse', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(207, 'petty-cash.categories.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(208, 'petty-cash.daily.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(209, 'dms.view.download', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(210, 'dms.view.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(211, 'dms.categories.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(212, 'dms.types.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(213, 'site-expense.categories.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(214, 'site-expense.view.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(215, 'site-expense.payout.disburse', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(216, 'vendors.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(217, 'partners.contributions.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(218, 'partners.payout.disburse', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(219, 'company-bank.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(220, 'coa.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(221, 'voucher-types.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(222, 'bank.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(223, 'loans.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(224, 'employees.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(225, 'engineers.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(226, 'cheque-statuses.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(227, 'floors.manage.delete', 'web', '2026-11-12 23:20:33', '2026-11-12 23:20:33'),
(228, 'unit-types.manage.delete', 'web', '2026-11-12 23:20:34', '2026-11-12 23:20:34'),
(229, 'users.manage.delete', 'web', '2026-11-12 23:20:34', '2026-11-12 23:20:34'),
(230, 'roles.manage.delete', 'web', '2026-11-12 23:20:34', '2026-11-12 23:20:34'),
(231, 'modules.manage.delete', 'web', '2026-11-12 23:20:34', '2026-11-12 23:20:34'),
(232, 'dashboard.view.create', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(233, 'dashboard.view.edit', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(234, 'dashboard.view.delete', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(235, 'projects.manage.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(236, 'projects.manage.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(237, 'units.manage.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(238, 'units.manage.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(239, 'partners.manage.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(240, 'partners.manage.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(241, 'sales.create.view', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(242, 'sales.create.delete', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(243, 'sales.create.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(244, 'sales.create.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(245, 'rate-revision.view.edit', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(246, 'rate-revision.view.delete', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(247, 'rate-revision.view.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(248, 'rate-revision.view.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(249, 'unit-matrix.view.create', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(250, 'unit-matrix.view.edit', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(251, 'unit-matrix.view.delete', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(252, 'sales.return.edit', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(253, 'sales.return.delete', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(254, 'sales.return.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(255, 'sales.return.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(256, 'sales.exchange.edit', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(257, 'sales.exchange.delete', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(258, 'sales.exchange.excel', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(259, 'sales.exchange.pdf', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(260, 'reports.sales_return.create', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(261, 'reports.sales_return.edit', 'web', '2026-11-13 00:51:23', '2026-11-13 00:51:23'),
(262, 'reports.sales_return.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(263, 'reports.exchange.create', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(264, 'reports.exchange.edit', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(265, 'reports.exchange.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(266, 'cancellation.additional.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(267, 'cancellation.additional.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(268, 'cancellation.additional.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(269, 'customers.manage.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(270, 'customers.manage.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(271, 'customers.ledger.create', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(272, 'customers.ledger.edit', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(273, 'customers.ledger.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(274, 'emi.schedules.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(275, 'emi.schedules.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(276, 'emi.schedules.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(277, 'cheque.entry.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(278, 'cheque.entry.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(279, 'cheque.entry.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(280, 'cheque.realize.create', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(281, 'cheque.realize.edit', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(282, 'cheque.realize.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(283, 'cheque.realize.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(284, 'cheque.realize.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(285, 'reports.collections.create', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(286, 'reports.collections.edit', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(287, 'reports.collections.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(288, 'contractors.manage.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(289, 'contractors.manage.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(290, 'ra-bills.create.view', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(291, 'ra-bills.create.edit', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(292, 'ra-bills.create.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(293, 'ra-bills.create.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(294, 'ra-bills.create.pdf', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(295, 'ra-bills.verify.create', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(296, 'ra-bills.verify.edit', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(297, 'ra-bills.verify.delete', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(298, 'ra-bills.verify.excel', 'web', '2026-11-13 00:51:24', '2026-11-13 00:51:24'),
(299, 'ra-bills.verify.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(300, 'ra-bills.payout.create', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(301, 'ra-bills.payout.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(302, 'ra-bills.payout.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(303, 'ra-bills.payout.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(304, 'ra-bills.payout.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(305, 'ra-bills.ledger.create', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(306, 'ra-bills.ledger.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(307, 'ra-bills.ledger.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(308, 'brokers.manage.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(309, 'brokers.manage.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(310, 'brokers.ledger.create', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(311, 'brokers.ledger.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(312, 'brokers.ledger.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(313, 'brokers.payout.create', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(314, 'brokers.payout.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(315, 'brokers.payout.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(316, 'brokers.payout.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(317, 'brokers.payout.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(318, 'petty-cash.categories.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(319, 'petty-cash.categories.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(320, 'petty-cash.contra.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(321, 'petty-cash.contra.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(322, 'petty-cash.contra.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(323, 'petty-cash.contra.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(324, 'petty-cash.daily.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(325, 'petty-cash.daily.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(326, 'petty-cash.report.create', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(327, 'petty-cash.report.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(328, 'petty-cash.report.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(329, 'dms.view.create', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(330, 'dms.view.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(331, 'dms.view.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(332, 'dms.view.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(333, 'dms.upload.view', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(334, 'dms.upload.edit', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(335, 'dms.upload.delete', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(336, 'dms.upload.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(337, 'dms.upload.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(338, 'dms.categories.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(339, 'dms.categories.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(340, 'dms.types.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(341, 'dms.types.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(342, 'site-expense.categories.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(343, 'site-expense.categories.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(344, 'site-expense.view.excel', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(345, 'site-expense.view.pdf', 'web', '2026-11-13 00:51:25', '2026-11-13 00:51:25'),
(346, 'site-expense.payout.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(347, 'site-expense.payout.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(348, 'site-expense.payout.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(349, 'site-expense.payout.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(350, 'site-expense.payout.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(351, 'vendors.manage.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(352, 'vendors.manage.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(353, 'partners.contributions.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(354, 'partners.contributions.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(355, 'partners.contributions.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(356, 'partners.ledger.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(357, 'partners.ledger.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(358, 'partners.ledger.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(359, 'partners.payout.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(360, 'partners.payout.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(361, 'partners.payout.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(362, 'partners.payout.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(363, 'partners.payout.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(364, 'reports.margins.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(365, 'reports.margins.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(366, 'reports.margins.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(367, 'company-bank.manage.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(368, 'company-bank.manage.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(369, 'treasury.dashboard.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(370, 'treasury.dashboard.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(371, 'treasury.dashboard.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(372, 'treasury.report.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(373, 'treasury.report.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(374, 'treasury.report.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(375, 'vouchers.contra.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(376, 'vouchers.contra.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(377, 'vouchers.contra.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(378, 'vouchers.contra.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(379, 'coa.manage.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(380, 'coa.manage.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(381, 'voucher-types.manage.excel', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(382, 'voucher-types.manage.pdf', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(383, 'reports.trial_balance.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(384, 'reports.trial_balance.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(385, 'reports.trial_balance.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(386, 'reports.profit_loss.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(387, 'reports.profit_loss.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(388, 'reports.profit_loss.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(389, 'reports.balance_sheet.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(390, 'reports.balance_sheet.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(391, 'reports.balance_sheet.delete', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(392, 'reports.gst.create', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(393, 'reports.gst.edit', 'web', '2026-11-13 00:51:26', '2026-11-13 00:51:26'),
(394, 'reports.gst.delete', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(395, 'bank.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(396, 'bank.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(397, 'loans.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(398, 'loans.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(399, 'loans.reports.create', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(400, 'loans.reports.edit', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(401, 'loans.reports.delete', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(402, 'employees.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(403, 'employees.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(404, 'engineers.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(405, 'engineers.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(406, 'cheque-statuses.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(407, 'cheque-statuses.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(408, 'floors.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(409, 'floors.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(410, 'unit-types.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(411, 'unit-types.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(412, 'users.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(413, 'users.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(414, 'roles.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(415, 'roles.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(416, 'modules.manage.excel', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(417, 'modules.manage.pdf', 'web', '2026-11-13 00:51:27', '2026-11-13 00:51:27'),
(418, 'dashboard.view', 'web', '2026-11-13 04:45:52', '2026-11-13 04:45:52'),
(419, 'activity_logs.manage.view', 'web', '2026-11-20 02:56:07', '2026-11-20 02:56:07'),
(420, 'payroll.financial_period.view', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(421, 'payroll.financial_period.create', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(422, 'payroll.financial_period.edit', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(423, 'payroll.financial_period.delete', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(424, 'payroll.leave_quota.view', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(425, 'payroll.leave_quota.create', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(426, 'payroll.leave_quota.edit', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(427, 'payroll.leave_quota.delete', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(428, 'payroll.salary_head.view', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(429, 'payroll.salary_head.create', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(430, 'payroll.salary_head.edit', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(431, 'payroll.salary_head.delete', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(432, 'payroll.salary.view', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(433, 'payroll.salary.create', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(434, 'payroll.salary.edit', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(435, 'payroll.salary.delete', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(436, 'payroll.salary.excel', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(437, 'payroll.salary.pdf', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(438, 'payroll.salary_report.view', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(439, 'payroll.salary_report.excel', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(440, 'payroll.salary_report.pdf', 'web', '2026-11-23 02:16:12', '2026-11-23 02:16:12'),
(441, 'payroll.components.view', 'web', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(442, 'payroll.components.create', 'web', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(443, 'payroll.components.edit', 'web', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(444, 'payroll.components.delete', 'web', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(445, 'office_rent.manage.view', 'web', '2026-11-25 02:40:31', '2026-11-25 02:40:31'),
(446, 'office_rent.manage.create', 'web', '2026-11-25 02:40:31', '2026-11-25 02:40:31'),
(447, 'office_rent.manage.edit', 'web', '2026-11-25 02:40:31', '2026-11-25 02:40:31'),
(448, 'office_rent.manage.delete', 'web', '2026-11-25 02:40:31', '2026-11-25 02:40:31'),
(449, 'office_rent.manage.excel', 'web', '2026-11-25 04:02:54', '2026-11-25 04:02:54'),
(450, 'payroll.financial_period.excel', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(451, 'payroll.financial_period.pdf', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(452, 'payroll.leave_quota.excel', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(453, 'payroll.leave_quota.pdf', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(454, 'payroll.salary_head.excel', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(455, 'payroll.salary_head.pdf', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(456, 'payroll.salary_report.create', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(457, 'payroll.salary_report.edit', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(458, 'payroll.salary_report.delete', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(459, 'payroll.components.excel', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(460, 'payroll.components.pdf', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17'),
(461, 'office_rent.manage.pdf', 'web', '2026-11-25 04:20:17', '2026-11-25 04:20:17');

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
-- Table structure for table `hindustansystem_petty_cash_boxes`
--

DROP TABLE IF EXISTS `hindustansystem_petty_cash_boxes`;
CREATE TABLE IF NOT EXISTS `hindustansystem_petty_cash_boxes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `box_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `box_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Site Petty Cash Box',
  `incharge_id` bigint UNSIGNED DEFAULT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `petty_cash_boxes_box_code_unique` (`box_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_petty_cash_boxes`
--

INSERT INTO `hindustansystem_petty_cash_boxes` (`id`, `project_id`, `box_code`, `box_name`, `incharge_id`, `current_balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'PC-TH-001', 'Site Petty Cash Box', 1, 9040.00, 'Active', '2026-11-10 01:14:13', '2026-11-10 04:06:23');

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
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `hindustansystem_petty_cash_transactions`
--

DROP TABLE IF EXISTS `hindustansystem_petty_cash_transactions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_petty_cash_transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `petty_cash_box_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_date` date DEFAULT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Cash',
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_in` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cash_out` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Posted',
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pct_voucher` (`voucher_id`),
  KEY `fk_pct_created_by` (`created_by`),
  KEY `idx_pct_box_date` (`petty_cash_box_id`,`transaction_date`),
  KEY `fk_hindustansystem_petty_cash_transactions_category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_petty_cash_transactions`
--

INSERT INTO `hindustansystem_petty_cash_transactions` (`id`, `petty_cash_box_id`, `category_id`, `voucher_id`, `transaction_date`, `voucher_number`, `transaction_type`, `reference_no`, `bill_date`, `narration`, `payment_mode`, `attachment_path`, `cash_in`, `cash_out`, `balance`, `created_by`, `status`, `is_historical`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 2, '2026-11-10', 'PCON-00001', 'Contra', 'ref-767', NULL, 'bnvfgh', 'Cash', NULL, 10000.00, 0.00, 10000.00, 1, 'Posted', 0, '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(2, 1, 23, 3, '2026-11-10', 'EXP-7086', 'Site Expense', '7676', '2026-11-10', 'Aircondition - huytytgjy', 'Cash', NULL, 0.00, 560.00, 10000.00, 1, 'approved', 1, '2026-11-10 01:15:22', '2026-11-10 01:15:22'),
(3, 1, 10, 4, '2026-11-10', 'EXP-3370', 'Site Expense', '354353', '2026-11-10', 'Electricity Charge - etedgff', 'Cash', NULL, 0.00, 960.00, 9040.00, 1, 'approved', 0, '2026-11-10 04:06:23', '2026-11-10 04:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_projects`
--

DROP TABLE IF EXISTS `hindustansystem_projects`;
CREATE TABLE IF NOT EXISTS `hindustansystem_projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_or_emirate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rera_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_floors` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_projects_system_id_code_unique` (`system_id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_projects`
--

INSERT INTO `hindustansystem_projects` (`id`, `system_id`, `name`, `code`, `location`, `city`, `state_or_emirate`, `country`, `rera_number`, `total_floors`, `start_date`, `expected_completion_date`, `status`, `description`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tabasco Hindustan', 'TH', 'MALL OF INDIA, KANHANGAD', 'Kasargode', 'Kerala', 'India', NULL, 0, NULL, NULL, 'ongoing', NULL, NULL, 1, '2026-10-15 01:11:22', '2026-10-15 01:11:22');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ra_bills`
--

DROP TABLE IF EXISTS `hindustansystem_ra_bills`;
CREATE TABLE IF NOT EXISTS `hindustansystem_ra_bills` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `invoice_month` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ra_bill_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contractor_id` bigint UNSIGNED DEFAULT NULL,
  `contractor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_date` date NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `additional_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `additional_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `verified_date` date DEFAULT NULL,
  `engineer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correction_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_approved_amount` decimal(15,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_amount` decimal(15,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ra_bills_ra_bill_number_unique` (`ra_bill_number`),
  KEY `ra_bills_system_id_index` (`system_id`),
  KEY `ra_bills_contractor_id_index` (`contractor_id`),
  KEY `ra_bills_project_id_index` (`project_id`),
  KEY `ra_bills_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ra_bills`
--

INSERT INTO `hindustansystem_ra_bills` (`id`, `system_id`, `invoice_month`, `ra_bill_number`, `contractor_id`, `contractor_name`, `project_id`, `unit_id`, `unit_name`, `submit_date`, `gross_amount`, `additional_amount`, `additional_percentage`, `verified_date`, `engineer_name`, `correction_amount`, `net_approved_amount`, `due_date`, `paid_amount`, `balance_amount`, `status`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'rq11', 12, 'MKH INFRASTRUCTURE', 1, NULL, NULL, '2026-11-16', 400000.00, 36000.00, 12.00, '2026-11-16', 'Anand Kumar (Senior Project Engineer)', 100000.00, 336000.00, '2026-11-19', 100000.00, 236000.00, 'partially_paid', 'dummy', 1, '2026-11-16 04:54:44', '2026-11-16 04:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ra_bill_payments`
--

DROP TABLE IF EXISTS `hindustansystem_ra_bill_payments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_ra_bill_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `ra_bill_id` bigint UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NEFT',
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ra_bill_payments_system_id_index` (`system_id`),
  KEY `ra_bill_payments_ra_bill_id_index` (`ra_bill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ra_bill_payments`
--

INSERT INTO `hindustansystem_ra_bill_payments` (`id`, `system_id`, `ra_bill_id`, `payment_date`, `paid_amount`, `payment_mode`, `company_bank_account_id`, `reference_no`, `voucher_id`, `status`, `is_historical`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-11-16', 100000.00, 'BANK_TRANSFER', 1, 'asdadsa', 6, 'paid', 0, NULL, 1, '2026-11-16 04:57:22', '2026-11-16 04:57:22');

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
  `is_allocated` tinyint(1) NOT NULL DEFAULT '0',
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `realization_status` enum('pending','cheque_in_hand','deposited','realized','bounced','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cheque_date` date DEFAULT NULL,
  `drawee_bank` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `realized_at` timestamp NULL DEFAULT NULL,
  `realized_by` bigint UNSIGNED DEFAULT NULL,
  `payment_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint UNSIGNED DEFAULT NULL,
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
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
  KEY `hindustansystem_receipts_bank_id_foreign` (`bank_id`),
  KEY `receipts_company_bank_account_id_foreign` (`company_bank_account_id`),
  KEY `receipts_realized_by_foreign` (`realized_by`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipts`
--

INSERT INTO `hindustansystem_receipts` (`id`, `sale_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `is_allocated`, `payment_mode`, `realization_status`, `cheque_date`, `drawee_bank`, `realized_at`, `realized_by`, `payment_type`, `reference_no`, `bank_id`, `company_bank_account_id`, `remarks`, `is_historical`, `created_by`, `partner_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2026-11-10', 1500000.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-11-09 18:30:00', 1, 'regular', '12345', 24, 1, 'Initial payment at sale creation', 0, 1, NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:50'),
(2, 2, 2, 1, 2, '2026-11-10', 1000000.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-11-12 18:30:00', 1, 'regular', '454654', 24, 1, 'Initial payment at sale creation', 0, 1, NULL, '2026-11-10 05:50:18', '2026-11-13 02:10:18'),
(3, 3, 1, 1, 10, '2026-11-16', 1500000.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', '1234567', 24, NULL, 'Initial payment at sale creation', 0, 1, NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(4, 4, 1, 1, 13, '2026-11-16', 1000000.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', '543546', 23, NULL, 'Initial payment at sale creation', 0, 1, NULL, '2026-11-16 03:06:12', '2026-11-16 03:06:12'),
(5, 5, 2, 1, 8, '2026-11-16', 500000.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', '6565', 24, NULL, 'Initial payment at sale creation', 0, 1, NULL, '2026-11-16 03:30:05', '2026-11-16 03:30:05');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_receipt_realization_logs`
--

DROP TABLE IF EXISTS `hindustansystem_receipt_realization_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_receipt_realization_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt_id` bigint UNSIGNED NOT NULL,
  `old_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receipt_realization_logs_changed_by_foreign` (`changed_by`),
  KEY `rec_real_logs_idx` (`receipt_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipt_realization_logs`
--

INSERT INTO `hindustansystem_receipt_realization_logs` (`id`, `receipt_id`, `old_status`, `new_status`, `remarks`, `changed_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'pending', 'realized', 'Initial payment at sale creation (Bank Ref: 43453)', 1, '2026-11-10 01:11:50', '2026-11-10 01:11:50'),
(2, 2, 'pending', 'realized', 'Initial payment at sale creation (Bank Ref: ref454)', 1, '2026-11-13 02:10:18', '2026-11-13 02:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_receipt_stores`
--

DROP TABLE IF EXISTS `hindustansystem_receipt_stores`;
CREATE TABLE IF NOT EXISTS `hindustansystem_receipt_stores` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `receipt_id` bigint UNSIGNED DEFAULT NULL,
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipt_stores`
--

INSERT INTO `hindustansystem_receipt_stores` (`id`, `receipt_id`, `company_bank_account_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `payment_mode`, `reference_no`, `remarks`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, '2026-11-10', 1500000.00, 'Cheque', '12345', 'Initial payment at sale creation (Bank Ref: 43453)', 'realized', 1, '2026-11-10 01:11:50', '2026-11-10 01:11:50'),
(2, 2, 1, 2, 1, NULL, '2026-11-10', 1000000.00, 'Cheque', '454654', 'Initial payment at sale creation (Bank Ref: ref454)', 'realized', 1, '2026-11-13 02:10:18', '2026-11-13 02:10:18');

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
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_roles`
--

INSERT INTO `hindustansystem_roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Owner', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(2, 'Accountant', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(3, 'Sales', 'web', '2026-07-06 03:53:38', '2026-07-06 03:53:38'),
(6, 'Super Admin', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42'),
(7, 'Admin', 'web', '2026-11-12 01:07:42', '2026-11-12 01:07:42');

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
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(181, 1),
(182, 1),
(183, 1),
(184, 1),
(185, 1),
(186, 1),
(187, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(192, 1),
(193, 1),
(194, 1),
(195, 1),
(196, 1),
(199, 1),
(201, 1),
(205, 1),
(207, 1),
(208, 1),
(210, 1),
(211, 1),
(212, 1),
(213, 1),
(214, 1),
(216, 1),
(217, 1),
(219, 1),
(220, 1),
(221, 1),
(222, 1),
(223, 1),
(224, 1),
(225, 1),
(226, 1),
(227, 1),
(228, 1),
(229, 1),
(230, 1),
(231, 1),
(232, 1),
(233, 1),
(234, 1),
(235, 1),
(236, 1),
(237, 1),
(238, 1),
(239, 1),
(240, 1),
(241, 1),
(242, 1),
(243, 1),
(244, 1),
(245, 1),
(246, 1),
(247, 1),
(248, 1),
(249, 1),
(250, 1),
(251, 1),
(252, 1),
(253, 1),
(254, 1),
(255, 1),
(256, 1),
(257, 1),
(258, 1),
(259, 1),
(260, 1),
(261, 1),
(262, 1),
(263, 1),
(264, 1),
(265, 1),
(266, 1),
(267, 1),
(268, 1),
(269, 1),
(270, 1),
(271, 1),
(272, 1),
(273, 1),
(274, 1),
(275, 1),
(276, 1),
(277, 1),
(278, 1),
(279, 1),
(280, 1),
(281, 1),
(282, 1),
(283, 1),
(284, 1),
(285, 1),
(286, 1),
(287, 1),
(288, 1),
(289, 1),
(290, 1),
(291, 1),
(292, 1),
(293, 1),
(294, 1),
(295, 1),
(296, 1),
(297, 1),
(298, 1),
(299, 1),
(300, 1),
(301, 1),
(302, 1),
(303, 1),
(304, 1),
(305, 1),
(306, 1),
(307, 1),
(308, 1),
(309, 1),
(310, 1),
(311, 1),
(312, 1),
(313, 1),
(314, 1),
(315, 1),
(316, 1),
(317, 1),
(318, 1),
(319, 1),
(320, 1),
(321, 1),
(322, 1),
(323, 1),
(324, 1),
(325, 1),
(326, 1),
(327, 1),
(328, 1),
(329, 1),
(330, 1),
(331, 1),
(332, 1),
(333, 1),
(334, 1),
(335, 1),
(336, 1),
(337, 1),
(338, 1),
(339, 1),
(340, 1),
(341, 1),
(342, 1),
(343, 1),
(344, 1),
(345, 1),
(346, 1),
(347, 1),
(348, 1),
(349, 1),
(350, 1),
(351, 1),
(352, 1),
(353, 1),
(354, 1),
(355, 1),
(356, 1),
(357, 1),
(358, 1),
(359, 1),
(360, 1),
(361, 1),
(362, 1),
(363, 1),
(364, 1),
(365, 1),
(366, 1),
(367, 1),
(368, 1),
(369, 1),
(370, 1),
(371, 1),
(372, 1),
(373, 1),
(374, 1),
(375, 1),
(376, 1),
(377, 1),
(378, 1),
(379, 1),
(380, 1),
(381, 1),
(382, 1),
(383, 1),
(384, 1),
(385, 1),
(386, 1),
(387, 1),
(388, 1),
(389, 1),
(390, 1),
(391, 1),
(392, 1),
(393, 1),
(394, 1),
(395, 1),
(396, 1),
(397, 1),
(398, 1),
(399, 1),
(400, 1),
(401, 1),
(402, 1),
(403, 1),
(404, 1),
(405, 1),
(406, 1),
(407, 1),
(408, 1),
(409, 1),
(410, 1),
(411, 1),
(412, 1),
(413, 1),
(414, 1),
(415, 1),
(416, 1),
(417, 1),
(420, 1),
(421, 1),
(422, 1),
(423, 1),
(424, 1),
(425, 1),
(426, 1),
(427, 1),
(428, 1),
(429, 1),
(430, 1),
(431, 1),
(432, 1),
(433, 1),
(434, 1),
(435, 1),
(436, 1),
(437, 1),
(438, 1),
(439, 1),
(440, 1),
(441, 1),
(442, 1),
(443, 1),
(444, 1),
(445, 1),
(446, 1),
(447, 1),
(448, 1),
(449, 1),
(450, 1),
(451, 1),
(452, 1),
(453, 1),
(454, 1),
(455, 1),
(456, 1),
(457, 1),
(458, 1),
(459, 1),
(460, 1),
(461, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(9, 2),
(13, 2),
(24, 3),
(36, 3),
(232, 3),
(241, 3),
(24, 6),
(25, 6),
(26, 6),
(27, 6),
(28, 6),
(29, 6),
(30, 6),
(31, 6),
(32, 6),
(33, 6),
(34, 6),
(35, 6),
(36, 6),
(37, 6),
(38, 6),
(39, 6),
(40, 6),
(41, 6),
(42, 6),
(43, 6),
(44, 6),
(45, 6),
(46, 6),
(47, 6),
(48, 6),
(49, 6),
(50, 6),
(51, 6),
(52, 6),
(53, 6),
(54, 6),
(55, 6),
(56, 6),
(57, 6),
(58, 6),
(59, 6),
(60, 6),
(61, 6),
(62, 6),
(63, 6),
(64, 6),
(65, 6),
(66, 6),
(67, 6),
(68, 6),
(69, 6),
(70, 6),
(71, 6),
(72, 6),
(73, 6),
(74, 6),
(75, 6),
(76, 6),
(77, 6),
(78, 6),
(79, 6),
(80, 6),
(81, 6),
(82, 6),
(83, 6),
(84, 6),
(85, 6),
(86, 6),
(87, 6),
(88, 6),
(89, 6),
(90, 6),
(91, 6),
(92, 6),
(93, 6),
(94, 6),
(95, 6),
(96, 6),
(97, 6),
(98, 6),
(99, 6),
(100, 6),
(101, 6),
(102, 6),
(103, 6),
(104, 6),
(105, 6),
(106, 6),
(107, 6),
(108, 6),
(109, 6),
(110, 6),
(111, 6),
(112, 6),
(113, 6),
(114, 6),
(115, 6),
(116, 6),
(117, 6),
(118, 6),
(119, 6),
(120, 6),
(121, 6),
(122, 6),
(123, 6),
(124, 6),
(125, 6),
(126, 6),
(127, 6),
(128, 6),
(129, 6),
(130, 6),
(131, 6),
(132, 6),
(133, 6),
(134, 6),
(135, 6),
(136, 6),
(137, 6),
(138, 6),
(139, 6),
(140, 6),
(141, 6),
(142, 6),
(143, 6),
(144, 6),
(145, 6),
(146, 6),
(147, 6),
(148, 6),
(149, 6),
(150, 6),
(151, 6),
(152, 6),
(153, 6),
(154, 6),
(155, 6),
(156, 6),
(157, 6),
(158, 6),
(159, 6),
(160, 6),
(161, 6),
(162, 6),
(163, 6),
(164, 6),
(165, 6),
(166, 6),
(167, 6),
(168, 6),
(169, 6),
(170, 6),
(171, 6),
(172, 6),
(173, 6),
(174, 6),
(175, 6),
(176, 6),
(177, 6),
(178, 6),
(179, 6),
(180, 6),
(181, 6),
(182, 6),
(183, 6),
(184, 6),
(185, 6),
(186, 6),
(187, 6),
(188, 6),
(189, 6),
(190, 6),
(191, 6),
(192, 6),
(193, 6),
(194, 6),
(195, 6),
(196, 6),
(199, 6),
(201, 6),
(205, 6),
(207, 6),
(208, 6),
(210, 6),
(211, 6),
(212, 6),
(213, 6),
(214, 6),
(216, 6),
(217, 6),
(219, 6),
(220, 6),
(221, 6),
(222, 6),
(223, 6),
(224, 6),
(225, 6),
(226, 6),
(227, 6),
(228, 6),
(230, 6),
(231, 6),
(232, 6),
(233, 6),
(234, 6),
(235, 6),
(236, 6),
(237, 6),
(238, 6),
(239, 6),
(240, 6),
(241, 6),
(242, 6),
(243, 6),
(244, 6),
(245, 6),
(246, 6),
(247, 6),
(248, 6),
(249, 6),
(250, 6),
(251, 6),
(252, 6),
(253, 6),
(254, 6),
(255, 6),
(256, 6),
(257, 6),
(258, 6),
(259, 6),
(260, 6),
(261, 6),
(262, 6),
(263, 6),
(264, 6),
(265, 6),
(266, 6),
(267, 6),
(268, 6),
(269, 6),
(270, 6),
(271, 6),
(272, 6),
(273, 6),
(274, 6),
(275, 6),
(276, 6),
(277, 6),
(278, 6),
(279, 6),
(280, 6),
(281, 6),
(282, 6),
(283, 6),
(284, 6),
(285, 6),
(286, 6),
(287, 6),
(288, 6),
(289, 6),
(290, 6),
(291, 6),
(292, 6),
(293, 6),
(294, 6),
(295, 6),
(296, 6),
(297, 6),
(298, 6),
(299, 6),
(300, 6),
(301, 6),
(302, 6),
(303, 6),
(304, 6),
(305, 6),
(306, 6),
(307, 6),
(308, 6),
(309, 6),
(310, 6),
(311, 6),
(312, 6),
(313, 6),
(314, 6),
(315, 6),
(316, 6),
(317, 6),
(318, 6),
(319, 6),
(320, 6),
(321, 6),
(322, 6),
(323, 6),
(324, 6),
(325, 6),
(326, 6),
(327, 6),
(328, 6),
(329, 6),
(330, 6),
(331, 6),
(332, 6),
(333, 6),
(334, 6),
(335, 6),
(336, 6),
(337, 6),
(338, 6),
(339, 6),
(340, 6),
(341, 6),
(342, 6),
(343, 6),
(344, 6),
(345, 6),
(346, 6),
(347, 6),
(348, 6),
(349, 6),
(350, 6),
(351, 6),
(352, 6),
(353, 6),
(354, 6),
(355, 6),
(356, 6),
(357, 6),
(358, 6),
(359, 6),
(360, 6),
(361, 6),
(362, 6),
(363, 6),
(364, 6),
(365, 6),
(366, 6),
(367, 6),
(368, 6),
(369, 6),
(370, 6),
(371, 6),
(372, 6),
(373, 6),
(374, 6),
(375, 6),
(376, 6),
(377, 6),
(378, 6),
(379, 6),
(380, 6),
(381, 6),
(382, 6),
(383, 6),
(384, 6),
(385, 6),
(386, 6),
(387, 6),
(388, 6),
(389, 6),
(390, 6),
(391, 6),
(392, 6),
(393, 6),
(394, 6),
(395, 6),
(396, 6),
(397, 6),
(398, 6),
(399, 6),
(400, 6),
(401, 6),
(402, 6),
(403, 6),
(404, 6),
(405, 6),
(406, 6),
(407, 6),
(408, 6),
(409, 6),
(410, 6),
(411, 6),
(412, 6),
(413, 6),
(414, 6),
(415, 6),
(416, 6),
(417, 6),
(24, 7),
(25, 7),
(26, 7),
(27, 7),
(28, 7),
(29, 7),
(30, 7),
(31, 7),
(32, 7),
(33, 7),
(34, 7),
(35, 7),
(36, 7),
(37, 7),
(38, 7),
(39, 7),
(40, 7),
(41, 7),
(42, 7),
(43, 7),
(44, 7),
(45, 7),
(46, 7),
(47, 7),
(48, 7),
(49, 7),
(50, 7),
(51, 7),
(52, 7),
(53, 7),
(54, 7),
(55, 7),
(56, 7),
(57, 7),
(58, 7),
(59, 7),
(60, 7),
(61, 7),
(62, 7),
(63, 7),
(64, 7),
(65, 7),
(66, 7),
(67, 7),
(68, 7),
(69, 7),
(70, 7),
(71, 7),
(72, 7),
(73, 7),
(74, 7),
(75, 7),
(76, 7),
(77, 7),
(78, 7),
(79, 7),
(80, 7),
(81, 7),
(82, 7),
(83, 7),
(84, 7),
(85, 7),
(86, 7),
(87, 7),
(88, 7),
(89, 7),
(90, 7),
(91, 7),
(92, 7),
(93, 7),
(94, 7),
(95, 7),
(96, 7),
(97, 7),
(98, 7),
(99, 7),
(100, 7),
(101, 7),
(102, 7),
(103, 7),
(104, 7),
(105, 7),
(106, 7),
(107, 7),
(108, 7),
(109, 7),
(110, 7),
(111, 7),
(112, 7),
(113, 7),
(114, 7),
(115, 7),
(116, 7),
(117, 7),
(118, 7),
(119, 7),
(120, 7),
(121, 7),
(122, 7),
(123, 7),
(124, 7),
(125, 7),
(126, 7),
(127, 7),
(128, 7),
(129, 7),
(130, 7),
(131, 7),
(132, 7),
(133, 7),
(134, 7),
(135, 7),
(136, 7),
(137, 7),
(138, 7),
(139, 7),
(140, 7),
(141, 7),
(142, 7),
(143, 7),
(144, 7),
(145, 7),
(146, 7),
(147, 7),
(148, 7),
(149, 7),
(150, 7),
(151, 7),
(152, 7),
(153, 7),
(154, 7),
(155, 7),
(156, 7),
(157, 7),
(158, 7),
(159, 7),
(160, 7),
(161, 7),
(162, 7),
(163, 7),
(164, 7),
(165, 7),
(166, 7),
(167, 7),
(168, 7),
(169, 7),
(170, 7),
(171, 7),
(172, 7),
(173, 7),
(174, 7),
(175, 7),
(176, 7),
(177, 7),
(178, 7),
(179, 7),
(180, 7),
(181, 7),
(182, 7),
(183, 7),
(184, 7),
(185, 7),
(186, 7);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_salary_components`
--

DROP TABLE IF EXISTS `hindustansystem_salary_components`;
CREATE TABLE IF NOT EXISTS `hindustansystem_salary_components` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('allowance','deduction') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'allowance',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_salary_components_system_id_index` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_salary_components`
--

INSERT INTO `hindustansystem_salary_components` (`id`, `system_id`, `name`, `type`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'House Rent Allowance (HRA)', 'allowance', 'Housing rent allowance eligible for tax exemption under Sec 10(13A)', 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(2, 1, 'Special Allowance', 'allowance', 'Flexible performance and retention allowance', 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(3, 1, 'Conveyance Allowance', 'allowance', 'Monthly travel and transit reimbursement', 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(4, 1, 'Provident Fund (EPF)', 'deduction', 'Statutory employee provident fund deduction (12%)', 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(5, 1, 'Professional Tax (PT)', 'deduction', 'State government professional tax deduction', 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(6, 1, 'Tax Deducted at Source (TDS)', 'deduction', 'Income tax withholding deducted as per slab', 'active', '2026-11-23 06:35:04', '2026-11-23 06:35:04'),
(17, 1, 'Basic salary', 'allowance', NULL, 'active', '2026-11-24 01:53:50', '2026-11-24 01:53:50');

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
  `final_discount` decimal(15,2) DEFAULT '0.00',
  `sale_date` date NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remaining_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `broker_involved` tinyint(1) NOT NULL DEFAULT '0',
  `initial_payment` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_mode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `additional_refund_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales`
--

INSERT INTO `hindustansystem_sales` (`id`, `sale_number`, `project_id`, `unit_id`, `customer_id`, `broker_id`, `agreement_date`, `registration_date`, `rate_per_sqft`, `sale_amount`, `gst_applicable`, `gst_type`, `base_amount`, `gst_percentage`, `gst_amount`, `total_amount`, `final_discount`, `sale_date`, `status`, `remaining_balance`, `broker_involved`, `initial_payment`, `payment_mode`, `reference_no`, `bank_id`, `remarks`, `payment_plan`, `emi_type`, `emi_installment_count`, `emi_frequency`, `first_installment_date`, `original_sale_id`, `is_resale`, `cancellation_reason`, `cancelled_at`, `cancellation_fee`, `additional_refund_amount`, `refund_amount`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TH-SH-G1-ANCH', 1, 1, 1, NULL, '2026-11-10', NULL, 22000.00, 7550000.00, 0, 'exclusive', 7550000.00, 18.00, 357500.00, 7907500.00, 0.00, '2026-11-10', 'active', 6407500.00, 0, 1500000.00, 'Cheque', '12345', 24, NULL, 'emi', 'equal', 12, 'monthly', '2026-12-10', NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'dfgfh', NULL, '2026-11-10 01:11:16', '2026-11-10 01:11:16', NULL),
(2, 'TH-SH-G2-ATHI', 1, 2, 2, 1, '2026-11-10', NULL, 22000.00, 30909000.00, 0, 'exclusive', 30909000.00, 18.00, 528000.00, 31437000.00, 0.00, '2026-11-10', 'active', 30437000.00, 0, 1000000.00, 'Cheque', '454654', 24, NULL, 'emi', 'equal', 10, 'monthly', '2026-12-10', NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'sfdhgfh', NULL, '2026-11-10 05:50:18', '2026-11-10 05:50:18', NULL),
(3, 'TH-SH-S2-ANCH', 1, 10, 1, 1, '2026-11-16', '2026-11-16', 17000.00, 18207000.00, 0, 'exclusive', 18207000.00, 18.00, 910350.00, 19117350.00, 0.00, '2026-11-16', 'active', 17617350.00, 0, 1500000.00, 'Cheque', '1234567', 24, NULL, 'emi', 'equal', 12, 'monthly', '2026-12-16', NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'yytytrytr', NULL, '2026-11-16 02:54:25', '2026-11-16 02:54:25', NULL),
(4, 'TH-SH-T1-ANCH', 1, 13, 1, NULL, '2026-11-16', '2026-11-16', 19000.00, 18544000.00, 0, 'exclusive', 18544000.00, 18.00, 927200.00, 19471200.00, 0.00, '2026-11-16', 'active', 18471200.00, 0, 1000000.00, 'Cheque', '543546', 23, NULL, 'emi', 'equal', 12, 'monthly', '2026-12-16', NULL, 0, NULL, NULL, 0.00, 0.00, 0.00, 'gfhf', NULL, '2026-11-16 03:06:11', '2026-11-16 03:06:11', NULL),
(5, 'TH-SH-F4-ATHI', 1, 8, 2, NULL, '2026-11-16', '2026-11-16', 19000.00, 23636000.00, 0, 'none', 23636000.00, NULL, 0.00, 23636000.00, 0.00, '2026-11-16', 'cancelled', 23136000.00, 0, 500000.00, 'Cheque', '6565', 24, NULL, 'emi', 'equal', 10, 'monthly', '2026-12-16', NULL, 0, 'Financial Issue', NULL, 0.00, 0.00, 400000.00, 'sfdsdf', NULL, '2026-11-16 03:30:04', '2026-11-16 04:52:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sales_executives`
--

DROP TABLE IF EXISTS `hindustansystem_sales_executives`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sales_executives` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_sales_executives_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `snapshot_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `performed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_sale_status_logs_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_sale_status_logs_performed_by_foreign` (`performed_by`)
) ;

--
-- Dumping data for table `hindustansystem_sale_status_logs`
--

INSERT INTO `hindustansystem_sale_status_logs` (`id`, `sale_id`, `from_status`, `to_status`, `event_type`, `reason`, `snapshot_data`, `performed_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'active', 'created', NULL, NULL, 1, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(2, 2, NULL, 'active', 'created', NULL, NULL, 1, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(3, 3, NULL, 'active', 'created', NULL, NULL, 1, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(4, 4, NULL, 'active', 'created', NULL, NULL, 1, '2026-11-16 03:06:11', '2026-11-16 03:06:11'),
(5, 5, NULL, 'active', 'created', NULL, NULL, 1, '2026-11-16 03:30:05', '2026-11-16 03:30:05'),
(6, 5, 'active', 'cancelled', 'cancelled', 'Financial Issue', '{\"old_sale\":{\"id\":5,\"sale_number\":\"TH-SH-F4-ATHI\",\"sale_date\":\"2026-11-16\",\"agreement_date\":\"2026-11-16\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":10,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-12-16\",\"rate_per_sqft\":19000,\"sale_amount\":23636000,\"gst_type\":\"none\",\"gst_amount\":0,\"base_amount\":23636000,\"total_amount\":23636000,\"remaining_balance\":23136000,\"total_paid\":500000},\"old_unit\":{\"id\":8,\"door_no\":\"F 4\",\"floor_name\":\"Floor 1\",\"unit_type_name\":\"Shop\",\"built_up_area\":1244,\"expected_sale_amount\":23636000},\"old_units\":[{\"id\":8,\"door_no\":\"F 4\",\"floor_name\":\"Floor 1\",\"unit_type_name\":\"Shop\",\"built_up_area\":1244,\"expected_sale_amount\":23636000}],\"customer\":{\"id\":2,\"name\":\"Athira\",\"phone\":\"6897695\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan\"},\"receipts\":[{\"id\":5,\"receipt_number\":null,\"receipt_date\":\"2026-11-16\",\"amount\":500000,\"payment_mode\":\"Cheque\",\"reference_no\":\"6565\",\"status\":\"posted\"}],\"installments\":[{\"id\":51,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-11-16\",\"amount\":500000,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":52,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-12-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":53,\"installment_no\":2,\"label\":\"EMI 2\",\"due_date\":\"2027-01-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":54,\"installment_no\":3,\"label\":\"EMI 3\",\"due_date\":\"2027-02-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":55,\"installment_no\":4,\"label\":\"EMI 4\",\"due_date\":\"2027-03-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":56,\"installment_no\":5,\"label\":\"EMI 5\",\"due_date\":\"2027-04-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":57,\"installment_no\":6,\"label\":\"EMI 6\",\"due_date\":\"2027-05-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":58,\"installment_no\":7,\"label\":\"EMI 7\",\"due_date\":\"2027-06-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":59,\"installment_no\":8,\"label\":\"EMI 8\",\"due_date\":\"2027-07-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":60,\"installment_no\":9,\"label\":\"EMI 9\",\"due_date\":\"2027-08-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":61,\"installment_no\":10,\"label\":\"EMI 10\",\"due_date\":\"2027-09-16\",\"amount\":2313600,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}]}', 1, '2026-11-16 04:51:41', '2026-11-16 04:51:41'),
(7, 5, 'cancelled', 'cancelled', 'refund_processed', 'Processed customer refund of ₹100,000.00 via Bank Transfer (NEFT / RTGS / IMPS) (Axis Bank 9752)', '{\"refund_amount\":100000,\"payment_mode\":\"Bank Transfer (NEFT \\/ RTGS \\/ IMPS)\",\"company_bank_account\":\"Axis Bank 9752\",\"journal_voucher_no\":\"JV-RF-2026-0005\",\"remarks\":\"Customer refund processed as per cancellation agreement.\"}', 1, '2026-11-16 04:52:10', '2026-11-16 04:52:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_units`
--

INSERT INTO `hindustansystem_sale_units` (`id`, `sale_id`, `unit_id`, `wing`, `rate_per_sqft`, `area_sqft`, `base_amount`, `gst_type`, `gst_percentage`, `gst_amount`, `line_total`, `brokerage_type`, `brokerage_value`, `brokerage_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 22000.00, 325.00, 7150000.00, 'exclusive', 5.00, 357500.00, 7507500.00, NULL, NULL, 0.00, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(2, 1, 60, NULL, 0.00, 1.00, 400000.00, 'none', 0.00, 0.00, 400000.00, NULL, NULL, 0.00, '2026-11-10 01:11:16', '2026-11-10 01:11:16'),
(3, 2, 2, NULL, 22000.00, 480.00, 10560000.00, 'exclusive', 5.00, 528000.00, 11088000.00, NULL, NULL, 0.00, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(4, 2, 6, NULL, 19000.00, 1071.00, 20349000.00, 'none', 0.00, 0.00, 20349000.00, NULL, NULL, 0.00, '2026-11-10 05:50:18', '2026-11-10 05:50:18'),
(5, 3, 10, NULL, 17000.00, 1071.00, 18207000.00, 'exclusive', 5.00, 910350.00, 19117350.00, NULL, NULL, 0.00, '2026-11-16 02:54:25', '2026-11-16 02:54:25'),
(6, 4, 13, NULL, 19000.00, 976.00, 18544000.00, 'exclusive', 5.00, 927200.00, 19471200.00, NULL, NULL, 0.00, '2026-11-16 03:06:11', '2026-11-16 03:06:11'),
(7, 5, 8, NULL, 19000.00, 1244.00, 23636000.00, 'none', 0.00, 0.00, 23636000.00, NULL, NULL, 0.00, '2026-11-16 03:30:04', '2026-11-16 03:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sessions`
--

DROP TABLE IF EXISTS `hindustansystem_sessions`;
CREATE TABLE IF NOT EXISTS `hindustansystem_sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_sessions_user_id_index` (`user_id`),
  KEY `hindustansystem_sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sessions`
--

INSERT INTO `hindustansystem_sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3yDAU0zWI1QDgw6IT2LYiBHlqzroo3mxO4F1g4WD', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoicEJKYlptQ2c4OVdlT0F5TkdXa1kwZWE0TlFDRHZUWFRvQWpzZHFzRiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795696245),
('4Q0VAox0Xhsuor8wOdk2nmgXgHYQbNkvNSffM8vp', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiTmRNRlN5ZU1jUTJvU3kwWlBDenFsemhGS3BVMkhTZTBONkdrU0dlUiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795686606),
('59zQGAsA4TpCtwxu9gmb74DKSbsi9e5jQoTvTppC', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiZjg4VGMxZ2s0a09YTzZBUFJoM1dqYXQ2d3B6Y2s0aDVzMzFhQ2NrWSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795685383),
('5ZUTUnhVJdykyrS9IItFl3n2A7qR1lhohf3xLKNv', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoibFhOaTNXMGNJWGg5VmRuOTQ3OG9zQXp3NlRLVElySjlzblh3MVh5YSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvMyIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795674497),
('742sIgF59E9tLEdwTdyZ1P6Ta2nf8ZkjZM2vmg9C', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiVlNjUUpjeThOSTdKcFpMMkI2b0F5WHNWbE5mckZ1S0hkYklGMk1mVCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlcyIsInJvdXRlIjoiZW1wbG95ZWVzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795675797),
('8ElPLuPfrIhxpNzxjtLEMMVi6vGGC90Jy8fLLk3w', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiV25EeFZiRXZleXBHdHpycElMaTdlS3F4eVI2NHZGSlRZUjlXWHFEZCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL2xlYXZlLXF1b3RhIiwicm91dGUiOiJwYXlyb2xsLmxlYXZlLXF1b3RhLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795695539),
('AtohmNfIPNhPmslHXWIOQ5Ijzr13AoZ3txisQSq4', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoieGVpTEN5UDdOT3MwazlwYnVYUDR6NFlLTnFPZHVnNDNsMms2TWVtcyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795604639),
('bPq5g27iA3GbxsIMDWVTOKktISMuNAnzSUMm6yrE', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiZ2JISVowb0NQaGphTTV4VXdobFYyUjUxdnFjV2JPUkMxcldJZ1owUyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795685557),
('c8RLINozhASXxlTwaViXfh4UgZzIW7TlmHbDVnfB', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoibFM2QlV5TnpoRHNXcDNvdkpTWE9ZTTc1NWZWNHR0cDdocTVtVUFVbyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795605079),
('dxNtal4O8N24CNSmeAeQWiju9m3HgNgINmhexUK9', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiSEZ2SWlxRlVlakI1QmczNXhCRkxKNWtlMzJEa0o5RVJzNjNUM3ZKTyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795672646),
('eB310W0YDghNVBQmwnE8Zf3GwTvhQn1IeRkKIgcg', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoicTBQeDBCc3BhaVUxelMyRDgyczRDM1BtQ0M4T3Jud1gwdk0wSTRRNCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlcyIsInJvdXRlIjoiZW1wbG95ZWVzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795589466),
('eNqhUrsyAigRhnT3Hxgqa4p2hwoKegBpu5b9cwRS', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiNTRzNWUxSmR2blBDS05yOTd2cm5iZFJUWjQ1RDdBSXpmbm1TUkcwZiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL2xlYXZlLXF1b3RhIiwicm91dGUiOiJwYXlyb2xsLmxlYXZlLXF1b3RhLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795690386),
('fIu8PVXyh19J0lk2usiyQCAjNBp359l8TazgB1bD', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoidm9EcERFWVVjcWRRU0xJcVRvUGptR0hKQTVseUpCdUQxRUJLQ0U4RSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvbGVhdmUtdHlwZXMiLCJyb3V0ZSI6ImVtcGxveWVlcy5sZWF2ZS10eXBlcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795677255),
('G0sWsmktI9w2p3rz2UIeAHJf1af9HWZX86c7CRb2', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiMHE4ZzBBNlhDWDJNcUw0eG05dDhGSnBwV0JmSklUUGpCSkV6MmUxUyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL3NhbGFyeSIsInJvdXRlIjoicGF5cm9sbC5zYWxhcnkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795693804),
('GObW8zngCgq9PvArZl2iyzeMTP9NCNdoZDwfe6GP', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiSk56QUpNbnRvT0hkMGFTeXd6UzZPR1c2bW1zam12RkRyanF0YzBEeSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795685174),
('GRrGlfp6iJV5nOZVwHwNpq74JbHX6gsFDws4pE2U', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiVENCaVZNUXpMcnh5U29TMTF0RDhMYU9PVUNtVmRCWEVodVVzd0ZXZyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL3NhbGFyeSIsInJvdXRlIjoicGF5cm9sbC5zYWxhcnkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795587565),
('HBeAgSwDKVT5aujNxUGU7W7oAvZOtXDpHmSs548I', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiRHE1QmRyYnFLcmM4MmtzTVBrNkNUSE1mOHhVNUlUU3JwNzFOMXR3MiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795600546),
('hMvDBGULP5dI4ejquDaSLxjP4GLXAnEVg1J4bDHm', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiZVF2eXBhN05zUzhTaTM2RkVFeW9Wb09vZ2U2QUI3Z1oxOXN5S3pLNyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795696813),
('IFceLllMLERSSCCfbS4FkHrqjBsAZUWmzRL1M6rY', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiQUNkRk9Fdm9jZW5UZk1iR21tRHNIYTFDeUVYRFVWeFJpZkFRUTFOSCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795597574),
('jiGZtxxBzz5ScucRMaJ49YzpA2TY7rlEaKJ9KjHm', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiTlhsUllMQkNDOWdoR0RrdDJPamh0UDJpNUh1dTI5Rk84VWRYcE81eSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795686811),
('kaKzNQvxp2eNBaK2y6XvtHfCUxs4dBVJ1Vs1fNYi', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoidFZWck9qQkhsV0RHaFpZVWh0TDBoMFNxM1BJYUZ0MllhV0xvdEdWMSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795594274),
('LxI0Fp18cYtAXv1Dqj1rgmN5U8EqoXseT8RpmTD2', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoidGVzSWdweEN6NEVoM052Umdha3JQeUx2d3hRemNiaGRkaDFXaFJNeiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL3NhbGFyeSIsInJvdXRlIjoicGF5cm9sbC5zYWxhcnkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795590365),
('mTD02gBwBrBhkfVFs2u0PlozvLuqGCrYtv0NmT9K', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJQZ2JaWUpISWNJMHVwdHNYbFhqR0dEY3pnSTM2cHFnVmYwbEZMMzJxIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYXlyb2xsXC9zYWxhcnktcmVwb3J0Iiwicm91dGUiOiJwYXlyb2xsLnNhbGFyeS1yZXBvcnQuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1795610971),
('mTTTsZkIbpUIF0ItJ1S7jIuzibseI8FJGGygxOIH', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiWTBaODh4emhvaTNmZ2xXM0l4ZGxHVHVSaTUzSTdIdUt2Z1E4YnFKSiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795672641),
('mZt1Ve7kjoKrKop40cs7xyXRMeHswWDQ7za1f13O', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiUzlTck9xeDdDRDlMYlUwSHduTzNPcHdqZVg2enRSWUNzOE55Q0ExUCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL3NhbGFyeS1yZXBvcnQiLCJyb3V0ZSI6InBheXJvbGwuc2FsYXJ5LXJlcG9ydC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795692068),
('nh0LSSHFYaZQTQj4SS7eZufu1snLFeDm0ZZuYM5s', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoibDVjZlY1U2tldDhXOEd3NllHN05BQllDb3YyYkhPaTl1TFg2eVRubSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795600921),
('o806lb3dXscG5zHTZ5zefKr5x1dYB8zdXk0ORHHz', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoicXF0aDJkWThNaFQ1QzJiQks5c3lYUFFhbHczaENUSHZGVm9XNGdrdSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlcyIsInJvdXRlIjoiZW1wbG95ZWVzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795588744),
('ou1eTaTElD1tyfag3jnuhnlezvX4QdotaXE9Lt2L', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiRkFaZWNiZVdwdmFXZVRmOG9PSWJ5TEh3NDN2dWNZd0VMdVl1SGNLTyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2NoYXJ0LW9mLWFjY291bnRzIiwicm91dGUiOiJjaGFydC1vZi1hY2NvdW50cy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795680904),
('OyW9Uoth813FKoYVMtJoEiINAwAZb3FgVOHY0vDG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIyVWRiTXg5STNESFhKUEcyd2pHQjVvSkZHZEJFR0pFQXhsOE15NHN0IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9vZmZpY2UtcmVudCIsInJvdXRlIjoib2ZmaWNlLXJlbnQuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1795605071),
('pQvvs6GqejKaTjhCSQr4Ke9FSe93GTggISFWWgTm', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJMTHNnQU1XMm5hWHEwMGx5OWhuM1hhamg2ODNZWHpkd2h0SzdPdjVtIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYXlyb2xsXC9zYWxhcnktcmVwb3J0Iiwicm91dGUiOiJwYXlyb2xsLnNhbGFyeS1yZXBvcnQuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1795524672),
('qjhmATAUP7GdHy36KQxS6TA66Ndf8Ez5My795Of2', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiWGJvaGpoMXpFUlg4Sk1oSUtLQ3M5dm5BVjI0WE41bVNCZTI3dENCNiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL3NhbGFyeSIsInJvdXRlIjoicGF5cm9sbC5zYWxhcnkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795588331),
('SgmTwkVgZ9pGnPfXRzohMq7va0nK2n5hRBMdBjxm', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiYzN3aEVvdldLV2pJZ01US2hYamlhbVpzaG1XMnVTRm5abmFPaGZXZCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL29mZmljZS1yZW50Iiwicm91dGUiOiJvZmZpY2UtcmVudC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795599214),
('tITgAkVym3jiIupxGCHgLaAoFDoxiC5fH059zbru', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiVzdlNWJOS29weTE0bEpYbHJsYU0xT0p3M3ZFWjU4aVFNTzdHTTd2QyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795687469),
('XeWQfM1AwvVyuFdKNJK1HWaamrFjkLlmawWBtlmx', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiYXp4Mkc2blVOa2Vsd1hZcHhJeUJvS0pwYnpRWTllanJmd2VuVDMxRCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2pvdXJuYWwtdm91Y2hlcnMiLCJyb3V0ZSI6ImpvdXJuYWwtdm91Y2hlcnMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795598344),
('XV7endbd28FXqu3OxSwb5moPQk8vCTUt4g324gp2', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiRUZRTGlhWENRSUlYeGduekhGYlJ5QUNaQWlZNHpvNk54SlNGMUo1ViIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlcyIsInJvdXRlIjoiZW1wbG95ZWVzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795683752),
('XvB4IhOdRhSQfuqGl5c41iBLaI6hjNitPsOyPtPN', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoid3pCN0FmTHJrVGE5eWlXbkxMVUZHcHg4NWtvQUcybXIzcm14NldhTiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL2xlYXZlLXF1b3RhIiwicm91dGUiOiJwYXlyb2xsLmxlYXZlLXF1b3RhLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795696804),
('XVHI0z8IuQnQTujUj7FCiADF22V1lwdMjjCps2W6', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiVkt3ZEFJT25qV0ZTSlVKMUlIZ2pyS3NGUXZ3UHlkQjFocm5DYU5VeiIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL2xlYXZlLXF1b3RhIiwicm91dGUiOiJwYXlyb2xsLmxlYXZlLXF1b3RhLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795696237),
('xyT0XVsiBEeYFpc3w1auYauF8RVR27lnmoIQVjpU', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoieHBpMG9ERHFubVNNdXhjTjVkVWdGZGVpRGxwSlJkamNxS21POGR4SCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL3NhbGFyeSIsInJvdXRlIjoicGF5cm9sbC5zYWxhcnkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795692721),
('y4VZ1Jwhzv2MKNOzYAUQnn950OrUXBm8eJ8tVmCI', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiMjhJT29QTHZXb3ZlRDZIOGlpQUg5aEJDaGpoNTNjNTFuT05QZzk4eCIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2VtcGxveWVlc1wvNiIsInJvdXRlIjoiZW1wbG95ZWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795690379),
('Y67WgXdWA8g2YPV1IXaAmZpQer1rBl1YXtN1i1il', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiemtHWm1SbEQ1ZmhGeTF3RkM0WFhBWHp6S01vWnRVZEdYSWlnOURUTSIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL2xlYXZlLXF1b3RhIiwicm91dGUiOiJwYXlyb2xsLmxlYXZlLXF1b3RhLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795690807),
('YQLxl3nuS2YMNQsozBZQAkQTCwRVhzKigacdOVlH', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJneUF0RkNLOWt3V0JydUI5Qk54djBNSXdGZjFkS1BKYXBxdDJ1WUE4IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYXlyb2xsXC9sZWF2ZS1xdW90YT9maW5hbmNpYWxfcGVyaW9kX2lkPTEiLCJyb3V0ZSI6InBheXJvbGwubGVhdmUtcXVvdGEuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1795696894),
('yWlPrfhiBuLklUAfYa9MGMgFYTHABUud3nN8yoVv', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiNk5yR2V6am5uMDhPTXoyQnF3eUl2c3RjT1k5T09xT2owaWhiZjB3RyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3BheXJvbGxcL2xlYXZlLXF1b3RhIiwicm91dGUiOiJwYXlyb2xsLmxlYXZlLXF1b3RhLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795688628);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_site_expenses`
--

DROP TABLE IF EXISTS `hindustansystem_site_expenses`;
CREATE TABLE IF NOT EXISTS `hindustansystem_site_expenses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `voucher_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `floor_id` bigint UNSIGNED DEFAULT NULL,
  `tower_block_tag` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_date` date NOT NULL,
  `payee_type` enum('registered','one_time') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registered',
  `payee_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `casual_payee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chart_of_account_id` bigint UNSIGNED DEFAULT NULL,
  `expense_category_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_category_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `cgst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sgst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `igst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_source_type` enum('bank','loan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank',
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `loan_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_reference_no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `payment_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_site_expenses_voucher_number_unique` (`voucher_number`),
  KEY `hindustansystem_site_expenses_project_id_foreign` (`project_id`),
  KEY `hindustansystem_site_expenses_floor_id_foreign` (`floor_id`),
  KEY `hindustansystem_site_expenses_payee_id_foreign` (`payee_id`),
  KEY `hindustansystem_site_expenses_chart_of_account_id_foreign` (`chart_of_account_id`),
  KEY `hindustansystem_site_expenses_company_bank_account_id_foreign` (`company_bank_account_id`),
  KEY `hindustansystem_site_expenses_loan_id_foreign` (`loan_id`),
  KEY `hindustansystem_site_expenses_created_by_foreign` (`created_by`),
  KEY `hindustansystem_site_expenses_vendor_id_foreign` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_site_expense_categories`
--

DROP TABLE IF EXISTS `hindustansystem_site_expense_categories`;
CREATE TABLE IF NOT EXISTS `hindustansystem_site_expense_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_of_account_id` bigint UNSIGNED DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_hindustansystem_site_expense_categories_project_id` (`project_id`),
  KEY `site_expense_categories_chart_of_account_id_foreign` (`chart_of_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_site_expense_categories`
--

INSERT INTO `hindustansystem_site_expense_categories` (`id`, `category_code`, `category_name`, `chart_of_account_id`, `project_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Site labour charge', 12, NULL, 'nil', 'active', '2026-09-25 01:07:08', '2026-10-01 03:24:41'),
(2, NULL, 'Labour charge of water proof', 12, NULL, NULL, 'active', '2026-09-26 00:53:12', '2026-10-01 03:24:41'),
(3, NULL, 'Site car parking installation expense', 10, NULL, NULL, 'active', '2026-10-02 02:33:51', '2026-10-02 02:33:51'),
(4, NULL, 'CCTV WORK', 10, NULL, 'CCTV WORK', 'active', '2026-10-03 02:54:15', '2026-10-03 02:54:15'),
(5, NULL, 'Site STP work', 10, NULL, 'Sewage treatment plant', 'active', '2026-10-07 04:23:06', '2026-10-07 04:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_site_expense_payments`
--

DROP TABLE IF EXISTS `hindustansystem_site_expense_payments`;
CREATE TABLE IF NOT EXISTS `hindustansystem_site_expense_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `site_expense_id` bigint UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bank Transfer',
  `payment_source_type` enum('bank','loan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank',
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `loan_id` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sep_expense_idx` (`site_expense_id`),
  KEY `sep_bank_idx` (`company_bank_account_id`),
  KEY `sep_loan_idx` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_systems`
--

DROP TABLE IF EXISTS `hindustansystem_systems`;
CREATE TABLE IF NOT EXISTS `hindustansystem_systems` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `vat_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `hindustansystem_unit_rate_logs`
--

DROP TABLE IF EXISTS `hindustansystem_unit_rate_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_unit_rate_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` bigint UNSIGNED NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `revision_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_change` decimal(15,2) DEFAULT NULL,
  `effective_from` date NOT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_unit_rate_logs_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_unit_rate_logs_changed_by_foreign` (`changed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_status_logs`
--

DROP TABLE IF EXISTS `hindustansystem_unit_status_logs`;
CREATE TABLE IF NOT EXISTS `hindustansystem_unit_status_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` bigint UNSIGNED NOT NULL,
  `from_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_unit_status_logs_unit_id_foreign` (`unit_id`),
  KEY `hindustansystem_unit_status_logs_changed_by_foreign` (`changed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_types`
--

DROP TABLE IF EXISTS `hindustansystem_unit_types`;
CREATE TABLE IF NOT EXISTS `hindustansystem_unit_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_id` bigint UNSIGNED DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_users_email_unique` (`email`),
  UNIQUE KEY `hindustansystem_users_employee_code_unique` (`employee_code`),
  UNIQUE KEY `hindustansystem_users_username_unique` (`username`),
  KEY `hindustansystem_users_system_id_foreign` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_users`
--

INSERT INTO `hindustansystem_users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `system_id`, `phone`, `employee_code`, `status`, `must_change_password`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Owner', 'owner@hindustan.com', 'owner', '2026-07-06 03:53:39', '$2y$12$Ojm4wMQDKmJnmvv66WBileXxQaP1IdtwdNvSVnaAJ5RJxKfYg6jPa', 'Vyq3yZ3o6Co9l584EioQ7JIxwy5qMefKhuNvok4Lj8izTO2HjGmnCcwNQaV9', '2026-07-06 03:53:39', '2026-11-13 04:18:51', 1, '+91 99999 99999', 'EMP-001', 'active', 0, NULL, NULL),
(2, 'admin', 'admin@hindustan.com', 'admin', '2026-07-06 03:53:40', '$2y$12$rVofIQZI2tIVy/jHVFLVPOyEJC2tVyvtMlUSsiLb.mLVUQRVh/W6m', NULL, '2026-07-06 03:53:40', '2026-07-09 03:35:53', 1, '+91 98765 00001', 'EMP-IN-ACC01', 'active', 0, NULL, NULL),
(3, 'Omar Accountant (UAE)', 'accountant.ae@hindustan.com', 'accountant', '2026-07-06 03:53:40', '$2y$12$.yyTbjelnCLyMkxEIxKo2esDcl/KbeQjmCeoDz/9NZ4wfsy/Dhj0y', NULL, '2026-07-06 03:53:40', '2026-11-13 06:35:13', 2, '+971 50 123 4567', 'EMP-AE-ACC01', 'active', 0, NULL, NULL),
(4, 'Amit', 'amit@hindustan.com', 'sales', '2026-07-06 03:53:41', '$2y$12$SbHL4XGcbKwECVWYigYq3OzS1Nr6BI0K2n7j351lkpo8BrIwepDbS', NULL, '2026-07-06 03:53:41', '2026-11-20 05:28:36', 1, '+91 98765 00002', 'EMP-IN-SAL01', 'active', 0, NULL, NULL),
(5, 'Super Admin', 'superadmin@hindustan.com', 'superadmin', '2026-07-06 03:53:41', '$2y$12$xElrrd0p6DWvKPkGsES/aeyxar2SJR0SVhGRb0/oYAbTxDCwVhx4e', NULL, '2026-07-06 03:53:41', '2026-11-20 05:29:31', 1, '+91 98765 00003', 'EMP-IN-SIT01', 'active', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_user_dashboard_layouts`
--

DROP TABLE IF EXISTS `hindustansystem_user_dashboard_layouts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_user_dashboard_layouts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `layout_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_user_dashboard_layouts_user_id_foreign` (`user_id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_vendors`
--

DROP TABLE IF EXISTS `hindustansystem_vendors`;
CREATE TABLE IF NOT EXISTS `hindustansystem_vendors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `vendor_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gstin` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linked_account_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_vendors_vendor_code_unique` (`vendor_code`),
  KEY `hindustansystem_vendors_linked_account_id_foreign` (`linked_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_vouchers`
--

DROP TABLE IF EXISTS `hindustansystem_vouchers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_vouchers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `voucher_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `reference_no` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `edited_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `is_historical` tinyint(1) NOT NULL DEFAULT '0',
  `reversal_of_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_vouchers_system_id_voucher_number_unique` (`system_id`,`voucher_number`),
  KEY `hindustansystem_vouchers_created_by_foreign` (`created_by`),
  KEY `hindustansystem_vouchers_edited_by_foreign` (`edited_by`),
  KEY `hindustansystem_vouchers_reversal_of_id_foreign` (`reversal_of_id`),
  KEY `fk_hindustansystem_vouchers_company_bank_account_id` (`company_bank_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_vouchers`
--

INSERT INTO `hindustansystem_vouchers` (`id`, `system_id`, `company_bank_account_id`, `voucher_number`, `type`, `date`, `narration`, `reference_no`, `created_by`, `edited_by`, `status`, `is_historical`, `reversal_of_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'PY-PTR-20261110-064335', 'Payment', '2026-11-10', 'Partner Payout / Drawing (Bank Transfer (NEFT / RTGS / IMPS) via Axis Bank 9752 (9752)) — Basheer (767676)', NULL, 1, NULL, 'Posted', 0, NULL, '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(2, 1, 1, 'PCON-00001', 'Contra', '2026-11-10', 'bnvfgh', 'ref-767', 1, NULL, 'Posted', 0, NULL, '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(3, 1, NULL, 'EXP-7086', 'Payment', '2026-11-10', 'Aircondition - huytytgjy', '7676', 1, NULL, 'Posted', 1, NULL, '2026-11-10 01:15:22', '2026-11-10 01:15:22'),
(4, 1, NULL, 'EXP-3370', 'Payment', '2026-11-10', 'Electricity Charge - etedgff', '354353', 1, NULL, 'Posted', 0, NULL, '2026-11-10 04:06:23', '2026-11-10 04:06:23'),
(5, 1, 1, 'PY-REF-20261116-102209-705', 'Payment', '2026-11-16', 'Customer Refund for cancellation on Unit F 4 (Athira) — Bank Transfer (NEFT / RTGS / IMPS) [Ref: 6565] (Customer refund processed as per cancellation agreement.)', '{\"allocations\":[{\"type\":\"refund\",\"target_id\":5,\"amount\":100000}],\"payment_mode\":\"Bank Transfer (NEFT \\/ RTGS \\/ IMPS)\",\"reference_no\":\"6565\",\"refund_date\":\"2026-11-16\",\"company_bank_account_id\":1,\"remarks\":\"Customer refund processed as per cancellation agreement.\"}', 1, NULL, 'Posted', 0, NULL, '2026-11-16 04:52:09', '2026-11-16 04:52:09'),
(6, 1, NULL, 'PV-00001', 'Payment', '2026-11-16', 'Staggered RA Bill Disbursement for #rq11 (MKH INFRASTRUCTURE)', 'asdadsa', 1, NULL, 'Posted', 0, NULL, '2026-11-16 04:57:22', '2026-11-16 04:57:22');

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
  `line_narration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_voucher_lines_voucher_id_foreign` (`voucher_id`),
  KEY `hindustansystem_voucher_lines_account_id_foreign` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_voucher_lines`
--

INSERT INTO `hindustansystem_voucher_lines` (`id`, `voucher_id`, `account_id`, `debit`, `credit`, `line_narration`, `created_at`, `updated_at`) VALUES
(1, 1, 20, 100000.00, 0.00, 'Partner Payout Release (Debit Drawing)', '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(2, 1, 4, 0.00, 100000.00, 'Credit Pay-From Account (Bank Transfer (NEFT / RTGS / IMPS) - Axis Bank 9752 (9752))', '2026-11-10 01:13:35', '2026-11-10 01:13:35'),
(3, 2, 17, 0.00, 10000.00, 'Cash withdrawal from bank', '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(4, 2, 32, 10000.00, 0.00, 'Petty cash receipt via contra', '2026-11-10 01:14:13', '2026-11-10 01:14:13'),
(5, 3, 38, 560.00, 0.00, 'Aircondition - huytytgjy', '2026-11-10 01:15:22', '2026-11-10 01:15:22'),
(6, 3, 32, 0.00, 560.00, 'Paid from Petty Cash Box (Cash)', '2026-11-10 01:15:22', '2026-11-10 01:15:22'),
(7, 4, 33, 960.00, 0.00, 'Electricity Charge - etedgff', '2026-11-10 04:06:23', '2026-11-10 04:06:23'),
(8, 4, 32, 0.00, 960.00, 'Paid from Petty Cash Box (Cash)', '2026-11-10 04:06:23', '2026-11-10 04:06:23'),
(9, 5, 23, 100000.00, 0.00, 'Customer Refund Payout to Athira', '2026-11-16 04:52:09', '2026-11-16 04:52:09'),
(10, 5, 4, 0.00, 100000.00, 'Axis Bank 9752 Payout', '2026-11-16 04:52:09', '2026-11-16 04:52:09'),
(11, 6, 18, 100000.00, 0.00, NULL, '2026-11-16 04:57:22', '2026-11-16 04:57:22'),
(12, 6, 29, 0.00, 100000.00, NULL, '2026-11-16 04:57:22', '2026-11-16 04:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_voucher_types`
--

DROP TABLE IF EXISTS `hindustansystem_voucher_types`;
CREATE TABLE IF NOT EXISTS `hindustansystem_voucher_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voucher_types_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_voucher_types`
--

INSERT INTO `hindustansystem_voucher_types` (`id`, `code`, `name`, `prefix`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'CONTRACTOR_RA_BILL', 'Contractor RA Bill Voucher', 'JV-RA', 'Generated on site engineer verified RA bill', 1, '2026-09-11 03:37:07', '2026-09-11 03:37:07'),
(2, 'CONTRACTOR_PAYMENT', 'Contractor Payment Release', 'JV-CP', 'Generated on contractor payment disbursement', 1, '2026-09-11 03:37:07', '2026-09-11 03:37:07'),
(15, 'SALES_BOOKING', 'Sales Booking Voucher', 'JV-SB', 'Generated on unit booking agreement', 1, '2026-09-15 02:43:39', '2026-09-15 02:43:39'),
(16, 'CUSTOMER_REFUND', 'Customer Refund Voucher', 'JV-RF', 'Generated on booking cancellation / refund issue', 1, '2026-09-15 03:13:42', '2026-09-15 03:13:42'),
(17, 'CUSTOMER_RECEIPT', 'Customer Payment Receipt', 'JV-CR', 'Generated on cheque realization / payment receipt', 1, '2026-09-15 03:24:11', '2026-09-15 03:24:11'),
(18, 'PETTY_CASH_CONTRA', 'Petty Cash Contra Transfer', 'JV-PC', 'Generated on bank cash withdrawal to petty cash box', 1, '2026-09-17 06:28:35', '2026-09-17 06:28:35'),
(19, 'AGENT_PAYMENT', 'Agent Payment Disbursement', 'JV-AP', 'Generated on paying commission to broker', 1, '2026-09-18 07:04:37', '2026-09-18 07:04:37'),
(20, 'PARTNER_CONTRIBUTION', 'Partner Capital Contribution Voucher', 'JV-PC', 'Generated on partner capital contribution deposit', 1, '2026-10-03 02:01:18', '2026-10-03 02:01:18'),
(25, 'SITE_EXPENSE_BILL', 'Site Expense Bill Voucher', 'JV-SE', 'Generated on site expense verification/approval (Credit Purchase to Sundry Creditors)', 1, '2026-10-21 03:11:12', '2026-10-21 03:11:12'),
(26, 'SITE_EXPENSE_PAYMENT', 'Site Expense Payment Release', 'JV-SEP', 'Generated on site expense payment disbursement (Debits Sundry Creditors, Credits Bank)', 1, '2026-10-21 03:21:58', '2026-10-21 03:21:58'),
(27, 'PARTNER_SHARE', 'Partner Share Payout Voucher', 'JV-PS', 'Generated on partner profit share / payout release', 1, '2026-10-23 03:52:24', '2026-10-23 03:52:24'),
(28, 'LOAN_EMI_PAYMENT', 'Bank Loan EMI Payment Voucher', 'JV-EMI', 'Generated on Bank Loan EMI / Interest repayment', 1, '2026-11-08 23:31:37', '2026-11-08 23:31:37'),
(30, 'SALARY_PAYMENT', 'Salary Payment', 'JV-SAL', 'Generated on employee salary disbursement', 1, '2026-11-25 00:52:15', '2026-11-25 00:52:15'),
(33, 'OFFICE_RENT', 'Office Rent Entry', 'JV-RENT', 'Generated on office rent expense booking and payment', 1, '2026-11-25 03:26:32', '2026-11-25 03:26:32'),
(35, 'OFFICE_RENT_PAYMENT', 'Office Rent Payment', 'JV-RENTP', 'Generated on settling unpaid office rent payable', 1, '2026-11-25 03:56:21', '2026-11-25 03:56:21');

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
-- Constraints for table `hindustansystem_categories`
--
ALTER TABLE `hindustansystem_categories`
  ADD CONSTRAINT `categories_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `hindustansystem_chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_hindustansystem_categories_project_id` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_categories_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_chart_of_accounts`
--
ALTER TABLE `hindustansystem_chart_of_accounts`
  ADD CONSTRAINT `hindustansystem_chart_of_accounts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_collection_reminders`
--
ALTER TABLE `hindustansystem_collection_reminders`
  ADD CONSTRAINT `collection_reminders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_reminders_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `hindustansystem_customer_installments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_reminders_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_dms_documents`
--
ALTER TABLE `hindustansystem_dms_documents`
  ADD CONSTRAINT `dms_documents_reference_project_id_foreign` FOREIGN KEY (`reference_project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `dms_documents_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dms_documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_dms_document_types`
--
ALTER TABLE `hindustansystem_dms_document_types`
  ADD CONSTRAINT `dms_document_types_category_fk` FOREIGN KEY (`dms_category_id`) REFERENCES `hindustansystem_dms_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `hindustansystem_employee_assets`
--
ALTER TABLE `hindustansystem_employee_assets`
  ADD CONSTRAINT `hindustansystem_employee_assets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_attendances`
--
ALTER TABLE `hindustansystem_employee_attendances`
  ADD CONSTRAINT `hindustansystem_employee_attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_documents`
--
ALTER TABLE `hindustansystem_employee_documents`
  ADD CONSTRAINT `hindustansystem_employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_emergency_contacts`
--
ALTER TABLE `hindustansystem_employee_emergency_contacts`
  ADD CONSTRAINT `hindustansystem_employee_emergency_contacts_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_experiences`
--
ALTER TABLE `hindustansystem_employee_experiences`
  ADD CONSTRAINT `hindustansystem_employee_experiences_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_leaves`
--
ALTER TABLE `hindustansystem_employee_leaves`
  ADD CONSTRAINT `hindustansystem_employee_leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_leave_quotas`
--
ALTER TABLE `hindustansystem_employee_leave_quotas`
  ADD CONSTRAINT `hindustansystem_employee_leave_quotas_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_overtimes`
--
ALTER TABLE `hindustansystem_employee_overtimes`
  ADD CONSTRAINT `hindustansystem_employee_overtimes_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_payrolls`
--
ALTER TABLE `hindustansystem_employee_payrolls`
  ADD CONSTRAINT `hindustansystem_employee_payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_salary_components`
--
ALTER TABLE `hindustansystem_employee_salary_components`
  ADD CONSTRAINT `hindustansystem_employee_salary_components_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_employee_salary_revisions`
--
ALTER TABLE `hindustansystem_employee_salary_revisions`
  ADD CONSTRAINT `hindustansystem_employee_salary_revisions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_engineers`
--
ALTER TABLE `hindustansystem_engineers`
  ADD CONSTRAINT `engineers_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_erp_sub_modules`
--
ALTER TABLE `hindustansystem_erp_sub_modules`
  ADD CONSTRAINT `hindustansystem_erp_sub_modules_erp_module_id_foreign` FOREIGN KEY (`erp_module_id`) REFERENCES `hindustansystem_erp_modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_floors`
--
ALTER TABLE `hindustansystem_floors`
  ADD CONSTRAINT `hindustansystem_floors_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_hindustan_units`
--
ALTER TABLE `hindustansystem_hindustan_units`
  ADD CONSTRAINT `hindustan_units_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `hindustansystem_floors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustan_units_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustan_units_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `hindustansystem_unit_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_journal_entries`
--
ALTER TABLE `hindustansystem_journal_entries`
  ADD CONSTRAINT `journal_entries_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `hindustansystem_chart_of_accounts` (`account_code`),
  ADD CONSTRAINT `journal_entries_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_journal_vouchers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_journal_vouchers`
--
ALTER TABLE `hindustansystem_journal_vouchers`
  ADD CONSTRAINT `journal_vouchers_voucher_type_id_foreign` FOREIGN KEY (`voucher_type_id`) REFERENCES `hindustansystem_voucher_types` (`id`);

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
-- Constraints for table `hindustansystem_loan_disbursals`
--
ALTER TABLE `hindustansystem_loan_disbursals`
  ADD CONSTRAINT `loan_disbursals_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_disbursals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_disbursals_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_disbursals_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loan_disbursals_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `pa_cbank_fk` FOREIGN KEY (`company_bank_account_id`) REFERENCES `hindustansystem_company_bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pa_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_partner_contributions`
--
ALTER TABLE `hindustansystem_partner_contributions`
  ADD CONSTRAINT `fk_prtc_bank` FOREIGN KEY (`company_bank_account_id`) REFERENCES `hindustansystem_company_bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prtc_partner` FOREIGN KEY (`partner_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prtc_payment_mode` FOREIGN KEY (`payment_mode_id`) REFERENCES `hindustansystem_payment_modes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_prtc_project` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_payroll_salaries`
--
ALTER TABLE `hindustansystem_payroll_salaries`
  ADD CONSTRAINT `hindustansystem_payroll_salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hindustansystem_employees` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_petty_cash_transactions`
--
ALTER TABLE `hindustansystem_petty_cash_transactions`
  ADD CONSTRAINT `fk_hindustansystem_petty_cash_transactions_category_id` FOREIGN KEY (`category_id`) REFERENCES `hindustansystem_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pct_box` FOREIGN KEY (`petty_cash_box_id`) REFERENCES `hindustansystem_petty_cash_boxes` (`id`),
  ADD CONSTRAINT `fk_pct_created_by` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`),
  ADD CONSTRAINT `fk_pct_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `hindustansystem_vouchers` (`id`);

--
-- Constraints for table `hindustansystem_projects`
--
ALTER TABLE `hindustansystem_projects`
  ADD CONSTRAINT `hindustansystem_projects_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_ra_bill_payments`
--
ALTER TABLE `hindustansystem_ra_bill_payments`
  ADD CONSTRAINT `ra_bill_payments_ra_bill_id_foreign` FOREIGN KEY (`ra_bill_id`) REFERENCES `hindustansystem_ra_bills` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `hindustansystem_receipts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipts_company_bank_account_id_foreign` FOREIGN KEY (`company_bank_account_id`) REFERENCES `hindustansystem_company_bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receipts_realized_by_foreign` FOREIGN KEY (`realized_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_receipt_realization_logs`
--
ALTER TABLE `hindustansystem_receipt_realization_logs`
  ADD CONSTRAINT `receipt_realization_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receipt_realization_logs_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `hindustansystem_receipts` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_site_expenses`
--
ALTER TABLE `hindustansystem_site_expenses`
  ADD CONSTRAINT `hindustansystem_site_expenses_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `hindustansystem_chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_site_expenses_company_bank_account_id_foreign` FOREIGN KEY (`company_bank_account_id`) REFERENCES `hindustansystem_company_bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_site_expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_site_expenses_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `hindustansystem_floors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_site_expenses_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_site_expenses_payee_id_foreign` FOREIGN KEY (`payee_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_site_expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_site_expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `hindustansystem_vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_site_expense_categories`
--
ALTER TABLE `hindustansystem_site_expense_categories`
  ADD CONSTRAINT `fk_hindustansystem_site_expense_categories_project_id` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `site_expense_categories_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `hindustansystem_chart_of_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_site_expense_payments`
--
ALTER TABLE `hindustansystem_site_expense_payments`
  ADD CONSTRAINT `hindustansystem_site_expense_payments_site_expense_id_foreign` FOREIGN KEY (`site_expense_id`) REFERENCES `hindustansystem_site_expenses` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_vendors`
--
ALTER TABLE `hindustansystem_vendors`
  ADD CONSTRAINT `hindustansystem_vendors_linked_account_id_foreign` FOREIGN KEY (`linked_account_id`) REFERENCES `hindustansystem_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hindustansystem_vouchers`
--
ALTER TABLE `hindustansystem_vouchers`
  ADD CONSTRAINT `fk_hindustansystem_vouchers_company_bank_account_id` FOREIGN KEY (`company_bank_account_id`) REFERENCES `hindustansystem_company_bank_accounts` (`id`) ON DELETE SET NULL,
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
