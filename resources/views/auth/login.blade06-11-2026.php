<x-guest-layout :cardless="true">
    <style>
        body {
            background-image: url('{{ asset('img/login-bg.jpg') }}?v=1') !important;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
            min-height: 100vh;
        }

        /* Login Card Container matching Second Image */
        .login-card {
            width: 100%;
            max-width: 1040px;
            background: #ffffff;
            border-radius: 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.65);
            overflow: hidden;
            display: flex;
            flex-direction: row;
            min-height: 610px;
            position: relative;
        }

        /* Left Column: Project Showcase Image */
        .login-left-col {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 53%;
            overflow: hidden;
            display: flex;
            align-items: stretch;
            justify-content: center;
            background: #1a2536;
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

        /* Right Column: White Login Form Area */
        .login-right-col {
            position: relative;
            width: 53%;
            margin-left: auto;
            min-height: 610px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            z-index: 2;
        }

        /* SVG S-Curve Divider with Rich Golden Ribbon */
        .login-curve-divider {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        .login-curve-svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Form Content Inner Wrapper */
        .login-form-inner {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 410px;
            margin: auto;
            padding: 36px 36px 24px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            height: 100%;
        }

        /* Responsive Behavior for Tablets & Mobile */
        @media (max-width: 860px) {
            .login-card {
                flex-direction: column;
                max-width: 460px;
                min-height: auto;
                border-radius: 28px;
            }
            .login-left-col {
                position: relative !important;
                width: 100% !important;
                height: 250px !important;
            }
            .login-curve-divider {
                display: none !important;
            }
            .login-right-col {
                width: 100% !important;
                margin-left: 0 !important;
                background-color: #ffffff !important;
                min-height: auto !important;
            }
            .login-form-inner {
                max-width: 100% !important;
                padding: 32px 24px 24px !important;
            }
        }

        /* Input Controls with Comfortable Vertical Padding & Warm Gold Focus Outline (#b8860b) */
        .login-input {
            width: 100%;
            padding: 15.5px 18px 15.5px 48px;
            background-color: #ffffff !important;
            border: 1.5px solid #ded6c5;
            border-radius: 14px;
            font-size: 14px;
            color: #24211d;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            box-sizing: border-box;
        }
        .login-input::placeholder {
            color: #a49d8e;
            font-weight: 400;
        }
        .login-input:focus {
            border-color: #b8860b;
            box-shadow: 0 0 0 4px rgba(184, 134, 11, 0.22);
        }
        .login-input.has-error {
            border-color: #e11d48 !important;
            background-color: #fffafb !important;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.12) !important;
        }
        .login-input.has-error:focus {
            border-color: #be123c !important;
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.2) !important;
        }

        /* Error Label */
        .login-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #e11d48;
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
            padding-left: 4px;
            animation: fadeInError 0.2s ease;
        }
        @keyframes fadeInError {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Gold Gradient Action Button with Subtle Warm Glow Shadow */
        .login-btn {
            width: 100%;
            padding: 15px 24px;
            background: linear-gradient(90deg, #d39a22 0%, #b58012 100%);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 10px 25px -3px rgba(184, 134, 11, 0.45), 0 4px 12px rgba(184, 134, 11, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.25s ease;
            box-sizing: border-box;
        }
        .login-btn:hover {
            filter: brightness(1.06);
            box-shadow: 0 14px 30px -4px rgba(184, 134, 11, 0.55), 0 6px 16px rgba(184, 134, 11, 0.35);
            transform: translateY(-1px);
        }
        .login-btn:active {
            transform: translateY(0);
        }
        .login-btn-icon-right {
            position: absolute;
            right: 20px;
            display: flex;
            align-items: center;
        }
    </style>

    <!-- Split 2-Column Auth Showcase Card with Golden Wave Divider -->
    <div class="login-card">

        <!-- Left Column: Project Showcase (First Image) -->
        <div class="login-left-col">
            <img src="{{ asset('img/login-project.jpg') }}?v=1" 
                 alt="Tabasco Hindustan Project" 
                 class="login-showcase-img">
        </div>

        <!-- Right Column: Login Authentication Form with S-Curve Divider -->
        <div class="login-right-col">

            <!-- SVG S-Curve Divider with Metallic Gold Ribbon Stroke & Shadow -->
            <div class="login-curve-divider" aria-hidden="true">
                <svg viewBox="0 0 530 640" preserveAspectRatio="none" class="login-curve-svg">
                    <defs>
                        <linearGradient id="goldRibbonGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#b88314" />
                            <stop offset="18%" stop-color="#ddae3a" />
                            <stop offset="45%" stop-color="#fceda6" />
                            <stop offset="65%" stop-color="#ddae3a" />
                            <stop offset="100%" stop-color="#9a6e10" />
                        </linearGradient>
                        <filter id="goldCurveShadow" x="-30%" y="-20%" width="160%" height="140%">
                            <feDropShadow dx="-4" dy="0" stdDeviation="5" flood-color="rgba(0,0,0,0.32)" />
                        </filter>
                    </defs>
                    <!-- White panel background with curved left boundary -->
                    <path d="M 44,0 C 48,90 34,190 18,310 C 2,420 8,510 26,580 C 34,605 38,625 40,640 L 530,640 L 530,0 Z" fill="#ffffff" />
                    <!-- Golden S-Curve Ribbon along the boundary -->
                    <path d="M 44,0 C 48,90 34,190 18,310 C 2,420 8,510 26,580 C 34,605 38,625 40,640" fill="none" stroke="url(#goldRibbonGrad)" stroke-width="8" stroke-linecap="round" filter="url(#goldCurveShadow)" />
                </svg>
            </div>

            <!-- Form Wrapper Centered Inside White Panel -->
            <div class="login-form-inner">
                <div>
                    <!-- Tabasco Monogram & Tagline -->
                    <div style="text-align: center; margin-bottom: 2px;">
                        <img src="{{ asset('img/logo-tabasco.png') }}?v=16" alt="Tabasco Human Capital" style="height: 68px; width: auto; margin: 0 auto; object-fit: contain; display: block;">
                        <p style="font-size: 10.5px; font-weight: 700; color: #b8861e; letter-spacing: 0.28em; text-transform: uppercase; margin-top: 10px; margin-bottom: 0;">
                            BUILD • MANAGE • GROW
                        </p>
                    </div>

                    <!-- Headline -->
                    <div style="text-align: center; margin-top: 16px; margin-bottom: 20px;">
                        <h1 style="font-size: 26px; font-weight: 800; color: #1e1b18; letter-spacing: -0.015em; margin: 0;">Welcome Back!</h1>
                        <p style="font-size: 13px; color: #7f786a; font-weight: 400; margin: 5px 0 0 0;">Sign in to your account to continue</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div style="margin-bottom: 14px; padding: 10px 14px; border-radius: 12px; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 12.5px; display: flex; align-items: center; gap: 8px;">
                            <svg style="width: 16px; height: 16px; color: #16a34a; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Authentication Error Alert Banner -->
                    @if ($errors->any())
                        <div style="margin-bottom: 14px; padding: 10px 14px; border-radius: 12px; background-color: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; font-size: 12.5px;">
                            <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 2px;">
                                <svg style="width: 15px; height: 15px; color: #e11d48; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Invalid Username or Password</span>
                            </div>
                            <p style="font-size: 11.5px; color: #be123c; margin: 0;">
                                {{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first()) }}
                            </p>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <!-- Email Address or Username -->
                        <div style="position: relative; margin-bottom: 14px;">
                            <div style="position: relative;">
                                <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #b8860b; display: flex; align-items: center; pointer-events: none;">
                                    <svg style="width: 18px; height: 18px;" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="email"
                                       type="text"
                                       name="email"
                                       value="{{ old('email') }}"
                                       autofocus
                                       autocomplete="username"
                                       placeholder="name@hindustan.com"
                                       class="login-input" />
                            </div>
                            <div id="email-error" class="login-field-error" style="display: none;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span class="error-msg-content">Please enter your email address or username.</span>
                            </div>
                        </div>

                        <!-- Password -->
                        <div style="position: relative; margin-bottom: 14px;">
                            <div style="position: relative;">
                                <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #b8860b; display: flex; align-items: center; pointer-events: none;">
                                    <svg style="width: 18px; height: 18px;" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       autocomplete="current-password"
                                       placeholder="••••••••"
                                       class="login-input"
                                       style="padding-right: 46px;" />
                                <button type="button"
                                        id="togglePassword"
                                        onclick="togglePasswordVisibility()"
                                        style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; color: #8e8574; cursor: pointer; display: flex; align-items: center;">
                                    <svg id="eyeIcon" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div id="password-error" class="login-field-error" style="display: none;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span class="error-msg-content">Please enter your password.</span>
                            </div>
                        </div>

                        <!-- Remember me & Forgot Password -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 6px; margin-bottom: 22px;">
                            <label style="display: inline-flex; align-items: center; cursor: pointer; user-select: none;">
                                <input id="remember_me" type="checkbox" name="remember"
                                       style="width: 16px; height: 16px; border-radius: 4px; border: 1.5px solid #c8bcab; accent-color: #b8860b; cursor: pointer;">
                                <span style="margin-left: 8px; font-size: 13px; color: #5c564b; font-weight: 500;">Remember me</span>
                            </label>
                            <!-- @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size: 13px; font-weight: 600; color: #b8860b; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    Forgot Password?
                                </a>
                            @endif -->
                        </div>

                        <!-- Login Button matching Second Image -->
                        <button type="submit" class="login-btn">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4-4-4m4 4H3m8-8h4a2 2 0 012 2v12a2 2 0 01-2 2h-4" />
                                </svg>
                                <span>LOGIN</span>
                            </div>
                            <span class="login-btn-icon-right">
                                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Bottom Area: Skyline Motif & Copyright with Extra Breathing Room -->
                <div style="margin-top: 28px; padding-bottom: 6px;">
                    <!-- Golden Skyline Motif matching Second Image -->
                    <div style="text-align: center; margin-bottom: 12px;">
                        <svg style="width: 44px; height: 18px; margin: 0 auto; color: #b8860b;" viewBox="0 0 60 24" fill="currentColor">
                            <rect x="2" y="14" width="6" height="10" rx="1" />
                            <rect x="10" y="8" width="7" height="16" rx="1" />
                            <rect x="19" y="12" width="6" height="12" rx="1" />
                            <rect x="27" y="4" width="8" height="20" rx="1" />
                            <polygon points="31,0 27,4 35,4" />
                            <rect x="37" y="10" width="6" height="14" rx="1" />
                            <rect x="45" y="6" width="7" height="18" rx="1" />
                            <rect x="54" y="14" width="4" height="10" rx="1" />
                        </svg>
                    </div>

                    <!-- Footer Copyright -->
                    <div style="text-align: center; width: 100%;">
                        <p style="font-size: 11.5px; color: #948b7c; font-weight: 400; margin: 0; letter-spacing: 0.01em;">
                            © 2026 Hindustan ERP. All rights reserved.
                        </p>
                    </div>
                </div>
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

        // Custom Form Validation (Suppresses default browser validation tooltips)
        document.getElementById('loginForm')?.addEventListener('submit', function (e) {
            var emailInput = document.getElementById('email');
            var passwordInput = document.getElementById('password');
            var emailError = document.getElementById('email-error');
            var passwordError = document.getElementById('password-error');
            var isValid = true;

            // Validate email or username
            if (!emailInput.value.trim()) {
                isValid = false;
                emailInput.classList.add('has-error');
                if (emailError) {
                    var span = emailError.querySelector('.error-msg-content');
                    if (span) span.textContent = 'Please enter your email address or username.';
                    emailError.style.display = 'flex';
                }
            } else {
                emailInput.classList.remove('has-error');
                if (emailError) emailError.style.display = 'none';
            }

            // Validate password
            if (!passwordInput.value) {
                isValid = false;
                passwordInput.classList.add('has-error');
                if (passwordError) {
                    var span = passwordError.querySelector('.error-msg-content');
                    if (span) span.textContent = 'Please enter your password.';
                    passwordError.style.display = 'flex';
                }
            } else {
                passwordInput.classList.remove('has-error');
                if (passwordError) passwordError.style.display = 'none';
            }

            if (!isValid) {
                e.preventDefault();
                if (!emailInput.value.trim()) {
                    emailInput.focus();
                } else if (!passwordInput.value) {
                    passwordInput.focus();
                }
            }
        });

        // Real-time error dismissal upon typing
        document.getElementById('email')?.addEventListener('input', function () {
            if (this.value.trim()) {
                this.classList.remove('has-error');
                var err = document.getElementById('email-error');
                if (err) err.style.display = 'none';
            }
        });

        document.getElementById('password')?.addEventListener('input', function () {
            if (this.value) {
                this.classList.remove('has-error');
                var err = document.getElementById('password-error');
                if (err) err.style.display = 'none';
            }
        });

        window.addEventListener('pageshow', function (event) {
            var navEntries = window.performance && window.performance.getEntriesByType ? window.performance.getEntriesByType('navigation') : [];
            var isBackForward = navEntries.length > 0 && navEntries[0].type === 'back_forward';
            if (event.persisted || isBackForward) {
                window.location.reload();
            }
        });
    </script>
</x-guest-layout>
