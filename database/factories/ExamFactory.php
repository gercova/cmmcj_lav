<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
   
    protected $model = Exam::class;
    public function definition(): array {
        // Obtener un ID de historia que realmente exista
        $historia = History::inRandomOrder()->first();
        
        // Si no hay historias, crear una primero
        if (!$historia) {
            $historia = History::factory()->create();
        }

        // Generar peso y talla con valores realistas
        $peso = $this->faker->randomFloat(2, 50, 110); // Entre 50kg y 110kg
        $talla = $this->faker->randomFloat(2, 1.50, 2.05); // Entre 1.50m y 2.05m
        
        // Calcular IMC correctamente
        $imc = $talla > 0 ? $peso / ($talla * $talla) : 0;

        return [
            'historia_id'       => $historia->id,
            'examen_tipo_id'    => $this->faker->numberBetween(1, 2),
            'pa'                => $this->faker->text(30),
            'fc'                => $this->faker->text(30),
            'fr'                => $this->faker->text(30),
            't'                 => $this->faker->text(30),
            'peso'              => $peso,
            'talla'             => $talla,
            'imc'               => round($imc, 2),
            'motivo_consulta'   => $this->faker->text(),
            'm'                 => $this->faker->text(),
            'rc'                => $this->faker->text(),
            'g'                 => $this->faker->text(),
            'p'                 => $this->faker->text(),
            'r1'                => $this->faker->randomElement(['V', 'C']),
            'u_parto'           => $this->faker->text(),
            'u_pap'             => $this->faker->text(),
            'u_ivaa'            => $this->faker->text(),
            'mac_id'            => $this->faker->numberBetween(1, 5),
            'fum'               => $this->faker->date(),
            'fpp'               => $this->faker->date(),
            'edad_gestacional'  => '',
            'apreciacion_general'   => $this->faker->text(),
            'piel_mucosas'          => $this->faker->text(),
            'aparato_respiratorio'  => $this->faker->text(),
            'cardio_vascular'   => $this->faker->text(),
            'abdomen'           => $this->faker->text(),
            'd_abm'             => $this->faker->text(50),
            'i_abm'             => $this->faker->text(50),
            'ap_abm'            => $this->faker->text(50),
            'c_abm'             => $this->faker->text(50),
            'p_abm'             => $this->faker->text(50),
            'mo_abm'            => $this->faker->text(50),
            'io_ro_abm'         => $this->faker->randomElement(['oi', 'ro']),
            'genito_urinario'   => $this->faker->text(),
            'neurologico'       => $this->faker->text(),
            'au'                => $this->faker->text(50),
            'spp'               => $this->faker->text(50),
            'lcf'               => $this->faker->text(50),
            'du'                => $this->faker->text(50),
            'mf'                => $this->faker->text(50),
            'oh'                => $this->faker->text(),
            'psc_prox_1'        => $this->faker->randomElement(['ok', '']),
            'psc_prox_2'        => $this->faker->randomElement(['ok', '']),
            'psc_prox_3'        => $this->faker->randomElement(['ok', '']),
            'psc_prox_4'        => $this->faker->randomElement(['ok', '']),
            'psc_prox_5'        => $this->faker->randomElement(['ok', '']),
            'psc_prox_6'        => $this->faker->randomElement(['ok', '']),
            'nutricion'         => $this->faker->randomElement(['ok', '']),
            'psicologia_1'      => $this->faker->randomElement(['ok', '']),
            'psicologia_2'      => $this->faker->randomElement(['ok', '']),
            'psicologia_3'      => $this->faker->randomElement(['ok', '']),
            'psicologia_4'      => $this->faker->randomElement(['ok', '']),
            'pezon'             => $this->faker->randomElement(['okp1', 'okp2', 'okp3']),
            'recomendaciones'   => $this->faker->text(),
            'is_active'         => 1,
            'created_at'        => $this->faker->dateTimeBetween('2010-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
        ];
    }
}
