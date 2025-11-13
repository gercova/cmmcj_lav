<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionCondition extends Model
{
    use HasFactory;

    protected $table        = 'condicion_ingreso';
    protected $primaryKey   = 'id';
    protected $fillable     = ['nombre'];
    public $timestamps      = false;

    public function Hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class, 'condicion_ingreso_id');
    }
}
