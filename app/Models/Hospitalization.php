<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Database\Factories\HospitalizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospitalization extends Model {

    use HasFactory, SoftDeletes, LogsActivity;

    protected $table        = 'hospitalizaciones';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'historia_id',
        'cama_id',
        'fc',
        't',
        'so2',
        'fecha_admision',
        'tipo_admision_id',
        'via_ingreso_id',
        'motivo_hospitalizacion',
        'alergias',
        'medicamentos_habituales',
        'antecedentes_importantes',
        'condicion_ingreso_id',
        'servicio_id',
        'tipo_cuidado_id',
        'fecha_egreso',
        'tipo_egreso_id',
        'diagnostico_egreso',
        'condicion_egreso_id',
        'resumen_evolucion',
        'causa_muerte',
        'nro_autorizacion_seguro',
        'aseguradora',
        'estado_hospitalizacion_id',
        'user_id'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'historia_id'               => 'integer',
        'cama_id'                   => 'integer',
        'fc'                        => 'string',
        't'                         => 'string',
        'so2'                       => 'string',
        'fecha_admision'            => 'date',
        'tipo_admision_id'          => 'integer',
        'via_ingreso_id'            => 'integer',
        'motivo_hospitalizacion'    => 'string',
        'alergias'                  => 'string',
        'medicamentos_habituales'   => 'string',
        'antecedentes_importantes'  => 'string',
        'condicion_ingreso_id'      => 'integer',
        'servicio_id'               => 'integer',
        'tipo_cuidado_id'           => 'integer',
        'fecha_egreso'              => 'date',
        'tipo_egreso_id'            => 'integer',
        'diagnostico_egreso'        => 'string',
        'condicion_egreso_id'       => 'integer',
        'resumen_evolucion'         => 'string',
        'causa_muerte'              => 'string',
        'nro_autorizacion_seguro'   => 'string',
        'aseguradora'               => 'string',
        'estado_hospitalizacion_id' => 'integer',
        'user_id'                   => 'integer'
    ];

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class);
    }

    public function cama(): BelongsTo {
        return $this->belongsTo(Bed::class);
    }

    public function tipoAdmision(): BelongsTo {
        return $this->belongsTo(AdmissionType::class, 'tipo_admision_id');
    }

    public function viaIngreso(): BelongsTo {
        return $this->belongsTo(ViaEntry::class, 'via_ingreso_id');
    }

    public function condicionIngreso(): BelongsTo {
        return $this->belongsTo(AdmissionCondition::class, 'condicion_ingreso_id');
    }

    public function servicio(): BelongsTo {
        return $this->belongsTo(Service::class, 'servicio_id');
    }

    public function tipoCuidado(): BelongsTo {
        return $this->belongsTo(CarefulType::class, 'tipo_cuidado_id');
    }

    public function tipoEgreso(): BelongsTo {
        return $this->belongsTo(DischargeType::class, 'tipo_egreso_id');
    }

    public function condicionEgreso(): BelongsTo {
        return $this->belongsTo(DischargeCondition::class, 'condicion_egreso_id');
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    // Especificar el factory personalizado
    protected static function newFactory() {
        return HospitalizationFactory::new();
    }
}
