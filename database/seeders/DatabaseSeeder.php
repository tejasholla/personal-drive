<?php

namespace Database\Seeders;

use App\Models\File;
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
            'email' => 'test@example.com',
            'is_admin' => 1,
            'password' => bcrypt('1234'),
        ]);


        File::factory()->count(10)->create(
            ['user_id' => User::first()->id ]
        );
    }
    
    
}
