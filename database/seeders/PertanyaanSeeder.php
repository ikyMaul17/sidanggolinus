<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PertanyaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            'safety' => [
                // 'Seberapa aman kondisi pegangan tangan untuk penumpang berdiri?',
                // 'Seberapa aman kondisi tangga bus saat naik/turun?',
                // 'Seberapa aman kaca jendela (tidak macet/longgar)?',
                // 'Seberapa aman bangku penumpang (tidak goyah)?',
                // 'Seberapa aman perasaan Anda terhadap kecepatan bus?',
                // 'Seberapa aman kondisi lantai bus (tidak licin)?',
                // 'Seberapa aman perjalanan saat bus mengerem?',
                // 'Seberapa aman perjalanan saat bus berbelok?',
                // 'Seberapa aman perjalanan saat bus melewati jalan rusak?',
                // 'Seberapa aman kapasitas penumpang (tidak penuh berlebihan)?',
                // 'Seberapa aman pengendalian sopir dari sisi penumpang?',
            ],
            'operational' => [
                // 'Seberapa baik fungsi AC dari sudut pandang penumpang?',
                // 'Seberapa lancar perjalanan bus tanpa berhenti mendadak?',
                // 'Seberapa lancar bus saat melewati polisi tidur?',
                // 'Seberapa lancar aliran udara kabin?',
                // 'Seberapa baik pencahayaan kabin belakang?',
                // 'Seberapa lancar perjalanan tanpa guncangan berlebihan?',
                // 'Seberapa tepat waktu berhenti di halte (tidak berlebihan)?',
                // 'Seberapa jarang bus melakukan ngetem terlalu lama?',
                // 'Seberapa lancar supir mengatur kecepatan selama perjalanan?',
                // 'Seberapa sedikit bus mengalami berhenti tiba-tiba?',
                // 'Seberapa lancar perjalanan dari fleksibilitas rute bus?',
                // 'Seberapa konsisten bus beroperasi tanpa kendala di tengah jalan?',
                // 'Seberapa baik kualitas suara mesin dari sisi penumpang?',
            ],
            'comfort' => [
                // 'Seberapa nyaman Anda dengan cara supir mengemudikan bus (ugal-ugalan atau tidak)?',
                // 'Seberapa nyaman Anda dengan cara supir melakukan pengereman?',
                // 'Seberapa nyaman Anda dengan kestabilan bus saat supir berbelok?',
                // 'Seberapa nyaman Anda dengan kecepatan berkendara supir selama perjalanan?',
                // 'Seberapa nyaman Anda terhadap sikap supir selama mengemudi?',
                // 'Seberapa nyaman Anda dengan komunikasi atau respon supir terhadap penumpang?',
                // 'Seberapa nyaman Anda dengan cara supir menghindari jalan rusak atau lubang?',
                // 'Seberapa nyaman Anda dengan cara supir menghadapi kemacetan atau situasi padat?',
                // 'Seberapa nyaman Anda dengan cara supir menjaga jarak aman dari kendaraan lain?',
                // 'Seberapa nyaman Anda dengan cara supir mengemudi saat kondisi hujan?',
                // 'Seberapa nyaman Anda dengan gaya mengemudi supir secara keseluruhan?',
                // 'Seberapa nyaman suhu AC di tempat duduk Anda?',
                // 'Seberapa nyaman kursi penumpang untuk diduduki?',
                // 'Seberapa bersih kabin bus dari debu/kotoran?',
                // 'Seberapa nyaman bau udara di dalam bus?',
                // 'Seberapa rendah tingkat kebisingan di kabin penumpang?',
                // 'Seberapa halus getaran bus yang Anda rasakan?',
                // 'Seberapa nyaman kepadatan penumpang?',
                // 'Seberapa nyaman guncangan saat bus mengerem?',
                // 'Seberapa bersih jendela bus?',
                // 'Seberapa nyaman aroma pewangi dalam bus?',
                // 'Seberapa nyaman perjalanan saat Anda menaiki bus dari awal hingga akhir?',
                // 'Seberapa nyaman kondisi tempat duduk di halte saat menunggu?',
                // 'Seberapa nyaman kebersihan lingkungan halte?',
                // 'Seberapa nyaman kondisi perlindungan halte terhadap panas dan hujan?',
                // 'Seberapa nyaman lantai halte dari segi kebersihan dan keamanan?',
                // 'Seberapa nyaman kondisi halte ketika banyak penumpang menunggu?',
                // 'Seberapa nyaman rasa aman Anda saat menunggu di halte?',
                // 'Seberapa nyaman akses menuju halte (jalan setapak atau trotoar)?',
                // 'Seberapa nyaman jarak halte dengan titik pickup yang Anda tuju?',
                // 'Seberapa nyaman halte secara keseluruhan sebagai tempat menunggu bus?',
            ],
        ];

        $now = now();

        foreach ($categories as $category => $questions) {
            foreach ($questions as $question) {
                DB::table('pertanyaan')->insert([
                    'kategori' => $category,
                    'teks_pertanyaan' => $question,
                    'status' => 'aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

    }
}
