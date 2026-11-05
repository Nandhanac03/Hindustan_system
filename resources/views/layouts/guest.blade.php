@props(['cardless' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HindustanERP') }} - Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabasco.css') }}?v=28.0">
        <script src="https://unpkg.com/lucide@latest"></script>

        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .fade-in { animation: fadeInUp 0.4s ease both; }

            /* Prevent browser autocomplete from overriding light theme colors */
            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus,
            input:-webkit-autofill:active {
                -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
                -webkit-text-fill-color: #22201d !important;
                transition: background-color 5000s ease-in-out 0s;
            }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 relative" style="background-color: #f6f4ee; font-family: 'Montserrat', sans-serif;">

        @if($cardless)
            <div class="relative z-10 w-full flex items-center justify-center fade-in">
                {{ $slot }}
            </div>
        @else
            <!-- Main Content Container for other auth pages -->
            <div class="relative z-10 w-full max-w-md mx-auto fade-in">
                <!-- Brand Header -->
                <div class="flex justify-center mb-6">
                    <img
                        src="{{ asset('img/logo-tabasco.png') }}?v=1.0"
                        alt="Tabasco Human Capital Logo"
                        class="h-20 w-auto object-contain rounded-lg"
                    >
                </div>

                <!-- Centered Layout: Auth Card -->
                <div class="w-full bg-white border border-[#e5dfd3] p-8 rounded-2xl shadow-xl">
                    {{ $slot }}
                </div>
            </div>
        @endif

        <script>
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        </script>
    </body>
</html>