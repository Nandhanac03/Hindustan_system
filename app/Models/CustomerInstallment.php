<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'installment_no',
        'label',
        'due_date',
        'amount',
        'paid_amount',
        'status',
        'schedule_type',
        'rescheduled_from_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function rescheduledFrom()
    {
        return $this->belongsTo(CustomerInstallment::class, 'rescheduled_from_id');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date && $this->due_date->isPast();
    }

    public static function allocatePaymentStatusForSale($saleId)
    {
        $sale = \App\Models\Sale::find($saleId);
        if (!$sale) {
            return;
        }

        $totalPaid = round((float)\App\Models\Receipt::where('sale_id', $saleId)->sum('amount'), 2);
        $allocatedPayment = $totalPaid;
        
        $installments = self::where('sale_id', $saleId)
            ->orderBy('installment_no')
            ->get();

        foreach ($installments as $inst) {
            $instAmount = round((float)$inst->amount, 2);
            if (round($allocatedPayment - $instAmount, 2) >= -0.01) {
                $inst->update(['status' => 'paid']);
                $allocatedPayment = max(0, round($allocatedPayment - $instAmount, 2));
            } elseif (round($allocatedPayment, 2) > 0.01) {
                $inst->update(['status' => 'partial']);
                $allocatedPayment = 0;
            } else {
                if ($inst->due_date && $inst->due_date->isPast()) {
                    $inst->update(['status' => 'overdue']);
                } else {
                    $inst->update(['status' => 'pending']);
                }
            }
        }
    }
}
