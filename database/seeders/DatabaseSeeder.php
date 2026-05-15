<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
<<<<<<< HEAD
            'fullname' => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
=======
            'name' => 'Test User',
            'email' => 'test@example.com',
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        ]);
    }
}
