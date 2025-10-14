<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission {
    use HasFactory;

    protected $table    = 'permissions';
    protected $fillable = ['name', 'guard_name', 'module_id', 'descripcion'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function module(){
        return $this->belongsTo(Module::class);
    }
}
