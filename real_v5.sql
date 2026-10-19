-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 19, 2026 at 05:11 AM
-- Server version: 10.4.34-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_v5`
--

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_accounting_settings`
--

CREATE TABLE `hindustansystem_accounting_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_accounting_settings`
--

INSERT INTO `hindustansystem_accounting_settings` (`id`, `key`, `value`, `locked_at`, `locked_by`, `created_at`, `updated_at`) VALUES
(1, 'is_opening_balance_locked', '0', NULL, NULL, '2026-09-16 11:42:15', '2026-09-16 11:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_accounts`
--

CREATE TABLE `hindustansystem_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` varchar(20) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(18, 1, 'SUP-ACC-0001', 'MKH INFRASTRUCTURE (Payable)', 'Liability', NULL, 1, '2026-10-17 04:42:04', '2026-10-17 04:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_activity_logs`
--

CREATE TABLE `hindustansystem_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(191) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_activity_logs`
--

INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 1, 'broker.created', NULL, NULL, 'Registered new broker \'noushad\' with default commission of 2%. Linked ledger account: BRK-052F0D.', '103.179.196.248', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-16 04:45:23'),
(2, 1, 1, 'broker.payout', NULL, NULL, 'Commission payout of ₹40,000.00 (Partially Paid) to broker \'noushad\' for Sale #TH-AP-EB-SOUM.', '103.179.196.248', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', '2026-10-16 05:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approvals`
--

CREATE TABLE `hindustansystem_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approvable_type` varchar(191) DEFAULT NULL,
  `approvable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approval_requests`
--

CREATE TABLE `hindustansystem_approval_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `priority` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `requester_name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approval_rules`
--

CREATE TABLE `hindustansystem_approval_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(191) NOT NULL,
  `min_role` varchar(191) NOT NULL,
  `threshold_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_approval_rules`
--

INSERT INTO `hindustansystem_approval_rules` (`id`, `module`, `min_role`, `threshold_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'discount', 'Owner', '100000.00', 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_banks`
--

CREATE TABLE `hindustansystem_banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(191) NOT NULL,
  `ifsc_code` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `payee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `bill_number` varchar(191) NOT NULL,
  `bill_type` varchar(191) DEFAULT NULL,
  `payment_terms` varchar(191) DEFAULT NULL,
  `place_of_supply` varchar(191) DEFAULT NULL,
  `expense_head` varchar(191) DEFAULT NULL,
  `bill_file` varchar(191) DEFAULT NULL,
  `bill_amount` decimal(15,2) NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending_approval',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bill_payments`
--

CREATE TABLE `hindustansystem_bill_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payee_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bookings`
--

CREATE TABLE `hindustansystem_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_number` varchar(191) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `sales_executive_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `broker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_rate_per_sqft` decimal(15,2) DEFAULT NULL,
  `gst_behavior` varchar(20) NOT NULL DEFAULT 'none',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_brokerages`
--

CREATE TABLE `hindustansystem_brokerages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `broker_id` bigint(20) UNSIGNED NOT NULL,
  `commission_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `commission_percent` decimal(5,2) DEFAULT NULL,
  `commission_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokerages`
--

INSERT INTO `hindustansystem_brokerages` (`id`, `sale_id`, `sale_unit_id`, `broker_id`, `commission_type`, `commission_percent`, `commission_amount`, `paid_amount`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 1, 'percentage', '2.00', '180920.00', '40000.00', 'partial', NULL, '2026-10-16 04:49:30', '2026-10-16 05:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_brokers`
--

CREATE TABLE `hindustansystem_brokers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `default_commission_pct` decimal(5,2) NOT NULL,
  `linked_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokers`
--

INSERT INTO `hindustansystem_brokers` (`id`, `system_id`, `name`, `default_commission_pct`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'noushad', '2.00', 1, '2026-10-16 04:45:23', '2026-10-16 04:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache`
--

CREATE TABLE `hindustansystem_cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_cache`
--

INSERT INTO `hindustansystem_cache` (`key`, `value`, `expiration`) VALUES
('hindustanerp-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:13:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"vouchers.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"expenses.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"expenses.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"collections.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"sales.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"sales.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:22:\"sales.discount.request\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"units.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"units.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"projects.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"projects.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:17:\"units.rate.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Owner\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"Sales\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"Site\";s:1:\"c\";s:3:\"web\";}}}', 1792472060),
('hindustanerp-cache-tabascohindusthan@gmail.com|103.179.196.236', 'i:1;', 1792041062),
('hindustanerp-cache-tabascohindusthan@gmail.com|103.179.196.236:timer', 'i:1792041062;', 1792041062),
('hindustanerp-cache-tfdtfr|61.1.223.224', 'i:1;', 1790844464),
('hindustanerp-cache-tfdtfr|61.1.223.224:timer', 'i:1790844464;', 1790844464);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache_locks`
--

CREATE TABLE `hindustansystem_cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_categories`
--

CREATE TABLE `hindustansystem_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(17, 'PAYMENT', NULL, 1, 'active', '2026-10-02 02:13:08', '2026-10-02 02:13:08');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_chart_of_accounts`
--

CREATE TABLE `hindustansystem_chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_code` varchar(20) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_type` enum('ASSET','LIABILITY','EQUITY','REVENUE','EXPENSE') NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `opening_balance_type` varchar(10) NOT NULL DEFAULT 'DR',
  `remarks` varchar(255) DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_chart_of_accounts`
--

INSERT INTO `hindustansystem_chart_of_accounts` (`id`, `account_code`, `account_name`, `account_type`, `opening_balance`, `opening_balance_type`, `remarks`, `project_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1010', 'Customer Receivable', 'ASSET', '0.00', 'DR', NULL, NULL, 1, '2026-09-25 05:40:29', '2026-09-25 05:40:29'),
(2, '2021', 'Output CGST Payable (2.5%)', 'LIABILITY', '0.00', 'DR', NULL, NULL, 1, '2026-09-25 05:40:29', '2026-09-25 05:40:29'),
(3, '2022', 'Output SGST Payable (2.5%)', 'LIABILITY', '0.00', 'DR', NULL, NULL, 1, '2026-09-25 05:40:29', '2026-09-25 05:40:29'),
(4, '1001', 'Bank Balances', 'ASSET', '0.00', 'DR', NULL, NULL, 1, '2026-09-25 05:44:37', '2026-09-25 05:44:37'),
(5, '2201', 'Bank Construction Loans / Project Credit Limits', 'LIABILITY', '0.00', 'DR', NULL, NULL, 1, '2026-09-25 05:44:37', '2026-09-25 05:44:37'),
(7, '2003', 'Agent Payable Liability', 'LIABILITY', '0.00', 'DR', NULL, NULL, 1, '2026-09-27 23:43:57', '2026-09-27 23:43:57'),
(8, '4002', 'Contractor Work Expenses', 'EXPENSE', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 00:23:17', '2026-09-28 00:23:17'),
(9, '2002', 'Contractor Payable', 'LIABILITY', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 00:23:17', '2026-09-28 00:23:17'),
(10, '4005', 'Construction Material Expenses', 'EXPENSE', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 01:15:24', '2026-09-28 01:15:24'),
(11, '4003', 'Site Office & Administrative', 'EXPENSE', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 01:35:27', '2026-09-28 01:35:27'),
(12, '4004', 'Machinery & Heavy Equipment Rental', 'EXPENSE', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 01:36:13', '2026-09-28 01:36:13'),
(13, '1002', 'Petty Cash Box', 'ASSET', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 02:14:38', '2026-09-28 02:14:38'),
(16, '3001', 'Share Capital', 'EQUITY', '0.00', 'DR', NULL, NULL, 1, '2026-09-28 05:39:16', '2026-09-28 05:39:16'),
(30, '4001', 'Agent Payable Expense', 'EXPENSE', '0.00', 'DR', NULL, NULL, 1, '2026-09-29 04:14:43', '2026-09-29 04:14:43'),
(31, '2202', 'Bank Loan Interest', 'EXPENSE', '0.00', 'DR', NULL, NULL, 1, '2026-10-01 07:48:05', '2026-10-01 07:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cheque_statuses`
--

CREATE TABLE `hindustansystem_cheque_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `color_code` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_collection_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `installment_id` bigint(20) UNSIGNED NOT NULL,
  `reminder_level` varchar(255) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `response` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_commission_entries`
--

CREATE TABLE `hindustansystem_commission_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `deal_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Accrued',
  `triggered_at` timestamp NULL DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_company_bank_accounts`
--

CREATE TABLE `hindustansystem_company_bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(191) NOT NULL,
  `account_name` varchar(191) DEFAULT NULL,
  `account_number` varchar(191) DEFAULT NULL,
  `account_type` enum('Current','Savings','Overdraft','Escrow','CC') NOT NULL DEFAULT 'Current',
  `ifsc_code` varchar(191) NOT NULL,
  `branch_name` varchar(191) DEFAULT NULL,
  `swift_code` varchar(191) DEFAULT NULL,
  `micr_code` varchar(191) DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `upi_id` varchar(191) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_company_bank_accounts`
--

INSERT INTO `hindustansystem_company_bank_accounts` (`id`, `bank_name`, `account_name`, `account_number`, `account_type`, `ifsc_code`, `branch_name`, `swift_code`, `micr_code`, `opening_balance`, `current_balance`, `upi_id`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Axis Bank 9752', 'Tabasco Hindustan', '9752', 'Current', '1234', NULL, NULL, NULL, '0.00', '3631347.17', NULL, 'active', 0, '2026-08-27 07:34:26', '2026-10-17 04:50:44'),
(2, 'Canara Bank 260', NULL, '260', 'Current', '1234', NULL, NULL, NULL, '0.00', '0.00', NULL, 'active', 0, '2026-08-27 07:34:56', '2026-10-05 06:13:42'),
(3, 'Karnataka Bank 213', NULL, '213', 'Current', '1234', NULL, NULL, NULL, '0.00', '0.00', NULL, 'active', 0, '2026-08-27 07:36:30', '2026-09-28 00:40:27'),
(4, 'Karnataka Bank 14801', NULL, NULL, 'Current', '1234', NULL, NULL, NULL, '0.00', '0.00', NULL, 'active', 0, '2026-08-27 07:37:16', '2026-10-12 04:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customers`
--

CREATE TABLE `hindustansystem_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `avatar_url` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` text DEFAULT NULL,
  `id_proof_type` varchar(50) DEFAULT NULL,
  `id_proof_number` varchar(50) DEFAULT NULL,
  `system` enum('india','uae') NOT NULL DEFAULT 'india',
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customers`
--

INSERT INTO `hindustansystem_customers` (`id`, `name`, `email`, `phone`, `avatar_url`, `created_at`, `updated_at`, `address`, `id_proof_type`, `id_proof_number`, `system`, `is_active`) VALUES
(1, 'jhancy', 'jhancydinuvj@gmail.com', '09048393993', NULL, '2026-10-15 05:19:49', '2026-10-15 05:19:49', NULL, NULL, NULL, 'india', 1),
(2, 'soumya', 'soumya@gmail.com', '8589964266', NULL, '2026-10-16 04:42:57', '2026-10-16 04:42:57', NULL, NULL, NULL, 'india', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customer_installments`
--

CREATE TABLE `hindustansystem_customer_installments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `installment_no` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `schedule_type` varchar(20) NOT NULL DEFAULT 'fixed_emi',
  `rescheduled_from_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customer_installments`
--

INSERT INTO `hindustansystem_customer_installments` (`id`, `sale_id`, `installment_no`, `label`, `due_date`, `amount`, `paid_amount`, `status`, `schedule_type`, `rescheduled_from_id`, `created_at`, `updated_at`) VALUES
(27, 3, 0, 'Down Payment', '2026-10-16', '7000000.00', '7000000.00', 'paid', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(28, 3, 1, 'EMI 1', '2026-11-16', '1091346.67', '1091346.67', 'paid', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-17 04:30:17'),
(29, 3, 2, 'EMI 2', '2026-12-16', '1091346.67', '1091346.67', 'paid', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-17 04:32:31'),
(30, 3, 3, 'EMI 3', '2027-01-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(31, 3, 4, 'EMI 4', '2027-02-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(32, 3, 5, 'EMI 5', '2027-03-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(33, 3, 6, 'EMI 6', '2027-04-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(34, 3, 7, 'EMI 7', '2027-05-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(35, 3, 8, 'EMI 8', '2027-06-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(36, 3, 9, 'EMI 9', '2027-07-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(37, 3, 10, 'EMI 10', '2027-08-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(38, 3, 11, 'EMI 11', '2027-09-16', '1091346.67', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(39, 3, 12, 'EMI 12', '2027-10-16', '1091346.63', '0.00', 'pending', 'fixed_emi', NULL, '2026-10-16 05:15:55', '2026-10-16 05:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_deals`
--

CREATE TABLE `hindustansystem_deals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `broker_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `sale_value` decimal(15,2) NOT NULL,
  `commission_pct_override` decimal(5,2) DEFAULT NULL,
  `trigger_condition` varchar(30) NOT NULL DEFAULT 'full_collection',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_dms_categories`
--

CREATE TABLE `hindustansystem_dms_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_dms_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `documentable_type` varchar(255) NOT NULL,
  `documentable_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `revision_number` varchar(255) DEFAULT NULL,
  `drawing_type` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `legal_category` varchar(255) DEFAULT NULL,
  `template_category` varchar(255) DEFAULT NULL,
  `tower` varchar(255) DEFAULT NULL,
  `reference_project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_dms_document_types`
--

CREATE TABLE `hindustansystem_dms_document_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dms_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_emi_reschedule_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `action_type` varchar(191) NOT NULL,
  `reason` text DEFAULT NULL,
  `old_schedule_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`old_schedule_snapshot`)),
  `new_schedule_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`new_schedule_snapshot`)),
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_emi_schedules`
--

CREATE TABLE `hindustansystem_emi_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `installment_no` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `emi_amount` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_date` date DEFAULT NULL,
  `principal_component` decimal(15,2) NOT NULL,
  `interest_component` decimal(15,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Due',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_employees`
--

CREATE TABLE `hindustansystem_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `designation` varchar(191) NOT NULL,
  `department` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `joining_date` date NOT NULL,
  `salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_engineers`
--

CREATE TABLE `hindustansystem_engineers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `engineer_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `designation` varchar(100) NOT NULL DEFAULT 'Site Engineer',
  `specialization` varchar(100) DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_engineers`
--

INSERT INTO `hindustansystem_engineers` (`id`, `engineer_code`, `name`, `email`, `phone`, `designation`, `specialization`, `project_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ENG-001', 'Rahul Accountant', NULL, NULL, 'Site Engineer', NULL, NULL, 1, '2026-10-14 08:06:40', '2026-10-14 08:06:40'),
(2, 'ENG-002', 'Anand Kumar', NULL, NULL, 'Senior Project Engineer', NULL, NULL, 1, '2026-10-14 08:06:40', '2026-10-14 08:06:40'),
(3, 'ENG-003', 'Suresh Nair', NULL, NULL, 'Civil Engineer', NULL, NULL, 1, '2026-10-14 08:06:40', '2026-10-14 08:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_failed_jobs`
--

CREATE TABLE `hindustansystem_failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` varchar(100) NOT NULL,
  `queue` varchar(100) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_floors`
--

CREATE TABLE `hindustansystem_floors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `floor_number` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_hindustan_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `floor_id` bigint(20) UNSIGNED NOT NULL,
  `unit_type_id` bigint(20) UNSIGNED NOT NULL,
  `door_no` varchar(191) NOT NULL,
  `built_up_area` decimal(10,2) DEFAULT NULL,
  `carpet_area` decimal(10,2) DEFAULT NULL,
  `expected_rate_per_sqft` decimal(12,2) DEFAULT NULL,
  `expected_sale_amount` decimal(14,2) DEFAULT NULL,
  `sale_rate_per_sqft` decimal(12,2) DEFAULT NULL,
  `sale_amount` decimal(14,2) DEFAULT NULL,
  `difference` decimal(14,2) DEFAULT NULL,
  `gst_behavior` varchar(20) NOT NULL DEFAULT 'none',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('available','booked','sold','blocked','hold','reserved') DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_hindustan_units`
--

INSERT INTO `hindustansystem_hindustan_units` (`id`, `project_id`, `floor_id`, `unit_type_id`, `door_no`, `built_up_area`, `carpet_area`, `expected_rate_per_sqft`, `expected_sale_amount`, `sale_rate_per_sqft`, `sale_amount`, `difference`, `gst_behavior`, `gst_amount`, `status`, `created_at`, `updated_at`, `is_active`, `deleted_at`) VALUES
(1, 1, 17, 2, 'G 1', '325.00', '208.00', '22000.00', '7150000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 01:59:33', '2026-10-15 01:59:33', 1, NULL),
(2, 1, 17, 2, 'G 2', '480.00', '304.00', '22000.00', '10560000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 01:59:49', '2026-10-15 01:59:49', 1, NULL),
(3, 1, 17, 2, 'G 3', '310.00', '196.00', '22000.00', '6820000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:00:04', '2026-10-15 02:00:04', 1, NULL),
(4, 1, 17, 2, 'G 4', '284.00', '183.00', '22000.00', '6248000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:00:24', '2026-10-15 02:00:24', 1, NULL),
(5, 1, 1, 2, 'F 1', '976.00', '613.00', '19000.00', '18544000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:00:49', '2026-10-15 02:00:49', 1, NULL),
(6, 1, 1, 2, 'F 2', '1071.00', '696.00', '19000.00', '20349000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:01:05', '2026-10-15 02:01:05', 1, NULL),
(7, 1, 1, 2, 'F 3', '846.00', '649.00', '19000.00', '16074000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:01:30', '2026-10-15 02:01:30', 1, NULL),
(8, 1, 1, 2, 'F 4', '1244.00', '708.00', '19000.00', '23636000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:01:49', '2026-10-15 02:01:49', 1, NULL),
(9, 1, 2, 2, 'S 1', '976.00', '613.00', '19000.00', '18544000.00', '18000.00', '17568000.00', '976000.00', 'exclusive', '2108160.00', 'sold', '2026-10-15 02:02:32', '2026-10-16 05:15:55', 1, NULL),
(10, 1, 2, 2, 'S 2', '1071.00', '696.00', '19000.00', '20349000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:02:54', '2026-10-15 02:02:54', 1, NULL),
(11, 1, 2, 2, 'S 3', '846.00', '649.00', '19000.00', '16074000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:03:12', '2026-10-15 02:03:12', 1, NULL),
(12, 1, 2, 2, 'S 4', '1244.00', '708.00', '19000.00', '23636000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:03:32', '2026-10-15 02:03:32', 1, NULL),
(13, 1, 3, 2, 'T 1', '976.00', '625.00', '19000.00', '18544000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:03:59', '2026-10-15 02:03:59', 1, NULL),
(14, 1, 3, 2, 'T 2', '1071.00', '708.00', '19000.00', '20349000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:04:22', '2026-10-15 02:04:22', 1, NULL),
(15, 1, 3, 2, 'T 3', '846.00', '660.00', '19000.00', '16074000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:04:41', '2026-10-15 02:04:41', 1, NULL),
(16, 1, 3, 2, 'T 4', '1244.00', '708.00', '19000.00', '23636000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:05:01', '2026-10-15 02:05:01', 1, NULL),
(17, 1, 18, 6, 'SI A', '1022.00', '686.00', '5000.00', '5110000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:07:04', '2026-10-16 05:07:46', 1, NULL),
(18, 1, 18, 6, 'SI B', '734.00', '507.00', '5000.00', '3670000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:09:06', '2026-10-15 02:09:06', 1, NULL),
(19, 1, 18, 6, 'SI C', '592.00', '402.00', '5000.00', '2960000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:09:26', '2026-10-15 02:09:26', 1, NULL),
(20, 1, 19, 6, 'SE A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:10:52', '2026-10-15 02:10:52', 1, NULL),
(21, 1, 19, 6, 'SE B', '1607.00', '1114.00', '5000.00', '8035000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:11:07', '2026-10-15 02:11:07', 1, NULL),
(22, 1, 19, 6, 'SE C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:11:26', '2026-10-15 02:11:26', 1, NULL),
(23, 1, 20, 6, 'E A', '949.00', '657.00', '5000.00', '4745000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:11:46', '2026-10-15 02:11:46', 1, NULL),
(24, 1, 20, 6, 'E B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:12:07', '2026-10-16 05:15:55', 1, NULL),
(25, 1, 20, 6, 'E C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:12:26', '2026-10-15 02:12:26', 1, NULL),
(26, 1, 22, 6, 'N A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:12:46', '2026-10-15 02:12:46', 1, NULL),
(27, 1, 22, 6, 'N B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:13:04', '2026-10-15 02:13:04', 1, NULL),
(28, 1, 22, 6, 'N C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:13:23', '2026-10-15 02:13:23', 1, NULL),
(29, 1, 23, 6, 'TE A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:14:13', '2026-10-15 02:14:13', 1, NULL),
(30, 1, 23, 6, 'TE B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:14:30', '2026-10-15 02:14:30', 1, NULL),
(31, 1, 23, 6, 'TE C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:14:51', '2026-10-15 02:14:51', 1, NULL),
(32, 1, 24, 6, '11 A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:15:06', '2026-10-15 02:15:06', 1, NULL),
(33, 1, 24, 6, '11 B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:15:26', '2026-10-15 02:15:26', 1, NULL),
(34, 1, 24, 6, '11 C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:15:42', '2026-10-15 02:15:42', 1, NULL),
(35, 1, 25, 6, '12 A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:16:04', '2026-10-15 02:16:04', 1, NULL),
(36, 1, 25, 6, '12 B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:16:35', '2026-10-15 02:16:35', 1, NULL),
(37, 1, 25, 6, '12 C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:16:54', '2026-10-15 02:16:54', 1, NULL),
(38, 1, 26, 6, '13 A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:17:12', '2026-10-15 02:17:12', 1, NULL),
(39, 1, 26, 6, '13 B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:17:29', '2026-10-15 02:17:29', 1, NULL),
(40, 1, 26, 6, '13 C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:17:48', '2026-10-15 02:17:48', 1, NULL),
(41, 1, 27, 6, '14 A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:18:05', '2026-10-15 02:18:05', 1, NULL),
(42, 1, 27, 6, '14 B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:18:21', '2026-10-15 02:18:21', 1, NULL),
(43, 1, 27, 6, '14 C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:18:36', '2026-10-15 02:18:36', 1, NULL),
(44, 1, 28, 6, '15 A', '979.00', '657.00', '5000.00', '4895000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:18:58', '2026-10-15 02:18:58', 1, NULL),
(45, 1, 28, 6, '15 B', '1572.00', '1090.00', '5000.00', '7860000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:19:22', '2026-10-15 02:19:22', 1, NULL),
(46, 1, 28, 6, '15 C', '1586.00', '1102.00', '5000.00', '7930000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:20:04', '2026-10-15 02:20:04', 1, NULL),
(47, 1, 29, 6, '16 A', '1107.00', '734.00', '5000.00', '5535000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:22:40', '2026-10-15 02:22:40', 1, NULL),
(48, 1, 29, 6, '16 B', '1712.00', '1175.00', '5000.00', '8560000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:22:56', '2026-10-15 02:22:56', 1, NULL),
(49, 1, 29, 6, '16 C', '1785.00', '1250.00', '5000.00', '8925000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:23:13', '2026-10-15 02:23:13', 1, NULL),
(50, 1, 30, 6, '17 A', '1107.00', '734.00', '5000.00', '5535000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:23:44', '2026-10-15 02:23:44', 1, NULL),
(51, 1, 30, 6, '17 B', '1712.00', '1162.00', '5000.00', '8560000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:24:13', '2026-10-15 02:24:13', 1, NULL),
(52, 1, 30, 6, '17 C', '1785.00', '1268.00', '5000.00', '8925000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:24:32', '2026-10-15 02:24:32', 1, NULL),
(53, 1, 4, 5, 'FO 1', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:25:58', '2026-10-16 05:07:46', 1, NULL),
(54, 1, 4, 5, 'FO 2', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:26:10', '2026-10-15 02:26:10', 1, NULL),
(55, 1, 4, 5, 'FO 3', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:26:20', '2026-10-15 02:26:20', 1, NULL),
(56, 1, 4, 5, 'FO 4', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:26:31', '2026-10-15 02:26:31', 1, NULL),
(57, 1, 4, 5, 'FO 5', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:26:41', '2026-10-15 02:26:41', 1, NULL),
(58, 1, 5, 5, 'FI 1', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:26:51', '2026-10-15 02:26:51', 1, NULL),
(59, 1, 5, 5, 'FI 2', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:27:01', '2026-10-15 02:27:01', 1, NULL),
(60, 1, 5, 5, 'FI 3', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:27:13', '2026-10-15 02:27:13', 1, NULL),
(61, 1, 5, 5, 'FI 4', NULL, NULL, NULL, '400000.00', '0.00', '420000.00', '-20000.00', 'exclusive', '20000.00', 'sold', '2026-10-15 02:27:25', '2026-10-16 04:49:30', 1, NULL),
(62, 1, 5, 5, 'FI 5', NULL, NULL, NULL, '400000.00', NULL, NULL, NULL, 'none', '0.00', 'available', '2026-10-15 02:27:35', '2026-10-15 02:27:35', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_jobs`
--

CREATE TABLE `hindustansystem_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_job_batches`
--

CREATE TABLE `hindustansystem_job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_journal_entries`
--

CREATE TABLE `hindustansystem_journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` varchar(20) NOT NULL,
  `debit_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_narration` varchar(255) DEFAULT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_journal_entries`
--

INSERT INTO `hindustansystem_journal_entries` (`id`, `voucher_id`, `account_id`, `debit_amount`, `credit_amount`, `line_narration`, `entity_type`, `entity_id`, `created_at`, `updated_at`) VALUES
(1, 1, '1010', '5248950.00', '0.00', 'Customer Receivable (jhancy)', 'CUSTOMER', 1, '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(2, 1, '2021', '0.00', '124975.00', 'Output CGST Payable', NULL, NULL, '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(3, 1, '2022', '0.00', '124975.00', 'Output SGST Payable', NULL, NULL, '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(4, 2, '1010', '9498300.00', '0.00', 'Customer Receivable (soumya)', 'CUSTOMER', 2, '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(5, 2, '2021', '0.00', '226150.00', 'Output CGST Payable', NULL, NULL, '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(6, 2, '2022', '0.00', '226150.00', 'Output SGST Payable', NULL, NULL, '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(7, 2, '2003', '0.00', '180920.00', 'Agent Payable Liability (noushad)', 'AGENT', 1, '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(8, 3, '1001', '1500000.00', '0.00', 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-10-16 04:54:36', '2026-10-16 04:54:36'),
(9, 3, '1010', '0.00', '1500000.00', 'Customer Receivables (jhancy Debt Decreases)', 'CUSTOMER', 1, '2026-10-16 04:54:36', '2026-10-16 04:54:36'),
(10, 4, '1001', '2000000.00', '0.00', 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-10-16 04:57:35', '2026-10-16 04:57:35'),
(11, 4, '1010', '0.00', '2000000.00', 'Customer Receivables (soumya Debt Decreases)', 'CUSTOMER', 2, '2026-10-16 04:57:35', '2026-10-16 04:57:35'),
(12, 5, '1001', '312412.50', '0.00', 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-10-16 04:58:38', '2026-10-16 04:58:38'),
(13, 5, '1010', '0.00', '312412.50', 'Customer Receivables (jhancy Debt Decreases)', 'CUSTOMER', 1, '2026-10-16 04:58:38', '2026-10-16 04:58:38'),
(14, 6, '2003', '40000.00', '0.00', 'Agent Commission Payables (noushad Cleared)', 'AGENT', 1, '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(15, 6, '4001', '0.00', '40000.00', 'Agent Expense Payables (noushad Cleared)', 'AGENT', 1, '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(16, 6, '1001', '0.00', '40000.00', 'Axis Bank 9752 (Bank Asset Decreases)', 'BANK', 1, '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(17, 7, '1010', '1000000.00', '0.00', 'Customer Receivables (Liability Cleared)', 'CUSTOMER', 1, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(18, 7, '1001', '0.00', '1000000.00', 'Axis Bank 9752 (Bank Asset Decreases)', 'BANK', 1, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(19, 8, '1010', '712412.00', '0.00', 'Customer Receivables (Liability Cleared)', 'CUSTOMER', 1, '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(20, 8, '1001', '0.00', '712412.00', 'Axis Bank 9752 (Bank Asset Decreases)', 'BANK', 1, '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(21, 9, '1001', '5000000.00', '0.00', 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-10-16 05:17:04', '2026-10-16 05:17:04'),
(22, 9, '1010', '0.00', '5000000.00', 'Customer Receivables (soumya Debt Decreases)', 'CUSTOMER', 2, '2026-10-16 05:17:04', '2026-10-16 05:17:04'),
(23, 10, '1002', '20000.00', '0.00', 'Petty Cash Box (Cash Asset Increases)', NULL, NULL, '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(24, 10, '1001', '0.00', '20000.00', 'Axis Bank 9752 (Bank Asset Decreases)', 'BANK', 1, '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(25, 11, '1001', '1091346.67', '0.00', 'Axis Bank 9752 (Asset Increases)', 'BANK', 1, '2026-10-17 04:30:57', '2026-10-17 04:30:57'),
(26, 11, '1010', '0.00', '1091346.67', 'Customer Receivables (soumya Debt Decreases)', 'CUSTOMER', 2, '2026-10-17 04:30:57', '2026-10-17 04:30:57'),
(27, 12, '4002', '5187500.00', '0.00', 'Contractor Work Expenses (MKH INFRASTRUCTURE - RA #1)', 'CONTRACTOR', 12, '2026-10-17 04:44:48', '2026-10-17 04:44:48'),
(28, 12, '2002', '0.00', '5187500.00', 'Contractor Payables Liability (MKH INFRASTRUCTURE - RA #1)', 'CONTRACTOR', 12, '2026-10-17 04:44:48', '2026-10-17 04:44:48'),
(29, 13, '2002', '2000000.00', '0.00', 'Contractor Payables Cleared (MKH INFRASTRUCTURE - RA #1)', 'CONTRACTOR', 12, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(30, 13, '1001', '0.00', '2000000.00', 'Payment Disbursed via Axis Bank 9752 (Ref: YFYTYU)', 'BANK', 1, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(31, 14, '2002', '1500000.00', '0.00', 'Contractor Payables Cleared (MKH INFRASTRUCTURE - RA #1)', 'CONTRACTOR', 12, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(32, 14, '1001', '0.00', '1500000.00', 'Payment Disbursed via Axis Bank 9752 (Ref: 1336655)', 'BANK', 1, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(33, 15, '4002', '4375000.00', '0.00', 'Contractor Work Expenses (MKH INFRASTRUCTURE - RA #2)', 'CONTRACTOR', 12, '2026-10-17 04:49:56', '2026-10-17 04:49:56'),
(34, 15, '2002', '0.00', '4375000.00', 'Contractor Payables Liability (MKH INFRASTRUCTURE - RA #2)', 'CONTRACTOR', 12, '2026-10-17 04:49:56', '2026-10-17 04:49:56'),
(35, 16, '2002', '1000000.00', '0.00', 'Contractor Payables Cleared (MKH INFRASTRUCTURE - RA #2)', 'CONTRACTOR', 12, '2026-10-17 04:50:44', '2026-10-17 04:50:44'),
(36, 16, '1001', '0.00', '1000000.00', 'Payment Disbursed via Axis Bank 9752 (Ref: UITI)', 'BANK', 1, '2026-10-17 04:50:44', '2026-10-17 04:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_journal_vouchers`
--

CREATE TABLE `hindustansystem_journal_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(50) NOT NULL,
  `voucher_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_date` date NOT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `narration` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Posted',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_journal_vouchers`
--

INSERT INTO `hindustansystem_journal_vouchers` (`id`, `voucher_no`, `voucher_type_id`, `voucher_date`, `reference_id`, `reference_no`, `narration`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'JV-SB-2026-0001', 15, '2026-10-15', 1, NULL, 'Booking jhancy Unit SI A', 'Posted', 1, '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(2, 'JV-SB-2026-0002', 15, '2026-10-16', 2, NULL, 'Booking soumya Unit E B with Agent noushad', 'Posted', 1, '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(3, 'JV-CR-2026-0001', 17, '2026-10-16', 1, NULL, 'Cheque #456123 cleared - jhancy', 'Posted', 1, '2026-10-16 04:54:36', '2026-10-16 04:54:36'),
(4, 'JV-CR-2026-0003', 17, '2026-10-16', 3, NULL, 'Cheque #456456 cleared - soumya', 'Posted', 1, '2026-10-16 04:57:35', '2026-10-16 04:57:35'),
(5, 'JV-CR-2026-0002', 17, '2026-10-16', 2, NULL, 'Cheque #456456 cleared - jhancy', 'Posted', 1, '2026-10-16 04:58:38', '2026-10-16 04:58:38'),
(6, 'JV-AP-2026-0001-44', 19, '2026-10-16', 1, NULL, 'Commission payout of ₹40,000.00 (Partially Paid) to broker \'noushad\' for Sale #TH-AP-EB-SOUM.', 'Posted', 1, '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(7, 'JV-RF-2026-0001', 16, '2026-10-16', 1, NULL, 'Booking Cancellation refund to jhancy (Customer refunghd processed as per cancellation agreement.)', 'Posted', 1, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(8, 'JV-RF-2026-0001-1', 16, '2026-10-16', 1, NULL, 'Booking Cancellation refund to jhancy (Customer refund processed as per cancellation agreement.)', 'Posted', 1, '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(9, 'JV-CR-2026-0004', 17, '2026-10-16', 4, NULL, 'Cheque #123456 cleared - soumya', 'Posted', 1, '2026-10-16 05:17:04', '2026-10-16 05:17:04'),
(10, 'JV-PC-2026-1839', 18, '2026-10-17', NULL, NULL, 'Cash withdrawal for site petty cash box', 'Posted', 1, '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(11, 'JV-CR-2026-0005', 17, '2026-10-17', 5, NULL, 'Cheque #455455 cleared - soumya', 'Posted', 1, '2026-10-17 04:30:57', '2026-10-17 04:30:57'),
(12, 'JV-RA-2026-0001', 1, '2026-10-17', 1, NULL, 'Verified RA Bill #1 - MKH INFRASTRUCTURE (Verified by: Rahul Accountant (Site Engineer))', 'Posted', 1, '2026-10-17 04:44:48', '2026-10-17 04:44:48'),
(13, 'JV-CP-2026-0001', 2, '2026-10-17', 1, NULL, 'Contractor Payment Release for RA Bill #1 to MKH INFRASTRUCTURE via Axis Bank 9752', 'Posted', 1, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(14, 'JV-CP-2026-0002', 2, '2026-10-17', 2, NULL, 'Contractor Payment Release for RA Bill #1 to MKH INFRASTRUCTURE via Axis Bank 9752', 'Posted', 1, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(15, 'JV-RA-2026-0002', 1, '2026-10-17', 2, NULL, 'Verified RA Bill #2 - MKH INFRASTRUCTURE (Verified by: Rahul Accountant (Site Engineer))', 'Posted', 1, '2026-10-17 04:49:56', '2026-10-17 04:49:56'),
(16, 'JV-CP-2026-0003', 2, '2026-10-30', 3, NULL, 'Contractor Payment Release for RA Bill #2 to MKH INFRASTRUCTURE via Axis Bank 9752', 'Posted', 1, '2026-10-17 04:50:44', '2026-10-17 04:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ledger_entries`
--

CREATE TABLE `hindustansystem_ledger_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_line_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `running_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ledger_entries`
--

INSERT INTO `hindustansystem_ledger_entries` (`id`, `system_id`, `account_id`, `voucher_id`, `voucher_line_id`, `date`, `debit`, `credit`, `running_balance`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2026-10-16', '40000.00', '0.00', '0.00', '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(2, 1, 2, 1, 2, '2026-10-16', '0.00', '40000.00', '0.00', '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(3, 1, 3, 2, 3, '2026-10-16', '1000000.00', '0.00', '0.00', '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(4, 1, 4, 2, 4, '2026-10-16', '0.00', '1000000.00', '0.00', '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(5, 1, 3, 3, 5, '2026-10-16', '712412.00', '0.00', '0.00', '2026-10-17 00:53:32', '2026-10-17 00:53:32'),
(6, 1, 4, 3, 6, '2026-10-16', '0.00', '712412.00', '0.00', '2026-10-17 00:53:32', '2026-10-17 00:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loans`
--

CREATE TABLE `hindustansystem_loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `loan_account_no` varchar(50) DEFAULT NULL,
  `lender_name` varchar(191) NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `tenure_months` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `schedule_type` varchar(20) NOT NULL,
  `outstanding_balance` decimal(15,2) NOT NULL,
  `ledger_account_id` bigint(20) UNSIGNED NOT NULL,
  `interest_account_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loan_disbursals`
--

CREATE TABLE `hindustansystem_loan_disbursals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `disbursal_no` varchar(50) NOT NULL,
  `disbursal_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_no` varchar(191) DEFAULT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `disbursal_type` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'DRAFT',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `posted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loan_interest_logs`
--

CREATE TABLE `hindustansystem_loan_interest_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `old_interest_rate` decimal(5,2) NOT NULL,
  `new_interest_rate` decimal(5,2) NOT NULL,
  `interest_period` varchar(191) NOT NULL DEFAULT 'annual',
  `reason` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loan_prepayments`
--

CREATE TABLE `hindustansystem_loan_prepayments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `prepayment_amount` decimal(15,2) NOT NULL,
  `prepayment_date` date NOT NULL,
  `reschedule_option` varchar(20) NOT NULL,
  `previous_outstanding` decimal(15,2) NOT NULL,
  `new_outstanding` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_migrations`
--

CREATE TABLE `hindustansystem_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(56, '2026_07_30_110000_add_gst_fields_to_bills_table', 32);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_permissions`
--

CREATE TABLE `hindustansystem_model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_roles`
--

CREATE TABLE `hindustansystem_model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
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

CREATE TABLE `hindustansystem_partner_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allocated_amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_partner_contributions`
--

CREATE TABLE `hindustansystem_partner_contributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED DEFAULT 1,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `company_bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `contribution_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Posted',
  `is_historical` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_partner_shares`
--

CREATE TABLE `hindustansystem_partner_shares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `share_pct` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_partner_shares`
--

INSERT INTO `hindustansystem_partner_shares` (`id`, `system_id`, `partner_id`, `project_id`, `share_pct`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '57.50', '2026-07-06 03:53:49', '2026-07-10 04:12:40'),
(2, 1, 2, 1, '42.50', '2026-07-06 03:53:49', '2026-07-10 04:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_password_reset_tokens`
--

CREATE TABLE `hindustansystem_password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payees`
--

CREATE TABLE `hindustansystem_payees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `gstin` varchar(191) DEFAULT NULL,
  `pan` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `linked_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payees`
--

INSERT INTO `hindustansystem_payees` (`id`, `system_id`, `type`, `name`, `phone`, `email`, `gstin`, `pan`, `address`, `is_active`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Partner', 'Basheer', NULL, NULL, NULL, NULL, NULL, 1, 2, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(2, 1, 'Partner', 'Pavoor', NULL, NULL, NULL, NULL, NULL, 1, 3, '2026-07-06 03:53:49', '2026-07-10 00:55:43'),
(12, 1, 'Contractor', 'MKH INFRASTRUCTURE', '09048393993', 'jhancydinuvj@gmail.com', NULL, NULL, 'MALL OF INDIA, KANHANGAD', 1, 18, '2026-10-17 04:42:04', '2026-10-17 04:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payments`
--

CREATE TABLE `hindustansystem_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_number` varchar(191) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payment_modes`
--

CREATE TABLE `hindustansystem_payment_modes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `requires_reference` tinyint(1) NOT NULL DEFAULT 0,
  `requires_bank` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_petty_cash_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `float_limit` decimal(15,2) NOT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `ledger_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_petty_cash_boxes`
--

CREATE TABLE `hindustansystem_petty_cash_boxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `box_code` varchar(255) NOT NULL,
  `box_name` varchar(255) NOT NULL DEFAULT 'Site Petty Cash Box',
  `incharge_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_petty_cash_boxes`
--

INSERT INTO `hindustansystem_petty_cash_boxes` (`id`, `project_id`, `box_code`, `box_name`, `incharge_id`, `current_balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'PC-HID-001', 'Site Petty Cash Box', 1, '17060.00', 'Active', '2026-09-11 05:36:58', '2026-10-17 00:59:49'),
(2, NULL, 'PC-GEN-001', 'Site Petty Cash Box', 1, '0.00', 'Active', '2026-10-14 08:07:24', '2026-10-14 08:07:24');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_petty_cash_entries`
--

CREATE TABLE `hindustansystem_petty_cash_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `petty_cash_account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(191) NOT NULL,
  `receipt_url` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_petty_cash_transactions`
--

CREATE TABLE `hindustansystem_petty_cash_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `petty_cash_box_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `voucher_number` varchar(255) DEFAULT NULL,
  `transaction_type` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `bill_date` date DEFAULT NULL,
  `narration` text DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT 'Cash',
  `attachment_path` varchar(255) DEFAULT NULL,
  `cash_in` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cash_out` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Posted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_petty_cash_transactions`
--

INSERT INTO `hindustansystem_petty_cash_transactions` (`id`, `petty_cash_box_id`, `category_id`, `voucher_id`, `transaction_date`, `voucher_number`, `transaction_type`, `reference_no`, `bill_date`, `narration`, `payment_mode`, `attachment_path`, `cash_in`, `cash_out`, `balance`, `created_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 4, '2026-10-17', 'PCON-00001', 'Contra', '655', NULL, 'cash withdrawed for office use', 'Cash', NULL, '20000.00', '0.00', '20000.00', 1, 'Posted', '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(2, 1, 2, NULL, '2026-10-17', 'EXP-5282', 'Site Expense', '1', '2026-10-17', 'News Paper Expense - NEWS PAPER BILL PAID', 'Cash', NULL, '0.00', '340.00', '19660.00', 1, 'approved', '2026-10-17 00:58:31', '2026-10-17 00:58:31'),
(3, 1, 3, NULL, '2026-10-17', 'EXP-9861', 'Site Expense', '2', '2026-10-17', 'Cleaning Charge - CLEANING CHARGE PAID', 'Cash', NULL, '0.00', '2500.00', '17160.00', 1, 'approved', '2026-10-17 00:59:01', '2026-10-17 00:59:01'),
(4, 1, 1, NULL, '2026-10-17', 'EXP-9517', 'Site Expense', '3', '2026-10-17', 'Office Expense - TEA AND SNACKS', 'Cash', NULL, '0.00', '100.00', '17060.00', 1, 'approved', '2026-10-17 00:59:49', '2026-10-17 00:59:49');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_projects`
--

CREATE TABLE `hindustansystem_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `location` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `state_or_emirate` varchar(191) NOT NULL,
  `country` varchar(191) NOT NULL,
  `rera_number` varchar(191) DEFAULT NULL,
  `total_floors` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'planning',
  `description` text DEFAULT NULL,
  `image_url` varchar(2048) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_projects`
--

INSERT INTO `hindustansystem_projects` (`id`, `system_id`, `name`, `code`, `location`, `city`, `state_or_emirate`, `country`, `rera_number`, `total_floors`, `start_date`, `expected_completion_date`, `status`, `description`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tabasco Hindustan', 'TH', 'MALL OF INDIA, KANHANGAD', 'Kasargode', 'Kerala', 'India', NULL, 0, NULL, NULL, 'ongoing', NULL, NULL, 1, '2026-10-15 01:11:22', '2026-10-15 01:11:22');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ra_bills`
--

CREATE TABLE `hindustansystem_ra_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `invoice_month` varchar(20) DEFAULT NULL,
  `ra_bill_number` varchar(255) NOT NULL,
  `contractor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contractor_name` varchar(255) DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_name` varchar(255) DEFAULT NULL,
  `submit_date` date NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `additional_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `additional_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `verified_date` date DEFAULT NULL,
  `engineer_name` varchar(255) DEFAULT NULL,
  `correction_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_approved_amount` decimal(15,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(15,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ra_bills`
--

INSERT INTO `hindustansystem_ra_bills` (`id`, `system_id`, `invoice_month`, `ra_bill_number`, `contractor_id`, `contractor_name`, `project_id`, `unit_id`, `unit_name`, `submit_date`, `gross_amount`, `additional_amount`, `additional_percentage`, `verified_date`, `engineer_name`, `correction_amount`, `net_approved_amount`, `due_date`, `paid_amount`, `balance_amount`, `status`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '1', 12, 'MKH INFRASTRUCTURE', 1, NULL, NULL, '2026-10-17', '5000000.00', '1037500.00', '25.00', '2026-10-17', 'Rahul Accountant (Site Engineer)', '850000.00', '5187500.00', '2026-11-17', '3500000.00', '1687500.00', 'partially_paid', 'STS', 1, '2026-10-17 04:43:45', '2026-10-17 04:47:33'),
(2, 1, NULL, '2', 12, 'MKH INFRASTRUCTURE', 1, NULL, NULL, '2026-10-22', '4000000.00', '875000.00', '25.00', '2026-10-17', 'Rahul Accountant (Site Engineer)', '500000.00', '4375000.00', '2026-11-22', '1000000.00', '3375000.00', 'partially_paid', 'NJNHC', 1, '2026-10-17 04:49:01', '2026-10-17 04:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_ra_bill_payments`
--

CREATE TABLE `hindustansystem_ra_bill_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `ra_bill_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(255) NOT NULL DEFAULT 'NEFT',
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `is_historical` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_ra_bill_payments`
--

INSERT INTO `hindustansystem_ra_bill_payments` (`id`, `system_id`, `ra_bill_id`, `payment_date`, `paid_amount`, `payment_mode`, `company_bank_account_id`, `reference_no`, `voucher_id`, `status`, `is_historical`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-10-17', '2000000.00', 'BANK_TRANSFER', 1, 'YFYTYU', 5, 'paid', 0, NULL, 1, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(2, 1, 1, '2026-10-17', '1500000.00', 'BANK_TRANSFER', 1, '1336655', 6, 'paid', 0, NULL, 1, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(3, 1, 2, '2026-10-30', '1000000.00', 'BANK_TRANSFER', 1, 'UITI', 7, 'paid', 0, NULL, 1, '2026-10-17 04:50:44', '2026-10-17 04:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_receipts`
--

CREATE TABLE `hindustansystem_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `is_allocated` tinyint(1) NOT NULL DEFAULT 0,
  `payment_mode` varchar(191) NOT NULL DEFAULT 'cash',
  `realization_status` enum('pending','cheque_in_hand','deposited','realized','bounced','cancelled') NOT NULL DEFAULT 'pending',
  `cheque_date` date DEFAULT NULL,
  `drawee_bank` varchar(100) DEFAULT NULL,
  `realized_at` timestamp NULL DEFAULT NULL,
  `realized_by` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_type` varchar(50) NOT NULL DEFAULT 'regular',
  `reference_no` varchar(191) DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipts`
--

INSERT INTO `hindustansystem_receipts` (`id`, `sale_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `is_allocated`, `payment_mode`, `realization_status`, `cheque_date`, `drawee_bank`, `realized_at`, `realized_by`, `payment_type`, `reference_no`, `bank_id`, `company_bank_account_id`, `remarks`, `created_by`, `partner_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 17, '2026-10-15', '1500000.00', 0, 'Cheque', 'realized', NULL, NULL, '2026-10-16 04:54:36', 1, 'regular', '456123', 23, 1, 'Initial payment at sale creation', 1, NULL, '2026-10-15 05:24:00', '2026-10-16 04:54:36'),
(2, 1, 1, 1, 17, '2026-10-15', '312412.50', 0, 'Bank Transfer (NEFT / RTGS / IMPS)', 'realized', NULL, NULL, '2026-10-16 04:58:38', 1, 'regular', '456456', 23, 1, 'rtrt', 1, NULL, '2026-10-15 07:21:11', '2026-10-16 04:58:38'),
(3, 3, 2, 1, 9, '2026-10-16', '2000000.00', 0, 'Cheque', 'realized', NULL, NULL, '2026-10-16 04:57:35', 1, 'regular', '456456', 23, 1, 'Initial payment from previous booking (TH-AP-EB-SOUM)', 1, NULL, '2026-10-16 04:49:30', '2026-10-16 05:15:55'),
(4, 3, 2, 1, 9, '2026-10-16', '5000000.00', 0, 'Cheque', 'realized', NULL, NULL, '2026-10-16 05:17:04', 1, 'regular', '123456', 23, 1, 'Initial payment at sale creation', 1, NULL, '2026-10-16 05:15:55', '2026-10-16 05:17:04'),
(5, 3, 2, 1, 9, '2026-10-17', '1091346.67', 0, 'Bank Transfer (NEFT / RTGS / IMPS)', 'realized', NULL, NULL, '2026-10-17 04:30:57', 1, 'regular', '455455', 23, 1, 'f', 1, NULL, '2026-10-17 04:30:17', '2026-10-17 04:30:57'),
(6, 3, 2, 1, 9, '2026-10-23', '1091346.67', 0, 'Bank Transfer (NEFT / RTGS / IMPS)', 'cancelled', NULL, NULL, NULL, NULL, 'regular', '785', NULL, 1, NULL, 1, NULL, '2026-10-17 04:32:31', '2026-10-17 04:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_receipt_realization_logs`
--

CREATE TABLE `hindustansystem_receipt_realization_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_id` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipt_realization_logs`
--

INSERT INTO `hindustansystem_receipt_realization_logs` (`id`, `receipt_id`, `old_status`, `new_status`, `remarks`, `changed_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'pending', 'realized', 'Initial payment at sale creation', 1, '2026-10-16 04:54:36', '2026-10-16 04:54:36'),
(2, 3, 'pending', 'realized', 'Initial payment at sale creation', 1, '2026-10-16 04:57:35', '2026-10-16 04:57:35'),
(3, 2, 'pending', 'realized', 'rtrt', 1, '2026-10-16 04:58:38', '2026-10-16 04:58:38'),
(4, 4, 'pending', 'realized', 'Initial payment at sale creation', 1, '2026-10-16 05:17:04', '2026-10-16 05:17:04'),
(5, 5, 'pending', 'realized', 'f', 1, '2026-10-17 04:30:57', '2026-10-17 04:30:57'),
(6, 6, 'pending', 'cancelled', 'insufficent fund', 1, '2026-10-17 04:33:08', '2026-10-17 04:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_receipt_stores`
--

CREATE TABLE `hindustansystem_receipt_stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_mode` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_receipt_stores`
--

INSERT INTO `hindustansystem_receipt_stores` (`id`, `receipt_id`, `company_bank_account_id`, `customer_id`, `project_id`, `unit_id`, `receipt_date`, `amount`, `payment_mode`, `reference_no`, `remarks`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, '2026-10-15', '1500000.00', 'Cheque', '456123', 'Initial payment at sale creation', 'realized', 1, '2026-10-16 04:54:36', '2026-10-16 04:54:36'),
(2, 3, 1, 2, 1, NULL, '2026-10-16', '2000000.00', 'Cheque', '456456', 'Initial payment at sale creation', 'realized', 1, '2026-10-16 04:57:35', '2026-10-16 04:57:35'),
(3, 2, 1, 1, 1, NULL, '2026-10-15', '312412.50', 'Bank Transfer (NEFT / RTGS / IMPS)', '456456', 'rtrt', 'realized', 1, '2026-10-16 04:58:38', '2026-10-16 04:58:38'),
(4, 4, 1, 2, 1, NULL, '2026-10-16', '5000000.00', 'Cheque', '123456', 'Initial payment at sale creation', 'realized', 1, '2026-10-16 05:17:04', '2026-10-16 05:17:04'),
(5, 5, 1, 2, 1, NULL, '2026-10-17', '1091346.67', 'Bank Transfer (NEFT / RTGS / IMPS)', '455455', 'f', 'realized', 1, '2026-10-17 04:30:57', '2026-10-17 04:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_replenishment_requests`
--

CREATE TABLE `hindustansystem_replenishment_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `petty_cash_account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_roles`
--

CREATE TABLE `hindustansystem_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_role_has_permissions`
--

INSERT INTO `hindustansystem_role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(5, 2),
(6, 1),
(6, 3),
(7, 1),
(7, 3),
(8, 1),
(8, 3),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(10, 1),
(10, 4),
(11, 1),
(12, 1),
(13, 1),
(13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sales`
--

CREATE TABLE `hindustansystem_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_number` varchar(191) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `broker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `rate_per_sqft` decimal(15,2) DEFAULT NULL,
  `sale_amount` decimal(15,2) NOT NULL,
  `gst_applicable` tinyint(1) NOT NULL DEFAULT 0,
  `gst_type` enum('none','inclusive','exclusive') NOT NULL DEFAULT 'none',
  `base_amount` decimal(15,2) DEFAULT NULL,
  `gst_percentage` decimal(5,2) DEFAULT NULL,
  `gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `final_discount` decimal(15,2) DEFAULT 0.00,
  `sale_date` date NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `remaining_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `broker_involved` tinyint(1) NOT NULL DEFAULT 0,
  `initial_payment` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_mode` enum('cash','cheque','bank_transfer','upi','demand_draft') DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `payment_plan` enum('lump_sum','emi') NOT NULL DEFAULT 'lump_sum',
  `emi_type` enum('equal','milestone') DEFAULT NULL,
  `emi_installment_count` int(11) DEFAULT NULL,
  `emi_frequency` varchar(191) DEFAULT NULL,
  `first_installment_date` date DEFAULT NULL,
  `original_sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_resale` tinyint(1) NOT NULL DEFAULT 0,
  `cancellation_reason` varchar(191) DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `additional_refund_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `refund_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales`
--

INSERT INTO `hindustansystem_sales` (`id`, `sale_number`, `project_id`, `unit_id`, `customer_id`, `broker_id`, `agreement_date`, `registration_date`, `rate_per_sqft`, `sale_amount`, `gst_applicable`, `gst_type`, `base_amount`, `gst_percentage`, `gst_amount`, `total_amount`, `final_discount`, `sale_date`, `status`, `remaining_balance`, `broker_involved`, `initial_payment`, `payment_mode`, `reference_no`, `bank_id`, `remarks`, `payment_plan`, `emi_type`, `emi_installment_count`, `emi_frequency`, `first_installment_date`, `original_sale_id`, `is_resale`, `cancellation_reason`, `cancelled_at`, `cancellation_fee`, `additional_refund_amount`, `refund_amount`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TH-AP-SIA-JHAN', 1, 17, 1, NULL, '2026-10-15', '2027-06-15', '4500.00', '4999000.00', 0, 'exclusive', '4999000.00', '18.00', '249950.00', '5248950.00', '0.00', '2026-10-15', 'cancelled', '3436537.50', 0, '1500000.00', 'cheque', '456123', 23, NULL, 'emi', 'equal', 12, 'monthly', '2026-11-15', NULL, 0, 'Financial Issue', NULL, '100000.00', '0.00', '0.50', 'ty', NULL, '2026-10-15 05:24:00', '2026-10-16 05:09:22', NULL),
(2, 'TH-AP-EB-SOUM', 1, 24, 2, 1, '2026-10-16', '2027-06-24', '5500.00', '9046000.00', 0, 'exclusive', '9046000.00', '18.00', '452300.00', '9498300.00', '0.00', '2026-10-16', 'exchanged', '9498300.00', 0, '2000000.00', 'cheque', '456456', 23, NULL, 'emi', 'equal', 12, 'monthly', '2026-11-16', NULL, 0, 'k', NULL, '0.00', '0.00', '0.00', NULL, NULL, '2026-10-16 04:49:30', '2026-10-16 05:15:55', NULL),
(3, 'TH-SH-S1-SOUM', 1, 9, 2, 1, '2026-10-16', NULL, '18000.00', '17968000.00', 0, 'exclusive', '17968000.00', '18.00', '2128160.00', '20096160.00', '0.00', '2026-10-16', 'active', '10913466.66', 0, '0.00', NULL, NULL, NULL, NULL, 'emi', 'equal', 12, 'monthly', '2026-11-16', NULL, 0, NULL, NULL, '0.00', '0.00', '0.00', 'Exchanged from sale TH-AP-EB-SOUM. k', NULL, '2026-10-16 05:15:55', '2026-10-17 04:32:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sales_executives`
--

CREATE TABLE `hindustansystem_sales_executives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `avatar_url` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sale_extra_works`
--

CREATE TABLE `hindustansystem_sale_extra_works` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(191) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `gst_type` varchar(191) NOT NULL DEFAULT 'none',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 18.00,
  `gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sale_status_logs`
--

CREATE TABLE `hindustansystem_sale_status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(191) DEFAULT NULL,
  `to_status` varchar(191) NOT NULL,
  `event_type` varchar(191) NOT NULL,
  `reason` text DEFAULT NULL,
  `snapshot_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`snapshot_data`)),
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_status_logs`
--

INSERT INTO `hindustansystem_sale_status_logs` (`id`, `sale_id`, `from_status`, `to_status`, `event_type`, `reason`, `snapshot_data`, `performed_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'active', 'created', NULL, NULL, 1, '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(2, 2, NULL, 'active', 'created', NULL, NULL, 1, '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(3, 1, 'active', 'cancelled', 'cancelled', 'Financial Issue', '{\"old_sale\":{\"id\":1,\"sale_number\":\"TH-AP-SIA-JHAN\",\"sale_date\":\"2026-10-15\",\"agreement_date\":\"2026-10-15\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":12,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-11-15\",\"rate_per_sqft\":4500,\"sale_amount\":4999000,\"gst_type\":\"exclusive\",\"gst_amount\":249950,\"base_amount\":4999000,\"total_amount\":5248950,\"remaining_balance\":3436537.5,\"total_paid\":1812412.5},\"old_unit\":{\"id\":17,\"door_no\":\"SI A\",\"floor_name\":\"Floor 6\",\"unit_type_name\":\"Apartment\",\"built_up_area\":1022,\"expected_sale_amount\":5110000},\"customer\":{\"id\":1,\"name\":\"jhancy\",\"phone\":\"09048393993\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan\"},\"receipts\":[{\"id\":1,\"receipt_number\":null,\"receipt_date\":\"2026-10-15\",\"amount\":1500000,\"payment_mode\":\"Cheque\",\"reference_no\":\"456123\",\"status\":\"posted\"},{\"id\":2,\"receipt_number\":null,\"receipt_date\":\"2026-10-15\",\"amount\":312412.5,\"payment_mode\":\"Bank Transfer (NEFT \\/ RTGS \\/ IMPS)\",\"reference_no\":\"456456\",\"status\":\"posted\"}],\"installments\":[{\"id\":1,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-10-15\",\"amount\":1500000,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":2,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-11-15\",\"amount\":312412.5,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":3,\"installment_no\":2,\"label\":\"EMI 2\",\"due_date\":\"2026-12-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":4,\"installment_no\":3,\"label\":\"EMI 3\",\"due_date\":\"2027-01-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":5,\"installment_no\":4,\"label\":\"EMI 4\",\"due_date\":\"2027-02-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":6,\"installment_no\":5,\"label\":\"EMI 5\",\"due_date\":\"2027-03-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":7,\"installment_no\":6,\"label\":\"EMI 6\",\"due_date\":\"2027-04-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":8,\"installment_no\":7,\"label\":\"EMI 7\",\"due_date\":\"2027-05-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":9,\"installment_no\":8,\"label\":\"EMI 8\",\"due_date\":\"2027-06-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":10,\"installment_no\":9,\"label\":\"EMI 9\",\"due_date\":\"2027-07-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":11,\"installment_no\":10,\"label\":\"EMI 10\",\"due_date\":\"2027-08-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":12,\"installment_no\":11,\"label\":\"EMI 11\",\"due_date\":\"2027-09-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":13,\"installment_no\":12,\"label\":\"EMI 12\",\"due_date\":\"2027-10-15\",\"amount\":312412.5,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}]}', 1, '2026-10-16 05:07:46', '2026-10-16 05:07:46'),
(4, 1, 'cancelled', 'cancelled', 'refund_processed', 'Processed customer refund of ₹1,000,000.00 via Cheque (Axis Bank 9752)', '{\"refund_amount\":1000000,\"payment_mode\":\"Cheque\",\"company_bank_account\":\"Axis Bank 9752\",\"journal_voucher_no\":\"JV-RF-2026-0001\",\"remarks\":\"Customer refunghd processed as per cancellation agreement.\"}', 1, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(5, 1, 'cancelled', 'cancelled', 'refund_processed', 'Processed customer refund of ₹712,412.00 via Cheque (Axis Bank 9752)', '{\"refund_amount\":712412,\"payment_mode\":\"Cheque\",\"company_bank_account\":\"Axis Bank 9752\",\"journal_voucher_no\":\"JV-RF-2026-0001-1\",\"remarks\":\"Customer refund processed as per cancellation agreement.\"}', 1, '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(6, 2, 'active', 'exchanged', 'exchanged', 'k', '{\"old_sale\":{\"id\":2,\"sale_number\":\"TH-AP-EB-SOUM\",\"sale_date\":\"2026-10-16\",\"agreement_date\":\"2026-10-16\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":12,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-11-16\",\"rate_per_sqft\":5500,\"sale_amount\":9046000,\"gst_type\":\"exclusive\",\"gst_amount\":452300,\"base_amount\":9046000,\"total_amount\":9498300,\"remaining_balance\":7498300,\"total_paid\":2000000},\"old_unit\":{\"id\":24,\"door_no\":\"E B\",\"floor_name\":\"Floor 8\",\"unit_type_name\":\"Apartment\",\"built_up_area\":1572,\"expected_sale_amount\":7860000},\"customer\":{\"id\":2,\"name\":\"soumya\",\"phone\":\"8589964266\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan\"},\"receipts\":[{\"id\":3,\"receipt_number\":null,\"receipt_date\":\"2026-10-16\",\"amount\":2000000,\"payment_mode\":\"Cheque\",\"reference_no\":\"456456\",\"status\":\"posted\"}],\"installments\":[{\"id\":14,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-10-16\",\"amount\":2000000,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":15,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-11-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":16,\"installment_no\":2,\"label\":\"EMI 2\",\"due_date\":\"2026-12-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":17,\"installment_no\":3,\"label\":\"EMI 3\",\"due_date\":\"2027-01-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":18,\"installment_no\":4,\"label\":\"EMI 4\",\"due_date\":\"2027-02-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":19,\"installment_no\":5,\"label\":\"EMI 5\",\"due_date\":\"2027-03-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":20,\"installment_no\":6,\"label\":\"EMI 6\",\"due_date\":\"2027-04-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":21,\"installment_no\":7,\"label\":\"EMI 7\",\"due_date\":\"2027-05-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":22,\"installment_no\":8,\"label\":\"EMI 8\",\"due_date\":\"2027-06-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":23,\"installment_no\":9,\"label\":\"EMI 9\",\"due_date\":\"2027-07-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":24,\"installment_no\":10,\"label\":\"EMI 10\",\"due_date\":\"2027-08-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":25,\"installment_no\":11,\"label\":\"EMI 11\",\"due_date\":\"2027-09-16\",\"amount\":624858.33,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":26,\"installment_no\":12,\"label\":\"EMI 12\",\"due_date\":\"2027-10-16\",\"amount\":624858.37,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}],\"exchange_meta\":{\"target_unit_id\":9,\"target_door_no\":\"S 1\",\"carry_forward\":true,\"exchange_reason\":\"k\",\"exchanged_at\":\"2026-10-16 09:15:55\",\"exchanged_by_user\":\"Owner\"}}', 1, '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(7, 3, NULL, 'active', 'created', 'Created via unit exchange from sale TH-AP-EB-SOUM', '{\"old_sale\":{\"id\":3,\"sale_number\":\"TH-SH-S1-SOUM\",\"sale_date\":\"2026-10-16\",\"agreement_date\":\"2026-10-16\",\"status\":\"active\",\"payment_plan\":\"emi\",\"emi_type\":\"equal\",\"emi_installment_count\":12,\"emi_frequency\":\"monthly\",\"first_installment_date\":\"2026-11-16\",\"rate_per_sqft\":18000,\"sale_amount\":17968000,\"gst_type\":\"exclusive\",\"gst_amount\":2128160,\"base_amount\":17968000,\"total_amount\":20096160,\"remaining_balance\":13096160,\"total_paid\":7000000},\"old_unit\":{\"id\":9,\"door_no\":\"S 1\",\"floor_name\":\"Floor 2\",\"unit_type_name\":\"Shop\",\"built_up_area\":976,\"expected_sale_amount\":18544000},\"customer\":{\"id\":2,\"name\":\"soumya\",\"phone\":\"8589964266\"},\"project\":{\"id\":1,\"name\":\"Tabasco Hindustan\"},\"receipts\":[{\"id\":3,\"receipt_number\":null,\"receipt_date\":\"2026-10-16\",\"amount\":2000000,\"payment_mode\":\"Cheque\",\"reference_no\":\"456456\",\"status\":\"posted\"},{\"id\":4,\"receipt_number\":null,\"receipt_date\":\"2026-10-16\",\"amount\":5000000,\"payment_mode\":\"Cheque\",\"reference_no\":\"123456\",\"status\":\"posted\"}],\"installments\":[{\"id\":27,\"installment_no\":0,\"label\":\"Down Payment\",\"due_date\":\"2026-10-16\",\"amount\":7000000,\"status\":\"paid\",\"schedule_type\":\"fixed_emi\"},{\"id\":28,\"installment_no\":1,\"label\":\"EMI 1\",\"due_date\":\"2026-11-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":29,\"installment_no\":2,\"label\":\"EMI 2\",\"due_date\":\"2026-12-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":30,\"installment_no\":3,\"label\":\"EMI 3\",\"due_date\":\"2027-01-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":31,\"installment_no\":4,\"label\":\"EMI 4\",\"due_date\":\"2027-02-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":32,\"installment_no\":5,\"label\":\"EMI 5\",\"due_date\":\"2027-03-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":33,\"installment_no\":6,\"label\":\"EMI 6\",\"due_date\":\"2027-04-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":34,\"installment_no\":7,\"label\":\"EMI 7\",\"due_date\":\"2027-05-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":35,\"installment_no\":8,\"label\":\"EMI 8\",\"due_date\":\"2027-06-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":36,\"installment_no\":9,\"label\":\"EMI 9\",\"due_date\":\"2027-07-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":37,\"installment_no\":10,\"label\":\"EMI 10\",\"due_date\":\"2027-08-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":38,\"installment_no\":11,\"label\":\"EMI 11\",\"due_date\":\"2027-09-16\",\"amount\":1091346.67,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"},{\"id\":39,\"installment_no\":12,\"label\":\"EMI 12\",\"due_date\":\"2027-10-16\",\"amount\":1091346.63,\"status\":\"pending\",\"schedule_type\":\"fixed_emi\"}]}', 1, '2026-10-16 05:15:55', '2026-10-16 05:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sale_units`
--

CREATE TABLE `hindustansystem_sale_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `wing` varchar(191) DEFAULT NULL,
  `rate_per_sqft` decimal(15,2) NOT NULL,
  `area_sqft` decimal(15,2) NOT NULL,
  `base_amount` decimal(15,2) NOT NULL,
  `gst_type` enum('none','inclusive','exclusive') NOT NULL DEFAULT 'none',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(15,2) NOT NULL,
  `brokerage_type` enum('percentage','fixed') DEFAULT NULL,
  `brokerage_value` decimal(15,2) DEFAULT NULL,
  `brokerage_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sale_units`
--

INSERT INTO `hindustansystem_sale_units` (`id`, `sale_id`, `unit_id`, `wing`, `rate_per_sqft`, `area_sqft`, `base_amount`, `gst_type`, `gst_percentage`, `gst_amount`, `line_total`, `brokerage_type`, `brokerage_value`, `brokerage_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 17, NULL, '4500.00', '1022.00', '4599000.00', 'exclusive', '5.00', '229950.00', '4828950.00', NULL, NULL, '0.00', '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(2, 1, 53, NULL, '0.00', '1.00', '400000.00', 'exclusive', '5.00', '20000.00', '420000.00', NULL, NULL, '0.00', '2026-10-15 05:24:00', '2026-10-15 05:24:00'),
(3, 2, 24, NULL, '5500.00', '1572.00', '8646000.00', 'exclusive', '5.00', '432300.00', '9078300.00', NULL, NULL, '0.00', '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(4, 2, 61, NULL, '0.00', '1.00', '400000.00', 'exclusive', '5.00', '20000.00', '420000.00', NULL, NULL, '0.00', '2026-10-16 04:49:30', '2026-10-16 04:49:30'),
(5, 3, 9, NULL, '18000.00', '976.00', '17568000.00', 'exclusive', '18.00', '2108160.00', '19676160.00', NULL, NULL, '0.00', '2026-10-16 05:15:55', '2026-10-16 05:15:55'),
(6, 3, 61, NULL, '0.00', '1.00', '400000.00', 'exclusive', '5.00', '20000.00', '420000.00', NULL, NULL, '0.00', '2026-10-16 05:15:55', '2026-10-16 05:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sessions`
--

CREATE TABLE `hindustansystem_sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sessions`
--

INSERT INTO `hindustansystem_sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dRNMqwkQLMm1OxUSfLx8U6XhOQzgtByWfa4PBBMx', 1, '59.88.130.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3S3JSTmRmamN2cUtBY0d6cHZFS2IzTkFrQWJvZndZNXg0eW1Xdlg5IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9yZWFsdHkueWFsbGEtd2ViLmNvbVwvdjVcL3B1YmxpY1wvY2hlcXVlLXJlYWxpemF0aW9uXC9yZWFsaXplZCIsInJvdXRlIjoiY2hlcXVlLXJlYWxpemF0aW9uLnJlYWxpemVkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1792386422),
('hHnmpgXNj9o2ij3bLpeL8sIRNgMOt1eyDmBGPN7k', 1, '59.88.130.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJIUFI5SklYTUxvWVNEeURuRXdNRmhHSFliaXVIclhPMVpZblJpc0NwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9yZWFsdHkueWFsbGEtd2ViLmNvbVwvdjVcL3B1YmxpY1wvZGFzaGJvYXJkIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1792386578),
('lPkv7cO2ZxXrl5VqXIInJSg10QLtHQAjICsMBDhq', NULL, '103.179.196.233', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ0VE1LWmFXaVZtTHFpMDNjYkpJVDF4c0hiczhzYWVjNXp1THlGc3ZiIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3JlYWx0eS55YWxsYS13ZWIuY29tXC92NVwvcHVibGljXC9yZXBvcnRzXC9wYXJ0bmVyLW91dGZsb3ctbGVkZ2VyIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvcmVhbHR5LnlhbGxhLXdlYi5jb21cL3Y1XC9wdWJsaWNcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1792382192);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_site_expenses`
--

CREATE TABLE `hindustansystem_site_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `voucher_number` varchar(50) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `floor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tower_block_tag` varchar(100) DEFAULT NULL,
  `voucher_date` date NOT NULL,
  `payee_type` enum('registered','one_time') NOT NULL DEFAULT 'registered',
  `payee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `casual_payee_name` varchar(255) DEFAULT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_category_code` varchar(20) NOT NULL,
  `expense_category_name` varchar(150) NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `cgst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sgst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `igst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_gst_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_source_type` enum('bank','loan') NOT NULL DEFAULT 'bank',
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_reference_no` varchar(100) NOT NULL,
  `narration` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Pending',
  `payment_status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_site_expense_categories`
--

CREATE TABLE `hindustansystem_site_expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_code` varchar(50) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_site_expense_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `site_expense_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(255) NOT NULL DEFAULT 'Bank Transfer',
  `payment_source_type` enum('bank','loan') NOT NULL DEFAULT 'bank',
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `is_historical` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_systems`
--

CREATE TABLE `hindustansystem_systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(2) NOT NULL,
  `country` varchar(191) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `gst_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `vat_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `timezone` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_unit_rate_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `revision_type` varchar(50) DEFAULT NULL,
  `change_details` varchar(255) DEFAULT NULL,
  `amount_change` decimal(15,2) DEFAULT NULL,
  `effective_from` date NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_rate_logs`
--

INSERT INTO `hindustansystem_unit_rate_logs` (`id`, `unit_id`, `rate`, `revision_type`, `change_details`, `amount_change`, `effective_from`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, '22000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 01:59:33'),
(2, 2, '22000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 01:59:49'),
(3, 3, '22000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:00:04'),
(4, 4, '22000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:00:24'),
(5, 5, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:00:49'),
(6, 6, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:01:05'),
(7, 7, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:01:30'),
(8, 8, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:01:49'),
(9, 9, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:02:32'),
(10, 10, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:02:54'),
(11, 11, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:03:12'),
(12, 12, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:03:32'),
(13, 13, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:03:59'),
(14, 14, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:04:22'),
(15, 15, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:04:41'),
(16, 16, '19000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:05:01'),
(17, 17, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:07:04'),
(18, 18, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:09:06'),
(19, 19, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:09:26'),
(20, 20, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:10:52'),
(21, 21, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:11:07'),
(22, 22, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:11:26'),
(23, 23, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:11:46'),
(24, 24, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:12:07'),
(25, 25, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:12:26'),
(26, 26, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:12:46'),
(27, 27, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:13:04'),
(28, 28, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:13:23'),
(29, 29, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:14:13'),
(30, 30, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:14:30'),
(31, 31, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:14:51'),
(32, 32, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:15:06'),
(33, 33, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:15:26'),
(34, 34, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:15:42'),
(35, 35, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:16:04'),
(36, 36, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:16:35'),
(37, 37, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:16:54'),
(38, 38, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:17:12'),
(39, 39, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:17:29'),
(40, 40, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:17:48'),
(41, 41, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:18:05'),
(42, 42, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:18:21'),
(43, 43, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:18:36'),
(44, 44, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:18:58'),
(45, 45, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:19:22'),
(46, 46, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:20:04'),
(47, 47, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:22:40'),
(48, 48, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:22:56'),
(49, 49, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:23:13'),
(50, 50, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:23:44'),
(51, 51, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:24:13'),
(52, 52, '5000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:24:32'),
(53, 53, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:25:58'),
(54, 54, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:26:10'),
(55, 55, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:26:20'),
(56, 56, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:26:31'),
(57, 57, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:26:41'),
(58, 58, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:26:51'),
(59, 59, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:27:01'),
(60, 60, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:27:13'),
(61, 61, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:27:25'),
(62, 62, '400000.00', 'Base Price Adjustment', 'Base Price Adjustment set to same rate', '0.00', '2026-10-15', 1, 'Initial Rate', '2026-10-15 02:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_status_logs`
--

CREATE TABLE `hindustansystem_unit_status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(191) DEFAULT NULL,
  `to_status` varchar(191) NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_status_logs`
--

INSERT INTO `hindustansystem_unit_status_logs` (`id`, `unit_id`, `from_status`, `to_status`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, NULL, 'available', 1, 'Unit creation', '2026-10-15 01:59:33'),
(2, 2, NULL, 'available', 1, 'Unit creation', '2026-10-15 01:59:49'),
(3, 3, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:00:04'),
(4, 4, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:00:24'),
(5, 5, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:00:49'),
(6, 6, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:01:05'),
(7, 7, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:01:30'),
(8, 8, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:01:49'),
(9, 9, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:02:32'),
(10, 10, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:02:54'),
(11, 11, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:03:12'),
(12, 12, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:03:32'),
(13, 13, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:03:59'),
(14, 14, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:04:22'),
(15, 15, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:04:41'),
(16, 16, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:05:01'),
(17, 17, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:07:04'),
(18, 18, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:09:06'),
(19, 19, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:09:26'),
(20, 20, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:10:52'),
(21, 21, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:11:07'),
(22, 22, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:11:26'),
(23, 23, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:11:46'),
(24, 24, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:12:07'),
(25, 25, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:12:26'),
(26, 26, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:12:46'),
(27, 27, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:13:04'),
(28, 28, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:13:23'),
(29, 29, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:14:13'),
(30, 30, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:14:30'),
(31, 31, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:14:51'),
(32, 32, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:15:06'),
(33, 33, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:15:26'),
(34, 34, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:15:42'),
(35, 35, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:16:04'),
(36, 36, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:16:35'),
(37, 37, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:16:54'),
(38, 38, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:17:12'),
(39, 39, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:17:29'),
(40, 40, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:17:48'),
(41, 41, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:18:05'),
(42, 42, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:18:21'),
(43, 43, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:18:36'),
(44, 44, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:18:58'),
(45, 45, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:19:22'),
(46, 46, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:20:04'),
(47, 47, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:22:40'),
(48, 48, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:22:56'),
(49, 49, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:23:13'),
(50, 50, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:23:44'),
(51, 51, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:24:13'),
(52, 52, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:24:32'),
(53, 53, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:25:58'),
(54, 54, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:26:10'),
(55, 55, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:26:20'),
(56, 56, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:26:31'),
(57, 57, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:26:41'),
(58, 58, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:26:51'),
(59, 59, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:27:01'),
(60, 60, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:27:13'),
(61, 61, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:27:25'),
(62, 62, NULL, 'available', 1, 'Unit creation', '2026-10-15 02:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_types`
--

CREATE TABLE `hindustansystem_unit_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `category` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `hindustansystem_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `employee_code` varchar(191) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_users`
--

INSERT INTO `hindustansystem_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `system_id`, `phone`, `employee_code`, `status`, `must_change_password`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Owner', 'owner@hindustan.com', '2026-07-06 03:53:39', '$2y$12$q1CE0lNCVgHBiMgVS2WQyeWdk0VYl/OyTRq9GEQrxj0tWuulWSCoq', 'm1NpbMpE0931MPmqzDHcRWbUxZT6udtMltiukbNbXS2VdYmCcdJzsVEdCRqa', '2026-07-06 03:53:39', '2026-07-06 03:53:39', 1, '+91 99999 99999', 'EMP-001', 'active', 0, NULL, NULL),
(2, 'Rajesh Accountant (IN)', 'accountant.in@hindustan.com', '2026-07-06 03:53:40', '$2y$12$rVofIQZI2tIVy/jHVFLVPOyEJC2tVyvtMlUSsiLb.mLVUQRVh/W6m', NULL, '2026-07-06 03:53:40', '2026-07-09 03:35:53', 1, '+91 98765 00001', 'EMP-IN-ACC01', 'active', 0, NULL, NULL),
(3, 'Omar Accountant (UAE)', 'accountant.ae@hindustan.com', '2026-07-06 03:53:40', '$2y$12$nhCUlYVdehjhoRDNzzde9.stFLNk3mZ/j0du.HsDo9mMygrk2oi5y', NULL, '2026-07-06 03:53:40', '2026-07-06 03:53:40', 2, '+971 50 123 4567', 'EMP-AE-ACC01', 'active', 0, NULL, NULL),
(4, 'Vikram Sales (IN)', 'sales.in@hindustan.com', '2026-07-06 03:53:41', '$2y$12$1AO5aTilUgSLi2SM1tPv4uYtmKrQH1a7/Dr6n7rLEM1X4nil2G2HO', NULL, '2026-07-06 03:53:41', '2026-07-06 03:53:41', 1, '+91 98765 00002', 'EMP-IN-SAL01', 'active', 0, NULL, NULL),
(5, 'Amit Site (IN)', 'site.in@hindustan.com', '2026-07-06 03:53:41', '$2y$12$juyU2BYL8M9a3OyCdSiE.e/1wV6hv4dbJdsRgRyPPOTk9atNE45cy', NULL, '2026-07-06 03:53:41', '2026-07-08 23:56:15', 1, '+91 98765 00003', 'EMP-IN-SIT01', 'active', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_user_dashboard_layouts`
--

CREATE TABLE `hindustansystem_user_dashboard_layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `layout_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`layout_settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_vendors`
--

CREATE TABLE `hindustansystem_vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `vendor_code` varchar(50) NOT NULL,
  `name` varchar(191) NOT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `gstin` varchar(15) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `linked_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_vendors`
--

INSERT INTO `hindustansystem_vendors` (`id`, `system_id`, `vendor_code`, `name`, `contact_person`, `phone`, `email`, `gstin`, `pan`, `address`, `bank_name`, `account_number`, `ifsc_code`, `branch`, `linked_account_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'VND-0001', 'INDO GULF SPECIALITIES & REHABS', 'JAISON', NULL, 'igsr.2018@gmail.com', '32AAFF18802B1ZW', NULL, NULL, NULL, NULL, NULL, NULL, 89, 1, '2026-09-26 00:41:00', '2026-09-26 00:41:00'),
(2, 1, 'VND-0002', 'MECHWISE SOLUTION PVT. LTD', 'MOHAMMED ANZAM', '8714488558', 'info@mechwiseparking.com', NULL, NULL, NULL, 'HDFC', '50200087625952', NULL, NULL, 92, 1, '2026-10-02 02:31:37', '2026-10-02 02:31:37'),
(3, 1, 'VND-0003', 'SNOWBELL TECHNOLOGIES', 'NASIR', '859309905', 'accounts@snowbelltech.com', '32ABICS1884N1ZU', NULL, 'CCTV', 'HDFC', NULL, NULL, NULL, 94, 1, '2026-10-03 02:53:16', '2026-10-03 02:53:16'),
(4, 1, 'VND-0004', 'GREEN INDIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 96, 1, '2026-10-07 04:24:37', '2026-10-07 04:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_vouchers`
--

CREATE TABLE `hindustansystem_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `company_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_number` varchar(191) NOT NULL,
  `type` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `narration` text DEFAULT NULL,
  `reference_no` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `edited_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `reversal_of_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_vouchers`
--

INSERT INTO `hindustansystem_vouchers` (`id`, `system_id`, `company_bank_account_id`, `voucher_number`, `type`, `date`, `narration`, `reference_no`, `created_by`, `edited_by`, `status`, `reversal_of_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'PV-BROKER-1-1792141280', 'Payment', '2026-10-16', 'yrtyr [Mode: Cheque]', '666566', 1, NULL, 'Posted', NULL, '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(2, 1, 1, 'PY-REF-20261016-090838-289', 'Payment', '2026-10-16', 'Customer Refund for cancellation on Unit SI A (jhancy) — Cheque (Customer refunghd processed as per cancellation agreement.)', '{\"allocations\":[{\"type\":\"refund\",\"target_id\":1,\"amount\":1000000}],\"payment_mode\":\"Cheque\",\"company_bank_account_id\":1,\"remarks\":\"Customer refunghd processed as per cancellation agreement.\"}', 1, NULL, 'Posted', NULL, '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(3, 1, 1, 'PY-REF-20261016-090922-900', 'Payment', '2026-10-16', 'Customer Refund for cancellation on Unit SI A (jhancy) — Cheque (Customer refund processed as per cancellation agreement.)', '{\"allocations\":[{\"type\":\"refund\",\"target_id\":1,\"amount\":712412}],\"payment_mode\":\"Cheque\",\"company_bank_account_id\":1,\"remarks\":\"Customer refund processed as per cancellation agreement.\"}', 1, NULL, 'Posted', NULL, '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(4, 1, 1, 'PCON-00001', 'Contra', '2026-10-17', 'cash withdrawed for office use', '655', 1, NULL, 'Posted', NULL, '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(5, 1, NULL, 'PV-00001', 'Payment', '2026-10-17', 'Staggered RA Bill Disbursement for #1 (MKH INFRASTRUCTURE)', 'YFYTYU', 1, NULL, 'Posted', NULL, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(6, 1, NULL, 'PV-00002', 'Payment', '2026-10-17', 'Staggered RA Bill Disbursement for #1 (MKH INFRASTRUCTURE)', '1336655', 1, NULL, 'Posted', NULL, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(7, 1, NULL, 'PV-00003', 'Payment', '2026-10-30', 'Staggered RA Bill Disbursement for #2 (MKH INFRASTRUCTURE)', 'UITI', 1, NULL, 'Posted', NULL, '2026-10-17 04:50:44', '2026-10-17 04:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_voucher_lines`
--

CREATE TABLE `hindustansystem_voucher_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_narration` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_voucher_lines`
--

INSERT INTO `hindustansystem_voucher_lines` (`id`, `voucher_id`, `account_id`, `debit`, `credit`, `line_narration`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '40000.00', '0.00', 'Debit Broker commission payable', '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(2, 1, 2, '0.00', '40000.00', 'Credit Bank for commission payout', '2026-10-16 05:01:20', '2026-10-16 05:01:20'),
(3, 2, 3, '1000000.00', '0.00', 'Customer Refund Payout to jhancy', '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(4, 2, 4, '0.00', '1000000.00', 'Axis Bank 9752 Payout', '2026-10-16 05:08:38', '2026-10-16 05:08:38'),
(5, 3, 3, '712412.00', '0.00', 'Customer Refund Payout to jhancy', '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(6, 3, 4, '0.00', '712412.00', 'Axis Bank 9752 Payout', '2026-10-16 05:09:22', '2026-10-16 05:09:22'),
(7, 4, 17, '0.00', '20000.00', 'Cash withdrawal from bank', '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(8, 4, 1, '20000.00', '0.00', 'Petty cash receipt via contra', '2026-10-17 00:57:47', '2026-10-17 00:57:47'),
(9, 5, 18, '2000000.00', '0.00', NULL, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(10, 5, 2, '0.00', '2000000.00', NULL, '2026-10-17 04:46:07', '2026-10-17 04:46:07'),
(11, 6, 18, '1500000.00', '0.00', NULL, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(12, 6, 2, '0.00', '1500000.00', NULL, '2026-10-17 04:47:33', '2026-10-17 04:47:33'),
(13, 7, 18, '1000000.00', '0.00', NULL, '2026-10-17 04:50:44', '2026-10-17 04:50:44'),
(14, 7, 2, '0.00', '1000000.00', NULL, '2026-10-17 04:50:44', '2026-10-17 04:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_voucher_types`
--

CREATE TABLE `hindustansystem_voucher_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(20, 'PARTNER_CONTRIBUTION', 'Partner Capital Contribution Voucher', 'JV-PC', 'Generated on partner capital contribution deposit', 1, '2026-10-03 02:01:18', '2026-10-03 02:01:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hindustansystem_accounting_settings`
--
ALTER TABLE `hindustansystem_accounting_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_accounting_settings_key_unique` (`key`);

--
-- Indexes for table `hindustansystem_accounts`
--
ALTER TABLE `hindustansystem_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_accounts_system_id_code_unique` (`system_id`,`code`),
  ADD KEY `hindustansystem_accounts_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `hindustansystem_activity_logs`
--
ALTER TABLE `hindustansystem_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `hindustansystem_activity_logs_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Indexes for table `hindustansystem_approvals`
--
ALTER TABLE `hindustansystem_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_approvals_approvable_type_approvable_id_index` (`approvable_type`,`approvable_id`),
  ADD KEY `hindustansystem_approvals_requested_by_foreign` (`requested_by`),
  ADD KEY `hindustansystem_approvals_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `hindustansystem_approval_requests`
--
ALTER TABLE `hindustansystem_approval_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_approval_rules`
--
ALTER TABLE `hindustansystem_approval_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_banks`
--
ALTER TABLE `hindustansystem_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_bills`
--
ALTER TABLE `hindustansystem_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_bills_system_id_bill_number_unique` (`system_id`,`bill_number`),
  ADD KEY `hindustansystem_bills_payee_id_foreign` (`payee_id`),
  ADD KEY `hindustansystem_bills_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_bills_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `hindustansystem_bill_payments`
--
ALTER TABLE `hindustansystem_bill_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_bill_payments_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_bill_payments_voucher_id_foreign` (`voucher_id`),
  ADD KEY `bp_bill_fk` (`bill_id`),
  ADD KEY `bp_payee_fk` (`payee_id`);

--
-- Indexes for table `hindustansystem_bookings`
--
ALTER TABLE `hindustansystem_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_bookings_booking_number_unique` (`booking_number`),
  ADD KEY `hindustansystem_bookings_customer_id_foreign` (`customer_id`),
  ADD KEY `hindustansystem_bookings_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_bookings_unit_id_foreign` (`unit_id`),
  ADD KEY `hindustansystem_bookings_sales_executive_id_foreign` (`sales_executive_id`),
  ADD KEY `hindustansystem_bookings_broker_id_foreign` (`broker_id`);

--
-- Indexes for table `hindustansystem_brokerages`
--
ALTER TABLE `hindustansystem_brokerages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_brokerages_sale_id_foreign` (`sale_id`),
  ADD KEY `hindustansystem_brokerages_broker_id_foreign` (`broker_id`),
  ADD KEY `hindustansystem_brokerages_sale_unit_id_foreign` (`sale_unit_id`);

--
-- Indexes for table `hindustansystem_brokers`
--
ALTER TABLE `hindustansystem_brokers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_brokers_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_brokers_linked_account_id_foreign` (`linked_account_id`);

--
-- Indexes for table `hindustansystem_cache`
--
ALTER TABLE `hindustansystem_cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `hindustansystem_cache_expiration_index` (`expiration`);

--
-- Indexes for table `hindustansystem_cache_locks`
--
ALTER TABLE `hindustansystem_cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `hindustansystem_cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `hindustansystem_categories`
--
ALTER TABLE `hindustansystem_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hindustansystem_categories_project_id` (`project_id`),
  ADD KEY `categories_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `hindustansystem_chart_of_accounts`
--
ALTER TABLE `hindustansystem_chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  ADD KEY `hindustansystem_chart_of_accounts_project_id_foreign` (`project_id`);

--
-- Indexes for table `hindustansystem_cheque_statuses`
--
ALTER TABLE `hindustansystem_cheque_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cheque_statuses_name_unique` (`name`);

--
-- Indexes for table `hindustansystem_collection_reminders`
--
ALTER TABLE `hindustansystem_collection_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collection_reminders_customer_id_foreign` (`customer_id`),
  ADD KEY `collection_reminders_sale_id_foreign` (`sale_id`),
  ADD KEY `collection_reminders_installment_id_foreign` (`installment_id`);

--
-- Indexes for table `hindustansystem_commission_entries`
--
ALTER TABLE `hindustansystem_commission_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_commission_entries_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_commission_entries_voucher_id_foreign` (`voucher_id`),
  ADD KEY `ce_deal_fk` (`deal_id`);

--
-- Indexes for table `hindustansystem_company_bank_accounts`
--
ALTER TABLE `hindustansystem_company_bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_customers`
--
ALTER TABLE `hindustansystem_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_customers_email_unique` (`email`);

--
-- Indexes for table `hindustansystem_customer_installments`
--
ALTER TABLE `hindustansystem_customer_installments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_customer_installments_sale_id_foreign` (`sale_id`),
  ADD KEY `cust_inst_rescheduled_fk` (`rescheduled_from_id`);

--
-- Indexes for table `hindustansystem_deals`
--
ALTER TABLE `hindustansystem_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_deals_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_deals_broker_id_foreign` (`broker_id`),
  ADD KEY `hindustansystem_deals_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_deals_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `hindustansystem_dms_categories`
--
ALTER TABLE `hindustansystem_dms_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dms_categories_code_unique` (`code`);

--
-- Indexes for table `hindustansystem_dms_documents`
--
ALTER TABLE `hindustansystem_dms_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_dms_documents_system_id_index` (`system_id`),
  ADD KEY `dms_docs_morph_idx` (`documentable_type`,`documentable_id`),
  ADD KEY `hindustansystem_dms_documents_category_document_type_index` (`category`,`document_type`),
  ADD KEY `dms_documents_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `dms_documents_reference_project_id_index` (`reference_project_id`);

--
-- Indexes for table `hindustansystem_dms_document_types`
--
ALTER TABLE `hindustansystem_dms_document_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dms_document_types_category_id_index` (`dms_category_id`);

--
-- Indexes for table `hindustansystem_emi_reschedule_logs`
--
ALTER TABLE `hindustansystem_emi_reschedule_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_emi_reschedule_logs_sale_id_foreign` (`sale_id`),
  ADD KEY `hindustansystem_emi_reschedule_logs_performed_by_foreign` (`performed_by`);

--
-- Indexes for table `hindustansystem_emi_schedules`
--
ALTER TABLE `hindustansystem_emi_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_emi_schedules_system_id_foreign` (`system_id`),
  ADD KEY `es_loan_fk` (`loan_id`);

--
-- Indexes for table `hindustansystem_employees`
--
ALTER TABLE `hindustansystem_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_employees_employee_id_unique` (`employee_id`),
  ADD KEY `hindustansystem_employees_system_id_foreign` (`system_id`);

--
-- Indexes for table `hindustansystem_engineers`
--
ALTER TABLE `hindustansystem_engineers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `engineers_engineer_code_unique` (`engineer_code`),
  ADD KEY `engineers_project_id_foreign` (`project_id`);

--
-- Indexes for table `hindustansystem_failed_jobs`
--
ALTER TABLE `hindustansystem_failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hindustansystem_floors`
--
ALTER TABLE `hindustansystem_floors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_floors_project_id_floor_number_unique` (`project_id`,`floor_number`);

--
-- Indexes for table `hindustansystem_hindustan_units`
--
ALTER TABLE `hindustansystem_hindustan_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustan_units_project_id_foreign` (`project_id`),
  ADD KEY `hindustan_units_floor_id_foreign` (`floor_id`),
  ADD KEY `hindustan_units_unit_type_id_foreign` (`unit_type_id`);

--
-- Indexes for table `hindustansystem_jobs`
--
ALTER TABLE `hindustansystem_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_jobs_queue_index` (`queue`);

--
-- Indexes for table `hindustansystem_job_batches`
--
ALTER TABLE `hindustansystem_job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_journal_entries`
--
ALTER TABLE `hindustansystem_journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entries_account_id_index` (`account_id`),
  ADD KEY `journal_entries_voucher_id_foreign` (`voucher_id`),
  ADD KEY `journal_entries_entity_type_entity_id_index` (`entity_type`,`entity_id`);

--
-- Indexes for table `hindustansystem_journal_vouchers`
--
ALTER TABLE `hindustansystem_journal_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_vouchers_voucher_no_unique` (`voucher_no`),
  ADD KEY `journal_vouchers_voucher_type_id_index` (`voucher_type_id`);

--
-- Indexes for table `hindustansystem_ledger_entries`
--
ALTER TABLE `hindustansystem_ledger_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_ledger_entries_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_ledger_entries_account_id_foreign` (`account_id`),
  ADD KEY `hindustansystem_ledger_entries_voucher_id_foreign` (`voucher_id`),
  ADD KEY `hindustansystem_ledger_entries_voucher_line_id_foreign` (`voucher_line_id`);

--
-- Indexes for table `hindustansystem_loans`
--
ALTER TABLE `hindustansystem_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_loans_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_loans_project_id_foreign` (`project_id`),
  ADD KEY `l_ledger_fk` (`ledger_account_id`),
  ADD KEY `l_interest_fk` (`interest_account_id`);

--
-- Indexes for table `hindustansystem_loan_disbursals`
--
ALTER TABLE `hindustansystem_loan_disbursals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_disbursals_system_id_index` (`system_id`),
  ADD KEY `loan_disbursals_loan_id_index` (`loan_id`),
  ADD KEY `loan_disbursals_disbursal_no_index` (`disbursal_no`),
  ADD KEY `loan_disbursals_created_by_foreign` (`created_by`),
  ADD KEY `loan_disbursals_posted_by_foreign` (`posted_by`),
  ADD KEY `loan_disbursals_cancelled_by_foreign` (`cancelled_by`);

--
-- Indexes for table `hindustansystem_loan_interest_logs`
--
ALTER TABLE `hindustansystem_loan_interest_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_loan_interest_logs_loan_id_index` (`loan_id`);

--
-- Indexes for table `hindustansystem_loan_prepayments`
--
ALTER TABLE `hindustansystem_loan_prepayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_loan_prepayments_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `hindustansystem_migrations`
--
ALTER TABLE `hindustansystem_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_model_has_permissions`
--
ALTER TABLE `hindustansystem_model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `hindustansystem_model_has_roles`
--
ALTER TABLE `hindustansystem_model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `hindustansystem_partner_allocations`
--
ALTER TABLE `hindustansystem_partner_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_partner_allocations_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_partner_allocations_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_partner_allocations_payment_id_foreign` (`payment_id`),
  ADD KEY `hindustansystem_partner_allocations_voucher_id_foreign` (`voucher_id`),
  ADD KEY `pa_partner_fk` (`partner_id`),
  ADD KEY `pa_cbank_fk` (`company_bank_account_id`);

--
-- Indexes for table `hindustansystem_partner_contributions`
--
ALTER TABLE `hindustansystem_partner_contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prtc_partner` (`partner_id`),
  ADD KEY `fk_prtc_project` (`project_id`),
  ADD KEY `fk_prtc_bank` (`company_bank_account_id`),
  ADD KEY `fk_prtc_payment_mode` (`payment_mode_id`);

--
-- Indexes for table `hindustansystem_partner_shares`
--
ALTER TABLE `hindustansystem_partner_shares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ps_sys_proj_part_unique` (`system_id`,`project_id`,`partner_id`),
  ADD KEY `hindustansystem_partner_shares_project_id_foreign` (`project_id`),
  ADD KEY `ps_partner_fk` (`partner_id`);

--
-- Indexes for table `hindustansystem_password_reset_tokens`
--
ALTER TABLE `hindustansystem_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `hindustansystem_payees`
--
ALTER TABLE `hindustansystem_payees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_payees_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_payees_linked_account_id_foreign` (`linked_account_id`);

--
-- Indexes for table `hindustansystem_payments`
--
ALTER TABLE `hindustansystem_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_payments_receipt_number_unique` (`receipt_number`),
  ADD KEY `hindustansystem_payments_customer_id_foreign` (`customer_id`),
  ADD KEY `hindustansystem_payments_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_payments_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `hindustansystem_payment_modes`
--
ALTER TABLE `hindustansystem_payment_modes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_payment_modes_system_id_code_unique` (`system_id`,`code`),
  ADD KEY `hindustansystem_payment_modes_system_id_index` (`system_id`);

--
-- Indexes for table `hindustansystem_permissions`
--
ALTER TABLE `hindustansystem_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `hindustansystem_petty_cash_accounts`
--
ALTER TABLE `hindustansystem_petty_cash_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_petty_cash_accounts_system_id_project_id_unique` (`system_id`,`project_id`),
  ADD KEY `hindustansystem_petty_cash_accounts_project_id_foreign` (`project_id`),
  ADD KEY `pca_ledger_fk` (`ledger_account_id`);

--
-- Indexes for table `hindustansystem_petty_cash_boxes`
--
ALTER TABLE `hindustansystem_petty_cash_boxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `petty_cash_boxes_box_code_unique` (`box_code`);

--
-- Indexes for table `hindustansystem_petty_cash_entries`
--
ALTER TABLE `hindustansystem_petty_cash_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_petty_cash_entries_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_petty_cash_entries_voucher_id_foreign` (`voucher_id`),
  ADD KEY `pce_pca_fk` (`petty_cash_account_id`);

--
-- Indexes for table `hindustansystem_petty_cash_transactions`
--
ALTER TABLE `hindustansystem_petty_cash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pct_voucher` (`voucher_id`),
  ADD KEY `fk_pct_created_by` (`created_by`),
  ADD KEY `idx_pct_box_date` (`petty_cash_box_id`,`transaction_date`),
  ADD KEY `fk_hindustansystem_petty_cash_transactions_category_id` (`category_id`);

--
-- Indexes for table `hindustansystem_projects`
--
ALTER TABLE `hindustansystem_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_projects_system_id_code_unique` (`system_id`,`code`);

--
-- Indexes for table `hindustansystem_ra_bills`
--
ALTER TABLE `hindustansystem_ra_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ra_bills_ra_bill_number_unique` (`ra_bill_number`),
  ADD KEY `ra_bills_system_id_index` (`system_id`),
  ADD KEY `ra_bills_contractor_id_index` (`contractor_id`),
  ADD KEY `ra_bills_project_id_index` (`project_id`),
  ADD KEY `ra_bills_status_index` (`status`);

--
-- Indexes for table `hindustansystem_ra_bill_payments`
--
ALTER TABLE `hindustansystem_ra_bill_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ra_bill_payments_system_id_index` (`system_id`),
  ADD KEY `ra_bill_payments_ra_bill_id_index` (`ra_bill_id`);

--
-- Indexes for table `hindustansystem_receipts`
--
ALTER TABLE `hindustansystem_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_receipts_sale_id_foreign` (`sale_id`),
  ADD KEY `hindustansystem_receipts_customer_id_foreign` (`customer_id`),
  ADD KEY `hindustansystem_receipts_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_receipts_unit_id_foreign` (`unit_id`),
  ADD KEY `hindustansystem_receipts_created_by_foreign` (`created_by`),
  ADD KEY `hindustansystem_receipts_partner_id_foreign` (`partner_id`),
  ADD KEY `hindustansystem_receipts_bank_id_foreign` (`bank_id`),
  ADD KEY `receipts_company_bank_account_id_foreign` (`company_bank_account_id`),
  ADD KEY `receipts_realized_by_foreign` (`realized_by`);

--
-- Indexes for table `hindustansystem_receipt_realization_logs`
--
ALTER TABLE `hindustansystem_receipt_realization_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_realization_logs_changed_by_foreign` (`changed_by`),
  ADD KEY `rec_real_logs_idx` (`receipt_id`,`created_at`);

--
-- Indexes for table `hindustansystem_receipt_stores`
--
ALTER TABLE `hindustansystem_receipt_stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hindustansystem_replenishment_requests`
--
ALTER TABLE `hindustansystem_replenishment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_replenishment_requests_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_replenishment_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `hindustansystem_replenishment_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `hindustansystem_replenishment_requests_voucher_id_foreign` (`voucher_id`),
  ADD KEY `rr_pca_fk` (`petty_cash_account_id`);

--
-- Indexes for table `hindustansystem_roles`
--
ALTER TABLE `hindustansystem_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `hindustansystem_role_has_permissions`
--
ALTER TABLE `hindustansystem_role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `hindustansystem_role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `hindustansystem_sales`
--
ALTER TABLE `hindustansystem_sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_sales_sale_number_unique` (`sale_number`),
  ADD KEY `hindustansystem_sales_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_sales_unit_id_foreign` (`unit_id`),
  ADD KEY `hindustansystem_sales_customer_id_foreign` (`customer_id`),
  ADD KEY `hindustansystem_sales_broker_id_foreign` (`broker_id`),
  ADD KEY `hindustansystem_sales_original_sale_id_foreign` (`original_sale_id`),
  ADD KEY `hindustansystem_sales_created_by_foreign` (`created_by`),
  ADD KEY `hindustansystem_sales_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `hindustansystem_sales_executives`
--
ALTER TABLE `hindustansystem_sales_executives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_sales_executives_email_unique` (`email`);

--
-- Indexes for table `hindustansystem_sale_extra_works`
--
ALTER TABLE `hindustansystem_sale_extra_works`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_sale_extra_works_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `hindustansystem_sale_status_logs`
--
ALTER TABLE `hindustansystem_sale_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_sale_status_logs_sale_id_foreign` (`sale_id`),
  ADD KEY `hindustansystem_sale_status_logs_performed_by_foreign` (`performed_by`);

--
-- Indexes for table `hindustansystem_sale_units`
--
ALTER TABLE `hindustansystem_sale_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_sale_units_sale_id_foreign` (`sale_id`),
  ADD KEY `hindustansystem_sale_units_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `hindustansystem_sessions`
--
ALTER TABLE `hindustansystem_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_sessions_user_id_index` (`user_id`),
  ADD KEY `hindustansystem_sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `hindustansystem_site_expenses`
--
ALTER TABLE `hindustansystem_site_expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_site_expenses_voucher_number_unique` (`voucher_number`),
  ADD KEY `hindustansystem_site_expenses_project_id_foreign` (`project_id`),
  ADD KEY `hindustansystem_site_expenses_floor_id_foreign` (`floor_id`),
  ADD KEY `hindustansystem_site_expenses_payee_id_foreign` (`payee_id`),
  ADD KEY `hindustansystem_site_expenses_chart_of_account_id_foreign` (`chart_of_account_id`),
  ADD KEY `hindustansystem_site_expenses_company_bank_account_id_foreign` (`company_bank_account_id`),
  ADD KEY `hindustansystem_site_expenses_loan_id_foreign` (`loan_id`),
  ADD KEY `hindustansystem_site_expenses_created_by_foreign` (`created_by`),
  ADD KEY `hindustansystem_site_expenses_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `hindustansystem_site_expense_categories`
--
ALTER TABLE `hindustansystem_site_expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hindustansystem_site_expense_categories_project_id` (`project_id`),
  ADD KEY `site_expense_categories_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `hindustansystem_site_expense_payments`
--
ALTER TABLE `hindustansystem_site_expense_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sep_expense_idx` (`site_expense_id`),
  ADD KEY `sep_bank_idx` (`company_bank_account_id`),
  ADD KEY `sep_loan_idx` (`loan_id`);

--
-- Indexes for table `hindustansystem_systems`
--
ALTER TABLE `hindustansystem_systems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_systems_code_unique` (`code`);

--
-- Indexes for table `hindustansystem_unit_rate_logs`
--
ALTER TABLE `hindustansystem_unit_rate_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_unit_rate_logs_unit_id_foreign` (`unit_id`),
  ADD KEY `hindustansystem_unit_rate_logs_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `hindustansystem_unit_status_logs`
--
ALTER TABLE `hindustansystem_unit_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_unit_status_logs_unit_id_foreign` (`unit_id`),
  ADD KEY `hindustansystem_unit_status_logs_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `hindustansystem_unit_types`
--
ALTER TABLE `hindustansystem_unit_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_unit_types_project_id_foreign` (`project_id`);

--
-- Indexes for table `hindustansystem_users`
--
ALTER TABLE `hindustansystem_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_users_email_unique` (`email`),
  ADD UNIQUE KEY `hindustansystem_users_employee_code_unique` (`employee_code`),
  ADD KEY `hindustansystem_users_system_id_foreign` (`system_id`);

--
-- Indexes for table `hindustansystem_user_dashboard_layouts`
--
ALTER TABLE `hindustansystem_user_dashboard_layouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_user_dashboard_layouts_user_id_foreign` (`user_id`);

--
-- Indexes for table `hindustansystem_vendors`
--
ALTER TABLE `hindustansystem_vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_vendors_vendor_code_unique` (`vendor_code`),
  ADD KEY `hindustansystem_vendors_linked_account_id_foreign` (`linked_account_id`);

--
-- Indexes for table `hindustansystem_vouchers`
--
ALTER TABLE `hindustansystem_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_vouchers_system_id_voucher_number_unique` (`system_id`,`voucher_number`),
  ADD KEY `hindustansystem_vouchers_created_by_foreign` (`created_by`),
  ADD KEY `hindustansystem_vouchers_edited_by_foreign` (`edited_by`),
  ADD KEY `hindustansystem_vouchers_reversal_of_id_foreign` (`reversal_of_id`),
  ADD KEY `fk_hindustansystem_vouchers_company_bank_account_id` (`company_bank_account_id`);

--
-- Indexes for table `hindustansystem_voucher_lines`
--
ALTER TABLE `hindustansystem_voucher_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_voucher_lines_voucher_id_foreign` (`voucher_id`),
  ADD KEY `hindustansystem_voucher_lines_account_id_foreign` (`account_id`);

--
-- Indexes for table `hindustansystem_voucher_types`
--
ALTER TABLE `hindustansystem_voucher_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_types_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hindustansystem_accounting_settings`
--
ALTER TABLE `hindustansystem_accounting_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_accounts`
--
ALTER TABLE `hindustansystem_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hindustansystem_activity_logs`
--
ALTER TABLE `hindustansystem_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_approvals`
--
ALTER TABLE `hindustansystem_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_approval_requests`
--
ALTER TABLE `hindustansystem_approval_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_approval_rules`
--
ALTER TABLE `hindustansystem_approval_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_banks`
--
ALTER TABLE `hindustansystem_banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `hindustansystem_bills`
--
ALTER TABLE `hindustansystem_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_bill_payments`
--
ALTER TABLE `hindustansystem_bill_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_bookings`
--
ALTER TABLE `hindustansystem_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_brokerages`
--
ALTER TABLE `hindustansystem_brokerages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_brokers`
--
ALTER TABLE `hindustansystem_brokers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_categories`
--
ALTER TABLE `hindustansystem_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `hindustansystem_chart_of_accounts`
--
ALTER TABLE `hindustansystem_chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `hindustansystem_cheque_statuses`
--
ALTER TABLE `hindustansystem_cheque_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hindustansystem_collection_reminders`
--
ALTER TABLE `hindustansystem_collection_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_commission_entries`
--
ALTER TABLE `hindustansystem_commission_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_company_bank_accounts`
--
ALTER TABLE `hindustansystem_company_bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_customers`
--
ALTER TABLE `hindustansystem_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_customer_installments`
--
ALTER TABLE `hindustansystem_customer_installments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `hindustansystem_deals`
--
ALTER TABLE `hindustansystem_deals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_dms_categories`
--
ALTER TABLE `hindustansystem_dms_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hindustansystem_dms_documents`
--
ALTER TABLE `hindustansystem_dms_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_dms_document_types`
--
ALTER TABLE `hindustansystem_dms_document_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `hindustansystem_emi_reschedule_logs`
--
ALTER TABLE `hindustansystem_emi_reschedule_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_emi_schedules`
--
ALTER TABLE `hindustansystem_emi_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_employees`
--
ALTER TABLE `hindustansystem_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_engineers`
--
ALTER TABLE `hindustansystem_engineers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_failed_jobs`
--
ALTER TABLE `hindustansystem_failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_floors`
--
ALTER TABLE `hindustansystem_floors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `hindustansystem_hindustan_units`
--
ALTER TABLE `hindustansystem_hindustan_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `hindustansystem_jobs`
--
ALTER TABLE `hindustansystem_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_journal_entries`
--
ALTER TABLE `hindustansystem_journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `hindustansystem_journal_vouchers`
--
ALTER TABLE `hindustansystem_journal_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hindustansystem_ledger_entries`
--
ALTER TABLE `hindustansystem_ledger_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hindustansystem_loans`
--
ALTER TABLE `hindustansystem_loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_loan_disbursals`
--
ALTER TABLE `hindustansystem_loan_disbursals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_loan_interest_logs`
--
ALTER TABLE `hindustansystem_loan_interest_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hindustansystem_loan_prepayments`
--
ALTER TABLE `hindustansystem_loan_prepayments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_migrations`
--
ALTER TABLE `hindustansystem_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `hindustansystem_partner_allocations`
--
ALTER TABLE `hindustansystem_partner_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_partner_contributions`
--
ALTER TABLE `hindustansystem_partner_contributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_partner_shares`
--
ALTER TABLE `hindustansystem_partner_shares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_payees`
--
ALTER TABLE `hindustansystem_payees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `hindustansystem_payments`
--
ALTER TABLE `hindustansystem_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_payment_modes`
--
ALTER TABLE `hindustansystem_payment_modes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hindustansystem_permissions`
--
ALTER TABLE `hindustansystem_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hindustansystem_petty_cash_accounts`
--
ALTER TABLE `hindustansystem_petty_cash_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_petty_cash_boxes`
--
ALTER TABLE `hindustansystem_petty_cash_boxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_petty_cash_entries`
--
ALTER TABLE `hindustansystem_petty_cash_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_petty_cash_transactions`
--
ALTER TABLE `hindustansystem_petty_cash_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_projects`
--
ALTER TABLE `hindustansystem_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_ra_bills`
--
ALTER TABLE `hindustansystem_ra_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_ra_bill_payments`
--
ALTER TABLE `hindustansystem_ra_bill_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_receipts`
--
ALTER TABLE `hindustansystem_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hindustansystem_receipt_realization_logs`
--
ALTER TABLE `hindustansystem_receipt_realization_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hindustansystem_receipt_stores`
--
ALTER TABLE `hindustansystem_receipt_stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hindustansystem_replenishment_requests`
--
ALTER TABLE `hindustansystem_replenishment_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_roles`
--
ALTER TABLE `hindustansystem_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_sales`
--
ALTER TABLE `hindustansystem_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_sales_executives`
--
ALTER TABLE `hindustansystem_sales_executives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_sale_extra_works`
--
ALTER TABLE `hindustansystem_sale_extra_works`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_sale_status_logs`
--
ALTER TABLE `hindustansystem_sale_status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hindustansystem_sale_units`
--
ALTER TABLE `hindustansystem_sale_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hindustansystem_site_expenses`
--
ALTER TABLE `hindustansystem_site_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_site_expense_categories`
--
ALTER TABLE `hindustansystem_site_expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hindustansystem_site_expense_payments`
--
ALTER TABLE `hindustansystem_site_expense_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_systems`
--
ALTER TABLE `hindustansystem_systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_unit_rate_logs`
--
ALTER TABLE `hindustansystem_unit_rate_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `hindustansystem_unit_status_logs`
--
ALTER TABLE `hindustansystem_unit_status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `hindustansystem_unit_types`
--
ALTER TABLE `hindustansystem_unit_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hindustansystem_users`
--
ALTER TABLE `hindustansystem_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hindustansystem_user_dashboard_layouts`
--
ALTER TABLE `hindustansystem_user_dashboard_layouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_vendors`
--
ALTER TABLE `hindustansystem_vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hindustansystem_vouchers`
--
ALTER TABLE `hindustansystem_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hindustansystem_voucher_lines`
--
ALTER TABLE `hindustansystem_voucher_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hindustansystem_voucher_types`
--
ALTER TABLE `hindustansystem_voucher_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- Constraints for table `hindustansystem_engineers`
--
ALTER TABLE `hindustansystem_engineers`
  ADD CONSTRAINT `engineers_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE SET NULL;

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
