<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DischargeType extends Model
{
    use HasFactory;

    protected $table        = 'tipo_egreso';
    protected $primaryKey   = 'id';
    protected $fillable     = ['nombre'];
    public $timestamps      = false;

    public function Hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class, 'tipo_egreso_id');
    }
}
