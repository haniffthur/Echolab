<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus user yang mungkin sudah ada dengan email yang sama
        User::where('email', 'admin@vms.test')->delete();

        // Buat User Admin Utama
        User::create([
            'name' => 'Admin VMS',
            'email' => 'admin@vms.test',
            'password' => Hash::make('password'), // Ganti password ini saat production
        ]);

        // Tampilkan info login di terminal setelah seeding
        $this->command->info('User Admin berhasil dibuat:');
        $this->command->line('Email: admin@vms.test');
        $this->command->line('Password: password');
    }
}