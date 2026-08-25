<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'petty_cash_box_id',
        'voucher_id',
        'transaction_date',
        'voucher_number',
        'transaction_type',
        'reference_no',
        'narration',
        'cash_in',
        'cash_out',
        'balance',
        'created_by',
        'status',
    ];

    public function pettyCashBox()
    {
        return $this->belongsTo(PettyCashBox::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
