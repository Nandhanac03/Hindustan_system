-- =============================================================================
-- HINDUSTAN ERP — CLEAR TRANSACTIONAL DATA SCRIPT (DATA ONLY, NO SCHEMA DROP)
-- =============================================================================
-- Database Prefix: hindustansystem_
-- Purpose:
--   Clears ONLY transactional row data (TRUNCATE TABLE) for server deployment
--   while keeping all table structures, columns/fields, and master/system data.
--
-- PRESERVED TABLES (NOT CLEARED):
--   - hindustansystem_systems
--   - hindustansystem_users, hindustansystem_roles, hindustansystem_permissions,
--     hindustansystem_model_has_roles, hindustansystem_model_has_permissions,
--     hindustansystem_role_has_permissions, hindustansystem_password_reset_tokens,
--     hindustansystem_sessions, hindustansystem_migrations,
--     hindustansystem_user_dashboard_layouts
--   - hindustansystem_projects
--   - hindustansystem_floors
--   - hindustansystem_unit_types
--   - hindustansystem_hindustan_units (units master — kept intact; status reset to 'available')
--   - hindustansystem_payment_modes (payment_method)
--   - hindustansystem_engineers
--   - hindustansystem_employees
--   - hindustansystem_cheque_statuses
--   - hindustansystem_company_bank_accounts (kept intact; current_balance reset to opening_balance)
--   - hindustansystem_banks
--   - hindustansystem_payees, hindustansystem_partner_shares
--   - hindustansystem_dms_document_types
--   - hindustansystem_dms_categories
--   - hindustansystem_categories
--   - hindustansystem_site_expense_categories
--   - hindustansystem_accounts, hindustansystem_chart_of_accounts,
--     hindustansystem_voucher_types, hindustansystem_approval_rules,
--     hindustansystem_accounting_settings
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Sales, Bookings & Customer EMI / Collection Schedules
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_collection_reminders`;
TRUNCATE TABLE `hindustansystem_emi_reschedule_logs`;
TRUNCATE TABLE `hindustansystem_customer_installments`;
TRUNCATE TABLE `hindustansystem_sale_status_logs`;
TRUNCATE TABLE `hindustansystem_sale_extra_works`;
TRUNCATE TABLE `hindustansystem_sale_units`;
TRUNCATE TABLE `hindustansystem_sales`;
TRUNCATE TABLE `hindustansystem_payments`;
TRUNCATE TABLE `hindustansystem_bookings`;
TRUNCATE TABLE `hindustansystem_customers`;
TRUNCATE TABLE `hindustansystem_sales_executives`;

-- -----------------------------------------------------------------------------
-- 2. Receipts & Cheque Realization
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_receipt_realization_logs`;
TRUNCATE TABLE `hindustansystem_receipt_stores`;
TRUNCATE TABLE `hindustansystem_receipts`;

-- -----------------------------------------------------------------------------
-- 3. Brokerage, Deals & Commissions
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_commission_entries`;
TRUNCATE TABLE `hindustansystem_deals`;
TRUNCATE TABLE `hindustansystem_brokerages`;
TRUNCATE TABLE `hindustansystem_brokers`;

-- -----------------------------------------------------------------------------
-- 4. Site Expenses, Contractor RA Bills, Bills & Vendors
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_ra_bill_payments`;
TRUNCATE TABLE `hindustansystem_ra_bills`;
TRUNCATE TABLE `hindustansystem_site_expense_payments`;
TRUNCATE TABLE `hindustansystem_site_expenses`;
TRUNCATE TABLE `hindustansystem_bill_payments`;
TRUNCATE TABLE `hindustansystem_bills`;
TRUNCATE TABLE `hindustansystem_vendors`;

-- -----------------------------------------------------------------------------
-- 5. Petty Cash Boxes, Accounts & Transactions
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_petty_cash_transactions`;
TRUNCATE TABLE `hindustansystem_petty_cash_boxes`;
TRUNCATE TABLE `hindustansystem_replenishment_requests`;
TRUNCATE TABLE `hindustansystem_petty_cash_entries`;
TRUNCATE TABLE `hindustansystem_petty_cash_accounts`;

-- -----------------------------------------------------------------------------
-- 6. Bank Loans, Loan EMI Schedules, Disbursals & Partner Contributions
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_loan_prepayments`;
TRUNCATE TABLE `hindustansystem_loan_disbursals`;
TRUNCATE TABLE `hindustansystem_loan_interest_logs`;
TRUNCATE TABLE `hindustansystem_emi_schedules`;
TRUNCATE TABLE `hindustansystem_loans`;
TRUNCATE TABLE `hindustansystem_partner_allocations`;
TRUNCATE TABLE `hindustansystem_partner_contributions`;

-- -----------------------------------------------------------------------------
-- 7. Accounting Vouchers, Journal Entries, DMS Documents & Audit Logs
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `hindustansystem_ledger_entries`;
TRUNCATE TABLE `hindustansystem_voucher_lines`;
TRUNCATE TABLE `hindustansystem_vouchers`;
TRUNCATE TABLE `hindustansystem_journal_entries`;
TRUNCATE TABLE `hindustansystem_journal_vouchers`;
TRUNCATE TABLE `hindustansystem_dms_documents`;
TRUNCATE TABLE `hindustansystem_approvals`;
TRUNCATE TABLE `hindustansystem_approval_requests`;
TRUNCATE TABLE `hindustansystem_unit_rate_logs`;
TRUNCATE TABLE `hindustansystem_unit_status_logs`;
TRUNCATE TABLE `hindustansystem_activity_logs`;

-- -----------------------------------------------------------------------------
-- 8. Reset Transactional State on Preserved Master Tables (Data Preserved)
-- -----------------------------------------------------------------------------
-- Reset all units back to 'available' and clear sold rate/amount overrides
UPDATE `hindustansystem_hindustan_units`
SET `status` = 'available',
    `sale_rate_per_sqft` = NULL,
    `sale_amount` = NULL,
    `difference` = NULL;

-- Reset company bank account running balances back to their opening balance
UPDATE `hindustansystem_company_bank_accounts`
SET `current_balance` = COALESCE(`opening_balance`, 0.00);

-- -----------------------------------------------------------------------------
-- 9. Password & Execution Log Tables (Preserved — Never Cleared)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hindustansystem_data_clear_passwords` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `password_key` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) DEFAULT 'Master password to clear transactional data',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hindustansystem_data_clear_passwords` (`password_key`, `description`, `is_active`, `created_at`, `updated_at`)
SELECT 'admin@123', 'Default Data Clear Password (editable in DB)', 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `hindustansystem_data_clear_passwords`);

CREATE TABLE IF NOT EXISTS `hindustansystem_data_clear_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `cleared_by` VARCHAR(150) NOT NULL,
    `ip_address` VARCHAR(64) DEFAULT NULL,
    `tables_cleared_count` INT NOT NULL DEFAULT 0,
    `tables_cleared` TEXT DEFAULT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'SUCCESS',
    `cleared_at` DATETIME NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hindustansystem_data_clear_logs`
    (`cleared_by`, `ip_address`, `tables_cleared_count`, `tables_cleared`, `status`, `cleared_at`, `created_at`)
VALUES
    ('SQL Script Execution', '127.0.0.1', 39, 'Executed via clear_transactional_data.sql', 'SUCCESS', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
