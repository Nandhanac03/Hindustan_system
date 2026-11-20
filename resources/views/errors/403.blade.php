<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | Hindustan ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabasco.css') }}?v=50.0">
</head>
<body class="h-full bg-slate-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/90 p-8 text-center space-y-6">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-[#a38c29]/15 border border-[#a38c29]/30 text-[#8a7522] flex items-center justify-center shadow-md">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-extrabold uppercase tracking-widest">
                403 &bull; Unauthorized Access
            </span>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Access Restricted</h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                {{ $message ?? 'You do not have permission to view this module. Please contact your system administrator to request access.' }}
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            @php
                $redirectUrl = route('dashboard');
                if (auth()->check() && !auth()->user()->canViewModule('dashboard.view')) {
                    $firstUrl = \App\Support\ErpMenu::getFirstPermittedUrl(auth()->user());
                    if ($firstUrl) {
                        $redirectUrl = $firstUrl;
                    }
                }
            @endphp

            <a href="{{ $redirectUrl }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#a38c29]/25 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Return to Permitted Area</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>

