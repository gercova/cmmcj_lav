<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrineTestValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'examen_id'     => 'required',
            'historia_id'   => 'required',
            'color'         => 'string|nullable',
            'aspecto'       => 'string|nullable',
            'densidad'      => 'string|nullable',
            'ph'            => 'decimal:3,1|nullable',
            'proteinas'     => 'string|nullable',
            'glucosa'       => 'string|nullable',
            'cetonas'       => 'string|nullable',
            'bilirrubina'   => 'string|nullable',
            'sangre_oculta' => 'string|nullable',
            'urobilinogeno' => 'string|nullable',
            'nitritos'      => 'string|nullable',
            'leucocitos_quimico'    => 'string|nullable',
            'leucocitos_campo'      => 'string|nullable',
            'hematies_campo'        => 'string|nullable',
            'celulas_epiteliales'   => 'string|nullable',
            'bacterias'             => 'string|nullable',
            'cristales'             => 'string|nullable',
            'cilindros'             => 'string|nullable',
            'mucus'                 => 'string|nullable',
            'observaciones'         => 'string|nullable',
        ];
    }

    public function messages(): array {
        return [
            'examen_id.required'    => 'El campo Examen ID es requerido',
            'historia_id.required'  => 'El campo Historia ID es requerido',
            'ph.decimal'            => 'El campo ph debe ser decimal con 3 dígitos antes y 1 dígito después del punto',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'examen_id'     => trim(strip_tags($this->examen_id)),
            'historia_id'   => trim(strip_tags($this->historia_id)),
            'color'         => trim(strip_tags($this->color)),
            'aspecto'       => trim(strip_tags($this->aspecto)),
            'densidad'      => trim(strip_tags($this->densidad)),
            'ph'            => trim(strip_tags($this->ph)),
            'proteinas'     => trim(strip_tags($this->proteinas)),
            'glucosa'       => trim(strip_tags($this->glucosa)),
            'cetonas'       => trim(strip_tags($this->cetonas)),
            'bilirrubina'   => trim(strip_tags($this->bilirrubina)),
            'sangre_oculta' => trim(strip_tags($this->sangre_oculta)),
            'urobilinogeno' => trim(strip_tags($this->urobilinogeno)),
            'nitritos'      => trim(strip_tags($this->nitritos)),
            'leucocitos_quimico'    => trim(strip_tags($this->leucocitos_quimico)),
            'leucocitos_campo'      => trim(strip_tags($this->leucocitos_campo)),
            'hematies_campo'        => trim(strip_tags($this->hematies_campo)),
            'celulas_epiteliales'   => trim(strip_tags($this->celulas_epiteliales)),
            'bacterias'             => trim(strip_tags($this->bacterias)),
            'cristales'             => trim(strip_tags($this->cristales)),
            'cilindros'             => trim(strip_tags($this->cilindros)),
            'mucus'                 => trim(strip_tags($this->mucus)),
            'observaciones' => trim(strip_tags($this->observaciones)),
        ]);
    }
}