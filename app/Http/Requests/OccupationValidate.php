<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OccupationValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'descripcion' => 'required|max:255|regex:/^(?:[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\/,#\-\(\)\.])+$/|unique:ocupaciones,descripcion,'.$this->id,
        ];
    }

    public function messages(): array {
        return [
            'descripcion.required'  => 'La Descripción es requerido.',
            'descripcion.max'       => 'La Descripción tiene un límite de 255 letras.',
            'descripcion.regex'     => 'La Descripción solo puede contener letras (con o sin tilde), números, espacios, y los siguientes símbolos: , / # - () .',
            'descripcion.unique'    => 'La Descripción ya existe.'
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'descripcion' => trim(strip_tags($this->descripcion)),
        ]);
    }
}
