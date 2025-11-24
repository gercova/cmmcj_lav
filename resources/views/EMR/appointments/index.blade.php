@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Citas') <!-- Título dinámico -->
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Citas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Citas</li>
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
                            <h3 class="card-title">
                                <i class="fas fa-calendar"></i> Agenda de Citas
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" id="btnNewAppointment">
                                    <i class="bi bi-plus-circle"></i> Nueva Cita
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Estado de Cita:</label>
                                    <select id="filterStatus" class="form-control">
                                        <option value="">Todos los estados</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Atendido</option>
                                        <option value="3">En Proceso</option>
                                        <option value="4">Cancelado</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label><br>
                                    <button id="btnRefresh" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i> Actualizar
                                    </button>
                                </div>
                            </div>

                            <!-- Calendario -->
                            <div id="calendar"></div>
                        </div>
                        <div class="overlay" id="loading" style="display: none;">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leyenda de colores -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Leyenda:</h5>
                            <span class="badge badge-warning">Pendiente</span>
                            <span class="badge badge-success">Atendido</span>
                            <span class="badge badge-info">En Proceso</span>
                            <span class="badge badge-danger">Cancelado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para detalles de la cita -->
        <div class="modal fade" id="appointmentModal" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle"></i> Detalles de la Cita
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="appointmentDetails">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin fa-3x"></i>
                                <p class="mt-2">Cargando información...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnEditAppointment" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Cita
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear nueva cita -->
        <div class="modal fade" id="newAppointmentModal" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle"></i> Nueva Cita Médica
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="formNewAppointment">
                        <div class="modal-body">
                            <div class="row">
                                <!-- Buscar Paciente -->
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="searchPatient">
                                            <i class="fas fa-search"></i> Buscar Paciente (DNI o Nombre) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchPatient" placeholder="Ingrese DNI o nombre del paciente">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="btnSearchPatient">
                                                    <i class="fas fa-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Busque al paciente por DNI o nombre</small>
                                    </div>
                                </div>

                                <!-- Información del paciente seleccionado -->
                                <div class="col-md-12 mb-3" id="patientInfo" style="display: none;">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-user"></i> Paciente Seleccionado:</h6>
                                        <p class="mb-0" id="selectedPatientInfo"></p>
                                    </div>
                                    <input type="hidden" id="patient_id" name="patient_id">
                                </div>

                                <!-- Fecha de la cita -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="appointment_date">
                                            <i class="fas fa-calendar"></i> Fecha de Cita <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                                    </div>
                                </div>

                                <!-- Hora de la cita -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="appointment_time">
                                            <i class="fas fa-clock"></i> Hora de Cita <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                                    </div>
                                </div>

                                <!-- Estado de la cita -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_id">
                                            <i class="fas fa-info-circle"></i> Estado <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" id="status_id" name="status_id" required>
                                            <option value="">Seleccione un estado</option>
                                            <option value="1" selected>Pendiente</option>
                                            <option value="2">Atendido</option>
                                            <option value="3">En Proceso</option>
                                            <option value="4">Cancelado</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Doctor (opcional) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">
                                            <i class="fas fa-user-md"></i> Doctor
                                        </label>
                                        <select class="form-control" id="doctor_id" name="doctor_id">
                                            <option value="">Sin asignar</option>
                                            <!-- Los doctores se cargarán dinámicamente -->
                                        </select>
                                    </div>
                                </div>

                                <!-- Motivo de consulta -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reason">
                                            <i class="fas fa-notes-medical"></i> Motivo de Consulta
                                        </label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Describa el motivo de la consulta"></textarea>
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observations">
                                            <i class="fas fa-comment"></i> Observaciones
                                        </label>
                                        <textarea class="form-control" id="observations" name="observations" rows="2" placeholder="Observaciones adicionales (opcional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success" id="btnSaveAppointment">
                                <i class="fas fa-save"></i> Guardar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }
        .fc-event {
            cursor: pointer;
            border-radius: 3px;
            padding: 2px 5px;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
        .appointment-detail-row {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .appointment-detail-row strong {
            display: inline-block;
            min-width: 150px;
        }
    </style>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
                
                axios.get('{{ route("appointments.calendar.data") }}', {
                    params: {
                        start: start,
                        end: end,
                        status: statusFilter
                    }
                })
                .then(function(response) {
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
                })
                .catch(function(error) {
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
                })
                .finally(function() {
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

                axios.get(`{{ url('appointments') }}/${appointmentId}/details`)
                    .then(function(response) {
                        const data = response.data;
                        const statusBadge = getStatusBadge(data.status_id);
                        
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
                        `);
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
                    3: '<span class="badge badge-info">En Proceso</span>',
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

            $('#btnEditAppointment').on('click', function() {
                if (currentAppointmentId) {
                    window.location.href = `{{ url('appointments') }}/${currentAppointmentId}/edit`;
                }
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
                $('#appointment_date').attr('min', today);
                $('#appointment_date').val(today);
                
                // Establecer hora actual
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                $('#appointment_time').val(`${hours}:${minutes}`);
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

                axios.get('{{ route("patients.search") }}', {
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
                })
                .catch(function(error) {
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
                $('#patient_id').val(id);
                $('#selectedPatientInfo').html(`
                    <strong>${name}</strong> - DNI: ${dni}
                `);
                $('#patientInfo').slideDown();
            }

            // Cargar lista de doctores
            function loadDoctors() {
                axios.get('{{ route("doctors.list") }}')
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
                
                const patientId = $('#patient_id').val();
                
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
                    historia_id: patientId,
                    fecha_cita: $('#appointment_date').val(),
                    hora_cita: $('#appointment_time').val(),
                    estado_cita_id: $('#status_id').val(),
                    doctor_id: $('#doctor_id').val() || null,
                    motivo: $('#reason').val(),
                    observaciones: $('#observations').val()
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

                axios.post('{{ route("appointments.store") }}', data)
                .then(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'La cita ha sido registrada correctamente',
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
                $('#formNewAppointment')[0].reset();
                $('#patient_id').val('');
                $('#searchPatient').val('');
                $('#patientInfo').hide();
                $('#selectedPatientInfo').html('');
                $('#status_id').val('1');
            }
        });
    </script>
<script src="{{ asset('js/beds.js') }}"></script>
@endsection