<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(route('dashboard'));
        }

        parent::mount();
    }
}
