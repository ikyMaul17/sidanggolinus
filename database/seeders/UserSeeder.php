<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
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
				"nama"		=>	"admin",
                "username"  =>	"admin",
				"email"		=>	"admin@gmail.com",
				"password"	=>	bcrypt('admin'),
				"role"		=>	"admin",
                "no_wa"		=>	"1234567890",
                "status"    => "aktif",
            ),
            array(
				"nama"		=>	"penumpang1",
                "username"  =>	"penumpang1",
				"email"		=>	"penumpang1@gmail.com",
				"password"	=>	bcrypt('penumpang1'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Laki-laki",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang2",
                "username"  =>	"penumpang2",
				"email"		=>	"penumpang2@gmail.com",
				"password"	=>	bcrypt('penumpang2'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang3",
                "username"  =>	"penumpang3",
				"email"		=>	"penumpang3@gmail.com",
				"password"	=>	bcrypt('penumpang3'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang4",
                "username"  =>	"penumpang4",
				"email"		=>	"penumpang4@gmail.com",
				"password"	=>	bcrypt('penumpang4'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang5",
                "username"  =>	"penumpang5",
				"email"		=>	"penumpang5@gmail.com",
				"password"	=>	bcrypt('penumpang5'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang6",
                "username"  =>	"penumpang6",
				"email"		=>	"penumpang6@gmail.com",
				"password"	=>	bcrypt('penumpang6'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang7",
                "username"  =>	"penumpang7",
				"email"		=>	"penumpang7@gmail.com",
				"password"	=>	bcrypt('penumpang7'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"penumpang8",
                "username"  =>	"penumpang8",
				"email"		=>	"penumpang8@gmail.com",
				"password"	=>	bcrypt('penumpang8'),
				"role"		=>	"penumpang",
                "id_jurusan"		=>	1,
                "id_fakultas"		=>	1,
                "nim"		=>	"12345",
                "no_wa"		=>	"1234567890",
                "jk"		=>	"Perempuan",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"supir1",
                "username"  =>	"supir1",
				"email"		=>	"supir1@gmail.com",
				"password"	=>	bcrypt('supir1'),
				"role"		=>	"supir",
                "bk_bus"	=>	"12345",
                "no_wa"		=>	"1234567890",
                "status"    => "aktif",
			),
            array(
				"nama"		=>	"supir2",
                "username"  =>	"supir2",
				"email"		=>	"supir2@gmail.com",
				"password"	=>	bcrypt('supir2'),
				"role"		=>	"supir",
                "bk_bus"	=>	"12345",
                "no_wa"		=>	"1234567890",
                "status"    => "aktif",
			)
		);

        foreach ($data as $raw_data) {
            User::insert($raw_data);
        }
    }
}
