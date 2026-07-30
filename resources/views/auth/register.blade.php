<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — One Health</title>
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

            {{-- Benefits --}}
            <div class="panel-illustration">
                <div class="panel-steps">
                    <h3>Join thousands of patients<br>who trust One Health</h3>

                    <div class="step-item">
                        <div class="step-num">✓</div>
                        <div class="step-text">
                            <strong>Free to Register</strong>
                            <span>Create your account in under 2 minutes with no fees.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-num">✓</div>
                        <div class="step-text">
                            <strong>Book Instantly</strong>
                            <span>Browse doctors and book appointments right away.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-num">✓</div>
                        <div class="step-text">
                            <strong>Track Everything</strong>
                            <span>View your appointments, notes, and health history.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-num">✓</div>
                        <div class="step-text">
                            <strong>Stay Updated</strong>
                            <span>Get real-time status updates on your appointments.</span>
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
                        <strong>Your Privacy Matters</strong><br>
                        Your personal data is encrypted and never shared.
                    </p>
                </div>
            </div>
        </aside>

        {{-- ── Right Form Panel ── --}}
        <main class="auth-form-wrap">
            <div class="auth-form-box">

                <div class="form-header">
                    <span class="eyebrow">New Account</span>
                    <h1>Create your account</h1>
                    <p>Start your journey to better health today — it's completely free.</p>
                </div>

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

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Role selector (shown first so user knows what they're registering as) --}}
                    <div class="field-group">
                        <label for="role">I am registering as</label>
                        <div class="field-wrap is-select">
                            <span class="field-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <select id="role" name="role" required
                                onchange="toggleSpecialization(this.value)"
                                class="{{ $errors->has('role') ? 'is-invalid' : '' }}">
                                <option value="">— Select role —</option>
                                <option value="patient" {{ old('role') === 'patient' ? 'selected' : '' }}>
                                    🏥 Patient — I want to book appointments
                                </option>
                                <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>
                                    👨‍⚕️ Doctor — I want to manage my practice
                                </option>
                            </select>
                        </div>
                        @error('role')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div class="field-group">
                        <label for="name">Full Name</label>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <input id="name" type="text" name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. Chan Vathanak"
                                required autofocus autocomplete="name"
                                class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                        </div>
                        @error('name')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="field-group">
                        <label for="email">Email Address</label>
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
                                required autocomplete="username"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        </div>
                        @error('email')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="field-group">
                        <label for="phone">Phone Number</label>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </span>
                            <input id="phone" type="text" name="phone"
                                value="{{ old('phone') }}"
                                placeholder="012 345 678"
                                autocomplete="tel"
                                class="{{ $errors->has('phone') ? 'is-invalid' : '' }}">
                        </div>
                        @error('phone')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Specialization (doctor only) --}}
                    <div class="field-group" id="specialization-field"
                        style="display:{{ old('role') === 'doctor' ? 'block' : 'none' }}">
                        <label for="specialization">Specialization</label>
                        <div class="field-wrap is-select">
                            <span class="field-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            <select id="specialization" name="specialization"
                                class="{{ $errors->has('specialization') ? 'is-invalid' : '' }}">
                                <option value="">— Select specialization —</option>
                                @foreach([
                                'General Health',
                                'Cardiology',
                                'Dental',
                                'Neurology',
                                'Orthopaedics',
                                'Dermatology',
                                'Pediatrics',
                                'Psychiatry',
                                'Ophthalmology',
                                ] as $s)
                                <option value="{{ $s }}" {{ old('specialization') === $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('specialization')
                        <span class="field-error">{{ $message }}</span>
                        @enderror

                        {{-- Doctor notice --}}
                        <div style="margin-top:.65rem;padding:.65rem 1rem;background:rgba(26,138,110,.06);
                                border:1px solid rgba(26,138,110,.2);border-radius:8px;
                                font-size:.8rem;color:#12705a;line-height:1.5">
                            ℹ️ Doctor accounts require admin approval before you can log in.
                        </div>
                    </div>

                    {{-- Password row --}}
                    <div class="fields-row">
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
                                    placeholder="Min 8 chars"
                                    required autocomplete="new-password"
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

                        <div class="field-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="field-wrap">
                                <span class="field-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                <input id="password_confirmation" type="password"
                                    name="password_confirmation"
                                    placeholder="Repeat password"
                                    required autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Create Account
                    </button>

                </form>

                <p class="form-footer">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign in</a>
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
        function toggleSpecialization(role) {
            const field = document.getElementById('specialization-field');
            const select = document.getElementById('specialization');
            field.style.display = role === 'doctor' ? 'block' : 'none';
            if (role !== 'doctor') select.value = '';
        }

        function togglePw(btn) {
            const input = btn.closest('.field-wrap').querySelector('input');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>

</html>