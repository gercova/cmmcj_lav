// Inicializar Select2
$('#moduleSelect').select2({
    theme: 'bootstrap4',
    placeholder: 'Seleccione un módulo',
    allowClear: true
});

let selectedPermissions = $('#selectedPermissions').val() ? $('#selectedPermissions').val().split(',').map(Number) : [];
let selectedAvailableRows = []; // IDs de filas seleccionadas en disponibles
let selectedAssignedRows = [];  // IDs de filas seleccionadas en asignados

// Cargar datos iniciales
cargarDatos('todos');

// Evento cambio de módulo
$('#moduleSelect').on('change', function(){
    const moduleId = $(this).val();
    selectedAvailableRows = []; // Limpiar selección al cambiar módulo
    cargarDatos(moduleId);
});

// Selección de filas en permisos disponibles
$(document).on('click', '#availablePermissions tr[data-id]', function(e) {
    // Evitar que se active cuando se hace clic en el botón de agregar
    if ($(e.target).closest('.add-permission').length === 0) {
        const rowId = $(this).data('id');
        const isSelected = $(this).hasClass('selected');
        
        if (e.ctrlKey || e.metaKey) {
            // Selección múltiple con Ctrl
            if (isSelected) {
                $(this).removeClass('selected');
                selectedAvailableRows = selectedAvailableRows.filter(id => id !== rowId);
            } else {
                $(this).addClass('selected');
                selectedAvailableRows.push(rowId);
            }
        } else {
            // Selección simple
            $('#availablePermissions tr.selected').removeClass('selected');
            selectedAvailableRows = [rowId];
            $(this).addClass('selected');
        }
        
        actualizarInfoSeleccion();
    }
});

// Selección de filas en permisos asignados
$(document).on('click', '#assignedPermissions tr[data-id]', function(e) {
    // Evitar que se active cuando se hace clic en el botón de quitar
    if ($(e.target).closest('.remove-permission').length === 0) {
        const rowId = $(this).data('id');
        const isSelected = $(this).hasClass('selected');
        
        if (e.ctrlKey || e.metaKey) {
            // Selección múltiple con Ctrl
            if (isSelected) {
                $(this).removeClass('selected');
                selectedAssignedRows = selectedAssignedRows.filter(id => id !== rowId);
            } else {
                $(this).addClass('selected');
                selectedAssignedRows.push(rowId);
            }
        } else {
            // Selección simple
            $('#assignedPermissions tr.selected').removeClass('selected');
            selectedAssignedRows = [rowId];
            $(this).addClass('selected');
        }
        
        actualizarInfoSeleccion();
    }
});

// Agregar permiso individual
$(document).on('click', '.add-permission', function(e) {
    e.stopPropagation(); // Evitar que se active la selección de la fila
    
    const permissionId = $(this).data('id');
    const permissionName = $(this).closest('tr').find('td:eq(1)').text();
    
    if (!selectedPermissions.includes(permissionId)) {
        agregarPermiso(permissionId, permissionName);
        $(this).closest('tr').remove();
        selectedAvailableRows = selectedAvailableRows.filter(id => id !== permissionId);
        actualizarContadores();
        reorganizarNumeracion();
        actualizarInfoSeleccion();
    }
});

// Quitar permiso individual
$(document).on('click', '.remove-permission', function(e) {
    e.stopPropagation(); // Evitar que se active la selección de la fila
    
    const permissionId = $(this).data('id');
    const permissionName = $(this).closest('tr').find('td:eq(1)').text();
    
    quitarPermiso(permissionId, permissionName);
    $(this).closest('tr').remove();
    selectedAssignedRows = selectedAssignedRows.filter(id => id !== permissionId);
    actualizarContadores();
    reorganizarNumeracion();
    actualizarInfoSeleccion();
});

// Agregar permisos seleccionados
$('#addSelectedPermissions').on('click', function() {
    if (selectedAvailableRows.length === 0) {
        showAlert('warning', 'Selecciona al menos un permiso para agregar');
        return;
    }

    selectedAvailableRows.forEach(permissionId => {
        const row = $(`#availablePermissions tr[data-id="${permissionId}"]`);
        if (row.length > 0) {
            const permissionName = row.find('td:eq(1)').text();
            
            if (!selectedPermissions.includes(permissionId)) {
                agregarPermiso(permissionId, permissionName);
                row.remove();
            }
        }
    });

    selectedAvailableRows = [];
    actualizarContadores();
    reorganizarNumeracion();
    actualizarInfoSeleccion();
    showAlert('success', 'Permisos agregados correctamente!');
});

// Quitar permisos seleccionados
$('#removeSelectedPermissions').on('click', function() {
    if (selectedAssignedRows.length === 0) {
        showAlert('warning', 'Selecciona al menos un permiso para quitar');
        return;
    }

    selectedAssignedRows.forEach(permissionId => {
        const row = $(`#assignedPermissions tr[data-id="${permissionId}"]`);
        if (row.length > 0) {
            const permissionName = row.find('td:eq(1)').text();
            quitarPermiso(permissionId, permissionName);
            row.remove();
        }
    });

    selectedAssignedRows = [];
    actualizarContadores();
    reorganizarNumeracion();
    actualizarInfoSeleccion();
    showAlert('success', 'Permisos quitados correctamente!');
});

// Agregar todos los permisos
$('#addAllPermissions').on('click', function() {
    const allRows = $('#availablePermissions tr[data-id]');
    if (allRows.length === 0) {
        showAlert('warning', 'No hay permisos disponibles para agregar');
        return;
    }

    allRows.each(function() {
        const permissionId = $(this).data('id');
        const permissionName = $(this).find('td:eq(1)').text();
        
        if (!selectedPermissions.includes(permissionId)) {
            agregarPermiso(permissionId, permissionName);
        }
    });

    $('#availablePermissions').html(`<tr>
        <td colspan="3" class="text-center text-muted">
            No hay permisos disponibles
        </td>
    </tr>`);
    
    selectedAvailableRows = [];
    actualizarContadores();
    actualizarInfoSeleccion();
    showAlert('success', 'Todos los permisos han sido agregados!');
});

// Quitar todos los permisos
$('#removeAllPermissions').on('click', function() {
    const allRows = $('#assignedPermissions tr[data-id]');
    if (allRows.length === 0) {
        showAlert('warning', 'No hay permisos asignados para quitar');
        return;
    }

    showConfirmModal(
        '¿Estás seguro de que quieres quitar TODOS los permisos asignados directamente?',
        function() {
            selectedPermissions = [];
            selectedAssignedRows = [];
            $('#assignedPermissions').html(`<tr><td colspan="3" class="text-center">No hay permisos asignados directamente</td></tr>`);
            actualizarHiddenField();
            actualizarContadores();
            actualizarInfoSeleccion();
            showAlert('success', 'Todos los permisos han sido quitados!');
        }
    );
});

// Envío del formulario
$('#assignPermissionsForm').on('submit', function(e) {
    e.preventDefault();
    guardarPermisos();
});

// Limpiar selección al hacer clic fuera de las tablas
$(document).on('click', function(e) {
    if (!$(e.target).closest('.table-container').length) {
        $('#availablePermissions tr.selected').removeClass('selected');
        $('#assignedPermissions tr.selected').removeClass('selected');
        selectedAvailableRows = [];
        selectedAssignedRows = [];
        actualizarInfoSeleccion();
    }
});

function cargarDatos(moduleId) {
    $('#availablePermissions').html(`<tr>
        <td colspan="3" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <br>Cargando permisos...
        </td>
    </tr>`);

    axios.post('/sys/users/searchByModule', {
        moduleId: moduleId,
        user_id: $('#userId').val()
    })
    .then(function(response) {
        const datos = response.data.permissions || response.data.result;
        actualizarTablaDisponibles(datos);
        actualizarContadores();
        actualizarInfoSeleccion();
    })
    .catch(function(error) {
        console.error('Error al cargar datos:', error);
        showAlert('error', '¡Error al cargar los datos!');
    });
}

function actualizarTablaDisponibles(datos) {
    const tablaBody = $('#availablePermissions');
    tablaBody.empty();

    if (!datos || datos.length === 0) {
        tablaBody.append(`<tr>
            <td colspan="3" class="text-center text-muted">
                No se encontraron permisos disponibles
            </td>
        </tr>`);
        return;
    }

    let hasAvailablePermissions = false;
    datos.forEach(function(dato, index) {
        // Solo mostrar permisos que no estén ya asignados
        if (!selectedPermissions.includes(dato.id)) {
            hasAvailablePermissions = true;
            const fila = `<tr data-id="${dato.id}">
                <td>${index + 1}</td>
                <td>${dato.name}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success add-permission btn-xs" data-id="${dato.id}" title="Agregar permiso individual">
                        <i class="fas fa-plus"></i>
                    </button>
                </td>
            </tr>`;
            tablaBody.append(fila);
        }
    });

    if (!hasAvailablePermissions) {
        tablaBody.append(`<tr>
            <td colspan="3" class="text-center text-muted">
                No hay permisos disponibles para este módulo
            </td>
        </tr>`);
    }
}

function agregarPermiso(permissionId, permissionName) {
    if (!selectedPermissions.includes(permissionId)) {
        selectedPermissions.push(permissionId);
        
        const newRow = `<tr data-id="${permissionId}">
            <td>${$('#assignedPermissions tr[data-id]').length + 1}</td>
            <td>${permissionName}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-permission btn-xs" data-id="${permissionId}" title="Quitar permiso individual">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;
        
        // Si la tabla está vacía, reemplazar el mensaje
        if ($('#assignedPermissions tr[data-id]').length === 0) {
            $('#assignedPermissions').html(newRow);
        } else {
            $('#assignedPermissions').append(newRow);
        }
        
        actualizarHiddenField();
    }
}

function quitarPermiso(permissionId, permissionName) {
    selectedPermissions = selectedPermissions.filter(id => id !== permissionId);
    actualizarHiddenField();
    
    // Si no quedan permisos, mostrar mensaje
    if (selectedPermissions.length === 0) {
        $('#assignedPermissions').html(`<tr>
            <td colspan="3" class="text-center">No hay permisos asignados directamente</td>
        </tr>`);
    }
}

function reorganizarNumeracion() {
    // Reorganizar numeración en permisos disponibles
    $('#availablePermissions tr[data-id]').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
    // Reorganizar numeración en permisos asignados
    $('#assignedPermissions tr[data-id]').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
}

function actualizarHiddenField() {
    $('#selectedPermissions').val(selectedPermissions.join(','));
}

function actualizarContadores() {
    const availableCount = $('#availablePermissions tr[data-id]').length;
    const assignedCount = selectedPermissions.length;
    $('#availableCount').text(availableCount);
    $('#assignedCount').text(assignedCount);
    // Mostrar/ocultar botones según sea necesario
    $('#addAllPermissions').toggle(availableCount > 0);
    $('#removeAllPermissions').toggle(assignedCount > 0);
}

function actualizarInfoSeleccion() {
    // Puedes agregar aquí información sobre la selección actual si lo deseas
    // Por ejemplo, mostrar cuántos elementos están seleccionados
    console.log('Disponibles seleccionados:', selectedAvailableRows.length);
    console.log('Asignados seleccionados:', selectedAssignedRows.length);
}

function guardarPermisos() {
    const userId = $('#userId').val();
    
    axios.post('/sys/users/update-permissions', {
        user_id: userId,
        permissions: selectedPermissions,
    })
    .then(function(response) {
        showAlert('success', response.data.message || '¡Permisos actualizados correctamente!');
        // Recargar la página después de 2 segundos
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    })
    .catch(function(error) {
        console.error('Error:', error);
        showAlert('error', '¡Error al guardar los permisos!');
    });
}

function showAlert(type, message) {
    // Usar Toastr o SweetAlert2 si están disponibles, sino usar alert nativo
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert(message);
    }
}

function showConfirmModal(message, callback) {
    $('#confirmModalBody').text(message);
    $('#confirmModal').modal('show');
    
    $('#confirmAction').off('click').on('click', function() {
        $('#confirmModal').modal('hide');
        callback();
    });
}