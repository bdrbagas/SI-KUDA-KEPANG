<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users first - disable FK checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Super Admin - hash password explicitly
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@sikudakepang.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'desa_id' => null,
        ]);

        // Admin Puskesmas
        User::create([
            'name' => 'Admin Puskesmas',
            'email' => 'puskesmas@sikudakepang.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_puskesmas',
            'desa_id' => null,
        ]);

        // Admin CSR
        User::create([
            'name' => 'Admin CSR',
            'email' => 'csr@sikudakepang.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_csr',
            'desa_id' => null,
        ]);

        // Admin Kecamatan
        User::create([
            'name' => 'Admin Kecamatan',
            'email' => 'kecamatan@sikudakepang.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
            'desa_id' => null,
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Login: admin@sikudakepang.id / password123');
    }
}
