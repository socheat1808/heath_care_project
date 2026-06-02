@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

<div class="page-content active" id="page-dashboard">

  {{-- ── Page Header ── --}}
  <div class="page-header">
    <div class="page-header-left">
      @php
      $hour = now()->hour;
      $greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening' );
        @endphp
        <h1>{{ $greet }}, Admin 👋</h1>
        <p>Here's what's happening at One-Health today, {{ now()->format('l, d F Y') }}.</p>
    </div>
    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      New Appointment
    </a>
  </div>
  {{-- ── Pending Appointments Alert ── --}}
  @php
  $newPending = \App\Models\Appointment::where('status', 'pending')
  ->whereDate('created_at', today())
  ->count();
  @endphp
  @if($newPending > 0)
  <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;
            padding:1rem 1.25rem;margin-bottom:1.25rem;
            display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div style="display:flex;align-items:center;gap:.75rem">
      <div style="font-size:1.5rem">🔔</div>
      <div>
        <div style="font-size:.9rem;font-weight:700;color:#92400e">
          {{ $newPending }} new appointment{{ $newPending > 1 ? 's' : '' }} today
        </div>
        <div style="font-size:.8rem;color:#b45309">
          Patients are waiting for approval
        </div>
      </div>
    </div>
    <a href="{{ route('admin.appointments.index') }}?status=pending"
      class="btn btn-primary"
      style="font-size:.83rem;padding:.45rem 1rem;text-decoration:none">
      Review Now →
    </a>
  </div>
  @endif

  {{-- ── Stat Cards ── --}}
  <div class="stats-grid">

    <div class="stat-card c-green">
      <div class="stat-card-top">
        <div class="stat-icon green">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <span class="stat-trend up">Total</span>
      </div>
      <div>
        <div class="stat-value">{{ number_format($totalPatients) }}</div>
        <div class="stat-label">Total Patients</div>
      </div>
    </div>

    <div class="stat-card c-blue">
      <div class="stat-card-top">
        <div class="stat-icon blue">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <span class="stat-trend up">{{ now()->format('M Y') }}</span>
      </div>
      <div>
        <div class="stat-value">{{ number_format($monthAppointments) }}</div>
        <div class="stat-label">Appointments This Month</div>
      </div>
    </div>

    <div class="stat-card c-amber">
      <div class="stat-card-top">
        <div class="stat-icon amber">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <span class="stat-trend down">Waiting</span>
      </div>
      <div>
        <div class="stat-value">{{ number_format($pendingApprovals) }}</div>
        <div class="stat-label">Pending Doctor Approvals</div>
      </div>
    </div>

    <div class="stat-card c-purple">
      <div class="stat-card-top">
        <div class="stat-icon purple">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <span class="stat-trend up">Active</span>
      </div>
      <div>
        <div class="stat-value">{{ number_format($totalDoctors) }}</div>
        <div class="stat-label">Active Doctors</div>
      </div>
    </div>

  </div>

  {{-- ── Bottom Row ── --}}
  <div class="bottom-row">

    {{-- Today's Appointments --}}
    <div class="table-card">
      <div class="table-header">
        <div>
          <div class="table-title">Today's Appointments</div>
          <div class="table-subtitle">{{ now()->format('l, d F Y') }}</div>
        </div>
        <span class="badge badge-green">{{ $todayAppointments->count() }} scheduled</span>
      </div>

      <div class="appt-list">
        @forelse($todayAppointments as $appt)
        @php
        $dotColor = match($appt->status) {
        'approved' => 'var(--green)',
        'pending' => 'var(--amber)',
        'completed' => 'var(--blue)',
        'cancelled' => 'var(--red)',
        default => 'var(--green)',
        };
        $badgeClass = match($appt->status) {
        'approved' => 'badge-green',
        'pending' => 'badge-amber',
        'completed' => 'badge-blue',
        'cancelled' => 'badge-red',
        default => 'badge-green',
        };
        @endphp
        <div class="appt-item">
          <div class="appt-time">
            {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
          </div>
          <div class="appt-dot" style="background: {{ $dotColor }}"></div>
          <div class="appt-info">
            <div class="appt-name">{{ $appt->patient->name ?? '—' }}</div>
            <div class="appt-dept">
              {{ $appt->department ?? 'General' }}
              — Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '—' }}
            </div>
          </div>
          <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:#6b7280;font-size:.875rem">
          <div style="font-size:36px;margin-bottom:.5rem">📅</div>
          No appointments scheduled for today.
        </div>
        @endforelse
      </div>

      <a href="{{ route('admin.appointments.index') }}"
        style="display:block;text-align:center;margin-top:1rem;font-size:.83rem;
                color:var(--green);font-weight:600;text-decoration:none">
        View all appointments →
      </a>
    </div>

    {{-- Doctor Availability --}}
    <div class="table-card">
      <div class="table-header">
        <div>
          <div class="table-title">Doctor Availability</div>
          <div class="table-subtitle">Today's schedule load</div>
        </div>
      </div>

      <div>
        @forelse($doctors as $doctor)
        @php
        $maxSlots = 10; // adjust based on your schedule
        $pct = $maxSlots > 0 ? min(100, round(($doctor->today_count / $maxSlots) * 100)) : 0;
        $barColor = $pct >= 80 ? 'var(--red)' : ($pct >= 50 ? 'var(--amber)' : 'var(--green)');
        $initials = strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1));
        @endphp
        <div class="avail-row">
          <div style="width:36px;height:36px;border-radius:50%;background:var(--green);
                      color:#fff;display:flex;align-items:center;justify-content:center;
                      font-size:.75rem;font-weight:700;flex-shrink:0">
            {{ $initials }}
          </div>
          <div class="avail-info">
            <div class="avail-name">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>
            <div class="avail-dept">{{ $doctor->specialization }}</div>
          </div>
          <div class="avail-bar-wrap">
            <div class="avail-bar-bg">
              <div class="avail-bar" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
            </div>
            <div class="avail-pct">{{ $pct }}% full</div>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:#6b7280;font-size:.875rem">
          No doctors available today.
        </div>
        @endforelse
      </div>

      <a href="{{ route('admin.doctors.index') }}"
        style="display:block;text-align:center;margin-top:1rem;font-size:.83rem;
                color:var(--green);font-weight:600;text-decoration:none">
        View all doctors →
      </a>
    </div>

  </div>

  {{-- ── Recent Appointments ── --}}
  <div class="table-card" style="margin-top:1.25rem">
    <div class="table-header">
      <div>
        <div class="table-title">Recent Appointments</div>
        <div class="table-subtitle">Latest booking activity</div>
      </div>
      <a href="{{ route('admin.appointments.index') }}" class="badge badge-green"
        style="text-decoration:none;cursor:pointer">View all</a>
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:.875rem">
      <thead>
        <tr style="border-bottom:2px solid #e5e7eb">
          <th style="text-align:left;padding:.65rem 1rem;font-size:.75rem;font-weight:700;
                     color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Patient</th>
          <th style="text-align:left;padding:.65rem 1rem;font-size:.75rem;font-weight:700;
                     color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Doctor</th>
          <th style="text-align:left;padding:.65rem 1rem;font-size:.75rem;font-weight:700;
                     color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Date</th>
          <th style="text-align:left;padding:.65rem 1rem;font-size:.75rem;font-weight:700;
                     color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Department</th>
          <th style="text-align:center;padding:.65rem 1rem;font-size:.75rem;font-weight:700;
                     color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentAppointments as $appt)
        @php
        $badgeClass = match($appt->status) {
        'approved' => 'badge-green',
        'pending' => 'badge-amber',
        'completed' => 'badge-blue',
        'cancelled' => 'badge-red',
        'rejected' => 'badge-red',
        default => 'badge-amber',
        };
        @endphp
        <tr style="border-bottom:1px solid #e5e7eb"
          onmouseover="this.style.background='#f9fafb'"
          onmouseout="this.style.background='transparent'">
          <td style="padding:.85rem 1rem;font-weight:600;color:#111827">
            {{ $appt->patient->name ?? '—' }}
          </td>
          <td style="padding:.85rem 1rem;color:#6b7280">
            Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '—' }}
          </td>
          <td style="padding:.85rem 1rem;color:#6b7280">
            {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
          </td>
          <td style="padding:.85rem 1rem;color:#6b7280">
            {{ $appt->department ?? 'General' }}
          </td>
          <td style="padding:.85rem 1rem;text-align:center">
            <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:2rem;color:#6b7280">
            No appointments yet.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection