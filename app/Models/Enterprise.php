<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;

    protected $table        = 'empresa';
    protected $primaryKey   = 'id';
    protected $fillable     = ['ruc', 'razon_social', 'nombre_comercial', 'rubro_empresa', 'codigo_pais', 'telefono_comercial', 'email_comercial', 'pais', 'ciudad', 'direccion', 'pagina_web', 'representante_legal', 'foto_representante', 'logo_miniatura', 'logo_principal', 'frase_empresa', 'fecha_creacion'];
    
    protected $casts        = [
        'ruc'                   => 'string',
        'razon_social'          => 'string',
        'nombre_comercial'      => 'string',
        'rubro_empresa'         => 'string',
        'codigo_pais'           => 'string',
        'telefono_comercial'    => 'string',
        'email_comercial'       => 'string',
        'pais'                  => 'string',
        'ciudad'                => 'string',
        'direccion'             => 'string',
        'pagina_web'            => 'string',
        'representante_legal'   => 'string',
        'foto_representante'    => 'string',
        'logo_miniatura'        => 'string',
        'logo_principal'        => 'string',
        'frase_empresa'         => 'string',
        'fecha_creacion'        => 'date',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];
}
