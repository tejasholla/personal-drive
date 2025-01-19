<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'The admin',
            'email' => 'demo@personaldrive.com',
            'is_admin' => 1,
            'password' => bcrypt('peace'),
        ]);
    }
}
