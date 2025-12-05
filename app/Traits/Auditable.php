<?php
// app/Traits/Auditable.php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable() {
        static::created(function ($model) {
            self::logAction($model, 'created');
        });

        static::updated(function ($model) {
            self::logAction($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logAction($model, 'deleted');
        });
    }

    private static function logAction($model, $action) {
        $oldValues = $action === 'updated' ? $model->getOriginal() : null;
        $newValues = $action !== 'deleted' ? $model->getAttributes() : null;

        AuditLog::create([
            'action'        => $action,
            'description'   => self::getDescription($model, $action),
            'model_type'    => get_class($model),
            'model_id'      => $model->id,
            'old_values'    => $oldValues,
            'new_values'    => $newValues,
            'url'           => Request::fullUrl(),
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::header('User-Agent'),
            'user_id'       => Auth::id(),
        ]);
    }

    private static function getDescription($model, $action): string {
        $modelName = class_basename($model);
        $descriptions = [
            'created' => "Nuevo {$modelName} creado",
            'updated' => "{$modelName} actualizado",
            'deleted' => "{$modelName} eliminado"
        ];

        return $descriptions[$action] ?? "Acción {$action} en {$modelName}";
    }
}
