<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrugResource extends JsonResource
{
    public function toArray(Request $request): array {
        return [
            'id'    => $this->id,
            'label' => $this->farmaco . ' (' . $this->unidad . ')',
        ];
    }
}
