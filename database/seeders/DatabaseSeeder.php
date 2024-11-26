<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Membuat Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminpassword'), // password di-hash dengan bcrypt
            'role' => 'admin',
            'no_identitas' => $this->generateNoIdentitas(), // Menambahkan no_identitas
        ]);

        // Membuat Staff
        User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('staffpassword'),
            'role' => 'staff',
            'no_identitas' => $this->generateNoIdentitas(), // Menambahkan no_identitas
        ]);

        // Membuat User
        User::create([
            'name' => 'Regular',
            'email' => 'user@example.com',
            'password' => Hash::make('userpassword'),
            'role' => 'user',
            'no_identitas' => $this->generateNoIdentitas(), // Menambahkan no_identitas
        ]);
    }

    // Fungsi untuk menghasilkan no_identitas dengan 14 digit angka acak
    private function generateNoIdentitas()
    {
        return rand(10000000000000, 99999999999999); // 14 digit angka acak
    }
}
