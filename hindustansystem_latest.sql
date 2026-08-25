-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 25, 2026 at 10:40 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(33, 1, 'BRK-BF4F8D', 'test Commission Payable Account', 'liability', NULL, 1, '2026-07-21 04:28:26', '2026-07-21 04:28:26'),
(34, 1, 'CUST-REC-10', 'Jerin Jose (Receivable)', 'Liability', NULL, 1, '2026-07-28 01:40:32', '2026-07-28 01:40:32'),
(35, 1, 'BRK-0921CF', 'Praveen Commission Payable Account', 'liability', NULL, 1, '2026-07-28 05:34:58', '2026-07-28 05:34:58'),
(36, 1, 'CUST-REC-11', 'JOE (Receivable)', 'Liability', NULL, 1, '2026-07-28 23:43:39', '2026-07-28 23:43:39'),
(37, 1, 'CUST-REC-12', 'Vimal (Receivable)', 'Liability', NULL, 1, '2026-07-29 05:27:51', '2026-07-29 05:27:51'),
(38, 1, 'CUST-REC-13', 'Sinaj (Receivable)', 'Liability', NULL, 1, '2026-07-29 05:27:51', '2026-07-29 05:27:51'),
(39, 1, 'CUST-REC-1', 'APARNA (Receivable)', 'Liability', NULL, 1, '2026-07-30 07:48:14', '2026-07-30 07:48:14'),
(40, 1, 'CUST-REC-2', 'ABDUL RAHIMAN (Receivable)', 'Liability', NULL, 1, '2026-07-30 07:48:15', '2026-07-30 07:48:15'),
(41, 1, 'CUST-REC-3', 'ARUN (Receivable)', 'Liability', NULL, 1, '2026-07-30 07:48:15', '2026-07-30 07:48:15'),
(42, 1, 'CUST-REC-6', 'ARAVINDAKASHAN (Receivable)', 'Liability', NULL, 1, '2026-07-30 07:48:15', '2026-07-30 07:48:15'),
(43, 1, 'CUST-REC-7', 'NAJMA AZEEZ (Receivable)', 'Liability', NULL, 1, '2026-07-30 07:48:15', '2026-07-30 07:48:15'),
(44, 1, 'BANK-KARNATAK-16', 'KARNATAKA BANK Account', 'Asset', NULL, 1, '2026-07-30 07:57:49', '2026-07-30 07:57:49'),
(45, 1, 'LOAN-LN56234', 'Loan Account - UNION BANK (LN56234)', 'liability', NULL, 1, '2026-07-31 00:42:01', '2026-07-31 00:42:01'),
(46, 1, 'LOAN-LN8097', 'Loan Account - PNB (LN8097)', 'liability', NULL, 1, '2026-07-31 00:43:06', '2026-07-31 00:43:06'),
(47, 1, 'LOAN-LN45345', 'Loan Account - ICICI BANK (LN45345)', 'liability', NULL, 1, '2026-07-31 00:44:09', '2026-07-31 00:44:09'),
(54, 1, 'LOAN-LN324', 'Loan Account - IUB (LN324)', 'liability', NULL, 1, '2026-07-31 00:53:14', '2026-07-31 00:53:14'),
(55, 1, 'LOAN-LN5687', 'Loan Account - KARNATAKA BANK (LN5687)', 'liability', NULL, 1, '2026-07-31 00:54:29', '2026-07-31 00:54:29'),
(61, 1, 'BRK-384B7A', 'Apex Realty Broker Commission Payable Account', 'liability', NULL, 1, '2026-07-31 01:59:48', '2026-07-31 01:59:48'),
(63, 1, 'SUP-ACC-0006', 'Luxstruct Builders PVT LTD (Payable)', 'Liability', NULL, 1, '2026-07-31 02:18:56', '2026-07-31 02:18:56'),
(64, 1, 'SUP-ACC-0007', 'DATA DUMMY (Payable)', 'Liability', NULL, 1, '2026-07-31 02:19:42', '2026-07-31 02:19:42'),
(65, 1, 'BANK-FEDERAL-17', 'Federal Account', 'Asset', NULL, 1, '2026-07-31 06:28:49', '2026-07-31 06:28:49'),
(66, 1, 'BANK-ICICIBAN-18', 'ICICI Bank Account', 'Asset', NULL, 1, '2026-07-31 06:28:49', '2026-07-31 06:28:49'),
(67, 1, 'BANK-SBI-19', 'SBI Account', 'Asset', NULL, 1, '2026-07-31 06:28:49', '2026-07-31 06:28:49'),
(68, 1, 'BANK-FEDERAL-20', 'Federal Account', 'Asset', NULL, 1, '2026-07-31 07:11:42', '2026-07-31 07:11:42'),
(69, 1, 'BANK-ICICIBAN-21', 'ICICI Bank Account', 'Asset', NULL, 1, '2026-07-31 07:11:42', '2026-07-31 07:11:42'),
(70, 1, 'BANK-SBI-22', 'SBI Account', 'Asset', NULL, 1, '2026-07-31 07:11:42', '2026-07-31 07:11:42'),
(71, 1, 'SUP-ACC-0008', 'AZUS (Payable)', 'Liability', NULL, 1, '2026-08-14 04:20:13', '2026-08-14 04:20:13'),
(72, 1, 'CUST-REC-14', 'Manu (Receivable)', 'Liability', NULL, 1, '2026-08-19 00:00:01', '2026-08-19 00:00:01'),
(73, 1, 'CUST-REC-15', 'DILSHAD (Receivable)', 'Liability', NULL, 1, '2026-08-19 00:00:01', '2026-08-19 00:00:01'),
(74, 1, 'CUST-REC-16', 'JABIR (Receivable)', 'Liability', NULL, 1, '2026-08-19 00:00:01', '2026-08-19 00:00:01'),
(76, 1, 'BK-7585', 'Karnataka Bank', 'Asset', NULL, 1, '2026-08-24 06:53:41', '2026-08-24 06:53:41'),
(77, 1, 'BK-2193', 'HDFC Bank Bank', 'Asset', NULL, 1, '2026-08-24 07:19:23', '2026-08-24 07:19:23');

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
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_activity_logs_user_id_foreign` (`user_id`),
  KEY `hindustansystem_activity_logs_system_id_foreign` (`system_id`),
  KEY `hindustansystem_activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(23, 1, 1, 'broker.payout', NULL, NULL, 'Bulk commission payout across 2 deal(s) to broker \'Metro Homes Agents\'.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 01:23:30'),
(24, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 87, 'Unit G 2 transitioned from \'available\' to \'blocked\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 01:57:21'),
(25, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 87, 'Unit G 2 transitioned from \'blocked\' to \'available\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 02:11:13'),
(26, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 48, 'Unit G 8 transitioned from \'available\' to \'blocked\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 02:12:58'),
(27, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 48, 'Unit G 8 transitioned from \'blocked\' to \'available\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 02:13:07'),
(28, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 93, 'Unit SI F transitioned from \'available\' to \'blocked\'. Reason: N/A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 02:48:29'),
(29, 1, 1, 'broker.payout', NULL, NULL, 'Commission payout to broker \'Apex Realty Brokers\' for Sale #HEV-01-APART-10A1-JERI.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 04:35:56'),
(30, 1, 1, 'broker.updated', NULL, NULL, 'Updated broker \'Apex Realty Brokers\' details. Commission changed from 2% to 3.5%.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 05:27:05'),
(31, 1, 1, 'broker.created', NULL, NULL, 'Registered new broker \'Praveen\' with default commission of 5%. Linked ledger account: BRK-0921CF.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 05:34:58'),
(32, 1, 1, 'broker.payout', NULL, NULL, 'Bulk commission payout across 1 deal(s) to broker \'Praveen\'.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 05:43:53'),
(33, 1, 1, 'broker.updated', NULL, NULL, 'Updated broker \'Praveen\' details. Commission changed from 5% to 2.5%.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 05:50:49'),
(34, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 318, 'Unit T 15 transitioned from \'available\' to \'blocked\'. Reason: N/A', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 01:05:57'),
(35, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 320, 'Unit T 17 transitioned from \'available\' to \'blocked\'. Reason: N/A', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 01:06:18'),
(36, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 365, 'Unit B1 D1254 transitioned from \'available\' to \'blocked\'. Reason: N/A', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-31 01:56:01'),
(37, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 365, 'Unit B1 D1254 transitioned from \'blocked\' to \'booked\'. Reason: N/A', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-31 01:56:04'),
(38, 1, 1, 'unit.status_changed', 'App\\Models\\Unit', 365, 'Unit B1 D1254 transitioned from \'booked\' to \'available\'. Reason: N/A', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-31 01:56:08'),
(39, 1, 1, 'broker.created', NULL, NULL, 'Registered new broker \'Apex Realty Broker\' with default commission of 2%. Linked ledger account: BRK-384B7A.', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-31 01:59:48'),
(40, 1, 1, 'broker.updated', NULL, NULL, 'Updated broker \'Apex Realty Broker\' details. Commission changed from 2% to 2.5%.', '59.88.142.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-31 01:59:56');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_approval_rules`
--

INSERT INTO `hindustansystem_approval_rules` (`id`, `module`, `min_role`, `threshold_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'discount', 'Owner', 100000.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(2, 'discount', 'Owner', 100000.00, 1, '2026-08-19 00:55:58', '2026-08-19 00:55:58');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_banks`
--

INSERT INTO `hindustansystem_banks` (`id`, `bank_name`, `ifsc_code`, `status`, `created_at`, `updated_at`) VALUES
(20, 'Federal', 'FB53767668676', 'active', '2026-07-31 07:11:42', '2026-07-31 07:11:42'),
(21, 'ICICI Bank', 'ICICI2333444', 'active', '2026-07-31 07:11:42', '2026-07-31 07:11:42'),
(22, 'SBI', 'SBIN0001234', 'active', '2026-07-31 07:11:42', '2026-07-31 07:11:42');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_bills`
--

INSERT INTO `hindustansystem_bills` (`id`, `system_id`, `payee_id`, `project_id`, `bill_number`, `bill_type`, `payment_terms`, `place_of_supply`, `expense_head`, `bill_file`, `bill_amount`, `gst_rate`, `gst_amount`, `final_amount`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(5, 1, 9, 1, 'BR/6575', 'Material Supply', '30 Days', 'Tamil Nadu (33)', 'Cement', 'bills/VtvEs4ULwN52lSAploML2dvtJsiH43x3Y8ndpB4V.png', 500000.00, 5.00, 25000.00, 525000.00, 'approved_unpaid', NULL, NULL, '2026-08-14 04:21:05', '2026-08-14 04:21:05');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `hindustansystem_cache`
--

INSERT INTO `hindustansystem_cache` (`key`, `value`, `expiration`) VALUES
('hindustanerp-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:13:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"vouchers.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"expenses.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"expenses.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"collections.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"sales.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"sales.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:22:\"sales.discount.request\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"units.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"units.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"projects.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"projects.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:17:\"units.rate.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Owner\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"Sales\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"Site\";s:1:\"c\";s:3:\"web\";}}}', 1787719847);

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
-- Table structure for table `hindustansystem_chart_of_accounts`
--

DROP TABLE IF EXISTS `hindustansystem_chart_of_accounts`;
CREATE TABLE IF NOT EXISTS `hindustansystem_chart_of_accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('ASSET','LIABILITY','REVENUE','EXPENSE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_chart_of_accounts_account_code_unique` (`account_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cheque_statuses`
--

DROP TABLE IF EXISTS `hindustansystem_cheque_statuses`;
CREATE TABLE IF NOT EXISTS `hindustansystem_cheque_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_cheque_statuses_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_cheque_statuses`
--

INSERT INTO `hindustansystem_cheque_statuses` (`id`, `name`, `color_code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pending', 'amber-500', 1, NULL, NULL),
(2, 'Realized', 'emerald-500', 1, NULL, NULL),
(3, 'Bounced', 'rose-500', 1, NULL, NULL),
(4, 'Cancelled', 'slate-500', 1, NULL, NULL),
(5, 'Deposited', 'Blue', 1, '2026-08-14 07:23:32', '2026-08-14 07:23:32'),
(6, 'Cheque in Hand', 'Orange', 1, '2026-08-16 23:53:55', '2026-08-16 23:53:55'),
(7, 'In Clearing', 'Purple', 1, '2026-08-16 23:55:21', '2026-08-16 23:55:21');

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
  `reminder_level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `response` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_collection_reminders_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_collection_reminders_sale_id_foreign` (`sale_id`),
  KEY `hindustansystem_collection_reminders_installment_id_foreign` (`installment_id`)
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Karnataka', 'Hindustan', '22556565', 'Savings', 'KAR700065', 'MG Road', 'HGF8787', '69859657', 0.00, 26402800.00, 'kar879845', 'active', 1, '2026-08-14 03:05:56', '2026-08-25 00:56:21'),
(3, 'HDFC Bank', 'hindustan', '6455756756', 'Savings', 'HDFC7574546', 'MG Road', 'HGF8787', '69859657', 0.00, 58931250.00, 'hdfc3454', 'active', 0, '2026-08-14 05:53:32', '2026-08-25 00:47:28'),
(4, 'RTYUTRURTUTRUTRU', 'DSGSGTRUTRUTRU', 'EWTEWT436436436', 'Current', 'DGDSG5747547TRYTRYTRYTR', 'DGBXDSGDSG', NULL, NULL, 0.00, 0.00, 'DSGDSG', 'active', 0, '2026-08-20 02:12:12', '2026-08-20 02:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customers`
--

DROP TABLE IF EXISTS `hindustansystem_customers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customers`
--

INSERT INTO `hindustansystem_customers` (`id`, `name`, `email`, `phone`, `avatar_url`, `created_at`, `updated_at`, `address`, `id_proof_type`, `id_proof_number`, `system`, `is_active`) VALUES
(2, 'ASHRAF', 'tabascohindusthan@gmail.com', '09048393993', NULL, '2026-08-04 01:40:11', '2026-08-04 01:40:11', NULL, NULL, NULL, 'india', 1),
(3, 'SHAMEER', 'jhancydinuvj@gmail.com', '09048393993', NULL, '2026-08-04 01:52:08', '2026-08-04 01:52:08', NULL, NULL, NULL, 'india', 1),
(7, 'INDULEKHA', 'induleka12@gmail.com', '09048393993', NULL, '2026-08-05 05:02:36', '2026-08-05 05:02:36', NULL, NULL, NULL, 'india', 1),
(8, 'RASNA', 'rasna58@gmail.com', NULL, NULL, '2026-08-05 05:12:49', '2026-08-05 05:12:49', NULL, NULL, NULL, 'india', 1),
(9, 'Anchumol', 'anchumol366@gmail.com', '565756756', NULL, '2026-08-13 00:30:46', '2026-08-13 00:30:46', NULL, NULL, NULL, 'india', 1),
(10, 'Jolly Joy', 'jolly@gmail.com', '64754654', NULL, '2026-08-13 00:36:43', '2026-08-13 00:36:43', NULL, NULL, NULL, 'india', 1),
(11, 'ARAVINDAKSHAN', 'aravind@gmail.com', '6576576786', NULL, '2026-08-14 05:02:52', '2026-08-14 05:02:52', NULL, NULL, NULL, 'india', 1),
(12, 'Arun', 'arun@gmail.com', '676768678', NULL, '2026-08-17 01:12:02', '2026-08-17 01:12:02', NULL, NULL, NULL, 'india', 1),
(13, 'Saranya', 'saranya@gmail.com', '6546765757', NULL, '2026-08-17 03:27:38', '2026-08-17 03:27:38', NULL, NULL, NULL, 'india', 1),
(14, 'Manu', 'manu@gmail.com', '678657575', NULL, '2026-08-17 06:41:53', '2026-08-17 06:41:53', NULL, NULL, NULL, 'india', 1),
(15, 'DILSHAD', 'dilshad@gmail.com', '56765786', NULL, '2026-08-18 01:36:14', '2026-08-18 01:36:14', NULL, NULL, NULL, 'india', 1),
(16, 'JABIR', 'jabir@gmail.com', '65r7657567', NULL, '2026-08-18 02:05:30', '2026-08-18 02:05:30', NULL, NULL, NULL, 'india', 1),
(20, 'Najmaz', 'najma@gmail.com', '878787745', NULL, '2026-08-20 00:41:07', '2026-08-20 00:41:07', NULL, NULL, NULL, 'india', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customer_installments`
--

INSERT INTO `hindustansystem_customer_installments` (`id`, `sale_id`, `installment_no`, `label`, `due_date`, `amount`, `paid_amount`, `status`, `schedule_type`, `rescheduled_from_id`, `created_at`, `updated_at`) VALUES
(1, 2, 0, 'Down Payment', '2026-08-04', 5000000.00, 5000000.00, 'paid', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(2, 2, 1, 'EMI 1', '2026-09-04', 168297.14, 168297.14, 'paid', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 02:04:17'),
(3, 2, 2, 'EMI 2', '2026-10-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(4, 2, 3, 'EMI 3', '2026-11-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(5, 2, 4, 'EMI 4', '2026-12-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(6, 2, 5, 'EMI 5', '2027-01-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(7, 2, 6, 'EMI 6', '2027-02-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(8, 2, 7, 'EMI 7', '2027-03-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(9, 2, 8, 'EMI 8', '2027-04-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(10, 2, 9, 'EMI 9', '2027-05-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(11, 2, 10, 'EMI 10', '2027-06-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(12, 2, 11, 'EMI 11', '2027-07-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(13, 2, 12, 'EMI 12', '2027-08-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(14, 2, 13, 'EMI 13', '2027-09-04', 168297.14, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(15, 2, 14, 'EMI 14', '2027-10-04', 168297.18, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(42, 3, 0, 'Down Payment', '2026-08-21', 1000000.00, 1000000.00, 'paid', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(43, 3, 1, 'EMI 1', '2026-09-04', 133229.17, 133229.17, 'paid', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 05:05:57'),
(44, 3, 2, 'EMI 2', '2026-10-04', 133229.17, 133229.17, 'paid', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 05:05:57'),
(45, 3, 3, 'EMI 3', '2026-11-04', 133229.17, 133229.17, 'paid', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 05:05:57'),
(46, 3, 4, 'EMI 4', '2026-12-04', 133229.17, 100312.49, 'partial', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 05:05:57'),
(47, 3, 5, 'EMI 5', '2027-01-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(48, 3, 6, 'EMI 6', '2027-02-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(49, 3, 7, 'EMI 7', '2027-03-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(50, 3, 8, 'EMI 8', '2027-04-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(51, 3, 9, 'EMI 9', '2027-05-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(52, 3, 10, 'EMI 10', '2027-06-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(53, 3, 11, 'EMI 11', '2027-07-04', 133229.17, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(54, 3, 12, 'EMI 12', '2027-08-04', 133229.13, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-04 04:57:00', '2026-08-04 04:57:00'),
(55, 4, 0, 'Down Payment', '2026-08-05', 1000000.00, 1000000.00, 'paid', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(56, 4, 1, 'EMI 1', '2026-09-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(57, 4, 2, 'EMI 2', '2026-10-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(58, 4, 3, 'EMI 3', '2026-11-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(59, 4, 4, 'EMI 4', '2026-12-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(60, 4, 5, 'EMI 5', '2027-01-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(61, 4, 6, 'EMI 6', '2027-02-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(62, 4, 7, 'EMI 7', '2027-03-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(63, 4, 8, 'EMI 8', '2027-04-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(64, 4, 9, 'EMI 9', '2027-05-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(65, 4, 10, 'EMI 10', '2027-06-05', 156704.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(66, 5, 0, 'Down Payment', '2026-08-05', 1000000.00, 1000000.00, 'paid', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(67, 5, 1, 'EMI 1', '2026-09-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(68, 5, 2, 'EMI 2', '2026-10-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(69, 5, 3, 'EMI 3', '2026-11-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(70, 5, 4, 'EMI 4', '2026-12-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(71, 5, 5, 'EMI 5', '2027-01-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(72, 5, 6, 'EMI 6', '2027-02-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(73, 5, 7, 'EMI 7', '2027-03-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(74, 5, 8, 'EMI 8', '2027-04-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(75, 5, 9, 'EMI 9', '2027-05-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(76, 5, 10, 'EMI 10', '2027-06-05', 103437.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(77, 6, 0, 'Down Payment', '2026-08-05', 200000.00, 200000.00, 'paid', 'fixed_emi', NULL, '2026-08-05 05:21:25', '2026-08-05 05:21:25'),
(78, 6, 1, 'EMI 1', '2026-09-05', 100000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:21:25', '2026-08-05 05:21:25'),
(79, 6, 2, 'EMI 2', '2026-10-05', 100000.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-05 05:21:25', '2026-08-05 05:21:25'),
(104, 9, 0, 'Down Payment', '2026-08-13', 14570600.00, 14570600.00, 'paid', 'fixed_emi', NULL, '2026-08-13 07:05:02', '2026-08-13 07:05:02'),
(105, 9, 1, 'EMI 1', '2026-09-13', 3161400.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-13 07:05:02', '2026-08-13 07:05:02'),
(106, 10, 0, 'Down Payment', '2026-08-14', 12358500.00, 12358500.00, 'paid', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(107, 10, 1, 'EMI 1', '2026-09-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(108, 10, 2, 'EMI 2', '2026-10-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(109, 10, 3, 'EMI 3', '2026-11-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(110, 10, 4, 'EMI 4', '2026-12-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(111, 10, 5, 'EMI 5', '2027-01-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(112, 10, 6, 'EMI 6', '2027-02-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(113, 10, 7, 'EMI 7', '2027-03-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(114, 10, 8, 'EMI 8', '2027-04-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(115, 10, 9, 'EMI 9', '2027-05-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(116, 10, 10, 'EMI 10', '2027-06-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(117, 10, 11, 'EMI 11', '2027-07-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(118, 10, 12, 'EMI 12', '2027-08-14', 1029875.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(119, 11, 0, 'Down Payment', '2026-08-17', 10736000.00, 10736000.00, 'paid', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(120, 11, 1, 'EMI 1', '2026-09-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(121, 11, 2, 'EMI 2', '2026-10-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(122, 11, 3, 'EMI 3', '2026-11-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(123, 11, 4, 'EMI 4', '2026-12-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(124, 11, 5, 'EMI 5', '2027-01-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(125, 11, 6, 'EMI 6', '2027-02-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(126, 11, 7, 'EMI 7', '2027-03-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(127, 11, 8, 'EMI 8', '2027-04-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(128, 11, 9, 'EMI 9', '2027-05-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(129, 11, 10, 'EMI 10', '2027-06-17', 1073600.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(130, 12, 0, 'Down Payment', '2026-08-17', 14368200.00, 14368200.00, 'paid', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(131, 12, 1, 'EMI 1', '2026-09-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(132, 12, 2, 'EMI 2', '2026-10-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(133, 12, 3, 'EMI 3', '2026-11-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(134, 12, 4, 'EMI 4', '2026-12-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(135, 12, 5, 'EMI 5', '2027-01-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(136, 12, 6, 'EMI 6', '2027-02-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(137, 12, 7, 'EMI 7', '2027-03-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(138, 12, 8, 'EMI 8', '2027-04-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(139, 12, 9, 'EMI 9', '2027-05-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(140, 12, 10, 'EMI 10', '2027-06-17', 1436820.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(141, 13, 0, 'Down Payment', '2026-08-17', 57091650.00, 57091650.00, 'paid', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(142, 13, 1, 'EMI 1', '2026-09-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(143, 13, 2, 'EMI 2', '2026-10-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(144, 13, 3, 'EMI 3', '2026-11-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(145, 13, 4, 'EMI 4', '2026-12-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(146, 13, 5, 'EMI 5', '2027-01-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(147, 13, 6, 'EMI 6', '2027-02-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(148, 13, 7, 'EMI 7', '2027-03-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(149, 13, 8, 'EMI 8', '2027-04-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(150, 13, 9, 'EMI 9', '2027-05-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(151, 13, 10, 'EMI 10', '2027-06-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(152, 13, 11, 'EMI 11', '2027-07-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(153, 13, 12, 'EMI 12', '2027-08-17', 4757637.50, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(154, 14, 0, 'Down Payment', '2026-08-17', 3124400.00, 3124400.00, 'paid', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(155, 14, 1, 'EMI 1', '2026-09-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(156, 14, 2, 'EMI 2', '2026-10-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(157, 14, 3, 'EMI 3', '2026-11-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(158, 14, 4, 'EMI 4', '2026-12-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(159, 14, 5, 'EMI 5', '2027-01-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(160, 14, 6, 'EMI 6', '2027-02-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(161, 14, 7, 'EMI 7', '2027-03-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(162, 14, 8, 'EMI 8', '2027-04-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(163, 14, 9, 'EMI 9', '2027-05-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:08', '2026-08-17 06:43:08'),
(164, 14, 10, 'EMI 10', '2027-06-17', 312440.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-17 06:43:08', '2026-08-17 06:43:08'),
(165, 15, 0, 'Down Payment', '2026-08-17', 3580500.00, 3580500.00, 'paid', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(166, 15, 1, 'EMI 1', '2026-06-10', 358050.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:18'),
(167, 15, 2, 'EMI 2', '2026-07-10', 358050.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:18'),
(168, 15, 3, 'EMI 3', '2026-08-10', 358050.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:18'),
(169, 15, 4, 'EMI 4', '2026-09-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(170, 15, 5, 'EMI 5', '2026-10-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(171, 15, 6, 'EMI 6', '2026-11-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(172, 15, 7, 'EMI 7', '2026-12-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(173, 15, 8, 'EMI 8', '2027-01-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(174, 15, 9, 'EMI 9', '2027-02-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:18', '2026-08-18 01:44:18'),
(175, 15, 10, 'EMI 10', '2027-03-10', 358050.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 01:44:18', '2026-08-18 01:44:18'),
(176, 16, 0, 'Down Payment', '2026-03-18', 5047350.00, 5047350.00, 'paid', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(177, 16, 1, 'EMI 1', '2026-03-19', 504735.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(178, 16, 2, 'EMI 2', '2026-04-19', 504735.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(179, 16, 3, 'EMI 3', '2026-05-19', 504735.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(180, 16, 4, 'EMI 4', '2026-06-19', 504735.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(181, 16, 5, 'EMI 5', '2026-07-19', 504735.00, 0.00, 'overdue', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(182, 16, 6, 'EMI 6', '2026-08-19', 504735.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(183, 16, 7, 'EMI 7', '2026-09-19', 504735.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(184, 16, 8, 'EMI 8', '2026-10-19', 504735.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(185, 16, 9, 'EMI 9', '2026-11-19', 504735.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(186, 16, 10, 'EMI 10', '2026-12-19', 504735.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(187, 17, 0, 'Down Payment', '2026-08-20', 11825000.00, 11825000.00, 'paid', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(188, 17, 1, 'EMI 1', '2026-09-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(189, 17, 2, 'EMI 2', '2026-10-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(190, 17, 3, 'EMI 3', '2026-11-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(191, 17, 4, 'EMI 4', '2026-12-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(192, 17, 5, 'EMI 5', '2027-01-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(193, 17, 6, 'EMI 6', '2027-02-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(194, 17, 7, 'EMI 7', '2027-03-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(195, 17, 8, 'EMI 8', '2027-04-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(196, 17, 9, 'EMI 9', '2027-05-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(197, 17, 10, 'EMI 10', '2027-06-20', 1182500.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(198, 18, 0, 'Down Payment', '2026-08-20', 5129200.00, 5129200.00, 'paid', 'fixed_emi', NULL, '2026-08-20 00:42:31', '2026-08-20 00:42:31'),
(199, 18, 1, 'EMI 1', '2026-09-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(200, 18, 2, 'EMI 2', '2026-10-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(201, 18, 3, 'EMI 3', '2026-11-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(202, 18, 4, 'EMI 4', '2026-12-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(203, 18, 5, 'EMI 5', '2027-01-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(204, 18, 6, 'EMI 6', '2027-02-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(205, 18, 7, 'EMI 7', '2027-03-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(206, 18, 8, 'EMI 8', '2027-04-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(207, 18, 9, 'EMI 9', '2027-05-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32'),
(208, 18, 10, 'EMI 10', '2027-06-20', 512920.00, 0.00, 'pending', 'fixed_emi', NULL, '2026-08-20 00:42:32', '2026-08-20 00:42:32');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_dms_documents`
--

DROP TABLE IF EXISTS `hindustansystem_dms_documents`;
CREATE TABLE IF NOT EXISTS `hindustansystem_dms_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `documentable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentable_id` bigint UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL,
  `mime_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dms_docs_morph_idx` (`documentable_type`,`documentable_id`),
  KEY `hindustansystem_dms_documents_uploaded_by_foreign` (`uploaded_by`),
  KEY `hindustansystem_dms_documents_category_document_type_index` (`category`,`document_type`),
  KEY `hindustansystem_dms_documents_system_id_index` (`system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  UNIQUE KEY `hindustansystem_engineers_engineer_code_unique` (`engineer_code`),
  KEY `hindustansystem_engineers_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_engineers`
--

INSERT INTO `hindustansystem_engineers` (`id`, `engineer_code`, `name`, `email`, `phone`, `designation`, `specialization`, `project_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ENG-001', 'Rahul Accountant', NULL, NULL, 'Site Engineer', NULL, NULL, 1, '2026-08-18 03:33:02', '2026-08-18 03:33:02'),
(2, 'ENG-002', 'Anand Kumar', NULL, NULL, 'Senior Project Engineer', NULL, NULL, 1, '2026-08-18 03:33:02', '2026-08-18 03:33:02'),
(3, 'ENG-003', 'Suresh Nair', NULL, NULL, 'Civil Engineer', NULL, NULL, 1, '2026-08-18 03:33:02', '2026-08-18 03:33:02');

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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_hindustan_units`
--

INSERT INTO `hindustansystem_hindustan_units` (`id`, `project_id`, `floor_id`, `unit_type_id`, `door_no`, `built_up_area`, `carpet_area`, `expected_rate_per_sqft`, `expected_sale_amount`, `sale_rate_per_sqft`, `sale_amount`, `difference`, `gst_behavior`, `gst_amount`, `status`, `created_at`, `updated_at`, `is_active`, `deleted_at`) VALUES
(1, 1, 17, 2, 'G 1', 4943.00, 3954.40, 22000.00, 108746000.00, 22000.00, 114183300.00, -5437300.00, 'exclusive', 5437300.00, 'sold', '2026-08-01 00:55:55', '2026-08-17 03:28:08', 1, NULL),
(2, 1, 17, 2, 'G 2', 480.00, 384.00, 22000.00, 10560000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 00:56:36', '2026-08-01 00:56:36', 1, NULL),
(3, 1, 17, 2, 'G 3', 310.00, 248.00, 22000.00, 6820000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 00:56:58', '2026-08-01 00:56:58', 1, NULL),
(4, 1, 17, 2, 'G 4', 284.00, 227.20, 22000.00, 6248000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 00:57:51', '2026-08-01 00:57:51', 1, NULL),
(5, 1, 17, 2, 'G 5', 284.00, 227.20, 22000.00, 6248000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 00:58:13', '2026-08-01 00:58:13', 1, NULL),
(6, 1, 17, 2, 'G 6', 248.00, 198.40, 22000.00, 5456000.00, 22000.00, 5728800.00, -272800.00, 'exclusive', 272800.00, 'sold', '2026-08-01 00:58:31', '2026-08-17 06:43:07', 1, NULL),
(7, 1, 17, 2, 'G 7', 229.00, 183.20, 22000.00, 5038000.00, 22000.00, 5038000.00, 0.00, 'none', 0.00, 'sold', '2026-08-01 00:58:52', '2026-08-20 00:39:44', 1, NULL),
(8, 1, 17, 2, 'G 8', 284.00, 227.20, 22000.00, 6248000.00, 22000.00, 6560400.00, -312400.00, 'exclusive', 312400.00, 'sold', '2026-08-01 00:59:11', '2026-08-20 00:42:31', 1, NULL),
(9, 1, 17, 2, 'G 9', 284.00, 227.20, 22000.00, 6248000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 00:59:30', '2026-08-01 00:59:30', 1, NULL),
(10, 1, 17, 2, 'G 10', 325.00, 260.00, 22000.00, 7150000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 00:59:58', '2026-08-01 00:59:58', 1, NULL),
(11, 1, 17, 2, 'G 11', 310.00, 248.00, 22000.00, 6820000.00, 22000.00, 7161000.00, -341000.00, 'exclusive', 341000.00, 'sold', '2026-08-01 01:00:14', '2026-08-18 01:44:17', 1, NULL),
(12, 1, 17, 2, 'G 12', 806.00, 644.80, 22000.00, 17732000.00, 22000.00, 17732000.00, 0.00, 'none', 0.00, 'sold', '2026-08-01 01:00:44', '2026-08-13 07:05:02', 1, NULL),
(13, 1, 17, 2, 'G 13', 575.00, 460.00, 22000.00, 12650000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:01:06', '2026-08-01 01:01:06', 1, NULL),
(14, 1, 17, 2, 'G 14', 542.00, 433.60, 22000.00, 11924000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:01:30', '2026-08-01 01:01:30', 1, NULL),
(15, 1, 17, 2, 'G 15', 555.00, 444.00, 22000.00, 12210000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:01:51', '2026-08-01 01:01:51', 1, NULL),
(16, 1, 17, 2, 'G 16', 437.00, 349.60, 22000.00, 9614000.00, 22000.00, 10094700.00, -480700.00, 'exclusive', 480700.00, 'sold', '2026-08-01 01:02:17', '2026-08-18 02:07:11', 1, NULL),
(17, 1, 17, 2, 'G 17', 541.00, 432.80, 22000.00, 11902000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:03:38', '2026-08-01 01:03:38', 1, NULL),
(18, 1, 17, 2, 'G 18', 541.00, 432.80, 22000.00, 11902000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:04:24', '2026-08-01 01:04:24', 1, NULL),
(19, 1, 17, 2, 'G 19', 1130.00, 904.00, 22000.00, 24860000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:04:52', '2026-08-01 01:04:52', 1, NULL),
(20, 1, 17, 2, 'G 20', 1271.00, 1016.80, 22000.00, 27962000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:05:12', '2026-08-01 01:05:12', 1, NULL),
(21, 1, 17, 2, 'G 21', 843.00, 674.40, 22000.00, 18546000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:05:32', '2026-08-01 01:05:32', 1, NULL),
(22, 1, 17, 2, 'G 22', 713.00, 570.40, 22000.00, 15686000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:06:10', '2026-08-01 01:06:10', 1, NULL),
(23, 1, 17, 2, 'G 23', 535.00, 428.00, 22000.00, 11770000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:06:34', '2026-08-01 01:06:34', 1, NULL),
(24, 1, 17, 2, 'G 24', 535.00, 428.00, 22000.00, 11770000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:07:02', '2026-08-01 01:07:02', 1, NULL),
(25, 1, 17, 2, 'G 25', 535.00, 428.00, 22000.00, 11770000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:07:20', '2026-08-01 01:07:20', 1, NULL),
(26, 1, 17, 2, 'G 26', 558.00, 446.40, 22000.00, 12276000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:07:48', '2026-08-01 01:07:48', 1, NULL),
(27, 1, 17, 2, 'G 27', 562.00, 449.60, 22000.00, 12364000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:08:08', '2026-08-01 01:08:08', 1, NULL),
(28, 1, 17, 2, 'G 28', 535.00, 428.00, 22000.00, 11770000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:08:34', '2026-08-01 01:08:34', 1, NULL),
(29, 1, 17, 2, 'G 29', 535.00, 428.00, 22000.00, 11770000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:08:59', '2026-08-01 01:08:59', 1, NULL),
(30, 1, 17, 2, 'G 30', 821.00, 656.80, 22000.00, 18062000.00, 8000.00, 7356160.00, 10705840.00, 'exclusive', 788160.00, 'sold', '2026-08-01 01:09:18', '2026-08-04 01:50:02', 1, NULL),
(31, 1, 18, 6, 'SI A', 1022.00, 73.54, 6000.00, 6132000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:32:35', '2026-08-01 02:14:31', 1, NULL),
(32, 1, 18, 6, 'SI B', 734.00, 51.68, 6000.00, 4404000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:34:15', '2026-08-01 02:14:50', 1, NULL),
(33, 1, 18, 6, 'SI C', 592.00, 473.60, 6000.00, 3552000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:36:39', '2026-08-01 02:14:59', 1, NULL),
(34, 1, 18, 6, 'SI D', 741.00, 592.80, 6000.00, 4446000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:37:16', '2026-08-01 02:15:08', 1, NULL),
(35, 1, 18, 6, 'SI E', 725.00, 580.00, 6000.00, 4350000.00, 3000.00, 2283750.00, 2066250.00, 'exclusive', 108750.00, 'sold', '2026-08-01 01:37:44', '2026-08-04 04:56:46', 1, NULL),
(36, 1, 18, 6, 'SI F', 608.00, 486.40, 6000.00, 3648000.00, 6000.00, 3648000.00, 0.00, 'none', 0.00, 'sold', '2026-08-01 01:38:04', '2026-08-20 00:42:31', 1, NULL),
(37, 1, 18, 6, 'SI G', 796.00, 636.80, 6000.00, 4776000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:38:37', '2026-08-01 02:15:50', 1, NULL),
(38, 1, 19, 6, 'SE A', 979.00, 783.20, 6000.00, 5874000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:39:27', '2026-08-01 02:16:03', 1, NULL),
(39, 1, 19, 6, 'SE B', 1607.00, 1285.60, 6000.00, 9642000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:39:49', '2026-08-01 02:16:29', 1, NULL),
(40, 1, 19, 6, 'SE C', 1586.00, 1268.80, 6000.00, 9516000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:40:09', '2026-08-01 02:16:38', 1, NULL),
(41, 1, 19, 6, 'SE D', 1063.00, 850.40, 6000.00, 6378000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-01 01:40:35', '2026-08-01 02:16:48', 1, NULL),
(42, 1, 4, 5, 'FO SLOT 1', NULL, NULL, NULL, 400000.00, 0.00, 420000.00, -20000.00, 'exclusive', 20000.00, 'sold', '2026-08-04 03:40:53', '2026-08-17 06:43:07', 1, NULL),
(43, 1, 4, 5, 'FO SLOT 2', NULL, NULL, NULL, 400000.00, 0.00, 400000.00, 0.00, 'none', 0.00, 'sold', '2026-08-04 03:41:14', '2026-08-05 05:21:25', 1, NULL),
(44, 1, 5, 5, 'FI SLOT 1', NULL, NULL, NULL, 300000.00, 0.00, 315000.00, -15000.00, 'exclusive', 15000.00, 'sold', '2026-08-04 03:41:29', '2026-08-04 04:56:46', 1, NULL),
(45, 1, 5, 5, 'FI SLOT 2', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-04 03:41:44', '2026-08-04 03:41:44', 1, NULL),
(46, 1, 4, 5, 'FO SLOT 3', NULL, NULL, NULL, 300000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-04 03:41:59', '2026-08-13 06:13:10', 1, NULL),
(47, 1, 5, 5, 'FI SLOT 3', NULL, NULL, NULL, 400000.00, NULL, NULL, NULL, 'none', 0.00, 'available', '2026-08-04 03:42:13', '2026-08-04 03:42:13', 1, NULL),
(48, 1, 1, 2, 'F 1', 976.00, 780.80, 22000.00, 21472000.00, 22000.00, 21472000.00, 0.00, 'none', 0.00, 'sold', '2026-08-05 03:08:45', '2026-08-17 01:12:41', 1, NULL),
(49, 1, 1, 2, 'F 2', 1070.00, 856.80, 22000.00, 23540000.00, 22000.00, 24717000.00, -1177000.00, 'exclusive', 1177000.00, 'sold', '2026-08-05 03:09:07', '2026-08-14 05:03:34', 1, NULL),
(50, 1, 1, 2, 'F 3', 846.00, 676.80, 22000.00, 18612000.00, 22000.00, 18612000.00, 0.00, 'none', 0.00, 'sold', '2026-08-05 03:09:30', '2026-08-20 00:39:43', 1, NULL),
(51, 1, 1, 2, 'F 4', 1244.00, 995.20, 22000.00, 27368000.00, 22000.00, 28736400.00, -1368400.00, 'exclusive', 1368400.00, 'sold', '2026-08-05 03:10:45', '2026-08-17 01:47:45', 1, NULL),
(52, 1, 1, 2, 'F 6', 573.00, 458.40, 22000.00, 12606000.00, 4000.00, 2567040.00, 10038960.00, 'exclusive', 275040.00, 'sold', '2026-08-05 03:11:42', '2026-08-05 05:06:21', 1, NULL),
(53, 1, 20, 6, 'E 1', 775.00, 54.12, 6000.00, 4650000.00, 2500.00, 2034375.00, 2615625.00, 'exclusive', 96875.00, 'sold', '2026-08-05 05:12:13', '2026-08-05 05:14:41', 1, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ledger_entries`
--

INSERT INTO `hindustansystem_ledger_entries` (`id`, `system_id`, `account_id`, `voucher_id`, `voucher_line_id`, `date`, `debit`, `credit`, `running_balance`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1, 1, '2026-08-05', 200000.00, 0.00, 0.00, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(2, 1, 21, 1, 2, '2026-08-05', 0.00, 200000.00, 0.00, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(3, 1, 2, 1, 3, '2026-08-05', 115000.00, 0.00, 0.00, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(4, 1, 7, 1, 4, '2026-08-05', 0.00, 115000.00, 0.00, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(5, 1, 3, 1, 5, '2026-08-05', 85000.00, 0.00, 0.00, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(6, 1, 7, 1, 6, '2026-08-05', 0.00, 85000.00, 0.00, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(7, 1, 10, 2, 7, '2026-08-14', 525000.00, 0.00, 0.00, '2026-08-14 04:21:05', '2026-08-14 04:21:05'),
(8, 1, 71, 2, 8, '2026-08-14', 0.00, 525000.00, 0.00, '2026-08-14 04:21:05', '2026-08-14 04:21:05');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  KEY `hindustansystem_loan_disbursals_created_by_foreign` (`created_by`),
  KEY `hindustansystem_loan_disbursals_posted_by_foreign` (`posted_by`),
  KEY `hindustansystem_loan_disbursals_cancelled_by_foreign` (`cancelled_by`),
  KEY `hindustansystem_loan_disbursals_system_id_index` (`system_id`),
  KEY `hindustansystem_loan_disbursals_loan_id_index` (`loan_id`),
  KEY `hindustansystem_loan_disbursals_disbursal_no_index` (`disbursal_no`)
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(58, '2026_08_10_100000_create_company_bank_accounts_table', 34),
(59, '2026_08_10_400000_add_is_allocated_to_receipts_table', 34),
(60, '2026_08_11_064117_add_deleted_at_to_hindustan_units_table', 35),
(61, '2026_08_12_100000_add_realization_fields_to_receipts_table', 35),
(62, '2026_08_12_100001_create_receipt_realization_logs_table', 35),
(63, '2026_08_13_110000_create_chart_of_accounts_table', 36),
(64, '2026_08_13_120000_create_voucher_types_table', 36),
(65, '2026_08_13_130000_create_engineers_table', 36),
(66, '2026_08_14_071418_create_receipt_stores_table', 36),
(67, '2026_08_14_160000_create_ra_bills_tables', 37),
(68, '2026_08_14_170000_add_unit_id_to_ra_bills_table', 37),
(69, '2026_08_14_123603_create_cheque_statuses_table', 38),
(70, '2026_08_18_063152_create_collection_reminders_table', 39),
(71, '2026_08_18_114834_create_petty_cash_boxes_table', 40),
(72, '2026_08_18_114846_create_petty_cash_transactions_table', 41),
(73, '2026_08_20_071302_add_additional_amount_to_ra_bills_table', 42),
(74, '2026_08_20_160000_add_revision_fields_to_unit_rate_logs_table', 43),
(75, '2026_08_24_120000_add_additional_percentage_to_ra_bills_table', 44),
(76, '2026_08_24_130000_create_dms_documents_table', 45),
(77, '2026_08_25_052858_create_petty_cash_boxes_table', 46),
(78, '2026_08_25_053455_create_petty_cash_transactions_table', 46),
(79, '2026_08_25_062143_add_attachment_to_petty_cash_transactions_table', 47),
(80, '2026_08_25_083407_add_payment_mode_to_petty_cash_transactions_table', 48),
(81, '2026_08_25_085244_add_bill_date_to_petty_cash_transactions_table', 49),
(82, '2026_08_25_095500_create_loan_disbursals_table', 50);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_partner_allocations`
--

INSERT INTO `hindustansystem_partner_allocations` (`id`, `system_id`, `partner_id`, `project_id`, `payment_id`, `allocated_amount`, `date`, `voucher_id`, `created_at`, `updated_at`) VALUES
(9, 1, 1, 1, NULL, 115000.00, '2026-08-05', 1, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(10, 1, 2, 1, NULL, 85000.00, '2026-08-05', 1, '2026-08-06 06:22:18', '2026-08-06 06:22:18');

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
  `linked_account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_payees_system_id_foreign` (`system_id`),
  KEY `hindustansystem_payees_linked_account_id_foreign` (`linked_account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payees`
--

INSERT INTO `hindustansystem_payees` (`id`, `system_id`, `type`, `name`, `phone`, `email`, `gstin`, `pan`, `address`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Partner', 'Basheer', NULL, NULL, NULL, NULL, NULL, 2, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(2, 1, 'Partner', 'Pavoor', NULL, NULL, NULL, NULL, NULL, 3, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(9, 1, 'Supplier', 'AZUS', '7657676', 'AS@GMAIL.COM', 'hgasdhsgdf48765', '5644', 'FDHGFHH', 71, '2026-08-14 04:20:13', '2026-08-14 04:20:13');

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
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_payments_receipt_number_unique` (`receipt_number`),
  KEY `hindustansystem_payments_customer_id_foreign` (`customer_id`),
  KEY `hindustansystem_payments_project_id_foreign` (`project_id`),
  KEY `hindustansystem_payments_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `hindustansystem_petty_cash_boxes`
--

DROP TABLE IF EXISTS `hindustansystem_petty_cash_boxes`;
CREATE TABLE IF NOT EXISTS `hindustansystem_petty_cash_boxes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `box_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `box_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Site Petty Cash Box',
  `incharge_id` bigint UNSIGNED DEFAULT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_petty_cash_boxes_box_code_unique` (`box_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_petty_cash_boxes`
--

INSERT INTO `hindustansystem_petty_cash_boxes` (`id`, `project_id`, `box_code`, `box_name`, `incharge_id`, `current_balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'PC-HID-001', 'Site Petty Cash Box', 1, 21000.00, 'Active', '2026-08-25 00:09:00', '2026-08-25 03:48:54');

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
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `voucher_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_date` date DEFAULT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Cash',
  `attachment_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_in` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cash_out` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Posted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_petty_cash_transactions`
--

INSERT INTO `hindustansystem_petty_cash_transactions` (`id`, `petty_cash_box_id`, `voucher_id`, `transaction_date`, `voucher_number`, `transaction_type`, `reference_no`, `bill_date`, `narration`, `payment_mode`, `attachment_path`, `cash_in`, `cash_out`, `balance`, `created_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 8, '2026-08-25', 'PCON-00004', 'Contra', 'CHQ765567', NULL, 'cash withdrawn karnataka bank account', 'Cash', NULL, 10000.00, 0.00, 10000.00, 1, 'Posted', '2026-08-25 00:10:09', '2026-08-25 00:10:09'),
(2, 1, 9, '2026-08-25', 'PCON-00005', 'Contra', 'CHQ65656', NULL, 'cash withdrwal', 'Cash', NULL, 12000.00, 0.00, 22000.00, 1, 'Posted', '2026-08-25 00:47:28', '2026-08-25 00:47:28'),
(3, 1, 10, '2026-08-25', 'PCON-00006', 'Contra', 'CHQ343', NULL, 'contra with drawal', 'Cash', NULL, 5000.00, 0.00, 27000.00, 1, 'Posted', '2026-08-25 00:56:21', '2026-08-25 00:56:21'),
(4, 1, NULL, '2026-08-25', 'EXP-7249', 'Site Expense', 'FF6565', '2026-08-25', 'Refreshments - site expense', 'Cash', 'petty-cash-attachments/1787648493_Screenshot 2026-08-07 170704.png', 0.00, 5000.00, 22000.00, 1, 'approved', '2026-08-25 03:31:33', '2026-08-25 03:31:33'),
(5, 1, NULL, '2026-08-25', 'EXP-7971', 'Site Expense', 'Bill-74674654', '2026-08-25', 'Labour Welfare - labour charges', 'Cheque', 'petty-cash-attachments/1787649534_Screenshot 2026-08-10 112219.png', 0.00, 1000.00, 21000.00, 1, 'approved', '2026-08-25 03:48:54', '2026-08-25 03:48:54');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_projects`
--

INSERT INTO `hindustansystem_projects` (`id`, `system_id`, `name`, `code`, `location`, `city`, `state_or_emirate`, `country`, `rera_number`, `total_floors`, `start_date`, `expected_completion_date`, `status`, `description`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tabasco Hindustan Infra Developers Pvt. Ltd', 'HID', 'Kanhangad', 'Kasaragod', 'Kerala', 'India', NULL, 20, '2026-07-07', '2026-07-27', 'ongoing', '<p><strong>The 1st RERA Approved property in Kasargod. </strong>Tabasco Mall is positioned at the heart of a well-known city named Kanhangad, being very close to popular Tourist Hubs such as the BekalFort, PallikereBeach, etc. In addition, Railway stations, Bus stands, Schools, Colleges, Hospitals, and many other essential infrastructures are integrated with in the city. The city is also located precisely in the middle of two International Airports, 90km away from each.</p>', 'projects/bbTdFnGMramRdHLW0f0ltakwuI3F3U8Kq2B6Amyh.png', 1, '2026-07-06 03:53:41', '2026-08-19 01:38:02');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ra_bills`
--

DROP TABLE IF EXISTS `hindustansystem_ra_bills`;
CREATE TABLE IF NOT EXISTS `hindustansystem_ra_bills` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `ra_bill_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contractor_id` bigint UNSIGNED DEFAULT NULL,
  `contractor_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `unit_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_date` date NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `additional_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `additional_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `verified_date` date DEFAULT NULL,
  `engineer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correction_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_approved_amount` decimal(15,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_amount` decimal(15,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_ra_bills_ra_bill_number_unique` (`ra_bill_number`),
  KEY `hindustansystem_ra_bills_system_id_index` (`system_id`),
  KEY `hindustansystem_ra_bills_contractor_id_index` (`contractor_id`),
  KEY `hindustansystem_ra_bills_project_id_index` (`project_id`),
  KEY `hindustansystem_ra_bills_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ra_bills`
--

INSERT INTO `hindustansystem_ra_bills` (`id`, `system_id`, `ra_bill_number`, `contractor_id`, `contractor_name`, `project_id`, `unit_id`, `unit_name`, `submit_date`, `gross_amount`, `additional_amount`, `additional_percentage`, `verified_date`, `engineer_name`, `correction_amount`, `net_approved_amount`, `due_date`, `paid_amount`, `balance_amount`, `status`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'RA--001', 9, 'AZUS', 1, NULL, NULL, '2026-08-20', 5000000.00, 147096.00, 2.94, '2026-08-20', 'Anand Kumar (Senior Project Engineer)', 4500000.00, 500000.00, '2026-08-13', 150000.00, 350000.00, 'partially_paid', 'VBCBVCB', 1, '2026-08-20 01:47:33', '2026-08-24 01:14:11'),
(2, 1, 'RA-0002', 9, 'AZUS', 1, NULL, NULL, '2026-08-20', 600000.00, 23400.00, 3.90, '2026-08-20', 'Anand Kumar (Senior Project Engineer)', 62340.00, 537660.00, '2026-08-20', 0.00, 537660.00, 'pending', 'GHGFH', 1, '2026-08-20 02:04:00', '2026-08-24 01:14:11'),
(3, 1, 'gfgf', 9, 'AZUS', 1, NULL, NULL, '2026-08-20', 755540.00, 20.00, 0.00, '2026-08-20', 'Anand Kumar (Senior Project Engineer)', 50000.00, 705540.00, '2026-08-20', 0.00, 705540.00, 'pending', 'cfgffhfh', 1, '2026-08-20 04:18:08', '2026-08-24 01:14:11');

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
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NEFT',
  `company_bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hindustansystem_ra_bill_payments_system_id_index` (`system_id`),
  KEY `hindustansystem_ra_bill_payments_ra_bill_id_index` (`ra_bill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ra_bill_payments`
--

INSERT INTO `hindustansystem_ra_bill_payments` (`id`, `system_id`, `ra_bill_id`, `payment_date`, `paid_amount`, `payment_mode`, `company_bank_account_id`, `reference_no`, `voucher_id`, `status`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-08-20', 100000.00, 'BANK_TRANSFER', 1, 'CVCXV', 3, 'paid', NULL, 1, '2026-08-20 01:50:11', '2026-08-20 01:50:11'),
(2, 1, 1, '2026-08-20', 50000.00, 'BANK_TRANSFER', 1, 'CVB', 4, 'paid', NULL, 1, '2026-08-20 01:51:12', '2026-08-20 01:51:12');

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
  KEY `hindustansystem_receipts_company_bank_account_id_foreign` (`company_bank_account_id`),
  KEY `hindustansystem_receipts_realized_by_foreign` (`realized_by`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipts`
--

INSERT INTO `hindustansystem_receipts` (`id`, `sale_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `is_allocated`, `payment_mode`, `realization_status`, `cheque_date`, `drawee_bank`, `realized_at`, `realized_by`, `payment_type`, `reference_no`, `bank_id`, `company_bank_account_id`, `remarks`, `created_by`, `partner_id`, `created_at`, `updated_at`) VALUES
(20, 2, 2, 1, 30, '2026-08-04', 5000000.00, 0, 'Cheque', 'cheque_in_hand', NULL, NULL, NULL, NULL, 'regular', '124566+', 20, 3, 'Initial payment at sale creation', 1, NULL, '2026-08-04 01:50:02', '2026-08-17 04:52:55'),
(21, 3, 3, 1, 35, '2026-08-13', 1000000.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-14 06:49:40', 1, 'regular', '456662', 20, 1, 'Initial payment at sale creation', 1, NULL, '2026-08-04 02:00:19', '2026-08-14 06:49:40'),
(22, 2, 2, 1, 30, '2026-08-04', 168297.14, 0, 'Cheque', 'bounced', NULL, NULL, NULL, NULL, 'regular', '5464+', NULL, NULL, '46646', 1, NULL, '2026-08-04 02:04:17', '2026-08-14 04:57:38'),
(23, 3, 3, 1, 35, '2026-08-29', 500000.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-13 18:30:00', 1, 'regular', NULL, NULL, 1, NULL, 1, NULL, '2026-08-04 05:05:57', '2026-08-14 03:38:06'),
(24, 4, 7, 1, 52, '2026-06-12', 1000000.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-13 18:30:00', 1, 'regular', '12689+6', 21, 3, 'Initial payment at sale creation', 1, NULL, '2026-08-05 05:06:21', '2026-08-14 05:54:42'),
(25, 5, 8, 1, 53, '2026-07-11', 1000000.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-13 18:30:00', 1, 'regular', '588965', 22, 1, 'Initial payment at sale creation', 1, NULL, '2026-08-05 05:14:41', '2026-08-14 04:50:58'),
(26, 6, 8, 1, 43, '2026-08-05', 200000.00, 0, 'Cheque', 'cancelled', NULL, NULL, NULL, NULL, 'regular', '8634', 20, NULL, 'Initial payment at sale creation', 1, NULL, '2026-08-05 05:21:25', '2026-08-14 05:01:29'),
(27, 7, 9, 1, 50, '2026-08-13', 1038300.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-13 18:30:00', 1, 'regular', 'CHQ454', 21, 1, 'Initial payment at sale creation', 1, NULL, '2026-08-13 06:11:30', '2026-08-14 04:45:10'),
(28, 9, 10, 1, 12, '2026-08-13', 13684000.00, 0, 'Cheque', 'bounced', NULL, NULL, NULL, NULL, 'regular', 'CHQ-8786576', 21, 1, 'Initial payment from previous booking (HID-SH-F4-JOLL)', 1, NULL, '2026-08-13 06:45:17', '2026-08-17 00:59:26'),
(29, 9, 10, 1, 12, '2026-08-13', 886600.00, 0, 'Cash', 'realized', NULL, NULL, '2026-08-17 02:11:13', 1, 'regular', NULL, NULL, 3, 'Initial payment at sale creation', 1, NULL, '2026-08-13 07:05:02', '2026-08-17 02:11:13'),
(30, 10, 11, 1, 49, '2026-08-14', 12358500.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-13 18:30:00', 1, 'regular', 'chq65743', 21, 1, 'Initial payment at sale creation', 1, NULL, '2026-08-14 05:03:35', '2026-08-14 05:04:03'),
(31, 11, 12, 1, 48, '2026-08-17', 10736000.00, 0, 'Cash', 'realized', NULL, NULL, '2026-08-17 01:13:32', 1, 'regular', NULL, NULL, 1, 'Initial payment at sale creation', 1, NULL, '2026-08-17 01:12:41', '2026-08-17 01:13:32'),
(32, 12, 7, 1, 51, '2026-08-17', 14368200.00, 0, 'Cheque', 'bounced', NULL, NULL, NULL, NULL, 'regular', 'CHQ5656', 21, NULL, 'Initial payment at sale creation', 1, NULL, '2026-08-17 01:47:45', '2026-08-17 02:10:31'),
(33, 13, 13, 1, 1, '2026-08-17', 57091650.00, 0, 'Cheque', 'realized', NULL, NULL, '2026-08-17 04:52:15', 1, 'regular', '64565', 20, 3, 'Initial payment at sale creation', 1, NULL, '2026-08-17 03:28:08', '2026-08-17 04:52:15'),
(34, 14, 14, 1, 6, '2026-08-17', 3124400.00, 0, 'Cheque', 'deposited', NULL, NULL, NULL, NULL, 'regular', 'CHQ66', 21, 1, 'Initial payment at sale creation', 1, NULL, '2026-08-17 06:43:07', '2026-08-19 00:14:32'),
(35, 15, 15, 1, 11, '2026-08-10', 3580500.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', 'CHQ5435', 21, NULL, 'Initial payment at sale creation', 1, NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(36, 16, 16, 1, 16, '2026-03-18', 5047350.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', 'chq535', 21, NULL, 'Initial payment at sale creation', 1, NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(37, 17, 7, 1, 50, '2026-08-20', 11825000.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', 'Chq565', 22, NULL, 'Initial payment at sale creation', 1, NULL, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(38, 18, 20, 1, 8, '2026-08-20', 5129200.00, 0, 'Cheque', 'pending', NULL, NULL, NULL, NULL, 'regular', 'CHQ7877', 20, NULL, 'Initial payment at sale creation', 1, NULL, '2026-08-20 00:42:31', '2026-08-20 00:42:31');

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
  KEY `hindustansystem_receipt_realization_logs_changed_by_foreign` (`changed_by`),
  KEY `rec_real_logs_idx` (`receipt_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipt_realization_logs`
--

INSERT INTO `hindustansystem_receipt_realization_logs` (`id`, `receipt_id`, `old_status`, `new_status`, `remarks`, `changed_by`, `created_at`, `updated_at`) VALUES
(1, 23, 'pending', 'cheque_in_hand', NULL, 1, '2026-08-14 02:42:21', '2026-08-14 02:42:21'),
(2, 23, 'cheque_in_hand', 'realized', 'Cheque cleared by bank.', 1, '2026-08-14 03:38:06', '2026-08-14 03:38:06'),
(3, 27, 'pending', 'realized', 'wetgeygtuehtejrgh (Bank Ref: hgjhfghfjgfdh)', 1, '2026-08-14 04:45:10', '2026-08-14 04:45:10'),
(4, 25, 'pending', 'realized', 'hygfhfjg (Bank Ref: 56557)', 1, '2026-08-14 04:50:58', '2026-08-14 04:50:58'),
(5, 22, 'pending', 'bounced', 'dfgdfhgh', 1, '2026-08-14 04:57:38', '2026-08-14 04:57:38'),
(6, 26, 'pending', 'cancelled', 'ghjhg', 1, '2026-08-14 05:01:29', '2026-08-14 05:01:29'),
(7, 30, 'pending', 'realized', 'fdtygfygtuh (Bank Ref: gfdhsgewtg)', 1, '2026-08-14 05:04:03', '2026-08-14 05:04:03'),
(8, 24, 'pending', 'realized', 'HFKKJDGDLKF (Bank Ref: GGH6556)', 1, '2026-08-14 05:54:42', '2026-08-14 05:54:42'),
(9, 21, 'pending', 'realized', '65ytugyug', 1, '2026-08-14 06:49:41', '2026-08-14 06:49:41'),
(10, 28, 'pending', 'pending', 'waiting', 1, '2026-08-17 00:39:01', '2026-08-17 00:39:01'),
(11, 28, 'pending', 'cheque_in_hand', 'tgghfjhgjfgh', 1, '2026-08-17 00:43:40', '2026-08-17 00:43:40'),
(12, 28, 'cheque_in_hand', 'deposited', 'deposited to bank', 1, '2026-08-17 00:44:25', '2026-08-17 00:44:25'),
(13, 28, 'deposited', 'cheque_in_hand', 'hand (Bank Ref: ABG6566)', 1, '2026-08-17 00:56:37', '2026-08-17 00:56:37'),
(14, 28, 'cheque_in_hand', 'bounced', 'bounced', 1, '2026-08-17 00:59:26', '2026-08-17 00:59:26'),
(15, 31, 'pending', 'realized', 'realized for cheque', 1, '2026-08-17 01:13:32', '2026-08-17 01:13:32'),
(16, 32, 'pending', 'bounced', 'Initial payment at sale creation', 1, '2026-08-17 02:10:31', '2026-08-17 02:10:31'),
(17, 29, 'pending', 'realized', 'Initial payment at sale creation', 1, '2026-08-17 02:11:13', '2026-08-17 02:11:13'),
(18, 33, 'pending', 'deposited', 'dfgtfdgf (Bank Ref: 4354564)', 1, '2026-08-17 03:49:09', '2026-08-17 03:49:09'),
(19, 33, 'deposited', 'pending', 'Pending data (Bank Ref: 65765765)', 1, '2026-08-17 03:49:50', '2026-08-17 03:49:50'),
(20, 33, 'pending', 'deposited', 'fsdgtgdsfgdf (Bank Ref: 56767)', 1, '2026-08-17 04:00:45', '2026-08-17 04:00:45'),
(21, 33, 'deposited', 'realized', 'fsdgtgdsfgdf (Bank Ref: 56767)', 1, '2026-08-17 04:52:15', '2026-08-17 04:52:15'),
(22, 20, 'pending', 'cheque_in_hand', 'Initial payment at sale creation (Bank Ref: hygtfujyuy)', 1, '2026-08-17 04:52:55', '2026-08-17 04:52:55'),
(23, 34, 'pending', 'deposited', 'Initial payment at sale creation (Bank Ref: yfyfyf)', 1, '2026-08-19 00:14:32', '2026-08-19 00:14:32');

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
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipt_stores`
--

INSERT INTO `hindustansystem_receipt_stores` (`id`, `receipt_id`, `company_bank_account_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `payment_mode`, `reference_no`, `remarks`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 29, 3, 10, 1, 12, '2026-08-13', 886600.00, 'Cash', NULL, 'Initial payment at sale creation', 'realized', 1, '2026-08-14 04:28:07', '2026-08-17 02:11:13'),
(2, 21, 1, 3, 1, NULL, '2026-08-13', 1000000.00, 'Cheque', '456662', '65ytugyug', 'realized', 1, '2026-08-14 06:49:40', '2026-08-14 06:49:40'),
(3, 31, 1, 12, 1, NULL, '2026-08-17', 10736000.00, 'Cash', NULL, 'realized for cheque', 'realized', 1, '2026-08-17 01:13:32', '2026-08-17 01:13:32'),
(4, 33, 3, 13, 1, NULL, '2026-08-17', 57091650.00, 'Cheque', '64565', 'fsdgtgdsfgdf (Bank Ref: 56767)', 'realized', 1, '2026-08-17 04:52:15', '2026-08-17 04:52:15');

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
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales`
--

INSERT INTO `hindustansystem_sales` (`id`, `sale_number`, `project_id`, `unit_id`, `customer_id`, `broker_id`, `agreement_date`, `registration_date`, `rate_per_sqft`, `sale_amount`, `gst_applicable`, `gst_type`, `base_amount`, `gst_percentage`, `gst_amount`, `total_amount`, `sale_date`, `status`, `remaining_balance`, `broker_involved`, `initial_payment`, `payment_mode`, `reference_no`, `bank_id`, `remarks`, `payment_plan`, `emi_type`, `emi_installment_count`, `emi_frequency`, `first_installment_date`, `original_sale_id`, `is_resale`, `cancellation_reason`, `cancelled_at`, `cancellation_fee`, `refund_amount`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'HID-SH-G30-ASHR', 1, 30, 2, NULL, '2026-08-04', '2027-07-14', 8000.00, 6568000.00, 0, 'exclusive', 6568000.00, 18.00, 788160.00, 7356160.00, '2026-08-04', 'active', 2187862.86, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 14, 'monthly', '2026-09-04', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-08-04 01:50:02', '2026-08-04 02:04:17', NULL),
(3, 'HID-AP-SIE-SHAM', 1, 35, 3, NULL, '2026-08-21', '2027-07-14', 3000.00, 2475000.00, 0, 'exclusive', 2475000.00, 18.00, 123750.00, 2598750.00, '2026-08-21', 'active', 1098750.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 12, 'monthly', '2026-09-04', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-08-04 02:00:19', '2026-08-04 05:05:57', NULL),
(4, 'HID-SH-F6-INDU', 1, 52, 7, NULL, '2026-08-05', '2027-07-14', 4000.00, 2292000.00, 0, 'exclusive', 2292000.00, 18.00, 275040.00, 2567040.00, '2026-08-05', 'active', 1567040.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-05', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-08-05 05:06:21', '2026-08-05 05:06:21', NULL),
(5, 'HID-AP-E1-RASN', 1, 53, 8, NULL, '2026-08-05', '2027-07-14', 2500.00, 1937500.00, 0, 'exclusive', 1937500.00, 18.00, 96875.00, 2034375.00, '2026-08-05', 'active', 1034375.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-05', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-08-05 05:14:41', '2026-08-05 05:14:41', NULL),
(6, 'HID-PA-FOSLOT2-RASN', 1, 43, 8, NULL, '2026-08-05', '2027-07-14', 0.00, 400000.00, 0, 'none', 400000.00, NULL, 0.00, 400000.00, '2026-08-05', 'active', 200000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 2, 'monthly', '2026-09-05', NULL, 0, NULL, NULL, 0.00, 0.00, NULL, NULL, '2026-08-05 05:21:25', '2026-08-05 05:21:25', NULL),
(7, 'HID-SH-F3-ANCH', 1, 50, 9, NULL, '2026-08-13', '2026-08-13', 2000.00, 1992000.00, 0, 'exclusive', 1992000.00, 18.00, 84600.00, 2076600.00, '2026-08-13', 'cancelled', 1038300.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 12, 'monthly', '2026-09-13', NULL, 0, 'cash tite', NULL, 200000.00, 838300.00, 'ghghfgf', NULL, '2026-08-13 06:11:30', '2026-08-13 06:13:10', NULL),
(8, 'HID-SH-F4-JOLL', 1, 51, 10, NULL, '2026-08-13', '2026-08-13', 22000.00, 27368000.00, 0, 'none', 27368000.00, NULL, 0.00, 27368000.00, '2026-08-13', 'exchanged', 27368000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-13', NULL, 0, 'FDGFDG', NULL, 0.00, 0.00, 'done', NULL, '2026-08-13 06:45:17', '2026-08-13 07:05:02', NULL),
(9, 'HID-SH-G12-JOLL', 1, 12, 10, NULL, '2026-08-13', NULL, 22000.00, 17732000.00, 0, 'none', 17732000.00, NULL, 0.00, 17732000.00, '2026-08-13', 'active', 3161400.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 1, 'monthly', '2026-09-13', NULL, 0, NULL, NULL, 0.00, 0.00, 'Exchanged from sale HID-SH-F4-JOLL. FDGFDG', NULL, '2026-08-13 07:05:02', '2026-08-13 07:05:02', NULL),
(10, 'HID-SH-F2-ARAV', 1, 49, 11, NULL, '2026-08-14', '2026-08-14', 22000.00, 23540000.00, 0, 'exclusive', 23540000.00, 18.00, 1177000.00, 24717000.00, '2026-08-14', 'active', 12358500.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 12, 'monthly', '2026-09-14', NULL, 0, NULL, NULL, 0.00, 0.00, 'gdhf', NULL, '2026-08-14 05:03:34', '2026-08-14 05:03:34', NULL),
(11, 'HID-SH-F1-ARUN', 1, 48, 12, NULL, '2026-08-17', '2026-08-17', 22000.00, 21472000.00, 0, 'none', 21472000.00, NULL, 0.00, 21472000.00, '2026-08-17', 'active', 10736000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-17', NULL, 0, NULL, NULL, 0.00, 0.00, 'jhfjf', NULL, '2026-08-17 01:12:41', '2026-08-17 01:12:41', NULL),
(12, 'HID-SH-F4-INDU', 1, 51, 7, NULL, '2026-08-17', '2026-08-17', 22000.00, 27368000.00, 0, 'exclusive', 27368000.00, 18.00, 1368400.00, 28736400.00, '2026-08-17', 'active', 14368200.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-17', NULL, 0, NULL, NULL, 0.00, 0.00, 'ghfdhgfd', NULL, '2026-08-17 01:47:45', '2026-08-17 01:47:45', NULL),
(13, 'HID-SH-G1-SARA', 1, 1, 13, NULL, '2026-08-17', '2026-08-17', 22000.00, 108746000.00, 0, 'exclusive', 108746000.00, 18.00, 5437300.00, 114183300.00, '2026-08-17', 'active', 57091650.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 12, 'monthly', '2026-09-17', NULL, 0, NULL, NULL, 0.00, 0.00, 'sdfdtgfd', NULL, '2026-08-17 03:28:08', '2026-08-17 03:28:08', NULL),
(14, 'HID-SH-G6-MANU', 1, 6, 14, NULL, '2026-08-17', '2026-08-17', 22000.00, 5956000.00, 0, 'exclusive', 5956000.00, 18.00, 292800.00, 6248800.00, '2026-08-17', 'active', 3124400.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-17', NULL, 0, NULL, NULL, 0.00, 0.00, 'tfeyfhgf', NULL, '2026-08-17 06:43:07', '2026-08-17 06:43:07', NULL),
(15, 'HID-SH-G11-DILS', 1, 11, 15, NULL, '2026-08-17', '2026-07-18', 22000.00, 6820000.00, 0, 'exclusive', 6820000.00, 18.00, 341000.00, 7161000.00, '2026-08-17', 'active', 3580500.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-06-10', NULL, 0, NULL, NULL, 0.00, 0.00, 'GBHBJG', NULL, '2026-08-18 01:44:17', '2026-08-18 01:44:17', NULL),
(16, 'HID-SH-G16-JABI', 1, 16, 16, NULL, '2026-03-18', '2026-03-18', 22000.00, 9614000.00, 0, 'exclusive', 9614000.00, 18.00, 480700.00, 10094700.00, '2026-03-18', 'active', 5047350.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-03-19', NULL, 0, NULL, NULL, 0.00, 0.00, 'gjghjghjh', NULL, '2026-08-18 02:07:11', '2026-08-18 02:07:11', NULL),
(17, 'HID-SH-F3-INDU', 1, 50, 7, NULL, '2026-08-20', '2026-08-05', 22000.00, 23650000.00, 0, 'none', 23650000.00, NULL, 0.00, 23650000.00, '2026-08-20', 'active', 11825000.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-20', NULL, 0, NULL, NULL, 0.00, 0.00, 'fdhgfhj', NULL, '2026-08-20 00:39:43', '2026-08-20 00:39:43', NULL),
(18, 'HID-SH-G8-NAJM', 1, 8, 20, NULL, '2026-08-20', '2026-08-20', 22000.00, 9946000.00, 0, 'exclusive', 9946000.00, 18.00, 312400.00, 10258400.00, '2026-08-20', 'active', 5129200.00, 0, 0.00, NULL, NULL, NULL, NULL, 'emi', 'equal', 10, 'monthly', '2026-09-20', NULL, 0, NULL, NULL, 0.00, 0.00, 'dgfdgfhg', NULL, '2026-08-20 00:42:31', '2026-08-20 00:42:31', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales_executives`
--

INSERT INTO `hindustansystem_sales_executives` (`id`, `name`, `email`, `avatar_url`, `created_at`, `updated_at`) VALUES
(3, 'Vikram Sharma', 'vikram@hindustan.com', 'VS', '2026-08-19 00:55:58', '2026-08-19 00:55:58'),
(4, 'Priya Nair', 'priya@hindustan.com', 'PN', '2026-08-19 00:55:58', '2026-08-19 00:55:58');

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
(1, 14, 'Furnishing,painting', 100000.00, 'none', 0.00, 0.00, 100000.00, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(2, 18, 'addon', 50000.00, 'none', 0.00, 0.00, 50000.00, '2026-08-20 00:42:31', '2026-08-20 00:42:31');

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
(1, 2, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(2, 3, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-04 02:00:19', '2026-08-04 02:00:19'),
(3, 4, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(4, 5, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(5, 6, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-05 05:21:25', '2026-08-05 05:21:25'),
(6, 7, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-13 06:11:30', '2026-08-13 06:11:30'),
(7, 7, 'active', 'cancelled', 'cancelled', 'cash tite', '{\"old_sale\":{\"id\":7,\"sale_number\":\"HID-SH-F3-ANCH\",\"sale_date\":\"2026-08-13\",\"agreement_date\":\"2026-08-13\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":12,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-09-13\",\"rate_per_sqft\":2000,\"sale_amount\":1992000,\"gst_type\":\"exclusive\",\"gst_amount\":84600,\"base_amount\":1992000,\"total_amount\":2076600,\"remaining_balance\":1038300,\"total_paid\":1038300},\"old_unit\":{\"id\":50,\"door_no\":\"F 3\",\"floor_name\":\"Floor 1\",\"unit_type_name\":\"Shop\",\"built_up_area\":846,\"expected_sale_amount\":18612000},\"customer\":{\"id\":9,\"name\":\"Anchumol\",\"phone\":\"565756756\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan Infra Developers Pvt. Ltd\"},\"receipts\":[{\"id\":27,\"receipt_number\":null,\"receipt_date\":\"2026-08-13\",\"amount\":1038300,\"payment_mode\":\"Cheque\",\"reference_no\":\"CHQ454\",\"status\":\"posted\"}],\"installments\":[{\"id\":80,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-08-13\",\"amount\":1038300,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":81,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-09-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":82,\"installment_no\":2,\"label\":\"EMI 2\",\"due_date\":\"2026-10-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":83,\"installment_no\":3,\"label\":\"EMI 3\",\"due_date\":\"2026-11-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":84,\"installment_no\":4,\"label\":\"EMI 4\",\"due_date\":\"2026-12-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":85,\"installment_no\":5,\"label\":\"EMI 5\",\"due_date\":\"2027-01-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":86,\"installment_no\":6,\"label\":\"EMI 6\",\"due_date\":\"2027-02-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":87,\"installment_no\":7,\"label\":\"EMI 7\",\"due_date\":\"2027-03-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":88,\"installment_no\":8,\"label\":\"EMI 8\",\"due_date\":\"2027-04-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":89,\"installment_no\":9,\"label\":\"EMI 9\",\"due_date\":\"2027-05-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":90,\"installment_no\":10,\"label\":\"EMI 10\",\"due_date\":\"2027-06-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":91,\"installment_no\":11,\"label\":\"EMI 11\",\"due_date\":\"2027-07-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":92,\"installment_no\":12,\"label\":\"EMI 12\",\"due_date\":\"2027-08-13\",\"amount\":86525,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}]}', 1, '2026-08-13 06:13:10', '2026-08-13 06:13:10'),
(8, 8, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-13 06:45:17', '2026-08-13 06:45:17'),
(9, 8, 'active', 'exchanged', 'exchanged', 'FDGFDG', '{\"old_sale\":{\"id\":8,\"sale_number\":\"HID-SH-F4-JOLL\",\"sale_date\":\"2026-08-13\",\"agreement_date\":\"2026-08-13\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":10,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-09-13\",\"rate_per_sqft\":22000,\"sale_amount\":27368000,\"gst_type\":\"none\",\"gst_amount\":0,\"base_amount\":27368000,\"total_amount\":27368000,\"remaining_balance\":13684000,\"total_paid\":13684000},\"old_unit\":{\"id\":51,\"door_no\":\"F 4\",\"floor_name\":\"Floor 1\",\"unit_type_name\":\"Shop\",\"built_up_area\":1244,\"expected_sale_amount\":27368000},\"customer\":{\"id\":10,\"name\":\"Jolly Joy\",\"phone\":\"64754654\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan Infra Developers Pvt. Ltd\"},\"receipts\":[{\"id\":28,\"receipt_number\":null,\"receipt_date\":\"2026-08-13\",\"amount\":13684000,\"payment_mode\":\"Cheque\",\"reference_no\":\"CHQ-8786576\",\"status\":\"posted\"}],\"installments\":[{\"id\":93,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-08-13\",\"amount\":13684000,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":94,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-09-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":95,\"installment_no\":2,\"label\":\"EMI 2\",\"due_date\":\"2026-10-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":96,\"installment_no\":3,\"label\":\"EMI 3\",\"due_date\":\"2026-11-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":97,\"installment_no\":4,\"label\":\"EMI 4\",\"due_date\":\"2026-12-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":98,\"installment_no\":5,\"label\":\"EMI 5\",\"due_date\":\"2027-01-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":99,\"installment_no\":6,\"label\":\"EMI 6\",\"due_date\":\"2027-02-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":100,\"installment_no\":7,\"label\":\"EMI 7\",\"due_date\":\"2027-03-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":101,\"installment_no\":8,\"label\":\"EMI 8\",\"due_date\":\"2027-04-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":102,\"installment_no\":9,\"label\":\"EMI 9\",\"due_date\":\"2027-05-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":103,\"installment_no\":10,\"label\":\"EMI 10\",\"due_date\":\"2027-06-13\",\"amount\":1368400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}],\"exchange_meta\":{\"target_unit_id\":12,\"target_door_no\":\"G 12\",\"carry_forward\":true,\"exchange_reason\":\"FDGFDG\",\"exchanged_at\":\"2026-08-13 12:35:02\",\"exchanged_by_user\":\"Owner\"}}', 1, '2026-08-13 07:05:02', '2026-08-13 07:05:02'),
(10, 9, NULL, 'active', 'created', 'Created via unit exchange from sale HID-SH-F4-JOLL', '{\"old_sale\":{\"id\":9,\"sale_number\":\"HID-SH-G12-JOLL\",\"sale_date\":\"2026-08-13\",\"agreement_date\":\"2026-08-13\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":1,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-09-13\",\"rate_per_sqft\":22000,\"sale_amount\":17732000,\"gst_type\":\"none\",\"gst_amount\":0,\"base_amount\":17732000,\"total_amount\":17732000,\"remaining_balance\":3161400,\"total_paid\":14570600},\"old_unit\":{\"id\":12,\"door_no\":\"G 12\",\"floor_name\":\"Ground Floor\",\"unit_type_name\":\"Shop\",\"built_up_area\":806,\"expected_sale_amount\":17732000},\"customer\":{\"id\":10,\"name\":\"Jolly Joy\",\"phone\":\"64754654\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan Infra Developers Pvt. Ltd\"},\"receipts\":[{\"id\":28,\"receipt_number\":null,\"receipt_date\":\"2026-08-13\",\"amount\":13684000,\"payment_mode\":\"Cheque\",\"reference_no\":\"CHQ-8786576\",\"status\":\"posted\"},{\"id\":29,\"receipt_number\":null,\"receipt_date\":\"2026-08-13\",\"amount\":886600,\"payment_mode\":\"Cash\",\"reference_no\":null,\"status\":\"posted\"}],\"installments\":[{\"id\":104,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-08-13\",\"amount\":14570600,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":105,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-09-13\",\"amount\":3161400,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}]}', 1, '2026-08-13 07:05:02', '2026-08-13 07:05:02'),
(11, 10, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-14 05:03:35', '2026-08-14 05:03:35'),
(12, 11, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(13, 12, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(14, 13, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(15, 14, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(16, 15, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(17, 16, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(18, 17, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-20 00:39:44', '2026-08-20 00:39:44'),
(19, 18, NULL, 'active', 'created', NULL, NULL, 1, '2026-08-20 00:42:31', '2026-08-20 00:42:31');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_units`
--

INSERT INTO `hindustansystem_sale_units` (`id`, `sale_id`, `unit_id`, `wing`, `rate_per_sqft`, `area_sqft`, `base_amount`, `gst_type`, `gst_percentage`, `gst_amount`, `line_total`, `brokerage_type`, `brokerage_value`, `brokerage_amount`, `created_at`, `updated_at`) VALUES
(1, 2, 30, NULL, 8000.00, 821.00, 6568000.00, 'exclusive', 12.00, 788160.00, 7356160.00, NULL, NULL, 0.00, '2026-08-04 01:50:02', '2026-08-04 01:50:02'),
(2, 3, 35, NULL, 3000.00, 725.00, 2175000.00, 'exclusive', 5.00, 108750.00, 2283750.00, NULL, NULL, 0.00, '2026-08-04 02:00:19', '2026-08-04 04:56:46'),
(3, 3, 44, NULL, 0.00, 1.00, 300000.00, 'exclusive', 5.00, 15000.00, 315000.00, NULL, NULL, 0.00, '2026-08-04 04:56:46', '2026-08-04 04:56:46'),
(4, 4, 52, NULL, 4000.00, 573.00, 2292000.00, 'exclusive', 12.00, 275040.00, 2567040.00, NULL, NULL, 0.00, '2026-08-05 05:06:21', '2026-08-05 05:06:21'),
(5, 5, 53, NULL, 2500.00, 775.00, 1937500.00, 'exclusive', 5.00, 96875.00, 2034375.00, NULL, NULL, 0.00, '2026-08-05 05:14:41', '2026-08-05 05:14:41'),
(6, 6, 43, NULL, 0.00, 1.00, 400000.00, 'none', 0.00, 0.00, 400000.00, NULL, NULL, 0.00, '2026-08-05 05:21:25', '2026-08-05 05:21:25'),
(7, 7, 50, NULL, 2000.00, 846.00, 1692000.00, 'exclusive', 5.00, 84600.00, 1776600.00, NULL, NULL, 0.00, '2026-08-13 06:11:30', '2026-08-13 06:11:30'),
(8, 7, 46, NULL, 0.00, 1.00, 300000.00, 'none', 0.00, 0.00, 300000.00, NULL, NULL, 0.00, '2026-08-13 06:11:30', '2026-08-13 06:11:30'),
(9, 8, 51, NULL, 22000.00, 1244.00, 27368000.00, 'none', 0.00, 0.00, 27368000.00, NULL, NULL, 0.00, '2026-08-13 06:45:17', '2026-08-13 06:45:17'),
(10, 9, 12, NULL, 22000.00, 806.00, 17732000.00, 'none', 0.00, 0.00, 17732000.00, NULL, NULL, 0.00, '2026-08-13 07:05:02', '2026-08-13 07:05:02'),
(11, 10, 49, NULL, 22000.00, 1070.00, 23540000.00, 'exclusive', 5.00, 1177000.00, 24717000.00, NULL, NULL, 0.00, '2026-08-14 05:03:34', '2026-08-14 05:03:34'),
(12, 11, 48, NULL, 22000.00, 976.00, 21472000.00, 'none', 0.00, 0.00, 21472000.00, NULL, NULL, 0.00, '2026-08-17 01:12:41', '2026-08-17 01:12:41'),
(13, 12, 51, NULL, 22000.00, 1244.00, 27368000.00, 'exclusive', 5.00, 1368400.00, 28736400.00, NULL, NULL, 0.00, '2026-08-17 01:47:45', '2026-08-17 01:47:45'),
(14, 13, 1, NULL, 22000.00, 4943.00, 108746000.00, 'exclusive', 5.00, 5437300.00, 114183300.00, NULL, NULL, 0.00, '2026-08-17 03:28:08', '2026-08-17 03:28:08'),
(15, 14, 6, NULL, 22000.00, 248.00, 5456000.00, 'exclusive', 5.00, 272800.00, 5728800.00, NULL, NULL, 0.00, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(16, 14, 42, NULL, 0.00, 1.00, 400000.00, 'exclusive', 5.00, 20000.00, 420000.00, NULL, NULL, 0.00, '2026-08-17 06:43:07', '2026-08-17 06:43:07'),
(17, 15, 11, NULL, 22000.00, 310.00, 6820000.00, 'exclusive', 5.00, 341000.00, 7161000.00, NULL, NULL, 0.00, '2026-08-18 01:44:17', '2026-08-18 01:44:17'),
(18, 16, 16, NULL, 22000.00, 437.00, 9614000.00, 'exclusive', 5.00, 480700.00, 10094700.00, NULL, NULL, 0.00, '2026-08-18 02:07:11', '2026-08-18 02:07:11'),
(19, 17, 50, NULL, 22000.00, 846.00, 18612000.00, 'none', 0.00, 0.00, 18612000.00, NULL, NULL, 0.00, '2026-08-20 00:39:43', '2026-08-20 00:39:43'),
(20, 17, 7, NULL, 22000.00, 229.00, 5038000.00, 'none', 0.00, 0.00, 5038000.00, NULL, NULL, 0.00, '2026-08-20 00:39:43', '2026-08-20 00:39:43'),
(21, 18, 8, NULL, 22000.00, 284.00, 6248000.00, 'exclusive', 5.00, 312400.00, 6560400.00, NULL, NULL, 0.00, '2026-08-20 00:42:31', '2026-08-20 00:42:31'),
(22, 18, 36, NULL, 6000.00, 608.00, 3648000.00, 'none', 0.00, 0.00, 3648000.00, NULL, NULL, 0.00, '2026-08-20 00:42:31', '2026-08-20 00:42:31');

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
('ZuhesBOxych4Lo9EMSCWS7Ryf4CsDw16AoKlYaTE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJIcFExNFFFTjJmbGdFUjRZcVhGYkNlT2FSSHFxYmRTeWQzVHRsa0ZJIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2FuLWRpc2J1cnNhbHMiLCJyb3V0ZSI6ImxvYW4tZGlzYnVyc2Fscy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1787654018);

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
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_rate_logs`
--

INSERT INTO `hindustansystem_unit_rate_logs` (`id`, `unit_id`, `rate`, `revision_type`, `change_details`, `amount_change`, `effective_from`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:55:55'),
(2, 2, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:56:36'),
(3, 3, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:56:58'),
(4, 4, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:57:51'),
(5, 5, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:58:13'),
(6, 6, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:58:31'),
(7, 7, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:58:52'),
(8, 8, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:59:11'),
(9, 9, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:59:30'),
(10, 10, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 00:59:58'),
(11, 11, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:00:14'),
(12, 12, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:00:44'),
(13, 13, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:01:06'),
(14, 14, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:01:30'),
(15, 15, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:01:51'),
(16, 16, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:02:17'),
(17, 17, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:03:38'),
(18, 18, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:04:24'),
(19, 19, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:04:52'),
(20, 20, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:05:12'),
(21, 21, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:05:32'),
(22, 22, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:06:10'),
(23, 23, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:06:34'),
(24, 24, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:07:02'),
(25, 25, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:07:20'),
(26, 26, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:07:48'),
(27, 27, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:08:08'),
(28, 28, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:08:34'),
(29, 29, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:08:59'),
(30, 30, 22000.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:09:18'),
(31, 31, 5800.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:32:35'),
(32, 32, 4700.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:34:15'),
(33, 33, 3800.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:36:39'),
(34, 34, 3500.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:37:16'),
(35, 35, 3500.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:37:44'),
(36, 36, 5800.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:38:04'),
(37, 37, 3700.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:38:37'),
(38, 38, 3500.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:39:27'),
(39, 39, 3500.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:39:49'),
(40, 40, 5500.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:40:09'),
(41, 41, 3600.00, NULL, NULL, NULL, '2026-08-01', 1, 'Initial Rate', '2026-08-01 01:40:35'),
(42, 31, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:14:31'),
(43, 32, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:14:50'),
(44, 33, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:14:59'),
(45, 34, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:15:08'),
(46, 35, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:15:19'),
(47, 36, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:15:40'),
(48, 37, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:15:50'),
(49, 38, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:16:03'),
(50, 39, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:16:29'),
(51, 40, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:16:38'),
(52, 41, 6000.00, NULL, NULL, NULL, '2026-08-01', 1, NULL, '2026-08-01 02:16:48'),
(53, 42, 400000.00, NULL, NULL, NULL, '2026-08-04', 1, 'Initial Rate', '2026-08-04 03:40:53'),
(54, 43, 400000.00, NULL, NULL, NULL, '2026-08-04', 1, 'Initial Rate', '2026-08-04 03:41:14'),
(55, 44, 300000.00, NULL, NULL, NULL, '2026-08-04', 1, 'Initial Rate', '2026-08-04 03:41:29'),
(56, 45, 300000.00, NULL, NULL, NULL, '2026-08-04', 1, 'Initial Rate', '2026-08-04 03:41:44'),
(57, 46, 300000.00, NULL, NULL, NULL, '2026-08-04', 1, 'Initial Rate', '2026-08-04 03:41:59'),
(58, 47, 400000.00, NULL, NULL, NULL, '2026-08-04', 1, 'Initial Rate', '2026-08-04 03:42:13'),
(59, 48, 22000.00, NULL, NULL, NULL, '2026-08-05', 1, 'Initial Rate', '2026-08-05 03:08:45'),
(60, 49, 22000.00, NULL, NULL, NULL, '2026-08-05', 1, 'Initial Rate', '2026-08-05 03:09:07'),
(61, 50, 22000.00, NULL, NULL, NULL, '2026-08-05', 1, 'Initial Rate', '2026-08-05 03:09:30'),
(62, 51, 22000.00, NULL, NULL, NULL, '2026-08-05', 1, 'Initial Rate', '2026-08-05 03:10:45'),
(63, 52, 22000.00, NULL, NULL, NULL, '2026-08-05', 1, 'Initial Rate', '2026-08-05 03:11:42'),
(64, 53, 6000.00, NULL, NULL, NULL, '2026-08-05', 1, 'Initial Rate', '2026-08-05 05:12:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_status_logs`
--

INSERT INTO `hindustansystem_unit_status_logs` (`id`, `unit_id`, `from_status`, `to_status`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:55:55'),
(2, 2, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:56:36'),
(3, 3, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:56:58'),
(4, 4, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:57:51'),
(5, 5, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:58:13'),
(6, 6, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:58:31'),
(7, 7, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:58:52'),
(8, 8, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:59:11'),
(9, 9, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:59:30'),
(10, 10, NULL, 'available', 1, 'Unit creation', '2026-08-01 00:59:58'),
(11, 11, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:00:14'),
(12, 12, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:00:44'),
(13, 13, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:01:06'),
(14, 14, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:01:30'),
(15, 15, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:01:51'),
(16, 16, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:02:17'),
(17, 17, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:03:38'),
(18, 18, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:04:24'),
(19, 19, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:04:52'),
(20, 20, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:05:12'),
(21, 21, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:05:32'),
(22, 22, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:06:10'),
(23, 23, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:06:34'),
(24, 24, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:07:02'),
(25, 25, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:07:20'),
(26, 26, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:07:48'),
(27, 27, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:08:08'),
(28, 28, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:08:34'),
(29, 29, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:08:59'),
(30, 30, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:09:18'),
(31, 31, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:32:35'),
(32, 32, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:34:15'),
(33, 33, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:36:39'),
(34, 34, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:37:16'),
(35, 35, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:37:44'),
(36, 36, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:38:04'),
(37, 37, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:38:37'),
(38, 38, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:39:27'),
(39, 39, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:39:49'),
(40, 40, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:40:09'),
(41, 41, NULL, 'available', 1, 'Unit creation', '2026-08-01 01:40:35'),
(42, 42, NULL, 'available', 1, 'Unit creation', '2026-08-04 03:40:53'),
(43, 43, NULL, 'available', 1, 'Unit creation', '2026-08-04 03:41:14'),
(44, 44, NULL, 'available', 1, 'Unit creation', '2026-08-04 03:41:29'),
(45, 45, NULL, 'available', 1, 'Unit creation', '2026-08-04 03:41:44'),
(46, 46, NULL, 'available', 1, 'Unit creation', '2026-08-04 03:41:59'),
(47, 47, NULL, 'available', 1, 'Unit creation', '2026-08-04 03:42:13'),
(48, 48, NULL, 'available', 1, 'Unit creation', '2026-08-05 03:08:45'),
(49, 49, NULL, 'available', 1, 'Unit creation', '2026-08-05 03:09:07'),
(50, 50, NULL, 'available', 1, 'Unit creation', '2026-08-05 03:09:30'),
(51, 51, NULL, 'available', 1, 'Unit creation', '2026-08-05 03:10:45'),
(52, 52, NULL, 'available', 1, 'Unit creation', '2026-08-05 03:11:42'),
(53, 53, NULL, 'available', 1, 'Unit creation', '2026-08-05 05:12:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_types`
--

INSERT INTO `hindustansystem_unit_types` (`id`, `project_id`, `name`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'Shop', 'commercial', 1, '2026-07-06 03:53:39', '2026-07-08 04:40:33'),
(5, 1, 'Parking', 'parking', 1, '2026-07-06 03:53:39', '2026-07-08 04:40:39'),
(6, 1, 'Apartment', 'residential', 1, '2026-07-10 02:06:51', '2026-07-10 02:06:51'),
(7, NULL, 'Flat', 'residential', 1, '2026-08-19 00:55:51', '2026-08-19 00:55:51'),
(8, NULL, 'Office', 'commercial', 1, '2026-08-19 00:55:51', '2026-08-19 00:55:51'),
(9, NULL, 'Villa', 'residential', 1, '2026-08-19 00:55:51', '2026-08-19 00:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_users`
--

DROP TABLE IF EXISTS `hindustansystem_users`;
CREATE TABLE IF NOT EXISTS `hindustansystem_users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  KEY `hindustansystem_users_system_id_foreign` (`system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_users`
--

INSERT INTO `hindustansystem_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `system_id`, `phone`, `employee_code`, `status`, `must_change_password`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Owner', 'owner@hindustan.com', '2026-08-19 00:55:51', '$2y$12$YKVyC2KTupBSMKBDMtt64eweXGIgHrBG9OpEZmDRdMuSGewCRaOH.', 'Tr8zrj4dtFB3NPBBAPhlKqFOc91lUP1LKLUKhyMPLfeln1wDPh8gP5GUqdhe', '2026-07-06 03:53:39', '2026-08-19 00:55:51', 1, '+91 99999 99999', 'EMP-001', 'active', 0, NULL, NULL),
(2, 'Rajesh Accountant (IN)', 'accountant.in@hindustan.com', '2026-08-19 00:55:52', '$2y$12$eTx9xaiIFJ3b9uFJWsggaOtzcX9hAbTMNqWiNV4hMijrNguK0A7xS', NULL, '2026-07-06 03:53:40', '2026-08-19 00:55:52', 1, '+91 98765 00001', 'EMP-IN-ACC01', 'active', 0, NULL, NULL),
(3, 'Omar Accountant (UAE)', 'accountant.ae@hindustan.com', '2026-08-19 00:55:54', '$2y$12$/tPr3VzgbmTF3KiTNJwWFebMWNc1H6P9o/gMWKbLwyuJyzpE6O7ru', NULL, '2026-07-06 03:53:40', '2026-08-19 00:55:54', 2, '+971 50 123 4567', 'EMP-AE-ACC01', 'active', 0, NULL, NULL),
(4, 'Vikram Sales (IN)', 'sales.in@hindustan.com', '2026-08-19 00:55:55', '$2y$12$TIpMEjuaGjaDa0PqutU6fOkRhknSQxf/rvGmzM24WFGyEEwZv3z2i', NULL, '2026-07-06 03:53:41', '2026-08-19 00:55:55', 1, '+91 98765 00002', 'EMP-IN-SAL01', 'active', 0, NULL, NULL),
(5, 'Amit Site (IN)', 'site.in@hindustan.com', '2026-08-19 00:55:56', '$2y$12$toQLQnJ9sDIKmQ4QjaZ.eeyago2hxherxsC7NrrXtHpQVLSuKSjra', NULL, '2026-07-06 03:53:41', '2026-08-19 00:55:56', 1, '+91 98765 00003', 'EMP-IN-SIT01', 'active', 0, NULL, NULL);

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
-- Table structure for table `hindustansystem_vouchers`
--

DROP TABLE IF EXISTS `hindustansystem_vouchers`;
CREATE TABLE IF NOT EXISTS `hindustansystem_vouchers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `system_id` bigint UNSIGNED NOT NULL,
  `voucher_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `reference_no` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `edited_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `reversal_of_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hindustansystem_vouchers_system_id_voucher_number_unique` (`system_id`,`voucher_number`),
  KEY `hindustansystem_vouchers_created_by_foreign` (`created_by`),
  KEY `hindustansystem_vouchers_edited_by_foreign` (`edited_by`),
  KEY `hindustansystem_vouchers_reversal_of_id_foreign` (`reversal_of_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_vouchers`
--

INSERT INTO `hindustansystem_vouchers` (`id`, `system_id`, `voucher_number`, `type`, `date`, `narration`, `reference_no`, `created_by`, `edited_by`, `status`, `reversal_of_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'RC-2026-00001', 'Receipt', '2026-08-05', NULL, '{\"project_id\":\"1\",\"payment_mode\":null,\"gst_rate\":0,\"split_active\":true,\"source_receipt_id\":26,\"allocations\":[{\"type\":\"partner\",\"target_id\":1,\"amount\":115000,\"remarks\":\"Partner Share (57.5%) allocation\",\"is_locked\":false},{\"type\":\"partner\",\"target_id\":2,\"amount\":85000,\"remarks\":\"Partner Share (42.5%) allocation\",\"is_locked\":false}]}', 1, NULL, 'Posted', NULL, '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(2, 1, 'JV-BILL-BR/6575-1786701065', 'Journal', '2026-08-14', 'Bill registered: BR/6575 for Supplier AZUS', NULL, 1, NULL, 'Posted', NULL, '2026-08-14 04:21:05', '2026-08-14 04:21:05'),
(3, 1, 'PV-00001', 'Payment', '2026-08-20', 'Staggered RA Bill Disbursement for #RA--001 (AZUS)', 'CVCXV', 1, NULL, 'Posted', NULL, '2026-08-20 01:50:11', '2026-08-20 01:50:11'),
(4, 1, 'PV-00002', 'Payment', '2026-08-20', 'Staggered RA Bill Disbursement for #RA--001 (AZUS)', 'CVB', 1, NULL, 'Posted', NULL, '2026-08-20 01:51:12', '2026-08-20 01:51:12'),
(5, 1, 'PCON-6A8C37CDAC545', 'Contra', '2026-08-24', 'fhfgfh', 'CHQ645435', 1, NULL, 'Posted', NULL, '2026-08-24 06:53:41', '2026-08-24 06:53:41'),
(6, 1, 'PCON-6A8C3B3851CBC', 'Contra', '2026-08-24', 'tfgyhfgyh', 'CHQ565534', 1, NULL, 'Posted', NULL, '2026-08-24 07:08:16', '2026-08-24 07:08:16'),
(7, 1, 'PCON-00003', 'Contra', '2026-08-24', 'yfghfghgfhgfh', 'CHQ35435', 1, NULL, 'Posted', NULL, '2026-08-24 07:19:23', '2026-08-24 07:19:23'),
(8, 1, 'PCON-00004', 'Contra', '2026-08-25', 'cash withdrawn karnataka bank account', 'CHQ765567', 1, NULL, 'Posted', NULL, '2026-08-25 00:10:09', '2026-08-25 00:10:09'),
(9, 1, 'PCON-00005', 'Contra', '2026-08-25', 'cash withdrwal', 'CHQ65656', 1, NULL, 'Posted', NULL, '2026-08-25 00:47:28', '2026-08-25 00:47:28'),
(10, 1, 'PCON-00006', 'Contra', '2026-08-25', 'contra with drawal', 'CHQ343', 1, NULL, 'Posted', NULL, '2026-08-25 00:56:21', '2026-08-25 00:56:21');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_voucher_lines`
--

INSERT INTO `hindustansystem_voucher_lines` (`id`, `voucher_id`, `account_id`, `debit`, `credit`, `line_narration`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 200000.00, 0.00, 'Debit to Destination Account', '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(2, 1, 21, 0.00, 200000.00, 'Credit to Customer Ledger', '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(3, 1, 2, 115000.00, 0.00, 'Partner payout share drawings: Basheer (Partner Share (57.5%) allocation)', '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(4, 1, 7, 0.00, 115000.00, 'Credit bank for Partner share drawings allocation', '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(5, 1, 3, 85000.00, 0.00, 'Partner payout share drawings: Pavoor (Partner Share (42.5%) allocation)', '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(6, 1, 7, 0.00, 85000.00, 'Credit bank for Partner share drawings allocation', '2026-08-06 06:22:18', '2026-08-06 06:22:18'),
(7, 2, 10, 525000.00, 0.00, 'Debit Site Expenses for bill #BR/6575', '2026-08-14 04:21:05', '2026-08-14 04:21:05'),
(8, 2, 71, 0.00, 525000.00, 'Credit Supplier payable for bill #BR/6575', '2026-08-14 04:21:05', '2026-08-14 04:21:05'),
(9, 3, 35, 100000.00, 0.00, NULL, '2026-08-20 01:50:11', '2026-08-20 01:50:11'),
(10, 3, 66, 0.00, 100000.00, NULL, '2026-08-20 01:50:11', '2026-08-20 01:50:11'),
(11, 4, 35, 50000.00, 0.00, NULL, '2026-08-20 01:51:12', '2026-08-20 01:51:12'),
(12, 4, 66, 0.00, 50000.00, NULL, '2026-08-20 01:51:12', '2026-08-20 01:51:12'),
(13, 5, 76, 0.00, 10000.00, 'Cash withdrawal from bank', '2026-08-24 06:53:41', '2026-08-24 06:53:41'),
(14, 5, 8, 10000.00, 0.00, 'Petty cash receipt via contra', '2026-08-24 06:53:41', '2026-08-24 06:53:41'),
(15, 6, 76, 0.00, 10000.00, 'Cash withdrawal from bank', '2026-08-24 07:08:16', '2026-08-24 07:08:16'),
(16, 6, 8, 10000.00, 0.00, 'Petty cash receipt via contra', '2026-08-24 07:08:16', '2026-08-24 07:08:16'),
(17, 7, 77, 0.00, 25000.00, 'Cash withdrawal from bank', '2026-08-24 07:19:23', '2026-08-24 07:19:23'),
(18, 7, 8, 25000.00, 0.00, 'Petty cash receipt via contra', '2026-08-24 07:19:23', '2026-08-24 07:19:23'),
(19, 8, 76, 0.00, 10000.00, 'Cash withdrawal from bank', '2026-08-25 00:10:09', '2026-08-25 00:10:09'),
(20, 8, 8, 10000.00, 0.00, 'Petty cash receipt via contra', '2026-08-25 00:10:09', '2026-08-25 00:10:09'),
(21, 9, 77, 0.00, 12000.00, 'Cash withdrawal from bank', '2026-08-25 00:47:28', '2026-08-25 00:47:28'),
(22, 9, 8, 12000.00, 0.00, 'Petty cash receipt via contra', '2026-08-25 00:47:28', '2026-08-25 00:47:28'),
(23, 10, 76, 0.00, 5000.00, 'Cash withdrawal from bank', '2026-08-25 00:56:21', '2026-08-25 00:56:21'),
(24, 10, 8, 5000.00, 0.00, 'Petty cash receipt via contra', '2026-08-25 00:56:21', '2026-08-25 00:56:21');

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
  UNIQUE KEY `hindustansystem_voucher_types_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Constraints for table `hindustansystem_collection_reminders`
--
ALTER TABLE `hindustansystem_collection_reminders`
  ADD CONSTRAINT `hindustansystem_collection_reminders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_collection_reminders_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `hindustansystem_customer_installments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_collection_reminders_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `hindustansystem_dms_documents_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_dms_documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL;

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
-- Constraints for table `hindustansystem_engineers`
--
ALTER TABLE `hindustansystem_engineers`
  ADD CONSTRAINT `hindustansystem_engineers_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `hindustansystem_loan_disbursals_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_loan_disbursals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_loan_disbursals_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_loan_disbursals_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_loan_disbursals_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_ra_bill_payments`
--
ALTER TABLE `hindustansystem_ra_bill_payments`
  ADD CONSTRAINT `hindustansystem_ra_bill_payments_ra_bill_id_foreign` FOREIGN KEY (`ra_bill_id`) REFERENCES `hindustansystem_ra_bills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_receipts`
--
ALTER TABLE `hindustansystem_receipts`
  ADD CONSTRAINT `hindustansystem_receipts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `hindustansystem_banks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_company_bank_account_id_foreign` FOREIGN KEY (`company_bank_account_id`) REFERENCES `hindustansystem_company_bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `hindustansystem_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_receipts_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `hindustansystem_payees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_receipts_realized_by_foreign` FOREIGN KEY (`realized_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipts_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `hindustansystem_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_receipts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_hindustan_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_receipt_realization_logs`
--
ALTER TABLE `hindustansystem_receipt_realization_logs`
  ADD CONSTRAINT `hindustansystem_receipt_realization_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_receipt_realization_logs_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `hindustansystem_receipts` (`id`) ON DELETE CASCADE;

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
