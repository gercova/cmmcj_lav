<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarefulType extends Model
{
    use HasFactory;

    protected $table        = 'tipo_cuidado';
    protected $primaryKey   = 'id';
    protected $fillable     = ['nombre'];
    public $timestamps      = false;

    public function Hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class, 'tipo_cuidado_id');
    }
}
