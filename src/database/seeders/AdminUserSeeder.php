<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuario')->insert([
            'email_usuario' => 'admin@example.com',
            'senha_usuario' => Hash::make('admin123'), // Never store plain passwords
            'role_usuario'  => 1, // 1 = admin
            'nome_usuario'  => 'Administrator',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
