<?php

namespace Database\Seeders;

use App\Models\Bed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bed::create(['description' => 'CAMA 1B', 'floor' => '2 PISO', 'detail' => 'CAMA UCI']);
        Bed::create(['description' => 'CAMA 2B', 'floor' => '2 PISO', 'detail' => 'CAMA NORMAL']);
        Bed::create(['description' => 'CAMA 3B', 'floor' => '2 PISO', 'detail' => 'CAMA NORMAL']);
        Bed::create(['description' => 'CAMA 4B', 'floor' => '2 PISO', 'detail' => 'CAMA NORMAL']);
    }
}
