<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalizationValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'historia_id'   => 'required',
            'bed_id'        => 'required',
            'fc'            => 'required',
            't'             => 'required',
            'so2'           => 'required',
            'vital_functions' => 'nullable|string',
            'observations'  => 'nullable|string',
            'others'        => 'nullable|string',
        ];
    }

    public function messages(): array {
        return [
            'historia_id.required'  => 'La Historia es requerida',
            'bed_id.required'       => 'La Cama es requerida',
            'fc.required'           => 'La Frecuencia es requerida',
            't.required'            => 'La Temperatura es requerida',
            'so2.required'          => 'La Saturación es requerida',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'historia_id'   => trim(strip_tags($this->historia_id)),
            'bed_id'        => trim(strip_tags($this->bed_id)),
            'fc'            => trim(strip_tags($this->fc)),
            't'             => trim(strip_tags($this->t)),
            'so2'           => trim(strip_tags($this->so2)),
            'vital_functions' => trim(strip_tags($this->vital_functions)),
            'observations'  => trim(strip_tags($this->observations)),
            'others'        => trim(strip_tags($this->others)),
        ]);
    }
}
