<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmoduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'module_id'     => $this->module_id,
            'module'        => new ModuleResource($this->whenLoaded('module')),
            'nombre'        => $this->nombre,
            'descripcion'   => $this->descripcion,
            'ruta'          => $this->ruta,
            'icono'         => $this->icono,
            'orden'         => $this->orden
        ];
    }
}
