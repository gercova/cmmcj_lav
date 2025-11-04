<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_sangre';
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
}
