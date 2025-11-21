<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_hematologia';
    protected $primaryKey   = 'id';
    protected $fillable = [
        'examen_id',
        'historia_id',
        'hemoglobina',
        'hematocrito',
        'leucocitos',
        'neutrofilos',
        'linfocitos',
        'monocitos',
        'eosinofilos',
        'basofilos',
        'plaquetas',
        'glucosa',
        'urea',
        'creatinina',
        'acido_urico',
        'colesterol_total',
        'trigliceridos',
        'transaminasas_got',
        'transaminasas_gpt',
        'bilirrubina_total',
        'bilirrubina_directa',
        'fosfatasa_alcalina',
        'proteinas_totales',
        'albumina',
        'globulina',
        'sodio',
        'potasio',
        'cloro',
        'calcio',
        'vsg',
        'tiempo_protrombina',
        'tpt',
        'observaciones',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'examen_id'     => 'integer',
        'historia_id'   => 'integer',
        'hemoglobina'   => 'decimal:2',
        'hematocrito'   => 'decimal:2',
        'leucocitos'    => 'decimal:2',
        'neutrofilos'   => 'decimal:2',
        'linfocitos'    => 'decimal:2',
        'monocitos'     => 'decimal:2',
        'eosinofilos'   => 'decimal:2',
        'basofilos'     => 'decimal:2',
        'plaquetas'     => 'decimal:2',
        'glucosa'       => 'decimal:2',
        'urea'          => 'decimal:2',
        'creatinina'    => 'decimal:2',
        'acido_urico'   => 'decimal:2',
        'colesterol_total'  => 'decimal:2',
        'trigliceridos'     => 'decimal:2',
        'transaminasas_got' => 'decimal:2',
        'transaminasas_gpt' => 'decimal:2',
        'bilirrubina_total' => 'decimal:2',
        'bilirrubina_directa'   => 'decimal:2',
        'fosfatasa_alcalina'    => 'decimal:2',
        'proteinas_totales'     => 'decimal:2',
        'albumina'      => 'decimal:2',
        'globulina'     => 'decimal:2',
        'sodio'         => 'decimal:2',
        'potasio'       => 'decimal:2',
        'cloro'         => 'decimal:2',
        'calcio'        => 'decimal:2',
        'vsg'           => 'decimal:2',
        'tiempo_protrombina' => 'decimal:2',
        'tpt'           => 'decimal:2', 
        'observaciones' => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function examen(): BelongsTo {
        return $this->belongsTo(Exam::class, 'examen_id');
    }

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class, 'historia_id');
    }
}
