<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
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

        $this->call([
            CategorySeeder::class,
            AdminSeeder::class,
        ]);
    }
}
