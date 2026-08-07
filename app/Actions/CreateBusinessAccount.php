<?php

namespace App\Actions;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateBusinessAccount
{
    public function handle(array $data): array
    {
        $plainPassword = Str::password(8);

        $user = DB::transaction(function () use ($data, $plainPassword) {
            $business = Business::create([
                'name' => $data['business_name'],
                'api_key' => Str::random(40),
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($plainPassword),
                'business_id' => $business->id,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('owner');

            return $user;
        });

        return [
            'user' => $user,
            'password' => $plainPassword,
        ];
    }
}
