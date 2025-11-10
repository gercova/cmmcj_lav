<?php

namespace Database\Factories;

use App\Models\BloodGroup;
use App\Models\DegreesInstruction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\History;
use App\Models\Insurance;
use App\Models\MaritalStatus;
use App\Models\UbigeoDistrict;

class HistoryFactory extends Factory {
    
    protected $model = History::class;
    public function definition(): array {
        $ubigeo = UbigeoDistrict::inRandomOrder()->first();
        $gs     = BloodGroup::inRandomOrder()->first();
        $gi     = DegreesInstruction::inRandomOrder()->first();
        $ms     = MaritalStatus::inRandomOrder()->first();
        $seguro = Insurance::inRandomOrder()->first();

        return [
            'tipo_documento_id'         => 1,
            'dni'                       => $this->faker->unique()->randomNumber(8, true),
            'nombres'                   => $this->faker->name(),
            'sexo'                      => $this->faker->randomElement(['M', 'F']),
            'fecha_nacimiento'          => $this->faker->date(),
            'telefono'                  => $this->faker->phoneNumber(),
            'email'                     => $this->faker->unique()->email(),
            'direccion'                 => $this->faker->address(),
            'grupo_sanguineo_id'        => $gs->id,
            'grado_instruccion_id'      => $gi->id,
            'ubigeo_nacimiento'         => $ubigeo->id,
            'ubigeo_residencia'         => $ubigeo->id,
            'ocupacion_id'              => $this->faker->numberBetween(1, 10),
            'estado_civil_id'           => $ms->id,
            'acompanante'               => $this->faker->name(),
            'acompanante_telefono'      => $this->faker->unique()->phoneNumber(),
            'acompanante_direccion'     => $this->faker->address(),
            'vinculo'                   => $this->faker->randomElement(['TIÓ', 'VECINO', 'PRIMO', 'PADRE', 'MADRE', 'HERMANO(A)']),
            'seguro_id'                 => $seguro->id,
            'seguro_descripcion'        => $this->faker->text(20),
            'ant_quirurgicos'           => $this->faker->text(50),
            'ant_patologicos'           => $this->faker->text(50),
            'ant_familiares'            => $this->faker->text(50),
            'ant_medicos'               => $this->faker->text(50),
            'rams'                      => $this->faker->text(50),
            'is_active'                 => '1',
            'created_at'                => $this->faker->dateTimeBetween('2018-01-01', '2025-12-31')->format('Y-m-d H:i:s'),
            'updated_at'                => now(),
        ];
    }
}
