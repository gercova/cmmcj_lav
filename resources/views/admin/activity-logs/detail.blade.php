@extends('layouts.skelenton')
@section('title', 'Detalle de Actividad')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bitácora del sistema</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Bitácora del sistema</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="d-flex justify-content-between align-items-center">
                        <h2>
                            <i class="bi bi-info-circle-fill"></i> Detalle de Actividad
                        </h2>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Información General -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-clipboard-list"></i> Información de la Actividad
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-{{ $activity->action === 'delete' ? 'danger' : ($activity->action === 'create' ? 'success' : 'info') }}">
                                                    <i class="fas fa-{{ $activity->action === 'delete' ? 'trash' : ($activity->action === 'create' ? 'plus' : 'edit') }}"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Acción</span>
                                                    <span class="info-box-number">
                                                        {{ $activity->formatted_action }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-secondary">
                                                    <i class="fas fa-cube"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Módulo</span>
                                                    <span class="info-box-number">{{ $activity->module }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 30%">ID del Registro:</th>
                                                        <td>
                                                            @if($activity->record_id)
                                                                <span class="badge bg-primary">#{{ $activity->record_id }}</span>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Fecha y Hora:</th>
                                                        <td>
                                                            <i class="far fa-calendar"></i>
                                                            {{ $activity->created_at->format('d/m/Y') }}
                                                            <i class="far fa-clock ml-2"></i>
                                                            {{ $activity->created_at->format('H:i:s') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Usuario:</th>
                                                        <td>
                                                            @if($activity->user)
                                                                <div class="d-flex align-items-center">
                                                                    <div class="mr-2">
                                                                        <i class="fas fa-user-circle"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $activity->user->name }}</strong>
                                                                        <div class="text-muted small">{{ $activity->user->email }}</div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">Sistema</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Dirección IP:</th>
                                                        <td>
                                                            <code>{{ $activity->ip_address }}</code>
                                                            @if($activity->ip_address)
                                                                <button class="btn btn-xs btn-info ml-2" onclick="copyToClipboard('{{ $activity->ip_address }}')">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>User Agent:</th>
                                                        <td>
                                                            <small class="text-muted">{{ $activity->user_agent }}</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cambios Realizados -->
                            @if($activity->action === 'update' && $activity->new_data)
                                <div class="card card-warning card-outline mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-exchange-alt"></i> Cambios Realizados
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Campo</th>
                                                        <th>Valor Anterior</th>
                                                        <th>Valor Nuevo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($activity->new_data as $field => $change)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong>
                                                            </td>
                                                            <td>
                                                                <div class="old-value" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                                    @if(is_array($change['old']))
                                                                        <pre class="mb-0 small">{{ json_encode($change['old'], JSON_PRETTY_PRINT) }}</pre>
                                                                    @else
                                                                        <span class="badge bg-secondary">{{ $change['old'] ?? 'NULL' }}</span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="new-value" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                                    @if(is_array($change['new']))
                                                                        <pre class="mb-0 small">{{ json_encode($change['new'], JSON_PRETTY_PRINT) }}</pre>
                                                                    @else
                                                                        <span class="badge bg-success">{{ $change['new'] ?? 'NULL' }}</span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Datos Originales (Solo para update y delete) -->
                            @if(in_array($activity->action, ['update', 'delete']) && $activity->old_data)
                                <div class="card card-danger card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-database"></i> Datos Originales
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="json-viewer" id="oldDataViewer">
                                            <pre>{{ json_encode($activity->old_data, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="toggleJsonView('oldDataViewer')">
                                            <i class="fas fa-expand-alt"></i> Expandir/Contraer
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Datos Nuevos (Solo para create) -->
                            @if($activity->action === 'create' && $activity->new_data)
                                <div class="card card-success card-outline mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-plus-circle"></i> Datos Creados
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="json-viewer" id="newDataViewer">
                                            <pre>{{ json_encode($activity->new_data, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="toggleJsonView('newDataViewer')">
                                            <i class="fas fa-expand-alt"></i> Expandir/Contraer
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Acciones Rápidas -->
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-bolt"></i> Acciones Rápidas
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($activity->user)
                                            <a href="{{ route('activity-logs.user', $activity->user_id) }}" class="btn btn-outline-primary mb-2">
                                                <i class="fas fa-user-clock"></i> Ver todas las actividades de {{ $activity->user->name }}
                                            </a>
                                        @endif

                                        <a href="{{ route('activity-logs.today') }}" class="btn btn-outline-info mb-2">
                                            <i class="fas fa-calendar-day"></i> Ver actividades de hoy
                                        </a>

                                        <button class="btn btn-outline-success mb-2" onclick="shareActivity()">
                                            <i class="fas fa-share-alt"></i> Compartir enlace
                                        </button>

                                        @if($activity->record_id && $activity->module !== 'Sistema')
                                            <button class="btn btn-outline-warning mb-2" onclick="viewRecord()">
                                                <i class="fas fa-external-link-alt"></i> Ver registro afectado
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Información Técnica -->
                            <div class="card card-dark mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-code"></i> Información Técnica
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong>ID Log:</strong>
                                            <code>{{ $activity->id }}</code>
                                        </li>
                                        <li class="mb-2">
                                            <strong>UUID:</strong>
                                            <small>{{ \Str::uuid() }}</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Creado:</strong>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </li>
                                        <li>
                                            <strong>Actualizado:</strong>
                                            {{ $activity->updated_at->diffForHumans() }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        margin-bottom: 1rem;
        min-height: 80px;
    }

    .info-box-icon {
        border-radius: .25rem 0 0 .25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
    }

    .info-box-content {
        padding: 10px;
        flex: 1;
    }

    .json-viewer {
        background: #2d3748;
        color: #e2e8f0;
        padding: 15px;
        border-radius: 5px;
        max-height: 300px;
        overflow-y: auto;
        font-size: 12px;
    }

    .json-viewer pre {
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .json-viewer.collapsed {
        max-height: 150px;
    }

    .json-viewer.expanded {
        max-height: 600px;
    }

    .old-value .badge {
        background-color: #dc3545 !important;
        text-decoration: line-through;
    }

    .new-value .badge {
        background-color: #28a745 !important;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .card-outline {
        border-top: 3px solid;
    }

    .card-primary.card-outline {
        border-top-color: #007bff;
    }

    .card-warning.card-outline {
        border-top-color: #ffc107;
    }

    .card-danger.card-outline {
        border-top-color: #dc3545;
    }

    .card-success.card-outline {
        border-top-color: #28a745;
    }

    .card-secondary.card-outline {
        border-top-color: #6c757d;
    }

    .card-dark.card-outline {
        border-top-color: #343a40;
    }
</style>

<script>
    // Copiar al portapapeles
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            toastr.success('Copiado al portapapeles');
        }).catch(err => {
            console.error('Error al copiar: ', err);
            toastr.error('Error al copiar');
        });
    }

    // Expandir/Contraer JSON
    function toggleJsonView(elementId) {
        const element = document.getElementById(elementId);
        if (element.classList.contains('collapsed')) {
            element.classList.remove('collapsed');
            element.classList.add('expanded');
        } else {
            element.classList.remove('expanded');
            element.classList.add('collapsed');
        }
    }

    // Compartir actividad
    function shareActivity() {
        if (navigator.share) {
            navigator.share({
                title: 'Actividad del Sistema',
                text: 'Revisa esta actividad del sistema',
                url: window.location.href
            })
            .then(() => console.log('Compartido exitosamente'))
            .catch((error) => console.log('Error al compartir', error));
        } else {
            copyToClipboard(window.location.href);
            toastr.info('Enlace copiado al portapapeles');
        }
    }

    // Ver registro afectado
    function viewRecord() {
        const module = '{{ strtolower($activity->module) }}';
        const recordId = '{{ $activity->record_id }}';

        // Mapeo de módulos a rutas (ajusta según tus rutas)
        const routeMap = {
            'user': '/admin/users/',
            'users': '/admin/users/',
            'product': '/admin/products/',
            'products': '/admin/products/',
            'client': '/admin/clients/',
            'clients': '/admin/clients/',
            // Agrega más mapeos según tus necesidades
        };

        let baseRoute = '/admin/';
        for (const [key, route] of Object.entries(routeMap)) {
            if (module.includes(key)) {
                baseRoute = route;
                break;
            }
        }

        const url = baseRoute + recordId;

        // Verificar si existe la ruta
        fetch(url, { method: 'HEAD' })
            .then(response => {
                if (response.ok) {
                    window.location.href = url;
                } else {
                    toastr.warning('El registro ya no existe o no se puede acceder');
                }
            })
            .catch(() => {
                toastr.error('Error al verificar el registro');
            });
    }

    // Inicializar JSON viewers
    document.addEventListener('DOMContentLoaded', function() {
        // Añadir clases iniciales
        const jsonViewers = document.querySelectorAll('.json-viewer');
        jsonViewers.forEach(viewer => {
            viewer.classList.add('collapsed');
        });

        // Inicializar tooltips de Bootstrap
        $('[data-toggle="tooltip"]').tooltip();

        // Mensaje de carga
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    });
</script>

@if(session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error('{{ session('error') }}');
    </script>
@endif
@endsection
