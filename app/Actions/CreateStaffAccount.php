<?php

namespace App\Actions;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateStaffAccount
{
    public function handle(Business $business, array $data): array
    {
        $plainPassword = Str::password(8);

        $user = DB::transaction(function () use ($business, $data, $plainPassword) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($plainPassword),
                'business_id' => $business->id,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('staff');

            return $user;
        });

        return [
            'user' => $user,
            'password' => $plainPassword,
        ];
    }
}
