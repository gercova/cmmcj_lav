<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'descripcion' => 'required|unique:modules,descripcion,'.$this->id
        ];
    }

    public function messages(): array {
        return [
            'descripcion.required'  => 'La Descripción es requerida.',
            'descripcion.unique'    => 'La Descripción ya existe.',
        ];
    }
}
