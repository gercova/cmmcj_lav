@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Crear Hospitalización') <!-- Título dinámico -->
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Nueva hospitalización</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('emr.hospitalizations.home') }}">Hospitalizaciones</a></li>
                            <li class="breadcrumb-item active">Nueva hospitalización</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header"></div>
                    <form id="hospitalizationForm" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Atendido por:</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="{{ auth()->user()->name }}" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Especialidad:</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="{{ auth()->user()->roles[0]->name }}" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Fecha:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm date" name="created_at" value="{{ now()->format('Y-m-d') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>DNI :: NOMBRES </label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $history->dni.' :: '.strtoupper($history->nombres) }}" readonly>
                                        <input type="hidden" name="historia_id" id="historia_id" value="{{ $history->id }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="cama_id">Cama: </label>
                                        <select name="cama_id" id="cama_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($beds as $b)
                                                <option value="{{ $b->id }}">{{ $b->description.' - '.$b->floor }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fc">Frecuencia cardiaca:</label>
                                        <input class="form-control form-control-sm" id="fc" name="fc" value="{{ old('fc') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="t">Temperatura C / F:</label>
                                        <input class="form-control form-control-sm" id="t" name="t" value="{{ old('t') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="so2">Saturación de oxígeno:</label>
                                        <input class="form-control form-control-sm" id="so2" name="so2" value="{{ old('so2') }}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fecha_admision">Fecha admisión: </label>
                                        <input type="text" class="form-control form-control-sm date" name="fecha_admision" id="fecha_admision" value="{{ old('fecha_admision') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fecha_admision">Tipo admisión: </label>
                                        <select name="tipo_admision_id" id="tipo_admision_id">
                                            <option value="">-- Seleccione --</option>
                                            @foreach ($at as $a)
                                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="via_ingreso_id">Vía Ingreso: </label>
                                        <select name="via_ingreso_id" id="via_ingreso_id">
                                            <option value="">-- Seleccione --</option>
                                            @foreach ($ve as $v)
                                                <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="motivo_hospitalizacion">Motivo hospitalización:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="motivo_hospitalizacion" id="motivo_hospitalizacion" rows="1">{{ old('motivo_hospitalizacion') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="alergias">Alergias:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="alergias" id="alergias" rows="1">{{ old('alergias') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="medicamentos_habituales">Medicamentos habituales:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="medicamentos_habituales" id="medicamentos_habituales" rows="1" autocomplete="off">{{old('medicamentos_habituales') }}</textarea>
                                            <div id="sugerencias-farmacos" class="sugerencias-container"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="condicion_ingreso_id">Condición ingreso: </label>
                                        <div class="col-sm-4">
                                            <select name="condicion_ingreso_id" id="condicion_ingreso_id">
                                                <option value="">-- Seleccione --</option>
                                                @foreach ($ec as $e)
                                                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="antecedentes_importantes">Ant. importantes:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="antecedentes_importantes" id="antecedentes_importantes" rows="1">{{ old('antecedentes_importantes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <hr>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="servicio_id">Servicio: </label>
                                                <select name="servicio_id" id="servicio_id">
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach ($se as $s)
                                                        <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="tipo_cuidado_id">Tipo cuidado: </label>
                                                <select name="tipo_cuidado_id" id="tipo_cuidado_id">
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach ($ct as $c)
                                                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="fecha_egreso">Fecha egreso: </label>
                                                <input type="text" class="form-control form-control-sm date" name="fecha_egreso" id="fecha_egreso" value="{{ old('fecha_egreso') }}">
                                                
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="tipo_egreso_id">Tipo egreso: </label>
                                                <select name="tipo_egreso_id" id="tipo_egreso_id">
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach ($dt as $d)
                                                        <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="diagnostico_egreso">Diagnóstico egreso:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="diagnostico_egreso" id="diagnostico_egreso" rows="1">{{ old('diagnostico_egreso') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="condicion_egreso_id">Condición egreso: </label>
                                        <div class="col-sm-4">
                                            <select name="condicion_egreso_id" id="condicion_egreso_id">
                                                <option value="">-- Seleccione --</option>
                                                @foreach ($dc as $d)
                                                    <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="resumen_evolucion">Resumen evolución:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="resumen_evolucion" id="resumen_evolucion" rows="1">{{ old('resumen_evolucion') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="causa_muerte">Causa muerte:</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm" name="causa_muerte" id="causa_muerte" rows="1">{{ old('causa_muerte') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="nro_autorizacion_seguro">Nro. autorización seguro:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control form-control-sm" name="nro_autorizacion_seguro" id="nro_autorizacion_seguro" value="{{ old('nro_autorizacion_seguro') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label" for="aseguradora">Aseguradora:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" name="aseguradora" id="aseguradora" value="{{ strtoupper($seguro->descripcion) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="estado_hospitalizacion_id">Estado hospitalización: </label>
                                        <div class="col-sm-4">
                                            <select name="estado_hospitalizacion_id" id="estado_hospitalizacion_id">
                                                <option value="">-- Seleccione --</option>
                                                @foreach ($hs as $h)
                                                    <option value="{{ $h->id }}">{{ $h->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script>
        let sSelectBed, sSelectAdmissionType, sSelectViaEntry, sSelectEntryCondition, sSelectService, sSelectCarefulType, sSelectDischargeType, sSelectDischargeCondition, sSelectHospitalizationStatus;
        if (sSelectBed) sSelectBed.destroy();
        sSelectBed = new SlimSelect({
            select: '#cama_id',
            placeholder: 'Seleccione una cama',
            allowDeselect: true
        });

        if(sSelectAdmissionType) sSelectAdmissionType.destroy();
        sSelectAdmissionType = new SlimSelect({
            select: '#tipo_admision_id',
            placeholder: 'Seleccione un tipo de admisión',
            allowDeselect: true
        });

        if(sSelectViaEntry) sSelectViaEntry.destroy();
        sSelectViaEntry = new SlimSelect({
            select: '#via_ingreso_id',
            placeholder: 'Seleccione una vía de ingreso',
            allowDeselect: true
        });

        if(sSelectEntryCondition) sSelectEntryCondition.destroy();
        sSelectEntryCondition = new SlimSelect({
            select: '#condicion_ingreso_id',
            placeholder: 'Seleccione una condición de ingreso', 
            allowDeselect: true
        });
        
        if (sSelectService) sSelectService.destroy();
        sSelectService = new SlimSelect({
            select: '#servicio_id',
            placeholder: 'Seleccione un servicio',
            allowDeselect: true
        });

        if(sSelectCarefulType) sSelectCarefulType.destroy();
        sSelectCarefulType = new SlimSelect({
            select: '#tipo_cuidado_id',
            placeholder: 'Seleccione un tipo de cuidado',
            allowDeselect: true
        });

        if(sSelectDischargeType) sSelectDischargeType.destroy();
        sSelectDischargeType = new SlimSelect({
            select: '#tipo_egreso_id',
            placeholder: 'Seleccione un tipo de egreso',
            allowDeselect: true
        });

        if(sSelectDischargeCondition) sSelectDischargeCondition.destroy();
        sSelectDischargeCondition = new SlimSelect({
            select: '#condicion_egreso_id',
            placeholder: 'Seleccione un condición',
            allowDeselect: true
        });

        if(sSelectHospitalizationStatus) sSelectHospitalizationStatus.destroy();
        sSelectHospitalizationStatus = new SlimSelect({
            select: '#estado_hospitalizacion_id',
            placeholder: 'Seleccione un estado',
            allowDeselect: true
        });

    </script>
    <script src="{{ asset('js/hospitalizations.js') }}"></script>
@endsection