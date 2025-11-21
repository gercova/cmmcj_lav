<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insurance extends Model
{
    use HasFactory;

    protected $table        = 'seguros';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function historia(): HasMany {
        return $this->hasMany(History::class, 'seguro_id');
    }
}
