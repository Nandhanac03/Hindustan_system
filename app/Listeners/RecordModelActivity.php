<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class RecordModelActivity
{
    private const IGNORED_MODELS = [
        ActivityLog::class,
    ];

    // Child, line-item, and internal log models created in bulk by parent actions
    private const IGNORED_BASENAMES = [
        'CustomerInstallment',
        'EmiSchedule',
        'EmiRescheduleLog',
        'JournalEntry',
        'VoucherLine',
        'LedgerEntry',
        'SaleUnit',
        'SaleStatusLog',
        'UnitRateLog',
        'UnitStatusLog',
        'ReceiptRealizationLog',
        'LoanInterestLog',
        'CollectionReminder',
    ];

    private const IGNORED_ATTRIBUTES = [
        'updated_at',
        'created_at',
        'remember_token',
        'password',
        'last_login_at',
        'last_login_ip',
    ];

    public function handle(string $eventName, array $payload): void
    {
        try {
            if (app()->runningInConsole() || ! Auth::check() || ! ActivityLog::isLoggingActive()) {
                return;
            }

            $model = $payload[0] ?? null;
            if (! $model instanceof Model) {
                return;
            }

            foreach (self::IGNORED_MODELS as $ignored) {
                if ($model instanceof $ignored) {
                    return;
                }
            }

            $actionType = match (true) {
                str_starts_with($eventName, 'eloquent.created:') => 'Created',
                str_starts_with($eventName, 'eloquent.updated:') => 'Updated',
                str_starts_with($eventName, 'eloquent.deleted:') => 'Deleted',
                default => null,
            };

            if ($actionType === null) {
                return;
            }

            $changes = [];
            $oldValues = [];

            if ($actionType === 'Updated') {
                $dirty = array_diff_key($model->getChanges(), array_flip(self::IGNORED_ATTRIBUTES));
                if (empty($dirty)) {
                    return;
                }
                foreach ($dirty as $field => $newVal) {
                    $changes[$field] = $newVal;
                    $oldValues[$field] = $model->getOriginal($field);
                }
            } elseif ($actionType === 'Created') {
                $changes = array_diff_key($model->getAttributes(), array_flip(self::IGNORED_ATTRIBUTES));
            } elseif ($actionType === 'Deleted') {
                $oldValues = array_diff_key($model->getOriginal(), array_flip(self::IGNORED_ATTRIBUTES));
            }

            $modelName = class_basename($model);
            if (in_array($modelName, self::IGNORED_BASENAMES, true)) {
                return;
            }
            $recordId = $model->getKey();
            $module = $this->resolveModule($modelName);
            $user = Auth::user();

            $changedFieldsText = '';
            if ($actionType === 'Updated' && ! empty($changes)) {
                $fields = implode(', ', array_slice(array_keys($changes), 0, 4));
                $changedFieldsText = " ({$fields})";
            }

            $description = "{$actionType} {$modelName} #{$recordId}{$changedFieldsText}";
            $path = '/' . ltrim(Request::path(), '/');

            ActivityLog::create([
                'user_id' => $user->id,
                'system_id' => $user->system_id,
                'action' => "{$actionType} {$modelName}",
                'category' => 'record',
                'screen' => Str::limit($path, 250, ''),
                'route_name' => Request::route()?->getName(),
                'method' => strtoupper(Request::method()),
                'module' => $module,
                'subject_type' => get_class($model),
                'subject_id' => is_numeric($recordId) ? (int) $recordId : null,
                'description' => Str::limit($description, 250, ''),
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Str::limit((string) (Request::userAgent() ?? 'Browser'), 250, ''),
                'metadata' => [
                    'old' => $oldValues,
                    'new' => $changes,
                ],
            ]);
        } catch (\Throwable $e) {
            // Never interrupt module operations if audit logging fails
        }
    }

    private function resolveModule(string $modelName): string
    {
        return match (true) {
            in_array($modelName, ['User', 'Role', 'Permission', 'ErpModule', 'ErpSubModule', 'System'], true) => 'Users & Roles',
            in_array($modelName, ['Project', 'Unit', 'UnitType', 'Floor', 'PartnerShare', 'UnitRateLog', 'UnitStatusLog'], true) => 'Projects & Configuration',
            in_array($modelName, ['Sale', 'SaleUnit', 'SaleExtraWork', 'SaleStatusLog', 'Booking', 'Deal'], true) => 'Sales & Booking',
            in_array($modelName, ['Customer', 'CustomerInstallment', 'Receipt', 'ReceiptStore', 'ReceiptRealizationLog', 'EmiSchedule', 'EmiRescheduleLog', 'CollectionReminder'], true) => 'Customer & Collections',
            in_array($modelName, ['RaBill', 'RaBillPayment', 'Engineer', 'Payee', 'Vendor'], true) => 'Contractor Desk',
            in_array($modelName, ['Broker', 'Brokerage', 'CommissionEntry', 'SalesExecutive'], true) => 'Brokerage',
            in_array($modelName, ['PettyCashBox', 'PettyCashTransaction'], true) => 'Petty Cash',
            in_array($modelName, ['DmsDocument', 'DmsCategory', 'DmsDocumentType', 'Document'], true) => 'Document Management',
            in_array($modelName, ['SiteExpense', 'SiteExpenseCategory', 'SiteExpensePayment'], true) => 'Site Expense',
            in_array($modelName, ['CompanyBankAccount', 'Bank', 'PaymentMode', 'ChequeStatus'], true) => 'Bank & Treasury',
            in_array($modelName, ['Voucher', 'VoucherLine', 'VoucherType', 'JournalVoucher', 'JournalEntry', 'ChartOfAccount', 'LedgerEntry', 'Account', 'AccountingSetting', 'Category', 'PartnerAllocation', 'PartnerContribution'], true) => 'Accounting Reports',
            in_array($modelName, ['Loan', 'LoanDisbursal', 'LoanInterestLog', 'LoanPrepayment'], true) => 'Loans & Debts',
            in_array($modelName, ['Approval', 'ApprovalRequest', 'ApprovalRule'], true) => 'Approvals',
            in_array($modelName, ['Employee'], true) => 'Master',
            default => Str::title(Str::snake($modelName, ' ')),
        };
    }
}

