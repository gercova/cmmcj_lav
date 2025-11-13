<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create(['nombre' => 'Medicina Interna']);
        Service::create(['nombre' => 'Cirugía']);
        Service::create(['nombre' => 'Pediatría']);
        Service::create(['nombre' => 'Obstetricia']);
        Service::create(['nombre' => 'Traumatología']);
        Service::create(['nombre' => 'Cardiología']);
        Service::create(['nombre' => 'Neurología']);
        Service::create(['nombre' => 'Ginecología']);
        Service::create(['nombre' => 'Neumología']);
        Service::create(['nombre' => 'UCI']);
        Service::create(['nombre' => 'UCIN']);
        Service::create(['nombre' => 'Otro']);
    }
}
