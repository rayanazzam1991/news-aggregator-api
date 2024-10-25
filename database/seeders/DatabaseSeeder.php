<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Rayan Azzam',
            'email' => 'user@example.com',
            'password' => 'P@ssword123',
        ]);
    }
}
