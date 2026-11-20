<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'username', 'password', 'system_id', 'phone', 'employee_code', 'status', 'must_change_password', 'last_login_at', 'last_login_ip'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasSystemScope;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'system_id',
        'phone',
        'employee_code',
        'status',
        'must_change_password',
        'last_login_at',
        'last_login_ip',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasMultiSystemAccess(): bool
    {
        return $this->hasRole('Owner') || $this->hasRole('Super Admin');
    }

    /**
     * Check if user has permission for a specific sub-module action.
     * Actions: view, create, edit, delete, excel, pdf
     */
    public function hasModulePermission(string $subCode, string $action = 'view'): bool
    {
        // 1. Owner and Super Admin roles have unrestricted access to everything
        if ($this->hasRole('Owner') || $this->hasRole('Super Admin')) {
            return true;
        }

        $subCode = trim($subCode);
        $action = strtolower(trim($action));

        // Format: "{subCode}.{action}"
        $permKey = "{$subCode}.{$action}";

        try {
            // Direct or role-based check via Spatie HasRoles trait
            if ($this->hasPermissionTo($permKey)) {
                return true;
            }

            // Fallback for export aliases
            if ($action === 'excel' || $action === 'pdf') {
                if ($this->hasPermissionTo("{$subCode}.export")) {
                    return true;
                }
            }

            // Fallback for general view access
            if ($action === 'view') {
                // If subCode itself is assigned as a standalone permission
                if ($this->hasPermissionTo($subCode)) {
                    return true;
                }
                if ($this->hasPermissionTo("{$subCode}.manage")) {
                    return true;
                }
                // If sub-module is a creation-oriented route (e.g. sales.create, ra-bills.create, dms.upload)
                if ($this->hasPermissionTo("{$subCode}.create")) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            // Permission does not exist in permissions table or Spatie exception
            return false;
        }

        return false;
    }

    /**
     * Check if user can view a given sub-module in the side menu or page.
     */
    public function canViewModule(string $subCode): bool
    {
        return $this->hasModulePermission($subCode, 'view');
    }

    /**
     * Check if user can view any sub-module in a list of sub-module codes.
     */
    public function canAccessAnySubModule(array $subCodes): bool
    {
        if ($this->hasRole('Owner') || $this->hasRole('Super Admin')) {
            return true;
        }

        foreach ($subCodes as $code) {
            if ($this->canViewModule((string) $code)) {
                return true;
            }
        }

        return false;
    }
}
