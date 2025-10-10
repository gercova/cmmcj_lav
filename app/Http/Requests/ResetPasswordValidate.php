<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'password'              => 'required|string|min:6|max:20|confirmed',
            'password_confirmation' => 'required|string|min:6|max:20',
        ];
    }

    public function messages(): array {
        return [
            'password.required'                 => 'El campo contraseña es requerido',
            'password.min'                      => 'La contraseña debe tener al menos 6 caracteres',
            'password.max'                      => 'La contraseña no puede tener más de 20 caracteres',
            'password_confirmation.required'    => 'El campo confirmar contraseña es requerido',
            'password_confirmation.min'         => 'La contraseña debe tener al menos 6 caracteres',
            'password_confirmation.max'         => 'La contraseña no puede tener más de 20 caracteres',
        ];
    }

    public function attributes(): array {
        return [
            'password'              => 'contraseña',
            'password_confirmation' => 'confirmar contraseña',
        ];
    }
}
