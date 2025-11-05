@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Asignar Roles de Usuario')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestión de Permisos de Usuario</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Seguridad</li>
                        <li class="breadcrumb-item"><a href="{{ route('security.users.home') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active">Permisos de {{ $user->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Asignar permisos a: <b>{{ $user->name }}</b></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="assignPermissionsForm" method="post">
                                @csrf
                                <div class="row">
                                    <!-- Columna de permisos disponibles -->
                                    <div class="col-md-5">
                                        <div class="card">
                                            <div class="card-header bg-info">
                                                <h3 class="card-title">Permisos Disponibles
                                                    <span class="badge badge-light counter-badge" id="availableCount">0</span>
                                                </h3>
                                                <div class="card-tools">
                                                    <small class="text-light">Haz clic en una fila para seleccionarla</small>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="moduleSelect">Filtrar por Módulo</label>
                                                    <select class="form-control select2" id="submoduleSelect" name="submoduleSelect" style="width: 100%;">
                                                        <option value="todos">Todos los módulos</option>
                                                        @foreach($submodulesPermissions as $su)
                                                            <option value="{{ $su->id }}">{{ $su->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                                                    <table class="table table-striped table-hover table-sm table-fixed">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th width="5%" class="sticky-top">#</th>
                                                                <th width="70%" class="sticky-top">Nombre</th>
                                                                <th width="25%" class="sticky-top">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="availablePermissions">
                                                            <!-- Los permisos se cargarán aquí via AJAX -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-mouse-pointer"></i> 
                                                        Selecciona filas y usa los botones para transferir permisos
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Columna de botones de acción -->
                                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                                        <div class="transfer-buttons text-center">
                                            <button type="button" id="addAllPermissions" class="btn btn-info mb-2" title="Agregar todos los permisos" style="display: none;">
                                                <i class="fas fa-angle-double-right"></i> Todos
                                            </button>
                                            <br>
                                            <button type="button" id="addSelectedPermissions" class="btn btn-success mb-2" title="Agregar permisos seleccionados">
                                                <i class="fas fa-arrow-right"></i> Agregar
                                            </button>
                                            <br>
                                            <button type="button" id="removeSelectedPermissions" class="btn btn-warning mb-2" title="Quitar permisos seleccionados">
                                                <i class="fas fa-arrow-left"></i> Quitar
                                            </button>
                                            <br>
                                            <button type="button" id="removeAllPermissions" class="btn btn-danger" title="Quitar todos los permisos" style="display: none;">
                                                <i class="fas fa-angle-double-left"></i> Ninguno
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Columna de permisos asignados -->
                                    <div class="col-md-5">
                                        <div class="card">
                                            <div class="card-header bg-success">
                                                <h3 class="card-title">Permisos Asignados Directamente
                                                    <span class="badge badge-light counter-badge" id="assignedCount">{{ $directPermissions->count() }}</span>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                                                    <table class="table table-striped table-hover table-sm table-fixed">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th width="5%" class="sticky-top">#</th>
                                                                <th width="70%" class="sticky-top">Nombre</th>
                                                                <th width="25%" class="sticky-top">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="assignedPermissions">
                                                            @forelse($directPermissions as $index => $permission)
                                                                <tr data-id="{{ $permission->id }}">
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $permission->name }}</td>
                                                                    <td class="action-buttons">
                                                                        <button type="button" class="btn btn-sm btn-danger remove-permission btn-xs" data-id="{{ $permission->id }}" title="Quitar permiso individual">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="text-center">No hay permisos asignados directamente</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="user_id" id="userId" value="{{ $user->id }}">
                                <input type="hidden" name="permissions" id="selectedPermissions" value="{{ $directPermissions->pluck('id')->implode(',') }}">
                                
                                <div class="form-group mt-3 text-center">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                                    <a href="{{ route('security.users.home') }}" class="btn btn-danger"><i class="fas fa-times"></i> Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Sección de permisos por rol -->
                    <div class="card card-warning mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Permisos heredados del rol: <b>{{ $user->getRoleNames()->implode(', ') }}</b></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="icon fas fa-info"></i>
                                Estos permisos son heredados de los roles asignados al usuario y no pueden ser modificados individualmente.
                            </div>
                            <div class="table-container" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped table-sm table-fixed">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%" class="sticky-top">#</th>
                                            <th width="30%" class="sticky-top">Nombre del Permiso</th>
                                            <th width="65%" class="sticky-top">Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rolePermissions as $index => $permission)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->descripcion ?? 'Sin descripción' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">El rol no tiene permisos asignados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                <!-- El mensaje se insertará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Aceptar</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-container {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    
    .table-fixed {
        width: 100%;
        margin-bottom: 0;
    }
    
    .table-fixed thead th.sticky-top {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Estilos para selección de filas */
    .table tbody tr {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }
    
    .table tbody tr.selected {
        background-color: #007bff !important;
        color: white;
    }
    
    .table tbody tr.selected:hover {
        background-color: #0056b3 !important;
    }
    
    .table tbody tr.selected .btn {
        color: white;
        border-color: white;
    }
    
    .table tbody tr.selected .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }
    
    /* Estilo para las tablas vacías */
    .table tbody tr td.text-center {
        font-style: italic;
        color: #6c757d;
        cursor: default;
    }
    
    /* Mejorar la visualización de los botones de acción */
    .btn-xs {
        padding: 0.15rem 0.4rem;
        font-size: 0.7rem;
        line-height: 1.2;
        border-radius: 0.2rem;
    }
    
    /* Indicador de selección múltiple */
    .selection-info {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 5px;
    }
</style>
@endpush

<script src="{{ asset('js/role.js') }}"></script>
@endsection