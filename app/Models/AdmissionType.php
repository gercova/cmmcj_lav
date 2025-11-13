<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionType extends Model
{
    use HasFactory;

    protected $table        = 'tipo_admision';
    protected $primaryKey   = 'id';
    protected $fillable     = ['nombre'];
    public $timestamps      = false;

    public function Hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class, 'tipo_admision_id');
    }
}
