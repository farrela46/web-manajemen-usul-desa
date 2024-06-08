<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'nama' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        DB::table('users')->insert([
            'nama' => 'Andi',
            'email' => 'andi@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'unverified',
            'role' => 'user',
        ]);

        DB::table('users')->insert([
            'nama' => 'Budi',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'unverified',
            'role' => 'user',
        ]);

        DB::table('users')->insert([
            'nama' => 'vivi',
            'email' => 'vivi@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'unverified',
            'role' => 'user',
        ]);
    }
}
