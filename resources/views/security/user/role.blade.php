@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Asignar Roles de Usuario')
@section('content')
<style>
    .badge-heredado {
        background-color: #ffc107;
        color: #212529;
        font-size: 0.75em;
        margin-left: 5px;
    }
    .permission-table {
        max-height: 500px;
        overflow-y: auto;
    }
    .table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
    }
    .highlight {
        background-color: #e3f2fd;
        transition: background-color 0.3s;
    }
    .transfer-buttons {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        gap: 10px;
    }
    .search-box {
        margin-bottom: 10px;
    }
    .counter-badge {
        font-size: 0.8rem;
        margin-left: 5px;
    }
    .action-buttons {
        white-space: nowrap;
    }
</style>
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
                                                    <!--<span class="badge badge-light counter-badge" id="availableCount">{{ $availablePermissions->count() }}</span>-->
                                                    <span class="badge badge-light counter-badge" id="availableCount"></span>
                                                </h3>
                                                <div class="card-tools">
                                                    <div class="input-group input-group-sm search-box">
                                                        <!--<input type="text" id="availableSearch" class="form-control" placeholder="Buscar...">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default" id="clearAvailableSearch">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-6">
                                                <label for="moduleSelect">Módulos</label>
                                                <select class="slim-select" id="moduleSelect" name="moduleSelect">
                                                    <option value="todos">todos</option>
                                                    @foreach($modulesPermissions as $module)
                                                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="card-body permission-table" id="loading">
                                                <table id="availablePermissions" class="table table-striped table-hover table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="70%">Nombre</th>
                                                            <th width="25%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <!--<tbody>
                                                        @forelse($availablePermissions as $index => $permission)
                                                            <tr data-id="{{ $permission->id }}">
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $permission->name }}</td>
                                                                <td class="action-buttons">
                                                                    <button type="button" class="btn btn-sm btn-primary add-permission btn-xs" data-id="{{ $permission->id }}">
                                                                        <i class="fas fa-plus"></i> Agregar
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center">No hay permisos disponibles</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>-->
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Columna de botones de acción -->
                                    <div class="col-md-2">
                                        <div class="transfer-buttons">
                                            <button type="button" id="addAllPermissions" class="btn btn-info mb-2" title="Agregar todos los permisos">
                                                <i class="fas fa-angle-double-right"></i> Todos
                                            </button>
                                            <button type="button" id="addSelectedPermissions" class="btn btn-info mb-2" title="Agregar permisos seleccionados" style="display: none;">
                                                <i class="fas fa-arrow-right"></i> Seleccionados
                                            </button>
                                            <button type="button" id="removeSelectedPermissions" class="btn btn-info mt-2" title="Quitar permisos seleccionados" style="display: none;">
                                                <i class="fas fa-arrow-left"></i> Seleccionados
                                            </button>
                                            <button type="button" id="removeAllPermissions" class="btn btn-info mt-2" title="Quitar todos los permisos">
                                                <i class="fas fa-angle-double-left"></i> Ninguno
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Columna de permisos asignados -->
                                    <div class="col-md-5">
                                        <div class="card">
                                            <div class="card-header bg-success">
                                                <h3 class="card-title">Permisos Asignados 
                                                    <span class="badge badge-light counter-badge" id="assignedCount">{{ $directPermissions->count() }}</span>
                                                </h3>
                                                <div class="card-tools">
                                                    <div class="input-group input-group-sm search-box">
                                                        <input type="text" id="assignedSearch" class="form-control" placeholder="Buscar...">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default" id="clearAssignedSearch">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body permission-table">
                                                <table id="assignedPermissions" class="table table-striped table-hover table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="70%">Nombre</th>
                                                            <th width="25%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($directPermissions as $index => $permission)
                                                            <tr data-id="{{ $permission->id }}">
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $permission->name }}</td>
                                                                <td class="action-buttons">
                                                                    <button type="button" class="btn btn-sm btn-danger remove-permission btn-xs" data-id="{{ $permission->id }}">
                                                                        <i class="fas fa-minus"></i> Quitar
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
                                <input type="hidden" name="user_id" id="userId" value="{{ $user->id }}">
                                <!-- Input oculto para los permisos seleccionados -->
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
                            <table id="permissionsRoleTable" class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="60%">Nombre del Permiso</th>
                                        <th width="35%">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rolePermissions as $index => $permission)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td>{{ $permission->description ?? 'Sin descripción' }}</td>
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
    </section>
</div>
<script src="{{ asset('js/role.js') }}"></script>
@endsection