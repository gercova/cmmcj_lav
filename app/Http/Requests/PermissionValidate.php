<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'              => 'required|unique:permissions,name,'.$this->id,
            'guard_name'        => 'required',
            'submodule_id'      => 'required|exists:submodules,id',
            'descripcion'       => 'required'
        ];
    }

    public function messages(): array { 
        return [
            'name.required'         => 'El Nombre es requerido',
            'name.unique'           => 'El Nombre ya existe',
            'guard_name.required'   => 'El Nombre de Guardia es requerido',
            'submodule_id.required' => 'El Submodulo es requerido',
            'submodule_id.exists'   => 'El Submodulo no existe',
            'descripcion.required'  => 'La Descripción es requerida',
        ];
    }
}
