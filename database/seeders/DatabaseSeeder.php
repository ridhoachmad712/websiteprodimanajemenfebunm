<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun admin awal (idempoten). Ganti kata sandi setelah login pertama.
        User::updateOrCreate(
            ['email' => 'admin@manajemenunm.test'],
            [
                'name' => 'Admin Manajemen',
                'password' => Hash::make('password123'),
            ],
        );

        $this->call([
            CategorySeeder::class,
            SettingSeeder::class,
            MenuSeeder::class,
            DosenSeeder::class,
            PostSeeder::class,
            PageSeeder::class,
            PrestasiSeeder::class,
            DownloadSeeder::class,
        ]);
    }
}
