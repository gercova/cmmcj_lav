<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiagnosticExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_diagnostico';
    protected $primaryKey   = 'id';
    protected $fillable     = ['examen_id', 'historia_id', 'diagnostico_id', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts        = [
        'examen_id'         => 'integer',
        'historia_id'       => 'integer',
        'diagnostico_id'    => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];
}
