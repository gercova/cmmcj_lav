<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalizationValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'historia_id'               => 'required',
            'cama_id'                    => 'required',
            'fc'                        => 'required',
            't'                         => 'required',
            'so2'                       => 'required',
            'fecha_admision'            => 'date|nullable',
            'tipo_admision_id'          => 'required',
            'via_ingreso_id'            => 'required',
            'motivo_hospitalizacion'    => 'required|string',
            'alergias'                  => 'string|nullable',
            'medicamentos_habituales'   => 'string|nullable',
            'antecedentes_importantes'  => 'string|nullable',
            'condicion_ingreso_id'      => 'required',
            'servicio_id'               => 'required',
            'tipo_cuidado_id'           => 'required',
            'fecha_egreso'              => 'date|nullable',
            'tipo_egreso_id'            => 'required',
            'diagnostico_egreso'        => 'string|nullable',
            'condicion_egreso_id'       => 'required',
            'resumen_evolucion'         => 'string|nullable',
            'causa_muerte'              => 'string|nullable',
            'nro_autorizacion_seguro'   => 'string|nullable',
            'aseguradora'               => 'string|nullable',
            'estado_hospitalizacion_id' => 'required|',
        ];
    }

    public function messages(): array {
        return [
            'historia_id.required'          => 'La Historia es requerida',
            'cama_id.required'              => 'La Cama es requerida',
            'fc.required'                   => 'La Frecuencia es requerida',
            't.required'                    => 'La Temperatura es requerida',
            'so2.required'                  => 'La Saturación es requerida',
            'tipo_admision_id.required'     => 'El Tipo Admisión es requerido',
            'via_ingreso_id.required'       => 'La Via de Ingreso es requerida',
            'motivo_hospitalizacion.required' => 'El Motivo hospitalización es requerido',
            'condicion_ingreso_id.required' => 'La Condición de Ingreso es requerida',
            'servicio_id.required'          => 'El Servicio es requerido',
            'tipo_cuidado_id.required'      => 'El Tipo de Cuidado es requerido',
            'tipo_egreso_id.required'       => 'El Tipo de Egreso es requerido',
            'condicion_egreso_id.required'  => 'La Condición de Egreso es requerida',
            'estado_hospitalizacion_id.required' => 'El Estado de Hospitalización es requerido',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'historia_id'               => trim(strip_tags($this->historia_id)),
            'bed_id'                    => trim(strip_tags($this->bed_id)),
            'fc'                        => trim(strip_tags($this->fc)),
            't'                         => trim(strip_tags($this->t)),
            'so2'                       => trim(strip_tags($this->so2)),
            'fecha_admision'            => trim(strip_tags($this->fecha_admision)),
            'tipo_admision_id'          => trim(strip_tags($this->tipo_admision_id)),
            'via_ingreso_id'            => trim(strip_tags($this->via_ingreso_id)),
            'motivo_hospitalizacion'    => trim(strip_tags($this->motivo_hospitalizacion)),
            'alergias'                  => trim(strip_tags($this->alergias)),
            'medicamentos_habituales'   => trim(strip_tags($this->medicamentos_habituales)),
            'antecedentes_importantes'  => trim(strip_tags($this->antecedentes_importantes)),
            'condicion_ingreso_id'      => trim(strip_tags($this->condicion_ingreso_id)),
            'servicio_id'               => trim(strip_tags($this->servicio_id)),
            'tipo_cuidado_id'           => trim(strip_tags($this->tipo_cuidado_id)),
            'fecha_egreso'              => trim(strip_tags($this->fecha_egreso)),
            'tipo_egreso_id'            => trim(strip_tags($this->tipo_egreso_id)),
            'diagnostico_egreso'        => trim(strip_tags($this->diagnostico_egreso)),
            'condicion_egreso_id'       => trim(strip_tags($this->condicion_egreso_id)),
            'resumen_evolucion'         => trim(strip_tags($this->resumen_evolucion)),
            'causa_muerte'              => trim(strip_tags($this->causa_muerte)),
            'nro_autorizacion_seguro'   => trim(strip_tags($this->nro_autorizacion_seguro)),
            'aseguradora'               => trim(strip_tags($this->aseguradora)),
            'estado_hospitalizacion_id' => trim(strip_tags($this->estado_hospitalizacion_id)),
        ]);
    }
}
