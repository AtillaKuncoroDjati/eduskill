<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'username' => 'root',
                'email' => 'root@access.com',
                'phone' => '089530695776',
                'password' => Hash::make('/'),
                'email_verified_at' => now(),
                'password_changed_at' => now(),
                'permission' => 'admin',
                'status' => 'aktif',
                'is_email_notification_enabled' => true,
                'is_whatsapp_notification_enabled' => false,
                'active_device' => null
            ],
            [
                'name' => 'Regular User',
                'username' => 'user',
                'email' => 'user@access.com',
                'phone' => '089530695777',
                'password' => Hash::make('/'),
                'email_verified_at' => now(),
                'password_changed_at' => now(),
                'permission' => 'user',
                'status' => 'aktif',
                'is_email_notification_enabled' => true,
                'is_whatsapp_notification_enabled' => false,
                'active_device' => null
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
