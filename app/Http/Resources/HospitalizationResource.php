<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'history_id'        => HistoryResource::collection($this->whenLoaded('historias')),
            'bed_id'            => BedResource::collection($this->whenLoaded('beds')),
            'fc'                => $this->fc,
            't'                 => $this->t,
            'so2'               => $this->so2,
            'vital_functions'   => $this->vital_functions,
            'observations'      => $this->observations,
            'others'            => $this->others,
        ];
    }
}
