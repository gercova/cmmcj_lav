<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstructionDegree extends Model
{
    use HasFactory;

    protected $table        = 'grados_instruccion';
    protected $primaryKey   = 'id';

    public function historia(): HasMany {
        return $this->hasMany(History::class, 'grado_instruccion_id');
    }
}
