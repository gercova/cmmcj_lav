<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource {
    
    public function toArray(Request $request): array {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'guard_name'    => $this->guard_name,
            'descripcion'   => $this->descripcion,
            'submodule_id'  => $this->submodule_id,
            'submodule'     => new SubmoduleResource($this->whenLoaded('submodule')),
            'module'        => new ModuleResource($this->whenLoaded('submodule.module')),
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
