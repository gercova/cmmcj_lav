<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoryValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'tipo_documento_id'         => 'required',
            'dni'                       => 'requited|digits:8|unique:historias,dni,'.$this->dni ?? NULL,
            'nombres'                   => 'required|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'apellidos'                 => 'required|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'sexo'                      => 'required|enum:F,M',
            'fecha_nacimiento'          => 'required|date',
            'telefono'                  => 'required|digits:11|regex:/^[0-9\-]+$/',
            'email'                     => 'required|email',
            'direccion'                 => 'required|string',
            'grupo_sanguineo_id'        => 'required|integer',
            'grado_instruccion_id'      => 'required|integer',
            'ubigeo_nacimiento'         => 'required|string',
            'ubigeo_residencia'         => 'required|string',
            'ocupacion_id'              => 'required|integer',
            'estado_civil_id'           => 'required|integer',
            'acompanante'               => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'acompanante_telefono'      => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'acompanante_direccion'     => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-/*="]+$/',
            'vinculo'                   => 'nullable|in:Padre,Madre,Hijo,Hija,Abuelo,Abuela,Tio,Tia,Nieto,Nieta,Cuñado,Vecino,Conocido',
            'seguro_id'                 => 'nullable|integer',
            'seguro_descripcion'        => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'antecedentes_quirurgicos'  => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'antecedentes_patologicos'  => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'antecedentes_familiares'   => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'antecedentes_medicos'      => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
            'rams'                      => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚs.,-/*="]+$/',
        ];
    }

    public function messages(): array {
        return [
            'tipo_documento_id.required'        => 'El tipo de documento es requerido',
            'dni.required'                      => 'El DNI es requerido',
            'dni.digits'                        => 'El DNI debe tener 8 dígitos',
            'dni.unique'                        => 'El DNI ya está registrado',
            'nombres.required'                  => 'Los nombres son requeridos',
            'nombres.regex'                     => 'Los nombres solo pueden contener letras, números y espacios',
            'apellidos.required'                => 'Los apellidos son requeridos',
            'apellidos.regex'                   => 'Los apellidos solo pueden contener letras, números y espacios',
            'sexo.required'                     => 'El sexo es requerido',
            'fecha_nacimiento.required'         => 'La fecha de nacimiento es requerida',
            'telefono.required'                 => 'El teléfono es requerido',
            'telefono.digits'                   => 'El teléfono debe tener 11 dígitos',
            'telefono.regex'                    => 'El teléfono solo puede contener números y guiones',
            'email.required'                    => 'El email es requerido',
            'email.email'                       => 'El email no es válido',
            'email.unique'                      => 'El email ya está registrado',
            'direccion.required'                => 'La dirección es requerida',
            'direccion.regex'                   => 'La dirección solo puede contener letras, números y espacios',
            'grupo_sanguineo_id.required'       => 'El grupo sanguíneo es requerido',
            'grado_instruccion_id.required'     => 'El grado de instrucción es requerido',
            'ubigeo_nacimiento.required'        => 'El ubigeo de nacimiento es requerido',
            'ubigeo_residencia.required'        => 'El ubigeo de residencia es requerido',
            'ocupacion_id.required'             => 'La ocupación es requerida',
            'estado_civil_id.required'          => 'El estado civil es requerido',
            'acompanante.regex'                 => 'El acompanante solo puede contener letras, números y espacios',
            'acompanante_telefono.regex'        => 'El teléfono del acompanante solo puede contener números y guiones',
            'acompanante_direccion.regex'       => 'La dirección del acompanante solo puede contener letras, números y espacios',
            'vinculo.in'                        => 'El vínculo es inválido',
            'seguro_id.required'                => 'El seguro es requerido',
            'seguro_descripcion.regex'          => 'La descripción del seguro solo puede contener letras, números y espacios',
            'antecedentes_quirurgicos.regex'    => 'Los antecedentes quirúrgicos solo pueden contener letras, números y espacios',
            'antecedentes_patologicos.regex'    => 'Los antecedentes patológicos solo pueden contener letras, números y espacios',
            'antecedentes_familiares.regex'     => 'Los antecedentes familiares solo pueden contener letras, números y espacios',
            'antecedentes_medicos.regex'        => 'Los antecedentes médicos solo pueden contener letras, números y espacios',
            'rams.regex'                        => 'Las RAMS solo pueden contener letras, números y espacios',
        ];
    }
}