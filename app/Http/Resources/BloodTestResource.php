<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BloodTestResource extends JsonResource {

    public function toArray(Request $request): array {
        return [
            'id'            => $this->id,
            'examen_id'     => $this->examen_id,
            'historia_id'   => $this->historia_id,
            'examen'        => new ExamResource($this->whenLoaded('examen')),
            'hemoglobina'   => $this->hemoglobina,
            'hematocrito'   => $this->hematocrito,
            'leucocitos'    => $this->leucocitos,
            'neutrofilos'   => $this->neutrofilos,
            'linfocitos'    => $this->linfocitos,
            'monocitos'     => $this->monocitos,
            'eosinofilos'   => $this->eosinofilos,
            'basofilos'     => $this->basofilos,
            'plaquetas'     => $this->plaquetas,
            'glucosa'       => $this->glucosa,
            'urea'          => $this->urea,
            'creatinina'    => $this->creatinina,
            'acido_urico'   => $this->acido_urico,
            'colesterol_total'  => $this->colesterol_total,
            'trigliceridos'     => $this->trigliceridos,
            'transaminasas_got' => $this->transaminasas_got,
            'transaminasas_gpt' => $this->transaminasas_gpt,
            'bilirrubina_total' => $this->bilirrubina_total,
            'bilirrubina_directa'   => $this->bilirrubina_directa,
            'fosfatasa_alcalina'    => $this->fosfatasa_alcalina,
            'proteinas_totales'     => $this->proteinas_totales,
            'albumina'  => $this->albumina,
            'globulina' => $this->globulina,
            'sodio'     => $this->sodio,
            'potasio'   => $this->potasio,
            'cloro'     => $this->cloro,
            'calcio'    => $this->calcio,
            'vsg'       => $this->vsg,
            'tiempo_protrombina' => $this->tiempo_protrombina,
            'tpt'       => $this->tpt,
            'observaciones' => $this->observaciones,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
