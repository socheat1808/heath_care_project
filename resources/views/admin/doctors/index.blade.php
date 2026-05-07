@extends('admin.layout')
@section('title', 'Doctors')
@section('content')

<div class="page-content active" id="page-doctors">

  <div class="page-header">
    <div class="page-header-left">
      <h1>Doctors</h1>
      <p>Manage all doctors registered on One-Health.</p>
    </div>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary"
      style="text-decoration:none;display:inline-flex;align-items:center;gap:.4rem">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Add Doctor
    </a>
  </div>

  @if(session('success'))
  <div class="alert-banner alert-green" style="margin-bottom:1.25rem">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
    {{ session('success') }}
  </div>
  @endif

  <!-- Stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem">
    <div class="stat-card c-green">
      <div class="stat-card-top">
        <div class="stat-icon green">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
      </div>
      <div>
        <div class="stat-value">{{ $doctors->count() }}</div>
        <div class="stat-label">Total Doctors</div>
      </div>
    </div>
    <div class="stat-card c-blue">
      <div class="stat-card-top">
        <div class="stat-icon blue">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <div>
        <div class="stat-value">{{ $doctors->where('status','available')->count() }}</div>
        <div class="stat-label">Available</div>
      </div>
    </div>
    <div class="stat-card c-amber">
      <div class="stat-card-top">
        <div class="stat-icon amber">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <div>
        <div class="stat-value">{{ $doctors->where('status','onleave')->count() }}</div>
        <div class="stat-label">On Leave</div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="chart-card" style="padding:0;overflow:hidden">
    <div style="display:flex;align-items:center;gap:1rem;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border)">
      <div class="topbar-search" style="flex:1;max-width:320px">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" placeholder="Search doctors…" oninput="filterTable(this.value)">
      </div>
    </div>

    <table style="width:100%;border-collapse:collapse" id="doctorTable">
      <thead>
        <tr style="border-bottom:1px solid var(--border)">
          <th class="th">Doctor</th>
          <th class="th">Specialisation</th>
          <th class="th">Contact</th>
          <th class="th">Experience</th>
          <th class="th">Fee</th>
          <th class="th">Status</th>
          <th class="th">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($doctors as $doctor)
        <tr class="tr-row" data-search="{{ strtolower($doctor->first_name.' '.$doctor->last_name.' '.$doctor->specialization) }}">
          <td class="td">
            <div style="display:flex;align-items:center;gap:.75rem">
              <div class="avatar av-green" style="width:38px;height:38px;font-size:.75rem;flex-shrink:0">
                {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
              </div>
              <div>
                <div style="font-weight:600;font-size:.9rem;color:var(--text)">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>
                <div style="font-size:.78rem;color:var(--text-muted)">{{ $doctor->email }}</div>
              </div>
            </div>
          </td>
          <td class="td" style="font-size:.875rem;color:var(--text)">{{ $doctor->specialization }}</td>
          <td class="td" style="font-size:.8rem;color:var(--text-muted)">{{ $doctor->phone ?? '—' }}</td>
          <td class="td" style="font-size:.875rem;color:var(--text)">{{ $doctor->years_of_experience }} yrs</td>
          <td class="td" style="font-size:.875rem;color:var(--text)">${{ number_format($doctor->consultation_fee, 0) }}</td>
          <td class="td">
            @if($doctor->status === 'available')
            <span class="badge badge-green">Available</span>
            @elseif($doctor->status === 'onleave')
            <span class="badge badge-amber">On Leave</span>
            @else
            <span class="badge badge-red">Unavailable</span>
            @endif
          </td>
          <td class="td">
            <div style="display:flex;align-items:center;gap:.4rem">
              {{-- Edit --}}
              <a href="{{ route('admin.doctors.edit', $doctor->DoctorID) }}" class="act-btn act-edit">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
              </a>
              {{-- Delete --}}
              <form method="POST" action="{{ route('admin.doctors.destroy', $doctor->DoctorID) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="act-btn act-delete"
                  data-name="{{ $doctor->first_name }} {{ $doctor->last_name }}"
                  onclick="return confirm('Delete Dr. ' + this.dataset.name + '? Their login account will also be removed.')">
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <p>No doctors yet. <a href="{{ route('admin.doctors.create') }}" style="color:var(--green)">Add the first one →</a></p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<style>
  .alert-banner {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .75rem 1.25rem;
    border-radius: 10px;
    font-size: .875rem;
    font-weight: 500
  }

  .alert-green {
    background: rgba(26, 138, 110, .1);
    color: var(--green);
    border: 1px solid rgba(26, 138, 110, .2)
  }

  .th {
    padding: .75rem 1.5rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap
  }

  .td {
    padding: .9rem 1.5rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle
  }

  .tr-row:last-child .td {
    border-bottom: none
  }

  .tr-row:hover {
    background: rgba(0, 0, 0, .02)
  }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .75rem;
    padding: 3rem 1.5rem;
    color: var(--text-muted);
    font-size: .875rem
  }

  .empty-state svg {
    width: 48px;
    height: 48px;
    opacity: .3
  }

  .act-btn {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .38rem .8rem;
    border-radius: 7px;
    font-size: .8rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    font-family: inherit;
    text-decoration: none
  }

  .act-btn:hover {
    opacity: .72
  }

  .act-edit {
    background: rgba(37, 99, 235, .1);
    color: #2563EB
  }

  .act-delete {
    background: rgba(220, 38, 38, .08);
    color: #DC2626
  }
</style>

<script>
  function filterTable(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#doctorTable .tr-row').forEach(row => {
      row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
  }
</script>

@endsection