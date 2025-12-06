<?php

namespace App\Traits;

use App\Jobs\LogActivityJob;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity {

    /**
     * Registrar actividad automáticamente
     */
    public static function bootLogsActivity() {
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', $model, $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    /**
     * Método para registrar actividad manualmente con Job
     */
    public static function logActivity($action, $model, $oldData = null, $newData = null) {
        $module = class_basename($model);
        $recordId = $model->getKey();

        // Excluir campos sensibles
        $excludedFields = ['password', 'remember_token', 'api_token', 'email_verified_at'];

        // Procesar datos antiguos
        if ($oldData) {
            $oldData = array_filter($oldData, function($key) use ($excludedFields) {
                return !in_array($key, $excludedFields);
            }, ARRAY_FILTER_USE_KEY);
        }

        // Procesar datos nuevos para update
        if ($action === 'update' && $newData) {
            $changes = [];
            foreach ($newData as $key => $value) {
                if (!in_array($key, $excludedFields)) {
                    if (array_key_exists($key, $oldData)) {
                        $changes[$key] = [
                            'old' => $oldData[$key],
                            'new' => $value
                        ];
                    }
                }
            }
            $newData = $changes;
        }

        // Despachar el Job
        LogActivityJob::dispatch([
            'action'        => $action,
            'module'        => $module,
            'record_id'     => $recordId,
            'old_data'      => $oldData,
            'new_data'      => $newData,
            'user_id'       => Auth::id(),
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Registrar acceso a un módulo con Job
     */
    public static function logAccess($moduleName) {
        LogActivityJob::dispatch([
            'action'        => 'access',
            'module'        => $moduleName,
            'user_id'       => Auth::id(),
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
