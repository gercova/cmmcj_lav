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
        Module::create(['descripcion' => 'dashboard']);
        Module::create(['descripcion' => 'documentos']);
        Module::create(['descripcion' => 'mantenimiento']);
        Module::create(['descripcion' => 'seguridad']);
    }
}
