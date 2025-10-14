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
            'module_id'     => $this->module_id,
            'descripcion'   => $this->descripcion,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
