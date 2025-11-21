<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrineTest extends Model {
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_orina';
    protected $primaryKey   = 'id';
    protected $fillable = [
        'examen_id',
        'historia_id',
        'color',
        'aspecto',
        'densidad',
        'ph',
        'proteinas',
        'glucosa',
        'cetonas',
        'bilirrubina',
        'sangre_oculta',
        'urobilinogeno',
        'nitritos',
        'leucocitos_quimico',
        'leucocitos_campo',
        'hematies_campo',
        'celulas_epiteliales',
        'bacterias',
        'cristales',
        'cilindros',
        'mucus',
        'observaciones',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'examen_id'     => 'integer',
        'historia_id'   => 'integer',
        'color'         => 'string',
        'aspecto'       => 'string',
        'densidad'      => 'decimal:2',
        'ph'            => 'decimal:2',
        'proteinas'     => 'string',
        'glucosa'       => 'string',
        'cetonas'       => 'string',
        'bilirrubina'   => 'string',
        'sangre_oculta' => 'string',
        'urobilinogeno' => 'string',
        'nitritos'      => 'string',
        'leucocitos_quimico'    => 'string',
        'leucocitos_campo'      => 'string',
        'hematies_campo'        => 'string',
        'celulas_epiteliales'   => 'string',
        'bacterias'     => 'string',
        'cristales'     => 'string',
        'cilindros'     => 'string',
        'mucus'         => 'string',
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