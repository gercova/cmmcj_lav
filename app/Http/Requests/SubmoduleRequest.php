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
            'descripcion' => 'required|max:100',
        ];
    }

    public function messages(): array {
        return [
            'module_id.required'    => 'El Módulo es requerido',
            'nombre.required'       => 'El Nombre es requerido',
            'nombre.unique'         => 'El Nombre ya existe',
            'descripcion.required'  => 'La Descripcion es requerida',
            'descripcion.max'       => 'La Descripcion tiene un máximo de 100 caracteres',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'module_id'     => trim(strip_tags($this->module_id)),
            'nombre'        => trim(strip_tags($this->nombre)),
            'descripcion'   => trim(strip_tags($this->descripcion)),
        ]);
    }
}
