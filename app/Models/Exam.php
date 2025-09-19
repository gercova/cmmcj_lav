<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examenes';
    protected $primaryKey   = 'id';
    protected $guarded      = [];
    protected $casts        = [
        'historia_id'       => 'integer',
        'examen_tipo_id'    => 'integer',
        'pa'                => 'string',
        'fc'                => 'string',
        'fr'                => 'string',
        't'                 => 'string',
        'peso'              => 'string',
        'talla'             => 'string',
        'imc'               => 'string',
        'motivo_consulta'   => 'string',
        'm'                 => 'string',
        'rc'                => 'string',
        'g'                 => 'string',
        'p'                 => 'string',
        'r1'                => 'string',
        'u_parto'           => 'string',
        'u_pap'             => 'string',
        'u_ivaa'            => 'string',
        'mac_id'            => 'integer',
        'fum'               => 'date',
        'fpp'               => 'date',
        'edad_gestacional'  => 'string',
        'apreciacion_general' => 'string',
        'piel_mucosas'      => 'string',
        'aparato_respiratorio' => 'string',
        'cardio_vascular'   => 'string',
        'abdomen'           => 'string',
        'i_abm'             => 'string',
        'ap_abm'            => 'string',
        'c_abm'             => 'string',
        'p_abm'             => 'string',
        'mo_abm'            => 'string',
        'io_ro_abm'         => 'string',
        'genito_urinario'   => 'string',
        'neurologico'       => 'string',
        'au'                => 'string',
        'spp'               => 'string',
        'lcf'               => 'string',
        'du'                => 'string',
        'mf'                => 'string',
        'oh'                => 'string',
        'psc_prox_1'        => 'string',
        'psc_prox_2'        => 'string',
        'psc_prox_3'        => 'string',
        'psc_prox_4'        => 'string',
        'psc_prox_5'        => 'string',
        'psc_prox_6'        => 'string',
        'psc_prox_1'        => 'string',
        'nutricion'         => 'string',
        'psicologia_1'      => 'string',
        'psicologia_2'      => 'sttring',
        'psicologia_3'      => 'string',
        'psicologia_4'      => 'string',
        'pezon'             => 'string',
        'recomendaciones'   => 'string',
        'is_active'         => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function medicationExams() {
        return $this->hasMany(MedicationExam::class, 'examen_id');
    }
}
