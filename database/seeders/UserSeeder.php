<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'your-email@domain.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('your password'),
                'role'     => 'admin',
            ]
        );
    }
}
