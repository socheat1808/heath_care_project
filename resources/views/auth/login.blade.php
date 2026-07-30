<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — One Health</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=Playfair+Display:wght@500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body>

    <div class="auth-wrap">

        {{-- ── Left Panel ── --}}
        <aside class="auth-panel">
            <div class="panel-grid"></div>

            {{-- Logo --}}
            <div class="panel-content">
                <a href="{{ route('home') }}" class="panel-logo">
                    <span>One</span>-Health
                </a>
            </div>

            {{-- Steps --}}
            <div class="panel-illustration">
                <div class="panel-steps">
                    <h3>Your health journey<br>starts here</h3>

                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-text">
                            <strong>Book Appointments</strong>
                            <span>Choose from our qualified doctors and book at your convenience.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <strong>Get Confirmed</strong>
                            <span>Your doctor reviews and approves your appointment request.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-text">
                            <strong>Track Your Health</strong>
                            <span>View appointment history, doctor notes, and health records.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-num">4</div>
                        <div class="step-text">
                            <strong>Stay Connected</strong>
                            <span>Get updates on your appointments and health status anytime.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trust badge --}}
            <div class="panel-footer-wrap">
                <div class="panel-trust">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#1a8a6e" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p>
                        <strong>Trusted Healthcare Platform</strong><br>
                        Your data is protected and kept private at all times.
                    </p>
                </div>
            </div>
        </aside>

        {{-- ── Right Form Panel ── --}}
        <main class="auth-form-wrap">
            <div class="auth-form-box">

                <div class="form-header">
                    <span class="eyebrow">Patient Portal</span>
                    <h1>Welcome back</h1>
                    <p>Sign in to access your health dashboard and appointments.</p>
                </div>

                {{-- Session status --}}
                @if(session('status'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;
                        padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.875rem;color:#15803d">
                    {{ session('status') }}
                </div>
                @endif

                {{-- Errors --}}
                @if($errors->any())
                <div class="alert-error">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        style="width:18px;height:18px;flex-shrink:0;margin-top:1px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                        <p style="margin:0 0 2px">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="field-group">
                        <label for="email">Email address</label>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                required autofocus autocomplete="username"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        </div>
                        @error('email')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field-group">
                        <label for="password">Password</label>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password"
                                placeholder="••••••••"
                                required autocomplete="current-password"
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <button type="button" class="toggle-password"
                                onclick="togglePw(this)" aria-label="Show password">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="form-meta">
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </button>

                </form>

                <p class="form-footer">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one free</a>
                </p>

                {{-- Back to home --}}
                <div style="text-align:center;margin-top:1.25rem">
                    <a href="{{ route('home') }}"
                        style="font-size:.83rem;color:var(--gray-400);text-decoration:none;
                           display:inline-flex;align-items:center;gap:.35rem;transition:color .2s"
                        onmouseover="this.style.color='var(--primary)'"
                        onmouseout="this.style.color='var(--gray-400)'">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            style="width:14px;height:14px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to home
                    </a>
                </div>

            </div>
        </main>

    </div>

    <script>
        function togglePw(btn) {
            const input = btn.closest('.field-wrap').querySelector('input');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>

</html>