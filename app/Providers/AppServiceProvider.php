<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
}
}
