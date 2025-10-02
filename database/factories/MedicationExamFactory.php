<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Exam;
use App\Models\History;
use App\Models\MedicationExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationExamFactory extends Factory {

    protected $model = MedicationExam::class;
    public function definition(): array {
        // Obtener un ID de historia y examen que realmente exista
        $historia       = History::inRandomOrder()->first();
        $examen         = Exam::inRandomOrder()->first();
        $farmaco        = Drug::inRandomOrder()->first();
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
            'farmaco_id'        => $farmaco->id,
            'descripcion'       => $this->faker->text(20),
            'dosis'             => $this->faker->text(10),
            'frecuencia'        => $this->faker->text(10),
            'created_at'        => $this->faker->dateTimeBetween('2010-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'updated_at'        => now(),
        ];
    }
}
