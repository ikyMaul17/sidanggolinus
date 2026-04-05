<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = [
            [
                'nama' => 'Bus Kampus 01',
                'plat_no' => 'BK 1234 AA',
                'kapasitas' => 40,
                'rute' => 'pulang',
            ],
            [
                'nama' => 'Bus Kampus 02',
                'plat_no' => 'BK 2345 AB',
                'kapasitas' => 35,
                'rute' => 'pulang',
            ],
            [
                'nama' => 'Bus Kampus 03',
                'plat_no' => 'BK 3456 AC',
                'kapasitas' => 45,
                'rute' => 'pergi',
            ],
            [
                'nama' => 'Bus Kampus 04',
                'plat_no' => 'BK 4567 AD',
                'kapasitas' => 30,
                'rute' => 'pergi',
            ],
        ];

        $now = now();

        foreach ($buses as $data) {
            $bus = Bus::create([
                'nama' => $data['nama'],
                'plat_no' => $data['plat_no'],
                'kapasitas' => $data['kapasitas'],
                'rute' => $data['rute'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $drivers = [
            'supir1@gmail.com' => 'Bus Kampus 01',
            'supir2@gmail.com' => 'Bus Kampus 02',
        ];

        foreach ($drivers as $email => $busName) {
            $bus = Bus::where('nama', $busName)->first();
            $driver = User::where('email', $email)->first();
            if ($driver) {
                $driver->id_bus = $bus->id;
                $driver->bk_bus = $bus->plat_no;
                $driver->save();
            }
        }

    }
}
