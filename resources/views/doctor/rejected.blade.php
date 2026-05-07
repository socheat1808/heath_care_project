<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account Rejected — One-Health</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green: #1A8A6E;
            --red: #DC2626;
            --red-light: rgba(220, 38, 38, .07);
            --text: #111827;
            --muted: #6B7280;
            --border: #E5E7EB;
            --bg: #F9FAFB;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 10%, rgba(220, 38, 38, .05) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            max-width: 460px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .06);
            animation: rise .5s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.1rem;
            color: var(--green);
            margin-bottom: 2rem;
        }

        .icon-wrap {
            width: 80px;
            height: 80px;
            background: var(--red-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.75rem;
        }

        .icon-wrap svg {
            width: 38px;
            height: 38px;
            color: var(--red);
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.75rem;
            color: var(--text);
            margin-bottom: .75rem;
        }

        p {
            font-size: .9375rem;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        .notice {
            background: var(--red-light);
            border: 1px solid rgba(220, 38, 38, .15);
            border-radius: 12px;
            padding: 1.1rem 1.4rem;
            margin-bottom: 2rem;
            font-size: .875rem;
            color: var(--red);
            line-height: 1.55;
            text-align: left;
        }

        .notice strong {
            display: block;
            margin-bottom: .25rem;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: .75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.5rem;
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        .btn svg {
            width: 15px;
            height: 15px;
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text);
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
        }

        .footer-note {
            margin-top: 1.25rem;
            font-size: .8rem;
            color: var(--muted);
        }

        .footer-note a {
            color: var(--green);
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="card">

        <div class="logo">⚕ One-Health</div>

        <div class="icon-wrap">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1>Application Rejected</h1>
        <p>
            Unfortunately, <strong>{{ Auth::user()->name }}</strong>, your doctor registration
            has not been approved at this time.
        </p>

        <div class="notice">
            <strong>What this means</strong>
            Your account cannot access the doctor dashboard. Please contact our support team
            if you believe this is a mistake or would like to appeal the decision.
        </div>

        <div class="actions">
            <a href="mailto:support@onehealth.com" class="btn btn-outline">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Contact Support
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign out
                </button>
            </form>
        </div>

        <div class="footer-note">
            Questions? <a href="mailto:support@onehealth.com">support@onehealth.com</a>
        </div>

    </div>
</body>

</html>