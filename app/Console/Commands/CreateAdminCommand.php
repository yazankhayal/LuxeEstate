<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature   = 'admin:create';
    protected $description = 'Create a new admin user interactively';

    public function handle(): int
    {
        $this->info('Creating a new admin user...');
        $this->newLine();

        $name = $this->ask('Name');
        $email = $this->ask('Email');

        // Validate email
        $validator = Validator::make(['email' => $email], ['email' => 'required|email|unique:users,email']);
        if ($validator->fails()) {
            $this->error('Invalid or already-taken email: ' . $validator->errors()->first('email'));
            return self::FAILURE;
        }

        $password = $this->secret('Password (min 8 chars)');
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return self::FAILURE;
        }

        $role = $this->choice('Role', ['admin', 'editor', 'viewer'], 0);

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => $role,
        ]);

        $this->newLine();
        $this->info("✅ Admin user created successfully!");
        $this->table(
            ['ID', 'Name', 'Email', 'Role'],
            [[$user->id, $user->name, $user->email, $user->role]]
        );

        return self::SUCCESS;
    }
}
