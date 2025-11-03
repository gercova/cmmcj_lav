<?php

namespace Database\Seeders;

use App\Models\ContraceptiveMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContraceptiveMethod::create(['descripcion' => 'Píldoras anticonceptivas']);
        ContraceptiveMethod::create(['descripcion' => 'Implante subdérmico']);
        ContraceptiveMethod::create(['descripcion' => 'Inyecciones']);
        ContraceptiveMethod::create(['descripcion' => 'Parche anticonceptivo']);
        ContraceptiveMethod::create(['descripcion' => 'Anillo vaginal']);
        ContraceptiveMethod::create(['descripcion' => 'DIU']);
        ContraceptiveMethod::create(['descripcion' => 'Ligadura de trompas']);
    }
}
