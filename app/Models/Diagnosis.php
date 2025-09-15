<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'diagnosticos';
    protected $primaryKey   = 'id';
    protected $fillable     = ['codigo', 'descripcion','tipo',  'created_at', 'updated_at', 'deleted_at'];
    protected $casts        = [
        'codigo'        => 'string',
        'descripcion'   => 'string',
        'tipo'          => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];
}
