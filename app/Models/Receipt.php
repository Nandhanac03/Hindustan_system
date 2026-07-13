<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'sale_id', 'customer_id', 'project_id', 'unit_id',
        'receipt_date', 'amount', 'payment_mode', 'reference_no', 'bank_id',
        'remarks', 'created_by', 'partner_id',
    ];

    protected $casts = [
        'receipt_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function partner()
    {
        return $this->belongsTo(Payee::class, 'partner_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getBankNameAttribute()
    {
        return $this->bank?->bank_name;
    }

    public static function allocateToPartners(self $receipt): void
    {
        if ($receipt->partner_id !== null) {
            return;
        }

        $shares = \App\Models\PartnerShare::where('project_id', $receipt->project_id)->get();
        foreach ($shares as $share) {
            $partnerAmount = $receipt->amount * ($share->share_pct / 100);
            
            self::create([
                'sale_id'      => $receipt->sale_id,
                'customer_id'  => $receipt->customer_id,
                'project_id'   => $receipt->project_id,
                'unit_id'      => $receipt->unit_id,
                'receipt_date' => $receipt->receipt_date,
                'amount'       => $partnerAmount,
                'payment_mode' => $receipt->payment_mode,
                'reference_no' => $receipt->reference_no,
                'bank_id'      => $receipt->bank_id,
                'remarks'      => "Share of collection ({$share->share_pct}%) from receipt #{$receipt->id}",
                'created_by'   => $receipt->created_by,
                'partner_id'   => $share->partner_id,
            ]);
        }
    }
}