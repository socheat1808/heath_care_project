@extends('admin.layout')
@section('title', 'Appointments')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
  <div class="page-header-left">
    <h1>Manage Appointments</h1>
    <p>Schedule, confirm and track all patient appointments.</p>
  </div>
  <div style="display:flex;gap:.65rem">
    <button class="btn btn-outline" onclick="toggleCalendar()"
      style="display:inline-flex;align-items:center;gap:.45rem">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      Calendar View
    </button>
    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary"
      style="display:inline-flex;align-items:center;gap:.45rem;text-decoration:none">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Add Appointment
    </a>
  </div>
</div>

{{-- ── Stats ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">

  <div class="stat-card">
    <div class="stat-card-top">
      <div class="stat-icon green">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <span class="stat-trend up">Today</span>
    </div>
    <div class="stat-value">{{ $stats['approved'] }}</div>
    <div class="stat-label">Confirmed Today</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-top">
      <div class="stat-icon amber">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <span class="stat-trend down">Waiting</span>
    </div>
    <div class="stat-value">{{ $stats['pending'] }}</div>
    <div class="stat-label">Pending Approval</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-top">
      <div class="stat-icon blue">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      <span class="stat-trend up">Month</span>
    </div>
    <div class="stat-value">{{ $stats['month'] }}</div>
    <div class="stat-label">This Month</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-top">
      <div class="stat-icon red" style="background:rgba(220,38,38,.08)">
        <svg fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      <span class="stat-trend down">Today</span>
    </div>
    <div class="stat-value">{{ $stats['cancelled'] }}</div>
    <div class="stat-label">Cancelled Today</div>
  </div>

</div>

{{-- ── Calendar (toggle) ── --}}
<div id="calendarSection"
  style="display:none;background:#fff;border:1px solid var(--border);
           border-radius:14px;padding:1.5rem;margin-bottom:1.25rem">
  <div style="font-size:.9rem;font-weight:700;color:var(--text);margin-bottom:1rem">
    Upcoming 30 Days
  </div>
  <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px">
    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
    <div style="text-align:center;font-size:.7rem;font-weight:700;
                    color:var(--text-muted);text-transform:uppercase;padding:4px 0">
      {{ $d }}
    </div>
    @endforeach

    @for($blank = 0; $blank < now()->dayOfWeek; $blank++)
      <div></div>
      @endfor

      @for($day = 0; $day <= 30; $day++)
        @php
        $date=now()->copy()->addDays($day);
        $dateKey = $date->format('Y-m-d');
        $count = isset($calendarData[$dateKey]) ? $calendarData[$dateKey]->count() : 0;
        @endphp
        <div style="border-radius:8px;min-height:52px;padding:5px;
                    border:1px solid {{ $count > 0 ? 'var(--green)' : 'var(--border)' }};
                    background:{{ $count > 0 ? 'rgba(26,138,110,.08)' : 'var(--bg)' }}">
          <div style="font-size:.72rem;font-weight:600;
                        color:{{ $date->isToday() ? 'var(--green)' : 'var(--text-muted)' }}">
            {{ $date->format('j') }}
          </div>
          @if($count > 0)
          <div style="font-size:.68rem;color:var(--green);font-weight:600;margin-top:2px">
            {{ $count }} appt{{ $count > 1 ? 's' : '' }}
          </div>
          @endif
        </div>
        @endfor
  </div>
</div>

{{-- ── Filters + Table ── --}}
<div class="table-card">

  <form method="GET" action="{{ route('admin.appointments.index') }}">
    <div class="search-bar">

      {{-- Search --}}
      <div class="search-input-wrap">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" name="patient"
          value="{{ request('patient') }}"
          placeholder="Search by patient name…">
      </div>

      {{-- Doctor filter --}}
      <select name="doctor_id" class="filter-select" onchange="this.form.submit()">
        <option value="">All Doctors</option>
        @foreach($doctors as $doc)
        <option value="{{ $doc->DoctorID }}"
          {{ request('doctor_id') == $doc->DoctorID ? 'selected' : '' }}>
          Dr. {{ $doc->first_name }} {{ $doc->last_name }}
        </option>
        @endforeach
      </select>

      {{-- Status tabs --}}
      <div class="status-filter">
        @foreach([''=>'All','pending'=>'Pending','approved'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled','rejected'=>'Rejected'] as $val => $label)
        <button type="submit" name="status" value="{{ $val }}"
          class="status-tab {{ request('status','') === $val ? 'active' : '' }}">
          {{ $label }}
        </button>
        @endforeach
      </div>

      {{-- Date filter --}}
      <input type="date" name="date" class="filter-select"
        value="{{ request('date') }}"
        onchange="this.form.submit()"
        style="margin-left:auto">

      {{-- Clear --}}
      @if(request()->hasAny(['patient','doctor_id','status','date']))
      <a href="{{ route('admin.appointments.index') }}"
        style="padding:.5rem 1rem;border:1px solid var(--border);border-radius:8px;
                       color:var(--text-muted);text-decoration:none;font-size:.83rem;
                       white-space:nowrap">
        Clear
      </a>
      @endif

    </div>
  </form>

  {{-- Table --}}
  <table class="data-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Specialization</th>
        <th>Date & Time</th>
        <th>Status</th>
        <th style="text-align:center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($appointments as $appt)
      @php
      $name = $appt->patient->name ?? 'Patient';
      $parts = explode(' ', $name);
      $initials = strtoupper(substr($parts[0],0,1).(isset($parts[1]) ? substr($parts[1],0,1) : ''));
      $colors = ['av-green','av-blue','av-amber','av-red','av-purple'];
      $color = $colors[$appt->id % count($colors)];
      $badgeMap = [
      'pending' => 'badge-amber',
      'approved' => 'badge-green',
      'rejected' => 'badge-red',
      'completed' => 'badge-blue',
      'cancelled' => 'badge-gray',
      ];
      $labelMap = [
      'pending' => 'Pending',
      'approved' => 'Confirmed',
      'rejected' => 'Rejected',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
      ];
      $badge = $badgeMap[$appt->status] ?? 'badge-gray';
      @endphp
      <tr>
        {{-- ID --}}
        <td style="color:var(--text-muted);font-size:.8rem;white-space:nowrap">
          #APT-{{ str_pad($appt->id, 4, '0', STR_PAD_LEFT) }}
        </td>

        {{-- Patient --}}
        <td>
          <div class="user-cell">
            <div class="avatar {{ $color }}" style="width:32px;height:32px;font-size:.72rem">
              {{ $initials }}
            </div>
            <div>
              <div style="font-weight:600;font-size:.875rem;color:var(--text)">{{ $name }}</div>
              <div style="font-size:.75rem;color:var(--text-muted)">
                {{ $appt->patient->email ?? '' }}
              </div>
            </div>
          </div>
        </td>

        {{-- Doctor --}}
        <td style="font-size:.875rem;color:var(--text)">
          Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '—' }}
        </td>

        {{-- Specialization --}}
        <td>
          <span class="badge badge-blue" style="font-size:.72rem">
            {{ $appt->doctor->specialization ?? '—' }}
          </span>
        </td>

        {{-- Date & Time --}}
        <td style="font-size:.83rem;color:var(--text-muted);white-space:nowrap">
          {{ $appt->appointment_date->format('d M Y') }}
          <span style="font-weight:600;color:var(--text)">
            · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
          </span>
        </td>

        {{-- Status --}}
        <td>
          <span class="badge {{ $badge }}">
            {{ $labelMap[$appt->status] ?? ucfirst($appt->status) }}
          </span>
        </td>

        {{-- Actions --}}
        <td style="text-align:center">
          <div style="display:flex;align-items:center;gap:.4rem;justify-content:center">

            {{-- View --}}
            <button type="button"
              title="View Details"
              onclick="openViewModal({
                                patient_name:  '{{ addslashes($appt->patient->name ?? '—') }}',
                                patient_email: '{{ addslashes($appt->patient->email ?? '—') }}',
                                doctor_name:   'Dr. {{ addslashes(($appt->doctor->first_name ?? '').' '.($appt->doctor->last_name ?? '')) }}',
                                doctor_spec:   '{{ addslashes($appt->doctor->specialization ?? '—') }}',
                                date:          '{{ $appt->appointment_date->format('d M Y') }}',
                                time:          '{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}',
                                department:    '{{ addslashes($appt->department ?? 'General') }}',
                                visit_type:    '{{ ucfirst(str_replace('-',' ',$appt->visit_type ?? 'In Person')) }}',
                                status:        '{{ ucfirst($appt->status) }}',
                                reason:        '{{ addslashes($appt->reason ?? '') }}',
                                notes:         '{{ addslashes($appt->notes ?? '') }}',
                                rejection:     '{{ addslashes($appt->rejection_reason ?? '') }}'
                            })"
              style="display:inline-flex;align-items:center;justify-content:center;
                                   width:30px;height:30px;border-radius:7px;background:var(--bg);
                                   border:1px solid var(--border);color:var(--text-muted);cursor:pointer"
              onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
              onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>

            {{-- Approve (pending only) --}}
            @if($appt->status === 'pending')
            <form method="POST" action="{{ route('admin.appointments.approve', $appt) }}">
              @csrf @method('PATCH')
              <button type="submit" title="Approve"
                style="display:inline-flex;align-items:center;justify-content:center;
                                       width:30px;height:30px;border-radius:7px;background:#f0fdf4;
                                       border:1px solid #bbf7d0;color:#16a34a;cursor:pointer"
                onmouseover="this.style.background='#16a34a';this.style.color='#fff'"
                onmouseout="this.style.background='#f0fdf4';this.style.color='#16a34a'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:13px;height:13px">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </button>
            </form>

            {{-- Reject (pending only) --}}
            <button type="button" title="Reject"
              onclick="openRejectModal({{ $appt->id }})"
              style="display:inline-flex;align-items:center;justify-content:center;
                                   width:30px;height:30px;border-radius:7px;background:#fef2f2;
                                   border:1px solid #fecaca;color:#dc2626;cursor:pointer"
              onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
              onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:13px;height:13px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            @endif

            {{-- Complete (approved only) --}}
            @if($appt->status === 'approved')
            <form method="POST" action="{{ route('admin.appointments.complete', $appt) }}">
              @csrf @method('PATCH')
              <button type="submit" title="Mark Complete"
                style="display:inline-flex;align-items:center;justify-content:center;
                                       width:30px;height:30px;border-radius:7px;background:#eff6ff;
                                       border:1px solid #bfdbfe;color:#2563eb;cursor:pointer"
                onmouseover="this.style.background='#2563eb';this.style.color='#fff'"
                onmouseout="this.style.background='#eff6ff';this.style.color='#2563eb'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </form>
            @endif

            {{-- Delete --}}
            <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}"
              onsubmit="return confirm('Delete this appointment?')">
              @csrf @method('DELETE')
              <button type="submit" title="Delete"
                style="display:inline-flex;align-items:center;justify-content:center;
                                       width:30px;height:30px;border-radius:7px;background:var(--bg);
                                       border:1px solid var(--border);color:var(--text-muted);cursor:pointer"
                onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </form>

          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted)">
          <div style="font-size:2.5rem;margin-bottom:.75rem">📅</div>
          <div style="font-weight:600;color:var(--text);margin-bottom:.25rem">No appointments found</div>
          <div style="font-size:.85rem">Try adjusting your filters.</div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Pagination --}}
  @if($appointments->hasPages())
  <div style="display:flex;align-items:center;justify-content:space-between;
                padding:1rem 1.5rem;border-top:1px solid var(--border);font-size:.83rem">
    <div style="color:var(--text-muted)">
      Showing {{ $appointments->firstItem() ?? 0 }}–{{ $appointments->lastItem() ?? 0 }}
      of {{ $appointments->total() }} appointments
    </div>
    <div>{{ $appointments->withQueryString()->links() }}</div>
  </div>
  @endif

</div>

{{-- ══════════════════════════════════════
     REJECT MODAL
══════════════════════════════════════ --}}
<div id="rejectModal"
  style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
           backdrop-filter:blur(2px);z-index:9999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:16px;padding:1.75rem;width:100%;max-width:440px;
                box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
      <div style="font-size:1rem;font-weight:700;color:var(--text)">Reject Appointment</div>
      <button onclick="closeRejectModal()"
        style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.25rem">✕</button>
    </div>
    <p style="color:var(--text-muted);font-size:.875rem;margin-bottom:1rem">
      Please provide a reason for rejecting this appointment.
    </p>
    <form method="POST" id="rejectForm">
      @csrf @method('PATCH')
      <textarea name="rejection_reason" required rows="4"
        placeholder="Enter rejection reason…"
        style="width:100%;border:1px solid var(--border);border-radius:10px;
                       padding:.75rem 1rem;font-family:inherit;font-size:.875rem;
                       resize:vertical;outline:none;box-sizing:border-box"
        onfocus="this.style.borderColor='var(--green)'"
        onblur="this.style.borderColor='var(--border)'"></textarea>
      <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1rem">
        <button type="button" onclick="closeRejectModal()"
          style="padding:.6rem 1.4rem;border-radius:10px;border:1px solid var(--border);
                           background:var(--bg);color:var(--text-muted);cursor:pointer;
                           font-family:inherit;font-size:.875rem;font-weight:600">
          Cancel
        </button>
        <button type="submit"
          style="padding:.6rem 1.4rem;border-radius:10px;border:none;
                           background:#dc2626;color:#fff;cursor:pointer;
                           font-family:inherit;font-size:.875rem;font-weight:600">
          Reject
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════════════════
     VIEW DETAIL MODAL
══════════════════════════════════════ --}}
<div id="viewModal"
  style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
           backdrop-filter:blur(2px);z-index:9999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:16px;padding:1.75rem;width:100%;max-width:520px;
                box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem;max-height:90vh;overflow-y:auto">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
      <div style="font-size:1rem;font-weight:700;color:var(--text)">Appointment Details</div>
      <button onclick="closeViewModal()"
        style="background:none;border:none;cursor:pointer;font-size:1.25rem;color:var(--text-muted)">✕</button>
    </div>

    {{-- Patient + Doctor --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
      <div style="padding:1rem;background:var(--bg);border-radius:10px">
        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;
                            letter-spacing:.08em;color:var(--text-muted);margin-bottom:.5rem">
          Patient
        </div>
        <div id="modal-patient-name" style="font-weight:700;color:var(--text)"></div>
        <div id="modal-patient-email" style="font-size:.78rem;color:var(--text-muted)"></div>
      </div>
      <div style="padding:1rem;background:var(--bg);border-radius:10px">
        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;
                            letter-spacing:.08em;color:var(--text-muted);margin-bottom:.5rem">
          Doctor
        </div>
        <div id="modal-doctor-name" style="font-weight:700;color:var(--text)"></div>
        <div id="modal-doctor-spec" style="font-size:.78rem;color:var(--text-muted)"></div>
      </div>
    </div>

    {{-- Details --}}
    <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.25rem">
      <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--text-muted)">Date</span>
        <span id="modal-date" style="font-weight:600;color:var(--text)"></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--text-muted)">Time</span>
        <span id="modal-time" style="font-weight:600;color:var(--text)"></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--text-muted)">Department</span>
        <span id="modal-department" style="font-weight:600;color:var(--text)"></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--text-muted)">Visit Type</span>
        <span id="modal-visit-type" style="font-weight:600;color:var(--text)"></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0">
        <span style="color:var(--text-muted)">Status</span>
        <span id="modal-status" style="font-weight:600;color:var(--text)"></span>
      </div>
    </div>

    {{-- Reason --}}
    <div id="modal-reason-wrap" style="display:none;margin-bottom:.75rem">
      <div style="font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:.35rem">
        Reason for Visit
      </div>
      <div id="modal-reason"
        style="padding:.75rem 1rem;background:var(--bg);border-radius:8px;
                       font-size:.83rem;color:var(--text-muted)"></div>
    </div>

    {{-- Notes --}}
    <div id="modal-notes-wrap" style="display:none;margin-bottom:.75rem">
      <div style="font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:.35rem">
        Doctor's Notes
      </div>
      <div id="modal-notes"
        style="padding:.75rem 1rem;background:var(--bg);border-radius:8px;
                       font-size:.83rem;color:var(--text-muted)"></div>
    </div>

    {{-- Rejection reason --}}
    <div id="modal-rejection-wrap" style="display:none;margin-bottom:.75rem">
      <div style="font-size:.78rem;font-weight:600;color:#b91c1c;margin-bottom:.35rem">
        Rejection Reason
      </div>
      <div id="modal-rejection"
        style="padding:.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;
                       border-radius:8px;font-size:.83rem;color:#b91c1c"></div>
    </div>

    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end">
      <button onclick="closeViewModal()"
        style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                       color:var(--text-muted);background:var(--bg);font-size:.875rem;
                       font-weight:600;cursor:pointer;font-family:inherit">
        Close
      </button>
    </div>
  </div>
</div>

{{-- ── JS ── --}}
<script>
  // Calendar toggle
  function toggleCalendar() {
    const c = document.getElementById('calendarSection');
    c.style.display = c.style.display === 'none' ? 'block' : 'none';
  }

  // Reject modal
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

  // View modal
  function openViewModal(data) {
    document.getElementById('modal-patient-name').textContent = data.patient_name;
    document.getElementById('modal-patient-email').textContent = data.patient_email;
    document.getElementById('modal-doctor-name').textContent = data.doctor_name;
    document.getElementById('modal-doctor-spec').textContent = data.doctor_spec;
    document.getElementById('modal-date').textContent = data.date;
    document.getElementById('modal-time').textContent = data.time;
    document.getElementById('modal-department').textContent = data.department;
    document.getElementById('modal-visit-type').textContent = data.visit_type;
    document.getElementById('modal-status').textContent = data.status;

    const reasonWrap = document.getElementById('modal-reason-wrap');
    const notesWrap = document.getElementById('modal-notes-wrap');
    const rejectionWrap = document.getElementById('modal-rejection-wrap');

    if (data.reason) {
      document.getElementById('modal-reason').textContent = data.reason;
      reasonWrap.style.display = 'block';
    } else {
      reasonWrap.style.display = 'none';
    }

    if (data.notes) {
      document.getElementById('modal-notes').textContent = data.notes;
      notesWrap.style.display = 'block';
    } else {
      notesWrap.style.display = 'none';
    }

    if (data.rejection) {
      document.getElementById('modal-rejection').textContent = data.rejection;
      rejectionWrap.style.display = 'block';
    } else {
      rejectionWrap.style.display = 'none';
    }

    document.getElementById('viewModal').style.display = 'flex';
  }

  function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
  }
  document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
  });
</script>

@endsection