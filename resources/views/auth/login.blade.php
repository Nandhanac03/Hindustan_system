<x-guest-layout :cardless="true">
    <style>
        .login-card {
            width: 100%;
            max-width: 1180px;
            background: #ffffff;
            border-radius: 36px;
            box-shadow: 0 25px 60px -15px rgba(85, 70, 40, 0.16), 0 2px 6px rgba(0, 0, 0, 0.04);
            border: 1px solid #e7dfd1;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            min-height: 700px;
        }
        @media (max-width: 860px) {
            .login-card {
                flex-direction: column;
                max-width: 500px;
                min-height: auto;
                border-radius: 28px;
            }
            .login-left-col {
                width: 100% !important;
                height: 320px !important;
                border-right: none !important;
                border-bottom: 1px solid #e2dac9;
            }
            .login-right-col {
                width: 100% !important;
                padding: 36px 24px !important;
            }
        }
        .login-left-col {
            width: 50%;
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: stretch;
            justify-content: center;
            background: #2e4b77;
            border-right: 1px solid #e5dccb;
        }
        .login-showcase-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
            display: block;
            pointer-events: none;
            user-select: none;
        }
        .login-right-col {
            width: 50%;
            flex: 1;
            background-color: #ffffff;
            padding: 48px 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .login-input {
            width: 100%;
            padding: 15px 18px 15px 48px;
            background-color: #ffffff !important;
            border: 1px solid #e3ddd0;
            border-radius: 14px;
            font-size: 14px;
            color: #24211d;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            box-sizing: border-box;
        }
        .login-input:focus {
            border-color: #a48b48;
            box-shadow: 0 0 0 4px rgba(164, 139, 72, 0.16);
        }
        .login-btn {
            width: 100%;
            padding: 15px 28px;
            background: linear-gradient(180deg, #b89f5d 0%, #a48b48 100%);
            border: 1px solid #9e8542;
            border-radius: 14px;
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 5px 14px rgba(164, 139, 72, 0.32);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        .login-btn:hover {
            filter: brightness(1.05);
            box-shadow: 0 7px 18px rgba(164, 139, 72, 0.42);
            transform: translateY(-1px);
        }
        .login-btn:active {
            transform: translateY(0);
        }
    </style>

    <!-- Split 2-Column Auth Showcase Card -->
    <div class="login-card">

        <!-- Left Column: Showcase Card Framed with Matching Sand Dune Curves Top & Bottom -->
        <div class="login-left-col">
            <img src="{{ asset('img/login-building-full.png') }}?v=2" 
                 alt="Tabasco Hindustan Building" 
                 class="login-showcase-img">
        </div>

        <!-- Right Column: Login Authentication Form -->
        <div class="login-right-col">

            <!-- Form Wrapper Centered -->
            <div style="width: 100%; max-width: 430px; margin: auto; padding: 24px 0;">
                <!-- Tabasco Monogram & Tagline -->
                <div style="text-align: center;">
                    <img src="{{ asset('img/logo-tabasco.png') }}?v=15" alt="Tabasco Human Capital" style="height: 72px; width: auto; margin: 0 auto; object-fit: contain; display: block;">
                    <p style="font-size: 11px; font-weight: 700; color: #a48c34; letter-spacing: 0.25em; text-transform: uppercase; margin-top: 12px; margin-bottom: 0;">
                        BUILD • MANAGE • GROW
                    </p>
                </div>

                <!-- Headline -->
                <div style="text-align: center; margin-top: 24px; margin-bottom: 26px;">
                    <h1 style="font-size: 28px; font-weight: 800; color: #201e1a; letter-spacing: -0.015em; margin: 0;">Welcome Back!</h1>
                    <p style="font-size: 13.5px; color: #7f786a; font-weight: 400; margin: 6px 0 0 0;">Sign in to your account to continue</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div style="margin-bottom: 16px; padding: 12px 16px; border-radius: 12px; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 13px; display: flex; align-items: center; gap: 10px;">
                        <svg style="width: 18px; height: 18px; color: #16a34a; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Authentication Error Alert Banner -->
                @if ($errors->any())
                    <div style="margin-bottom: 16px; padding: 12px 16px; border-radius: 12px; background-color: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; font-size: 13px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 3px;">
                            <svg style="width: 16px; height: 16px; color: #e11d48; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Invalid Username or Password</span>
                        </div>
                        <p style="font-size: 12px; color: #be123c; margin: 0;">
                            {{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first()) }}
                        </p>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address or Username -->
                    <div style="position: relative; margin-bottom: 16px;">
                        <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9c9485; display: flex; align-items: center; pointer-events: none;">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="email"
                               type="text"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Email Address or Username"
                               class="login-input" />
                    </div>

                    <!-- Password -->
                    <div style="position: relative; margin-bottom: 16px;">
                        <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9c9485; display: flex; align-items: center; pointer-events: none;">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Password"
                               class="login-input"
                               style="padding-right: 46px;" />
                        <button type="button"
                                id="togglePassword"
                                onclick="togglePasswordVisibility()"
                                style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; color: #9c9485; cursor: pointer; display: flex; align-items: center;">
                            <svg id="eyeIcon" style="width: 19px; height: 19px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Remember me & Forgot Password -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; margin-bottom: 24px;">
                        <label style="display: inline-flex; align-items: center; cursor: pointer; user-select: none;">
                            <input id="remember_me" type="checkbox" name="remember"
                                   style="width: 17px; height: 17px; border-radius: 4px; border: 1px solid #d4ccbd; accent-color: #a48b48; cursor: pointer;">
                            <span style="margin-left: 9px; font-size: 13px; color: #6e675b; font-weight: 500;">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size: 13px; font-weight: 600; color: #6e675b; text-decoration: none;" onmouseover="this.style.color='#a48b48'" onmouseout="this.style.color='#6e675b'">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-btn">
                        <span>Login</span>
                        <span style="font-size: 16px; font-weight: 700;">→</span>
                    </button>
                </form>
            </div>

            <!-- Footer Copyright -->
            <div style="text-align: center; width: 100%; padding-top: 16px;">
                <p style="font-size: 11.5px; color: #9c9588; font-weight: 400; margin: 0; letter-spacing: 0.01em;">
                    © 2026 Hindustan ERP. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var input = document.getElementById('password');
            var eye = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = 'password';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        window.addEventListener('pageshow', function (event) {
            var navEntries = window.performance && window.performance.getEntriesByType ? window.performance.getEntriesByType('navigation') : [];
            var isBackForward = navEntries.length > 0 && navEntries[0].type === 'back_forward';
            if (event.persisted || isBackForward) {
                window.location.reload();
            }
        });
    </script>
</x-guest-layout>
