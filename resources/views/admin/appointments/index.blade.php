@extends('admin.layout')
@section('title', 'Appointments')

@section('content')
<!-- ══════════════════════
       PAGE: APPOINTMENTS
  ══════════════════════ -->
<div class="page-content active" id="page-appointments">
  <div class="page-header">
    <div class="page-header-left">
      <h1>Manage Appointments</h1>
      <p>Schedule, confirm and track all patient appointments.</p>
    </div>
    <div style="display:flex;gap:8px">
      <button class="btn btn-outline" onclick="toggleCalendar()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Calendar View
      </button>
      <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
        <div class="nav-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        Add Appointment
      </a>
    </div>
  </div>

  @if (session('success'))
  <div style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;padding:11px 16px;border-radius:8px;margin-bottom:16px;font-size:.9rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
  @endif

  {{-- Stats --}}
  <div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card c-green">
      <div class="stat-card-top">
        <div class="stat-icon green"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg></div>
      </div>
      <div>
        <div class="stat-value">{{ $stats['approved'] }}</div>
        <div class="stat-label">Confirmed Today</div>
      </div>
    </div>
    <div class="stat-card c-blue">
      <div class="stat-card-top">
        <div class="stat-icon blue"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg></div>
      </div>
      <div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Approval</div>
      </div>
    </div>
    <div class="stat-card c-amber">
      <div class="stat-card-top">
        <div class="stat-icon amber"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg></div>
      </div>
      <div>
        <div class="stat-value">{{ $stats['month'] }}</div>
        <div class="stat-label">This Month</div>
      </div>
    </div>
    <div class="stat-card c-purple">
      <div class="stat-card-top">
        <div class="stat-icon purple"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg></div>
      </div>
      <div>
        <div class="stat-value">{{ $stats['cancelled'] }}</div>
        <div class="stat-label">Cancelled Today</div>
      </div>
    </div>
  </div>

  {{-- Calendar (toggle) --}}
  <div id="calendarSection" style="display:none;background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;margin-bottom:20px;">
    <h3 style="font-size:1rem;font-weight:600;margin-bottom:16px;">Upcoming 30 Days</h3>
    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;">
      @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
      <div style="text-align:center;font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:4px 0;">{{ $d }}</div>
      @endforeach
      @for ($blank = 0; $blank < now()->dayOfWeek; $blank++)
        <div></div>
        @endfor
        @for ($day = 0; $day <= 30; $day++)
          @php
          $date=now()->copy()->addDays($day);
          $dateKey = $date->format('Y-m-d');
          $count = isset($calendarData[$dateKey]) ? $calendarData[$dateKey]->count() : 0;
          @endphp
          <div style="border-radius:8px;min-height:52px;padding:5px;border:1px solid {{ $count > 0 ? '#1a7a5e' : 'var(--border)' }};background:{{ $count > 0 ? '#e8f5f1' : 'var(--bg,#f7f9fc)' }};">
            <div style="font-size:.72rem;font-weight:600;color:{{ $date->isToday() ? '#1a7a5e' : 'var(--text-muted)' }};">{{ $date->format('j') }}</div>
            @if ($count > 0)
            <div style="font-size:.68rem;color:#1a7a5e;font-weight:600;margin-top:2px;">{{ $count }} appt{{ $count > 1 ? 's' : '' }}</div>
            @endif
          </div>
          @endfor
    </div>
  </div>

  {{-- Table --}}
  <div class="table-card">
    <form method="GET" action="{{ route('admin.appointments.index') }}">
      <div class="search-bar">
        <div class="search-input-wrap">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input type="text" name="patient" value="{{ request('patient') }}" placeholder="Search appointments…">
        </div>
        {{-- Change the doctor dropdown --}}
        <select name="doctor_id" class="filter-select" onchange="this.form.submit()">
          <option value="">All Doctors</option>
          @foreach($doctors as $doc)
          <option value="{{ $doc->DoctorID }}"
            {{ request('doctor_id') == $doc->DoctorID ? 'selected' : '' }}>
            Dr. {{ $doc->first_name }} {{ $doc->last_name }}
          </option>
          @endforeach
        </select>
        <div class="status-filter">
          @foreach ([''=>'All', 'approved'=>'Confirmed', 'pending'=>'Pending', 'cancelled'=>'Cancelled', 'completed'=>'Completed'] as $val => $label)
          <button type="submit" name="status" value="{{ $val }}"
            class="status-tab {{ request('status', '') === $val ? 'active' : '' }}">
            {{ $label }}
          </button>
          @endforeach
        </div>
        <input type="date" name="date" class="filter-select" style="margin-left:auto"
          value="{{ request('date') }}" onchange="this.form.submit()">
      </div>
    </form>

    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Patient</th>
          <th>Doctor</th>
          <th>Specialization</th>
          <th>Date & Time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($appointments as $appt)
        @php
        $name = $appt->patient->name ?? 'Patient';
        $parts = explode(' ', $name);
        $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
        $colors = ['av-green','av-blue','av-pink','av-red','av-amber'];
        $color = $colors[$appt->id % count($colors)];
        $badgeMap = ['pending'=>'badge-amber','approved'=>'badge-green','rejected'=>'badge-red','completed'=>'badge-blue','cancelled'=>'badge-gray'];
        $badge = $badgeMap[$appt->status] ?? 'badge-gray';
        $labelMap = ['pending'=>'Pending','approved'=>'Confirmed','rejected'=>'Rejected','completed'=>'Completed','cancelled'=>'Cancelled'];
        @endphp
        <tr>
          <td style="color:var(--text-muted);font-size:0.8rem">#APT-{{ str_pad($appt->id, 4, '0', STR_PAD_LEFT) }}</td>
          <td>
            <div class="user-cell">
              <div class="avatar {{ $color }}" style="width:30px;height:30px;font-size:0.7rem">{{ $initials }}</div>
              <span style="font-weight:500;font-size:0.875rem">{{ $name }}</span>
            </div>
          </td>
          <td style="color:var(--text-secondary)">Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '' }}</td>
          <td><span class="badge badge-green">{{ $appt->doctor->specialization ?? '—' }}</span></td>
          <td style="color:var(--text-secondary);font-size:0.85rem">
            {{ $appt->appointment_date->format('d M Y') }} — {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
          </td>
          <td><span class="badge {{ $badge }}">{{ $labelMap[$appt->status] ?? ucfirst($appt->status) }}</span></td>
          <td>
            {{-- View --}}
            <button class="action-btn" title="View">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            {{-- Approve / Reject --}}
            @if ($appt->status === 'pending')
            <form method="POST" action="{{ route('admin.appointments.approve', $appt) }}" style="display:inline">
              @csrf @method('PATCH')
              <button class="action-btn" title="Approve" style="color:#10b981">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </button>
            </form>
            <button class="action-btn danger" title="Reject" onclick="openRejectModal({{ $appt->id }})">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            @endif
            {{-- Complete --}}
            @if ($appt->status === 'approved')
            <form method="POST" action="{{ route('admin.appointments.complete', $appt) }}" style="display:inline">
              @csrf @method('PATCH')
              <button class="action-btn" title="Mark Complete" style="color:#6366f1">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </form>
            @endif
            {{-- Delete --}}
            <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}" style="display:inline"
              onsubmit="return confirm('Delete this appointment?')">
              @csrf @method('DELETE')
              <button class="action-btn danger" title="Delete">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
            <div style="font-size:2rem;margin-bottom:8px;">📅</div>
            <div style="font-weight:600;color:var(--text-secondary);">No appointments found</div>
            <div style="font-size:.85rem;margin-top:4px;">Try adjusting your filters.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="pagination">
      <span class="pagination-info">
        Showing {{ $appointments->firstItem() ?? 0 }}–{{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments
      </span>
      <div class="pagination-btns">
        {{ $appointments->withQueryString()->links() }}
      </div>
    </div>
  </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px);z-index:200;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <h3 style="font-size:1.05rem;font-weight:600;margin-bottom:6px;">Reject Appointment</h3>
    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:14px;">Please provide a reason for rejecting this appointment.</p>
    <form method="POST" id="rejectForm">
      @csrf @method('PATCH')
      <textarea name="rejection_reason" required placeholder="Enter rejection reason…"
        style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-family:inherit;font-size:.9rem;resize:vertical;min-height:100px;outline:none;"></textarea>
      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px;">
        <button type="button" onclick="closeRejectModal()"
          style="padding:8px 18px;border-radius:8px;border:1px solid var(--border);background:transparent;cursor:pointer;font-family:inherit;">Cancel</button>
        <button type="submit"
          style="padding:8px 18px;border-radius:8px;border:none;background:#ef4444;color:#fff;cursor:pointer;font-family:inherit;font-weight:500;">Reject</button>
      </div>
    </form>
  </div>
</div>

<script>
  function toggleCalendar() {
    const c = document.getElementById('calendarSection');
    c.style.display = c.style.display === 'none' ? 'block' : 'none';
  }

  function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/admin/appointments/${id}/reject`;
    document.getElementById('rejectModal').style.display = 'flex';
  }

  function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
  }
  document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
  });
</script>

@endsection