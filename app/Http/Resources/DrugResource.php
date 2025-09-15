<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrugResource extends JsonResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'unidad_medida_id'  => $this->unidad_medida_id,
            'descripcion'       => $this->descripcion,
            'detalle'           => $this->detalle,
            'precio'            => $this->precio,
            'stock'             => $this->stock,
            'stock_min'         => $this->stock_min,
            'stock_max'         => $this->stock_max,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'deleted_at'        => $this->deleted_at
        ];
    }
}
