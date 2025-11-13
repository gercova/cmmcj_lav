<?php

namespace Database\Seeders;

use App\Models\HospitalizationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalizationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HospitalizationStatus::create(['nombre' => 'Activa']);
        HospitalizationStatus::create(['nombre' => 'Cerrada']);
        HospitalizationStatus::create(['nombre' => 'Cancelada']);
    }
}
