<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'servicio';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function Hospitalization(): HasMany {
        return $this->hasMany(Hospitalization::class, 'servicio_id');
    }
}
