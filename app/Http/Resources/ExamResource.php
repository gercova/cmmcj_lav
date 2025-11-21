<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array {
        return [
            'historia_id'       => $this->historia_id,
            'historia'          => new HistoryResource($this->whenLoaded('historia')),
            'examen_tipo_id'    => $this->examen_tipo_id,
            'pa'                => $this->pa,
            'fc'                => $this->fc,
            'fr'                => $this->fr,
            't'                 => $this->t,
            'peso'              => $this->peso,
            'talla'             => $this->talla,
            'imc'               => $this->imc,
            'motivo_consulta'   => $this->motivo_consulta,
            'm'                 => $this->m,
            'rc'                => $this->rc,
            'g'                 => $this->g,
            'p'                 => $this->p,
            'r1'                => $this->r1,
            'u_parto'           => $this->u_parto,
            'u_pap'             => $this->u_pap,
            'u_ivaa'            => $this->u_ivaa,
            'mac_id'            => $this->mac_id,
            'fum'               => $this->fum,
            'fpp'               => $this->fpp,
            'edad_gestacional'  => $this->edad_gestacional,
            'apreciacion_general'   => $this->apreciacion_general,
            'piel_mucosas'          => $this->piel_mucosas,
            'aparato_respiratorio'  => $this->aparato_respiratorio,
            'cardio_vascular'   => $this->cardio_vascular,
            'abdomen'           => $this->abdomen,
            'd_abm'             => $this->d_abm,
            'i_abm'             => $this->i_abm,
            'ap_abm'            => $this->ap_abm,
            'c_abm'             => $this->c_abm,
            'p_abm'             => $this->p_abm,
            'mo_abm'            => $this->mo_abm,
            'io_ro_abm'         => $this->io_ro_abm,
            'genito_urinario'   => $this->genito_urinario,
            'neurologico'       => $this->neurologico,
            'au'                => $this->au,
            'spp'               => $this->spp,
            'lcf'               => $this->lcf,
            'du'                => $this->du,
            'mf'                => $this->mf,
            'oh'                => $this->oh,
            'psc_prox_1'        => $this->psc_prox_1,
            'psc_prox_2'        => $this->psc_prox_2,
            'psc_prox_3'        => $this->psc_prox_3,
            'psc_prox_4'        => $this->psc_prox_4,
            'psc_prox_5'        => $this->psc_prox_5,
            'psc_prox_6'        => $this->psc_prox_6,
            'psc_prox_1'        => $this->psc_prox_1,
            'nutricion'         => $this->nutricion,
            'psicologia_1'      => $this->psicologia_1,
            'psicologia_2'      => $this->psicologia_2,
            'psicologia_3'      => $this->psicologia_3,
            'psicologia_4'      => $this->psicologia_4,
            'pezon'             => $this->pezon,
            'recomendaciones'   => $this->recomendaciones,
            'is_active'         => $this->is_active,
            'created_at'        => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
