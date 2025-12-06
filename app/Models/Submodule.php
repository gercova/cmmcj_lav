<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submodule extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table        = 'submodules';
    protected $primaryKey   = 'id';
    protected $fillable     = ['module_id', 'nombre', 'descripcion'];
    protected $dates        = ['created_at', 'updated_at', 'deleted_at'];

    public function module(): BelongsTo {
        return $this->belongsTo(Module::class);
    }

    public function permission(): HasMany {
        return $this->hasMany(Permission::class);
    }
}
