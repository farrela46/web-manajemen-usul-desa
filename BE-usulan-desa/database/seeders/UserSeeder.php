<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
            'created_at' => Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('users')->insert([
            'nama' => 'Andi',
            'email' => 'andi@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'unverified',
            'NIK' => 4859384759384759,
            'role' => 'user',
            'created_at' => Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('users')->insert([
            'nama' => 'Budi',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'unverified',
            'NIK' => 4859384759384754,
            'role' => 'user',
            'created_at' => Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('users')->insert([
            'nama' => 'vivi',
            'email' => 'vivi@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'unverified',
            'NIK' => 4859384759384753,
            'role' => 'user',
            'created_at' => Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);
    }
}
