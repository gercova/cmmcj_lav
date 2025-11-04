<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmoduleRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'module_id' => 'required',
            'nombre'    => 'required|unique:submodules,nombre,id,'.$this->id,
            'ruta'      => 'required|string',
            'icono'     => 'required|string',
        ];
    }

    public function messages(): array {
        return [
            'module_id.required'    => 'El Módulo es requerido',
            'nombre.required'       => 'El Nombre es requerido',
            'nombre.unique'         => 'El Nombre ya existe',
            'ruta.required'         => 'La Ruta es requerida',
            'icono.required'        => 'El Ícono es requerido',
        ];
    }
}
