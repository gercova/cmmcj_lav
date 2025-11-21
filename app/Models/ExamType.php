<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamType extends Model
{
    use HasFactory;

    protected $table        = 'tipo_examen';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function examen(): HasMany {
        return $this->hasMany(Exam::class, 'examen_tipo_id');
    }
}
