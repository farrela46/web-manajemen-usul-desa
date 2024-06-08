<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ProgramSeeder;
use Database\Seeders\SuggestionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
        $this->call([
            SuggestionSeeder::class,
        ]);
        $this->call([
            CommentSeeder::class,
        ]);
        $this->call([
            ProgramSeeder::class,
        ]);
    }
}
