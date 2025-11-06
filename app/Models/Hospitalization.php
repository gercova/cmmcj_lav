<?php

namespace App\Models;

use Database\Factories\HospitalizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospitalization extends Model {

    use HasFactory, SoftDeletes;

    protected $table        = 'hospitalizations';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'historia_id', 
        'bed_id', 
        'fc', 
        't', 
        'so2',
        'vital_functions', 
        'observations', 
        'others'
    ];
    protected $dates        = ['created_at', 'updated_at', 'deleted_at'];

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class);
    }

    public function bed(): BelongsTo {
        return $this->belongsTo(Bed::class);
    }

    // Especificar el factory personalizado
    protected static function newFactory() {
        return HospitalizationFactory::new();
    }
}
