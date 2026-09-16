<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountingSetting extends Model
{
    protected $table = 'accounting_settings';

    protected $fillable = [
        'key',
        'value',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
    ];

    /**
     * Check if Opening Balances are locked
     */
    public static function isOpeningBalanceLocked(): bool
    {
        $setting = static::where('key', 'is_opening_balance_locked')->first();
        return $setting ? (bool) $setting->value : false;
    }

    /**
     * Get details of lock
     */
    public static function getLockDetails(): ?array
    {
        $setting = static::where('key', 'is_opening_balance_locked')->first();
        if (!$setting || !$setting->value) {
            return null;
        }

        $user = $setting->locked_by ? User::find($setting->locked_by) : null;

        return [
            'is_locked' => true,
            'locked_at' => $setting->locked_at,
            'locked_by' => $user ? $user->name : 'System Admin',
        ];
    }

    /**
     * Lock Opening Balances
     */
    public static function lockOpeningBalances(?int $userId = null): void
    {
        static::updateOrCreate(
            ['key' => 'is_opening_balance_locked'],
            [
                'value' => '1',
                'locked_at' => now(),
                'locked_by' => $userId ?? (Auth::id() ?? 1),
            ]
        );
    }

    /**
     * Unlock Opening Balances (Administrative Override)
     */
    public static function unlockOpeningBalances(): void
    {
        static::updateOrCreate(
            ['key' => 'is_opening_balance_locked'],
            [
                'value' => '0',
                'locked_at' => null,
                'locked_by' => null,
            ]
        );
    }
}
