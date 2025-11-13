<?php

namespace Database\Seeders;

use App\Models\DischargeCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DischargeConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DischargeCondition::create(['nombre' => 'Curado']);
        DischargeCondition::create(['nombre' => 'Mejorado']);
        DischargeCondition::create(['nombre' => 'Sin Mejoría']);
        DischargeCondition::create(['nombre' => 'Fallecido']);
    }
}
