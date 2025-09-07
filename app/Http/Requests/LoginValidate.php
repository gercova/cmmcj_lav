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
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function messages(): array {
        return [
            'email.required'    => 'El campo correo electrónico es requerido',
            'email.email'       => 'El campo correo electrónico debe ser válido',
            'password.required' => 'El campo contraseña es requerido',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres',
        ];
    }
}
