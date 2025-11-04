<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'sangre',
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
}
