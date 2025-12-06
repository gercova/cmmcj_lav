<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';

    protected $fillable = [
        'action',
        'module',
        'record_id',
        'old_data',
        'new_data',
        'user_id',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener las acciones de hoy
     */
    public function scopeToday($query) {
        return $query->whereDate('created_at', today());
    }

    /**
     * Obtener las acciones de un usuario específico
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Formatear la acción para mostrar
     */
    public function getFormattedActionAttribute(): string {
        $actions = [
            'access' => 'Acceso',
            'create' => 'Creación',
            'update' => 'Actualización',
            'delete' => 'Eliminación'
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Formatear la hora
     */
    public function getFormattedTimeAttribute(): string {
        return $this->created_at->format('H:i:s');
    }
}
