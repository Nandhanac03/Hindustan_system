<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-3 shadow-xs">
            <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-medium text-xs">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Authentication Error Alert Banner -->
    @if ($errors->any())
        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-xs animate-fade-in">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 space-y-0.5">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-rose-900 text-xs uppercase tracking-wider">Access Denied</h4>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-200/60 text-rose-800 uppercase tracking-widest">Failed</span>
                    </div>
                    <p class="text-[11px] text-rose-700 font-medium leading-relaxed">
                        {{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first()) }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
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
                   placeholder="Enter your email or username"
                   class="w-full bg-white border {{ $errors->has('email') ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/15 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 text-slate-900' }} rounded-xl text-xs px-4 py-3 placeholder-slate-400 focus:outline-none transition shadow-xs font-medium" />
            @if($errors->has('email'))
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200/80 text-rose-700 text-[11px] font-semibold mt-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="relative">
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full bg-white border {{ $errors->has('password') ? 'border-rose-400 ring-2 ring-rose-400/20 bg-rose-50/15 text-rose-900' : 'border-slate-200 focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 text-slate-900' }} rounded-xl text-xs px-4 py-3 pr-10 placeholder-slate-400 focus:outline-none transition shadow-xs font-medium" />
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
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200/80 text-rose-700 text-[11px] font-semibold mt-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first('password') }}</span>
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="rounded border-slate-300 bg-white text-[#a38c29] focus:ring-[#a38c29]/20 focus:ring-offset-0 w-4 h-4 cursor-pointer" />
                <span class="ms-2 text-xs text-slate-500 font-medium select-none">Remember this session</span>
            </label>
        </div>

        <!-- Action Submit -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-bold transition shadow-lg shadow-[#a38c29]/20 tracking-wide uppercase">
                Sign In
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500">Need access? Contact your administrator.</span>
        </div>
    </form>
</x-guest-layout>