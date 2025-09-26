<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin if not exists
        User::firstOrCreate(
            ['email' => 'admin@penyewaan.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // Create super admin if not exists
        User::firstOrCreate(
            ['email' => 'superadmin@penyewaan.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'role' => 'admin',
                'phone' => '081234567891',
                'email_verified_at' => now(),
            ]
        );

        echo "Admin accounts created/verified successfully!\n";
        echo "Default Admin: admin@penyewaan.com | Password: admin123\n";
        echo "Super Admin: superadmin@penyewaan.com | Password: superadmin123\n";
    }
}