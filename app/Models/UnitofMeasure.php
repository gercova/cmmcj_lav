<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitofMeasure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unidad_medida';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'aka',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'descripcion'   => 'string',
        'aka'           => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function drugs(){
        return $this->hasMany(Drug::class, 'unidad_medida_id');
    }
}
