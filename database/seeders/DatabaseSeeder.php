<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['Username' => 'admin'],
            [
                'PasswordHash' => Hash::make('123456'),
                'Email' => 'admin@zenstyle.com',
                'Phone' => '0900000001',
                'RoleID' => 1,
                'IsActive' => 1,
                'DateBirth' => null,
                'Position' => null,
            ]
        );
    }
}