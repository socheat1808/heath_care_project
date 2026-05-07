<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — One-Health</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @stack('admin')
</head>

<body>

    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- ══════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════ -->
    <aside class="sidebar" id="sidebar">

        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}">
                <div class="logo-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="logo-text"><span>One</span>-Health</span>
                <span class="logo-badge">Admin</span>
            </a>
        </div>

        <!-- ── Overview ── -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">Overview</div>

            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                Dashboard
            </a>
        </div>

        <!-- ── Management ── -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">Management</div>

            <!-- Patients -->
            <a href="{{ route('admin.patients.index') }}"
                class="nav-item {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                Patients
            </a>

            <!-- Doctors -->
            <a href="{{ route('admin.doctors.index') }}"
                class="nav-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM12 14v1m0 4h.01" />
                    </svg>
                </div>
                Doctors
            </a>

            <!-- Doctor Approvals — with live pending badge -->
            <a href="{{ route('admin.doctors.doctor-approvals') }}"
                class="nav-item {{ request()->routeIs('admin.doctors.doctor-approvals*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                Doctor Approvals
                @php
                $pendingCount = \App\Models\User::where('role','doctor')->where('status','pending')->count();
                @endphp
                @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>

            <!-- Appointments -->
            <a href="{{ route('admin.appointments.index') }}"
                class="nav-item {{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                Appointments
            </a>
        </div>

        <!-- ── Content ── -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">Content</div>

            <a href="#"
                class="nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                Blog / News
            </a>
        </div>

        <!-- ── System ── -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">System</div>

            <a href="#" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                Settings
            </a>
        </div>

        <!-- Admin profile + logout -->
        <div class="sidebar-bottom">
            <div class="admin-profile">
                <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="admin-info">
                    <div class="admin-name">{{ Auth::user()->name }}</div>
                    <div class="admin-role">Super Administrator</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:4px;display:flex;align-items:center;opacity:0.5">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- ══════════════════════════════════════
         MAIN
    ══════════════════════════════════════ -->
    <div class="main">

        <!-- TOPBAR -->
        <header class="topbar">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <span class="topbar-title" id="topbarTitle">@yield('title')</span>

            <div class="topbar-search">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Search anything…">
            </div>

            <div class="topbar-actions">
                <!-- Notification bell -->
                <button class="icon-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="notif-dot"></span>
                </button>

                <!-- Messages -->
                <button class="icon-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </button>

                <!-- Admin info + logout -->
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="text-align:right">
                        <div style="font-size:.85rem;font-weight:600;color:#333;">{{ Auth::user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0">
                            @csrf
                            <button type="submit"
                                style="background:none;border:none;padding:0;font-size:.7rem;color:#ef4444;cursor:pointer;font-family:inherit;">
                                Log Out
                            </button>
                        </form>
                    </div>
                    <div style="width:34px;height:34px;border-radius:50%;background:#4f46e5;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        @yield('content')

    </div>{{-- /main --}}

    <script src="{{ asset('assets/vendor/appadmin.js') }}"></script>
    @stack('appadmin')

</body>

</html>