<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'sale_id', 'customer_id', 'project_id', 'unit_id',
        'receipt_date', 'amount', 'payment_mode', 'reference_no', 'bank_name',
        'remarks', 'created_by',
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
}