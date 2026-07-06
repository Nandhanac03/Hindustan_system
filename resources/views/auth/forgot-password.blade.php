<x-guest-layout>
    <div class="mb-4 text-xs text-slate-400 leading-normal">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-500 text-xs font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Email Address</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   placeholder="name@company.com"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-650 focus:outline-none transition" />
            @if($errors->has('email'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition shadow-lg hover:shadow-primary/10 tracking-wide uppercase">
                Email Password Reset Link
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-xs text-primary hover:text-indigo-400 font-medium transition">
                &larr; Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
