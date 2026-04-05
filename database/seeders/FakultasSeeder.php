<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                "kode" => "001",
                "nama" => "Fakultas Ilmu Komputer",
            ),
            array(
                "kode" => "002",
                "nama" => "Fakultas Teknik",
            ),
            array(
                "kode" => "003",
                "nama" => "Fakultas Ekonomi dan Bisnis",
            ),
            array(
                "kode" => "004",
                "nama" => "Fakultas Hukum",
            ),
            array(
                "kode" => "005",
                "nama" => "Fakultas Ilmu Sosial dan Ilmu Politik",
            ),
            array(
                "kode" => "006",
                "nama" => "Fakultas Kedokteran",
            ),
            array(
                "kode" => "007",
                "nama" => "Fakultas Psikologi",
            ),
            array(
                "kode" => "008",
                "nama" => "Fakultas Keguruan dan Ilmu Pendidikan",
            ),
        );

        foreach ($data as $raw_data) {
            Fakultas::insert($raw_data);
        }
    }
}
