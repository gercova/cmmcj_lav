<?php

namespace Database\Seeders;

use App\Models\CarefulType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarefulTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarefulType::create(['nombre' => 'Básico']);
        CarefulType::create(['nombre' => 'Intermedio']);
        CarefulType::create(['nombre' => 'Intensivo']);
        CarefulType::create(['nombre' => 'Monitorizado']);
    }
}
