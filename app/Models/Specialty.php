<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model {
    
    use HasFactory, SoftDeletes;

    protected $table        = 'especialidades';
    protected $primaryKey   = 'id';
    protected $guarded      = [];

    protected $casts = [
        'ocupacion_id'  => 'integer',
        'descripcion'   => 'string',
    ];
}
