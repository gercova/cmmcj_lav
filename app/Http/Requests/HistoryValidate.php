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
            'nombres'                   => 'required|string',
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
            'acompanante'               => 'nullable|string',
            'acompanante_telefono'      => 'nullable|string',
            'acompanante_direccion'     => 'nullable|string',
            'vinculo'                   => 'nullable|string',
            'seguro_id'                 => 'nullable|integer',
            'seguro_descripcion'        => 'nullable|string',
            'ant_quirurgicos'           => 'nullable|string',
            'ant_patologicos'           => 'nullable|string',
            'ant_familiares'            => 'nullable|string',
            'ant_medicos'               => 'nullable|string',
            'rams'                      => 'nullable|string',
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
            'sexo.required'                     => 'El sexo es requerido',
            'fecha_nacimiento.required'         => 'La fecha de nacimiento es requerida',
            'telefono.required'                 => 'El teléfono es requerido',
            'telefono.max'                      => 'El teléfono solo puede tener máximo 11 caracteres',
            'email.required'                    => 'El email es requerido',
            'email.email'                       => 'El email no es válido',
            'email.unique'                      => 'El email ya está registrado',
            'direccion.required'                => 'La dirección es requerida',
            'grupo_sanguineo_id.required'       => 'El grupo sanguíneo es requerido',
            'grado_instruccion_id.required'     => 'El grado de instrucción es requerido',
            'ubigeo_nacimiento.required'        => 'El ubigeo de nacimiento es requerido',
            'ubigeo_residencia.required'        => 'El ubigeo de residencia es requerido',
            'ocupacion_id.required'             => 'La ocupación es requerida',
            'estado_civil_id.required'          => 'El estado civil es requerido',
            'vinculo.in'                        => 'El vínculo es inválido',
            'seguro_id.required'                => 'El seguro es requerido',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'tipo_documento_id'         => trim(strip_tags($this->tipo_documento_id)),
            'dni'                       => trim(strip_tags($this->dni)),
            'nombres'                   => trim(strip_tags($this->nombres)),
            'sexo'                      => trim(strip_tags($this->sexo)),
            'fecha_nacimiento'          => trim(strip_tags($this->fecha_nacimiento)),
            'telefono'                  => trim(strip_tags($this->telefono)),
            'email'                     => trim(strip_tags($this->email)),
            'direccion'                 => trim(strip_tags($this->direccion)),
            'grupo_sanguineo_id'        => trim(strip_tags($this->grupo_sanguineo_id)),
            'grado_instruccion_id'      => trim(strip_tags($this->grado_instruccion_id)),
            'ubigeo_nacimiento'         => trim(strip_tags($this->ubigeo_nacimiento)),
            'ubigeo_residencia'         => trim(strip_tags($this->ubigeo_residencia)),
            'ocupacion_id'              => trim(strip_tags($this->ocupacion_id)),
            'estado_civil_id'           => trim(strip_tags($this->estado_civil_id)),
            'acompanante'               => trim(strip_tags($this->acompanante)),
            'acompanante_telefono'      => trim(strip_tags($this->acompanante_telefono)),
            'acompanante_direccion'     => trim(strip_tags($this->acompanante_direccion)),
            'vinculo'                   => trim(strip_tags($this->vinculo)),
            'seguro_id'                 => trim(strip_tags($this->seguro_id)),
            'seguro_descripcion'        => trim(strip_tags($this->seguro_descripcion)),
            'ant_quirurgicos'           => trim(strip_tags($this->ant_quirurgicos)),
            'ant_patologicos'           => trim(strip_tags($this->ant_patologicos)),
            'ant_familiares'            => trim(strip_tags($this->ant_familiares)),
            'ant_medicos'               => trim(strip_tags($this->ant_medicos)),
            'rams'                      => trim(strip_tags($this->rams)),
        ]);
    }
}