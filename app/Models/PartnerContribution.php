<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerContribution extends Model
{
    use HasSystemScope;

    protected $table = 'partner_contributions';

    protected $fillable = [
        'system_id',
        'partner_id',
        'project_id',
        'company_bank_account_id',
        'contribution_date',
        'amount',
        'payment_mode_id',
        'reference_no',
        'remarks',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'contribution_date' => 'date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'partner_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function paymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
