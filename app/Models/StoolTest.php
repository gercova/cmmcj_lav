<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoolTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_heces';
    protected $primaryKey   = 'id';
    protected $fillable = [
        'examen_id',
        'historia_id',
        'consistencia',
        'color',
        'mucus',
        'restos_alimenticios',
        'leucocitos',
        'hematies',
        'bacterias',
        'levaduras',
        'parasitos',
        'huevos_parasitos',
        'sangre_oculta',
        'ph',
        'grasa_fecal',
        'cultivo_bacteriano',
        'sensibilidad_antimicrobiana',
        'observaciones',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'examen_id'             => 'integer',
        'historia_id'           => 'integer',
        'consistencia'          => 'string',
        'color'                 => 'string',
        'mucus'                 => 'string',
        'restos_alimenticios'   => 'string',
        'leucocitos'            => 'string',
        'hematies'              => 'string',
        'bacterias'             => 'string',
        'levaduras'             => 'string',
        'parasitos'             => 'string',
        'huevos_parasitos'      => 'string',
        'sangre_oculta'         => 'string',
        'ph'                    => 'decimal:2',
        'grasa_fecal'           => 'string',
        'cultivo_bacteriano'    => 'string',
        'sensibilidad_antimicrobiana' => 'string',
        'observaciones'         => 'string',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
    ];

    public function examen(): BelongsTo {
        return $this->belongsTo(Exam::class, 'examen_id');
    }

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class, 'historia_id');
    }
}
