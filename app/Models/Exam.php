<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model {
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table        = 'examenes';
    protected $primaryKey   = 'id';
    protected $fillable     = ['historia_id', 'examen_tipo_id', 'pa', 'fc', 'fr', 't', 'peso', 'talla', 'imc', 'motivo_consulta', 'm', 'rc', 'g', 'p', 'r1', 'u_parto', 'u_pap', 'u_ivaa', 'mac_id', 'fum', 'fpp', 'edad_gestacional', 'apreciacion_general', 'piel_mucosas', 'aparato_respiratorio', 'cardio_vascular', 'abdomen', 'd_abm', 'i_abm', 'ap_abm', 'c_abm', 'p_abm', 'mo_abm', 'io_ro_abm', 'genito_urinario', 'neurologico', 'au', 'spp', 'lcf', 'du', 'mf', 'oh', 'psc_prox_1', 'psc_prox_2', 'psc_prox_3', 'psc_prox_4', 'psc_prox_5', 'psc_prox_6', 'psc_prox_1', 'nutricion', 'psicologia_1', 'psicologia_2', 'psicologia_3', 'psicologia_4', 'pezon', 'recomendaciones'];

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
        'apreciacion_general'   => 'string',
        'piel_mucosas'          => 'string',
        'aparato_respiratorio'  => 'string',
        'cardio_vascular'   => 'string',
        'abdomen'           => 'string',
        'd_abm'             => 'string',
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
        'psicologia_2'      => 'string',
        'psicologia_3'      => 'string',
        'psicologia_4'      => 'string',
        'pezon'             => 'string',
        'recomendaciones'   => 'string',
        'is_active'         => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class, 'historia_id');
    }

    public function tipoExamen(): BelongsTo {
        return $this->belongsTo(ExamType::class, 'examen_tipo_id');
    }

    public function metodoAnticonceptivo(): BelongsTo {
        return $this->belongsTo(ContraceptiveMethod::class, 'mac_id');
    }

    public function diagnostico(): HasMany {
        return $this->hasMany(DiagnosticExam::class, 'exam_id');
    }

    public function medicacion(): HasMany {
        return $this->hasMany(MedicationExam::class, 'examen_id');
    }

    public function documento(): HasMany {
        return $this->hasMany(DocumentExam::class, 'examen_id');
    }

    public function examenSangre(): HasMany {
        return $this->hasMany(BloodTest::class, 'examen_id');
    }

    public function examenOrina(): HasMany {
        return $this->hasMany(UrineTest::class, 'examen_id');
    }

    public function examenHeces(): HasMany {
        return $this->hasMany(StoolTest::class, 'examen_id');
    }
}
