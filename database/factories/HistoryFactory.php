<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\History;
use App\Models\UbigeoDistrict;

class HistoryFactory extends Factory {
    
    protected $model = History::class;
    public function definition(): array {
        $ubigeo = UbigeoDistrict::inRandomOrder()->first();
        return [
            'tipo_documento_id'         => 1,
            'dni'                       => $this->faker->unique()->randomNumber(8, true),
            'nombres'                   => $this->faker->name(),
            'sexo'                      => $this->faker->randomElement(['M', 'F']),
            'fecha_nacimiento'          => $this->faker->date(),
            'telefono'                  => $this->faker->phoneNumber(),
            'email'                     => $this->faker->unique()->email(),
            'direccion'                 => $this->faker->address(),
            'grupo_sanguineo_id'        => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'grado_instruccion_id'      => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'ubigeo_nacimiento'         => $ubigeo->id,
            'ubigeo_residencia'         => $ubigeo->id,
            'ocupacion_id'              => $this->faker->numberBetween(1, 10),
            'estado_civil_id'           => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'acompanante'               => $this->faker->name(),
            'acompanante_telefono'      => $this->faker->unique()->phoneNumber(),
            'acompanante_direccion'     => $this->faker->address(),
            'vinculo'                   => $this->faker->randomElement(['TIÓ', 'VECINO', 'PRIMO', 'PADRE', 'MADRE', 'HERMANO(A)']),
            'seguro_id'                 => $this->faker->randomElement([1, 2, 3]),
            'seguro_descripcion'        => $this->faker->text(),
            'ant_quirurgicos'  => $this->faker->text(),
            'ant_patologicos'  => $this->faker->text(),
            'ant_familiares'   => $this->faker->text(),
            'ant_medicos'      => $this->faker->text(),
            'rams'                      => $this->faker->text(),
            'is_active'                 => '1',
            'created_at'                => $this->faker->dateTimeBetween('2018-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'updated_at'                => now(),
        ];
    }
}
