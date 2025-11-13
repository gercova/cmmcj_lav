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
            'examen_id'     => 'required',
            'historia_id'   => 'required',
            'hemoglobina'   => 'nullable|decimal:5,2',
            'hematocrito'   => 'nullable|decimal:5,2',
            'leucocitos'    => 'nullable|decimal:5,2',
            'neutrofilos'   => 'nullable|decimal:5,2',
            'linfocitos'    => 'nullable|decimal:5,2',
            'monocitos'     => 'nullable|decimal:5,2',
            'eosinofilos'   => 'nullable|decimal:5,2',
            'basofilos'     => 'nullable|decimal:5,2',
            'plaquetas'     => 'nullable|decimal:5,2',
            'glucosa'       => 'nullable|decimal:5,2',
            'urea'          => 'nullable|decimal:5,2',
            'creatinina'    => 'nullable|decimal:5,2',
            'acido_urico'   => 'nullable|decimal:5,2',
            'colesterol_total'  => 'nullable|decimal:5,2',
            'trigliceridos'     => 'nullable|decimal:5,2',
            'transaminasas_got' => 'nullable|decimal:5,2',
            'transaminasas_gpt' => 'nullable|decimal:5,2',
            'bilirrubina_total' => 'nullable|decimal:5,2',
            'bilirrubina_directa'   => 'nullable|decimal:5,2',
            'fosfatasa_alcalina'    => 'nullable|decimal:5,2',
            'proteinas_totales'     => 'nullable|decimal:5,2',
            'albumina'      => 'nullable|decimal:5,2',
            'globulina'     => 'nullable|decimal:5,2',
            'sodio'         => 'nullable|decimal:5,2',
            'potasio'       => 'nullable|decimal:5,2',
            'cloro'         => 'nullable|decimal:5,2',
            'calcio'        => 'nullable|decimal:5,2',
            'vsg'           => 'nullable|decimal:5,2',  
            'tiempo_protrombina' => 'nullable|decimal:5,2',
            'tpt'           => 'nullable|decimal:5,2',
            'observaciones' => 'string|nullable',
        ];
    }

    public function messages(): array {
        return [
            'examen_id.required'    => 'El ID Examen es requerido',
            'historia_id.required'  => 'El ID Historia es requerido',
            'hemoglobina.decimal'   => 'El campo debe ser decimal',
            'hematocrito.decimal'   => 'El campo debe ser decimal',
            'leucocitos.decimal'    => 'El campo debe ser decimal',
            'neutrofilos.decimal'   => 'El campo debe ser decimal',
            'linfocitos.decimal'    => 'El campo debe ser decimal',
            'monocitos.decimal'     => 'El campo debe ser decimal',
            'eosinofilos.decimal'   => 'El campo debe ser decimal',
            'basofilos.decimal'     => 'El campo debe ser decimal',
            'plaquetas.decimal'     => 'El campo debe ser decimal',
            'glucosa.decimal'       => 'El campo debe ser decimal',
            'urea.decimal'          => 'El campo debe ser decimal',
            'creatinina.decimal'    => 'El campo debe ser decimal',
            'acido_urico.decimal'   => 'El campo debe ser decimal',
            'colesterol_total.decimal'  => 'El campo debe ser decimal',
            'trigliceridos.decimal'     => 'El campo debe ser decimal',
            'transaminasas_got.decimal' => 'El campo debe ser decimal',
            'transaminasas_gpt.decimal' => 'El campo debe ser decimal',
            'bilirrubina_total.decimal' => 'El campo debe ser decimal',
            'bilirrubina_directa.decimal' => 'El campo debe ser decimal',
            'fosfatasa_alcalina.decimal' => 'El campo debe ser decimal',
            'proteinas_totales.decimal' => 'El campo debe ser decimal',
            'albumina.decimal'      => 'El campo debe ser decimal',
            'globulina.decimal'     => 'El campo debe ser decimal',
            'sodio.decimal'         => 'El campo debe ser decimal',
            'potasio.decimal'       => 'El campo debe ser decimal',
            'cloro.decimal'         => 'El campo debe ser decimal',
            'calcio.decimal'        => 'El campo debe ser decimal',
            'vsg.decimal'           => 'El campo debe ser decimal',  
            'tiempo_protrombina.decimal' => 'El campo debe ser decimal',
            'tpt.decimal'           => 'El campo debe ser decimal', 
            
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'examen_id'     => trim(strip_tags($this->examen_id)),
            'historia_id'   => trim(strip_tags($this->historia_id)),
            'hemoglobina'   => trim(strip_tags($this->hemoglobina)),
            'hematocrito'   => trim(strip_tags($this->hematocrito)),
            'leucocitos'    => trim(strip_tags($this->leucocitos)),
            'neutrofilos'   => trim(strip_tags($this->neutrofilos)),
            'linfocitos'    => trim(strip_tags($this->linfocitos)),
            'monocitos'     => trim(strip_tags($this->monocitos)),
            'eosinofilos'   => trim(strip_tags($this->eosinofilos)),
            'basofilos'     => trim(strip_tags($this->basofilos)),
            'plaquetas'     => trim(strip_tags($this->plaquetas)),
            'glucosa'       => trim(strip_tags($this->glucosa)),
            'urea'          => trim(strip_tags($this->urea)),
            'creatinina'    => trim(strip_tags($this->creatinina)),
            'acido_urico'   => trim(strip_tags($this->acido_urico)),
            'colesterol_total'  => trim(strip_tags($this->colesterol_total)),
            'trigliceridos'     => trim(strip_tags($this->trigliceridos)),
            'transaminasas_got' => trim(strip_tags($this->transaminasas_got)),
            'transaminasas_gpt' => trim(strip_tags($this->transaminasas_gpt)),
            'bilirrubina_total' => trim(strip_tags($this->bilirrubina_total)),
            'bilirrubina_directa' => trim(strip_tags($this->bilirrubina_directa)),
            'fosfatasa_alcalina' => trim(strip_tags($this->fosfatasa_alcalina)),
            'proteinas_totales' => trim(strip_tags($this->proteinas_totales)),
            'albumina'      => trim(strip_tags($this->albumina)),
            'globulina'     => trim(strip_tags($this->globulina)),
            'sodio'         => trim(strip_tags($this->sodio)),
            'potasio'       => trim(strip_tags($this->potasio)),
            'cloro'         => trim(strip_tags($this->cloro)),
            'calcio'        => trim(strip_tags($this->calcio)),
            'vsg'           => trim(strip_tags($this->vsg)),
            'tiempo_protrombina' => trim(strip_tags($this->tiempo_protrombina)),
            'tpt'           => trim(strip_tags($this->tpt)),
            'observaciones' => trim(strip_tags($this->observaciones)),
        ]);
    }
}