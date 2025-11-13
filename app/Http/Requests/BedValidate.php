<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BedValidate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description'   => 'required|string|max:255|unique:habitacion_cama,description,'.$this->id,
            'floor'         => 'required|string|max:50',
            'detail'        => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'description.required'  => 'La Descripción es requerida',
            'description.max'       => 'La Descripción no puede exceder los 255 caracteres',
            'description.unique'    => 'La Descripción ya existe',
            'floor.required'        => 'El Piso es requerido',
            'floor.max'             => 'El Piso no puede exceder los 50 caracteres',
            'detail.required'       => 'El Detalle es requerido',
            'detail.max'            => 'El Detalle no puede exceder los 255 caracteres'
        ];
    }
}
