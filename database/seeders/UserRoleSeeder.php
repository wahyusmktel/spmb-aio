<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Roles (aman dijalankan berkali-kali)
        // Method firstOrCreate() akan mencari role, jika tidak ada, baru dibuat.
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleStaffTU = Role::firstOrCreate(['name' => 'Staff TU']);
        $roleKepsek = Role::firstOrCreate(['name' => 'Kepala Sekolah']);
        $roleGuru = Role::firstOrCreate(['name' => 'Guru']);

        // 2. Jadikan User pertama (ID=1) sebagai Admin
        // Kita asumsikan user pertama yang terdaftar (mungkin kamu) adalah Admin.
        $adminUser = User::find(1);

        if ($adminUser) {
            // Assign role Admin ke user ID 1
            $adminUser->assignRole($roleAdmin);
        } else {
            // Fallback: Jika database kosong, buat Admin baru
            $adminUser = User::create([
                'name' => 'Admin Aplikasi',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123') // GANTI INI
            ]);
            $adminUser->assignRole($roleAdmin);
        }

        // 3. Buat user baru untuk Staff TU (aman dijalankan berkali-kali)
        // Method updateOrCreate() akan mencari berdasarkan email,
        // jika ada di-update, jika tidak ada di-create.
        $staffUser = User::updateOrCreate(
            ['email' => 'stafftu@gmail.com'], // Kunci pencarian
            [ // Data untuk create/update
                'name' => 'Staff Tata Usaha',
                'password' => Hash::make('password123') // GANTI INI
            ]
        );

        // Assign role Staff TU
        $staffUser->assignRole($roleStaffTU);

        // 4. Buat Kepala Sekolah <-- TAMBAHKAN BLOK INI
        $kepsekUser = User::updateOrCreate(
            ['email' => 'kepsek@gmail.com'],
            ['name' => 'Kepala Sekolah', 'password' => Hash::make('password123')]
        );
        $kepsekUser->assignRole($roleKepsek);

        $guruUser = User::updateOrCreate(
            ['email' => 'guru@gmail.com'],
            ['name' => 'Guru Contoh', 'password' => Hash::make('password123')]
        );
        $guruUser->assignRole($roleGuru);
    }
}
