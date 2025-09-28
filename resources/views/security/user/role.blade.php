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
                                                    <span class="badge badge-light counter-badge" id="availableCount">{{ $availablePermissions->count() }}</span>
                                                </h3>
                                                <div class="card-tools">
                                                    <div class="input-group input-group-sm search-box">
                                                        <input type="text" id="availableSearch" class="form-control" placeholder="Buscar...">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default" id="clearAvailableSearch">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body permission-table">
                                                <table id="availablePermissions" class="table table-striped table-hover table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="70%">Nombre</th>
                                                            <th width="25%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
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
                                                    </tbody>
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
<script>
// Módulo principal de gestión de permisos
const PermissionManager = (function() {
    // Configuración
    const config = {
        availableTable: '#availablePermissions',
        assignedTable: '#assignedPermissions',
        availableSearch: '#availableSearch',
        assignedSearch: '#assignedSearch',
        availableCount: '#availableCount',
        assignedCount: '#assignedCount',
        addAllBtn: '#addAllPermissions',
        removeAllBtn: '#removeAllPermissions',
        addSelectedBtn: '#addSelectedPermissions',
        removeSelectedBtn: '#removeSelectedPermissions',
        selectedPermissions: '#selectedPermissions',
        form: '#assignPermissionsForm'
    };
    // Estado de la aplicación
    let state = {
        selectedAvailable: [],
        selectedAssigned: []
    };
    // Inicializar el módulo
    function init() {
        bindEvents();
        setupSearch();
        updateCounters();
        setupRowSelection();
    }
    // Vincular eventos
    function bindEvents() {
        // Agregar permiso individual
        $(document).on('click', '.add-permission', function(e) {
            const permissionId = $(this).data('id');
            movePermission(permissionId, config.availableTable, config.assignedTable, 'add');
        });
        // Quitar permiso individual
        $(document).on('click', '.remove-permission', function(e) {
            const permissionId = $(this).data('id');
            movePermission(permissionId, config.assignedTable, config.availableTable, 'remove');
        });
        // Agregar todos los permisos
        $(config.addAllBtn).on('click', addAllPermissions);
        // Quitar todos los permisos
        $(config.removeAllBtn).on('click', removeAllPermissions);
        // Agregar seleccionados
        $(config.addSelectedBtn).on('click', addSelectedPermissions);
        // Quitar seleccionados
        $(config.removeSelectedBtn).on('click', removeSelectedPermissions);
        // Enviar formulario
        //$(config.form).on('submit', submitForm);
    }
    // Configurar búsqueda
    function setupSearch() {
        // Búsqueda en tabla de disponibles
        $(config.availableSearch).on('keyup', function() {
            searchTable(config.availableTable, $(this).val());
        });
        
        // Búsqueda en tabla de asignados
        $(config.assignedSearch).on('keyup', function() {
            searchTable(config.assignedTable, $(this).val());
        });
        
        // Limpiar búsqueda disponibles
        $('#clearAvailableSearch').on('click', function() {
            $(config.availableSearch).val('').trigger('keyup');
        });
        
        // Limpiar búsqueda asignados
        $('#clearAssignedSearch').on('click', function() {
            $(config.assignedSearch).val('').trigger('keyup');
        });
    }
    // Configurar selección de filas
    function setupRowSelection() {
        // Toggle selection on row click
        $(document).on('click', `${config.availableTable} tr, ${config.assignedTable} tr`, function(e) {
            if (!$(e.target).is('button')) {
                $(this).toggleClass('highlight');
                updateSelectionState();
            }
        });
    }
    // Actualizar estado de selección
    function updateSelectionState() {
        state.selectedAvailable = [];
        $(`${config.availableTable} tr.highlight`).each(function() {
            state.selectedAvailable.push($(this).data('id'));
        });
        
        state.selectedAssigned = [];
        $(`${config.assignedTable} tr.highlight`).each(function() {
            state.selectedAssigned.push($(this).data('id'));
        });
        
        // Mostrar/ocultar botones de selección
        toggleButton(config.addSelectedBtn, state.selectedAvailable.length > 0);
        toggleButton(config.removeSelectedBtn, state.selectedAssigned.length > 0);
    }
    // Mostrar/ocultar botón
    function toggleButton(buttonSelector, show) {
        if (show) {
            $(buttonSelector).show();
        } else {
            $(buttonSelector).hide();
        }
    }
    // Mover un permiso entre tablas
    function movePermission(permissionId, fromTable, toTable, action) {
        const row = $(`tr[data-id="${permissionId}"]`);
        
        if (row.length) {
            // Destacar la fila que se está moviendo
            row.addClass('highlight');
            
            // Cambiar el botón según la acción
            if (action === 'add') {
                row.find('.add-permission')
                    .removeClass('btn-primary add-permission')
                    .addClass('btn-danger remove-permission')
                    .html('<i class="fas fa-minus"></i> Quitar')
                    .data('id', permissionId);
            } else {
                row.find('.remove-permission')
                    .removeClass('btn-danger remove-permission')
                    .addClass('btn-primary add-permission')
                    .html('<i class="fas fa-plus"></i> Agregar')
                    .data('id', permissionId);
            }
            
            // Mover la fila
            row.appendTo(`${toTable} tbody`);
            
            // Reordenar y actualizar
            renumberTable(fromTable);
            renumberTable(toTable);
            updateHiddenInput();
            updateCounters();
            updateSelectionState();
            
            // Quitar el highlight después de un tiempo
            setTimeout(() => {
                row.removeClass('highlight');
            }, 1000);
        }
    }
    // Agregar todos los permisos
    function addAllPermissions() {
        $(`${config.availableTable} tbody tr`).each(function() {
            const permissionId = $(this).data('id');
            movePermission(permissionId, config.availableTable, config.assignedTable, 'add');
        });
    }
    // Quitar todos los permisos
    function removeAllPermissions() {
        $(`${config.assignedTable} tbody tr`).each(function() {
            const permissionId = $(this).data('id');
            movePermission(permissionId, config.assignedTable, config.availableTable, 'remove');
        });
    }
    // Agregar permisos seleccionados
    function addSelectedPermissions() {
        state.selectedAvailable.forEach(permissionId => {
            movePermission(permissionId, config.availableTable, config.assignedTable, 'add');
        });
        state.selectedAvailable = [];
    }
    // Quitar permisos seleccionados
    function removeSelectedPermissions() {
        state.selectedAssigned.forEach(permissionId => {
            movePermission(permissionId, config.assignedTable, config.availableTable, 'remove');
        });
        state.selectedAssigned = [];
    }
    // Buscar en una tabla
    function searchTable(tableId, searchText) {
        const value = searchText.toLowerCase();
        $(`${tableId} tbody tr`).each(function() {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(value) > -1);
        });
    }
    // Renumerar tabla
    function renumberTable(tableId) {
        $(`${tableId} tbody tr`).each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    // Actualizar contadores
    function updateCounters() {
        const availableCount = $(`${config.availableTable} tbody tr:visible`).length;
        const assignedCount = $(`${config.assignedTable} tbody tr:visible`).length;
        
        $(config.availableCount).text(availableCount);
        $(config.assignedCount).text(assignedCount);
    }
    // Actualizar input oculto
    function updateHiddenInput() {
        const permissionIds = [];
        $(`${config.assignedTable} tbody tr`).each(function() {
            permissionIds.push($(this).data('id'));
        });
        
        $(config.selectedPermissions).val(permissionIds.join(','));
    }
    // En el submit del formulario
    $('#assignPermissionsForm').submit(async function(e) {
        e.preventDefault(); // Agrega esto para prevenir el submit normal
        
        const userId = $('#userId').val();
        const selectedCount = $('#assignedPermissions tbody tr').not(':has(.text-muted)').length;
        
        if (selectedCount === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Debe asignar al menos un permiso directo al usuario',
            });
            return false;
        }

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
        
        try {
            const response = await axios.post(
                `${API_BASE_URL}/sys/users/storePermission/${userId}`, 
                $(this).serialize() // Mejor usar serialize() para forms
            );
            
            if(response.data.status){
                Swal.fire({
                    icon: 'success',
                    title: 'Operación exitosa',
                    text: response.data.message,
                }).then(() => {
                    window.location.href = response.data.route;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.data.message,
                });
            }
            
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: 'Intente nuevamente más tarde',
            });
        } finally {
            submitButton.prop('disabled', false).html(originalButtonText);
        }
    });
    
    // Mostrar notificación
    function showToast(type, title, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        Toast.fire({
            icon: type,
            title: title,
            text: message
        });
    }
    
    // Exponer métodos públicos
    return {
        init: init
    };
})();

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    PermissionManager.init();
});
</script>
@endsection