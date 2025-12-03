<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['nama_role' => 'admin']);
        Role::create(['nama_role' => 'dosen']);
        Role::create(['nama_role' => 'mahasiswa']);
        Role::create(['nama_role' => 'teknisi']);
    }
}
