<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'citas';
    protected $fillable = [
        'historia_id',
        'estado_cita_id',
        'fecha',
        'hora',
        'descripcion',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'historia_id'       => 'integer',
        'estado_cita_id'    => 'integer',
        'fecha'             => 'date',
        'hora'              => 'datetime',
        'descripcion'       => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function estadoCita(): BelongsTo {
        return $this->belongsTo(AppointmentsStatus::class, 'estado_cita_id');
    }
}
