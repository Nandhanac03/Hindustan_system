-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 06, 2026 at 10:20 AM
-- Server version: 5.7.36
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

CREATE TABLE `hindustansystem_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_accounts`
--

INSERT INTO `hindustansystem_accounts` (`id`, `system_id`, `code`, `name`, `type`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'BRK-ACC-01', 'Broker Commissions Payable', 'liability', NULL, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(2, 1, 'PRT-ACC-01', 'Aditya Roy Capital', 'liability', NULL, 1, '2026-07-06 03:53:49', '2026-07-06 03:53:49'),
(3, 1, 'PRT-ACC-02', 'Divya Sharma Capital', 'liability', NULL, 1, '2026-07-06 03:53:49', '2026-07-06 03:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_activity_logs`
--

CREATE TABLE `hindustansystem_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_activity_logs`
--

INSERT INTO `hindustansystem_activity_logs` (`id`, `user_id`, `system_id`, `action`, `subject_type`, `subject_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, NULL, 'System Booted', NULL, NULL, 'System initialized and default seed data populated.', '127.0.0.1', 'Symfony', '2026-07-06 03:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approvals`
--

CREATE TABLE `hindustansystem_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approvable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
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
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `requester_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_approval_rules`
--

CREATE TABLE `hindustansystem_approval_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `threshold_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_approval_rules`
--

INSERT INTO `hindustansystem_approval_rules` (`id`, `module`, `min_role`, `threshold_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'discount', 'Owner', 100000.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_bills`
--

CREATE TABLE `hindustansystem_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `payee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `bill_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_amount` decimal(15,2) NOT NULL,
  `final_amount` decimal(15,2) NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_approval',
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
  `booking_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `sales_executive_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agreement_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `broker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_rate_per_sqft` decimal(15,2) DEFAULT NULL,
  `gst_behavior` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `gst_amount` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_bookings`
--

INSERT INTO `hindustansystem_bookings` (`id`, `booking_number`, `customer_id`, `project_id`, `unit_id`, `sales_executive_id`, `amount`, `status`, `created_at`, `updated_at`, `agreement_date`, `registration_date`, `broker_id`, `sale_rate_per_sqft`, `gst_behavior`, `gst_amount`) VALUES
(1, 'BK-0001', 1, 1, 1, 1, 5000000.00, 'approved', '2026-07-06 03:53:48', '2026-07-06 03:53:48', NULL, NULL, NULL, NULL, 'none', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_brokers`
--

CREATE TABLE `hindustansystem_brokers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_commission_pct` decimal(5,2) NOT NULL,
  `linked_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_brokers`
--

INSERT INTO `hindustansystem_brokers` (`id`, `system_id`, `name`, `default_commission_pct`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Apex Realty Brokers', 2.50, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(2, 1, 'Metro Homes Agents', 1.75, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache`
--

CREATE TABLE `hindustansystem_cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_cache`
--

INSERT INTO `hindustansystem_cache` (`key`, `value`, `expiration`) VALUES
('hindustanerp-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:13:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"vouchers.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"expenses.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"expenses.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"collections.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"sales.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"sales.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:22:\"sales.discount.request\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"units.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"units.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"projects.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"projects.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:17:\"units.rate.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Owner\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"Sales\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"Site\";s:1:\"c\";s:3:\"web\";}}}', 1783416589);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_cache_locks`
--

CREATE TABLE `hindustansystem_cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint(20) NOT NULL
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
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Accrued',
  `triggered_at` timestamp NULL DEFAULT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_customers`
--

CREATE TABLE `hindustansystem_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_customers`
--

INSERT INTO `hindustansystem_customers` (`id`, `name`, `email`, `phone`, `avatar_url`, `created_at`, `updated_at`) VALUES
(1, 'Vijay Malhotra', 'vijay@gmail.com', '+91 98765 43212', 'VM', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(2, 'Neha Kapoor', 'neha@gmail.com', '+91 98765 43213', 'NK', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(3, 'Rajesh Gupta', 'rajesh@gmail.com', '+91 98765 43214', 'RG', '2026-07-06 03:53:42', '2026-07-06 03:53:42');

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
  `trigger_condition` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_collection',
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
  `principal_component` decimal(15,2) NOT NULL,
  `interest_component` decimal(15,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Due',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_failed_jobs`
--

CREATE TABLE `hindustansystem_failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_floors`
--

CREATE TABLE `hindustansystem_floors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `floor_number` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(6, 2, 1, 'Floor 1', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(7, 2, 2, 'Floor 2', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(8, 2, 3, 'Floor 3', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(9, 2, 4, 'Floor 4', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(10, 2, 5, 'Floor 5', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(11, 3, 1, 'Floor 1', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(12, 3, 2, 'Floor 2', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(13, 3, 3, 'Floor 3', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(14, 3, 4, 'Floor 4', '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(15, 3, 5, 'Floor 5', '2026-07-06 03:53:42', '2026-07-06 03:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_jobs`
--

CREATE TABLE `hindustansystem_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `running_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_loans`
--

CREATE TABLE `hindustansystem_loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `lender_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `tenure_months` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `schedule_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outstanding_balance` decimal(15,2) NOT NULL,
  `ledger_account_id` bigint(20) UNSIGNED NOT NULL,
  `interest_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_migrations`
--

CREATE TABLE `hindustansystem_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(9, '2026_07_06_143821_add_gst_and_broker_fields_to_bookings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_permissions`
--

CREATE TABLE `hindustansystem_model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_model_has_roles`
--

CREATE TABLE `hindustansystem_model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `allocated_amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
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
(1, 1, 1, 1, 60.00, '2026-07-06 03:53:49', '2026-07-06 03:53:49'),
(2, 1, 2, 1, 40.00, '2026-07-06 03:53:49', '2026-07-06 03:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_password_reset_tokens`
--

CREATE TABLE `hindustansystem_password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payees`
--

CREATE TABLE `hindustansystem_payees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `linked_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payees`
--

INSERT INTO `hindustansystem_payees` (`id`, `system_id`, `type`, `name`, `linked_account_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Partner', 'Aditya Roy', 2, '2026-07-06 03:53:49', '2026-07-06 03:53:49'),
(2, 1, 'Partner', 'Divya Sharma', 3, '2026-07-06 03:53:49', '2026-07-06 03:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_payments`
--

CREATE TABLE `hindustansystem_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_payments`
--

INSERT INTO `hindustansystem_payments` (`id`, `receipt_number`, `customer_id`, `project_id`, `booking_id`, `amount`, `payment_mode`, `status`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 'REC-00001', 1, 1, 1, 2000000.00, 'Bank Transfer', 'completed', '2026-07-06 03:53:48', '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_permissions`
--

CREATE TABLE `hindustansystem_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ledger_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_petty_cash_entries`
--

CREATE TABLE `hindustansystem_petty_cash_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `petty_cash_account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_projects`
--

CREATE TABLE `hindustansystem_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_or_emirate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rera_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_floors` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_projects`
--

INSERT INTO `hindustansystem_projects` (`id`, `system_id`, `name`, `code`, `location`, `city`, `state_or_emirate`, `country`, `rera_number`, `total_floors`, `start_date`, `expected_completion_date`, `status`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hindustan Emerald Valley', 'HEV-01', 'Sector 62', 'Noida', 'Uttar Pradesh', 'India', NULL, 5, NULL, NULL, 'ongoing', NULL, 1, '2026-07-06 03:53:41', '2026-07-06 03:53:41'),
(2, 1, 'Hindustan Sapphire Heights', 'HSH-01', 'Sector 150', 'Noida', 'Uttar Pradesh', 'India', NULL, 5, NULL, NULL, 'completed', NULL, 1, '2026-07-06 03:53:41', '2026-07-06 03:53:41'),
(3, 2, 'Hindustan Grand Plaza', 'HGP-01', 'Dubai Marina', 'Dubai', 'Dubai', 'UAE', NULL, 5, NULL, NULL, 'ongoing', NULL, 1, '2026-07-06 03:53:41', '2026-07-06 03:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_replenishment_requests`
--

CREATE TABLE `hindustansystem_replenishment_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `petty_cash_account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `hindustansystem_sales_executives`
--

CREATE TABLE `hindustansystem_sales_executives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sales_executives`
--

INSERT INTO `hindustansystem_sales_executives` (`id`, `name`, `email`, `avatar_url`, `created_at`, `updated_at`) VALUES
(1, 'Vikram Sharma', 'vikram@hindustan.com', 'VS', '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(2, 'Priya Nair', 'priya@hindustan.com', 'PN', '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_sessions`
--

CREATE TABLE `hindustansystem_sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_sessions`
--

INSERT INTO `hindustansystem_sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bEKvqAleNdKgMNqZJf7ZOX4sRIdRM1n1LQDKR2o5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'eyJfdG9rZW4iOiJoSFZ1NzlRR1lpR09iTjNkT0pxakFuSUlFdWVHelpwTTFycWM2STB5IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9IaW5kdXN0YW5fc3lzdGVtXC9wdWJsaWNcL3VuaXRzIiwicm91dGUiOiJ1bml0cy5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1783331903);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_systems`
--

CREATE TABLE `hindustansystem_systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `vat_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `timezone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
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
-- Table structure for table `hindustansystem_units`
--

CREATE TABLE `hindustansystem_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `floor_id` bigint(20) UNSIGNED NOT NULL,
  `unit_type_id` bigint(20) UNSIGNED NOT NULL,
  `unit_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bua_area` decimal(10,2) NOT NULL,
  `carpet_area` decimal(10,2) DEFAULT NULL,
  `area_unit` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sqft',
  `facing` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `base_rate` decimal(15,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_units`
--

INSERT INTO `hindustansystem_units` (`id`, `project_id`, `floor_id`, `unit_type_id`, `unit_number`, `bua_area`, `carpet_area`, `area_unit`, `facing`, `status`, `base_rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'U-101', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(2, 1, 1, 1, 'U-102', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(3, 1, 1, 1, 'U-103', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(4, 1, 1, 1, 'U-104', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:42', '2026-07-06 03:53:42'),
(5, 1, 2, 1, 'U-201', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(6, 1, 2, 1, 'U-202', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(7, 1, 2, 1, 'U-203', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(8, 1, 2, 1, 'U-204', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(9, 1, 3, 1, 'U-301', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(10, 1, 3, 1, 'U-302', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(11, 1, 3, 1, 'U-303', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(12, 1, 3, 1, 'U-304', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(13, 1, 4, 1, 'U-401', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(14, 1, 4, 1, 'U-402', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:43', '2026-07-06 03:53:43'),
(15, 1, 4, 1, 'U-403', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(16, 1, 4, 1, 'U-404', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(17, 1, 5, 1, 'U-501', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(18, 1, 5, 1, 'U-502', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(19, 1, 5, 1, 'U-503', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(20, 1, 5, 1, 'U-504', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(21, 2, 6, 1, 'U-101', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(22, 2, 6, 1, 'U-102', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(23, 2, 6, 1, 'U-103', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:44', '2026-07-06 03:53:44'),
(24, 2, 6, 1, 'U-104', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(25, 2, 7, 1, 'U-201', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(26, 2, 7, 1, 'U-202', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(27, 2, 7, 1, 'U-203', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(28, 2, 7, 1, 'U-204', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(29, 2, 8, 1, 'U-301', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(30, 2, 8, 1, 'U-302', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(31, 2, 8, 1, 'U-303', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(32, 2, 8, 1, 'U-304', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(33, 2, 9, 1, 'U-401', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:45', '2026-07-06 03:53:45'),
(34, 2, 9, 1, 'U-402', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(35, 2, 9, 1, 'U-403', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(36, 2, 9, 1, 'U-404', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(37, 2, 10, 1, 'U-501', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(38, 2, 10, 1, 'U-502', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(39, 2, 10, 1, 'U-503', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(40, 2, 10, 1, 'U-504', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(41, 3, 11, 1, 'U-101', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(42, 3, 11, 1, 'U-102', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(43, 3, 11, 1, 'U-103', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(44, 3, 11, 1, 'U-104', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:46', '2026-07-06 03:53:46'),
(45, 3, 12, 1, 'U-201', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(46, 3, 12, 1, 'U-202', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(47, 3, 12, 1, 'U-203', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(48, 3, 12, 1, 'U-204', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(49, 3, 13, 1, 'U-301', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(50, 3, 13, 1, 'U-302', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(51, 3, 13, 1, 'U-303', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(52, 3, 13, 1, 'U-304', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(53, 3, 14, 1, 'U-401', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(54, 3, 14, 1, 'U-402', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:47', '2026-07-06 03:53:47'),
(55, 3, 14, 1, 'U-403', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(56, 3, 14, 1, 'U-404', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(57, 3, 15, 1, 'U-501', 1200.00, 1000.00, 'sqft', 'East', 'sold', 4500.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(58, 3, 15, 1, 'U-502', 1200.00, 1000.00, 'sqft', 'East', 'booked', 4500.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(59, 3, 15, 1, 'U-503', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48'),
(60, 3, 15, 1, 'U-504', 1200.00, 1000.00, 'sqft', 'East', 'available', 4500.00, 1, '2026-07-06 03:53:48', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_rate_logs`
--

CREATE TABLE `hindustansystem_unit_rate_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `effective_from` date NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_rate_logs`
--

INSERT INTO `hindustansystem_unit_rate_logs` (`id`, `unit_id`, `rate`, `effective_from`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:42'),
(2, 2, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:42'),
(3, 3, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:42'),
(4, 4, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(5, 5, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(6, 6, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(7, 7, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(8, 8, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(9, 9, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(10, 10, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(11, 11, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(12, 12, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(13, 13, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(14, 14, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(15, 15, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(16, 16, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(17, 17, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(18, 18, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(19, 19, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(20, 20, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(21, 21, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(22, 22, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(23, 23, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(24, 24, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(25, 25, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(26, 26, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(27, 27, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(28, 28, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(29, 29, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(30, 30, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(31, 31, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(32, 32, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(33, 33, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(34, 34, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(35, 35, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(36, 36, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(37, 37, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(38, 38, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(39, 39, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(40, 40, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(41, 41, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(42, 42, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(43, 43, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(44, 44, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(45, 45, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(46, 46, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(47, 47, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(48, 48, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(49, 49, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(50, 50, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(51, 51, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(52, 52, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(53, 53, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(54, 54, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(55, 55, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(56, 56, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(57, 57, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(58, 58, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(59, 59, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(60, 60, 4500.00, '2026-07-06', 1, 'Initial seeding', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_status_logs`
--

CREATE TABLE `hindustansystem_unit_status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_status_logs`
--

INSERT INTO `hindustansystem_unit_status_logs` (`id`, `unit_id`, `from_status`, `to_status`, `changed_by`, `reason`, `created_at`) VALUES
(1, 1, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:42'),
(2, 2, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:42'),
(3, 3, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:42'),
(4, 4, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(5, 5, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(6, 6, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(7, 7, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(8, 8, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(9, 9, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(10, 10, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(11, 11, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(12, 12, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(13, 13, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:43'),
(14, 14, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(15, 15, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(16, 16, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(17, 17, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(18, 18, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(19, 19, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(20, 20, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(21, 21, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(22, 22, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:44'),
(23, 23, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(24, 24, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(25, 25, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(26, 26, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(27, 27, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(28, 28, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(29, 29, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(30, 30, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(31, 31, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(32, 32, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:45'),
(33, 33, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(34, 34, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(35, 35, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(36, 36, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(37, 37, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(38, 38, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(39, 39, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(40, 40, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(41, 41, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(42, 42, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(43, 43, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:46'),
(44, 44, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(45, 45, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(46, 46, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(47, 47, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(48, 48, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(49, 49, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(50, 50, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(51, 51, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(52, 52, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(53, 53, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:47'),
(54, 54, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(55, 55, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(56, 56, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(57, 57, NULL, 'sold', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(58, 58, NULL, 'booked', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(59, 59, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:48'),
(60, 60, NULL, 'available', 1, 'Initial seeding', '2026-07-06 03:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_unit_types`
--

CREATE TABLE `hindustansystem_unit_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_unit_types`
--

INSERT INTO `hindustansystem_unit_types` (`id`, `name`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Flat', 'residential', 1, '2026-07-06 03:53:39', '2026-07-06 03:53:39'),
(2, 'Shop', 'commercial', 1, '2026-07-06 03:53:39', '2026-07-06 03:53:39'),
(3, 'Office', 'commercial', 1, '2026-07-06 03:53:39', '2026-07-06 03:53:39'),
(4, 'Villa', 'residential', 1, '2026-07-06 03:53:39', '2026-07-06 03:53:39'),
(5, 'Parking', 'parking', 1, '2026-07-06 03:53:39', '2026-07-06 03:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_users`
--

CREATE TABLE `hindustansystem_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hindustansystem_users`
--

INSERT INTO `hindustansystem_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `system_id`, `phone`, `employee_code`, `status`, `must_change_password`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Owner', 'owner@hindustan.com', '2026-07-06 03:53:39', '$2y$12$q1CE0lNCVgHBiMgVS2WQyeWdk0VYl/OyTRq9GEQrxj0tWuulWSCoq', NULL, '2026-07-06 03:53:39', '2026-07-06 03:53:39', 1, '+91 99999 99999', 'EMP-001', 'active', 0, NULL, NULL),
(2, 'Rajesh Accountant (IN)', 'accountant.in@hindustan.com', '2026-07-06 03:53:40', '$2y$12$rVofIQZI2tIVy/jHVFLVPOyEJC2tVyvtMlUSsiLb.mLVUQRVh/W6m', NULL, '2026-07-06 03:53:40', '2026-07-06 03:53:40', 1, '+91 98765 00001', 'EMP-IN-ACC01', 'active', 0, NULL, NULL),
(3, 'Omar Accountant (UAE)', 'accountant.ae@hindustan.com', '2026-07-06 03:53:40', '$2y$12$nhCUlYVdehjhoRDNzzde9.stFLNk3mZ/j0du.HsDo9mMygrk2oi5y', NULL, '2026-07-06 03:53:40', '2026-07-06 03:53:40', 2, '+971 50 123 4567', 'EMP-AE-ACC01', 'active', 0, NULL, NULL),
(4, 'Vikram Sales (IN)', 'sales.in@hindustan.com', '2026-07-06 03:53:41', '$2y$12$1AO5aTilUgSLi2SM1tPv4uYtmKrQH1a7/Dr6n7rLEM1X4nil2G2HO', NULL, '2026-07-06 03:53:41', '2026-07-06 03:53:41', 1, '+91 98765 00002', 'EMP-IN-SAL01', 'active', 0, NULL, NULL),
(5, 'Amit Site (IN)', 'site.in@hindustan.com', '2026-07-06 03:53:41', '$2y$12$juyU2BYL8M9a3OyCdSiE.e/1wV6hv4dbJdsRgRyPPOTk9atNE45cy', NULL, '2026-07-06 03:53:41', '2026-07-06 03:53:41', 1, '+91 98765 00003', 'EMP-IN-SIT01', 'active', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_user_dashboard_layouts`
--

CREATE TABLE `hindustansystem_user_dashboard_layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `layout_settings` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_vouchers`
--

CREATE TABLE `hindustansystem_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `edited_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `reversal_of_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hindustansystem_voucher_lines`
--

CREATE TABLE `hindustansystem_voucher_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `line_narration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `hindustansystem_commission_entries`
--
ALTER TABLE `hindustansystem_commission_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_commission_entries_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_commission_entries_voucher_id_foreign` (`voucher_id`),
  ADD KEY `ce_deal_fk` (`deal_id`);

--
-- Indexes for table `hindustansystem_customers`
--
ALTER TABLE `hindustansystem_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_customers_email_unique` (`email`);

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
-- Indexes for table `hindustansystem_emi_schedules`
--
ALTER TABLE `hindustansystem_emi_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_emi_schedules_system_id_foreign` (`system_id`),
  ADD KEY `es_loan_fk` (`loan_id`);

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
  ADD KEY `pa_partner_fk` (`partner_id`);

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
-- Indexes for table `hindustansystem_petty_cash_entries`
--
ALTER TABLE `hindustansystem_petty_cash_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_petty_cash_entries_system_id_foreign` (`system_id`),
  ADD KEY `hindustansystem_petty_cash_entries_voucher_id_foreign` (`voucher_id`),
  ADD KEY `pce_pca_fk` (`petty_cash_account_id`);

--
-- Indexes for table `hindustansystem_projects`
--
ALTER TABLE `hindustansystem_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_projects_system_id_code_unique` (`system_id`,`code`);

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
-- Indexes for table `hindustansystem_sales_executives`
--
ALTER TABLE `hindustansystem_sales_executives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_sales_executives_email_unique` (`email`);

--
-- Indexes for table `hindustansystem_sessions`
--
ALTER TABLE `hindustansystem_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_sessions_user_id_index` (`user_id`),
  ADD KEY `hindustansystem_sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `hindustansystem_systems`
--
ALTER TABLE `hindustansystem_systems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_systems_code_unique` (`code`);

--
-- Indexes for table `hindustansystem_units`
--
ALTER TABLE `hindustansystem_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_units_project_id_unit_number_unique` (`project_id`,`unit_number`),
  ADD KEY `hindustansystem_units_floor_id_foreign` (`floor_id`),
  ADD KEY `hindustansystem_units_unit_type_id_foreign` (`unit_type_id`);

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `hindustansystem_vouchers`
--
ALTER TABLE `hindustansystem_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hindustansystem_vouchers_system_id_voucher_number_unique` (`system_id`,`voucher_number`),
  ADD KEY `hindustansystem_vouchers_created_by_foreign` (`created_by`),
  ADD KEY `hindustansystem_vouchers_edited_by_foreign` (`edited_by`),
  ADD KEY `hindustansystem_vouchers_reversal_of_id_foreign` (`reversal_of_id`);

--
-- Indexes for table `hindustansystem_voucher_lines`
--
ALTER TABLE `hindustansystem_voucher_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hindustansystem_voucher_lines_voucher_id_foreign` (`voucher_id`),
  ADD KEY `hindustansystem_voucher_lines_account_id_foreign` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hindustansystem_accounts`
--
ALTER TABLE `hindustansystem_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_activity_logs`
--
ALTER TABLE `hindustansystem_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `hindustansystem_bills`
--
ALTER TABLE `hindustansystem_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_bill_payments`
--
ALTER TABLE `hindustansystem_bill_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_bookings`
--
ALTER TABLE `hindustansystem_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hindustansystem_brokers`
--
ALTER TABLE `hindustansystem_brokers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_commission_entries`
--
ALTER TABLE `hindustansystem_commission_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_customers`
--
ALTER TABLE `hindustansystem_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hindustansystem_deals`
--
ALTER TABLE `hindustansystem_deals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_emi_schedules`
--
ALTER TABLE `hindustansystem_emi_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_failed_jobs`
--
ALTER TABLE `hindustansystem_failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_floors`
--
ALTER TABLE `hindustansystem_floors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hindustansystem_jobs`
--
ALTER TABLE `hindustansystem_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_ledger_entries`
--
ALTER TABLE `hindustansystem_ledger_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_loans`
--
ALTER TABLE `hindustansystem_loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_migrations`
--
ALTER TABLE `hindustansystem_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hindustansystem_partner_allocations`
--
ALTER TABLE `hindustansystem_partner_allocations`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_payments`
--
ALTER TABLE `hindustansystem_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `hindustansystem_petty_cash_entries`
--
ALTER TABLE `hindustansystem_petty_cash_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_projects`
--
ALTER TABLE `hindustansystem_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `hindustansystem_sales_executives`
--
ALTER TABLE `hindustansystem_sales_executives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_systems`
--
ALTER TABLE `hindustansystem_systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hindustansystem_units`
--
ALTER TABLE `hindustansystem_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `hindustansystem_unit_rate_logs`
--
ALTER TABLE `hindustansystem_unit_rate_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `hindustansystem_unit_status_logs`
--
ALTER TABLE `hindustansystem_unit_status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `hindustansystem_unit_types`
--
ALTER TABLE `hindustansystem_unit_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `hindustansystem_vouchers`
--
ALTER TABLE `hindustansystem_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hindustansystem_voucher_lines`
--
ALTER TABLE `hindustansystem_voucher_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `hindustansystem_bookings_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_units` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `hindustansystem_deals`
--
ALTER TABLE `hindustansystem_deals`
  ADD CONSTRAINT `hindustansystem_deals_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `hindustansystem_bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_deals_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `hindustansystem_brokers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_deals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_deals_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_emi_schedules`
--
ALTER TABLE `hindustansystem_emi_schedules`
  ADD CONSTRAINT `es_loan_fk` FOREIGN KEY (`loan_id`) REFERENCES `hindustansystem_loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hindustansystem_emi_schedules_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `hindustansystem_systems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_floors`
--
ALTER TABLE `hindustansystem_floors`
  ADD CONSTRAINT `hindustansystem_floors_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `hindustansystem_projects` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `hindustansystem_unit_rate_logs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hindustansystem_unit_status_logs`
--
ALTER TABLE `hindustansystem_unit_status_logs`
  ADD CONSTRAINT `hindustansystem_unit_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `hindustansystem_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hindustansystem_unit_status_logs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `hindustansystem_units` (`id`) ON DELETE CASCADE;

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
