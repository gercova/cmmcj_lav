<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        = 'examen_documento';
    protected $primaryKey   = 'id';
    //protected $guarded      = [];
    protected $fillable     = ['examen_id', 'historia_id', 'nombre_examen', 'documento', 'fecha_examen'];
    protected $hidden       = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts        = [
        'examen_id'         => 'integer',
        'historia_id'       => 'integer',
        'nombre_examen'     => 'string',
        'documento'         => 'string',
        'fecha_examen'      => 'date',  
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function examen(): BelongsTo {
        return $this->belongsTo(Exam::class, 'examen_id');
    }

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class, 'historia_id');
    }
}
