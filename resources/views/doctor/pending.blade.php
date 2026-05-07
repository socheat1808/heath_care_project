<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="refresh" content="30"> {{-- auto-refresh every 30s --}}
    <title>Account Pending — One-Health</title>
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
            --green-light: rgba(26, 138, 110, .08);
            --amber: #D97706;
            --amber-light: rgba(217, 119, 6, .08);
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
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(26, 138, 110, .07) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(217, 119, 6, .06) 0%, transparent 70%);
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
            background: var(--amber-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.75rem;
            animation: pulse 2.5s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(217, 119, 6, .25);
            }

            50% {
                box-shadow: 0 0 0 16px rgba(217, 119, 6, 0);
            }
        }

        .icon-wrap svg {
            width: 38px;
            height: 38px;
            color: var(--amber);
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

        .steps {
            background: var(--green-light);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            text-align: left;
            margin-bottom: 2rem;
        }

        .steps-title {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--green);
            margin-bottom: .75rem;
        }

        .step-row {
            display: flex;
            gap: .75rem;
            align-items: flex-start;
            margin-bottom: .6rem;
        }

        .step-row:last-child {
            margin-bottom: 0;
        }

        .step-num {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .step-text {
            font-size: .85rem;
            color: var(--text);
            line-height: 1.5;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.75rem;
            border-radius: 10px;
            background: var(--green);
            color: #fff;
            font-size: .875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
            font-family: inherit;
        }

        .btn-logout:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        .btn-logout svg {
            width: 16px;
            height: 16px;
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1>Awaiting Approval</h1>
        <p>
            Welcome, <strong>{{ Auth::user()->name }}</strong>!<br>
            Your doctor account is under review. This page refreshes every 30 seconds automatically.
            Once approved, log back in to access your dashboard.
        </p>

        <div class="steps">
            <div class="steps-title">What happens next?</div>
            <div class="step-row">
                <div class="step-num">1</div>
                <div class="step-text">Admin reviews your registration and credentials.</div>
            </div>
            <div class="step-row">
                <div class="step-num">2</div>
                <div class="step-text">Your account status will be updated to approved.</div>
            </div>
            <div class="step-row">
                <div class="step-num">3</div>
                <div class="step-text">Log back in to access your full doctor dashboard.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign out
            </button>
        </form>

        <div class="footer-note">
            Need help? <a href="mailto:support@onehealth.com">support@onehealth.com</a>
        </div>

    </div>
</body>

</html>