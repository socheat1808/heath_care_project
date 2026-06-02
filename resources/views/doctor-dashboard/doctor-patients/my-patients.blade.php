{{-- resources/views/doctor-dashboard/my-patients.blade.php --}}
@extends('doctor-dashboard.layout')
@section('title', 'My Patients')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>My Patients</h1>
        <p>All patients who have booked appointments with you.</p>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Done</span>
        </div>
        <div class="stat-value">{{ $totalCompleted }}</div>
        <div class="stat-label">Completed Visits</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon amber">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend down">Waiting</span>
        </div>
        <div class="stat-value">{{ $totalPending }}</div>
        <div class="stat-label">Pending Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="stat-trend up">Today</span>
        </div>
        <div class="stat-value">{{ $totalToday }}</div>
        <div class="stat-label">Today's Appointments</div>
    </div>

</div>

{{-- ── Patients List ── --}}
<div class="chart-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem">
        <div>
            <div class="card-title">Patient List</div>
            <div class="card-subtitle">{{ $totalPatients }} patients found</div>
        </div>

        {{-- Search --}}
        <div style="position:relative;min-width:220px">
            <svg style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:14px;height:14px;stroke:var(--text-muted)"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
            </svg>
            <input type="text" id="searchInput" placeholder="Search patient..."
                onkeyup="filterPatients()"
                style="width:100%;padding:.55rem .9rem .55rem 2.2rem;border:1px solid var(--border);
                       border-radius:10px;background:var(--bg);color:var(--text);font-size:.83rem;outline:none">
        </div>
    </div>

    {{-- Table --}}
    @if($patients->count())
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem" id="patientsTable">
            <thead>
                <tr style="border-bottom:2px solid var(--border)">
                    <th style="text-align:left;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Patient
                    </th>
                    <th style="text-align:left;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Contact
                    </th>
                    <th style="text-align:center;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Total Visits
                    </th>
                    <th style="text-align:center;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Completed
                    </th>
                    <th style="text-align:center;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Pending
                    </th>
                    <th style="text-align:left;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Last Visit
                    </th>
                    <th style="text-align:center;padding:.65rem 1rem;font-size:.78rem;font-weight:700;
                               color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody id="patientRows">
                @foreach($patients as $p)
                @php
                $name = $p['patient']->name ?? '—';
                $email = $p['patient']->email ?? '—';
                $phone = $p['patient']->phone ?? '—';
                $initials = strtoupper(substr($name, 0, 2));
                $statusBadge = match($p['last_status']) {
                'completed' => ['Completed', 'badge-blue'],
                'approved' => ['Approved', 'badge-green'],
                'pending' => ['Pending', 'badge-amber'],
                'cancelled' => ['Cancelled', 'badge-red'],
                'rejected' => ['Rejected', 'badge-red'],
                default => ['Unknown', 'badge-gray'],
                };
                @endphp
                <tr class="patient-row"
                    style="border-bottom:1px solid var(--border);transition:background .15s"
                    onmouseover="this.style.background='var(--bg)'"
                    onmouseout="this.style.background='transparent'">

                    {{-- Patient name + avatar --}}
                    <td style="padding:.85rem 1rem">
                        <div style="display:flex;align-items:center;gap:.75rem">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--green);
                                        color:#fff;display:flex;align-items:center;justify-content:center;
                                        font-size:.8rem;font-weight:700;flex-shrink:0">
                                {{ $initials }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--text)" class="patient-name">
                                    {{ $name }}
                                </div>
                                <div style="font-size:.75rem;color:var(--text-muted)">Patient</div>
                            </div>
                        </div>
                    </td>

                    {{-- Contact --}}
                    <td style="padding:.85rem 1rem">
                        <div style="font-size:.83rem;color:var(--text)">{{ $email }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted)">{{ $phone }}</div>
                    </td>

                    {{-- Total visits --}}
                    <td style="padding:.85rem 1rem;text-align:center">
                        <span style="font-weight:700;color:var(--text);font-size:1rem">
                            {{ $p['total'] }}
                        </span>
                    </td>

                    {{-- Completed --}}
                    <td style="padding:.85rem 1rem;text-align:center">
                        <span class="badge badge-blue">{{ $p['completed'] }}</span>
                    </td>

                    {{-- Pending --}}
                    <td style="padding:.85rem 1rem;text-align:center">
                        <span class="badge badge-amber">{{ $p['pending'] }}</span>
                    </td>

                    {{-- Last visit --}}
                    <td style="padding:.85rem 1rem">
                        <div style="font-size:.83rem;color:var(--text);font-weight:600">
                            {{ \Carbon\Carbon::parse($p['last_visit'])->format('d M Y') }}
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted)">
                            {{ \Carbon\Carbon::parse($p['last_visit'])->diffForHumans() }}
                        </div>
                    </td>

                    {{-- Last status --}}
                    <td style="padding:.85rem 1rem;text-align:center">
                        <span class="badge {{ $statusBadge[1] }}">{{ $statusBadge[0] }}</span>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else
    {{-- Empty state --}}
    <div style="text-align:center;padding:4rem;color:var(--text-muted)">
        <div style="font-size:48px;margin-bottom:.75rem">👥</div>
        <div style="font-size:1rem;font-weight:600;color:var(--text);margin-bottom:.35rem">
            No patients yet
        </div>
        <div style="font-size:.875rem">
            Patients will appear here once they book appointments with you.
        </div>
    </div>
    @endif

</div>

{{-- ── Search JS ── --}}
<script>
    function filterPatients() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.patient-row');
        rows.forEach(row => {
            const name = row.querySelector('.patient-name').textContent.toLowerCase();
            row.style.display = name.includes(input) ? '' : 'none';
        });
    }
</script>

@endsection