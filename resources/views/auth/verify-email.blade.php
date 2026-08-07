<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email — One Health</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:#f9fafb;min-height:100vh;
             display:flex;align-items:center;justify-content:center;padding:2rem}
        .card{background:#fff;border-radius:16px;padding:3rem 2.5rem;max-width:480px;
              width:100%;box-shadow:0 4px 24px rgba(0,0,0,.08);text-align:center}
        .icon{width:72px;height:72px;border-radius:50%;background:rgba(26,138,110,.1);
              display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem}
        h1{font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:.65rem}
        p{font-size:.9rem;color:#6b7280;line-height:1.6;margin-bottom:1.5rem}
        .btn{display:inline-flex;align-items:center;gap:.45rem;padding:.7rem 1.75rem;
             background:#1A8A6E;color:#fff;border:none;border-radius:10px;font-size:.9rem;
             font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;transition:opacity .2s}
        .btn:hover{opacity:.88}
        .btn-outline{background:transparent;color:#1A8A6E;border:1.5px solid #1A8A6E;margin-left:.5rem}
        .alert{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;
               padding:.75rem 1rem;font-size:.85rem;color:#15803d;margin-bottom:1.5rem}
        .email-highlight{font-weight:600;color:#1A8A6E}
        .logo{font-size:1.1rem;font-weight:700;color:#111827;margin-bottom:2rem;display:block;text-decoration:none}
        .logo span{color:#1A8A6E}
    </style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo"><span>One</span>-Health</a>

    <div class="icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="#1A8A6E" stroke-width="1.8" style="width:36px;height:36px">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </div>

    <h1>Verify your email</h1>

    <p>
        We sent a verification link to
        <span class="email-highlight">{{ Auth::user()->email }}</span>.
        Please check your inbox and click the link to activate your account.
    </p>

    @if(session('status') === 'verification-link-sent')
    <div class="alert">✅ A new verification link has been sent to your email address.</div>
    @endif

    <div style="display:flex;justify-content:center;flex-wrap:wrap;gap:.5rem">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline">Log Out</button>
        </form>
    </div>

    <p style="margin-top:1.5rem;font-size:.8rem;color:#9ca3af">
        Didn't receive the email? Check your spam folder or click resend above.
    </p>
</div>
</body>
</html>
