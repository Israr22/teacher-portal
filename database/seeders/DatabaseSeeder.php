<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $user = \App\Models\User::factory()->create(['email' => 'israr@example.com']);

        \App\Models\Student::factory()->count(5)->create(['user_id' => $user->id]);
    }
}
