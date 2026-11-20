<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = trim((string) $this->input('email'));
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');

        $authenticated = false;

        // 1. If valid email format, try email first
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $authenticated = Auth::attempt(['email' => $login, 'password' => $password], $remember);
        }

        // 2. Try username
        if (!$authenticated) {
            $authenticated = Auth::attempt(['username' => $login, 'password' => $password], $remember);
        }

        // 3. Try name
        if (!$authenticated) {
            $authenticated = Auth::attempt(['name' => $login, 'password' => $password], $remember);
        }

        // 4. Try email as fallback (e.g., custom format or legacy email handle)
        if (!$authenticated) {
            $authenticated = Auth::attempt(['email' => $login, 'password' => $password], $remember);
        }

        // 5. Try employee_code
        if (!$authenticated) {
            $authenticated = Auth::attempt(['employee_code' => $login, 'password' => $password], $remember);
        }

        // 6. Case-insensitive lookup fallback (handles case variations like Admin vs admin, EMP-001 vs emp-001)
        if (!$authenticated) {
            $lower = strtolower($login);
            $user = \App\Models\User::withoutGlobalScopes()
                ->where(function ($q) use ($lower) {
                    $q->whereRaw('LOWER(username) = ?', [$lower])
                      ->orWhereRaw('LOWER(email) = ?', [$lower])
                      ->orWhereRaw('LOWER(name) = ?', [$lower])
                      ->orWhereRaw('LOWER(employee_code) = ?', [$lower]);
                })
                ->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                Auth::login($user, $remember);
                $authenticated = true;
            }
        }

        if (!$authenticated) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
