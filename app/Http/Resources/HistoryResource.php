<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tipo_documento_id' => $this->tipo_documento_id,
            'dni'               => $this->dni,
            'nombres'           => $this->nombres,
            'sexo'              => $this->sexo,
            'fecha_nacimiento'  => $this->fecha_nacimiento,
            'telefono'          => $this->telefono,
            'email'             => $this->email,
            'direccion'         => $this->direccion,
            'grupo_sanguineo_id'    => $this->grupo_sanguineo_id,
            'grado_instruccion_id'  => $this->grado_instruccion_id,
            'ubigeo_nacimiento' => $this->ubigeo_nacimiento,
            'ubigeo_residencia' => $this->ubigeo_residencia,
            'ocupacion_id'      => $this->ocupacion_id,
            'estado_civil_id'   => $this->estado_civil_id,
            'acompanante'       => $this->acompanante,
            'acompanante_telefono'  => $this->acompanante_telefono,
            'acompanante_direccion' => $this->acompanante_direccion,
            'vinculo'           => $this->vinculo,
            'seguro_id'         => $this->seguro_id,
            'seguro_descripcion' => $this->seguro_descripcion,
            'ant_quirurgicos'   => $this->ant_quirurgicos,
            'ant_patologicos'   => $this->ant_patologicos,
            'ant_familiares'    => $this->ant_familiares,
            'ant_medicos'       => $this->ant_medicos,
            'rams'              => $this->rams,
        ];
    }
}
