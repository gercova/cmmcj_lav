<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submodule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'submodules';
    protected $primaryKey   = 'id';
    protected $fillable     = ['module_id', 'nombre', 'descripcion', 'ruta', 'icono', 'orden'];
    protected $dates        = ['created_at', 'updated_at', 'deleted_at'];
}
