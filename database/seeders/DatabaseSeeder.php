<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Log::info('Starting DatabaseSeeder');

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('12345678'),
                'role' => 'viewer',
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );
        Log::info('Test user created');

        Log::info('Creating 30 users via factory');
        \App\Models\User::factory(30)->create();
        Log::info('30 users created');

        $this->call([
            CategorySeeder::class,
            AdminSeeder::class,
        ]);
        Log::info('Category and Admin seeders called');

        Log::info('Creating 30 financial records via factory');
        \App\Models\FinancialRecord::factory(30)->create();
        Log::info('30 financial records created');

        Log::info('DatabaseSeeder completed');
    }
}
