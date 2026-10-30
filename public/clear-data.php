<?php
/**
 * HINDUSTAN ERP — SERVER TRANSACTIONAL DATA CLEAR UTILITY
 * -----------------------------------------------------------------------------
 * Clears ONLY transactional row data (TRUNCATE TABLE) while preserving all
 * master tables, system settings, users, roles, units, and table structures.
 *
 * - Verifies password against DB table `hindustansystem_data_clear_passwords`
 *   (and `hindustansystem_users`).
 * - Records every clear operation (Date & Time, IP, Tables Cleared) in DB table
 *   `hindustansystem_data_clear_logs`.
 */

declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

// Load database credentials from .env
$parseEnvFile = static function (string $envPath): array {
    $vars = [];
    if (!is_readable($envPath)) {
        return $vars;
    }
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
            (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
            $val = substr($val, 1, -1);
        }
        $vars[$key] = $val;
    }
    return $vars;
};

$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
$env = $parseEnvFile($envPath);

$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? 'hindustansystem';
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';
$prefix = 'hindustansystem_';

$pdo = null;
$dbConnectionError = null;

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 1. Ensure password table exists in DB
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `{$prefix}data_clear_passwords` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `password_key` VARCHAR(255) NOT NULL,
            `description` VARCHAR(255) DEFAULT 'Master password to clear transactional data',
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Seed default password row if table is empty
    $countPwd = (int) $pdo->query("SELECT COUNT(*) FROM `{$prefix}data_clear_passwords`")->fetchColumn();
    if ($countPwd === 0) {
        $stmtSeed = $pdo->prepare("
            INSERT INTO `{$prefix}data_clear_passwords` (`password_key`, `description`, `is_active`, `created_at`, `updated_at`)
            VALUES (:pwd, :desc, 1, NOW(), NOW())
        ");
        $stmtSeed->execute([
            ':pwd'  => 'admin@123',
            ':desc' => 'Default Data Clear Password (editable in DB table hindustansystem_data_clear_passwords)',
        ]);
    }

    // 2. Ensure clear data logs table exists in DB
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `{$prefix}data_clear_logs` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `cleared_by` VARCHAR(150) NOT NULL,
            `ip_address` VARCHAR(64) DEFAULT NULL,
            `tables_cleared_count` INT NOT NULL DEFAULT 0,
            `tables_cleared` TEXT DEFAULT NULL,
            `status` VARCHAR(50) NOT NULL DEFAULT 'SUCCESS',
            `cleared_at` DATETIME NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
} catch (Throwable $e) {
    $dbConnectionError = $e->getMessage();
}

// Preserved Master & System Tables (NEVER cleared)
$preservedMasterTables = [
    'projects',
    'floors',
    'unit_types',
    'hindustan_units (units)',
    'payment_modes (payment_method)',
    'engineers',
    'employees',
    'cheque_statuses',
    'company_bank_accounts',
    'banks',
    'payees & partner_shares',
    'dms_document_types',
    'dms_categories',
    'categories',
    'site_expense_categories',
    'users & roles/permissions',
    'systems',
    'accounts, chart_of_accounts & voucher_types',
    'data_clear_passwords & data_clear_logs',
];

// Transactional Tables to TRUNCATE (Data cleared only, structure & columns preserved)
$transactionalTablesToClear = [
    // 1. Sales, Bookings & Customer EMI / Collection Schedules
    'collection_reminders',
    'emi_reschedule_logs',
    'customer_installments',
    'sale_status_logs',
    'sale_extra_works',
    'sale_units',
    'sales',
    'payments',
    'bookings',
    'customers',
    'sales_executives',

    // 2. Receipts & Cheque Realization
    'receipt_realization_logs',
    'receipt_stores',
    'receipts',

    // 3. Brokerage, Deals & Commissions
    'commission_entries',
    'deals',
    'brokerages',
    'brokers',

    // 4. Site Expenses, Contractor RA Bills, Bills & Vendors
    'ra_bill_payments',
    'ra_bills',
    'site_expense_payments',
    'site_expenses',
    'bill_payments',
    'bills',
    'vendors',

    // 5. Petty Cash Boxes, Accounts & Transactions
    'petty_cash_transactions',
    'petty_cash_boxes',
    'replenishment_requests',
    'petty_cash_entries',
    'petty_cash_accounts',

    // 6. Bank Loans, Loan EMI Schedules, Disbursals & Partner Contributions
    'loan_prepayments',
    'loan_disbursals',
    'loan_interest_logs',
    'emi_schedules',
    'loans',
    'partner_allocations',
    'partner_contributions',

    // 7. Accounting Vouchers, Journal Entries, DMS Documents & Audit Logs
    'ledger_entries',
    'voucher_lines',
    'vouchers',
    'journal_entries',
    'journal_vouchers',
    'dms_documents',
    'approvals',
    'approval_requests',
    'unit_rate_logs',
    'unit_status_logs',
    'activity_logs',
];

$alertType = null; // 'success' | 'error'
$alertMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo !== null) {
    $enteredPassword = trim((string) ($_POST['password'] ?? ''));

    if ($enteredPassword === '') {
        $alertType = 'error';
        $alertMessage = 'Please enter the database password to clear transactional data.';
    } else {
        $isValidPassword = false;
        $matchedActor = 'Database Admin';

        // Check 1: Verify against `hindustansystem_data_clear_passwords` table
        $stmtPwd = $pdo->query("SELECT `password_key`, `description` FROM `{$prefix}data_clear_passwords` WHERE `is_active` = 1");
        $pwdRows = $stmtPwd->fetchAll();
        foreach ($pwdRows as $pRow) {
            $storedKey = (string) $pRow['password_key'];
            if (
                hash_equals($storedKey, $enteredPassword) ||
                (str_starts_with($storedKey, '$2y$') && password_verify($enteredPassword, $storedKey))
            ) {
                $isValidPassword = true;
                $matchedActor = 'DB Password Table (' . ($pRow['description'] ?: 'Master Key') . ')';
                break;
            }
        }

        // Check 2: Also allow active user passwords from `hindustansystem_users` table
        if (!$isValidPassword) {
            try {
                $stmtUsers = $pdo->query("SELECT `name`, `email`, `password` FROM `{$prefix}users` WHERE `status` = 'active'");
                foreach ($stmtUsers->fetchAll() as $uRow) {
                    $uHash = (string) ($uRow['password'] ?? '');
                    if ($uHash !== '' && (password_verify($enteredPassword, $uHash) || hash_equals($uHash, $enteredPassword))) {
                        $isValidPassword = true;
                        $matchedActor = ($uRow['name'] ?? 'User') . ' (' . ($uRow['email'] ?? '') . ')';
                        break;
                    }
                }
            } catch (Throwable $ignore) {
                // Ignore if users table query fails
            }
        }

        if (!$isValidPassword) {
            $alertType = 'error';
            $alertMessage = 'Invalid Password! Password did not match any active record in hindustansystem_data_clear_passwords or hindustansystem_users.';
        } else {
            // Execute Data Clear
            $clearedTables = [];
            try {
                // Fetch existing tables in DB so we only truncate tables that exist
                $existingTables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                $existingMap = array_flip($existingTables);

                $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

                foreach ($transactionalTablesToClear as $baseTable) {
                    $fullTable = $prefix . $baseTable;
                    if (isset($existingMap[$fullTable])) {
                        $pdo->exec("TRUNCATE TABLE `{$fullTable}`;");
                        $clearedTables[] = $fullTable;
                    }
                }

                // Reset transactional statuses on preserved master tables
                if (isset($existingMap[$prefix . 'hindustan_units'])) {
                    $pdo->exec("
                        UPDATE `{$prefix}hindustan_units`
                        SET `status` = 'available',
                            `sale_rate_per_sqft` = NULL,
                            `sale_amount` = NULL,
                            `difference` = NULL;
                    ");
                }

                if (isset($existingMap[$prefix . 'company_bank_accounts'])) {
                    $pdo->exec("
                        UPDATE `{$prefix}company_bank_accounts`
                        SET `current_balance` = COALESCE(`opening_balance`, 0.00);
                    ");
                }

                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

                $nowFormatted = date('Y-m-d H:i:s');
                $displayTime  = date('d M Y, h:i:s A');
                $ipAddress    = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

                // Store execution log in `hindustansystem_data_clear_logs`
                $stmtLog = $pdo->prepare("
                    INSERT INTO `{$prefix}data_clear_logs`
                        (`cleared_by`, `ip_address`, `tables_cleared_count`, `tables_cleared`, `status`, `cleared_at`, `created_at`)
                    VALUES
                        (:cleared_by, :ip_address, :cnt, :tables_list, 'SUCCESS', :cleared_at, :created_at)
                ");
                $stmtLog->execute([
                    ':cleared_by'  => $matchedActor,
                    ':ip_address'  => $ipAddress,
                    ':cnt'         => count($clearedTables),
                    ':tables_list' => implode(', ', $clearedTables),
                    ':cleared_at'  => $nowFormatted,
                    ':created_at'  => $nowFormatted,
                ]);

                $alertType = 'success';
                $alertMessage = "Transactional data cleared successfully on {$displayTime}! (" . count($clearedTables) . " transactional tables cleared; all master tables preserved.)";
            } catch (Throwable $ex) {
                try {
                    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
                } catch (Throwable $ignore) {}
                $alertType = 'error';
                $alertMessage = 'Error while clearing data: ' . $ex->getMessage();
            }
        }
    }
}

// Fetch recent clear logs from `hindustansystem_data_clear_logs`
$clearLogs = [];
if ($pdo !== null) {
    try {
        $clearLogs = $pdo->query("SELECT * FROM `{$prefix}data_clear_logs` ORDER BY `id` DESC LIMIT 25")->fetchAll();
    } catch (Throwable $ignore) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hindustan ERP — Server Database Clear Utility</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            padding: 32px 16px;
            line-height: 1.5;
        }
        .container { max-width: 960px; margin: 0 auto; }
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
            margin-bottom: 20px;
        }
        .header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .title { font-size: 18px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.04em; }
        .subtitle { font-size: 12px; color: #64748b; margin-top: 2px; }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 5px solid #d97706;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13px;
            color: #92400e;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-left: 5px solid #059669;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 5px solid #dc2626;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 20px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
        }
        .box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
            font-weight: 800;
        }
        .tag-list { display: flex; flex-wrap: wrap; gap: 6px; }
        .tag {
            font-family: monospace;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
        }
        .tag-keep { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .tag-clear { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .form-row {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
            background: #f8fafc;
            padding: 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .form-group { flex: 1; min-width: 240px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
            letter-spacing: 0.05em;
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            font-size: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
        }
        .form-group input:focus { border-color: #a38c29; box-shadow: 0 0 0 3px rgba(163, 140, 41, 0.15); }
        .btn-danger {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 11px 22px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-danger:hover { background: #b91c1c; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th {
            background: #1e293b;
            color: #ffffff;
            text-align: left;
            padding: 10px 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        tr:hover td { background: #f8fafc; }
        .mono { font-family: monospace; font-weight: 700; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header-bar">
            <div>
                <div class="title">Hindustan ERP — Clear Transactional Data</div>
                <div class="subtitle">Clears transactional rows only (no columns or tables dropped). Master &amp; System tables are preserved.</div>
            </div>
            <span class="badge">DB: <?= htmlspecialchars($dbName) ?></span>
        </div>

        <?php if ($dbConnectionError): ?>
            <div class="alert-error">
                Database Connection Error: <?= htmlspecialchars($dbConnectionError) ?>
            </div>
        <?php endif; ?>

        <?php if ($alertMessage): ?>
            <div class="<?= $alertType === 'success' ? 'alert-success' : 'alert-error' ?>">
                <?= htmlspecialchars($alertMessage) ?>
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    alert(<?= json_encode($alertMessage) ?>);
                });
            </script>
        <?php endif; ?>

        <div class="alert-warning">
            <strong>⚠️ ALERT:</strong> Running this action will clear all transactional records (Sales, Bookings, Receipts, Cheque Realizations, Vouchers, Expenses, Loans, Petty Cash) and reset Units to <code>available</code> and Company Bank Account balances to their <code>opening_balance</code>. All Master tables listed in green below will <strong>NOT</strong> be cleared.
        </div>

        <div class="grid-2">
            <div class="box">
                <h4 style="color:#166534;">✅ Preserved Master Tables (NOT Cleared)</h4>
                <div class="tag-list">
                    <?php foreach ($preservedMasterTables as $t): ?>
                        <span class="tag tag-keep"><?= htmlspecialchars($t) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="box">
                <h4 style="color:#991b1b;">🗑️ Transactional Tables to Clear (Rows Only)</h4>
                <div class="tag-list">
                    <?php foreach ($transactionalTablesToClear as $t): ?>
                        <span class="tag tag-clear"><?= htmlspecialchars($t) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <form method="POST" action="/clear-data.php" onsubmit="return confirm('⚠️ ALERT: Are you sure you want to clear all transactional data now? Master tables will NOT be removed.');">
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Enter Database Clear Password (from <code><?= htmlspecialchars($prefix) ?>data_clear_passwords</code> or Owner User Password)</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password to authorize data clear..." autocomplete="off">
                </div>
                <button type="submit" class="btn-danger">Clear Transactional Data</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="header-bar" style="margin-bottom: 12px;">
            <div>
                <div class="title" style="font-size: 14px;">Clear Data Execution Logs (Stored in <code><?= htmlspecialchars($prefix) ?>data_clear_logs</code>)</div>
                <div class="subtitle">Audit history of date &amp; time when transactional data was cleared.</div>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date &amp; Time (IST)</th>
                        <th>Authorized By</th>
                        <th>IP Address</th>
                        <th>Tables Cleared</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clearLogs)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:#94a3b8; padding: 20px;">No data clear logs recorded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clearLogs as $log): ?>
                            <tr>
                                <td class="mono"><?= (int) $log['id'] ?></td>
                                <td class="mono"><?= htmlspecialchars(date('d M Y, h:i:s A', strtotime((string) $log['cleared_at']))) ?></td>
                                <td><?= htmlspecialchars((string) $log['cleared_by']) ?></td>
                                <td class="mono"><?= htmlspecialchars((string) ($log['ip_address'] ?? '—')) ?></td>
                                <td class="mono"><?= (int) $log['tables_cleared_count'] ?> tables</td>
                                <td><span class="tag tag-keep"><?= htmlspecialchars((string) $log['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
