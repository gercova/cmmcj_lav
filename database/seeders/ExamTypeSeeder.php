<?php

namespace Database\Seeders;

use App\Models\ExamType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamType::create(['descripcion' => 'Primera Consulta']);
        ExamType::create(['descripcion' => 'Ginecología']);
        ExamType::create(['descripcion' => 'Examen']);
        ExamType::create(['descripcion' => 'Reevaluación']);
        ExamType::create(['descripcion' => 'Control']);
    }
}
