<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeguroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Insurance::create(['descripcion' => 'EsSalud']);
        Insurance::create(['descripcion' => 'SIS']);
        Insurance::create(['descripcion' => 'Rimac Seguros']);
        Insurance::create(['descripcion' => 'Pacifico Seguros']);
        Insurance::create(['descripcion' => 'Mapfre']);
        Insurance::create(['descripcion' => 'Otros']);
    }
}
