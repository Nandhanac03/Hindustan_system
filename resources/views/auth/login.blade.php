<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-500 text-xs font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                   autocomplete="username"
                   placeholder="name@company.com"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-600 focus:outline-none transition" />
            @if($errors->has('email'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] text-primary hover:text-indigo-400 font-bold uppercase tracking-wider transition" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-600 focus:outline-none transition" />
            @if($errors->has('password'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember" 
                       class="rounded border-slate-800 bg-slate-950 text-primary focus:ring-primary/10 focus:ring-offset-0 w-4 h-4 cursor-pointer" />
                <span class="ms-2 text-xs text-slate-450 font-medium select-none">Remember this session</span>
            </label>
        </div>

        <!-- Action Submit -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition shadow-lg hover:shadow-primary/10 tracking-wide uppercase">
                Sign In
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500">Need access? Contact your administrator.</span>
        </div>
    </form>
</x-guest-layout>
