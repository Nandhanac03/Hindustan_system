<x-guest-layout>
    @php
        $systems = \App\Models\System::where('is_active', true)->get();
    @endphp

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <label for="name" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Full Name</label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   placeholder="John Doe"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-650 focus:outline-none transition" />
            @if($errors->has('name'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Email -->
        <div class="space-y-1.5">
            <label for="email" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Email Address</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   placeholder="name@company.com"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-650 focus:outline-none transition" />
            @if($errors->has('email'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Associated System -->
        <div class="space-y-1.5">
            <label for="system_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Operating Entity / System</label>
            <select id="system_id" 
                    name="system_id" 
                    required 
                    class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 focus:outline-none transition cursor-pointer">
                <option value="">Select Region...</option>
                @foreach($systems as $sys)
                    <option value="{{ $sys->id }}" {{ old('system_id') == $sys->id ? 'selected' : '' }}>
                        {{ $sys->name }} ({{ $sys->country }}) - {{ $sys->currency_code }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('system_id'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('system_id') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <label for="password" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Password</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   placeholder="••••••••"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-650 focus:outline-none transition" />
            @if($errors->has('password'))
                <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Confirm Password</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   placeholder="••••••••"
                   class="w-full bg-slate-950/80 border border-slate-800 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-white px-4 py-3 placeholder-slate-650 focus:outline-none transition" />
        </div>

        <!-- Action Submit -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition shadow-lg hover:shadow-primary/10 tracking-wide uppercase">
                Create Account
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-xs text-primary hover:text-indigo-400 font-medium transition">
                Already registered? Sign in &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>
