<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create(['descripcion' => 'Administrador']);
        Profile::create(['descripcion' => 'Especialista']);
        Profile::create(['descripcion' => 'Asistente']);
    }
}
