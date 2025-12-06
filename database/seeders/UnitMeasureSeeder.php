<?php

namespace Database\Seeders;

use App\Models\UnitofMeasure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitofMeasure::create(['descripcion' => 'TABLETA', 'aka' => 'TAB.']);
        UnitofMeasure::create(['descripcion' => 'CAPSULA', 'aka' => 'CAP.']);
        UnitofMeasure::create(['descripcion' => 'AMPOLLA', 'aka' => 'AMP.']);
        UnitofMeasure::create(['descripcion' => 'INHALADOR', 'aka' => 'INH.']);
        UnitofMeasure::create(['descripcion' => 'FRASCO', 'aka' => 'FSCO.']);
        UnitofMeasure::create(['descripcion' => 'SOBRE', 'aka' => 'SOB.']);
        UnitofMeasure::create(['descripcion' => 'SPRAY', 'aka' => 'SPRY.']);
        UnitofMeasure::create(['descripcion' => 'TRATAMIENTO', 'aka' => 'TTO.']);
        UnitofMeasure::create(['descripcion' => 'CREMA', 'aka' => 'CRM.']);
        UnitofMeasure::create(['descripcion' => 'CAJA', 'aka' => 'CAJ.']);
    }
}
