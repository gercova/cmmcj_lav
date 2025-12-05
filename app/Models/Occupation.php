<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Occupation extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table        = 'ocupaciones';
    protected $primaryKey   = 'id';
    protected $fillable     = ['descripcion'];

    protected $hidden       = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts        = [
        'descripcion'   => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function historia() {
        return $this->hasMany(History::class, 'ocupacion_id');
    }
}
