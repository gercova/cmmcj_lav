<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drug extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'farmacos';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'unidad_medida_id',
        'descripcion',
        'detalle',
        'precio',
        'stock',
        'minimo_stock',
        'maximo_stock',
        // 'fecha_vencimiento',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'unidad_medida_id'  => 'integer',
        'descripcion'       => 'string',
        'detalle'           => 'string',
        'precio'            => 'decimal:2',
        'stock'             => 'integer',
        'minimo_stock'      => 'integer',
        'maximo_stock'      => 'integer',
        // 'fecha_vencimiento' => 'date',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function unidadMedida(){
        return $this->belongsTo(UnitofMeasure::class, 'unidad_medida_id');
    }

    public function medications(){
        return $this->hasMany(MedicationExam::class, 'farmaco_id');
    }
}
