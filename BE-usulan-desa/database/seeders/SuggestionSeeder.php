<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suggestions')->insert([
            'userID' => 2,
            'suggestion' => 'Pembuatan Warnet Gaming',
            'description' => 'Didasari oleh berkembangnya ranah Esports di Indonesia, saya rasa tidak ada salahnya kita menyalurkan bakat anak anak kampung kita dengan melalui pembuatan warnet gaming. Hal ini juga menurut saya dapat mewadahi talenta talenta muda kampung kita sehingga tidak kalah dengan kampung sebelah, selain itu ini dapat menghindarkan anak muda kampung kita terjerumus pada hal negatif.',
            'status' => 'approved',
            'date' => '2024-06-04',
        ]);

        DB::table('suggestions')->insert([
            'userID' => 3,
            'suggestion' => 'Pengadaan Ponten Umum',
            'description' => 'Menyikapi Permintaan user Mayo 300 warga desa butuh tempat berak, apakah warga setuju?',
            'status' => 'rejected',
            'date' => '2024-06-04',
        ]);
    }
}
