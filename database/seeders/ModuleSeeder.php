<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create(['descripcion' => 'principal']);
        Module::create(['descripcion' => 'home']);
        Module::create(['descripcion' => 'reporte']);
        Module::create(['descripcion' => 'documento']);
        Module::create(['descripcion' => 'mantenimiento']);
        Module::create(['descripcion' => 'negocio']);
        Module::create(['descripcion' => 'seguridad']);
    }
}
