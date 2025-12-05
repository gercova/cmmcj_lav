<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'action',
        'description',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime:d/m/Y H:i:s'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo {
        return $this->morphTo();
    }

    /**
     * Obtener logs del día actual
     */
    public static function getTodayLogs() {
        return self::whereDate('created_at', today())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener logs por usuario
     */
    public static function getLogsByUser($userId) {
        return self::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Formatear acción para mostrar
     */
    public function getFormattedActionAttribute(): string {
        $actions = [
            'created'       => 'Creación',
            'updated'       => 'Actualización',
            'deleted'       => 'Eliminación',
            'login'         => 'Inicio de sesión',
            'logout'        => 'Cierre de sesión',
            'viewed'        => 'Visualización',
            'downloaded'    => 'Descarga',
            'exported'      => 'Exportación'
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Obtener hora formateada
     */
    public function getTimeAttribute(): string {
        return $this->created_at->format('H:i');
    }
}
