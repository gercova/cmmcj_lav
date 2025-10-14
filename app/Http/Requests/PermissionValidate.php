<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'          => 'required|unique:permissions,name,'.$this->id,
            'guard_name'    => 'required',
            'module_id'     => 'required|exists:modules,id',
        ];
    }

    public function messages(): array { 
        return [
            'name.required'         => 'El Nombre es requerido',
            'name.unique'           => 'El Nombre ya existe',
            'guard_name.required'   => 'El Nombre de Guardia es requerido',
            'module_id.required'    => 'El Modulo es requerido',
            'module_id.exists'      => 'El Modulo no existe',
        ];
    }
}
