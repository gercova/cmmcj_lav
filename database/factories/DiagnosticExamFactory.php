<?php

namespace Database\Factories;

use App\Models\Diagnosis;
use App\Models\DiagnosticExam;
use App\Models\Exam;
use App\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiagnosticExam>
 */
class DiagnosticExamFactory extends Factory
{
    protected $model = DiagnosticExam::class;
    public function definition(): array {
        // Obtener un ID de historia y examen que realmente exista
        $historia       = History::inRandomOrder()->first();
        $examen         = Exam::inRandomOrder()->first();
        $diagnostico    = Diagnosis::inRandomOrder()->first();
        // Si no hay historias, crear una primero
        if (!$historia) {
            $historia = History::factory()->create();
        }
        // Si no hay examenes, crear uno primero
        if (!$examen) {
            $examen = Exam::factory()->create();
        }

        return [
            'examen_id'         => $examen->id,
            'historia_id'       => $historia->id,
            'diagnostico_id'    => $diagnostico->id,
            'created_at'        => $this->faker->dateTimeBetween('2010-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'updated_at'        => now(),
        ];
    }
}
