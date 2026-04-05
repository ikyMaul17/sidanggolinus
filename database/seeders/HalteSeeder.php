<?php

namespace Database\Seeders;

use App\Models\HaltePergi;
use App\Models\HaltePulang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HalteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $halterPergi = [
        [
            'nama' => 'Halte A',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ],
        [
            'nama' => 'Halte B',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ],
        [
            'nama' => 'Halte C',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ]
       ];

       $haltePulang = [
        [
            'nama' => 'Halte D',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ],
        [
            'nama' => 'Halte E',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ],
        [
            'nama' => 'Halte F',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ]
       ];

         foreach ($halterPergi as $data) {
          HaltePergi::create([
                'nama' => $data['nama'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
          ]);
         }

         foreach ($haltePulang as $data) {
          HaltePulang::create([
                'nama' => $data['nama'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
          ]);
         }
    }
}
