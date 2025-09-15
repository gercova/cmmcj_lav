<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DegreesInstruction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table    = 'grados_instruccion';
    protected $fillable = ['descripcion'];

    public function histories() {
        return $this->hasMany(History::class, 'grado_instruccion_id');
    }
}
