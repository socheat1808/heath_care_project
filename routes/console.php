<?php

use Illuminate\Support\Facades\Schedule;

// Send appointment reminders every day at 8:00 AM
Schedule::command('appointments:send-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Phnom_Penh');
