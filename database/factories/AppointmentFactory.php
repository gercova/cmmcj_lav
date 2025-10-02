<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory {

    protected $model = Appointment::class;
    public function definition(): array {
        // Obtener un ID de historia y examen que realmente exista
        $historia = History::inRandomOrder()->first();
        // Si no hay historias, crear una primero
        if (!$historia) {
            $historia = History::factory()->create();
        }

        return [
            'historia_id'       => $historia->id,
            'estado_cita_id'    => $this->faker->randomElement([1, 2, 3, 4]),
            'created_at'        => $this->faker->dateTimeBetween('2010-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'updated_at'        => now(),
        ];
    }
}
