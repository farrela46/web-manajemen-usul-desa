<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            'comment' => 'Setuju, rank saya Radiant merasa sia sia kalo ga di support. Tolong minimal RTX 4070 , monitor 540hz , Intel Core i 9 ya , dengan peripheral yang maksimal maka potensi talenta akan maksimal',
            'suggestionID' => 1,
            'userID' => 3,
        ]);

        DB::table('comments')->insert([
            'comment' => 'Setuju, rank saya Radiant merasa sia sia kalo ga di support. Tolong minimal RTX 4070 , monitor 540hz , Intel Core i 9 ya , dengan peripheral yang maksimal maka potensi talenta akan maksimal',
            'suggestionID' => 1,
            'userID' => 4,
        ]);

        DB::table('comments')->insert([
            'comment' => 'Saya sangat setuju, warga harus sangat jauh sekali berjalan ke desa sebelah hanya untuk berak saja',
            'suggestionID' => 2,
            'userID' => 2,
        ]);
    }
}
