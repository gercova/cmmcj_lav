<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'codigo'        => 'string|max:10|unique:diagnosticos,codigo,'.$this->id,
            'descripcion'   => 'required|string|max:255|regex:/^(?:[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\/,#\-\(\)\.])+$/',
            'tipo'          => 'required|string|max:255|in:CIE10,CMMCJ',
        ];
    }

    public function messages(): array {
        return [
            'codigo.string'             => 'El campo código debe ser una cadena de texto',
            'codigo.max'                => 'El campo código debe tener máximo 10 caracteres',
            'codigo.unique'             => 'El campo código ya existe',
            'descripcion.required'      => 'El campo descripción es requerido',
            'descripcion.string'        => 'El campo descripción debe ser una cadena de texto',
            'descripcion.max'           => 'El campo descripción debe tener máximo 255 caracteres',
            'descripcion.regex'         => 'El campo descripción solo puede contener letras, números y espacios',
            'tipo.required'             => 'El campo tipo es requerido',
            'tipo.in'                   => 'El campo tipo solo puede contener CIE10 o CMMCJ',
        ];
    }
}
