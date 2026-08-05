<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-600 text-xs font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address or Username -->
        <div class="space-y-1.5">
            <label for="email" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Email Address or Username</label>
            <input id="email"
                   type="text"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                  
                   class="w-full bg-white border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
            @if($errors->has('email'))
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <!-- <div class="flex items-center justify-between">
                <label for="password" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] text-primary hover:text-primary-700 font-bold uppercase tracking-wider transition" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div> -->
            <div class="relative">
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full bg-white border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 pr-10 placeholder-slate-400 focus:outline-none transition" />
                <button type="button" id="togglePassword"
                        onclick="(function(){var i=document.getElementById('password'),b=document.getElementById('togglePassword');if(i.type==='password'){i.type='text';b.innerHTML='<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-4 h-4\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88\'/></svg>';}else{i.type='password';b.innerHTML='<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-4 h-4\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z\'/></svg>';}})();"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>
            @if($errors->has('password'))
                <p class="text-xs text-rose-600 font-medium mt-1">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="rounded border-slate-300 bg-white text-primary focus:ring-primary/10 focus:ring-offset-0 w-4 h-4 cursor-pointer" />
                <span class="ms-2 text-xs text-slate-500 font-medium select-none">Remember this session</span>
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