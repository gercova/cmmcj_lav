<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViaEntry extends Model
{
    use HasFactory;

    protected $table        = 'via_ingreso';
    protected $primaryKey   = 'id';
    protected $fillable     = ['nombre'];
    public $timestamps      = false;

    public function hospitalizacion(): HasMany {
        return $this->hasMany(Hospitalization::class, 'via_ingreso_id');
    }
}
