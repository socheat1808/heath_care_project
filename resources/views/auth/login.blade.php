<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - one Healthcare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <script src="{{ asset('assets/js/auth.js') }}"></script>
</head>

<body>
    <div class="auth-wrap">

        <!-- LEFT PANEL (unchanged) -->
        <aside class="auth-panel">
            ...
        </aside>

        <!-- RIGHT FORM PANEL -->
        <main class="auth-form-wrap">
            <div class="auth-form-box">

                <div class="form-header">
                    <span class="eyebrow">Patient Portal</span>
                    <h1>Welcome back</h1>
                    <p>Sign in to access your health dashboard and appointments.</p>
                </div>

                <!-- ✅ Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                <!-- ✅ Global Errors -->
                @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="field-group">
                        <x-input-label for="email" :value="__('Email')" />

                        <div class="field-wrap">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        </div>

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="field-group mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <div class="field-wrap">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember -->
                    <div class="form-meta">
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-submit">
                        {{ __('Log in') }}
                    </button>

                </form>

                <p class="form-footer">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one</a>
                </p>

            </div>
        </main>

    </div>
</body>

</html>