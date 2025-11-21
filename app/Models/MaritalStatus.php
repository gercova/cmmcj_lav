<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaritalStatus extends Model
{
    use HasFactory;

    protected $table        = 'estado_civil';
    protected $primaryKey   = 'id';
    protected $fillable     = ['descripcion'];
    public $timestamps      = false;

    public function historia(): HasMany {
        return $this->hasMany(History::class, 'estado_civil_id');
    }
}
