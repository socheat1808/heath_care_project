<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day',
        'is_active',
        'start_time',
        'end_time',
        'max_appointments',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship back to doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'DoctorID');
    }
}
