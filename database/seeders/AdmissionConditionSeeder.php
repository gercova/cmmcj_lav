<?php

namespace Database\Seeders;

use App\Models\AdmissionCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdmissionCondition::create(['nombre' => 'Estable']);
        AdmissionCondition::create(['nombre' => 'Grave']);
        AdmissionCondition::create(['nombre' => 'Muy Grave']);
        AdmissionCondition::create(['nombre' => 'Crítico']);
    }
}
