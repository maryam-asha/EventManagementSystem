<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $adminUser->assignRole('admin');

        $superAdminUser = User::factory()->create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@example.com',
        ]);
        $superAdminUser->assignRole('super-admin');

        $organizerUser = User::factory()->create([
            'name' => 'organizer',
            'email' => 'organizer@example.com',
        ]);
        $organizerUser->assignRole('organizer');
    }
}
