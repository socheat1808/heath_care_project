@extends('admin.layout')

@section('title', 'Edit Doctor')

@section('content')

<div class="page-content active">

    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.doctors.index') }}" class="action-btn back-btn">
                    ←
                </a>
                <h1>Edit Doctor</h1>
            </div>
            <p>Update Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} profile.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('admin.doctors.update', $doctor->DoctorID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- PERSONAL -->
            <div class="form-section">
                <h3>Personal Information</h3>

                <div class="form-grid">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required>
                        @error('first_name') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required>
                        @error('last_name') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $doctor->email) }}" required>
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}">
                    </div>

                    <!-- PHOTO -->
                    <div class="full">
                        <label>Profile Photo</label>
                        <div class="photo-upload-box" id="photo-box" onclick="document.getElementById('photo').click()">
                            @if($doctor->photo)
                            <img id="preview"
                                src="{{ Storage::url($doctor->photo) }}"
                                style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                            <span id="upload-label" style="font-size:13px;color:#888;">Click to change photo</span>
                            @else
                            <img id="preview" src="" style="display:none;width:80px;height:80px;border-radius:50%;object-fit:cover;">
                            <span id="upload-label">📷 Click to upload photo</span>
                            @endif
                        </div>
                        <input type="file" id="photo" name="photo"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                            hidden onchange="previewPhoto(this)">
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- PROFESSIONAL -->
            <div class="form-section">
                <h3>Professional Information</h3>

                <div class="form-grid">
                    <div>
                        <label>Specialisation</label>
                        <select name="specialization" required>
                            @foreach(['General Health','Cardiology','Dental','Neurology','Orthopaedics','Dermatology','Pediatrics','Psychiatry','Ophthalmology'] as $s)
                            <option value="{{ $s }}" {{ old('specialization', $doctor->specialization)==$s?'selected':'' }}>
                                {{ $s }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="status" required>
                            <option value="available" {{ old('status',$doctor->status)=='available'?'selected':'' }}>Available</option>
                            <option value="onleave" {{ old('status',$doctor->status)=='onleave'?'selected':'' }}>On Leave</option>
                            <option value="unavailable" {{ old('status',$doctor->status)=='unavailable'?'selected':'' }}>Unavailable</option>
                        </select>
                    </div>

                    <div>
                        <label>Years of Experience</label>
                        <input type="number" name="years_of_experience"
                            value="{{ old('years_of_experience', $doctor->years_of_experience) }}">
                    </div>

                    <div>
                        <label>Consultation Fee ($)</label>
                        <input type="number" name="consultation_fee"
                            value="{{ old('consultation_fee', $doctor->consultation_fee) }}">
                    </div>

                    <div class="full">
                        <label>Biography / Notes</label>
                        <textarea name="biography_note" rows="4">{{ old('biography_note', $doctor->biography_note) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ACCOUNT -->
            <div class="form-section">
                <h3>Account Settings</h3>

                <div class="form-grid">
                    <div>
                        <label>New Password</label>
                        <input type="text" name="password" placeholder="Leave blank to keep current password">
                        <span class="hint">Only fill if you want to change password</span>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="form-footer">
                <a href="{{ route('admin.doctors.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Update Doctor</button>
            </div>

        </form>
    </div>

</div>

<!-- STYLE -->
<style>
    .form-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
    }

    .form-section {
        margin-bottom: 20px;
    }

    .form-section h3 {
        margin-bottom: 10px;
        font-size: 14px;
        color: #444;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .form-grid .full {
        grid-column: 1/-1;
    }

    label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 13px;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #2563eb;
        outline: none;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-outline {
        border: 1px solid #ddd;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
    }

    .error {
        color: red;
        font-size: 11px;
    }

    .hint {
        font-size: 11px;
        color: #888;
    }

    .back-btn {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #ddd;
        text-decoration: none;
    }

    .photo-upload-box {
        display: flex;
        align-items: center;
        gap: 14px;
        border: 2px dashed #ddd;
        border-radius: 10px;
        padding: 14px;
        cursor: pointer;
        transition: border-color .2s;
    }

    .photo-upload-box:hover {
        border-color: #2563eb;
    }

    #upload-label {
        font-size: 13px;
        color: #888;
    }
</style>

<script>
    function previewPhoto(input) {
        const preview = document.getElementById('preview');
        const label = document.getElementById('upload-label');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
                label.textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection