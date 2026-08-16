<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Peran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@papua-pegunungan.pks.id'],
            [
                'name'     => 'Admin DPW',
                'password' => Hash::make('rahasia123'),
                'role'     => Peran::ADMIN,
            ]
        );

        $this->call(MenuSeeder::class);
        $this->call(KontenSeeder::class);
    }
}
