<?php

namespace Database\Seeders;

use App\Models\AppointmentsStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentsStatus::create(['descripcion' => 'PENDIENTE']);
        AppointmentsStatus::create(['descripcion' => 'ATENDIDO']);
        AppointmentsStatus::create(['descripcion' => 'PROGRAMADO']);
        AppointmentsStatus::create(['descripcion' => 'CANCELADO']);
    }
}
