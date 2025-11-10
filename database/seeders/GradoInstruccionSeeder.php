<?php

namespace Database\Seeders;

use App\Models\DegreesInstruction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradoInstruccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DegreesInstruction::create(['nombre' => 'Primaria completa']);
        DegreesInstruction::create(['nombre' => 'Primaria incompleta']);
        DegreesInstruction::create(['nombre' => 'Secundaria completa']);
        DegreesInstruction::create(['nombre' => 'Secundaria incompleta']);
        DegreesInstruction::create(['nombre' => 'Universitaria Completa']);
        DegreesInstruction::create(['nombre' => 'Universitaria incompleta']);
        DegreesInstruction::create(['nombre' => 'Técnica completa']);
        DegreesInstruction::create(['nombre' => 'Técnica incompleta']);
        DegreesInstruction::create(['nombre' => 'Sin grado']);
    }
}
