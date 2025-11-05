<?php

namespace Database\Factories;

use App\Models\Bed;
use App\Models\History;
use App\Models\Hospitalization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospitalization>
 */
class HospitalizationFactory extends Factory
{
    
    protected $model = Hospitalization::class;
    public function definition(): array {
        // Obtener un ID de historia que realmente exista
        $historia   = History::inRandomOrder()->first();
        $cama       = Bed::inRandomOrder()->first();
        // Si no hay historias, crear una primero
        if (!$historia) {
            $historia = History::factory()->create();
        }

        return [
            'historia_id'       => $historia->id,
            'bed_id'            => $cama->id,
            'fc'                => $this->faker->numberBetween(60, 100).' lt. x min', 
            't'                 => '36 C°', 
            'so2'               => '98%',
            'vital_functions'   => $this->faker->text(30), 
            'observations'      => $this->faker->text(100), 
            'others'            => $this->faker->text(50),
            'created_at'        => $this->faker->dateTimeBetween('2010-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
        ];
    }
}
