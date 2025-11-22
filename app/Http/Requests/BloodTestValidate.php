<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BloodTestValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'examen_id'             => 'required',
            'historia_id'           => 'required',
            'hemoglobina'           => 'nullable|numeric',
            'hematocrito'           => 'nullable|numeric',
            'leucocitos'            => 'nullable|numeric',
            'neutrofilos'           => 'nullable|numeric',
            'linfocitos'            => 'nullable|numeric',
            'monocitos'             => 'nullable|numeric',
            'eosinofilos'           => 'nullable|numeric',
            'basofilos'             => 'nullable|numeric',
            'plaquetas'             => 'nullable|numeric',
            'glucosa'               => 'nullable|numeric',
            'urea'                  => 'nullable|numeric',
            'creatinina'            => 'nullable|numeric',
            'acido_urico'           => 'nullable|numeric',
            'colesterol_total'      => 'nullable|numeric',
            'trigliceridos'         => 'nullable|numeric',
            'transaminasas_got'     => 'nullable|numeric',
            'transaminasas_gpt'     => 'nullable|numeric',
            'bilirrubina_total'     => 'nullable|numeric',
            'bilirrubina_directa'   => 'nullable|numeric',
            'fosfatasa_alcalina'    => 'nullable|numeric',
            'proteinas_totales'     => 'nullable|numeric',
            'albumina'              => 'nullable|numeric',
            'globulina'             => 'nullable|numeric',
            'sodio'                 => 'nullable|numeric',
            'potasio'               => 'nullable|numeric',
            'cloro'                 => 'nullable|numeric',
            'calcio'                => 'nullable|numeric',
            'vsg'                   => 'nullable|numeric',  
            'tiempo_protrombina'    => 'nullable|numeric',
            'tpt'                   => 'nullable|numeric',
            'observaciones'         => 'string|nullable',
        ];
    }

    public function messages(): array {
        return [
            'examen_id.required'            => 'El ID Examen es requerido',
            'historia_id.required'          => 'El ID Historia es requerido',
            'hemoglobina.decimal'           => 'El campo debe ser numérico o decimal',
            'hematocrito.decimal'           => 'El campo debe ser numérico o decimal',
            'leucocitos.decimal'            => 'El campo debe ser numérico o decimal',
            'neutrofilos.decimal'           => 'El campo debe ser numérico o decimal',
            'linfocitos.decimal'            => 'El campo debe ser numérico o decimal',
            'monocitos.decimal'             => 'El campo debe ser numérico o decimal',
            'eosinofilos.decimal'           => 'El campo debe ser numérico o decimal',
            'basofilos.decimal'             => 'El campo debe ser numérico o decimal',
            'plaquetas.decimal'             => 'El campo debe ser numérico o decimal',
            'glucosa.decimal'               => 'El campo debe ser numérico o decimal',
            'urea.decimal'                  => 'El campo debe ser numérico o decimal',
            'creatinina.decimal'            => 'El campo debe ser numérico o decimal',
            'acido_urico.decimal'           => 'El campo debe ser numérico o decimal',
            'colesterol_total.decimal'      => 'El campo debe ser numérico o decimal',
            'trigliceridos.decimal'         => 'El campo debe ser numérico o decimal',
            'transaminasas_got.decimal'     => 'El campo debe ser numérico o decimal',
            'transaminasas_gpt.decimal'     => 'El campo debe ser numérico o decimal',
            'bilirrubina_total.decimal'     => 'El campo debe ser numérico o decimal',
            'bilirrubina_directa.decimal'   => 'El campo debe ser numérico o decimal',
            'fosfatasa_alcalina.decimal'    => 'El campo debe ser numérico o decimal',
            'proteinas_totales.decimal'     => 'El campo debe ser numérico o decimal',
            'albumina.decimal'              => 'El campo debe ser numérico o decimal',
            'globulina.decimal'             => 'El campo debe ser numérico o decimal',
            'sodio.decimal'                 => 'El campo debe ser numérico o decimal',
            'potasio.decimal'               => 'El campo debe ser numérico o decimal',
            'cloro.decimal'                 => 'El campo debe ser numérico o decimal',
            'calcio.decimal'                => 'El campo debe ser numérico o decimal',
            'vsg.decimal'                   => 'El campo debe ser numérico o decimal',  
            'tiempo_protrombina.decimal'    => 'El campo debe ser numérico o decimal',
            'tpt.decimal'                   => 'El campo debe ser numérico o decimal', 
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'examen_id'             => trim(strip_tags($this->examen_id)),
            'historia_id'           => trim(strip_tags($this->historia_id)),
            'hemoglobina'           => trim(strip_tags($this->hemoglobina)),
            'hematocrito'           => trim(strip_tags($this->hematocrito)),
            'leucocitos'            => trim(strip_tags($this->leucocitos)),
            'neutrofilos'           => trim(strip_tags($this->neutrofilos)),
            'linfocitos'            => trim(strip_tags($this->linfocitos)),
            'monocitos'             => trim(strip_tags($this->monocitos)),
            'eosinofilos'           => trim(strip_tags($this->eosinofilos)),
            'basofilos'             => trim(strip_tags($this->basofilos)),
            'plaquetas'             => trim(strip_tags($this->plaquetas)),
            'glucosa'               => trim(strip_tags($this->glucosa)),
            'urea'                  => trim(strip_tags($this->urea)),
            'creatinina'            => trim(strip_tags($this->creatinina)),
            'acido_urico'           => trim(strip_tags($this->acido_urico)),
            'colesterol_total'      => trim(strip_tags($this->colesterol_total)),
            'trigliceridos'         => trim(strip_tags($this->trigliceridos)),
            'transaminasas_got'     => trim(strip_tags($this->transaminasas_got)),
            'transaminasas_gpt'     => trim(strip_tags($this->transaminasas_gpt)),
            'bilirrubina_total'     => trim(strip_tags($this->bilirrubina_total)),
            'bilirrubina_directa'   => trim(strip_tags($this->bilirrubina_directa)),
            'fosfatasa_alcalina'    => trim(strip_tags($this->fosfatasa_alcalina)),
            'proteinas_totales'     => trim(strip_tags($this->proteinas_totales)),
            'albumina'              => trim(strip_tags($this->albumina)),
            'globulina'             => trim(strip_tags($this->globulina)),
            'sodio'                 => trim(strip_tags($this->sodio)),
            'potasio'               => trim(strip_tags($this->potasio)),
            'cloro'                 => trim(strip_tags($this->cloro)),
            'calcio'                => trim(strip_tags($this->calcio)),
            'vsg'                   => trim(strip_tags($this->vsg)),
            'tiempo_protrombina'    => trim(strip_tags($this->tiempo_protrombina)),
            'tpt'                   => trim(strip_tags($this->tpt)),
            'observaciones'         => trim(strip_tags($this->observaciones)),
        ]);
    }
}