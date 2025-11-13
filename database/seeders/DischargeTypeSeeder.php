<?php

namespace Database\Seeders;

use App\Models\DischargeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DischargeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DischargeType::create(['nombre' => 'Alta Médica']);
        DischargeType::create(['nombre' => 'Alta Voluntaria']);
        DischargeType::create(['nombre' => 'Transferencia']);
        DischargeType::create(['nombre' => 'Fallecimiento']);
        DischargeType::create(['nombre' => 'Otro']);
    }
}
