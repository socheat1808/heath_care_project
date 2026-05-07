@extends('doctor-dashboard.layout')
@section('title', 'Dashboard')
@section('content')

@php
$user = Auth::user();
$doctor = \App\Models\AdminDoctor::where('email', $user->email)->first();
$hour = now()->hour;
$greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening' );
    @endphp

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>{{ $greet }}, Dr. {{ $doctor->first_name ?? $user->name }} 👋</h1>
            <p>Here's your practice overview for today, {{ now()->format('l, d M Y') }}.</p>
        </div>
        <a href="#" class="btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            View Appointments
        </a>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="stat-trend up">Today</span>
            </div>
            <div class="stat-value">{{ $todayAppointments }}</div>
            <div class="stat-label">Today's Appointments</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="stat-trend up">Total</span>
            </div>
            <div class="stat-value">{{ $totalPatients }}</div>
            <div class="stat-label">Total Patients</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon amber">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-trend down">Pending</span>
            </div>
            <div class="stat-value">{{ $pendingAppointments }}</div>
            <div class="stat-label">Pending Appointments</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon purple">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-trend up">This month</span>
            </div>
            <div class="stat-value">{{ $completedThisMonth }}</div>
            <div class="stat-label">Completed This Month</div>
        </div>

    </div>

    <!-- BOTTOM ROW -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">

        <!-- Today's Appointments -->
        <div class="chart-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
                <div>
                    <div class="card-title">Today's Appointments</div>
                    <div class="card-subtitle">{{ now()->format('l, d M Y') }}</div>
                </div>
                <span class="badge badge-green">{{ $todayAppointments }} scheduled</span>
            </div>

            @forelse($todayList as $appt)
            <div style="display:flex;align-items:center;gap:.85rem;padding:.75rem 0;border-bottom:1px solid var(--border)">
                <div style="font-size:.8rem;font-weight:600;color:var(--text-muted);width:44px;flex-shrink:0">
                    {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                </div>
                <div style="width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0"></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.875rem;font-weight:600;color:var(--text)">{{ $appt->patient->name ?? 'Patient' }}</div>
                    <div style="font-size:.78rem;color:var(--text-muted)">{{ $appt->department ?? 'General' }}</div>
                </div>
                <span class="badge {{ $appt->status === 'confirmed' ? 'badge-green' : ($appt->status === 'pending' ? 'badge-amber' : 'badge-red') }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:.875rem">
                <div style="font-size:36px;margin-bottom:.5rem">📅</div>
                No appointments scheduled for today.
            </div>
            @endforelse

            <a href="#"
                style="display:block;text-align:center;margin-top:1rem;font-size:.85rem;color:var(--green);font-weight:600;text-decoration:none">
                View all appointments →
            </a>
        </div>

        <!-- Doctor Profile Summary -->
        <div class="chart-card">
            <div style="margin-bottom:1.25rem">
                <div class="card-title">My Profile</div>
                <div class="card-subtitle">Your professional summary</div>
            </div>

            <!-- Avatar + name -->
            <div style="display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--bg);border-radius:12px;margin-bottom:1.25rem">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--green);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.25rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1).substr($doctor->last_name ?? 'R', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:1rem;font-weight:700;color:var(--text)">
                        Dr. {{ $doctor->first_name ?? '' }} {{ $doctor->last_name ?? $user->name }}
                    </div>
                    <div style="font-size:.8rem;color:var(--text-muted)">{{ $doctor->specialization ?? 'Doctor' }}</div>
                    @if($doctor && $doctor->status === 'available')
                    <span class="badge badge-green" style="margin-top:.3rem">● Available</span>
                    @elseif($doctor && $doctor->status === 'onleave')
                    <span class="badge badge-amber" style="margin-top:.3rem">● On Leave</span>
                    @else
                    <span class="badge badge-red" style="margin-top:.3rem">● Unavailable</span>
                    @endif
                </div>
            </div>

            <!-- Info rows -->
            @if($doctor)
            <div style="display:flex;flex-direction:column;gap:.6rem">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Experience</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->years_of_experience }} years</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Consultation Fee</span>
                    <span style="font-weight:600;color:var(--text)">${{ number_format($doctor->consultation_fee, 0) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Email</span>
                    <span style="font-weight:600;color:var(--text)">{{ $user->email }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0">
                    <span style="color:var(--text-muted)">Phone</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->phone ?? '—' }}</span>
                </div>
            </div>
            @endif

            <a href="{{ route('doctor-dashboard.profile') }}"
                class="btn-primary" style="display:flex;justify-content:center;margin-top:1.25rem;text-decoration:none">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profile
            </a>
        </div>

    </div>

    @endsection