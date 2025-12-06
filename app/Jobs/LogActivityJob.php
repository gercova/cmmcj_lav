<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;
    public $maxExceptions = 3;

    protected $data;

    public function __construct(array $data) {
        $this->data = $data;
        // Asignar a una cola específica si quieres
        $this->onQueue('logs');
    }

    public function handle() {
        try {
            ActivityLog::create($this->data);
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad: ' . $e->getMessage());

            // Intentar nuevamente sin datos JSON si falla
            if (isset($this->data['old_data'])) {
                $this->data['old_data'] = null;
            }
            if (isset($this->data['new_data'])) {
                $this->data['new_data'] = null;
            }

            ActivityLog::create($this->data);
        }
    }

    public function failed(\Throwable $exception) {
        Log::error('Job LogActivityJob falló: ' . $exception->getMessage());
    }
}
