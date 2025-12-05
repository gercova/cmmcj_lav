$(document).ready(function() {
    console.log('Iniciando calendario...');

    let calendar;
    let currentAppointmentId = null;

    // Inicializar el calendario
    function initCalendar() {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: function(info, successCallback, failureCallback) {
                loadAppointments(info.startStr, info.endStr, successCallback, failureCallback);
            },
            eventClick: function(info) {
                showAppointmentDetails(info.event.id);
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            displayEventTime: true,
            eventDisplay: 'block'
        });

        calendar.render();
    }

    // Cargar citas desde el servidor
    function loadAppointments(start, end, successCallback, failureCallback) {
        console.log('Cargando citas...', { start, end });
        showLoading(true);
        const statusFilter = $('#filterStatus').val();
        axios.get(`${API_URL}/sys/appointments/calendar/data`, {
            params: {
                start: start,
                end: end,
                status: statusFilter
            }
        }).then(function(response) {
            console.log('Respuesta recibida:', response.data);

            if (response.data.error) {
                console.error('Error en respuesta:', response.data);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: `<pre>${JSON.stringify(response.data, null, 2)}</pre>`,
                    confirmButtonText: 'Aceptar'
                });
                failureCallback(response.data.error);
                return;
            }

            const events = response.data.map(appointment => {
                console.log('Procesando cita:', appointment);
                return {
                    id: appointment.id,
                    title: appointment.title,
                    start: appointment.start,
                    end: appointment.end,
                    backgroundColor: getColorByStatus(appointment.status_id),
                    borderColor: getColorByStatus(appointment.status_id),
                    extendedProps: {
                        patient: appointment.patient,
                        dni: appointment.dni,
                        status: appointment.status,
                        status_id: appointment.status_id
                    }
                };
            });

            console.log('Eventos formateados:', events);
            successCallback(events);

            if (events.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin citas',
                    text: 'No hay citas registradas en el rango de fechas seleccionado.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }).catch(function(error) {
            console.error('Error completo:', error);
            console.error('Respuesta del error:', error.response);

            let errorMessage = 'No se pudieron cargar las citas.';
            if (error.response && error.response.data) {
                errorMessage += '<br><br><pre>' + JSON.stringify(error.response.data, null, 2) + '</pre>';
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage,
                confirmButtonText: 'Aceptar'
            });

            failureCallback(error);
        }).finally(function() {
            showLoading(false);
        });
    }

    // Obtener color según estado
    function getColorByStatus(statusId) {
        const colors = {
            1: '#ffc107', // Pendiente - warning
            2: '#28a745', // Atendido - success
            3: '#17a2b8', // En Proceso - info
            4: '#dc3545'  // Cancelado - danger
        };
        return colors[statusId] || '#6c757d';
    }

    // Mostrar detalles de la cita
    function showAppointmentDetails(appointmentId) {
        currentAppointmentId = appointmentId;
        $('#appointmentModal').modal('show');
        $('#appointmentDetails').html(`
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-3x"></i>
                <p class="mt-2">Cargando información...</p>
            </div>
        `);

        axios.get(`${API_URL}/sys/appointments/${appointmentId}/details`)
            .then(function(response) {
                const data          = response.data;
                const statusBadge   = getStatusBadge(data.status_id);

                $('#appointmentDetails').html(`
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-user"></i> Paciente:</strong>
                        ${data.patient_name}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-id-card"></i> DNI:</strong>
                        ${data.dni}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-calendar-check"></i> Fecha:</strong>
                        ${data.appointment_date}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-clock"></i> Hora:</strong>
                        ${data.appointment_time}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-info-circle"></i> Estado:</strong>
                        ${statusBadge}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-user-md"></i> Doctor:</strong>
                        ${data.doctor_name || 'No asignado'}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-notes-medical"></i> Motivo:</strong>
                        ${data.reason || 'Sin especificar'}
                    </div>
                    <div class="appointment-detail-row">
                        <strong><i class="fas fa-notes-medical"></i> Observaciones:</strong>
                        ${data.observations || 'Sin especificar'}
                    </div>
                `);
                $('#btnEditAppointment').val(appointmentId);
                $('#btnDeleteAppointment').val(appointmentId);
            })
            .catch(function(error) {
                console.error('Error:', error);
                $('#appointmentDetails').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error al cargar los detalles de la cita.
                    </div>
                `);
            });
    }

    // Obtener badge de estado
    function getStatusBadge(statusId) {
        const badges = {
            1: '<span class="badge badge-warning">Pendiente</span>',
            2: '<span class="badge badge-success">Atendido</span>',
            3: '<span class="badge badge-info">Programado</span>',
            4: '<span class="badge badge-danger">Cancelado</span>'
        };
        return badges[statusId] || '<span class="badge badge-secondary">Desconocido</span>';
    }

    // Mostrar/ocultar loading
    function showLoading(show) {
        if (show) {
            $('#loading').show();
        } else {
            $('#loading').hide();
        }
    }

    // Event listeners
    $('#filterStatus').on('change', function() {
        calendar.refetchEvents();
    });

    $('#btnRefresh').on('click', function() {
        calendar.refetchEvents();
        Swal.fire({
            icon: 'success',
            title: 'Actualizado',
            text: 'El calendario ha sido actualizado correctamente.',
            timer: 1500,
            showConfirmButton: false
        });
    });

    // =====================================================
    // ABRIR MODAL PARA EDITAR CITA
    // =====================================================
    $(document).on('click', '#btnEditAppointment', async function(e) {
        e.preventDefault();
        $('#appointmentModal').modal('hide'); // Cerrar modal de detalles
        const appointmentId = $(this).attr('value'); // Obtener ID del data-attribute

        if(!appointmentId){
            alertNotify('error', 'No se encontró el ID de la cita.');
            return;
        }

        // Mostrar loading en el botón
        const btnOriginal = $(this).html();
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        try {
            // Obtener datos de la cita
            const response = await axios.get(`${API_URL}/sys/appointments/${appointmentId}`);
            if(response.status === 200 && response.data){
                const appointment = response.data; // Ajustar según tu AppointmentResource
                console.log(appointment);
                $('.seachPatient').addClass('d-none');
                // Limpiar formulario y errores
                // $('#formEditAppointment')[0].reset();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback, .error-message').remove();

                selectPatient(appointment.historia.id, appointment.historia.dni, appointment.historia.nombres);
                // Llenar los campos del formulario
                //$('[name="patient_id"]').val(appointment.historia.id).trigger('change'); // Si es select2
                $('[name="historia_id"]').val(appointment.historia.id).trigger('change');
                $('[name="doctor_id"]').val(appointment.user_id).trigger('change');

                $('[name="estado_cita_id"]').val(appointment.estado_cita_id).trigger('change');

                // Llenar fecha (convertir de Y-m-d a d/m/Y si es necesario)
                $('#fecha').val(appointment.fecha);
                $('#hora').val(appointment.hora);

                $('#motivo_consulta').val(appointment.motivo_consulta);
                $('#observaciones').val(appointment.observaciones);

                // Cambiar título y botón del modal
                $('#appointmentModalLabel').html('<i class="fas fa-edit"></i> Editar Cita');
                $('#btnSubmitAppointment').html('<i class="fas fa-save"></i> Actualizar').removeClass('btn-primary').addClass('btn-warning');

                // Mostrar modal
                $('#newAppointmentModal').find('input[name="appId"]').val(appointment.id);
                $('#newAppointmentModal').modal('show');


            } else {
                alertNotify('error', 'No se pudieron obtener los datos de la cita.');
            }

        } catch(error) {
            console.error('Error al cargar la cita:', error);
            alertNotify('error', 'Error al cargar los datos de la cita.');
        } finally {
            $(this).prop('disabled', false).html(btnOriginal);
        }
    });

    // =====================================================
    // ELIMINAR CITA
    // =====================================================
    $(document).on('click', '#btnDeleteAppointment', async function() {
        const appointmentId = $(this).attr('value');

        if(!appointmentId){
            alertNotify('error', 'No se encontró el ID de la cita.');
            return;
        }

        // Confirmación con SweetAlert2 (si lo tienes instalado)
        if(typeof Swal !== 'undefined'){
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await deleteAppointment(appointmentId);
                }
            });
        } else {
            // Confirmación nativa
            if(confirm('¿Estás seguro de eliminar esta cita?')){
                await deleteAppointment(appointmentId);
            }
        }
    });

    // Función para eliminar la cita
    async function deleteAppointment(appointmentId) {
        try {

            const response = await axios.delete(`${API_URL}/sys/appointments/destroy/${appointmentId}`);
            if(response.status === 200 && response.data.status === true){
                $('#appointmentModal').modal('hide')
                calendar.refetchEvents();
                alertNotify(response.data.type || 'success', response.data.message || 'Cita eliminada exitosamente');
            } else {
                alertNotify('error', response.data.message || 'No se pudo eliminar la cita.');
            }
        } catch(error) {
            console.error('Error al eliminar:', error);
            alertNotify('error', 'Error al eliminar la cita.');
        }
    }

    // =====================================================
    // LIMPIAR ERRORES AL ESCRIBIR
    // =====================================================
    $(document).on('input change', '.is-invalid', function(){
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('.invalid-feedback, .error-message').remove();
        $(this).closest('.input-group').removeClass('is-invalid');
    });


    // =====================================================
    // LIMPIAR MODAL AL CERRAR
    // =====================================================
    $('#appointmentModal').on('hidden.bs.modal', function () {
        $('#appointmentForm')[0].reset();
        $('#appointmentForm').find('input[name="_method"], input[name="id"]').remove();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback, .error-message').remove();
        $('#datepickerFecha').datetimepicker('clear');
        $('#datepickerHora').datetimepicker('clear');
    });

    // Inicializar
    initCalendar();
    // Cargar doctores al iniciar
    loadDoctors();
    // SweetAlert para confirmaciones
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 no está cargado. Algunas funcionalidades pueden no estar disponibles.');
    }

    // ==================== FUNCIONES PARA NUEVA CITA ====================

    // Abrir modal de nueva cita
    $('#btnNewAppointment').on('click', function() {
        resetNewAppointmentForm();
        $('#newAppointmentModal').modal('show');

        // Establecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        $('#fecha').attr('min', today);
        $('#fecha').val(today);

        // Establecer hora actual
        const now       = new Date();
        const hours     = String(now.getHours()).padStart(2, '0');
        const minutes   = String(now.getMinutes()).padStart(2, '0');
        $('#hora').val(`${hours}:${minutes}`);
    });

    // Buscar paciente
    $('#btnSearchPatient').on('click', function() {
        const searchTerm = $('#searchPatient').val().trim();

        if (searchTerm.length < 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor ingrese al menos 3 caracteres para buscar',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        searchPatient(searchTerm);
    });

    // Buscar con Enter
    $('#searchPatient').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btnSearchPatient').click();
        }
    });

    // Buscar paciente
    function searchPatient(searchTerm) {
        Swal.fire({
            title: 'Buscando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        axios.get(`${API_URL}/sys/ap/patients-search`, {
            params: { term: searchTerm }
        })
        .then(function(response) {
            Swal.close();

            if (response.data.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin resultados',
                    text: 'No se encontraron pacientes con ese criterio de búsqueda',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            showPatientSelector(response.data);
        }).catch(function(error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda. Intente nuevamente.',
                confirmButtonText: 'Aceptar'
            });
        });
    }

    // Mostrar selector de pacientes
    function showPatientSelector(patients) {
        let html = '<div class="list-group" style="max-height: 300px; overflow-y: auto;">';

        patients.forEach(patient => {
            html += `
                <a href="#" class="list-group-item list-group-item-action select-patient"
                    data-id="${patient.id}"
                    data-dni="${patient.dni}"
                    data-name="${patient.nombres}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1"><i class="fas fa-user"></i> ${patient.nombres}</h6>
                        <small><i class="fas fa-id-card"></i> ${patient.dni}</small>
                    </div>
                </a>
            `;
        });

        html += '</div>';

        Swal.fire({
            title: 'Seleccione un paciente',
            html: html,
            showConfirmButton: false,
            showCloseButton: true,
            width: '600px'
        });

        // Event listener para seleccionar paciente
        $('.select-patient').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const dni = $(this).data('dni');
            const name = $(this).data('name');
            selectPatient(id, dni, name);
            Swal.close();
        });
    }

    // Seleccionar paciente
    function selectPatient(id, dni, name) {
        $('#historia_id').val(id);
        $('#selectedPatientInfo').html(`<strong>${name}</strong> - DNI: ${dni}`);
        $('#patientInfo').slideDown();
    }

    // Cargar lista de doctores
    function loadDoctors() {
        // axios.get('{{ route("doctors.list") }}')
        axios.get(`${API_URL}/sys/ap/doctors-list`)
        .then(function(response) {
            const select = $('#doctor_id');
            select.find('option:not(:first)').remove();

            response.data.forEach(doctor => {
                select.append(new Option(doctor.name, doctor.id));
            });
        })
        .catch(function(error) {
            console.error('Error al cargar doctores:', error);
        });
    }

    // Guardar nueva cita
    $('#formNewAppointment').on('submit', function(e) {
        e.preventDefault();
        const patientId = $('#historia_id').val();
        if (!patientId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor seleccione un paciente',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        const formData = {
            historia_id     : patientId,
            fecha           : $('#fecha').val(),
            hora            : $('#hora').val(),
            estado_cita_id  : $('#estado_cita_id').val(),
            user_id         : $('#doctor_id').val() || null,
            motivo_consulta : $('#motivo_consulta').val(),
            observaciones   : $('#observaciones').val(),
            id              : $('#appId').val(),
        };

        saveAppointment(formData);
    });

    // Guardar cita
    function saveAppointment(data) {
        Swal.fire({
            title: 'Guardando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        axios.post(`${API_URL}/sys/appointments/store`, data)
        .then(function(response) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                $('#newAppointmentModal').modal('hide');
                calendar.refetchEvents();
            });
        })
        .catch(function(error) {
            console.error('Error:', error);
            let errorMessage = 'No se pudo guardar la cita. Intente nuevamente.';

            if (error.response && error.response.data && error.response.data.errors) {
                const errors = error.response.data.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage,
                confirmButtonText: 'Aceptar'
            });
        });
    }

    // Reset del formulario
    function resetNewAppointmentForm() {
        $('.seachPatient').removeClass('d-none');
        $('#formNewAppointment')[0].reset();
        $('#patient_id').val('');
        $('#searchPatient').val('');
        $('#patientInfo').hide();
        $('#selectedPatientInfo').html('');
        $('#status_id').val('1');
        $('#appId').val('');
    }
});
