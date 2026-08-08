<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminder;
use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    protected $signature   = 'appointments:send-reminders';
    protected $description = 'Send reminder emails for appointments scheduled tomorrow';

    public function handle(): void
    {
        $tomorrow = now()->addDay()->toDateString();

        $appointments = Appointment::with('patient', 'doctor')
            ->whereDate('appointment_date', $tomorrow)
            ->where('status', 'approved')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments tomorrow — no reminders sent.');
            return;
        }

        $sent   = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            if (!$appointment->patient || !$appointment->patient->email) {
                continue;
            }

            try {
                Mail::to($appointment->patient->email)
                    ->send(new AppointmentReminder($appointment));
                $sent++;
                $this->info("✅ Reminder sent to {$appointment->patient->name} ({$appointment->patient->email})");
            } catch (\Exception $e) {
                $failed++;
                $this->error("❌ Failed for {$appointment->patient->name}: {$e->getMessage()}");
                \Log::error('Appointment reminder failed: ' . $e->getMessage());
            }
        }

        $this->info("Done — {$sent} sent, {$failed} failed.");
    }
}
