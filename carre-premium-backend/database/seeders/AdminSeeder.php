<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@carrepremium.com',
                'password' => Hash::make('Admin@2024'),
                'role' => 'super_admin',
                'phone' => '+225 07 XX XX XX XX',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Manager',
                'email' => 'manager@carrepremium.com',
                'password' => Hash::make('Manager@2024'),
                'role' => 'admin',
                'phone' => '+225 05 XX XX XX XX',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Moderator',
                'email' => 'moderator@carrepremium.com',
                'password' => Hash::make('Moderator@2024'),
                'role' => 'moderator',
                'phone' => '+225 01 XX XX XX XX',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
