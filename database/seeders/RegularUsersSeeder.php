<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RegularUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
       

        // Create 10 regular users
        for ($i = 1; $i <= 10; $i++) {
            $user = User::factory()->create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
            ]);
            
            $user->assignRole('user');
        }
    }
} 