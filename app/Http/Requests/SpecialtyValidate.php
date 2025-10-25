<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialtyValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'ocupacion_id'  => 'required',
            'descripcion'   => 'required|max:255'
        ];
    }

    public function messages(): array {
        return [
            'ocupacion_id.required' => 'La Ocupación es requerida',
            'descripcion.requred'   => 'La Descripción es requerida',
            'descripcion.max'       => 'La Descripción tiene un límite de 255 caracteres',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'ocupacion_id'  => trim(strip_tags($this->ocupacion_id)),
            'descripcion'   => trim(strip_tags($this->descripcion)),
        ]);
    }
}
