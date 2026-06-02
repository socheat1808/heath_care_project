@extends('patient.layout')
@section('title', 'Dashboard')
@section('content')

@php
$hour = now()->hour;
$greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening' );
    $user=Auth::user();
    @endphp

    {{-- ── Page Header ── --}}
    <div class="page-header">
    <div>
        <h1>{{ $greet }}, {{ $user->name }} 👋</h1>
        <p>Here's your health overview for today, {{ now()->format('l, d M Y') }}.</p>
    </div>
    <a href="{{ route('patient.appointments.find-doctors') }}" class="btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Book Appointment
    </a>
    </div>

    {{-- ── Flash ── --}}
    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;font-size:.875rem;color:#15803d;display:flex;align-items:center;gap:.65rem">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="stat-trend up">Total</span>
            </div>
            <div class="stat-value">{{ $totalAppointments }}</div>
            <div class="stat-label">Total Appointments</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-trend up">Approved</span>
            </div>
            <div class="stat-value">{{ $upcomingCount }}</div>
            <div class="stat-label">Upcoming Visits</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon amber">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <span class="stat-trend down">Waiting</span>
            </div>
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Awaiting Approval</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon purple">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-trend up">Done</span>
            </div>
            <div class="stat-value">{{ $completedCount }}</div>
            <div class="stat-label">Completed Visits</div>
        </div>

    </div>

    {{-- ── Bottom Grid ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">

        {{-- Upcoming appointments --}}
        <div class="chart-card" style="margin-bottom:0">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.1rem">
                <div>
                    <div class="card-title">Upcoming Appointments</div>
                    <div class="card-subtitle">Your next scheduled visits</div>
                </div>
                <a href="{{ route('patient.appointments.index') }}"
                    style="font-size:.78rem;color:var(--green);font-weight:600;text-decoration:none">View all →</a>
            </div>

            @forelse($upcomingList as $appt)
            <div style="display:flex;align-items:center;gap:.85rem;padding:.75rem 0;border-bottom:1px solid var(--border)">
                <div style="width:38px;height:38px;border-radius:50%;background:var(--green);color:#fff;
                        display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($appt->doctor->first_name ?? 'D',0,1).substr($appt->doctor->last_name ?? 'R',0,1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.875rem;font-weight:600;color:var(--text);
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '—' }}
                    </div>
                    <div style="font-size:.76rem;color:var(--text-muted)">
                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                        · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                    </div>
                </div>
                @php
                $bc = match($appt->status) {
                'approved' => 'badge-green',
                'pending' => 'badge-amber',
                default => 'badge-blue',
                };
                @endphp
                <span class="badge {{ $bc }}">{{ ucfirst($appt->status) }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:.875rem">
                <div style="font-size:32px;margin-bottom:.5rem">📅</div>
                No upcoming appointments.
            </div>
            @endforelse

            <a href="{{ route('patient.appointments.find-doctors') }}"
                style="display:flex;align-items:center;justify-content:center;gap:.4rem;
                  margin-top:1rem;padding:.6rem;border:1px dashed var(--border);border-radius:10px;
                  color:var(--text-muted);text-decoration:none;font-size:.83rem;font-weight:600"
                onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Book another appointment
            </a>
        </div>

        {{-- Right column --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem">

            {{-- Profile summary --}}
            <div class="chart-card" style="margin-bottom:0">
                <div class="card-title" style="margin-bottom:1rem">My Profile</div>

                <div style="display:flex;align-items:center;gap:.85rem;margin-bottom:1rem;
                        padding:1rem;background:var(--bg);border-radius:12px">
                    <div style="width:48px;height:48px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($user->name,0,2)) }}
                    </div>
                    <div>
                        <div style="font-size:.95rem;font-weight:700;color:var(--text)">{{ $user->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted)">{{ $user->email }}</div>
                        <span class="badge badge-green" style="margin-top:.3rem">● Patient</span>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.83rem">
                    <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                        <span style="color:var(--text-muted)">Phone</span>
                        <span style="font-weight:600;color:var(--text)">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                        <span style="color:var(--text-muted)">Member since</span>
                        <span style="font-weight:600;color:var(--text)">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.45rem 0">
                        <span style="color:var(--text-muted)">Total visits</span>
                        <span style="font-weight:600;color:var(--text)">{{ $completedCount }}</span>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}"
                    class="btn-primary"
                    style="display:flex;justify-content:center;margin-top:1.1rem;text-decoration:none">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profile
                </a>
            </div>

            {{-- Recent activity --}}
            <div class="chart-card" style="margin-bottom:0">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                    <div class="card-title">Recent Activity</div>
                    <a href="{{ route('patient.appointments.index') }}"
                        style="font-size:.78rem;color:var(--green);font-weight:600;text-decoration:none">See all →</a>
                </div>

                @forelse($recentActivity as $appt)
                @php
                $icon = match($appt->status) {
                'approved' => ['✅', '#f0fdf4'],
                'pending' => ['⏳', '#fffbeb'],
                'completed' => ['🏥', '#f5f3ff'],
                'cancelled' => ['❌', '#fef2f2'],
                'rejected' => ['🚫', '#fef2f2'],
                default => ['📋', 'var(--bg)'],
                };
                @endphp
                <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.65rem 0;border-bottom:1px solid var(--border)">
                    <div style="width:32px;height:32px;border-radius:8px;background:{{ $icon[1] }};
                            display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">
                        {{ $icon[0] }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.83rem;font-weight:600;color:var(--text)">
                            {{ ucfirst($appt->status) }} — Dr. {{ $appt->doctor->last_name ?? '—' }}
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted)">
                            {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                            · {{ $appt->department ?? 'General' }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:1.5rem;color:var(--text-muted);font-size:.83rem">
                    No recent activity.
                </div>
                @endforelse
            </div>

        </div>
    </div>

    @endsection