<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'                  => 'required|string|max:255',
            'especialidad_id'       => 'required',
            'role_id'               => 'required',
            'avatar'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array {
        return [
            'name.required'             => 'El Nombre es requerido',
            'name.max'                  => 'El Nombre tiene un límite de 255 caracteres',
            'especialidad_id.required'  => 'La Especialidad es requerida',
            'role_id.required'          => 'El Rol es requerido',
            'avatar.image'              => 'El campo avatar solo acpeta estos formatos jpeg, png, jpg, gif',
            'avatar.max'                => 'Límite de la imagen excedida',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'name'              => trim(strip_tags($this->name)),
            'especialidad_id'   => trim(strip_tags($this->especialidad_id)),
        ]);
    }
}
