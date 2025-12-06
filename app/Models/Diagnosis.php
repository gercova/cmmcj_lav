<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table        = 'diagnosticos';
    protected $primaryKey   = 'id';
    protected $guarded      = [];
    protected $casts        = [
        'codigo'        => 'string',
        'descripcion'   => 'string',
        'tipo'          => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public static function getAllDiagnostics($startIndex, $pageSize, $itemSearch) {
        $query = Diagnosis::where('codigo', 'like', "%{$itemSearch}%")
            ->orWhere('descripcion', 'like', "%{$itemSearch}%");

        $count      = $query->count();
        $results    = $query->offset($startIndex)
            ->limit($pageSize)
            ->get();
        return [$results, $count];
    }

    public function examenDiagnostico(): HasMany {
        return $this->hasMany(DiagnosticExam::class, 'diagnostico_id');
    }
}
