<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model {
    use HasFactory, SoftDeletes, Auditable;

    protected $table        = 'modules';
    protected $primaryKey   = 'id';
    protected $fillable     = ['descripcion'];
    protected $hidden       = ['created_at', 'updated_at', 'deleted_at'];

    public function submodules(): HasMany {
        return $this->hasMany(Submodule::class);
    }
}
