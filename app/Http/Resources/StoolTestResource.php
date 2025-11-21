<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoolTestResource extends JsonResource {
    
    public function toArray(Request $request): array {
        return [
            'id'                    => $this->id,
            'examen_id'             => $this->examen_id,
            'historia_id'           => $this->historia_id,
            'examen'                => new ExamResource($this->whenLoaded('examen')),
            'consistencia'          => $this->consistencia,
            'color'                 => $this->color,
            'mucus'                 => $this->mucus,
            'restos_alimenticios'   => $this->restos_alimenticios,
            'leucocitos'            => $this->leucocitos,
            'hematies'              => $this->hematies,
            'bacterias'             => $this->bacterias,
            'levaduras'             => $this->levaduras,
            'parasitos'             => $this->parasitos,
            'huevos_parasitos'      => $this->huevos_parasitos,
            'sangre_oculta'         => $this->sangre_oculta,
            'ph'                    => $this->ph,
            'grasa_fecal'           => $this->grasa_fecal,
            'cultivo_bacteriano'    => $this->cultivo_bacteriano,
            'sensibilidad_antimicrobiana' => $this->sensibilidad_antimicrobiana,
            'observaciones'         => $this->observaciones,
            'created_at'            => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
