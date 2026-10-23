<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Receipt;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Single source of truth for the "Realized Collections" KPI.
 *
 * Collections are counted from RECEIPTS, not from sales. Cash that was
 * actually received against a sale which was later cancelled / returned /
 * exchanged is still part of the company's realized cash flow, so it must
 * stay in the figure. Customer refunds paid back out of the company bank
 * account are then deducted to give the net realized amount.
 *
 * Every screen that renders a collections KPI (Dashboard, EMI Collections,
 * reports …) must go through this service so the numbers can never drift
 * apart again.
 */
class RealizedCollectionService
{
    /**
     * Every sale inside the given scope, regardless of status
     * (active, cancelled, returned, exchanged …).
     *
     * @return array<int, int|string>
     */
    public static function saleIds(?int $projectId = null): array
    {
        return Sale::query()
            ->when($projectId !== null, fn ($q) => $q->where('project_id', $projectId))
            ->pluck('id')
            ->all();
    }

    /**
     * Realized, customer-facing receipts inside the given scope.
     *
     * A receipt is in scope when it is booked against one of the in-scope
     * sales, or (for legacy rows) when it carries the project id directly.
     */
    public static function receiptQuery(?int $projectId = null, ?array $saleIds = null): Builder
    {
        $saleIds ??= self::saleIds($projectId);

        return Receipt::query()
            ->whereNull('partner_id')
            ->where('realization_status', 'realized')
            ->when(
                $projectId !== null || $saleIds !== [],
                fn (Builder $q) => $q->where(function (Builder $sub) use ($projectId, $saleIds) {
                    if ($projectId !== null) {
                        $sub->where('project_id', $projectId);
                    }
                    if ($saleIds !== []) {
                        $sub->orWhereIn('sale_id', $saleIds);
                    }
                })
            );
    }

    /**
     * Customer refunds paid out through Posted Receipt/Payment vouchers.
     *
     * @return array{total: float, monthly: array<int, float>}
     */
    public static function refunds(array $saleIds, ?int $year = null): array
    {
        $year ??= (int) Carbon::now()->year;

        $vouchers = DB::table('vouchers')
            ->whereIn('type', ['Receipt', 'Payment'])
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->get(['id', 'date', 'reference_no']);

        $total   = 0.0;
        $monthly = [];

        foreach ($vouchers as $voucher) {
            $reference = json_decode($voucher->reference_no, true);
            if (empty($reference['allocations']) || !is_array($reference['allocations'])) {
                continue;
            }

            foreach ($reference['allocations'] as $allocation) {
                if (($allocation['type'] ?? '') !== 'refund' || empty($allocation['target_id'])) {
                    continue;
                }
                if (!in_array((int) $allocation['target_id'], $saleIds, true)) {
                    continue;
                }

                $amount = (float) ($allocation['amount'] ?? 0);
                $total += $amount;

                if (!empty($voucher->date) && Carbon::parse($voucher->date)->year === $year) {
                    $month = (int) Carbon::parse($voucher->date)->month;
                    $monthly[$month] = ($monthly[$month] ?? 0.0) + $amount;
                }
            }
        }

        return ['total' => $total, 'monthly' => $monthly];
    }

    /**
     * Net realized collections for the given scope.
     *
     * @return array{gross: float, refunds: float, net: float, monthly_refunds: array<int, float>}
     */
    public static function summarize(?int $projectId = null): array
    {
        $saleIds = self::saleIds($projectId);

        $gross   = (float) self::receiptQuery($projectId, $saleIds)->sum('amount');
        $refunds = self::refunds($saleIds);

        return [
            'gross'          => $gross,
            'refunds'        => $refunds['total'],
            'net'            => max(0.0, $gross - $refunds['total']),
            'monthly_refunds'=> $refunds['monthly'],
        ];
    }
}
