<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'username' => 'required|string|regex:/^[a-zA-Z]+$/',
            'password' => 'required|min:8',
        ];
    }

    public function messages(): array {
        return [
            'username.required'    => 'El campo nombre de usuario es requerido',
            'username.regex'       => 'El campo nombre de usuario solo puede contener letras',
            'password.required'    => 'El campo contraseña es requerido',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres',
        ];
    }
}
