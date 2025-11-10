<?php

namespace Database\Seeders;

use App\Models\MaritalStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaritalStatus::create(['descripcion' => 'Soltero(a)']);
        MaritalStatus::create(['descripcion' => 'Casado(a)']);
        MaritalStatus::create(['descripcion' => 'Divorciado(a)']);
        MaritalStatus::create(['descripcion' => 'Viudo(a)']);
        MaritalStatus::create(['descripcion' => 'Conviviente']);
    }
}
