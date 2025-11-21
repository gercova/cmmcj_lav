<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model {
    
    use HasFactory, SoftDeletes;

    protected $table        = 'especialidades';
    protected $primaryKey   = 'id';
    protected $fillable     = ['ocupacion_id', 'descripcion'];
    protected $dates        = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'ocupacion_id'  => 'integer',
        'descripcion'   => 'string',
    ];

    public function ocupacion(): BelongsTo {
        return $this->belongsTo(Occupation::class, 'ocupacion_id');
    }
}
