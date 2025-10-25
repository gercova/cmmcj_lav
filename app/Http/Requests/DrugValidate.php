<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DrugValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'unidad_medida_id'  => 'required',
            'descripcion'       => 'required|max:255|regex:/^(?:[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\/,#\-\(\)\.])+$/|unique:ocupaciones,descripcion,'.$this->id,
            'detalle'           => 'nullable|max:255|regex:/^(?:[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\/,#\-\(\)\.])+$/',
        ];
    }

    public function messages(): array {
        return [
            'unidad_medida_id.required' => 'La Unidad Medida es requerida',
            'descripcion.required'      => 'La Descripción es requerida',
            'descripcion.max'           => 'La Descripción no puede exceder los 255 caracteres',
            'descripcion.regex'         => 'La Descripción solo puede contener letras (con o sin tilde), números, espacios, y los siguientes símbolos: , / # - () .',
            'descripcion.unique'        => 'La Descripción ya existe',
            'detalle'                   => 'El Detalle no puede exceder los 255 caracteres',
            'detalle.regex'             => 'La Descripción solo puede contener letras (con o sin tilde), números, espacios, y los siguientes símbolos: , / # - () .',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'unidad_medida_id'  => trim(strip_tags($this->unidad_medida_id)),
            'descripcion'       => trim(strip_tags($this->descripcion)),
            'detalle'           => trim(strip_tags($this->detalle)),
        ]);
    }
}
