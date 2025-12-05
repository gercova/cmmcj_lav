<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'citas';
    protected $fillable = [
        'historia_id',
        'estado_cita_id',
        'user_id',
        'fecha',
        'hora',
        'motivo_consulta',
        'observaciones',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'historia_id'       => 'integer',
        'estado_cita_id'    => 'integer',
        'user_id'           => 'integer',
        'fecha'             => 'date',
        'hora'              => 'datetime:H:i',
        'motivo_consulta'   => 'string',
        'observaciones'     => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function historia(): BelongsTo {
        return $this->belongsTo(History::class, 'historia_id');
    }

    public function estadoCita(): BelongsTo {
        return $this->belongsTo(AppointmentsStatus::class, 'estado_cita_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
