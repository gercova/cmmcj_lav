<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'historias';
    protected $primaryKey   = 'id';
    protected $guarded      = [];

    protected $casts = [
        'tipo_documento_id'         => 'integer',
        'dni'                       => 'string:8',
        'nombres'                   => 'string',
        'apellidos'                 => 'string',
        'sexo'                      => 'enum',
        'fecha_nacimiento'          => 'date',
        'telefono'                  => 'string:11',
        'email'                     => 'string',
        'direccion'                 => 'string',
        'grupo_sanguineo_id'        => 'integer',
        'grado_instruccion_id'      => 'integer',
        'ubigeo_nacimiento'         => 'string',
        'ubigeo_residencia'         => 'string',
        'ocupacion_id'              => 'integer',
        'estado_civil_id'           => 'integer',
        'acompanante'               => 'string',
        'acompanante_telefono'      => 'string',
        'acompanante_direccion'     => 'string',
        'vinculo'                   => 'string',
        'seguro_id'                 => 'integer',
        'seguro_descripcion'        => 'string',
        'antecedentes_quirurgicos'  => 'string',
        'antecedentes_patologicos'  => 'string',
        'antecedentes_familiares'   => 'string',
        'antecedentes_medicos'      => 'string',
        'rams'                      => 'string',
        'is_active'                 => 'boolean',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
        'deleted_at'                => 'datetime'
    ];

    
}
