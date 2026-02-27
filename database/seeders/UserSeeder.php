<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@poptin.com',
            'email_verified_at' => now(),
            'password' => bcrypt('DefaultP@ssword1')
        ]);
        $admin->assignRole(RoleEnum::Admin->value);

        $user = User::create([
            'name' => 'normal user',
            'email' => 'user@poptin.com',
            'email_verified_at' => now(),
            'password' => bcrypt('DefaultP@ssword1')
        ]);
        $user->assignRole(RoleEnum::User->value);
    }
}
