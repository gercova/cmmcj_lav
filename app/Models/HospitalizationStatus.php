<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HospitalizationStatus extends Model
{
    use HasFactory;

    protected $table = 'estado_hospitalizacion';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function Hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class, 'estado_hospitalizacion_id');
    }
}
