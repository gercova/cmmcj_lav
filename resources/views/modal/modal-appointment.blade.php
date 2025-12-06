<!-- Modal citas -->
<div class="modal fade" id="appointmenDefaultModal" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="appointmentForm" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paciente">Paciente:</label>
                        <input type="text" class="form-control date" name="paciente" id="paciente" value="" readonly>
                        <input type="hidden"  name="historia_id" id="historia_id" value="">
                        <input type="hidden"  name="cita_id" id="cita_id" value="">
                    </div>
                    <div class="form-group">
                        <label for="estado_cita_id">Selecciona un estado:</label>
                        <select id="estado_cita_id" name="estado_cita_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <div class="input-group date" id="datepickerFecha" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="fecha" id="fecha" data-target="#datepickerFecha"  placeholder="Seleccione fecha" readonly>
                            <div class="input-group-append" data-target="#datepickerFecha" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paciente">Hora:</label>
                        <div class="input-group date" id="datepickerHora" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="hora" id="hora" data-target="#datepickerHora" placeholder="Seleccione hora" readonly>
                            <div class="input-group-append" data-target="#datepickerHora" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
