<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrineTest extends Model
{
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
}
