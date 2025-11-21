<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodGroup extends Model
{
    use HasFactory;

    protected $table        = 'grupos_sanguineos';
    protected $primaryKey   = 'id';
    protected $fillable     = ['nombre'];
    public $timestamps      = false;

    public function historia(): HasMany {
        return $this->hasMany(History::class, 'grupo_sanguineo_id');
    }
}
