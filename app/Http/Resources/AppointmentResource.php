<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array{
        return [
            'id'                => $this->id,
            'historia_id'       => $this->historia_id,
            'historia'          => HistoryResource::make($this->whenLoaded('historia')),
            'estado_cita_id'    => $this->estado_cita_id,
            'user_id'           => $this->user_id,
            'fecha'             => $this->fecha->format('Y-m-d'),
            'hora'              => $this->hora->format('H:i'),
            'motivo_consulta'   => $this->motivo_consulta,
            'observaciones'     => $this->observaciones,
        ];
    }
}
