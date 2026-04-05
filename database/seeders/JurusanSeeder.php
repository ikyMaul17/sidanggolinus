<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            // Fakultas Teknik
            array(
                "kode" => "001",
                "nama" => "Teknik Informatika",
                "id_fakultas" => 1,
            ),
            array(
                "kode" => "002",
                "nama" => "Sistem Informasi",
                "id_fakultas" => 1,
            ),
            array(
                "kode" => "003",
                "nama" => "Ilmu Komputer",
                "id_fakultas" => 1,
            ),
            array(
                "kode" => "004",
                "nama" => "Teknik Elektro",
                "id_fakultas" => 2,
            ),
            array(
                "kode" => "005",
                "nama" => "Teknik Sipil",
                "id_fakultas" => 2,
            ),
        
            // Fakultas Ekonomi dan Bisnis
            array(
                "kode" => "006",
                "nama" => "Akuntansi",
                "id_fakultas" => 3,
            ),
            array(
                "kode" => "007",
                "nama" => "Manajemen",
                "id_fakultas" => 3,
            ),
            array(
                "kode" => "008",
                "nama" => "Ekonomi Pembangunan",
                "id_fakultas" => 3,
            ),
            array(
                "kode" => "009",
                "nama" => "Ilmu Komunikasi",
                "id_fakultas" => 5, // Bisa juga masuk ke Fakultas Ilmu Sosial
            ),
        
            // Fakultas Ilmu Sosial dan Ilmu Politik
            array(
                "kode" => "010",
                "nama" => "Ilmu Politik",
                "id_fakultas" => 4,
            ),
            array(
                "kode" => "011",
                "nama" => "Sosiologi",
                "id_fakultas" => 5,
            ),
            array(
                "kode" => "012",
                "nama" => "Antropologi",
                "id_fakultas" => 5,
            ),
        
            // Fakultas Hukum
            array(
                "kode" => "013",
                "nama" => "Hukum",
                "id_fakultas" => 4,
            ),
        
            // Fakultas Kedokteran
            array(
                "kode" => "014",
                "nama" => "Kedokteran",
                "id_fakultas" => 6,
            ),
            array(
                "kode" => "015",
                "nama" => "Farmasi",
                "id_fakultas" => 6,
            ),
        );

        foreach ($data as $raw_data) {
            Jurusan::insert($raw_data);
        }
    }
}
