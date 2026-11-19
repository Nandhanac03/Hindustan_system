<?php

namespace App\Providers;

use App\Listeners\RecordModelActivity;
use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\FilamentLoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Event::listen(Login::class, function (Login $event): void {
            if (! ActivityLog::isLoggingActive()) {
                return;
            }
            $user = $event->user;
            ActivityLog::create([
                'user_id' => $user->getAuthIdentifier(),
                'system_id' => $user->system_id ?? null,
                'action' => 'App opened',
                'category' => 'security',
                'screen' => '/login',
                'route_name' => 'login',
                'method' => 'POST',
                'module' => 'Authentication',
                'description' => 'User logged in to Hindustan ERP',
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Str::limit((string) (Request::userAgent() ?? 'Browser'), 250, ''),
            ]);
        });

        Event::listen(Logout::class, function (Logout $event): void {
            if (! ActivityLog::isLoggingActive() || ! $event->user) {
                return;
            }
            $user = $event->user;
            ActivityLog::create([
                'user_id' => $user->getAuthIdentifier(),
                'system_id' => $user->system_id ?? null,
                'action' => 'User Logout',
                'category' => 'security',
                'screen' => '/logout',
                'route_name' => 'logout',
                'method' => 'POST',
                'module' => 'Authentication',
                'description' => 'User signed out of Hindustan ERP',
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Str::limit((string) (Request::userAgent() ?? 'Browser'), 250, ''),
            ]);
        });

        Event::listen(
            ['eloquent.created: *', 'eloquent.updated: *', 'eloquent.deleted: *'],
            [RecordModelActivity::class, 'handle']
        );
    }
}
