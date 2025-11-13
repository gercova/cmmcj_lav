<?php

namespace Database\Seeders;

use App\Models\AdmissionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdmissionType::create(['nombre' => 'Programada']);
        AdmissionType::create(['nombre' => 'Urgencia']);
        AdmissionType::create(['nombre' => 'Emergencia']);
        AdmissionType::create(['nombre' => 'Transferencia']);
    }
}
