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
                                <i class="bi bi-calendar3"></i> Agenda de Citas
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
                                        @foreach ($ec as $e)
                                            <option value="{{ $e->id }}">{{ $e->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label><br>
                                    <button id="btnRefresh" class="btn btn-info">
                                        <i class="bi bi-arrow-clockwise"></i> Actualizar
                                    </button>
                                </div>
                            </div>

                            <!-- Calendario -->
                            <div id="calendar"></div>
                        </div>
                        <div class="overlay" id="loading" style="display: none;">
                            <i class="fas fa-2x bi bi-arrow-clockwise"></i>
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
                            <span class="badge badge-info">Programado</span>
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
                        <button type="button" class="btn btn-danger" id="btnDeleteAppointment" value="">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnEditAppointment" class="btn btn-warning" value="">
                            <i class="bi bi-pencil-square"></i> Editar Cita
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
                            <i class="bi bi-plus-circle"></i> Nueva Cita Médica
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="formNewAppointment">
                        <div class="modal-body">
                            <div class="row">
                                <!-- Buscar Paciente -->
                                <div class="col-md-12 mb-3 seachPatient">
                                    <div class="form-group">
                                        <label for="searchPatient">
                                            <i class="bi bi-search"></i> Buscar Paciente (DNI o Nombre) <span class="text-danger">*</span>
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
                                        <h6><i class="bi bi-search"></i> Paciente Seleccionado:</h6>
                                        <p class="mb-0" id="selectedPatientInfo"></p>
                                    </div>
                                    <input type="hidden" id="historia_id" name="historia_id">
                                </div>

                                <!-- Fecha de la cita -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">
                                            <i class="bi bi-calendar-week"></i> Fecha de Cita <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                                    </div>
                                </div>
                                <!-- Hora de la cita -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hora">
                                            <i class="bi bi-clock"></i> Hora de Cita <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="form-control" id="hora" name="hora" required>
                                    </div>
                                </div>

                                <!-- Estado de la cita -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estado_cita_id">
                                            <i class="fas fa-info-circle"></i> Estado <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" id="estado_cita_id" name="estado_cita_id" required>
                                            <option value="">Seleccione un estado</option>
                                            @foreach($ec as $e)
                                                <option value="{{ $e->id }}">{{ $e->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Doctor (opcional) -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">
                                            <i class="fas fa-user-md"></i> Especialista
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
                                        <label for="motivo_consulta">
                                            <i class="bi bi-journal-medical"></i> Motivo de Consulta
                                        </label>
                                        <textarea class="form-control" id="motivo_consulta" name="motivo_consulta" rows="2" placeholder="Describa el motivo de la consulta"></textarea>
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observaciones">
                                            <i class="bi bi-journal-text"></i> Observaciones
                                        </label>
                                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Observaciones adicionales (opcional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="appId" name="appId" value="">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" id="btnSaveAppointment">Guardar Cita</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar/calendar.main.min.css') }}">
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

<script src="{{ asset('plugins/fullcalendar/calendar.main.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalendar/es.js') }}"></script>
<script src="{{ asset('js/appointments.js') }}"></script>
<script src="{{ asset('js/beds.js') }}"></script>
@endsection
