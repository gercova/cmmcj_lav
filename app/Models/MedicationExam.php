<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_medicacion';
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    protected $fillable     = [
        'examen_id',
        'historia_id',
        'farmaco_id',
        'descripcion',
        'dosis',
        'frecuencia',
        'duracion'
    ];

    protected $casts = [
        'examen_id'     => 'integer',
        'historia_id'   => 'integer',
        'farmaco_id'    => 'integer',
        'descripcion'   => 'string',
        'dosis'         => 'string',
        'frecuencia'    => 'string',
        'duracion'      => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function histories(){
        return $this->belongsTo(History::class, 'historia_id');
    }

    public function exams(){
        return $this->belongsTo(Exam::class, 'examen_id');
    }

    public function drugs(){
        return $this->belongsTo(Drug::class, 'farmaco_id');
    }
}
