@extends('layouts.skelenton')
@section('title', 'Actividades de Usuario')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-user-clock mr-2"></i>Actividades de {{ $user->name }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a> Home</li>
                        <li class="breadcrumb-item"><a href="{{ route('activity-logs.today') }}">Bitácora</a></li>
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Columna izquierda: Perfil y Filtros -->
                <div class="col-lg-3 col-md-4">
                    <!-- Perfil del Usuario -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="{{ auth()->user()->profile_photo_url }}" alt="{{ $user->name }}">
                            </div>

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>
                            <p class="text-muted text-center">{{ $user->email }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Total Actividades</b>
                                    <a class="float-right text-primary">{{ $activities->total() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Última Actividad</b>
                                    <a class="float-right">
                                        @if($activities->count() > 0)
                                            <span class="badge badge-info">
                                                {{ $activities->first()->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Registrado</b>
                                    <a class="float-right">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </a>
                                </li>
                            </ul>

                            <a href="{{ route('activity-logs.today') }}"
                            class="btn btn-primary btn-block">
                                <i class="fas fa-calendar-day mr-1"></i> Ver Actividades de Hoy
                            </a>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i> Filtros
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('activity-logs.user', $user->id) }}" method="GET">
                                <div class="form-group">
                                    <label>Acción:</label>
                                    <select name="action" class="form-control select2" style="width: 100%;">
                                        <option value="">Todas las acciones</option>
                                        <option value="access" {{ request('action') == 'access' ? 'selected' : '' }}>
                                            <i class="fas fa-sign-in-alt text-info mr-1"></i> Acceso
                                        </option>
                                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>
                                            <i class="fas fa-plus text-success mr-1"></i> Creación
                                        </option>
                                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>
                                            <i class="fas fa-edit text-warning mr-1"></i> Actualización
                                        </option>
                                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>
                                            <i class="fas fa-trash text-danger mr-1"></i> Eliminación
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Módulo:</label>
                                    <input type="text" name="module"
                                        class="form-control"
                                        value="{{ request('module') }}"
                                        placeholder="Ej: Usuarios, Productos...">
                                </div>

                                <div class="form-group">
                                    <label>Fecha:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="date" name="date"
                                            class="form-control"
                                            value="{{ request('date') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-info btn-block">
                                            <i class="fas fa-search mr-1"></i> Filtrar
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('activity-logs.user', $user->id) }}"
                                        class="btn btn-default btn-block">
                                            <i class="fas fa-undo mr-1"></i> Limpiar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Actividades -->
                <div class="col-lg-9 col-md-8">
                    <!-- Estadísticas -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $activities->where('action', 'access')->count() }}</h3>
                                    <p>Accesos</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <a href="{{ route('activity-logs.user', $user->id) }}?action=access" class="small-box-footer">
                                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $activities->where('action', 'create')->count() }}</h3>
                                    <p>Creaciones</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <a href="{{ route('activity-logs.user', $user->id) }}?action=create" class="small-box-footer">
                                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $activities->where('action', 'update')->count() }}</h3>
                                    <p>Actualizaciones</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <a href="{{ route('activity-logs.user', $user->id) }}?action=update" class="small-box-footer">
                                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $activities->where('action', 'delete')->count() }}</h3>
                                    <p>Eliminaciones</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-trash"></i>
                                </div>
                                <a href="{{ route('activity-logs.user', $user->id) }}?action=delete" class="small-box-footer">
                                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Actividades -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-1"></i> Historial de Actividades
                            </h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            @if($activities->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 10%">Acción</th>
                                                <th style="width: 20%">Módulo</th>
                                                <th style="width: 35%">Detalles</th>
                                                <th style="width: 20%">Fecha/Hora</th>
                                                <th style="width: 15%" class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activities as $activity)
                                                <tr>
                                                    <td>
                                                        @if($activity->action == 'access')
                                                            <span class="badge badge-info">
                                                                <i class="fas fa-sign-in-alt mr-1"></i> Acceso
                                                            </span>
                                                        @elseif($activity->action == 'create')
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-plus mr-1"></i> Creación
                                                            </span>
                                                        @elseif($activity->action == 'update')
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-edit mr-1"></i> Actualización
                                                            </span>
                                                        @elseif($activity->action == 'delete')
                                                            <span class="badge badge-danger">
                                                                <i class="fas fa-trash mr-1"></i> Eliminación
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-cube mr-2 text-muted"></i>
                                                            <strong>{{ $activity->module }}</strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            @if($activity->record_id)
                                                                <span class="text-sm">
                                                                    <i class="fas fa-hashtag mr-1 text-muted"></i>
                                                                    ID: {{ $activity->record_id }}
                                                                </span>
                                                            @endif
                                                            @if($activity->ip_address)
                                                                <span class="text-sm">
                                                                    <i class="fas fa-globe mr-1 text-muted"></i>
                                                                    {{ $activity->ip_address }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-sm font-weight-bold">
                                                                {{ $activity->created_at->format('d/m/Y') }}
                                                            </span>
                                                            <span class="text-xs text-muted">
                                                                <i class="far fa-clock mr-1"></i>
                                                                {{ $activity->created_at->format('H:i:s') }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <a href="{{ route('activity-logs.detail', $activity->id) }}"
                                                            class="btn btn-sm btn-info"
                                                            data-toggle="tooltip"
                                                            title="Ver detalles">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($activity->record_id && $activity->module != 'Sistema')
                                                                <a href="#"
                                                                class="btn btn-sm btn-secondary"
                                                                data-toggle="tooltip"
                                                                title="Ver registro">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                                        <h4 class="text-muted">No hay actividades registradas</h4>
                                        <p class="text-muted mb-4">
                                            Este usuario aún no ha realizado ninguna acción en el sistema.
                                        </p>
                                        <a href="{{ route('activity-logs.user', $user->id) }}"
                                        class="btn btn-primary">
                                            <i class="fas fa-redo mr-1"></i> Refrescar
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($activities->hasPages())
                            <div class="card-footer clearfix">
                                <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" role="status" aria-live="polite">
                                            Mostrando {{ $activities->firstItem() }} a {{ $activities->lastItem() }}
                                            de {{ $activities->total() }} registros
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            {{ $activities->links('pagination::bootstrap-4') }}
                                        </div>
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
    .profile-user-img {
        border: 3px solid #adb5bd;
        margin: 0 auto;
        padding: 3px;
        width: 100px;
        height: 100px;
        object-fit: cover;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .empty-state {
        padding: 40px 0;
    }

    .empty-state i {
        opacity: 0.5;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.8em;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .card-outline {
        border-top: 3px solid;
    }

    .card-outline.card-primary {
        border-top-color: #007bff;
    }

    .card-outline.card-info {
        border-top-color: #17a2b8;
    }

    .thead-light th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }

    .btn-group .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
</style>

<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccionar acción',
            allowClear: true
        });

        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

        // Búsqueda en la tabla
        $('input[name="table_search"]').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Formatear fecha en inputs de tipo date
        $('input[type="date"]').each(function() {
            if ($(this).val()) {
                var date = new Date($(this).val());
                $(this).val(date.toISOString().split('T')[0]);
            }
        });

        // Mostrar mensaje si no hay actividades
        @if($activities->count() == 0)
            toastr.info('No se encontraron actividades para este usuario con los filtros aplicados.');
        @endif
    });

    // Función para ver registro (debes implementar según tus rutas)
    function viewRecord(module, recordId) {
        // Aquí puedes implementar la lógica para redirigir al registro
        console.log('Ver registro:', module, recordId);

        // Ejemplo:
        // const routeMap = {
        //     'User': '/admin/users/',
        //     'Product': '/admin/products/'
        // };
        //
        // if (routeMap[module]) {
        //     window.location.href = routeMap[module] + recordId;
        // } else {
        //     toastr.warning('No se puede acceder a este registro');
        // }
    }
</script>
@endsection
