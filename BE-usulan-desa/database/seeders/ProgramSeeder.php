<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            'name' => 'Pembuatan Warnet Gaming',
            'description' => 'Didasari oleh berkembangnya ranah Esports di Indonesia, saya rasa tidak ada salahnya kita menyalurkan bakat anak anak kampung kita dengan melalui pembuatan warnet gaming. \nHal ini juga menurut saya dapat mewadahi talenta talenta muda kampung kita sehingga tidak kalah dengan kampung sebelah, selain itu ini dapat menghindarkan anak muda kampung kita terjerumus pada hal negatif.',
            'start_date' => '2024-06-05',
            'end_date' => '2024-06-08',
            'status' => 'approved',
            'target' => 'Memfasilitasi Talenta Muda',
            'userID' => 2,
            'suggestionID' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
