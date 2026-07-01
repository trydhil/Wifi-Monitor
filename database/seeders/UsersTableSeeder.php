<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Polrestabes',
                'email' => 'admin@polrestabes.go.id',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Teknisi Jaringan',
                'email' => 'teknisi@polrestabes.go.id',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Supervisor IT',
                'email' => 'supervisor@polrestabes.go.id',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Operator Monitoring',
                'email' => 'operator@polrestabes.go.id',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Kepala Divisi IT',
                'email' => 'kadiv@polrestabes.go.id',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}