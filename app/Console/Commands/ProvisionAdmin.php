<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ProvisionAdmin extends Command
{
    protected $signature = 'app:provision-admin';

    protected $description = 'Create the first administrator from private environment variables';

    public function handle(): int
    {
        if (User::where('role_id', 1)->exists()) {
            $this->info('Administrator already exists. No credentials were changed.');
            return self::SUCCESS;
        }

        $email = getenv('BOOTSTRAP_ADMIN_EMAIL') ?: '';
        $password = getenv('BOOTSTRAP_ADMIN_PASSWORD') ?: '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || str_ends_with(strtolower($email), '.test')
            || strlen($password) < 12
            || $password === 'DemoPass123!') {
            $this->error('Set a real BOOTSTRAP_ADMIN_EMAIL and a unique BOOTSTRAP_ADMIN_PASSWORD of at least 12 characters.');
            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('The bootstrap email already belongs to a non-admin user.');
            return self::FAILURE;
        }

        User::forceCreate([
            'email' => $email,
            'password' => Hash::make($password),
            'names' => 'Administrador',
            'role_id' => 1,
            'email_verified_at' => now(),
        ]);

        $this->info('Administrator created. Remove BOOTSTRAP_ADMIN_PASSWORD from the hosting settings.');
        return self::SUCCESS;
    }
}
