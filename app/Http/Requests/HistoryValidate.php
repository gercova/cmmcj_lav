<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HistoryValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'tipo_documento_id'         => 'required',
            'dni'                       => [
                'required',
                'unique:historias,dni,'.$this->id,
                Rule::when($this->id_td === 1, [
                    'digits:8',
                    Rule::unique('historias', 'dni')->ignore($this->id),
                ]),
                Rule::when($this->id_td === 3, [
                    'size:9',
                    Rule::unique('historias', 'dni')->ignore($this->id),
                ]),
            ],
            'nombres'                   => 'required|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'sexo'                      => 'required',
            'fecha_nacimiento'          => 'required|date',
            'telefono'                  => 'required|max:11|regex:/^[0-9\-]+$/',
            'email'                     => 'required|email',
            'direccion'                 => 'required|string',
            'grupo_sanguineo_id'        => 'required|integer',
            'grado_instruccion_id'      => 'required|integer',
            'ubigeo_nacimiento'         => 'required',
            'ubigeo_residencia'         => 'required',
            'ocupacion_id'              => 'required',
            'estado_civil_id'           => 'required|integer',
            'acompanante'               => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'acompanante_telefono'      => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'acompanante_direccion'     => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'vinculo'                   => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'seguro_id'                 => 'nullable|integer',
            'seguro_descripcion'        => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'ant_quirurgicos'           => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'ant_patologicos'           => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'ant_familiares'            => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'ant_medicos'               => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
            'rams'                      => 'nullable|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,-]+$/',
        ];
    }

    public function messages(): array {
        return [
            'tipo_documento_id.required'        => 'El tipo de documento es requerido',
            'dni.required'                      => 'El DNI es requerido',
            'dni.digits'                        => 'El DNI debe tener 8 dígitos',
            'dni.unique'                        => 'El DNI ya está registrado',
            'dni.size'                          => 'El DNI debe tener 9 dígitos',
            'nombres.required'                  => 'Los nombres son requeridos',
            'nombres.regex'                     => 'Los nombres solo pueden contener letras, números y espacios',
            'sexo.required'                     => 'El sexo es requerido',
            'fecha_nacimiento.required'         => 'La fecha de nacimiento es requerida',
            'telefono.required'                 => 'El teléfono es requerido',
            'telefono.max'                      => 'El teléfono solo puede tener máximo 11 caracteres',
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