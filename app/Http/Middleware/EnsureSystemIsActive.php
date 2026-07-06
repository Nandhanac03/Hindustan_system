<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSystemIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check user status and their system status
            if (!$user->isActive() || ($user->system && !$user->system->is_active)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                throw new AuthenticationException('Your account or system is currently inactive.');
            }
        }

        return $next($request);
    }
}
