@extends('layouts.skelenton')
@section('title', 'Bitácora del Día')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-history mr-2"></i>Bitácora del Sistema
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active">Bitácora del Día</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <!-- Filtros rápidos -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-info active">
                            <input type="radio" name="filter" id="filter_all" checked> Todas
                        </label>
                        <label class="btn btn-outline-info">
                            <input type="radio" name="filter" id="filter_access">
                            <i class="fas fa-sign-in-alt mr-1"></i> Accesos
                        </label>
                        <label class="btn btn-outline-info">
                            <input type="radio" name="filter" id="filter_create">
                            <i class="fas fa-plus mr-1"></i> Creaciones
                        </label>
                        <label class="btn btn-outline-info">
                            <input type="radio" name="filter" id="filter_update">
                            <i class="fas fa-edit mr-1"></i> Actualizaciones
                        </label>
                        <label class="btn btn-outline-info">
                            <input type="radio" name="filter" id="filter_delete">
                            <i class="fas fa-trash mr-1"></i> Eliminaciones
                        </label>
                    </div>
                    <button class="btn btn-default float-right" id="refreshBtn">
                        <i class="fas fa-redo"></i> Actualizar
                    </button>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-info">
                        <div class="inner">
                            <h3>{{ $activities->flatten()->count() }}</h3>
                            <p>Total Actividades Hoy</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-success">
                        <div class="inner">
                            @php
                                $uniqueUsers = $activities->flatten()->pluck('user_id')->unique()->count();
                            @endphp
                            <h3>{{ $uniqueUsers }}</h3>
                            <p>Usuarios Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-warning">
                        <div class="inner">
                            @php
                                $modules = $activities->flatten()->pluck('module')->unique()->count();
                            @endphp
                            <h3>{{ $modules }}</h3>
                            <p>Módulos Afectados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-gradient-danger">
                        <div class="inner">
                            <h3>{{ $activities->count() }}</h3>
                            <p>Grupos de Actividad</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline de actividades -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-calendar-alt mr-1"></i> Actividades del Día - {{ now()->format('d/m/Y') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($activities->count() > 0)
                                <div class="timeline">
                                    @foreach($activities as $key => $group)
                                        @php
                                            [$time, $action] = explode('|', $key);
                                            $firstActivity = $group->first();
                                        @endphp

                                        <!-- timeline item -->
                                        <div class="timeline-item" data-action="{{ $firstActivity->action }}">
                                            <!-- timeline icon -->
                                            <div class="timeline-icon">
                                                @if($firstActivity->action === 'delete')
                                                    <i class="fas fa-trash bg-danger"></i>
                                                @elseif($firstActivity->action === 'create')
                                                    <i class="fas fa-plus bg-success"></i>
                                                @elseif($firstActivity->action === 'update')
                                                    <i class="fas fa-edit bg-warning"></i>
                                                @else
                                                    <i class="fas fa-sign-in-alt bg-info"></i>
                                                @endif
                                            </div>

                                            <!-- timeline content -->
                                            <div class="timeline-content">
                                                <div class="timeline-header">
                                                    <span class="time">
                                                        <i class="far fa-clock mr-1"></i>
                                                        <strong>{{ $time }}</strong>
                                                    </span>
                                                    <h4 class="mb-0">
                                                        @if($firstActivity->action === 'delete')
                                                            <span class="badge badge-danger">
                                                                <i class="fas fa-trash mr-1"></i> Eliminación
                                                            </span>
                                                        @elseif($firstActivity->action === 'create')
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-plus mr-1"></i> Creación
                                                            </span>
                                                        @elseif($firstActivity->action === 'update')
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-edit mr-1"></i> Actualización
                                                            </span>
                                                        @else
                                                            <span class="badge badge-info">
                                                                <i class="fas fa-sign-in-alt mr-1"></i> Acceso
                                                            </span>
                                                        @endif
                                                        <small class="text-muted ml-2">{{ $group->count() }} actividad(es)</small>
                                                    </h4>
                                                </div>

                                                <div class="timeline-body">
                                                    @foreach($group as $activity)
                                                        <div class="activity-item mb-3 p-3 border rounded"
                                                                data-user="{{ strtolower(str_replace(' ', '-', $activity->user->name ?? 'sistema')) }}"
                                                                data-module="{{ strtolower($activity->module) }}">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <div class="user-avatar mr-2">
                                                                            @if($activity->user)
                                                                                <i class="fas fa-user-circle fa-lg text-primary"></i>
                                                                            @else
                                                                                <i class="fas fa-server fa-lg text-secondary"></i>
                                                                            @endif
                                                                        </div>
                                                                        <div>
                                                                            <h5 class="mb-0">
                                                                                <strong class="{{ $activity->user ? 'text-primary' : 'text-secondary' }}">
                                                                                    {{ $activity->user->name ?? 'Sistema' }}
                                                                                </strong>
                                                                            </h5>
                                                                            @if($activity->user)
                                                                                <small class="text-muted">{{ $activity->user->email ?? '' }}</small>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="activity-details">
                                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                                            <span class="badge badge-light">
                                                                                <i class="fas fa-cube mr-1"></i> {{ $activity->module }}
                                                                            </span>

                                                                            @if($activity->record_id)
                                                                                <span class="badge badge-light">
                                                                                    <i class="fas fa-hashtag mr-1"></i> ID: {{ $activity->record_id }}
                                                                                </span>
                                                                            @endif

                                                                            @if($activity->ip_address)
                                                                                <span class="badge badge-light">
                                                                                    <i class="fas fa-globe mr-1"></i> {{ $activity->ip_address }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="ml-3">
                                                                    <a href="{{ route('activity-logs.detail', $activity->id) }}"
                                                                        class="btn btn-sm btn-outline-info"
                                                                        data-toggle="tooltip"
                                                                        title="Ver detalles completos">
                                                                        <i class="fas fa-search"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END timeline item -->
                                    @endforeach

                                    <!-- timeline end label -->
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-flag-checkered bg-secondary"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <h4 class="mb-0 text-muted">
                                                    <i class="fas fa-check-circle mr-1"></i> Inicio del día
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                        <h4 class="text-muted">No hay actividades registradas hoy</h4>
                                        <p class="text-muted">Aún no se han registrado actividades en el sistema para el día de hoy.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($activities->count() > 0)
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="legend">
                                            <span class="mr-3"><i class="fas fa-circle text-info mr-1"></i> Accesos</span>
                                            <span class="mr-3"><i class="fas fa-circle text-success mr-1"></i> Creaciones</span>
                                            <span class="mr-3"><i class="fas fa-circle text-warning mr-1"></i> Actualizaciones</span>
                                            <span><i class="fas fa-circle text-danger mr-1"></i> Eliminaciones</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small class="text-muted">
                                            Última actualización: {{ now()->format('H:i:s') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .timeline {
        position: relative;
        padding: 0;
        margin: 0;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 31px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #dee2e6 0%, #adb5bd 50%, #dee2e6 100%);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: 20px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        box-shadow: 0 0 0 3px #fff;
    }

    .timeline-icon i {
        font-size: 12px;
        color: white;
    }

    .timeline-content {
        margin-left: 60px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
    }

    .timeline-header {
        padding: 1rem 1.25rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        position: relative;
    }

    .timeline-header .time {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
    }

    .timeline-body {
        padding: 1.25rem;
    }

    .activity-item {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        border-left: 4px solid #007bff !important;
    }

    .activity-item:hover {
        background-color: #e9ecef;
        transform: translateX(5px);
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    }

    .activity-item[data-action="delete"] {
        border-left-color: #dc3545 !important;
    }

    .activity-item[data-action="create"] {
        border-left-color: #28a745 !important;
    }

    .activity-item[data-action="update"] {
        border-left-color: #ffc107 !important;
    }

    .activity-item[data-action="access"] {
        border-left-color: #17a2b8 !important;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .activity-details .badge-light {
        background-color: rgba(0, 0, 0, 0.05);
        color: #495057;
    }

    .empty-state {
        max-width: 400px;
        margin: 0 auto;
    }

    .legend {
        font-size: 0.875rem;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .timeline:before {
            left: 20px;
        }

        .timeline-icon {
            left: 10px;
        }

        .timeline-content {
            margin-left: 45px;
        }

        .timeline-header .time {
            position: static;
            transform: none;
            display: block;
            margin-top: 0.5rem;
        }

        .timeline-header h4 {
            font-size: 1.1rem;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Filtros por tipo de acción
        $('input[name="filter"]').change(function() {
            var filter = $(this).attr('id');

            $('.timeline-item').show();

            if (filter === 'filter_access') {
                $('.timeline-item').not('[data-action="access"]').hide();
            } else if (filter === 'filter_create') {
                $('.timeline-item').not('[data-action="create"]').hide();
            } else if (filter === 'filter_update') {
                $('.timeline-item').not('[data-action="update"]').hide();
            } else if (filter === 'filter_delete') {
                $('.timeline-item').not('[data-action="delete"]').hide();
            }
        });

        // Botón de refrescar
        $('#refreshBtn').click(function() {
            $(this).prop('disabled', true);
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');

            setTimeout(function() {
                location.reload();
            }, 500);
        });

        // Búsqueda en tiempo real (opcional)
        $('#searchInput').on('keyup', function() {
            var search = $(this).val().toLowerCase();

            $('.activity-item').each(function() {
                var user = $(this).data('user');
                var module = $(this).data('module');
                var text = $(this).text().toLowerCase();

                if (user.includes(search) || module.includes(search) || text.includes(search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Scroll suave a los elementos
        $('.activity-item').click(function(e) {
            if (!$(e.target).is('a, button, .btn')) {
                $(this).toggleClass('shadow-lg');
            }
        });

        // Mostrar hora actualizada cada minuto
        function updateTime() {
            var now = new Date();
            var timeString = now.getHours().toString().padStart(2, '0') + ':' +
                            now.getMinutes().toString().padStart(2, '0') + ':' +
                            now.getSeconds().toString().padStart(2, '0');
            $('.last-updated').text('Última actualización: ' + timeString);
        }

        setInterval(updateTime, 60000);

        // Inicializar
        updateTime();
    });
</script>
@endsection
