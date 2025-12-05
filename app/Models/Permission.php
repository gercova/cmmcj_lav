<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission {

    use HasFactory, Auditable;

    protected $table    = 'permissions';
    protected $fillable = ['name', 'guard_name', 'submodule_id', 'descripcion'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $casts = [
        'name'          => 'string',
        'guard_name'    => 'string',
        'submodule_id'  => 'integer',
        'descripcion'   => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function submodule(): BelongsTo {
        return $this->belongsTo(Submodule::class);
    }
}
