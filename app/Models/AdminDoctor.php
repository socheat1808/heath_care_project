<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDoctor extends Model
{
    protected $primaryKey = 'DoctorID';
    protected $table = 'doctors';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        'status',
        'years_of_experience',
        'consultation_fee',
        'schedule_load',
        'biography_note',
        'photo',
        'password',
    ];
}
