<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@netpass.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'business_id' => null,
                'email_verified_at' => now(),
            ],
        );

        $admin->syncRoles(['admin']);
    }
}
