<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\AdminDoctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        // Block admin registration
        if (isset($input['role']) && $input['role'] === 'admin') {
            throw ValidationException::withMessages([
                'role' => 'You are not allowed to register as admin.',
            ]);
        }

        Validator::make($input, [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone'          => ['required', 'string'],
            'role'           => ['required', 'in:patient,doctor'],
            'specialization' => ['required_if:role,doctor', 'nullable', 'string'],
            'password'       => $this->passwordRules(),
        ])->validate();

        // ✅ Store in variable first — don't return yet
        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'phone'    => $input['phone'],
            'role'     => $input['role'],
            'status'   => $input['role'] === 'doctor' ? 'pending' : 'active',
            'password' => Hash::make($input['password']),
        ]);

        // ✅ Now create doctor profile BEFORE returning
        if ($input['role'] === 'doctor') {
            $nameParts = explode(' ', trim($input['name']));
            AdminDoctor::create([
                'first_name'          => $nameParts[0],
                'last_name'           => implode(' ', array_slice($nameParts, 1)),
                'email'               => $input['email'],
                'phone'               => $input['phone'] ?? null,
                'specialization'      => $input['specialization'] ?? 'General Health',
                'status'              => 'unavailable',
                'years_of_experience' => 0,
                'consultation_fee'    => 0,
                'schedule_load'       => 0,
            ]);
        }

        // ✅ Return at the end
        return $user;
    }
}
