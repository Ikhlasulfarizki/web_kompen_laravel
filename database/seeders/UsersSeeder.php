<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin role
        $adminRole = Role::where('nama_role', 'admin')->first();

        if ($adminRole) {
            User::create([
                'username' => 'admin',
                'password' => 'admin123',
                'role_id' => $adminRole->id,
            ]);
        }
    }
}
