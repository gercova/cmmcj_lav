let slimSelect;
if (slimSelect) slimSelect.destroy();
slimSelect = new SlimSelect({
    select: '#moduleSelect',
    placeholder: 'Seleccione un módulo',
    allowDeselect: true
});

cargarDatos('todos');

$('#moduleSelect').on('change', function(){
    const moduleId = $(this).val();
    if(moduleId){
        console.log(moduleId);
        cargarDatos(moduleId);
    }
});

function cargarDatos(moduleId) {
    // Mostrar loading
    //$('#loading').removeClass('d-none');
    $('#tabla-container').addClass('d-none');
    // Hacer petición AJAX
    axios.post('/sys/users/searchByModule', {
        moduleId: moduleId
    }).then(function(response) {
        const datos = response.data.result;
        const total = response.data.totalCount;
        // Actualizar tabla
        actualizarTabla(datos);
        
        // Actualizar contador
        $('#availableCount').text(`Mostrando ${total} resultados`);
    }).catch(function(error) {
        console.error('Error al cargar datos:', error);
        alert('¡Error al cargar los datos, papu!');
    }).finally(function() {
        // Ocultar loading
        // $('#loading').addClass('d-none');
        $('#availablePermissions').removeClass('d-none');
    });
}

function actualizarTabla(datos) {
    const tablaBody = $('#availablePermissions');
    tablaBody.empty();
    
    if (datos.length === 0) {
        tablaBody.append(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No se encontraron resultados, papu 😢
                </td>
            </tr>
        `);
        return;
    }
    
    datos.forEach(function(dato, index) {
        const fila = `
            <tr>
                <td>${index + 1}</td>
                <td>${dato.name}</td>
                <td>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxPrimary${dato.id}" >
                        <label for="checkboxPrimary${dato.id}"></label>
                    </div>
                </td>
            </tr>
        `;
        tablaBody.append(fila);
    });
}

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
                `${API_URL}/sys/users/store/${userId}`, 
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