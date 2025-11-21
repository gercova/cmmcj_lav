<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegreesInstruction extends Model
{
    use HasFactory;

    protected $table    = 'grados_instruccion';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre'];
    public $timestamps  = false;

    public function historia() {
        return $this->hasMany(History::class, 'grado_instruccion_id');
    }
}
