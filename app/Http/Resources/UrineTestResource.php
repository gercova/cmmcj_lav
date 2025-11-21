<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrineTestResource extends JsonResource {
    
    public function toArray(Request $request): array {
        return [
            'id'            => $this->id,
            'examen_id'     => $this->examen_id,
            'historia_id'   => $this->historia_id,
            'examen'        => new ExamResource($this->whenLoaded('examen')),
            'color'         => $this->color,
            'aspecto'       => $this->aspecto,
            'densidad'      => $this->densidad,
            'ph'            => $this->ph,
            'proteinas'     => $this->proteinas,
            'glucosa'       => $this->glucosa,
            'cetonas'       => $this->cetonas,
            'bilirrubina'   => $this->bilirrubina,
            'sangre_oculta' => $this->sangre_oculta,
            'urobilinogeno' => $this->urobilinogeno,
            'nitritos'      => $this->nitritos,
            'leucocitos_quimico'    => $this->leucocitos_quimico,
            'leucocitos_campo'      => $this->leucocitos_campo,
            'hematies_campo'        => $this->hematies_campo,
            'celulas_epiteliales'   => $this->celulas_epiteliales,
            'bacterias'     => $this->bacterias,
            'cristales'     => $this->cristales,
            'cilindros'     => $this->cilindros,
            'mucus'         => $this->mucus,
            'observaciones' => $this->observaciones,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
