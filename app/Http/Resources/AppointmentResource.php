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
            'estado_cita_id'    => $this->estado_cita_id,
            'fecha'             => $this->fecha,
            'hora'              => $this->hora, 
            'descripcion'       => $this->descripcion,
        ];
    }
}
