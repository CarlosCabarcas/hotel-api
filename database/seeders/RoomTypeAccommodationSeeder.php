<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeAccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $standard = RoomType::where('name', 'Estándar')->first();
        $junior = RoomType::where('name', 'Junior')->first();
        $suite = RoomType::where('name', 'Suite')->first();

        $standard->accommodations()->sync([1, 2]);
        $junior->accommodations()->sync([3, 4]);
        $suite->accommodations()->sync([1, 2, 3]);
    }
}
