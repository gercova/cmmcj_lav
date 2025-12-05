@php
    use App\Models\AuditLog;

    // Obtener logs del día actual agrupados por hora
    $logs = AuditLog::getTodayLogs();
    $groupedLogs = $logs->groupBy(function($log) {
        return $log->created_at->format('H:00'); // Agrupar por hora
    });
@endphp

<aside class="control-sidebar control-sidebar-dark">
    <div class="p-3 control-sidebar-content">
        <h5 class="text-center mb-3">
            <i class="fas fa-history mr-2"></i>Bitácora del Día
            <small class="d-block text-muted">{{ now()->format('d/m/Y') }}</small>
        </h5>

        <div class="timeline" id="auditTimeline">
            @forelse($groupedLogs as $hour => $hourLogs)
                <div class="time-label mb-2">
                    <span class="bg-purple">{{ $hour }}</span>
                </div>

                @foreach($hourLogs as $log)
                    <div class="audit-item">
                        <i class="fas fa-{{ $log->action === 'login' ? 'sign-in-alt' :
                            ($log->action === 'logout' ? 'sign-out-alt' :
                            ($log->action === 'created' ? 'plus-circle' :
                            ($log->action === 'deleted' ? 'trash-alt' : 'edit'))) }}
                            bg-{{ $log->action === 'login' ? 'success' :
                                ($log->action === 'logout' ? 'danger' :
                                ($log->action === 'created' ? 'primary' :
                                ($log->action === 'deleted' ? 'dark' : 'warning'))) }}"></i>

                        <div class="timeline-item">
                            <span class="time">
                                <i class="far fa-clock"></i> {{ $log->time }}
                            </span>

                            <div class="timeline-header">
                                <a href="#" class="audit-action" data-toggle="collapse"
                                   data-target="#details-{{ $log->id }}">
                                    {{ $log->formatted_action }}
                                </a>

                                @if($log->description)
                                    <small class="text-muted d-block">
                                        {{ Str::limit($log->description, 40) }}
                                    </small>
                                @endif
                            </div>

                            <div class="timeline-body collapse" id="details-{{ $log->id }}">
                                <div class="ml-4">
                                    <strong><i class="fas fa-user mr-1"></i> Usuario:</strong>
                                    <span class="text-primary">
                                        {{ $log->user->name ?? 'Sistema' }}
                                    </span>

                                    @if($log->model_type)
                                        <br>
                                        <strong><i class="fas fa-database mr-1"></i> Modelo:</strong>
                                        {{ class_basename($log->model_type) }}
                                        @if($log->model_id)
                                            <span class="badge badge-info">ID: {{ $log->model_id }}</span>
                                        @endif
                                    @endif

                                    @if($log->ip_address)
                                        <br>
                                        <strong><i class="fas fa-network-wired mr-1"></i> IP:</strong>
                                        <code>{{ $log->ip_address }}</code>
                                    @endif

                                    @if($log->old_values || $log->new_values)
                                        <br>
                                        <button class="btn btn-xs btn-outline-info mt-2" type="button" data-toggle="collapse" data-target="#changes-{{ $log->id }}">
                                            <i class="fas fa-exchange-alt"></i> Ver cambios
                                        </button>

                                        <div class="collapse mt-2" id="changes-{{ $log->id }}">
                                            <div class="card card-body p-2">
                                                @if($log->old_values)
                                                    <small class="text-danger">
                                                        <strong>Antes:</strong>
                                                        {{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}
                                                    </small>
                                                @endif

                                                @if($log->new_values)
                                                    <small class="text-success">
                                                        <strong>Después:</strong>
                                                        {{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                    <p>No hay registros de actividad hoy</p>
                </div>
            @endforelse

            <div>
                <i class="far fa-clock bg-gray"></i>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('audit.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fas fa-list mr-1"></i> Ver bitácora completa
            </a>
        </div>
    </div>
</aside>
<style>
    /* resources/css/audit-sidebar.css */
    .control-sidebar {
        overflow-y: auto;
    }

    .audit-item .timeline-item {
        background: #2c3b41;
        border-radius: 3px;
        padding: 10px;
        margin-bottom: 10px;
        border-left: 3px solid #007bff;
    }

    .audit-item .timeline-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 5px;
        margin-bottom: 5px;
    }

    .audit-item .timeline-header a.audit-action {
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }

    .audit-item .timeline-header a.audit-action:hover {
        color: #007bff;
    }

    .audit-item .timeline-body {
        font-size: 0.85rem;
        background: rgba(0,0,0,0.2);
        border-radius: 3px;
        padding: 8px;
        margin-top: 5px;
    }

    .time-label {
        text-align: center;
        padding: 5px;
        font-weight: 600;
    }

    .time-label > span {
        border-radius: 4px;
        padding: 3px 10px;
        font-size: 0.8rem;
    }
</style>
