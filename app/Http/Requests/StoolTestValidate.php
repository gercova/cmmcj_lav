<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoolTestValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'examen_id'             => 'required',
            'historia_id'           => 'required',
            'consistencia'          => 'string|nullable',
            'color'                 => 'string|nullable',
            'mucus'                 => 'string|nullable',
            'sangre'                => 'string|nullable',
            'restos_alimenticios'   => 'string|nullable',
            'leucocitos'            => 'string|nullable',
            'hematies'              => 'string|nullable',
            'bacterias'             => 'string|nullable',
            'levaduras'             => 'string|nullable',
            'parasitos'             => 'string|nullable',
            'huevos_parasitos'      => 'string|nullable',
            'sangre_oculta'         => 'string|nullable',
            'ph'                    => 'numeric|nullable',
            'grasa_fecal'           => 'string|nullable',
            'cultivo_bacteriano'    => 'string|nullable',
            'sensibilidad_antimicrobiana' => 'string|nullable',
            'observaciones' => 'string|nullable',
        ];
    }

    public function messages(): array {
        return [
            'examen_id.required'    => 'El campo Examen ID es requerido',
            'historia_id.required'  => 'El campo Historia ID es requerido',
            'ph.decimal'            => 'El campo PH debe ser numérico o decimal',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'examen_id'     => trim(strip_tags($this->examen_id)),
            'historia_id'   => trim(strip_tags($this->historia_id)),
            'consistencia'  => trim(strip_tags($this->consistencia)),
            'color'         => trim(strip_tags($this->color)),
            'mucus'         => trim(strip_tags($this->mucus)),
            'restos_alimenticios' => trim(strip_tags($this->restos_alimenticios)),
            'leucocitos'    => trim(strip_tags($this->leucocitos)),
            'hematies'      => trim(strip_tags($this->hematies)),
            'bacterias'     => trim(strip_tags($this->bacterias)),
            'levaduras'     => trim(strip_tags($this->levaduras)),
            'parasitos'     => trim(strip_tags($this->parasitos)),
            'huevos_parasitos' => trim(strip_tags($this->huevos_parasitos)),
            'sangre_oculta' => trim(strip_tags($this->sangre_oculta)),
            'ph'            => trim(strip_tags($this->ph)),
            'grasa_fecal'   => trim(strip_tags($this->grasa_fecal)),
            'cultivo_bacteriano' => trim(strip_tags($this->cultivo_bacteriano)),
            'sensibilidad_antimicrobiana' => trim(strip_tags($this->sensibilidad_antimicrobiana)),
            'observaciones' => trim(strip_tags($this->observaciones)),
        ]);
    }
}
