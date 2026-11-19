<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    public const LOGGING_CACHE_KEY = 'erp_activity_logging_active';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'system_id',
        'action',
        'category',
        'screen',
        'route_name',
        'method',
        'module',
        'duration_seconds',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public static function isLoggingActive(): bool
    {
        return (bool) Cache::get(self::LOGGING_CACHE_KEY, true);
    }

    public static function setLoggingActive(bool $active): void
    {
        Cache::forever(self::LOGGING_CACHE_KEY, $active);
    }

    public static function record(string $action, string $description, ?Model $subject = null, array $extra = []): ?self
    {
        if (! self::isLoggingActive()) {
            return null;
        }

        $user = Auth::user();

        return self::create(array_merge([
            'user_id' => $extra['user_id'] ?? ($user ? $user->id : null),
            'system_id' => $extra['system_id'] ?? ($user ? $user->system_id : null),
            'action' => $action,
            'category' => $extra['category'] ?? ($subject ? 'record' : 'action'),
            'screen' => $extra['screen'] ?? null,
            'route_name' => $extra['route_name'] ?? null,
            'method' => $extra['method'] ?? null,
            'module' => $extra['module'] ?? null,
            'duration_seconds' => $extra['duration_seconds'] ?? null,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'description' => Str::limit($description, 250, ''),
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Str::limit((string) (Request::userAgent() ?? 'CLI/System'), 250, ''),
            'metadata' => $extra['metadata'] ?? null,
        ]));
    }
}
